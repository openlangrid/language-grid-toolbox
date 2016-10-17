<?php

if( ! defined( 'FSHARE_COMMENT_FUNCTIONS_INCLUDED' ) ) {

define( 'FSHARE_COMMENT_FUNCTIONS_INCLUDED' , 1 ) ;

// comment callback functions

function filesharing_comments_update( $lid , $total_num ) {
	global $xoopsDB , $table_files ;

	$ret = $xoopsDB->query( "UPDATE $table_files SET comments=$total_num WHERE lid=$lid" ) ;
	return $ret ;
}

function filesharing_comments_approve( &$comment )
{
	// notification mail here
}

}
?>