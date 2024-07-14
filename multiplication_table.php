<?php
for ($i = 1; $i <= 9; $i++) {
    for ($j = 1; $j <= 9; $j++) {
        echo sprintf("%2d x %2d = %2d   ", $i, $j, $i * $j);
    }
    echo "\n";
}
?>