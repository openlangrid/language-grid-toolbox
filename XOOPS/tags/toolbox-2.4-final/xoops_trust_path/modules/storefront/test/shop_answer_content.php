<?php

require_once 'test_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/class/shop_question.php';

echo "****************************** [shop_answer_content.php test start] ******************************";


echo '<p>--  Test1 ShopAnswerContentImage::insert() ----------------------------------</p>' . PHP_EOL;


	$params[0] = array(
					'shop_answer_id'  => 2,
					'content_title'   => 'test image 2',
					'permission'      => '0',
					'content_type'    => 'image',
					
					'file_id'         => 0,
					'image_file_name' => 'test image file2',
					'image_mimetype'  => 'image/jpeg',
					'image_data'      => '22222222',
					'image_width'     => 400,
					'image_height'    => 300,
					
					'original_url'    => 'dummy url2',
					'latitude'        => 0.0,
					'longitude'       => 0.0,
					
					'zoom'            => 0,
					'map_type' => 'dummy map type',
					'start_addr_latitude'  => 0.0,
					
					'start_addr_longitude' => 0.0,
					'start_location'       => 'dummy start location2',
					'end_addr_latitude'    => 0.0,
					
					'end_addr_longitude'   => 0.0,
					'end_location'         => 'dummy end location2',
					'travel_mode'          => '0',
					
					'route_select'         => 0
					);



	$params[1] = array(
					'shop_answer_id'  => 3,
					'content_title'   => 'test image 3',
					'permission'      => '0',
					'content_type'    => 'image',
					
					'file_id'         => 0,
					'image_file_name' => 'test image file3',
					'image_mimetype'  => 'image/jpeg',
					'image_data'      => '333333',
					'image_width'     => 400,
					'image_height'    => 300,
					
					'original_url'    => 'dummy url3',
					'latitude'        => 0.0,
					'longitude'       => 0.0,
					
					'zoom'            => 0,
					'map_type' => 'dummy map type',
					'start_addr_latitude'  => 0.0,
					
					'start_addr_longitude' => 0.0,
					'start_location'       => 'dummy start location3',
					'end_addr_latitude'    => 0.0,
					
					'end_addr_longitude'   => 0.0,
					'end_location'         => 'dummy end location3',
					'travel_mode'          => '0',
					
					'route_select'         => 0
					);
					
	$params[2] = array(
					'shop_answer_id'  => 4,
					'content_title'   => 'test image 4',
					'permission'      => '0',
					'content_type'    => 'image',
					
					'file_id'         => 0,
					'image_file_name' => 'test image file43',
					'image_mimetype'  => 'image/jpeg',
					'image_data'      => '4444444',
					'image_width'     => 400,
					'image_height'    => 300,
					
					'original_url'    => 'dummy url4',
					'latitude'        => 0.0,
					'longitude'       => 0.0,
					
					'zoom'            => 0,
					'map_type' => 'dummy map type',
					'start_addr_latitude'  => 0.0,
					
					'start_addr_longitude' => 0.0,
					'start_location'       => 'dummy start location4',
					'end_addr_latitude'    => 0.0,
					
					'end_addr_longitude'   => 0.0,
					'end_location'         => 'dummy end location4',
					'travel_mode'          => '0',
					
					'route_select'         => 0
					);
					
		$params[3] = array(
					'shop_answer_id'  => 4,
					'content_title'   => 'test image 5',
					'permission'      => '0',
					'content_type'    => 'image',
					
					'file_id'         => 0,
					'image_file_name' => 'test image file5',
					'image_mimetype'  => 'image/jpeg',
					'image_data'      => '555555',
					'image_width'     => 400,
					'image_height'    => 300,
					
					'original_url'    => 'dummy url5',
					'latitude'        => 0.0,
					'longitude'       => 0.0,
					
					'zoom'            => 0,
					'map_type' => 'dummy map type',
					'start_addr_latitude'  => 0.0,
					
					'start_addr_longitude' => 0.0,
					'start_location'       => 'dummy start location5',
					'end_addr_latitude'    => 0.0,
					
					'end_addr_longitude'   => 0.0,
					'end_location'         => 'dummy end location5',
					'travel_mode'          => '0',
					
					'route_select'         => 0
					);

	$shopAnswerContentImage = ShopAnswerContentImage::createFromParams($params[3]);
	//$result = $shopAnswerContentImage -> insert();

	echo "[result]" . $result . "<br/>";
	
	// debug
	if (0) {
		echo "<pre>";
		var_dump($shopAnswerContentImage);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test1 ShopAnswerContentImage::insert() OK</p>' . PHP_EOL;


echo '<p>--  Test2 findById() ----------------------------------</p>' . PHP_EOL;

	// find by $shopAnswerContentId
	$shopAnswerContent = ShopAnswerContent::findById(22);

	// debug
	if (0) {
		echo "<pre>";
		var_dump($shopAnswerContent);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test2 findById() OK</p>' . PHP_EOL;


echo '<p>--  Test3 findAllByAnswerId() ----------------------------------</p>' . PHP_EOL;

	$shopAnswerContents = ShopAnswerContent::findAllByAnswerId(4, 'ASC');

	// debug
	if (0) {
		echo "<pre>";
		var_dump($shopAnswerContents);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test3 findAllByAnswerId() OK</p>' . PHP_EOL;




echo '<p>--  Test4 delete() ----------------------------------</p>' . PHP_EOL;
	
	// delete image
	$shopAnswerContents = ShopAnswerContent::findAllByAnswerId(4, 'ASC');
	foreach ($shopAnswerContents as $shopAnswerContent) {
		$result = $shopAnswerContent -> delete();
		echo "[del result]" . $result . "<br/>";
	}

	if (0) {
		echo '<pre>';
		var_dump($shopAnswerContent);
		echo '</pre>';
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test4 delete() OK</p>' . PHP_EOL;



echo '<p>--  Test5 ShopAnswerContentGoogleMap::insert() ----------------------------------</p>' . PHP_EOL;


	$params[0] = array(
					'shop_answer_id'  => 100,
					'content_title'   => 'test google map 100',
					'permission'      => '0',
					'content_type'    => 'google_map',
					
					'file_id'         => 0,
					'image_file_name' => null,
					'image_mimetype'  => null,
					'image_data'      => null,
					'image_width'     => null,
					'image_height'    => null,
					
					'original_url'    => 'dummy url 100',
					'latitude'        => 100.101,
					'longitude'       => 100.102,
					
					'zoom'            => 3,
					'map_type' => 'dummy map type',
					'start_addr_latitude'  => 5.25,
					
					'start_addr_longitude' => 75.23,
					'start_location'       => 'dummy start location 100',
					'end_addr_latitude'    => 79.24,
					
					'end_addr_longitude'   => 6.26,
					'end_location'         => 'dummy end location 100',
					'travel_mode'          => '2',
					
					'route_select'         => 5
					);



	$params[1] = array(
					'shop_answer_id'  => 101,
					'content_title'   => 'test google map 101',
					'permission'      => '0',
					'content_type'    => 'google_map',
					
					'file_id'         => 0,
					'image_file_name' => null,
					'image_mimetype'  => null,
					'image_data'      => null,
					'image_width'     => null,
					'image_height'    => null,
					
					'original_url'    => 'dummy url 101',
					'latitude'        => 101.222,
					'longitude'       => 101.333,
					
					'zoom'            => 4,
					'map_type' => 'dummy map type',
					'start_addr_latitude'  => 9.83,
					
					'start_addr_longitude' => 95.23,
					'start_location'       => 'dummy start location 101',
					'end_addr_latitude'    => 99.24,
					
					'end_addr_longitude'   => 96.26,
					'end_location'         => 'dummy end location 101',
					'travel_mode'          => '3',
					
					'route_select'         => 6
					);
					
	$params[2] = array(
					'shop_answer_id'  => 102,
					'content_title'   => 'test google map 102',
					'permission'      => '0',
					'content_type'    => 'google_map',
					
					'file_id'         => 0,
					'image_file_name' => null,
					'image_mimetype'  => null,
					'image_data'      => null,
					'image_width'     => null,
					'image_height'    => null,
					
					'original_url'    => 'dummy url 102',
					'latitude'        => 102.102,
					'longitude'       => 102.1022,
					
					'zoom'            => 4,
					'map_type' => 'dummy map type',
					'start_addr_latitude'  => 2.83,
					
					'start_addr_longitude' => 92.23,
					'start_location'       => 'dummy start location 102',
					'end_addr_latitude'    => 92.24,
					
					'end_addr_longitude'   => 92.26,
					'end_location'         => 'dummy end location 102',
					'travel_mode'          => '4',
					
					'route_select'         => 7
					);
					
		$params[3] = array(
					'shop_answer_id'  => 102,
					'content_title'   => 'test google map 102(2)',
					'permission'      => '0',
					'content_type'    => 'google_map',
					
					'file_id'         => 0,
					'image_file_name' => null,
					'image_mimetype'  => null,
					'image_data'      => null,
					'image_width'     => null,
					'image_height'    => null,
					
					'original_url'    => 'dummy url 102',
					'latitude'        => 9.09,
					'longitude'       => 9.87,
					
					'zoom'            => 4,
					'map_type' => 'dummy map type',
					'start_addr_latitude'  => 2.83,
					
					'start_addr_longitude' => 92.23,
					'start_location'       => 'dummy start location 102(2)',
					'end_addr_latitude'    => 92.24,
					
					'end_addr_longitude'   => 92.26,
					'end_location'         => 'dummy end location 102(2)',
					'travel_mode'          => '5',
					
					'route_select'         => 8
					);

	$shopAnswerContentGoogleMap = ShopAnswerContentGoogleMap::createFromParams($params[3]);
	//$result = $shopAnswerContentGoogleMap -> insert();

	echo "[result]" . $result . "<br/>";
	
	// debug
	if (0) {
		echo "<pre>";
		var_dump($shopAnswerContentImage);
		echo "</pre>";
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test5 ShopAnswerContentGoogleMap::insert() OK</p>' . PHP_EOL;


echo '<p>--  Test6 delete() ----------------------------------</p>' . PHP_EOL;
	
	// delete google map
	$shopAnswerContents = ShopAnswerContent::findAllByAnswerId(102, 'ASC');
	foreach ($shopAnswerContents as $shopAnswerContent) {
		$result = $shopAnswerContent -> delete();
		echo "[del result]" . $result . "<br/>";
	}

	if (1) {
		echo '<pre>';
		var_dump($shopAnswerContents);
		echo '</pre>';
	}

echo '<p style="background-color:#F8FFF8;border:2px solid green;">Test6 delete() OK</p>' . PHP_EOL;


echo "****************************** [shop_answer_content.php test end] ******************************";
?>