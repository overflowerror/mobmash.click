<h1>
    Which do you like better?
</h1>
<div class="selection">
    <?php
        $mob = $left ?? [];
        $side = "left";
        include __DIR__ . "/mob.php";
    ?>
    <div class="separator">
        <div>
            OR
        </div>
    </div>
    <?php
        $mob = $right ?? [];
        $side = "right";
        include __DIR__ . "/mob.php";
    ?>
</div>
