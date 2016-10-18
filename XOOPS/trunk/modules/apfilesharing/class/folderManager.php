<?php
require_once dirname(__FILE__).'/folder.php';

class FolderManager {
	private $xoopsDB;
	private $table_folder;
	private $table_files;
	
	public function __construct() {
		$this->xoopsDB =& Database::getInstance();
		$this->table_folder = $this->xoopsDB->prefix( "apfilesharing_folder" ) ;
		$this->table_files = $this->xoopsDB->prefix( "apfilesharing_files" ) ;
		$this->table_folder_rp = $this->xoopsDB->prefix( "apfilesharing_folder_read_permission" ) ;
		$this->table_folder_ep = $this->xoopsDB->prefix( "apfilesharing_folder_edit_permission" ) ;

	}
	
	public function getFolder($id){
		$sql  = ' ';
		$sql .= ' SELECT ';
		$sql .= ' folder_id, ';
		$sql .= ' parent_id, ';
		$sql .= ' title, ';
		$sql .= ' description, ';
		$sql .= ' create_date, ';
		$sql .= ' edit_date, ';
		$sql .= ' user_id, ';
		$sql .= ' read_permission_type, ';
		$sql .= ' edit_permission_type ';
		$sql .= ' FROM '.$this->table_folder.' ';
		$sql .= ' WHERE folder_id = %d ';
		
		$sql = sprintf($sql,$id);
		
		
		$result = $this->xoopsDB->query($sql);
		
		if($row = $this->xoopsDB->fetchArray($result)){
			$folder = new Folder($row);
		}else{
			$folder = new Folder(array());
		}
		
		return $folder;
	}
	
	public function createFolder($folder){
		$sql  = '';
		$sql .= 'insert into '.$this->table_folder .'(
				parent_id,title,description,
				create_date,edit_date,user_id,
				read_permission_type,edit_permission_type 
				) ';
		$sql .= "values(%d,'%s','%s',%d,%d,%d,'%s','%s')";
		$sql = sprintf($sql,
					$folder->getParentId(),
					mysql_real_escape_string($folder->getTitle()),
					mysql_real_escape_string($folder->getDescription()),
					$folder->getCreateDate(),
					$folder->getEditDate(),
					$folder->getUserId(),
					mysql_real_escape_string($folder->getReadType()),
					mysql_real_escape_string($folder->getEditType())
		);
		

		$result = $this->xoopsDB->query($sql);
		if(!$result){
			die( "DB error: INSERT folder table" );
		}
		return $this->xoopsDB->getInsertId();
	}
	
	public function updateFolder($nfolder){
		$sql  = '';
		$sql .= 'UPDATE '.$this->table_folder.' SET ';
		$sql .= 'parent_id = %d, ';
		$sql .= 'title = \'%s\', ';
		$sql .= 'description = \'%s\', ';
		$sql .= 'edit_date = %d, ';
		$sql .= 'read_permission_type = \'%s\' ,';
		$sql .= 'edit_permission_type = \'%s\' ';
		$sql .= 'WHERE folder_id = %d ';
			
		$sql = sprintf($sql,
					$nfolder->getParentId(),
					mysql_real_escape_string($nfolder->getTitle()),
					mysql_real_escape_string($nfolder->getDescription()),
					time(),
					mysql_real_escape_string($nfolder->getReadType()),
					mysql_real_escape_string($nfolder->getEditType()),
					$nfolder->getId()
		);
		
		$result = $this->xoopsDB->query($sql);
		if(!result){
			die( "DB error: UPDATE folder table");
		}
	}
	
	public function deleteFolder($id){
		require_once dirname(__FILE__).'/fileManager.php';

		$fileManager = new FileManager();
		//FileDelete
		$fileManager->deleteFileCond('folder_id = '.intval($id));
		
		//FolderDelete
		$sql  = '';
		$sql .= 'SELECT ';
		$sql .= 'folder_id ';
		$sql .= 'FROM '.$this->table_folder.' ';
		$sql .= 'WHERE parent_id = '.intval($id).' ';
				
		$rs = $this->xoopsDB->query($sql);
		while( $row = $this->xoopsDB->fetchArray( $rs ) ) {
			$this->deleteFolder($row['folder_id']);
		}
		$this->xoopsDB->freeRecordSet($rs);
		
		$sql = "DELETE FROM ".$this->table_folder." WHERE folder_id=".intval($id);

		$this->xoopsDB->query($sql ) or die( "DB error: DELETE folder table." ) ;
		
		$this->deleteReadPermission($id);
		$this->deleteEditPermission($id);

	}
	
	public function createReadPermission($folder_id,$groups){

		foreach($groups as $group_id){
			$sql ='';
			$sql  = ' INSERT INTO '.$this->table_folder_rp.' VALUES(%d,%d) ';
			$sql  = sprintf($sql,intval($folder_id),intval($group_id));
			
			$result = $this->xoopsDB->query($sql);
			
			if(!$result){
				die( "DB error: INSERT folder_permission table" );
			}
		}
	}
	
	public function createEditPermission($folder_id,$groups){
		foreach($groups as $group_id){
			$sql ='';
			$sql  = ' INSERT INTO '.$this->table_folder_ep.' VALUES(%d,%d) ';
			$sql  = sprintf($sql,intval($folder_id),intval($group_id));
			
			$result = $this->xoopsDB->query($sql);
			
			if(!$result){
				die( "DB error: INSERT folder_permission table" );
			}
		}
	}
	
	public function deleteReadPermission($folder_id){
		$sql  = ' DELETE FROM '.$this->table_folder_rp.' WHERE folder_id=%d ';
		$sql = sprintf($sql,intval($folder_id));			
		$result = $this->xoopsDB->query($sql);
		
		if(!$result){
			die( "DB error: DELETE folder_permission table" );
		}
	}
	
	public function deleteEditPermission($folder_id){
		$sql  = ' DELETE FROM '.$this->table_folder_ep.' WHERE folder_id=%d ';
		$sql = sprintf($sql,intval($folder_id));			
		$result = $this->xoopsDB->query($sql);
		
		if(!$result){
			die( "DB error: DELETE folder_permission table" );
		}
	}
	
	public function getReadPermission($folder_id){
		$sql  =' SELECT group_id ';
		$sql .=' FROM '.$this->table_folder_rp.' ';
		$sql .=' WHERE folder_id = %d';
		$sql  = sprintf($sql,intval($folder_id));

		$result = $this->xoopsDB->query($sql);
		$group = array();
		while( $groups = $this->xoopsDB->fetchArray( $result ) ) {
			$group[] = $groups['group_id'];
		}
		
		return $group;
		
	}

	public function getEditPermission($folder_id){
		$sql  =' SELECT group_id ';
		$sql .=' FROM '.$this->table_folder_ep.' ';
		$sql .=' WHERE folder_id = %d';
		$sql  = sprintf($sql,intval($folder_id));

		$result = $this->xoopsDB->query($sql);
		$group = array();
		while( $groups = $this->xoopsDB->fetchArray( $result ) ) {
			$group[] = $groups['group_id'];
		}
		
		return $group;
		
	}
	
	
}