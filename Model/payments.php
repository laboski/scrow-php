<?php 
use Scrowsdk\V1\Src\Response;
use Scrowsdk\V1\Src\Request;
use Scrowsdk\V1\Src\Payment;

include_once'V1/App/Config.php';
include('V1/Src/Response.php');
include('V1/Src/Request.php');
include('V1/Src/Payment.php');

$Response = new Response();
$Request  = new Request($Response);
$Payment  = new Payment($Response, 1);

if ($Request->method() == 'post') {
	$body = array();
	foreach ($_POST as $key => $value) {
		filter_input(INPUT_POST, $value, FILTER_SANITIZE_SPECIAL_CHARS);
		$body[$key] = $value;
	}
	if (isset($body['submit'])) {
		unset($body['submit']);
	}
	echo $Payment->startTransaction($body);
}
?>
