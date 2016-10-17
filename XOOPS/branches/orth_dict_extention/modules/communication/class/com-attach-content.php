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

require_once dirname(__FILE__).'/com-abstract-model.php';
require_once dirname(__FILE__).'/com-content.php';

class Com_Attach_Content extends Com_AbstractModel {

	protected $postId;
	protected $contentId;
	protected $imageId = 0;
	
	protected $deleteFlag;
	
	public $attachContentId;
	
	// marker
	protected $contentMarker;
	

	public function __construct($params = array()) {
		$this -> record = $params;
	}

	protected function getTableName() {
		return "attachment_content";
	}
	
	protected function getColumnsOnInsert() {
		return array(
			"post_id",
			"content_id",
			"image_id",
			"content_marker_created"
		);
	}

	public function setParams($params) {
		$this -> postId    = $params['messageId'];
		if($params['messageId']) {
			$params['post_id'] = $params['messageId'];
		}
		$this -> contentId = $params['contentId'];
		$this -> params = $params;
	}
	
	public function getId() {
		return $this -> getAttachContentId();
	}
	
	public function getAttachContentId() {
		return $this->_get("attachment_content_id");
	}
	
	public function hasMarker() {
		if(is_null($this -> contentMarker))
			$this -> contentMarker = Com_Content_Marker::findByAttachmentContentId($this -> getId());
		return !is_null($this -> contentMarker);
	}
	
	public function getContentMarker() {
		if(!$this -> contentMarker) {
			$this -> contentMarker = 
				Com_Content_Marker::findByAttachmentContentId($this -> getAttachContentId());
		}
		return $this -> contentMarker;
	}
	
	public function getPostId() {
		return $this -> _get('post_id');
	}
	
	public function getMessageId() {
		return $this -> _get('post_id');
	}
	
	public function getContentId() {
		return $this -> _get('content_id');
	}
	
	public function isAvailable() {
		return true;
	}
	
	public function insert() {
		if($this -> validateOnInsert()) {
			die();
		}
		
		// transaction start
		$xoopsDB =& Database::getInstance();
		$result = $this -> insert_attachment_content($xoopsDB);
		if($result && $this -> params['marker']) {
			$result &= $this -> insert_content_marker($xoopsDB, $this -> _get('attachment_content_id'));	
		}
		// transaction end
		return $result;
	}
	
	private function insert_attachment_content($xoopsDB){
		$exist = self::findByMessageId($this -> params['post_id']);
		if($exist) $exist -> delete();
		
		$result = parent::insert($xoopsDB);
		
		return $result; 
	}
	
	private function insert_content_marker($xoopsDB, $attachment_content_id){
		$markerParams = $this -> params['marker'];
		$markerParams['attachment_content_id'] = $attachment_content_id;
		
		if(!@$markerParams['x_coordinate'] || !@$markerParams['y_coordinate']) {
			return;
		}
		
		$marker = new Com_Content_Marker($markerParams);
		$marker -> insert($xoopsDB);
		
		$this -> contentMarker = $marker;
		return $result; 
	}
	
	public function validateOnInsert() {
		if(!$this -> params['post_id']) return true;
		if(!$this -> params['content_id']) return true;
		return false;
	}
	
	public function delete() {
		$xoopsDB =& Database::getInstance();
		$result = parent::delete($xoopsDB, array('attachment_content_id' => $this -> _get('attachment_content_id')));
		
		if($this -> hasMarker()) {
			$result &= $this -> getContentMarker() -> delete($xoopsDB);
		}
		
		return $result;
	}

	static public function findByMessageId($messageId) {
		return self::find(array("post_id" => $messageId));
	}
	static public function findById($attachment_content_id) {
		return self::find(array('attachment_content_id' => $attachment_content_id));
	}
	
	static public function find($where = array()) {
		return parent::find('Com_Attach_Content', array('where' => $where));
	}
	
	static public function createFromParams($params) {
		$attach = new Com_Attach_Content();
		$attach -> setParams($params);
		return $attach;
	}
	
	private $content = null;
	public function getContent() {
		if(!$this -> content) $this -> content = Com_Content::findById($this -> getContentId());
		return $this -> content;
	}
	
