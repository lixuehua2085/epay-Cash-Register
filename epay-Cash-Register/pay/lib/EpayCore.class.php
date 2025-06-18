<?php
class EpayCore
{
	private $pid;
	private $key;
	private $submit_url;
	private $mapi_url;
	private $api_url;
	private $sign_type = 'MD5';

	function __construct($config){
		$this->pid = $config['pid'];
		$this->key = $config['key'];
		$this->submit_url = $config['apiurl'].'submit.php';
		$this->mapi_url = $config['apiurl'].'mapi.php';
		$this->api_url = $config['apiurl'].'api.php';
	}

	// 发起支付（页面跳转）
	public function pagePay($param_tmp, $button='正在跳转'){
		$param = $this->buildRequestParam($param_tmp);

		$html = '<form id="dopay" action="'.$this->submit_url.'" method="post">';
		foreach ($param as $k=>$v) {
			$html.= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		$html .= '<div style="text-align: center;"><svg t="1750196117007" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5357" width="200" height="200"><path d="M508 294.8c-21.5 0-39-17.5-39-39V105.5c0-21.5 17.5-39 39-39s39 17.5 39 39v150.3c0 21.5-17.5 39-39 39zM380.4 330.2c-13.5 0-26.6-7-33.8-19.5l-75.1-130.1c-10.8-18.7-4.4-42.5 14.3-53.3 18.7-10.8 42.5-4.4 53.3 14.3l75.1 130.1c10.8 18.7 4.4 42.5-14.3 53.3-6.2 3.5-12.9 5.2-19.5 5.2zM287.5 424.7c-6.6 0-13.3-1.7-19.5-5.2l-130.1-75.1c-18.7-10.8-25-34.6-14.3-53.3 10.8-18.7 34.6-25 53.3-14.3L307 351.9c18.7 10.8 25 34.6 14.3 53.3-7.2 12.5-20.3 19.5-33.8 19.5zM254.5 552.9H104.2c-21.5 0-39-17.5-39-39s17.5-39 39-39h150.3c21.5 0 39 17.5 39 39s-17.5 39-39 39zM159.8 755.6c-13.5 0-26.6-7-33.8-19.5-10.8-18.7-4.4-42.5 14.3-53.3l130.1-75.1c18.7-10.8 42.5-4.4 53.3 14.3 10.8 18.7 4.4 42.5-14.3 53.3l-130.1 75.1c-6.2 3.5-12.9 5.2-19.5 5.2zM309.2 903.4c-6.6 0-13.3-1.7-19.5-5.2-18.7-10.8-25-34.6-14.3-53.3l75.1-130.1c10.8-18.7 34.6-25 53.3-14.3 18.7 10.8 25 34.6 14.3 53.3L343 883.9c-7.2 12.5-20.3 19.5-33.8 19.5zM512.6 956.6c-21.5 0-39-17.5-39-39V767.4c0-21.5 17.5-39 39-39s39 17.5 39 39v150.3c0 21.5-17.5 38.9-39 38.9zM715.3 901.1c-13.5 0-26.6-7-33.8-19.5l-75.1-130.1c-10.8-18.7-4.4-42.5 14.3-53.3 18.7-10.8 42.5-4.4 53.3 14.3l75.1 130.1c10.8 18.7 4.4 42.5-14.3 53.3-6.1 3.5-12.9 5.2-19.5 5.2zM863 751.6c-6.6 0-13.3-1.7-19.5-5.2l-130.1-75.1c-18.7-10.8-25-34.6-14.3-53.3 10.8-18.7 34.6-25 53.3-14.3l130.1 75.1c18.7 10.8 25 34.6 14.3 53.3-7.2 12.5-20.3 19.5-33.8 19.5zM916.3 548.3H766.1c-21.5 0-39-17.5-39-39s17.5-39 39-39h150.3c21.5 0 39 17.5 39 39s-17.5 39-39.1 39zM730.7 420.7c-13.5 0-26.6-7-33.8-19.5-10.8-18.7-4.4-42.5 14.3-53.3l130.1-75.1c18.7-10.8 42.5-4.4 53.3 14.3 10.8 18.7 4.4 42.5-14.3 53.3l-130.1 75.1c-6.2 3.5-12.9 5.2-19.5 5.2zM636.1 327.9c-6.6 0-13.3-1.7-19.5-5.2-18.7-10.8-25-34.6-14.3-53.3l75.1-130.1c10.8-18.7 34.6-25 53.3-14.3 18.7 10.8 25 34.6 14.3 53.3l-75.1 130.1c-7.2 12.5-20.3 19.5-33.8 19.5z" fill="" p-id="5358"></path></svg></div></form><script>document.getElementById("dopay").submit();</script>';

		return $html;
	}

	// 发起支付（获取链接）
	public function getPayLink($param_tmp){
		$param = $this->buildRequestParam($param_tmp);
		$url = $this->submit_url.'?'.http_build_query($param);
		return $url;
	}

	// 发起支付（API接口）
	public function apiPay($param_tmp){
		$param = $this->buildRequestParam($param_tmp);
		$response = $this->getHttpResponse($this->mapi_url, http_build_query($param));
		$arr = json_decode($response, true);
		return $arr;
	}

	// 异步回调验证
	public function verifyNotify(){
		if(empty($_GET)) return false;

		$sign = $this->getSign($_GET);

		if($sign === $_GET['sign']){
			$signResult = true;
		}else{
			$signResult = false;
		}

		return $signResult;
	}

	// 同步回调验证
	public function verifyReturn(){
		if(empty($_GET)) return false;

		$sign = $this->getSign($_GET);

		if($sign === $_GET['sign']){
			$signResult = true;
		}else{
			$signResult = false;
		}

		return $signResult;
	}

	// 查询订单支付状态
	public function orderStatus($trade_no){
		$result = $this->queryOrder($trade_no);
		if($result['status']==1){
			return true;
		}else{
			return false;
		}
	}

	// 查询订单
	public function queryOrder($trade_no){
		$url = $this->api_url.'?act=order&pid=' . $this->pid . '&key=' . $this->key . '&trade_no=' . $trade_no;
		$response = $this->getHttpResponse($url);
		$arr = json_decode($response, true);
		return $arr;
	}

	// 订单退款
	public function refund($trade_no, $money){
		$url = $this->api_url.'?act=refund';
		$post = 'pid=' . $this->pid . '&key=' . $this->key . '&trade_no=' . $trade_no . '&money=' . $money;
		$response = $this->getHttpResponse($url, $post);
		$arr = json_decode($response, true);
		return $arr;
	}

	private function buildRequestParam($param){
		$mysign = $this->getSign($param);
		$param['sign'] = $mysign;
		$param['sign_type'] = $this->sign_type;
		return $param;
	}

	// 计算签名
	private function getSign($param){
		ksort($param);
		reset($param);
		$signstr = '';
	
		foreach($param as $k => $v){
			if($k != "sign" && $k != "sign_type" && $v!=''){
				$signstr .= $k.'='.$v.'&';
			}
		}
		$signstr = substr($signstr,0,-1);
		$signstr .= $this->key;
		$sign = md5($signstr);
		return $sign;
	}

	// 请求外部资源
	private function getHttpResponse($url, $post = false, $timeout = 10){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$httpheader[] = "Accept: */*";
		$httpheader[] = "Accept-Language: zh-CN,zh;q=0.8";
		$httpheader[] = "Connection: close";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if($post){
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
}
