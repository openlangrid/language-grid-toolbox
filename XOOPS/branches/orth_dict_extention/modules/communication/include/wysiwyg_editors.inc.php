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

if( empty( $_POST['body_editor'] ) ) {
	$body_editor = @$xoopsModuleConfig['body_editor'] ;
} else {
	$body_editor = $_POST['body_editor'] ;
}

if( $body_editor == 'common_fckeditor' && ! empty( $xoopsModuleConfig['allow_html'] ) ) {

	// FCKeditor in common/fckeditor/
	$d3forum_wysiwyg_header = '
		<script type="text/javascript" src="'.XOOPS_URL.'/common/fckeditor/fckeditor.js"></script>
		<script type="text/javascript"><!--
			function fckeditor_exec() {
				var oFCKeditor = new FCKeditor( "'.$d3forum_wysiwygs['name'].'" , "100%" , "500" , "Default" );
				
				oFCKeditor.BasePath = "'.XOOPS_URL.'/common/fckeditor/";
				
				oFCKeditor.ReplaceTextarea();
			}
		// --></script>
	' ;
	$d3forum_wysiwyg_body = '<textarea id="'.$d3forum_wysiwygs['name'].'" name="'.$d3forum_wysiwygs['name'].'">'.htmlspecialchars($d3forum_wysiwygs['value'],ENT_QUOTES).'</textarea><script>fckeditor_exec();</script>' ;

/*} else if( $body_editor == 'common_spaw' && file_exists( XOOPS_ROOT_PATH.'/common/spaw/spaw_control.class.php' ) ) {

	// older spaw in common/spaw/
	include XOOPS_ROOT_PATH.'/common/spaw/spaw_control.class.php' ;
	ob_start() ;
	$sw = new SPAW_Wysiwyg( $d3forum_wysiwygs['name'] , $d3forum_wysiwygs['value'] ) ;
	$sw->show() ;
	$d3forum_wysiwyg_body = ob_get_contents() ;
	$d3forum_wysiwyg_header = '' ;
	ob_end_clean() ;
*/
} else {

	// normal (xoopsdhtmltarea)
	$d3forum_wysiwyg_body = '' ;
	$d3forum_wysiwyg_header = '' ;

}

?>