	public function findAllByContentId($contentId) {
		$xoopsDB =& Database::getInstance();
		$tmp = new Com_Attach_Content();
		
		$sql  = " SELECT ";
		$sql .= "    * ";
		$sql .= " FROM ";
		$sql .=	     $tmp -> prefixedTableName();
		$sql .= " WHERE ";
		$sql .= "    content_id = %d";
		$sql .= " ORDER BY ";
		$sql .= "    content_marker_created DESC ";
		
		$sql = sprintf($sql, mysql_real_escape_string($contentId));
		$attachContents = array();
		if($result = $xoopsDB->query($sql)) {
		
			while ($row = $xoopsDB->fetchArray($result)) {
				$attachContent = new Com_Attach_Content($row);
				array_push($attachContents, $attachContent);
			}
		}
		
		return $attachContents;
	}
	
	/*	
	public function getXcoordinate() {
		if ($this -> contentMarker && $this -> contentMarker -> getMarkerId()) {
			return $this -> contentMarker -> getXCoordinate();
		}
		return 0;
	}
	
	public function getYcoordinate() {
		if ($this -> contentMarker && $this -> contentMarker -> getMarkerId()) {
			return $this -> contentMarker -> getYCoordinate();
		}
		return 0;
	}
	
	static protected function actualTableName($baseName) {
		$xoopsDB =& Database::getInstance();
		return $xoopsDB->prefix."_".$GLOBALS['mydirname']."_".$baseName;
	}

	private function selectNewAttachContent($xoopsDB, $postId) {

		$sql  = " SELECT ";
		$sql .= "    ac.attachment_content_id, ac.post_id, ac.content_id, ac.image_id, ac.content_marker_created, co.delete_flag ";
		$sql .= " FROM ";
		$sql .=	     $this -> actualTableName("attachment_content ") . " ac ";
		$sql .= "    INNER JOIN ";
		$sql .=	     $this -> actualTableName("content ") . " co ";
		$sql .= "    ON ";
		$sql .= "    ac.content_id = co.content_id ";		
		$sql .= " WHERE ";
		$sql .= "    ac.post_id = %d";
		$sql .= " ORDER BY ";
		$sql .= "    ac.content_marker_created DESC ";

		$sql = sprintf($sql, mysql_real_escape_string($postId));
		
		if($result = $xoopsDB->query($sql)) {
			
			$contentAttachRecord = $xoopsDB->fetchArray($result);						
			$this -> attachContentId = $contentAttachRecord['attachment_content_id'];
			$this -> postId          = $contentAttachRecord['post_id'];
			$this -> contentId       = $contentAttachRecord['content_id'];
			$this -> imageId         = $contentAttachRecord['image_id'];			
			$this -> deleteFlag      = $contentAttachRecord['delete_flag'];
			return $result;
		}
	}

	private function selectAvailableAttachContent($xoopsDB, $postId) {

		$sql  = " SELECT ";
		$sql .= "    ac.attachment_content_id, ac.post_id, ac.content_id, ac.image_id, ac.content_marker_created, co.delete_flag ";
		$sql .= " FROM ";
		$sql .=	     $this -> actualTableName("attachment_content ") . " ac ";
		$sql .= "    INNER JOIN ";
		$sql .=	     $this -> actualTableName("content ") . " co ";
		$sql .= "    ON ";
		$sql .= "    ac.content_id = co.content_id ";		
		$sql .= " WHERE ";
		$sql .= "    ac.post_id = %d ";
		$sql .= "    AND ";
		$sql .= "    co.delete_flag = 0 ";		
		$sql .= " ORDER BY ";
		$sql .= "    ac.content_marker_created DESC ";

		$sql = sprintf($sql, mysql_real_escape_string($postId));
		
		if($result = $xoopsDB->query($sql)) {
			
			$contentAttachRecord = $xoopsDB->fetchArray($result);						
			$this -> attachContentId = $contentAttachRecord['attachment_content_id'];
			$this -> postId          = $contentAttachRecord['post_id'];
			$this -> contentId       = $contentAttachRecord['content_id'];
			$this -> imageId         = $contentAttachRecord['image_id'];			
			$this -> deleteFlag      = $contentAttachRecord['delete_flag'];
			return $result;
		}
	}
	
	private function selectNewAttachContentByContentId($xoopsDB, $contentId) {
		$sql  = " SELECT ";
		$sql .= "    attachment_content_id, post_id, content_id, image_id, content_marker_created ";
		$sql .= " FROM ";
		$sql .=	     $this -> actualTableName("attachment_content ");
		$sql .= " WHERE ";
		$sql .= "    content_id = %d";
		$sql .= " ORDER BY ";
		$sql .= "    content_marker_created DESC ";
		
		$sql = sprintf($sql, mysql_real_escape_string($contentId));
		
		$attachContents = array();
		
		if($result = $xoopsDB->query($sql)) {
		
			while ($row = $xoopsDB->fetchArray($result)) {
				$attachContent = new Com_Attach_Content(null);
				$attachContent -> setAttachContentId($row['attachment_content_id']);
				$attachContent -> setPostId($row['post_id']);
				$attachContent -> setContentId($row['content_id']);
				$attachContent -> setImageId($row['image_id']);
				
				array_push($attachContents, $attachContent);
			}
		}
		
		return $attachContents;
	}

	private function selectNewContentMarker($xoopsDB, $attachment_content_id) {

		$sql  = " SELECT ";
		$sql .=     " marker_id, attachment_content_id, x_coordinate, y_coordinate, content_marker_created ";
		$sql .= " FROM ";
		$sql .=	    $this -> actualTableName("content_marker ");
		$sql .= " WHERE ";
		$sql .= "   attachment_content_id = %d";
		$sql .= " ORDER BY ";
		$sql .= "   content_marker_created DESC ";

		$sql = sprintf($sql, mysql_real_escape_string($attachment_content_id));
		
		if($result = $xoopsDB->query($sql)) {
			
			while($row = $xoopsDB->fetchArray($result)) {
				
				$this -> contentMarker = new Com_Content_Marker(array(
													'marker_id' => $row['marker_id'],
													'attachment_content_id' =>  $row['attachment_content_id'],
													'x_coordinate' => $row['x_coordinate'],
													'y_coordinate' => $row['y_coordinate']));				
			}
		}
		return $result;
	}

	private function delete_attachment_content($xoopsDB){
		$sql  = " DELETE FROM ";
		$sql .=       $this -> actualTableName("attachment_content ");
		$sql .= " WHERE ";
		$sql .= 	" post_id = %d ";
		
		$sql = sprintf($sql, mysql_real_escape_string($this -> postId));
		
		return $result = $xoopsDB -> queryF($sql);
	}
	
	private function delete_attachment_content_by_content_id($xoopsDB, $contentId){
		$sql  = " DELETE FROM ";
		$sql .=       $this -> actualTableName("attachment_content ");
		$sql .= " WHERE ";
		$sql .= 	" content_id = %d ";
		
		$sql = sprintf($sql, mysql_real_escape_string($contentId));
		
		return $result = $xoopsDB -> queryF($sql);
	}
	
	private function delete_content_marker($xoopsDB, $attachment_content_id) {
		$sql  = " DELETE FROM ";
		$sql .=       $this -> actualTableName("content_marker ");
		$sql .= " WHERE ";
		$sql .= 	" attachment_content_id = %d ";
		
		$sql = sprintf($sql, mysql_real_escape_string($attachment_content_id));
		
		return $result = $xoopsDB -> queryF($sql);
	}

	public function getContentMarkerById($postId) {
		// transaction start
		$xoopsDB =& Database::getInstance();
		$result = $this -> selectNewAttachContent($xoopsDB, $postId);
		
		if ($result && $this -> attachContentId) {
			
			$this -> selectNewContentMarker($xoopsDB, $this -> attachContentId);
			return $this -> contentMarker;
		}
	}
	
	static public function createWithParams($params) {
		$content = new Com_Attach_Content($params);
		return $content;
	}
	
	
	// alias for findByMessageId
	static public function findByPostId($postId) {
		return Com_Attach_Content::findByMessageId($postId);	
	}
	
	static public function findByMessageId($messageId) {
		$xoopsDB =& Database::getInstance();
		$attach = new Com_Attach_Content(null);
		$result = $attach -> selectNewAttachContent($xoopsDB, $messageId);
		
		if ($result && $attach -> attachContentId) {
			$attach -> selectNewContentMarker($xoopsDB, $attach -> attachContentId);
		}
		return $attach;
	}
	
	static public function findAvailableAttachContentById($postId) {
		$xoopsDB =& Database::getInstance();
		$attach = new Com_Attach_Content(null);
		$result = $attach -> selectAvailableAttachContent($xoopsDB, $postId);
		
		if ($result && $attach -> attachContentId) {
			$attach -> selectNewContentMarker($xoopsDB, $attach -> attachContentId);
		}
		return $attach;
	}
	
	static public function deletes($contentId) {
		
		$updateCount = 0;
		
		$xoopsDB =& Database::getInstance();
		$attach = new Com_Attach_Content(null);
		$results = $attach -> selectNewAttachContentByContentId($xoopsDB, $contentId);
		
	
		if ($results) {
			
			$attachContentIdArray = array();
			foreach ($results as $key => $attachContent) {
				if ($attachContent -> getAttachContentId()) {
					array_push($attachContentIdArray, $attachContent -> getAttachContentId());
				}
			}
			
			if (count($attachContentIdArray) > 0) {

				$commaSeparatedId = implode(",", $attachContentIdArray) == null ? "null" : implode(",", $attachContentIdArray);
				
				// delete markers
				$contentMarker = new Com_Content_Marker(null);
				$updateCount += $contentMarker -> delete_content_markers($xoopsDB, $commaSeparatedId);
			}
			
			// delete attach contents
			$updateCount += $attach -> delete_attachment_content_by_content_id($xoopsDB, $contentId);
			
		}
		return $updateCount;
	}
	
	static public function getPostCount($contentId) {
		
		$attachContentIdArray = array();
		
		$xoopsDB =& Database::getInstance();
		$attach = new Com_Attach_Content(null);
		$results = $attach -> selectNewAttachContentByContentId($xoopsDB, $contentId);
		
		if ($results) {
			foreach ($results as $key => $attachContent) {
				
				if ($attachContent -> getAttachContentId()) {
					
					array_push($attachContentIdArray, $attachContent -> getAttachContentId());
				}
			}
		}
		return count($attachContentIdArray);
	}
	*/
	/*
	public function update() {
		// transaction start
		$xoopsDB =& Database::getInstance();
		$result = $this -> selectNewAttachContent($xoopsDB, $this -> postId);
		
		if ($this -> attachContentId) {
			$markerResult = $this -> delete_content_marker($xoopsDB, $this -> attachContentId);
		}
		$attachContentResult = $this -> delete_attachment_content($xoopsDB);
		
		if($this -> contentId) {
			$result1 = $this -> insert_attachment_content($xoopsDB);
		} 
		
		$newX = $this -> contentMarker -> getXCoordinate();
		$newY = $this -> contentMarker -> getYCoordinate();
		
		if ($newX != null && $newY != null) {
			$result2 = $this -> insert_content_marker($xoopsDB, $this -> attachContentId);
		}
		// transaction end
		return $result;
	}
	
	public function delete() {
		// transaction start
		$xoopsDB =& Database::getInstance();
		
		$result = $this -> selectNewAttachContent($xoopsDB, $this -> postId);
		
		if ($result) {
			$this -> selectNewContentMarker($xoopsDB, $this -> attachContentId);			
		}

		// delete marker
		if ($this -> contentMarker && $this -> contentMarker -> getMarkerId()) {	
			$result = $this -> contentMarker -> delete($xoopsDB);
		}
		
		$result &= $this -> delete_attachment_content($xoopsDB);
		
		// transaction end
		return $result;
	}
	*/	
}


