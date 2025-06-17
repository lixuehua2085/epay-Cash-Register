<?php
require_once("./config/database.php");
require_once("./config/database.php");
if( $_SERVER['REQUEST_METHOD'] === 'GET'){
    $trade_no = isset($_GET['trade_no']) ? $_GET['trade_no'] : '';
} else {
    $trade_no = isset($_POST['trade_no']) ? $_POST['trade_no'] : '';
}
$config = json_decode(file_get_contents("./config/config.json"), true);
$page = file_get_contents("./template/payment.html");
$errorpage = file_get_contents("./template/error.html");
$pdo = new PDO("mysql:host=$serverip;dbname=$dbname", $username, $password);
if (empty($trade_no)) {
    die("trade_no参数不能为空");
}
$sql = "SELECT name, amount, statu FROM trade WHERE no = :trade_no";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':trade_no',$trade_no);
$stmt->execute();
$result =$stmt->fetch(PDO::FETCH_ASSOC);
if (!$result) {
    $errorpage = str_replace('${reason}', "无此订单！", $errorpage);
    echo $errorpage;
    exit();
}
$trade_name =$result['name'];
$trade_amount =$result['amount'];
$statu =$result['statu'];
if (strtolower($statu) !== 'fail') {
    $errorpage = str_replace('${reason}', "此订单已完成支付！", $errorpage);
    echo $errorpage;
    exit();
}
$page = str_replace('${trade_name}', $trade_name, $page);
$page = str_replace('${trade_amount}', $trade_amount, $page);
$page = str_replace('${trade_no}', $trade_no, $page);
$page = str_replace('${alipay_payment_status}', $config['alipay_payment_status'], $page);
$page = str_replace('${wxpay_payment_status}', $config['wxpay_payment_status'], $page);
$page = str_replace('${qqpay_payment_status}', $config['qqpay_payment_status'], $page);
$page = str_replace('${ysfpay_payment_status}', $config['ysfpay_payment_status'], $page);
$page = str_replace('${web_name}', $config['web_name'], $page);
echo $page
?>
