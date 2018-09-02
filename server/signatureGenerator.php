<?php

	function generateToken( $size ) {
		$token = "";
		$charactersSource = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		for($i = 0; $i < $size; $i++) {
			$token .= $charactersSource[mt_rand(0, strlen($charactersSource) - 1)];
		}
		return $token;
	}

	$time = new DateTime();
	$ApiKey = "ryE6LgHsdp910mO633h6R6DRUl";
	$merchant_id = "678810";
	$reference_code = $time->getTimestamp() . generateToken(6);
	$value = '1190000';
	$currency = 'COP';
	$signature = "$ApiKey~$merchant_id~$reference_code~$value~$currency";
	$encoded_signature = md5($signature);
	$response = new stdClass();
	$response->reference_code = $reference_code;
	$response->signature = $encoded_signature;
	$responseJSON = json_encode($response);
	echo  $responseJSON;
?>
