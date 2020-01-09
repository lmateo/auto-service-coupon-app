<?php
require "../lib/ncs_api_transform.php";

$cookiejar = tempnam(sys_get_temp_dir(), 'cookiejar-');

function call($method, $url, $csrf = false, $data = false) {
	global $cookiejar;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	curl_setopt($ch, CURLOPT_URL, $url);
	$headers = array();
	if ($data) {
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Content-Length: ' . strlen($data);
	}
	if ($csrf) {
		$headers[] = 'X-XSRF-TOKEN: ' . $csrf;
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiejar);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiejar);

	return curl_exec($ch);
}

$method_POST = 'POST';
$method_GET = 'GET';
$webspecialsfields ='columns=active,deal_type,webspecials_id,apr_text,body_style_code,brand_logo_url,buy_price,disclaimer_text,custom_marquee_text,alt_link_url,lease_extras,lease_price,lease_term,make_id,make_name,model_id,model_name,mpg,msrp,new_used_ws,ordering,save_up_to_amount,single_lease_price,single_lease_term,single_lease_miles,stock_number,trim_level,vehicle_image,vin_number,year,zero_down_lease_price,zero_down_lease_term,finance_for_price,finance_for_term,finance_for_payment_calcu_url,lease_special_active,zero_down_lease_special_active,';
$wspricningfields ='ws_pricing.name,ws_pricing.price,ws_pricing.ordering,ws_pricing.active,';
$storesfields ='stores.letter,stores.storename,stores.leadsemail,stores.address1,stores.address2,stores.city,stores.state,stores.zip,stores.salesphone';
$sortOrdering ='&order=ordering,asc&order=active,desc&order=year,desc&order=id,desc';
$url_api_post = 'http://ncs.quirkspecials.com/quirk-inventory-site-dev/api/api.php/';
$url_api_get = 'http://ncs.quirkspecials.com/quirk-inventory-site-dev/api/web_specials?'.$webspecialsfields.$wspricningfields.$storesfields.'&include=ws_pricing,stores&filter[]=store_letter,eq,g&filter[]=new_used_ws,eq,New&filter[]=active,eq,1&filter[]=ws_pricing.active,eq,1'.$sortOrdering;
$user_credentials = 'username=admin&password=1yP8s82YUO';

// using api-auth:
$csrf = json_decode(call($method_POST,$url_api_post, false, $user_credentials));
$response = call($method_GET,$url_api_get, $csrf);

unlink($cookiejar);

$jsonObject = json_decode($response,true);

$jsonObject = ncs_api_transform($jsonObject);
$output = json_encode($jsonObject,JSON_PRETTY_PRINT);


?>
<html>
<head>
</head>
<body>
<pre><?php echo $output ?></pre>

</body>
</html>