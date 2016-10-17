<?php
require_once dirname(__FILE__).'/file.php';


class FileManager  {
	
	private $files;
	private $xoopsDB;
	private $table_folder;
	private $table_files;
	
	public function __construct() {
		$this->xoopsDB =& Database::getInstance();
		$this->table_folder = $this->xoopsDB->prefix( "apfilesharing_folder" ) ;
		$this->table_files = $this->xoopsDB->prefix( "apfilesharing_files" ) ;
		$this->table_files_rp = $this->xoopsDB->prefix( "apfilesharing_files_read_permission" ) ;
		$this->table_files_ep = $this->xoopsDB->prefix( "apfilesharing_files_edit_permission" ) ;

	}

	
	public function getFile($id){
		
		$sql  = ' ';
		$sql .= ' SELECT '; 
		$sql .= ' file_id, ';
		$sql .= ' folder_id, ';
		$sql .= ' title, ';
		$sql .= ' ext, ';
		$sql .= ' user_id, ';
		$sql .= ' status, ';
		$sql .= ' date, ';
		$sql .= ' description, ';
		$sql .= ' create_date,';
		$sql .= ' edit_date, ';
		$sql .= ' read_permission_type, ';
		$sql .= ' edit_permission_type ';	
		$sql .= ' FROM '.$this->table_files.' ';
		$sql .= ' WHERE file_id = %d ';

		$sql = sprintf($sql,$id);
		
		$result = $this->xoopsDB->query( $sql);
		
		if($row = $this->xoopsDB->fetchArray( $result )){
			$file = new File($row);
		} else {
			$file = new File(array());
		}
				
		return $file;
	}
	
	public function getFileUser($id){
		$sql  = '';
		$sql .= 'SELECT user_id ';
		$sql .= 'FROM '. $table_files .' ';
		$sql .= 'WHERE file_id = %d ';
		
		$sql = sprintf($sql,$id);		
		
		$result = $this->xoopsDB->query($sql);
		return $this->xoopsDB->fetchRow( $result );
		
	}
	
	public function createFile($file){

		$sql  = '';
		$sql .= 'insert into '.$this->table_files .'(
				folder_id,title,ext,user_id,status,
				date,description,create_date,edit_date,
				read_permission_type,edit_permission_type 
				) ';
		$sql .= "values(%d,'%s','%s',%d,%d,%d,'%s',%d,%d,'%s','%s')";	

		$sql = sprintf($sql,
					$file->getFolderId(),
					mysql_real_escape_string($file->getTitle()),
					mysql_real_escape_string($file->getExtension()),
					$file->getUserId(),
					$file->getStatus(),
					$file->getDate(),
					mysql_real_escape_string($file->getDescription()),
					$file->getCreateDate(),
					$file->getEditDate(),
					mysql_real_escape_string($file->getReadType()),
					mysql_real_escape_string($file->getEditType())
		);
		
