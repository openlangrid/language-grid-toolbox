<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //

require_once dirname(__FILE__).'/com-attach-content.php';
require_once dirname(__FILE__).'/file.php';
require_once dirname(__FILE__).'/language-manager.php';

class Com_Content {
	/*
	 * paramter require
	 * 	uid : login user_id
	 *  content_title : title of content
	 */
	
	// image or google map or pdf or web
	protected $type;
	
	protected $contentId;
	
	private $contentRecord;
	
	protected $params;
	
	protected $topicId;
	
	protected function __construct($contentRecord) {
		if($contentRecord) {
			$this -> contentRecord = $contentRecord;
			$this -> contentId = $contentRecord['content_id'];
			$this -> topicId = $contentRecord['topic_id']; 
		}
	}
	
	protected function setParams($params) {
		if(!$params['content_title']) $params['content_title'] = 'noname';
		$this -> params = $params;
	}
	
	public function getType() {
		return $this -> type;
	}

	public function getTopicId() {
		return $this -> topicId;
	}
	
	public function getContentId() {
		return $this -> contentId;
	}
	
	public function getContentTitle() {
		return $this -> contentRecord['content_title'];
	}
	
	public function getDeleteFlag() {
		return $this -> contentRecord['delete_flag'];
	}
	
	public function isAvailable() {
		return !$this -> contentRecord['delete_flag'];
	}
	
	public function isOwner($uid) {
		return $this -> contentRecord['uid'] == $uid;
	}
	
	public function isOwnerByLoginUser() {
		return $this -> isOwner(getLoginUserUID());
	}
	
	public function selectedForHtmlOption($contentId) {
		return $this->contentId == $contentId ? 'selected="selected"' : '';
	}
	
	public function relatedMessageNum() {
		$attachContents = Com_Attach_Content::findAllByContentId($this -> getContentId());
		return count($attachContents);
	}
	
	protected function insert_content($xoopsDB) {
		$baseSql  = " INSERT INTO ";
		$baseSql .=        $this->actualTableName("content ");
		$baseSql .= "      (topic_id, uid, content_title, content_type, content_created, delete_flag) ";
		$baseSql .= " VALUES ";
		$baseSql .= "       ('%s', '%s', '%s',  '%s',  NOW(), 0)";
	
		$insertSql = sprintf($baseSql,
								$this->topicId,
								mysql_real_escape_string($this->params['uid']),
								mysql_real_escape_string($this->params['content_title']),
								mysql_real_escape_string($this->getType())
								);
		$result = $xoopsDB -> queryF($insertSql);
		$this -> selectNewContent($xoopsDB, $this->params['uid']);
		return $result;
	}
	
	private function selectNewContent($xoopsDB, $uid) {

		$sql  = " SELECT ";
		$sql .= "     content_id, topic_id, uid, content_title, content_type, content_created";
		$sql .= " FROM ";
		$sql .=	      $this->actualTableName("content ");
		$sql .= " WHERE ";
		$sql .= "     uid = %s AND topic_id = %s";
		$sql .= " ORDER BY ";
		$sql .= "     content_created DESC ";

		$sql = sprintf($sql, mysql_real_escape_string($uid), $this->topicId);
		
		if($result = $xoopsDB->query($sql)) {
			$this->contentRecord = $xoopsDB->fetchArray($result);
			$this->contentId = $this->contentRecord['content_id'];
		}
	}
	
	
	protected function delete_content($xoopsDB) {
		if(!$this -> contentId) return 0;
		
		$sql  = " DELETE FROM ";
		$sql .=        $this->actualTableName("content ");		
		$sql .= " WHERE ";
		$sql .= "       content_id = %d ";
	
		$sql = sprintf($sql, mysql_real_escape_string($this->contentId));

		$result = $xoopsDB -> queryF($sql);
		
		return $result;
	}
	
	protected function delete_content_logically($xoopsDB) {
		if(!$this -> contentId) return 0;
		
		$sql  = " UPDATE ";
		$sql .=        $this->actualTableName("content ");
		$sql .= " SET ";
		$sql .= "      delete_flag = 1";		
		$sql .= " WHERE ";
		$sql .= "      content_id = %d ";
	
		$sql = sprintf($sql, mysql_real_escape_string($this->contentId));

		$result = $xoopsDB -> queryF($sql);
		
		return $result;
	}

	
	static protected function actualTableName($baseName) {
		$xoopsDB =& Database::getInstance();
		return $xoopsDB->prefix."_".$GLOBALS['mydirname']."_".$baseName;
	}
	
