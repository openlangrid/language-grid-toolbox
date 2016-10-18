<?php
require_once dirname(__FILE__).'/file.php';
require_once dirname(__FILE__).'/folder.php';
class ContentManager {
	private $contents = array();
	private $xoopsDB;
	private $table_folder;
	private $table_files;
	
	public function __construct($xoopsDB,$table_folder,$table_files) {
		
		$this->xoopsDB 	   = $xoopsDB;
		$this->table_folder   = $table_folder;
		$this->table_files = $table_files;
	}
	
	
	public function getContentList($root,$lsize,$pos,$sortkey,$uid){

			$sql  = '';
			$sql .= '(SELECT ';
			$sql .= '1 as ftype,';
			$sql .= 'folder_id as id,';
			$sql .= 'title,';
			$sql .= 'description,';
			$sql .= 'read_permission_type,';
//			$sql .= 'IF((read_permission_type = \'public\' OR (read_permission_type = \'user\' and user_id ='.$uid.')),1,0) as read_permission,';
			$sql .= 'edit_permission_type,';
//			$sql .= 'IF((edit_permission_type = \'public\' OR (edit_permission_type = \'user\' and user_id ='.$uid.')),1,0) as edit_permission,';
			$sql .= 'user_id,';
			$sql .= 'edit_date as date ';
			$sql .= 'from '.$this->table_folder.' ';
			$sql .= 'where parent_id = '. intval($root) .') ';
			$sql .= ' UNION ';
			$sql .= '(SELECT ';
			$sql .= '2 as ftype,';
			$sql .= 'file_id as id,';
			$sql .= 'title,';
			$sql .= 'description,';
			$sql .= 'read_permission_type,';
//			$sql .= 'IF((read_permission_type = \'public\' OR (read_permission_type = \'user\' and user_id ='.$uid.')),1,0) as read_permission,';
			$sql .= 'edit_permission_type,';
//			$sql .= 'IF((edit_permission_type = \'public\' OR (edit_permission_type = \'user\' and user_id ='.$uid.')),1,0) as edit_permission,';
			$sql .= 'user_id,';
			$sql .= 'edit_date as date ';
			$sql .= 'from '.$this->table_files.' ';
			$sql .= 'where status > 0 ';
			$sql .= 'and folder_id = '. intval($root) .') ';
			

			
//			$prs = $this->xoopsDB->queryF( $sql , $lsize , $pos );
			$prs = $this->xoopsDB->queryF( $sql);
			while( $fetched_result_array = $this->xoopsDB->fetchArray( $prs ) ) {
				$this->contents[] = apfilesharing_get_array_for_file_assign( $fetched_result_array , true );
				
			}
			

			if(count($this->contents)>0){
				foreach($this->contents as $key=>$row){
					$ftype[$key] = $row['ftype'];
					$title[$key] = $row['title'];
					$description[$key] = $row['description'];
					$r_permission[$key] = $row['can_read'];//$this->contents['read_permission'];
					$e_permission[$key] = $row['can_edit'];//$this->contents['edit_permission'];
					$date[$key] = $row['datetime'];

				}
				if($sortkey > 0 && $sortkey < 11){
					switch($sortkey){
						case  1:
							array_multisort($title,SORT_DESC,$this->contents);
							break;
						case  2:
							array_multisort($title,SORT_ASC,$this->contents);
							break;
						case  3:
							array_multisort($description,SORT_DESC,$this->contents);
							break;
						case  4:
							array_multisort($description,SORT_ASC,$this->contents);
							break;
						case  5:
							array_multisort($r_permission,SORT_DESC,$this->contents);
							break;
						case  6:
							array_multisort($r_permission,SORT_ASC,$this->contents);
							break;
						case  7:
							array_multisort($e_permission,SORT_DESC,$this->contents);
							break;
						case  8:
							array_multisort($e_permission,SORT_ASC,$this->contents);
							break;
						case  9:
							array_multisort($date,SORT_DESC,$this->contents);
							break;
						case 10:
							array_multisort($date,SORT_ASC,$this->contents);
							break;
					}
				}else{
					array_multisort($ftype,SORT_ASC,$title,SORT_ASC,$this->contents);
				}
				$this->contents = array_slice($this->contents,$pos,$lsize);
			}
		return $this->contents;
	}

	public function getContentCount($root){
		if($this->contents){
			$file_numtotal = count($this->contents);
		}else{
			$sql  = '';
			$sql .= 'SELECT t1.cnt + t2.cnt ';
			$sql .= 'FROM (SELECT COUNT(folder_id) as cnt FROM '.$this->table_folder.' WHERE parent_id = '.$root.') AS t1, ';
			$sql .= '(SELECT COUNT(file_id) as cnt FROM '.$this->table_files.' WHERE status > 0 AND folder_id = '.$root.') as t2 ';
			$result = $this->xoopsDB->query($sql);
			list( $file_num_total ) = $this->xoopsDB->fetchRow( $result );
		}
		return intval($file_num_total);

	}	

 

	

	

	
	
}