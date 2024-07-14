<?php
header("Access-Control-Allow-Origin: *"); // 允許特定的域名
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// // 設置返回的內容類型
header('Content-Type: application/json');

//本地端 資料庫連線設定
$db_host = "localhost";
$db_user = "root";
$db_pass = "password";
$db_select = "um_practice_project";
try {
    
    // 建立連線
    $conn = new PDO("mysql:host=$db_host;dbname=$db_select", $db_user, $db_pass);
    
    // 設定錯誤模式為異常
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit();
}
?>