	static public function findById($contentId) {
		$sql  = " SELECT * ";
		$sql .= "    FROM ".Com_Content::actualTableName("content ");
		$sql .= " WHERE content_id = %s ";
		$sql  = sprintf($sql, mysql_real_escape_string($contentId));

		$xoopsDB =& Database::getInstance();
		if($result = $xoopsDB->query($sql)) {
			$record = $xoopsDB->fetchArray($result);
			switch($record['content_type']) {
				case 'image':
					return new Com_ContentImage($record);
				case 'google_map':
					return new Com_ContentGoogleMap($record);
			}
		}
		
		return new Com_ContentBlank();
	}
	
	static public function findAvailableContentById($contentId) {
		$sql  = " SELECT * ";
		$sql .= "    FROM ".Com_Content::actualTableName("content ");
		$sql .= " WHERE ";
		$sql .= "    content_id = %s ";
		$sql .= "    AND ";
		$sql .= "    delete_flag = 0 ";
		
		$sql  = sprintf($sql, mysql_real_escape_string($contentId));
		
		$xoopsDB =& Database::getInstance();
		if($result = $xoopsDB->query($sql)) {
			$record = $xoopsDB->fetchArray($result);
			switch($record['content_type']) {
				case 'image':
					return new Com_ContentImage($record);
				case 'google_map':
					return new Com_ContentGoogleMap($record);
			}
		}
		
		return new Com_ContentBlank();
	}
	
	static public function isContentOwner($contentId, $userId) {
		$sql  = " SELECT * ";
		$sql .= "    FROM ".Com_Content::actualTableName("content ");
		$sql .= " WHERE content_id = %s ";
		$sql .= "       AND ";
		$sql .= "       uid = %d ";
		$sql  = sprintf($sql, 
						mysql_real_escape_string($contentId),
						mysql_real_escape_string($userId));
						
		$xoopsDB =& Database::getInstance();
		$result = $xoopsDB->query($sql);
		
		if ($row = $xoopsDB->fetchArray($result)) {
			return true;
		} else {
			return false;
		}
	}
	
	static public function findAvailableContentsByTopicId($topicId) {
		$results = array();
		
		$sql = " SELECT * ";
		$sql .= "    FROM ".Com_Content::actualTableName("content ");
		$sql .= " WHERE ";
		$sql .= "    topic_id = %s ";
		$sql .= "    AND ";
		$sql .= "    delete_flag = 0 ";
		$sql .= "    ORDER BY content_title asc ";
		$sql = sprintf($sql, $topicId);
		
		$xoopsDB =& Database::getInstance();
		if($resultSet = $xoopsDB->query($sql)) {
			while($record = $xoopsDB->fetchArray($resultSet)) {
				switch($record['content_type']) {
				case 'image':
					array_push($results, new Com_ContentImage($record));
					break;
				case 'google_map':
					array_push($results, new Com_ContentGoogleMap($record));
					break;
				}
			}
		}

		return $results;
	}
	
	static public function findAllByTopicId($topicId) {
		$results = array();
		
		$sql = " SELECT * ";
		$sql .= "    FROM ".Com_Content::actualTableName("content ");
		$sql .= " WHERE topic_id = %s ";
		$sql .= "    ORDER BY content_title asc ";
		$sql = sprintf($sql, $topicId);
		
		$xoopsDB =& Database::getInstance();
		if($resultSet = $xoopsDB->query($sql)) {
			while($record = $xoopsDB->fetchArray($resultSet)) {
				switch($record['content_type']) {
				case 'image':
					array_push($results, new Com_ContentImage($record));
					break;
				case 'google_map':
					array_push($results, new Com_ContentGoogleMap($record));
					break;
				}
			}
		}

		return $results;
	}
	
	static public function findAvailableContentsByTopicIdAndTitle($topicId, $title) {
		foreach(self::findAvailableContentsByTopicId($topicId) as $content) {
			if($content -> getContentTitle() == $title)	return $content;
		}
		return null;
	}
	
