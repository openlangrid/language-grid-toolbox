<?php
class Folder {
	private $id;
	private $parent_id;
	private $title;
	private $desc;
	private $c_date;
	private $e_date;
	private $uid;
	private $read_type;
	private $edit_type;
	
	public function __construct($param=array()) {
		if($param){
			$this->id = $param["folder_id"];
			$this->parent_id = $param["parent_id"];
			$this->title = $param["title"];
			$this->desc = $param["description"];
			$this->c_date = $param["create_date"];
			$this->e_date = $param["edit_date"];
			$this->uid = $param["user_id"];
			$this->read_type = $param["read_permission_type"];
			$this->edit_type = $param["edit_permission_type"];
		}
	}
	
	public function getId(){
		return $this->id;
	}
	public function setId($id){
		$this->id = $id;
	}
	
	public function getParentId(){
		return $this->parent_id;
	}
	public function setParentId($parent_id){
		$this->parent_id = $parent_id;
	}
	
	public function getTitle(){
		return $this->title;
	}
	public function setTitle($title){
		$this->title = $title;
	}
	
	public function getDescription(){
	return $this->desc;
	}
	public function setDescription($desc){
		$this->desc = $desc;
	}
	
	public function getCreateDate(){
		return $this->c_date;
	}
	public function setCreateDate($c_date){
		$this->c_date = $c_date;
	}
	
	public function getEditDate(){
		return $this->e_date;
	}
	public function setEditDate($e_date){
		$this->e_date = $e_date;
	}
	
	public function getUserId(){
		return $this->uid;
	}
	public function setUserId($uid){
		$this->uid = $uid;
	}
	
	public function getReadType(){
		return $this->read_type;
	}
	public function setReadType($read_type){
		$this->read_type = $read_type;
	}
	
	public function getEditType(){
		return $this->edit_type;
	}
	public function setEditType($edit_type){
		$this->edit_type = $edit_type;
	}

}