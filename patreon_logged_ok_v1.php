<?php
session_start();

require_once __DIR__.'/vendor/autoload.php';
 
use Patreon\API;
use Patreon\OAuth;


$client_id = '';      // Replace with your data
$client_secret = '';  // Replace with your data

$redirect_uri = "https://www.sharetextures.com/patreon_logged_ok_v1.php"; 
//echo 'redir';



if ( $_GET['code'] != '' ) {
	
//	echo 'gewt';

	$oauth_client = new OAuth($client_id, $client_secret);	
		
	$tokens = $oauth_client->get_tokens($_GET['code'], $redirect_uri);
	$access_token = $tokens['access_token'];
	$refresh_token = $tokens['refresh_token'];
	
	
}

/*
var_dump($tokens);
echo '<br/>-------------------------------<br/>';

var_dump($_GET);
echo '<br/>-------------------------------<br/>';
var_dump($access_token);
echo '<br/>-------------------------------<br/>';
var_dump($refresh_token);
echo '<br/>-------------------------------<br/>';
*/


$api_client = new API($access_token);

//var_dump($tokens);

$api_client->api_return_format = 'object';

$patron_response = $api_client->fetch_user();

$res = json_decode((base64_decode( $_GET['state'])));

//echo '<pre>';
//print_r($patron_response['included'][0]);
//echo '</pre>';

//var_dump($_REQUEST); 


 

foreach ($patron_response['included'] as $sps) {

	# code...

	if($sps['relationships']['creator']['data']['id']=='13286870' && $sps['type']=='pledge') {
	//	echo 'ODENEN:'.$sps['attributes']['amount_cents'].' @ '.$sps['attributes']['created_at'];


		$_SESSION['para2'] = $sps['attributes']['amount_cents'];
		header('Location:'.'https:'.$res->comeback);
	} 

}
