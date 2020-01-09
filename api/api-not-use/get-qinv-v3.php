<?php
set_time_limit(10000);	
ini_set("memory_limit","100M"); 
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);
//require_once("inc.functions.makes_models.inc.php");
require_once("class.qinvApi.php");
/* http://home.quirkcars.com/api/v2 */
$arr = $_POST;
$aQinv = new qinvApi();
$aQinv->apiDataArr = $arr;
$aQinv->handleRequest();
$response = $aQinv->retXml; 
print $response;
?>
									
