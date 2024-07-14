<?php
// 檢查是否提供了兩個參數
if ($argc != 3) {
    echo "Usage: php " . $argv[0] . " <start> <end>\n";
    exit(1);
}

// 獲取參數
$start = intval($argv[1]);
$end = intval($argv[2]);

// 驗證參數
if ($start >= $end || $start < 1 || $end > 100) {
    echo "Invalid range. Please ensure start < end, start >= 1, and end <= 100.\n";
    exit(1);
}

// 生成乘法表
for ($i = $start; $i <= $end; $i++) {
    for ($j = $start; $j <= $end; $j++) {
        echo sprintf("%3d x %3d = %3d   ", $i, $j, $i * $j);
    }
    echo "\n";
}
?>