class Com_Content_Marker extends Com_AbstractModel {

	protected $markerId;
    protected $attachmentContentId;
    protected $xCoordinate;
    protected $yCoordinate;
    
    const PREVIEW_AREA_WIDTH = 450;
    
    public function __construct($params=array()) {	
    	$this -> params = $params;
		if ($params) {
			if (isset($params['marker_id'])) {
				$this -> markerId = $params['marker_id'];
			}
			if (isset($params['attachment_content_id'])) {
				$this -> attachmentContentId = $params['attachment_content_id'];
			}
			if (isset($params['x_coordinate'])) {
				$this -> xCoordinate = $params['x_coordinate'];	
			}
			if (isset($params['y_coordinate'])) {
				$this -> yCoordinate = $params['y_coordinate'];
			}			
		}
    }
    
    protected $content;
    public function getContent() {
    	if(!$this->content) {
    		$attach = Com_Attach_Content::findById($this -> attachmentContentId);
    		$this->content = $attach -> getContent();
    	}
    	return $this -> content;
    }
    
	protected function getTableName() {
		return "content_marker";
	}
	
	protected function getColumnsOnInsert() {
		return array(
			"attachment_content_id",
			"x_coordinate",
			"y_coordinate",
			"content_marker_created"
		);
	}

    public function insert($xoopsDB) {
    	parent::insert($xoopsDB);
    }