	static public function findByTopicIdAndTitle($topicId, $title) {
		foreach(self::findAllByTopicId($topicId) as $content) {
			if($content -> getContentTitle() == $title)	return $content;
		}
		return null;
	}
}

class Com_ContentBlank extends Com_Content {
	public function __construct($contentRecord = null) {
		//parent::__construct($contentRecord);
		$this -> type = 'blank';
	}
	
	public function getImageId() {
		return '';
	}
}

// Content Class For Image
class Com_ContentImage extends Com_Content {
	
	/*
	 * paramter require
	 * 	file_name : original filename
	 *  mime_type : MIME type of image (ex. image/jpeg, image/gif)
	 *  data : image data(binary)
	 */
	
	// some properties here (delete this comment)
	private $contentImageRecord;
	
	public function __construct($contentRecord = null) {
		parent::__construct($contentRecord);
		$this -> type = "image";
		
		if($this -> contentId ) {
			$xoopsDB =& Database::getInstance();
			$this -> select_content_image($xoopsDB);
		}
	}
	
	public function getImageId() {
		return $this -> contentImageRecord['image_id'];
	}
	
	public function getFileId(){
		return $this -> contentImageRecord['file_id'];
	}
	
	public function getFile() {
		return File::findById($this -> getFileId());
	}
	
	public function getImageData() {
		if($this -> getFileId() == "0") {
			return $this -> contentImageRecord['image_data'];
		} else {
			$file = $this -> getFile();
			return file_get_contents($file -> getAbsolutePath());
		}
	}
	
	public function getMimeType() {
		return $this -> contentImageRecord['image_mimetype'];
	}
	
	public function getOriginalFilename() {
		return $this -> contentImageRecord['image_file_name'];
	}
	
	public function getImageWidth() {
		return intval($this -> contentImageRecord['image_width']);
	}
	
	public function getImageHeight() {
		return intval($this -> contentImageRecord['image_height']);
	}
	
	public function isOverPreviewArea() {
		return $this -> getImageWidth() > Com_Content_Marker::PREVIEW_AREA_WIDTH;		
	}
	
	public function getCreated() {
		return $this -> contentImageRecord['image_created'];
	}
	
	public function getLabelForOption() {
		return $this->getContentTitle();
	}
	
	public function setImageWidth($width) {
		return $this -> contentImageRecord['image_width'] = $width;
	}
	
	public function setImageHeight($height) {
		return $this -> contentImageRecord['image_height'] = $height;
	}
	
	// 1 save content
	// 2 save content_image
	public function insert() {
		// transaction start
		$xoopsDB =& Database::getInstance();

		$result = parent::insert_content($xoopsDB);
		
		$result += $this -> insert_content_image($xoopsDB);

		// transaction end
		return $result;
	}
	
	// 1 delete content_image
	// 2 delete content
	public function delete() {
		// transaction start
		$xoopsDB =& Database::getInstance();
		
		$result .= parent::delete_content_logically($xoopsDB);

		// transaction end		
		return $result;
	}
	
	private function insert_content_image($xoopsDB){
		if(!$this -> contentId) return 0;
		
		$baseSql  = " INSERT INTO ";
		$baseSql .=        $this->actualTableName("content_image ");
		$baseSql .= "      (content_id, file_id, image_file_name, image_mimetype, image_width, image_height, image_created) ";
		$baseSql .= " VALUES ";
		$baseSql .= "       ('%s', '%s', '%s',  '%s', '%d', '%d', NOW())";
	
		$insertSql = sprintf($baseSql,
								$this->contentId,
								mysql_real_escape_string($this->params['file_id']),
								mysql_real_escape_string($this->params['file_name']),
								mysql_real_escape_string($this->params['mime_type']),
								mysql_real_escape_string($this->params['image_width']),
								mysql_real_escape_string($this->params['image_height'])
					);

		$result = $xoopsDB -> queryF($insertSql);
		$this -> select_content_image($xoopsDB);
		return $result;
	}
	
