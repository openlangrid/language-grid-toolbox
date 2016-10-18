<?php

$shopAnswerId = @$_GET['shopAnswerId'] ? $_GET['shopAnswerId'] : @$_POST['shopAnswerId'];
$contentUpdateAreaId = @$_GET['contentUpdateAreaId'] ? $_GET['contentUpdateAreaId'] : @$_POST['contentUpdateAreaId'];

$startLocation = null;
$endLocation   = null;

$requesttParamsArray = split("&", @$_POST['google_map_params']);
$googleParams = array();

foreach ($requesttParamsArray as $index => $param) {
	$keyAndValue = split("=", $param);
	$googleParams[$keyAndValue[0]] = $keyAndValue[1];	
}

// split to latitude, longitude
list($latitude, $longitude) = split(",", $googleParams['ll']);

list($startAddrLatitude, $startAddrLongitude) = split(",", $googleParams['saddr']);
if (!is_numeric($startAddrLatitude)) {
	$startAddrLatitude  = null;
	$startAddrLongitude = null;
	$startLocation = $googleParams['saddr'];// start location name
}

list($endAddrLatitude, $endAddrLongitude) = split(",", $googleParams['daddr']);
if (!is_numeric($endAddrLatitude)) {
	$endAddrLatitude  = null;
	$endAddrLongitude = null;
	$endLocation = $googleParams['daddr'];// end location name
}

$shopAnswerContentGoogleMap 
	= ShopAnswerContentGoogleMap::createFromParams(
									array(
										'shop_answer_id'  => $shopAnswerId,
										'content_title'   => $_POST['content_title'],
										'permission'      => '0',// TODO
										'content_type'    => 'google_map',
										
										'file_id'         => 0,
										'image_file_name' => null,
										'image_mimetype'  => null,
										'image_data'      => null,
										'image_width'     => null,
										'image_height'    => null,
										
										'original_url'    => '',
										'latitude'        => $latitude,
										'longitude'       => $longitude,
										
										'zoom'            => $googleParams['z'],
										'map_type'        => $googleParams['t'],
										'start_addr_latitude'  => $startAddrLatitude,
										
										'start_addr_longitude' => $startAddrLongitude,
										'start_location'       => $startLocation,
										'end_addr_latitude'    => $endAddrLatitude,
										
										'end_addr_longitude'   => $endAddrLongitude,
										'end_location'         => $endLocation,
										'travel_mode'          => $googleParams['dirflg'],
										
										'route_select'         => $googleParams['start']
									)

);

$result = $shopAnswerContentGoogleMap -> insert();
$shopAnswerContentId = $shopAnswerContentGoogleMap -> getShopAnswerContentId();
$contentTitle = $shopAnswerContentGoogleMap -> getContentTitle();

redirect_header(XOOPS_URL.'/modules/'.$GLOBALS['mytrustdirname'].'/answer_content/?action=_reload&'.
         'shopAnswerId=' . $shopAnswerId . '&shopAnswerContentId=' . $shopAnswerContentId . '&contentType=google_map' .
		 '&contentTitle='. urlencode($contentTitle) .'&contentUpdateAreaId=' . $contentUpdateAreaId);

?>