    static protected function actualTableName($baseName) {
		$xoopsDB =& Database::getInstance();
		return $xoopsDB->prefix."_".$GLOBALS['mydirname']."_".$baseName;
	}
	
    public function setMarkerId($markerId) {
    	return $this -> markerId = $markerId;
    }
    public function setAttachmentContentId($attachmentContentId) {
    	return $this -> attachmentContentId = $attachmentContentId;
    }
    
    public function setXCoordinate($xCoordinate) {
    	return $this -> xCoordinate = $xCoordinate;
    }
    public function setYCoordinate($yCoordinate) {
    	return $this -> yCoordinate = $yCoordinate;
    }
    
    public function getMarkerId() {
    	return $this -> markerId;
    }
    public function getAttachmentContentId() {
    	return $this -> attachmentContentId;
    }
    
    public function getXCoordinate() {
    	return $this -> xCoordinate;
    }
    public function getYCoordinate() {
    	return $this -> yCoordinate;
    }
    
    public function getXCoordinate4zoom() {
    	$x = $this -> getZoomRate() * intval($this -> getXCoordinate());
    	return $x - $this -> getSubstractWidthValue();
    }
    
    public function getYCoordinate4zoom() {
    	return $this -> getZoomRate() * intval($this -> getYCoordinate());
    }
    
