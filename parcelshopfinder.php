<?php
// Defining Constants 
define('delisID', 'yourdelisid');
define('password', 'yourpassword');
define('messageLanguage', 'en_US');
define('baseUrl', 'https://public-ws-stage.dpd.com/services/');

define('DEBUG', false);

if(DEBUG){
	// For debugging purposes only:
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

if($_SERVER['HTTP_REFERER'] == ""){ // You can change this to the path of your script and check !=
	die();
}

session_name("DPDLocator");
session_start();

$serverIP = $_SERVER['SERVER_ADDR'];
$clientIP = $_SERVER['REMOTE_ADDR'];
$sugar = "b7amcxHT7ECBxyhkgV5uMj7byWqsgU";
$referer = $_SERVER['HTTP_REFERER'];

$sessionID = md5(md5($serverIP.'.'.$clientIP).$sugar.md5($referer));

if($_SESSION['identifier'] != $sessionID){
	die();
} else {
	function DPD_SoapCall($strUrl, $strFunction, $arrBodyParam, $arrHeaderParam = false){
		// Create new SoapClient, trace is used for (future) error reporting
		$client = new SoapClient($strUrl, array('trace' => 1));
		
		if($arrHeaderParam){
			// Create (and set) new header with namespace, and parameters
			$sHeader = new SoapHeader('http://dpd.com/common/service/types/Authentication/2.0', 'authentication', $arrHeaderParam);
			$client->__setSoapHeaders(array($sHeader));
		}
		
		$result;
		
		try {
			$result = $client->__soapCall($strFunction, array($arrBodyParam));
		} catch (SoapFault $fault) {
			throw new Exception("An error occured during Soap call to server. ".$fault->getMessage().": ".$client->__getLastRequest(), 800);
		}
		
		if(DEBUG){
			echo "Last Request:\n";
			echo $client->__getLastRequest();
			echo "\n";
			echo $client->__getLastResponse();
		}
		
		unset($client);
		
		return $result;;
	}

	function DPD_authenticate($delisID, $password){
		$url = baseUrl."LoginService/V2_0/?wsdl";
		$function = "getAuth";
		$body = array(
			"delisId" => $delisID,
			"password" => $password,
			"messageLanguage" => messageLanguage
		);
		
		$result;
		try{
			$result = DPD_SoapCall($url, $function, $body);
		} catch (Exception $e){
			throw new Exception("Something went wrong with ws authentication: "."/r/n".$e->getMessage());
		}
		
		return $result;
	}

	function DPD_findParcelShop($long, $lat, $filter = false, $limit = 15){
		$authenticationResult;
		try {
			$authenticationResult = DPD_authenticate(delisID, password);
		} catch (Exception $e) {
			throw new Exception("Something went wrong authenticating:"."/r/n".$e->getMessage());
		}
		$url = baseUrl."ParcelShopFinderService/V3_0/?wsdl";
		$function = "findParcelShopsByGeoData";
		$header = array(
			"delisId" => $authenticationResult->return->delisId,
			"authToken" => $authenticationResult->return->authToken,
			"messageLanguage" => messageLanguage
		);
		$body = array(
			"longitude" => $long,
			"latitude" => $lat,
			"limit" => $limit
		);
		
		if($filter){
			switch($filter){
				case "pick-up":
					$body["consigneePickupAllowed"] = "true";
					break;
				case "return":
					$body["returnAllowed"] = "true";
					break;
				default:
			}
		}
		
		$result;
		try {
			$result = DPD_SoapCall($url, $function, $body, $header);
		} catch (Exception $e) {
			throw new Exception("Something went wrong looking for the parcelshops:"."/r/n".$e->getMessage());
		}
		return $result;
	}

	if(isset($_GET['findParcelShops'])){
		$result;
		try{
			$result = DPD_findParcelShop($_GET['long'], $_GET['lat'], $_GET['findParcelShops']);
		} catch (Exception $e){
			echo "FALSE";
			die();
		}
		//var_dump($result);
		echo json_encode($result);
	}
}
?>