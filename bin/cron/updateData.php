<?php

require_once __DIR__ . "/../../core.php";
require_once __DIR__ . "/../../lib/updateData.php";

echo "Loading mob list...\n";
$mobs = getMobs();

echo "Filtering invalid entries...\n";
$mobs = array_filter($mobs, fn ($mob) =>
    !str_starts_with($mob, "id=") &&
    !str_contains($mob, "Old ") &&
    $mob != "NPC" &&
    $mob != "Agent" &&
    !str_ends_with($mob, "Ghost") &&
    $mob != "Giant" &&
    $mob != "Killer Bunny"
);

echo "Fetching image URLs...\n";
$mobs = array_map(fn ($mob) => [
    "name" => $mob,
    "image" => getImage($mob)
], $mobs);

echo "Removing non-existing images...\n";
$mobs = array_filter($mobs, fn ($mob) => $mob["image"] != "");

echo "Removing duplicates...\n";
$mobs = array_reduce($mobs, function ($mobs, $mob) {
    $urls = array_map(fn ($mob) => $mob["image"], $mobs);
    if (!in_array($mob["image"], $urls)) {
        $mobs[] = $mob;
    }
    return $mobs;
}, []);

echo "Downloading images...\n";
foreach ($mobs as &$mob) {
    echo "   ... " . $mob["name"] . "\n";
    $filename = downloadImage($mob["image"], $mob["name"], "mobs");
    $mob["filename"] = $filename;
}

echo "Adding to database...\n";
foreach ($mobs as &$mob) {
    echo "   ... " . $mob["name"] . "\n";
    addOrUpdateMob($mob["name"], $mob["filename"]);
}

echo "Fetching spawn egg image URLs...\n";
$spawnEggs = getSpawnEggsImages();

echo "Downloading images...\n";
foreach ($spawnEggs as &$spawnEgg) {
    echo "   ... " . $spawnEgg["name"] . "\n";
    $filename = downloadImage($spawnEgg["image"], $spawnEgg["name"], "eggs");
    $spawnEgg["filename"] = $filename;
}

echo "Making chimeras...\n";
foreach ($spawnEggs as $leftEgg) {
    foreach ($spawnEggs as $rightEgg) {
        echo "   ... " . $leftEgg["name"] . " " . $rightEgg["name"] . "\n";
        echo "       -> " . makeChimera($leftEgg, $rightEgg, "eggs") . "\n";
    }
}

echo "Done.\n";