    protected function getZoomRate() {
    	$width = $this -> getContent() -> getImageWidth();
    	if($width <= self::PREVIEW_AREA_WIDTH) {
    		return 1;
    	} else {
    		return $width / self::PREVIEW_AREA_WIDTH;
    	}
    }
    
    // if content image width less than PREVIEW_AREA_WIDTH,
    // there is margin in preview area when marker set;
    // In display zoom, It need ignore margin size.
    // this method calculate the size.
    protected function getSubstractWidthValue() {
    	$width = $this -> getContent() -> getImageWidth();
    	if($width <= self::PREVIEW_AREA_WIDTH) {
    		$margin = self::PREVIEW_AREA_WIDTH - $width;
    		return $margin / 2;
    	} else {
    		return 0;
    	}
    }
    
    private function selectPostContentMarker($xoopsDB, $attachment_content_id) {

		$sql  = " SELECT ";
		$sql .=     " marker_id, attachment_content_id, x_coordinate, y_coordinate, content_marker_created ";
		$sql .= " FROM ";
		$sql .=	    $this -> actualTableName("content_marker ");
		$sql .= " WHERE ";
		$sql .= "   attachment_content_id = %d";
		$sql .= " ORDER BY ";
		$sql .= "   content_marker_created DESC ";

		$sql = sprintf($sql, mysql_real_escape_string($attachment_content_id));		
		if($result = $xoopsDB->query($sql)) {
			
			while($row = $xoopsDB->fetchArray($result)) {
				$this -> markerId -> $row['marker_id'];
				$this -> attachmentContentId -> $row['attachment_content_id'];
				$this -> xCoordinate -> $row['x_coordinate'];
				$this -> yCoordinate -> $row['y_coordinate'];
			}
		}
	}
	
    
	private function delete_content_marker($xoopsDB){
		$baseSql  = " DELETE FROM ";
		$baseSql .=        $this -> actualTableName("content_marker ");
		$baseSql .= " WHERE ";
		$baseSql .= "      attachment_content_id = %d";
		
		$deleteSql = sprintf($baseSql,
								mysql_real_escape_string($this -> attachmentContentId));
								
		$result = $xoopsDB -> queryF($deleteSql);		
		return $result; 
	}
    
	public function delete_content_markers($xoopsDB, $condition){
		$baseSql  = " DELETE FROM ";
		$baseSql .=        $this -> actualTableName("content_marker ");
		$baseSql .= " WHERE ";
		$baseSql .= "      attachment_content_id IN (%s)";
	
		$deleteSql = sprintf($baseSql, mysql_real_escape_string($condition));
		
		$result = $xoopsDB -> queryF($deleteSql);		
		return $result; 
	}
	
	public function delete($xoopsDB) {
		
		$result = $this -> delete_content_marker($xoopsDB);
		
		// transaction end
		return $result;
	}
	
	static public function findByAttachmentContentId($attachment_content_id) {
		return self::find(array('attachment_content_id' => $attachment_content_id));
	}
	
	static public function findById($marker_id) {
		return self::find(array('marker_id' => $marker_id));
	}
	
	static public function find($where = array()) {
		return parent::find('Com_Content_Marker', array('where' => $where));
	}
}

?>