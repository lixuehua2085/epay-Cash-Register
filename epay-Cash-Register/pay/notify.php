<?php
function notify($trade_no){
    require_once("../config/database.php");
    require_once("../config/Communication_Key.php");
    $pdo = new PDO("mysql:host=$serverip;dbname=$dbname", $username, $password);
    $sql = "SELECT return_type, return_url, amount FROM trade WHERE no = :trade_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':trade_no',$trade_no);
    $stmt->execute();
    $result =$stmt->fetch(PDO::FETCH_ASSOC);
    $sign = md5($trade_no.$result['return_url'].$result['amount'].$ckey);
    if($result['return_type'] == "async") {
        $url = $result['return_url']."?no=".$trade_no."&amount=".$result['amount']."&sign=".$sign;
        file_get_contents($url);
        header("Location: ".$result['return_url']);
    } else {
        $url = $result['return_url']."?no=".$trade_no."&amount=".$result['amount']."&sign=".$sign;
        header("Location: $url");
        exit();
    }
}
?>