<?php
$shopAnswerId = @$_GET['shopAnswerId'] ? $_GET['shopAnswerId'] : @$_POST['shopAnswerId'];
$contentUpdateAreaId = @$_GET['contentUpdateAreaId'] ? $_GET['contentUpdateAreaId'] : @$_POST['contentUpdateAreaId'];

$fileId = @$_GET['fileId'] ? $_GET['fileId'] : @$_POST['fileId'];

$file = File::findById($fileId);
$fileName = $file -> getName();

list($width, $height) = getimagesize($file -> getFullPath());

if (file_exists($file -> getFullPath())) {
	$shopAnswerContentImage = ShopAnswerContentImage::createFromParams(array(
		'shop_answer_id'  => $shopAnswerId,
		'content_title'   => $_POST['content_title'],
		'permission'      => '0',// TODO
		'content_type'    => 'image',

		'file_id'         => $fileId,
		'image_file_name' => $fileName,
		'image_mimetype'  => '',
		'image_data'      => null,
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
	));
	$shopAnswerContentImage->insert();
}

$shopAnswerContentId = $shopAnswerContentImage -> getShopAnswerContentId();
$contentTitle = $shopAnswerContentImage -> getContentTitle();

redirect_header(XOOPS_MODULE_URL.'/'.$GLOBALS['mytrustdirname'].'/answer_content/?action=_reload&'.
            'shopAnswerId=' . $shopAnswerId . '&shopAnswerContentId=' . $shopAnswerContentId .
			'&contentType=image&contentTitle=' . urlencode($contentTitle) .'&contentUpdateAreaId=' . $contentUpdateAreaId);
