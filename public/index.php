<?php
require __DIR__ . '/../vendor/autoload.php';

use \pillr\library\http\Response as HttpResponse;
use \pillr\library\http\Uri as Uri;

// TIP: Use the $_SERVER Superglobal to get all the data you need from the Client's HTTP Request.

// TIP: HTTP headers are printed natively in PHP by invoking header().
// Ex. header('Content-Type', 'text/html');


if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
//get the complete request uri
$uri .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

date_default_timezone_set('America/Montreal');
$date = new DateTime ();
//set message body, not sure if it is the way it should be
$messagebody =json_encode(array("@id"=>$uri,
		"to" => "Pillr",
		"subject" => "Hello Pillr",
		"message" => "Here is my submission.",
		"from" => "Zheng Hua Zhu",
		"timeSent" => $date->getTimestamp ()));

$httpResponse=new HttpResponse(
		explode("/", $_SERVER['SERVER_PROTOCOL'])[1],
		'200',
		'OK',
		array('Date' => date ( 'D, d M Y H:i:s T' ),
				'Server' => $_SERVER["SERVER_NAME"],
				'Last-Modified' => date ( "D, d M Y H:i:s T", getlastmod () ),
				'Content-Length' => strlen ( $messagebody ),
				'Content-Type' => 'application/json',),
		json_decode($messagebody)
		);
$json=json_encode(array(
		$_SERVER["SERVER_PROTOCOL"]." ".$httpResponse->getStatusCode()." ".$httpResponse->getReasonPhrase(),
		$httpResponse->getHeaders(),
		$httpResponse->getBody(),
));

echo $json;
