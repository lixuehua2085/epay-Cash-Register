<?php
require_once("./config/database.php");
require_once("./config/Communication_Key.php");
if( $_SERVER['REQUEST_METHOD'] === 'GET'){
    $trade_name = isset($_GET['trade_name']) ? $_GET['trade_name'] : '';
    $trade_amount = isset($_GET['trade_amount']) ? $_GET['trade_amount'] : '';
    $return_url = isset($_GET['return_url']) ? $_GET['return_url'] : '';
    $return_type = isset($_GET['return_type']) ? $_GET['return_type'] : '';
    $trade_no = isset($_GET['trade_no']) ? $_GET['trade_no'] : '';
    $sign = isset($_GET['sign']) ? $_GET['sign'] : '';
}else{
    $trade_name = isset($_POST['trade_name']) ? $_POST['trade_name'] : '';
    $trade_amount = isset($_POST['trade_amount']) ? $_POST['trade_amount'] : '';
    $return_url = isset($_POST['return_url']) ? $_POST['return_url'] : '';
    $return_type = isset($_POST['return_type']) ? $_POST['return_type'] : '';
    $trade_no = isset($_POST['trade_no']) ? $_POST['trade_no'] : '';
    $sign = isset($_POST['sign']) ? $_POST['sign'] : '';
}
if (empty($trade_no)) {
    die("401");
}
if (empty($trade_amount)) {
    die("402");
}
if (empty($return_url)) {
    die("403");
}
if (empty($sign)) {
    die("404");
}
if (empty($return_type)) {
    $return_type = "sync";
}
if (empty($trade_name)) {
    $trade_name = "收银台支付";
}
if ($sign !== md5($trade_no.$ckey)) {
    die("405");
}
$pdo = new PDO("mysql:host=$serverip;dbname=$dbname", $username, $password);
$trade_no = htmlspecialchars($trade_no, ENT_QUOTES, 'UTF-8');
$trade_name = htmlspecialchars($trade_name, ENT_QUOTES, 'UTF-8');
$trade_amount = filter_var($trade_amount);
$return_url = filter_var($return_url, FILTER_SANITIZE_URL);
$return_type = htmlspecialchars($return_type, ENT_QUOTES, 'UTF-8');
$sql = "INSERT INTO trade (no, name, amount, return_url, return_type) VALUES (:trade_no, :trade_name, :trade_amount, :return_url, :return_type)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':trade_no', $trade_no);
$stmt->bindParam(':trade_name', $trade_name);
$stmt->bindParam(':trade_amount', $trade_amount);
$stmt->bindParam(':return_url', $return_url);
$stmt->bindParam(':return_type', $return_type);
if ($stmt->execute()) {
} else {
    die("500");
}
echo "200";
?>
