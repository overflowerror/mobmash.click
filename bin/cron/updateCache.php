<?php

require_once __DIR__ . "/../../core.php";
require_once __DIR__ . "/../../lib/updateCache.php";

echo "Updating rating cache...\n";
updateCache();

echo "Done.\n";
