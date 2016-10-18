<?php
require_once dirname(__FILE__).'/file.php';

class ShopAnswerContent {

	const PERM_ALL_WRITEABLE = 0;
	const PERM_ALL_READABLE = 1;
	const PERM_PRIVATE = 2;

	protected $record;
	protected $params;

	protected function __construct($params) {
		//$params['delete_flag'] = false;
		$this -> params = $params;
	}

	/**
	 * @param array $param
	 * @return ShopAnswerContent
	 */
	static public function createFromParams(array $param) {
		$param['content_title'] = unescape_magic_quote($param['content_title']);
		
		switch ($param['content_type']) {
			case 'image':
				// get file data from file sharing API
				$file = File::findById($param['file_id']);

				if ($file) {
					$param['image_file_name'] = $file -> getName();
					$param['image_mimetype']  = $file -> getMimeType();
					$param['created']         = $file -> getCreationDate();
					$filePath = $file -> getAbsolutePath();
					$param['image_data'] = file_get_contents($filePath);
	
					$content = new ShopAnswerContentImage($param);
				} else {
					$content = new InvalidShopAnswerContent($param);
				}
				break;
			case 'google_map':
				$content = new ShopAnswerContentGoogleMap($param);
				break;
			default:
				$content = new ShopAnswerContent($param);
				break;
		}
		return $content;
	}

	static protected function actualTableName($baseName) {
		$xoopsDB =& Database::getInstance();
		return $xoopsDB->prefix."_".$GLOBALS['mydirname']."_".$baseName;
	}

	/**
	 * Returns ShopAnswerContent instance found by shop_answer_content_id.
	 * @param int shop_answer_content_id
	 * @return ShopAnswerContent
	 */
	static public function findById($shopAnswerContentId) {
		$xoopsDB =& Database::getInstance();

		$sql  = " SELECT * ";
		$sql .= "     FROM  ";
		$sql .=         self::actualTableName("shop_answer_contents ");
		$sql .= " WHERE ";
		$sql .= "       shop_answer_content_id = %d ";
		$sql .= " ORDER BY ";
		$sql .= "       created DESC";
		$sql  = sprintf($sql, $shopAnswerContentId);

		if($result = $xoopsDB -> query($sql)) {
			$record = $xoopsDB -> fetchArray($result);
			return self::createFromParams($record);
		}
	}

	/**
	 * Returns array of ShopAnswerContent instance found by shop_answer_id.
	 * @param int shop_answer_id
	 * @return array of ShopAnswerContent instance.
	 */
	static public function findAllByAnswerId($shopAnswerId, $sort = 'DESC') {
		$xoopsDB =& Database::getInstance();
		$shopAnswerContents = array();

		$sql  = " SELECT * ";
		$sql .= "     FROM  ";
		$sql .=         self::actualTableName("shop_answer_contents ");
		$sql .= " WHERE ";
		$sql .= "       shop_answer_id = %d ";
		$sql .= " ORDER BY ";
		$sql .= "       created {$sort}";
		$sql  = sprintf($sql, $shopAnswerId);

		if($result = $xoopsDB -> query($sql)) {
			while($record = $xoopsDB -> fetchArray($result)) {
				array_push($shopAnswerContents, self::createFromParams($record));
			}
		}
		return $shopAnswerContents;
	}

	public function getShopAnswerContentId() {
		return $this -> params['shop_answer_content_id'];
	}

	public function getShopAnswerId() {
		return $this -> params['shop_answer_id'];
	}

	public function getContentType() {
		return $this -> params['content_type'];
	}

	public function getContentTitle() {
		return $this -> params['content_title'];
	}

	public function getCreated() {
		return $this -> params['created'];
	}

	/**
	 * delete shop_answer_contents by shop_answer_content_id.
	 * @return boolean
	 */
	public function delete() {

		if(!$this -> params['shop_answer_content_id']) return 0;

		$xoopsDB =& Database::getInstance();

		$sql  = " DELETE FROM ";
		$sql .=         self::actualTableName("shop_answer_contents ") ;
		$sql .= " WHERE ";
		$sql .= "       shop_answer_content_id = %d ";

		$sql = sprintf($sql, mysql_real_escape_string($this -> params['shop_answer_content_id']));

		$result = $xoopsDB -> queryF($sql);

		return $result;
	}
}

class ShopAnswerContentImage extends ShopAnswerContent {


	public function __construct($params) {
		parent::__construct($params);
	}

	// TODO Unable to get fileId from FileSharingClient#addFile function
	public function getSharingFileId() {

	}

