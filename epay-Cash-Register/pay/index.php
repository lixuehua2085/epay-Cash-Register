<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>正在为您跳转到支付页面，请稍候...</title>
	<style type="text/css">
body{margin:0;padding:0}
p{position:absolute;left:50%;top:50%;height:35px;margin:-35px 0 0 -160px;padding:20px;font:bold 16px/30px "宋体",Arial;text-indent:40px;border:1px solid #c5d0dc}
#waiting{font-family:Arial}
	</style>
</head>
<body>
<?php
require_once("lib/epay.config.php");
require_once("lib/EpayCore.class.php");
require_once("../config/database.php");
$notify_url = "http://".$_SERVER['HTTP_HOST']."/pay/notify_url.php";
$return_url = "http://".$_SERVER['HTTP_HOST']."/pay/return_url.php";
$trade_no = $_GET['trade_no'];
$type = $_GET['payment'];
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
    die("无此订单");
}
$name =$result['name'];
$amount =$result['amount'];
$statu =$result['statu'];
if (strtolower($statu) !== 'fail') {
    echo "此订单已完成，请勿重复提交";
    exit();
}
$parameter = array(
	"pid" => $epay_config['pid'],
	"type" => $type,
	"notify_url" => $notify_url,
	"return_url" => $return_url,
	"out_trade_no" => $trade_no,
	"name" => $name,
	"money"	=> $amount,
);
$epay = new EpayCore($epay_config);
$html_text = $epay->pagePay($parameter);
echo $html_text;
?>
<p>正在为您跳转到支付页面，请稍候...</p>
</body>
</html>