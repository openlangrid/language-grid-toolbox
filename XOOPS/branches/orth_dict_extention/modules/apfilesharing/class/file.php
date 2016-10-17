<?php

class File {
	private $id;
	private $folder_id;
	private $title;
	private $ext;
	private $status;
	private $desc;
	private $date;
	private $c_date;
	private $e_date;
	private $uid;
	private $read_type;
	private $edit_type;
	
	public function __construct($param=array()) {
		if($param){
			$this->id = $param['file_id'];
			$this->folder_id = $param['folder_id'];
			$this->title = $param['title'];
			$this->ext = $param['ext'];
			$this->status =$param['status'];
			$this->desc = $param['description'];
			$this->date = $param['date'];
			$this->c_date = $param['create_date'];
			$this->e_date = $param['edit_date'];
			$this->uid = $param['user_id'];
			$this->read_type = $param['read_permission_type'];
			$this->edit_type = $param['edit_permission_type'];
		}else{
			$this->id = null;
			$this->folder_id = null;
			$this->title = null;
			$this->ext = null;
			$this->status = null;
			$this->desc = null;
			$this->date = null;
			$this->c_date = null;
			$this->e_date = null;
			$this->uid = null;
			$this->read_type = null;
			$this->edit_type = null;
		}
	}
	
	public function getId(){
		return $this->id;
	}
	public function setId($id){
		$this->id = $id;
	}
	
	public function getFolderId(){
		return $this->folder_id;
	}
	public function setFolderId($folder_id){
		$this->folder_id = $folder_id;
	}
	
	public function getTitle(){
		return $this->title;
	}
	public function setTitle($title){
		$this->title = $title;
	}
	
	public function getExtension(){
		return $this->ext;
	}
	public function setExtension($ext){
		$this->ext = $ext;
	}
	
	public function getStatus(){
		return $this->status;
	}
	public function setStatus($status){
		$this->status = $status;
	}
	
	public function getDescription(){
		return $this->desc;
	}
	public function setDescription($desc){
		$this->desc = $desc;
	}
	
	public function getDate(){
		return $this->date;
	}
	public function setDate($date){
		$this->date = $date;
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
	//test Only
	public function testPrint(){
		$st  = ' ';
		$st .= 'id:'.$this->id."\n";
		$st .= 'fid:'.$this->folder_id."\n";
		$st .= 'title:'.$this->title."\n";
		$st .= 'ext:'.$this->ext."\n";
		$st .= 'status:'.$this->status."\n";
		$st .= 'desc:'.$this->desc."\n";
		$st .= 'date:'.$this->date."\n";
		$st .= 'c_date:'.$this->c_date."\n";
		$st .= 'e_date:'.$this->e_date."\n";
		$st .= 'uid:'.$this->uid."\n";
		$st .= 'rtype:'.$this->read_type."\n";
		$st .= 'etype:'.$this->edit_type."\n";
		
		return $st;
	}
}