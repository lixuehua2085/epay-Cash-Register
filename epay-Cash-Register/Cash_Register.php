<?php
require_once("./config/database.php");
if( $_SERVER['REQUEST_METHOD'] === 'GET'){
    $trade_name = isset($_GET['trade_name']) ? $_GET['trade_name'] : '';
    $trade_amount = isset($_GET['trade_amount']) ? $_GET['trade_amount'] : '';
    $return_url = isset($_GET['return_url']) ? $_GET['return_url'] : '';
    $return_type = isset($_GET['return_type']) ? $_GET['return_type'] : '';
}else{
    $trade_name = isset($_POST['trade_name']) ? $_POST['trade_name'] : '';
    $trade_amount = isset($_POST['trade_amount']) ? $_POST['trade_amount'] : '';
    $return_url = isset($_POST['return_url']) ? $_POST['return_url'] : '';
    $return_type = isset($_POST['return_type']) ? $_POST['return_type'] : '';
}
$trade_no = date("YmdHis").rand(1000, 9999);
$config = file_get_contents("./config/config.json");
$config = json_decode($config, true);
$page = file_get_contents("./template/payment.html");
$page = str_replace('${trade_name}', $trade_name, $page);
$page = str_replace('${trade_amount}', $trade_amount, $page);
$page = str_replace('${trade_no}', $trade_no, $page);
$page = str_replace('${alipay_payment_status}', $config['alipay_payment_status'], $page);
$page = str_replace('${wxpay_payment_status}', $config['wxpay_payment_status'], $page);
$page = str_replace('${qqpay_payment_status}', $config['qqpay_payment_status'], $page);
$page = str_replace('${ysfpay_payment_status}', $config['ysfpay_payment_status'], $page);
$page = str_replace('${web_name}', $config['web_name'], $page);
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
    echo "数据库错误！";
}
echo $page
?>