		$result = $this->xoopsDB->query($sql);
		if(!$result){
			die( "DB error: INSERT file table" );
		}
		return $this->xoopsDB->getInsertId();
	}
	
	public function updateFile($nfile){
		
		$sql  = ' ';
		$sql .= ' UPDATE '.$this->table_files.' SET ';
		$sql .= ' folder_id = %d, ';
		$sql .= ' description = \'%s\', ';
		$sql .= ' edit_date = %d, ';
		$sql .= ' read_permission_type = \'%s\' ,';
		$sql .= ' edit_permission_type = \'%s\' ,';
		$sql .= ' status = 2 ';
		$sql .= 'WHERE file_id = %d ';
		
		$sql = sprintf($sql, 
					$nfile->getFolderId(),
					mysql_real_escape_string($nfile->getDescription()),
					time(),
					mysql_real_escape_string($nfile->getReadType()),
					mysql_real_escape_string($nfile->getEditType()),
					$nfile->getId()
		);
		
		$result = $this->xoopsDB->query($sql);
		if(!result){
			die( "DB error: UPDATE file table");
		}
	}
	
	public function deleteFile($id){
		$this->deleteFileCond("file_id = ".intval($id));
	}
	
	public function deleteFileCond($whr){
		global $files_dir , $thumbs_dir , $apfilesharing_mid ;
		
		$sql = "SELECT file_id, ext FROM ".$this->table_files ." WHERE $whr";
		$prs = $this->xoopsDB->query( $sql) ;
		
		while( list( $file_id , $ext ) = $this->xoopsDB->fetchRow( $prs ) ) {

			xoops_comment_delete( $apfilesharing_mid , $file_id ) ;

			
			$sql = "DELETE FROM ".$this->table_files ." WHERE file_id = %d";
			$sql = sprintf($sql,intval($file_id));

			$this->xoopsDB->query($sql) or die( "DB error: DELETE file table." ) ;
			
			$this->deleteReadPermission($file_id);
			$this->deleteEditPermission($file_id);
			
			@unlink( "$files_dir/$file_id.$ext" ) ;
			@unlink( "$files_dir/$file_id.gif" ) ;
			@unlink( "$thumbs_dir/$file_id.$ext" ) ;
			@unlink( "$thumbs_dir/$file_id.gif" ) ;
		}
		fclose( $fp );
	}
	
	public function createReadPermission($file_id,$groups){
		foreach($groups as $group_id){
			$sql  = ' INSERT INTO '.$this->table_files_rp.' ';
			$sql .= ' VALUES(%d,%d) ';
			
			$sql  = sprintf($sql,intval($file_id),intval($group_id));
			
			$result = $this->xoopsDB->query($sql);
			
			if(!$result){
				die( "DB error: INSERT file_permission table" );
			}
		}
	}
		
	public function createEditPermission($file_id,$groups){
		foreach($groups as $group_id){
			$sql  = ' INSERT INTO '.$this->table_files_ep.' ';
			$sql .= ' VALUES(%d,%d) ';
			
			$sql  = sprintf($sql,intval($file_id),intval($group_id));
			
			$result = $this->xoopsDB->query($sql);
			
			if(!$result){
				die( "DB error: INSERT file_permission table" );
			}
		}
	}
	
	public function deleteReadPermission($file_id){
		$sql  = ' DELETE FROM '.$this->table_files_rp.' WHERE file_id=%d ';
		$sql = sprintf($sql,intval($file_id));			
		$result = $this->xoopsDB->query($sql);
		
		if(!$result){
			die( "DB error: DELETE file_permission table" );
		}
	}
	
	public function deleteEditPermission($file_id){
		$sql  = ' DELETE FROM '.$this->table_files_ep.' WHERE file_id=%d ';
		$sql = sprintf($sql,intval($file_id));			
		$result = $this->xoopsDB->query($sql);
		
		if(!$result){
			die( "DB error: DELETE file_permission table" );
		}
	}
	
	public function getReadPermission($file_id){
		$sql  =' SELECT group_id ';
		$sql .=' FROM '.$this->table_files_rp.' ';
		$sql .=' WHERE file_id = %d';
		$sql  = sprintf($sql,intval($file_id));

		$result = $this->xoopsDB->query($sql);
		$group = array();
		while( $groups = $this->xoopsDB->fetchArray( $result ) ) {
			$group[] = $groups['group_id'];
		}
		
		return $group;
		
	}

	public function getEditPermission($file_id){
		$sql  =' SELECT group_id ';
		$sql .=' FROM '.$this->table_files_ep.' ';
		$sql .=' WHERE file_id = %d';
		$sql  = sprintf($sql,intval($file_id));

		$result = $this->xoopsDB->query($sql);
		$group = array();
		while( $groups = $this->xoopsDB->fetchArray( $result ) ) {
			$group[] = $groups['group_id'];
		}
		
		return $group;
		
	}
	
}