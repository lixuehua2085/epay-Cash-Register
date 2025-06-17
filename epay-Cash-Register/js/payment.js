window.onload=function(){
    const hiddenDivs = document.querySelectorAll('div#hidden');
    hiddenDivs.forEach(div => {
    div.remove();
    });
}
function TurnToPay(payment){
    var span = document.getElementById('trade_no');
    var trade_no = span.textContent;
    window.location.replace("/pay/index.php?trade_no="+trade_no+"&payment="+payment);
}