	private function select_content_image($xoopsDB) {
		$sql = "  SELECT * ";
		$sql .= "     FROM ".$this->actualTableName("content_image ");
		$sql .= " WHERE content_id = %s ";
		$sql = sprintf($sql, $this->contentId);
		if($result = $xoopsDB->query($sql)) {
			$this->contentImageRecord = $xoopsDB->fetchArray($result);
		}
	}
		
	private function delete_content_image($xoopsDB){
		$sql  = " DELETE FROM ";
		$sql .=        $this->actualTableName("content_image ");
		$sql .= " WHERE ";
		$sql .= "      content_id = %d";
		
		return $xoopsDB -> queryF(sprintf($sql, mysql_real_escape_string($this->contentId)));
	}
	
	// add file for file sharing
	private function addFile() {	
		$obj = new FileSharingClient();
		if ($this -> params['category_id']) {
			$obj -> addFile($this -> params['category_id'], 
							$this -> params['file_name'], 
							$this -> params['file_path'], 
							$this -> params['content_title']);	
		}
	}
	
	static public function createWithParams($topicId, $params) {
		$content = new Com_ContentImage();
				
		$content->topicId = $topicId;
		$content->setParams(self::extractParamsOfFile($params));
		return $content;
	}
	
	static protected function extractParamsOfFile($params) {
		$file = $params['file'];
		$params['file_id'] = $file -> getId();
 		$params['mime_type'] = self::getMimeTypeByExtention($file -> getExt());
 		$params['file_name'] = $file -> getName();
		
		list($width, $height) = getimagesize(@$file -> getAbsolutePath());
		
		$params['image_width'] = $width;
		$params['image_height'] = $height;
		
		return $params;
	}
	
	static protected function getMimeTypeByExtention($ext) {
		$types = array(
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif'
		);
		
		$ext = strtolower($ext);
		if(!@$types[$ext]) {
			throw new Exception("not supported file type");
		}
		return $types[$ext];
	}
}


// Content Class For GoogleMap
class Com_ContentGoogleMap extends Com_Content {
	
	// some properties here (delete this comment)
	private $content_google_map_record;
	
	public function __construct($content_record = null) {
		parent::__construct($content_record);
		
		$this -> type = "google_map";
		
		if($this -> contentId ) {
			$xoopsDB =& Database::getInstance();
			$this -> select_content_google_map($xoopsDB);
		}
	}
	
	public function getLabelForOption() {
		return 'google map' . ':' . $this->getContentTitle();
	}
	
	public function getGoogleMapId() {
		return $this -> content_google_map_record['google_map_id'];
	}
	
	public function getLatitude() {
		return $this -> content_google_map_record['latitude'];
	}
	
	public function getLongitude() {
		return $this -> content_google_map_record['longitude'];
	}
	
	public function getZoom() {
		return $this -> content_google_map_record['zoom'];
	}
	
	public function getMapType() {
		return $this -> content_google_map_record['map_type'];
	}
	
	public function getStartAddrLatitude() {
		return $this -> content_google_map_record['start_addr_latitude'];
	}
	
	public function getStartAddrLongitude() {
		return $this -> content_google_map_record['start_addr_longitude'];
	}
	
	public function getStartLocation() {
		return $this -> content_google_map_record['start_location'];
	}
	
	public function getEndAddrLatitude() {
		return $this -> content_google_map_record['end_addr_latitude'];
	}
	
	public function getEndAddrLongitude() {
		return $this -> content_google_map_record['end_addr_longitude'];
	}
	
	public function getEndLocation() {
		return $this -> content_google_map_record['end_location'];
	}
	
	public function getTravelMode() {
		return $this -> content_google_map_record['travel_mode'];
	}
	
	public function getRouteSelect() {
		return $this -> content_google_map_record['route_select'];
	}
	
	public function getContentType() {
		return $this -> content_google_map_record['content_type'];
	}
	
	public function getGoogleMapCreated() {
		return $this -> content_google_map_record['google_map_created'];
	}
	

	// 1 insert content
	// 2 insert content_google_map
	public function insert() {
		// transaction start
		$xoopsDB =& Database::getInstance();
		
		$result = parent::insert_content($xoopsDB);
		
		$result .= $this -> insert_content_google_map($xoopsDB);
		// transaction end
		return $result;
	}
	
