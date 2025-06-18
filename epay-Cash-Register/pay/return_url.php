<?php
require_once("lib/epay.config.php");
require_once("lib/EpayCore.class.php");
$epay = new EpayCore($epay_config);
$verify_result = $epay->verifyReturn();
$errorpage = file_get_contents("../template/error.html");
if($verify_result) {
	$out_trade_no = $_GET['out_trade_no'];
	$trade_no = $_GET['trade_no'];
	$trade_status = $_GET['trade_status'];
	$type = $_GET['type'];
	if($_GET['trade_status'] == 'TRADE_SUCCESS') {
	    require_once("./notify.php");
	    notify($out_trade_no);
	}
	else {
		$errorpage = str_replace('${reason}', "此订单未支付成功！", $errorpage);
        echo $errorpage;
	}
}
else {
	$errorpage = str_replace('${reason}', "校验失败！", $errorpage);
    echo $errorpage;
}
?>