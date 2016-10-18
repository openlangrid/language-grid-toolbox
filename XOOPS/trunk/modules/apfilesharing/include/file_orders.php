<?php

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

$apfilesharing_orders = array(
	"lidA" => array( "lid ASC" , _MD_ALBM_LIDASC ) ,
	"titleA" => array( "title ASC" , _MD_ALBM_FILENAMEATOZ ) ,
	"dateA" => array( "date ASC" , _MD_ALBM_DATEOLD ) ,
	"hitsA" => array( "hits ASC" , _MD_ALBM_POPULARITYLTOM ) ,
	"ratingA" => array( "rating ASC" , _MD_ALBM_RATINGLTOH ) ,
	"lidD" => array( "lid DESC" , _MD_ALBM_LIDDESC ) ,
	"titleD" => array( "title DESC" , _MD_ALBM_FILENAMEZTOA ) ,
	"dateD" => array( "date DESC" , _MD_ALBM_DATENEW ) ,
	"hitsD" => array( "hits DESC" , _MD_ALBM_POPULARITYMTOL ) ,
	"ratingD" => array( "rating DESC" , _MD_ALBM_RATINGHTOL ) ,
) ;

?>