	// 1 delete content_google_map
	// 2 delete content
	public function delete() {
		// transaction start
		$xoopsDB =& Database::getInstance();
		
		$result .= parent::delete_content_logically($xoopsDB);
		
		// transaction end
		return $result;
	}
	
	private function insert_content_google_map($xoopsDB) {
		if(!$this -> contentId) return 0;
		
		$sql  = " INSERT INTO ";
		$sql .=        $this->actualTableName("content_google_map ");
		$sql .= "      (latitude,           longitude,            zoom,              map_type,  ";
		$sql .= "      start_addr_latitude, start_addr_longitude, start_location, ";
		$sql .= "      end_addr_latitude,   end_addr_longitude,   end_location, ";
		$sql .= "      travel_mode,         route_select, ";
		$sql .= "      content_type,        content_id,           google_map_created )";
		
		$sql .= " VALUES ";
		$sql .= "       ('%F', '%F', '%d',  '%s', ";
		$sql .= "       '%F', '%F', '%s', ";
		$sql .= "       '%F', '%F', '%s', ";
		$sql .= "       '%s', '%d', ";
		$sql .= "       '%s', '%d', NOW())";
	
		$sql = sprintf($sql,
							mysql_real_escape_string($this->params['latitude']),
							mysql_real_escape_string($this->params['longitude']),
							mysql_real_escape_string($this->params['zoom']),
							mysql_real_escape_string($this->params['map_type']),
							
							mysql_real_escape_string($this->params['start_addr_latitude']),
							mysql_real_escape_string($this->params['start_addr_longitude']),
							mysql_real_escape_string($this->params['start_location']),
							
							mysql_real_escape_string($this->params['end_addr_latitude']),
							mysql_real_escape_string($this->params['end_addr_longitude']),
							mysql_real_escape_string($this->params['end_location']),
							
							mysql_real_escape_string($this->params['travel_mode']),
							mysql_real_escape_string($this->params['route_select']),
							
							mysql_real_escape_string($this->params['content_type']),
							mysql_real_escape_string($this->contentId)
						);

		$result = $xoopsDB -> queryF($sql);
		
		$this -> select_content_google_map($xoopsDB);
		
		return $result;
	}
	
	// TODO
	private function delete_content_google_map($xoopsDB) {
		if(!$this -> contentId) return 0;
		
		$sql  = " DELETE FROM ";
		$sql .=        $this->actualTableName("content_google_map ");		
		$sql .= " WHERE ";
		$sql .= "       content_id = %d ";
	
		$sql = sprintf($sql, mysql_real_escape_string($this->contentId));

		$result = $xoopsDB -> queryF($sql);
		
		return $result;
	}
	
	private function select_content_google_map($xoopsDB) {
		$sql = " SELECT * ";
		$sql .= "    FROM ".$this->actualTableName("content_google_map ");
		$sql .= " WHERE ";
		$sql .= "    content_id = %d ";
		$sql = sprintf($sql, $this->contentId);
				
		if($result = $xoopsDB->query($sql)) {			
			$this->content_google_map_record = $xoopsDB->fetchArray($result);
		}
	}
		
	static public function createWithParams($topicId, $params) {
		$content = new Com_ContentGoogleMap();
		$content->topicId = $topicId;
		$content->setParams($params);
		return $content;
	}
}

class Com_Content_util {
	
	protected static $ZOOM_VIEW_WIDTH = 900;
	protected static $ZOOM_VIEW_HEIGHT = 640;
	
	protected $offsetHeight;
	
	protected $markerWidth;
	protected $markerHeight;
	
	protected $fromWidth;
	protected $fromHeight;
	
	protected $toWidth;
	protected $toHeight;
	
	const RATIO = 2;
	
	public function __construct($toWidth, $toHeight, $fromWidth=425, $fromHeight=320, $markerWidth=27, $markerHeight=27, $offsetHeight=15) {
		$this->offsetHeight = $offsetHeight;
		$this->markerWidth = $markerWidth;
		$this->markerHeight = $markerHeight;
		$this->fromWidth = $fromWidth;
		$this->fromHeight = $fromHeight;
		$this->toWidth = self::$ZOOM_VIEW_WIDTH > $toWidth ? self::$ZOOM_VIEW_WIDTH : $toWidth;
		$this->toHeight = self::$ZOOM_VIEW_HEIGHT > $toHeight ? self::$ZOOM_VIEW_HEIGHT : $toHeight;
	}