	public function insert() {

		if(!$this -> params['shop_answer_id']) return 0;

		$xoopsDB =& Database::getInstance();

		$sql  = " INSERT INTO ";
		$sql .=        self::actualTableName("shop_answer_contents ") ;
		$sql .= "      (shop_answer_id,      content_title,   permission,        created, content_type,";

		$sql .= "      file_id,              image_file_name, image_mimetype,";
		$sql .= "      image_data,           image_width,     image_height,";

		$sql .= "      original_url,         latitude,       longitude,";
		$sql .= "      zoom,                 map_type,       start_addr_latitude,";
		$sql .= "      start_addr_longitude, start_location, end_addr_latitude,";
		$sql .= "      end_addr_longitude,   end_location,   travel_mode,";
		$sql .= "      route_select ) ";

		$sql .= " VALUES ";
		$sql .= "       ( '%d', '%s', '%s', NOW(), '%s', ";

		$sql .= "         '%d', '%s', '%s', ";
		$sql .= "         '%s', '%d', '%d', ";

		$sql .= "         '%s', '%F', '%F', ";
		$sql .= "         '%d', '%s', '%F', ";
		$sql .= "         '%F', '%s', '%F', ";
		$sql .= "         '%F', '%s', '%s', ";
		$sql .= "         '%d' ) ";

		$sql = sprintf($sql,
							mysql_real_escape_string($this -> params['shop_answer_id']),
							mysql_real_escape_string($this -> params['content_title']),
							mysql_real_escape_string($this -> params['permission']),     // delegate to file sharing
							mysql_real_escape_string('image'),// 'content_type'

							mysql_real_escape_string($this -> params['file_id']),
							mysql_real_escape_string($this -> params['image_file_name']),// delegate to file sharing
							mysql_real_escape_string($this -> params['image_mimetype']), // delegate to file sharing
							mysql_real_escape_string($this -> params['image_data']),     // delegate to file sharing
							mysql_real_escape_string($this -> params['image_width']),    // delegate to file sharing
							mysql_real_escape_string($this -> params['image_height']),   // delegate to file sharing

							mysql_real_escape_string($this -> params['original_url']),			// 'original_url' for google map
							mysql_real_escape_string(0.0),										// 'latitude' for google map
							mysql_real_escape_string(0.0),										// 'longitude' for google map

							mysql_real_escape_string(0),										// 'zoom' for google map
							mysql_real_escape_string($this -> params['map_type']),				// 'map_type' for google map
							mysql_real_escape_string(0.0),										// 'start_addr_latitude' for google map

							mysql_real_escape_string(0.0),										// 'start_addr_longitude' for google map
							mysql_real_escape_string($this -> params['start_location']),		// 'start_location' for google map
							mysql_real_escape_string(0.0),										// 'end_addr_latitude' for google map

							mysql_real_escape_string(0.0),										// 'end_addr_longitude' for google map
							mysql_real_escape_string($this -> params['end_location']),			// 'end_location' for google map
							mysql_real_escape_string($this -> params['travel_mode']),			// 'travel_mode' for google map

							mysql_real_escape_string($this -> params['route_select'])			// 'route_select' for google map
						);

		$result = $xoopsDB -> queryF($sql);

		$this -> selectImage($xoopsDB);

		return $result;
	}

	/**
	 * Returns shop_answer_contents data found by shop_answer_id.
	 * @param object $xoopsDB
	 * @return void
	 */
	private function selectImage($xoopsDB) {
		$sql  = " SELECT * ";
		$sql .= "     FROM  ";
		$sql .=         self::actualTableName("shop_answer_contents ");
		$sql .= " WHERE ";
		$sql .= "       shop_answer_id = %d ";
		$sql .= " ORDER BY ";
		$sql .= "       created DESC";
		$sql  = sprintf($sql, $this -> params['shop_answer_id']);

		if($result = $xoopsDB -> query($sql)) {
			$this -> params = $xoopsDB->fetchArray($result);
		}
	}

	public function getFileId() {
		return $this -> params['file_id'];
	}

	public function getFilename() {
		return $this -> params['image_file_name'];
	}

	public function getMimeType() {
		return $this -> params['image_mimetype'];
	}

	public function getImageData() {
		return $this -> params['image_data'];
	}

	public function getImageWidth() {
		return intval($this -> params['image_width']);
	}

	public function getImageHeight() {
		return intval($this -> params['image_height']);
	}
	
	public function htmlStylePreviewSize() {
		return $this -> getImageWidth() >= $this -> getImageHeight() ? "width='160'" : "height='180'";
	}
}

class ShopAnswerContentGoogleMap extends ShopAnswerContent {

	public $latitude;
	public $longitude;

	public function __construct($params) {
		parent::__construct($params);
	}

