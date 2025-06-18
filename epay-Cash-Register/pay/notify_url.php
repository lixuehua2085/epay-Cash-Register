<?php
require_once "lib/epay.config.php";
require_once "lib/EpayCore.class.php";
$epay = new EpayCore($epay_config);
$verify_result = $epay->verifyNotify();
if ($verify_result) {
    $out_trade_no = $_GET["out_trade_no"];
    $trade_no = $_GET["trade_no"];
    $trade_status = $_GET["trade_status"];
    $type = $_GET["type"];
    $money = $_GET["money"];
    $param = $_GET["param"];
    if ($_GET["trade_status"] == "TRADE_SUCCESS") {
    }
    echo "success";
} else {
    echo "fail";
}
?>