	public function getMarkerPointFromLeftTop($vector){
		return array("x" => ($vector['x'] + $this->markerWidth / 2), "y" => ($vector['y'] + $this->markerHeight));
	}
	
	public function getLeftTopFromMarkerPoint($vector){
		return array("x" => ($vector['x'] - $this->markerWidth / 2), "y" => ($vector['y'] - $this->markerHeight));
	}
	
	public function getZoomPositionFromVector($vector){
		return array("x" => ($vector['x'] + $this->toWidth / 2), "y" => ($vector['y'] + $this->offsetHeight)); 
	}
	
	public function getPreviewVectorFromTopCenter($vector){
		return array("x" => ($vector['x'] - $this->fromWidth / 2), "y" => ($vector['y'] - $this->offsetHeight)); 
	}
	
	public function getImageZoomRatio($content){
		if($content->getImageWidth() > $this -> fromWidth){
			return $this->fromWidth / $content->getImageWidth();
		}else{
			return 1;
		}
	}
	
	public function getVectorZoomRatio($content){
		if($content->getImageWidth() > $this->toWidth){
			return $this->toWidth / $this->fromWidth;
		}else if($content->getImageWidth() > $this->fromWidth){
			return $content->getImageWidth() / $this->fromWidth;
		}else{
			return 1;
		}
	}
	
	public function multipleZoomRatio($content){
		$zoomRatio = $this->getImageZoomRatio($content);
		$content->setImageWidth($content->getImageWidth() * $zoomRatio);
		$content->setImageHeight($content->getImageHeight() * $zoomRatio);
		return $content;
	}
	
	private function logg($vector){
		//error_log("x = " . $vector['x'] . ",  y = " . $vector['y']);
	}
	public function changeMarkerPositionForZoom($content, $marker){
		$zoomRatio = $this->getVectorZoomRatio($content);
		//error_log("zoom=$zoomRatio");
		$vector = array("x" => $marker -> getXCoordinate(), "y" => $marker -> getYCoordinate());
		$this->logg($vector);
		$vector = $this->getMarkerPointFromLeftTop($vector);
		$this->logg($vector);
		$vector = $this->getPreviewVectorFromTopCenter($vector);
		$this->logg($vector);
		$vector = array("x" => $vector["x"] * $zoomRatio, "y" => $vector["y"] * $zoomRatio);
		$this->logg($vector);
		$vector = $this->getZoomPositionFromVector($vector);
		$this->logg($vector);
		$vector = $this->getLeftTopFromMarkerPoint($vector);
		$this->logg($vector);
		$marker->setXCoordinate($vector["x"]);
		$marker->setYCoordinate($vector["y"]);
	}
	
public function changeMarkerPositionForZoom2($content, $marker){
		$zoomRatio = $this->getImageZoomRatio($content);
		//error_log("zoom=$zoomRatio");
		$vector = array("x" => $marker -> getXCoordinate(), "y" => $marker -> getYCoordinate());
		$this->logg($vector);
		$vector = $this->getMarkerPointFromLeftTop($vector);
		$this->logg($vector);
		$vector = array("x" => $vector["x"] * $zoomRatio, "y" => $vector["y"] * $zoomRatio);
		$this->logg($vector);
		$vector = $this->getLeftTopFromMarkerPoint($vector);
		$this->logg($vector);
		$marker->setXCoordinate($vector["x"]);
		$marker->setYCoordinate($vector["y"]);
	}
	
	static private function getResizeRatio($regularSize, $currentSize) {
		
		if ($currentSize <= $regularSize) {
			return 1;
		}else{
			return $regularSize / $currentSize;
		}
	}
	
	// designed for Image content only.
	// opitimize preview content width.
	static public function getResizeContentWidth($content, $viewAreaWidth) {
		if ($content -> getImageWidth()) {
						
			$resizeRatio = self::getResizeRatio($viewAreaWidth, $content -> getImageWidth());
			$content -> setImageWidth($content -> getImageWidth() * $resizeRatio);
			$content -> setImageHeight($content -> getImageHeight() * $resizeRatio);
		}
		
		return $content;
	}
}
?>