	/**
	 * Insert google map data.
	 * @return number|unknown
	 */
	public function insert() {
		$xoopsDB =& Database::getInstance();

		if(!$this -> params['shop_answer_id']) return 0;

		$sql  = " INSERT INTO ";
		$sql .=        self::actualTableName("shop_answer_contents ") ;
		$sql .= "      (shop_answer_id,      content_title,   permission,        created, content_type,";

		$sql .= "      file_id,              image_file_name, image_mimetype,";
		$sql .= "      image_data,           image_width,     image_height,";

		$sql .= "      original_url,         latitude,       longitude,";
		$sql .= "      zoom,                 map_type,       start_addr_latitude,";
		$sql .= "      start_addr_longitude, start_location, end_addr_latitude,";
		$sql .= "      end_addr_longitude,   end_location,   travel_mode,";
		$sql .= "      route_select ) ";

		$sql .= " VALUES ";
		$sql .= "       ( '%d', '%s', '%s', NOW(), '%s', ";

		$sql .= "         '%d', '%s', '%s', ";
		$sql .= "         '%s', '%d', '%d', ";

		$sql .= "         '%s', '%F', '%F', ";
		$sql .= "         '%d', '%s', '%F', ";
		$sql .= "         '%F', '%s', '%F', ";
		$sql .= "         '%F', '%s', '%s', ";
		$sql .= "         '%d' ) ";

		$sql = sprintf($sql,
							mysql_real_escape_string($this -> params['shop_answer_id']),
							mysql_real_escape_string($this -> params['content_title']),
							mysql_real_escape_string($this -> params['permission']),
							mysql_real_escape_string('google_map'),// content_type

							mysql_real_escape_string(-1),									// 'file_id' for image file
							mysql_real_escape_string($this -> params['image_file_name']),	// 'image_file_name' for image file
							mysql_real_escape_string($this -> params['image_mimetype']),	// 'image_mimetype' for image file
							mysql_real_escape_string($this -> params['image_data']),		// 'image_data' for image file
							mysql_real_escape_string($this -> params['image_width']),		// 'image_width' for image file
							mysql_real_escape_string($this -> params['image_height']),		// 'image_height' for image file

							mysql_real_escape_string($this -> params['original_url']),
							mysql_real_escape_string($this -> params['latitude']),
							mysql_real_escape_string($this -> params['longitude']),

							mysql_real_escape_string($this -> params['zoom']),
							mysql_real_escape_string($this -> params['map_type']),
							mysql_real_escape_string($this -> params['start_addr_latitude']),

							mysql_real_escape_string($this -> params['start_addr_longitude']),
							mysql_real_escape_string($this -> params['start_location']),
							mysql_real_escape_string($this -> params['end_addr_latitude']),

							mysql_real_escape_string($this -> params['end_addr_longitude']),
							mysql_real_escape_string($this -> params['end_location']),
							mysql_real_escape_string($this -> params['travel_mode']),

							mysql_real_escape_string($this -> params['route_select'])
						);

		$result = $xoopsDB -> queryF($sql);

		$this -> selectGoogleMap($xoopsDB);

		return $result;
	}

	private function selectGoogleMap($xoopsDB) {
		$sql  = " SELECT * ";
		$sql .= "     FROM  ";
		$sql .=         self::actualTableName("shop_answer_contents ");
		$sql .= " WHERE ";
		$sql .= "       shop_answer_id = %d ";
		$sql .= " ORDER BY ";
		$sql .= "       created DESC";
		$sql  = sprintf($sql, $this -> params['shop_answer_id']);

		if($result = $xoopsDB -> query($sql)) {
			$this -> params = $xoopsDB->fetchArray($result);
		}
	}

	public function getOriginalUrl() {
		return $this -> params['original_url'];
	}

	public function getLatitude() {
		return $this -> params['latitude'];
	}

	public function getLongitude() {
		return $this -> params['longitude'];
	}

	public function getZoom() {
		return $this -> params['zoom'];
	}

	public function getMapType() {
		return $this -> params['map_type'];
	}

	public function getStartAddrLatitude() {
		return $this -> params['start_addr_latitude'];
	}

	public function getStartAddrLongitude() {
		return $this -> params['start_addr_longitude'];
	}

	public function getStartLocation() {
		return $this -> params['start_location'];
	}

	public function getEndAddrLatitude() {
		return $this -> params['end_addr_latitude'];
	}

	public function getEndAddrLongitude() {
		return $this -> params['end_addr_longitude'];
	}

	public function getEndLocation() {
		return $this -> params['end_location'];
	}

	public function getTravelMode() {
		return $this -> params['travel_mode'];
	}

	public function getRouteSelect() {
		return $this -> params['route_select'];
	}
}

class InvalidShopAnswerContent extends ShopAnswerContent {
	public function __construct($params) {
		parent::__construct($params);
	}
	
	public function getContentType() {
		return 'invalid';
	}
	
	public function getFileId() {
		return $this -> params['file_id'];
	}
	
	public function getFilename() {
		return '';
	}

	public function getMimeType() {
		return '';
	}

	public function getImageData() {
		return '';
	}

	public function getImageWidth() {
		return '';
	}

	public function getImageHeight() {
		return '';
	}
} 

?>