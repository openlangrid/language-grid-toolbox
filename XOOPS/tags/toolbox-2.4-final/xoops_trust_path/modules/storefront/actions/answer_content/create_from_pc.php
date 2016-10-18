<?php

$shopAnswerId = @$_GET['shopAnswerId'] ? $_GET['shopAnswerId'] : @$_POST['shopAnswerId'];
$contentUpdateAreaId = @$_GET['contentUpdateAreaId'] ? $_GET['contentUpdateAreaId'] : @$_POST['contentUpdateAreaId'];


$tmp_path = $_FILES['upfile']['tmp_name'];
$data = file_get_contents($tmp_path);

list($width, $height) = getimagesize($tmp_path);

$content = null;

if (is_uploaded_file($tmp_path)) {

	$shopAnswerContentImage 
		= ShopAnswerContentImage::createFromParams(
								 array(
										'shop_answer_id'  => $shopAnswerId,
										'content_title'   => $_POST['content_title'],
										'permission'      => '0',// TODO
										'content_type'    => 'image',
										
										'file_id'         => 0,//TODO
										'image_file_name' => $_FILES['upfile']['name'],
										'image_mimetype'  => $_FILES['upfile']['type'],
										'image_data'      => $data,
										'image_width'     => $width,
										'image_height'    => $height,
										
										'original_url'    => '',
										'latitude'        => 0.0,
										'longitude'       => 0.0,
										
										'zoom'            => 0,
										'map_type' => '',
										'start_addr_latitude'  => 0.0,
										
										'start_addr_longitude' => 0.0,
										'start_location'       => '',
										'end_addr_latitude'    => 0.0,
										
										'end_addr_longitude'   => 0.0,
										'end_location'         => '',
										'travel_mode'          => '',
										
										'route_select'         => 0
										)
								);
}

$result = $shopAnswerContentImage -> insert();
$shopAnswerContentId = $shopAnswerContentImage -> getShopAnswerContentId();
$contentTitle = $shopAnswerContentImage -> getContentTitle();

redirect_header(XOOPS_URL.'/modules/'.$GLOBALS['mytrustdirname'].'/answer_content/?action=_reload&'.
            'shopAnswerId=' . $shopAnswerId . '&shopAnswerContentId=' . $shopAnswerContentId .
			'&contentType=image&contentTitle=' . urlencode($contentTitle) .'&contentUpdateAreaId=' . $contentUpdateAreaId);
?>