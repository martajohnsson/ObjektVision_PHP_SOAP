<?php 
/**
 * This script will communicate with ObjektVision's SOAP api
 * It's just a proof of concept so you will get the idea how 
 * to do it on your own.
 *
 * SOAPClient paramters used are only for testing purposes.
 * It might be a good idea to cache your requests. More information
 * can be found here: http://php.net/manual/en/class.soapclient.php
 */


/**
 * Disable the WSDL cache feautre (default: 1)
 * Set the number of seconds that cache files will be used (default: 86400)
 * Default timeout (in seconds) for socket based streams (default: 60)
 */
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 900);
ini_set('default_socket_timeout', 15);

/**
 * Login details for ObjektVision
 */
$vendorkey = '';
$brokerid = '';
$password = '';

/**
 * Prepare a initial login to the SOAP api
 * @param string VendorKey
 * @param int BrokerID
 * @param string password
 */
$login_details = array(
	'VendorKey'=> $vendorkey,
	'BrokerID'=> $brokerid,
	'password'=> $password
);

/**
 * URL to the test API, remove ".test" from the url to 
 * use the live version of the api.
 * http://import.test.objektvision.se/soap/server.asmx?wsdl
 */
$wsdl = 'http://import.objektvision.se/soap/server.asmx?wsdl';

/**
 * SOAP Client options 
 * @param uri => 
 * @param style =>
 * @param use => 
 * @param soap_version => SOAP_1_1, SOAP_1_2
 * @param cache_wsdl => WSDL_CACHE_NONE, WSDL_CACHE_DISK, WSDL_CACHE_MEMORY or WSDL_CACHE_BOTH
 * @param connection_timeout =>
 * @param trace => true / false
 * @param encoding => UTF-8 / iso-8859-1
 * @param exceptions => true / false
 */
$options = array(	
	'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',
	'style'=>SOAP_RPC,
	'use'=>SOAP_ENCODED,
	'soap_version'=>SOAP_1_1,
	'cache_wsdl'=>WSDL_CACHE_NONE,
	'connection_timeout'=>15,
	'trace'=>true,
	'encoding'=>'UTF-8',
	'exceptions'=>true
);

/**
 * - Create a new SoapClient
 * - Login to the server
 * - GetAdvertList
 */
try {
	$client = new SoapClient($wsdl, $options);
	$client->Login($login_details);
	$GetAdvertList = $client->GetAdvertList();
}
catch(Exception $e) {
	die($e->getMessage());
}

/**
 * The foreach below will print detailed data on all your items on ObjektVision.
 * Add die(); after the print_r to only print 1 item.
 */
echo '<pre>';
$advert = $GetAdvertList->GetAdvertListResult;
foreach ($advert->Advert as $a)
{
	print_r($client->GetEstateByServerId(array('ServerID' => $a->ServerID, 'CustomerID' => $brokerid, 'Password' => $password)));
	die();
}
echo '</pre>';

?>