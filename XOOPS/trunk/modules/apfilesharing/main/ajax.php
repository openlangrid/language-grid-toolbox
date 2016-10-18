<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to share
// files with other users.
// Copyright (C) 2009  NICT Language Grid Project
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
include( 'header.php' ) ;

header('Content-Type: application/json; charset=utf-8;');
switch($_POST['command']){
	case "check_next":
		$cid = @$_POST["cid"];
		$myid = @$_POST["my_cid"];
		if(!is_numeric($cid)){
			echo json_encode(array('status'=>'ERROR'));
		}else{
			if(sub_folder_exists($cid,$myid)){
				echo json_encode(array('status'=>'OK', 'contents'=> true));
			}else{
				echo json_encode(array('status'=>'OK', 'contents'=> false));
			}
		}
	break;
	case "add_select":
		$id = @$_POST["id"];
		$pid = @$_POST["pid"];
		$myid = @$_POST["my_cid"];
		
		$contents = " &gt; ";
		if($id % 3 == 0){$contents .= "<br>";}
		$contents .= '<a id="minus'.$id.'" class="btn btn-tgr" href="javascript:del_select('.$id.'); "><img src="./img/icn_minus.gif"></a>';
		$contents .= '<select id="cid'.$id.'" name="cid" onchange="check_next(this,'.$id.'); ">';
		
		$options = get_sub_folder($pid,$myid);
		$firstId = null;
		foreach($options as $val){
			$contents .= "<option value='".$val['id']."'";
			$contents .= ">".$val['title']."</option>\n";
			
			if($firstId == null){
				$firstId = $val['id'];
			}
		}
		$contents .= '</select>';

		echo json_encode(array('status'=>'OK', 'contents'=> $contents ,'hasNext' => sub_folder_exists($firstId)));
	break;
	case "disable_c":
		
		$folder_id = @$_POST["cid"];
		if(is_null($folder_id)){
			$parent_id = @$_POST["pid"];
			$my_id = @$_POST["my_cid"];
			$options=get_sub_folder($parent_id,$my_id);
			$folder_id=$options[0]['id'];
		}
		include_once( 'class/folderManager.php' );
		include_once( 'class/folder.php' );
		
		$folderM = new FolderManager();
		$folder = $folderM->getFolder($folder_id);
		$read_type = $folder->getReadType();
		$read_permission=$folderM->getReadPermission($folder_id);
		echo json_encode(
			array(
				'status'=>'OK',
				'read_type'=> $read_type,
				'read_permission'=>$read_permission
			)
		);
	break;	
}
exit();
?>