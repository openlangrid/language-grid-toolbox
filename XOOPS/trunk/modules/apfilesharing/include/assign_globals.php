<?php

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

$apfilesharing_assign_globals = array(
	'lang_total' => _MD_ALBM_CAPTION_TOTAL ,
	'mod_url' => $mod_url ,
	'mod_copyright' => $mod_copyright ,
	'lang_latest_list' => _MD_ALBM_LATESTLIST ,
	'lang_descriptionc' => _MD_ALBM_DESCRIPTIONC ,
	'lang_lastupdatec' => _MD_ALBM_LASTUPDATEC ,
	'lang_tellafriend' => _MD_ALBM_TELLAFRIEND ,
	'lang_subject4taf' => _MD_ALBM_SUBJECT4TAF ,
	'lang_submitter' => _MD_ALBM_SUBMITTER ,
	'lang_top_title' =>  @str_replace("{0}",$xoopsModuleConfig['apfilesharing_perpage'],_MD_ALBM_NEWFILES ),
	'lang_hitsc' => _MD_ALBM_HITSC ,
	'lang_commentsc' => _MD_ALBM_COMMENTSC ,
	'lang_new' => _MD_ALBM_NEW ,
	'lang_updated' => _MD_ALBM_UPDATED ,
	'lang_popular' => _MD_ALBM_POPULAR ,
	'lang_ratethisfile' => _MD_ALBM_RATETHISFILE ,
	'lang_editthisfile' => _MD_ALBM_EDITTHISFILE ,
	'lang_delthisfile' => _MD_ALBM_AM_BUTTON_REMOVE ,
	'lang_guestname' => _MD_ALBM_CAPTION_GUESTNAME ,
	'lang_category' => _MD_ALBM_CAPTION_CATEGORY ,
	'lang_nomatch' => _MD_ALBM_NOMATCH ,
	'lang_directcatsel' => _MD_ALBM_DIRECTCATSEL ,
	'files_url' => $files_url ,
	//'thumbs_url' => $thumbs_url ,
	'thumbsize' => $apfilesharing_thumbsize ,
	'colsoftableview' => $apfilesharing_colsoftableview ,
	'canrateview' => $global_perms & GPERM_RATEVIEW ,
	'canratevote' => $global_perms & GPERM_RATEVOTE ,
	'cantellafriend' => $global_perms & GPERM_TELLAFRIEND ,
) ;

?>