<?php

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

//
// definitions
//

$theme_name = basename( dirname(__FILE__) ) ;
$site_salt = substr( md5( XOOPS_URL ) , -4 ) ;
$menu_cache_file = XOOPS_TRUST_PATH.'/cache/theme_'.$theme_name.'_menus_'.$site_salt.'.php' ;

// root controllers
@include_once XOOPS_ROOT_PATH.'/language/'.$GLOBALS['xoopsConfig']['language'].'/user.php' ;
$root_controllers = array(
	'/register.php' => array( 'name' => @_US_USERREG ) ,
	'/userinfo.php' => array( 'name' => @_US_PROFILE ) ,
	'/edituser.php' => array( 'name' => @_US_EDITPROFILE ) ,
	'/viewpmsg.php' => array( 'name' => @_US_INBOX ) ,
	'/readpmsg.php' => array( 'name' => @_US_INBOX , 'url' => XOOPS_URL.'/viewpmsg.php' ) ,
	'/notifications.php' => array( 'name' => @_NOT_NOTIFICATION ) ,
	'/search.php' => array( 'name' => @_SR_SEARCH ) ,
) ;

// the best method is to be assigned by module self.  especially D3 modules have to assign this :-)

// the second best method to get breadcrumbs (rebuilding from an assigned var) NOT IMPLEMENTED
$modcat_assigns = array(
	// 0=>var_name, 1=>separator
	'mydownloads' => array( 'category_path' , '&nbsp;:&nbsp;' ) ,
	'mylinks' => array( 'category_path' , '&nbsp;:&nbsp;' ) ,
) ;

// the last(worst) method to get breadcrumbs (querying parents recursively)
$modcat_trees = array(
	// 0=>table, 1=>col4id, 2=>col4pid, 3=>col4name, 4=>GET index, 5=>_tpl_vars, 6=>url_fmt
	'AMS'=>array('ams_topics','topic_id','topic_pid','topic_title','storytopic',null,'index.php?storytopic=%d'),
	'articles'=>array('articles_cat','id','cat_parent_id','cat_name','cat_id',null,'index.php?cat_id=%d'),
	'booklists'=>array('mybooks_cat','cid','pid','title','cid',null,'viewcat.php?cid=%d'),
	'catads'=>array('catads_cat','cat_id','pid','title','cat_id',null,'adslist.php?cat_id=%d'),
	'debaser'=>array('debaser_genre','genreid','subgenreid','genretitle','genreid',null,'genre.php?genreid=%d'),
	'myAds'=>array('ann_categories','cid','pid','title','cid',null,'index.php?pa=view&amp;cid=%d'),
	'myalbum'=>array('myalbum_cat','cid','pid','title','cid','photo.cid','viewcat.php?cid=%d'),
	'mydownloads'=>array('mydownloads_cat','cid','pid','title','cid',null,'viewcat.php?cid=%d'),
	'mylinks'=>array('mylinks_cat','cid','pid','title','cid',null,'viewcat.php?cid=%d'),
	'mymovie'=>array('mymovie_cat','cid','pid','title','cid',null,'viewcat.php?cid=%d'),
	'news'=>array('topics','topic_id','topic_pid','topic_title','storytopic',null,'index.php?storytopic=%d'),
	'piCal'=>array('pical_cat','cid','pid','cat_title','cid',null,'index.php?cid=%d'),
	'plzXoo'=>array('plzxoo_category','cid','pid','name','cid','category.cid','index.php?cid=%d'),
	'smartfaq'=>array('smartfaq_categories','categoryid','parentid','name','categoryid',null,'category.php?categoryid=%d'),
	'smartsection'=>array('smartsection_categories','categoryid','parentid','name','categoryid',null,'category.php?categoryid=%d'),
	'tutorials'=>array('tutorials_categorys','cid','scid','cname','cid',null,'listutorials?cid=%d'),
	'weblinks'=>array('weblinks_category','cid','pid','title','cid',null,'viewcat.php?cid=%d'),
	'weblog'=>array('weblog_category','cat_id','cat_pid','cat_title','cat_id',null,'index.php?cat_id=%d'),
	'wfdownloads'=>array('wfdownloads_cat','cid','pid','title','cid',null,'viewcat.php?cid=%d'),
	'wfsection'=>array('wfs_category','id','pid','title','category',null,'viewarticles.php?category=%d'),
	'wordpress'=>array('wp_categories','cat_ID','category_parent','cat_name','cat',null,'index.php?cat=%d'),
	'xcgal'=>array('xcgal_categories','cid','parent','name','cat',null,'index.php?cat=%d'),
	'xfsection'=>array('xfs_category','id','pid','title','category',null,'index.php?category=%d'),
) ;

global $xoopsUser, $xoopsModule, $xoopsOption, $xoopsConfig;

$dirname = is_object( @$xoopsModule ) ? $xoopsModule->getVar('dirname') : '' ;
$modname = is_object( @$xoopsModule ) ? $xoopsModule->getVar('name') : '' ;

// for compatibility of 2.0.x from xoops.org
$this->assign( array(
	'xoops_modulename' => $modname ,
	'xoops_dirname' => $dirname ,
) ) ;

// groups
if( is_object( @$xoopsUser ) ) {
	$member_handler =& xoops_gethandler( 'member' ) ;
	$groups = $member_handler->getGroupsByUser( $xoopsUser->getVar('uid') , true ) ;
	foreach( $groups as $group ) {
		$groups4assign[] = array( 'id' => $group->getVar('groupid') , 'name' => $group->getVar('name') ) ;
	}
} else {
	$groups4assign[] = array( 'id' => XOOPS_GROUP_ANONYMOUS , 'name' => _GUESTS ) ;
}
$this->assign( "xugj_groups" , $groups4assign ) ;

// xoops_breadcrumbs
if( ! is_array( @$this->_tpl_vars['xoops_breadcrumbs'] ) ) {
	$breadcrumbs = array() ;
	// root controllers
	if( ! is_object( @$xoopsModule ) ) {
		$page = strrchr( $_SERVER['SCRIPT_NAME'] , '/' ) ;
		if( isset( $root_controllers[ $page ] ) ) {
			$breadcrumbs[] = $root_controllers[ $page ] ;
		}
	} else {
		// default
		$breadcrumbs[] = array( 'url' => XOOPS_URL."/modules/$dirname/" , 'name' => $modname ) ;
		if( isset( $modcat_assigns[ $dirname ] ) && strlen( $tplvar = xugj_assign_get_tpl_vars( $this , $modcat_assigns[ $dirname ][0] ) ) ) {
			// get from breadcrumbs for each modules (the second best)
			$tplvars_info = $modcat_assigns[ $dirname ] ;
			$bc_tmps = explode( $tplvars_info[1] , $tplvar ) ;
			array_shift( $bc_tmps ) ;
			foreach( $bc_tmps as $bc_tmp ) {
				if( preg_match( '#href\=([\"\']?)(.*)\\1>(.*)\<\/a\>#' , $bc_tmp , $regs ) ) {
					$breadcrumbs[] = array(
						'name' => $regs[3] ,
						'url' => $regs[2] ,
					) ;
				}
			}
			if( $tplvars_info[2] ) xugj_assign_clear_tpl_vars( $this , $tplvars_info[0] ) ;
		} else if( isset( $modcat_trees[ $dirname ] ) ) {
			// category tree (the last method)
			$tree_info = $modcat_trees[ $dirname ] ;
			if( @$_GET[ $tree_info[4] ] > 0 ) $id_val = intval( $_GET[ $tree_info[4] ] ) ;
			else if( ! empty( $tree_info[5] ) ) $id_val = xugj_assign_get_tpl_vars( $this , $tree_info[5] ) ;
			if( ! empty( $id_val ) ) $breadcrumbs = array_merge( $breadcrumbs , xugj_assign_get_breadcrumbs_by_tree( $tree_info[0] , $tree_info[1] , $tree_info[2] , $tree_info[3] , $id_val , XOOPS_URL.'/modules/'.$dirname.'/'.$tree_info[6] ) ) ;
		}
		if( ! in_array( @$this->_tpl_vars['xoops_pagetitle'] , array( $modname , $breadcrumbs[sizeof($breadcrumbs)-1]['name'] ) ) ) {
			$breadcrumbs[] = array( 'name' => $this->_tpl_vars['xoops_pagetitle'] ) ;
		}
	}
	$this->assign( "xoops_breadcrumbs" , $breadcrumbs ) ;
}

function xugj_assign_get_breadcrumbs_by_tree( $table , $id_col , $pid_col , $name_col , $id_val , $url_fmt , $paths = array() )
{
	$db =& Database::getInstance() ;

	$sql = "SELECT `$pid_col`,`$name_col` FROM ".$db->prefix($table)." WHERE `$id_col`=".intval($id_val) ;
	$result = $db->query( $sql ) ;
	if( $db->getRowsNum( $result ) == 0 ) return $paths ;
	list( $pid , $name ) = $db->fetchRow( $result ) ;
	$paths = array_merge( array( array(
		'name' => htmlspecialchars( $name , ENT_QUOTES ) ,
		'url' => sprintf( $url_fmt , $id_val ) ,
	) ) , $paths ) ;

	return xugj_assign_get_breadcrumbs_by_tree( $table , $id_col , $pid_col , $name_col , $pid , $url_fmt , $paths ) ;
}


function xugj_assign_get_tpl_vars( &$smarty , $dot_expression )
{
	$indexes = explode( '.' , $dot_expression ) ;
	$current_array = $smarty->_tpl_vars ;
	foreach( $indexes as $index ) {
		$current_array = @$current_array[ $index ] ;
	}

	return $current_array ;
}


$menus = array() ;
@include $menu_cache_file ;
if( empty( $menus ) ) {
	// cache menus
	$module_handler =& xoops_gethandler( 'module' ) ;
	$criteria = new CriteriaCompo( new Criteria( 'hasmain' , 1 ) ) ;
	$criteria->add( new Criteria( 'isactive' , 1 ) ) ;
	$criteria->add( new Criteria( 'weight' , 0 , '>' ) ) ;
	$modules =& $module_handler->getObjects( $criteria , true ) ;
	$moduleperm_handler =& xoops_gethandler( 'groupperm' ) ;
	$group_handler =& xoops_gethandler( 'group' ) ;
	$groups =& $group_handler->getObjects() ;
	$config_handler =& xoops_gethandler( 'config' ) ;

	// backup
	if( is_object( @$GLOBALS['xoopsModule'] ) ) {
		$xoopsModuleBackup =& $GLOBALS['xoopsModule'] ;
		$xoopsModuleConfigBackup =& $GLOBALS['xoopsModuleConfig'] ;
	}

	foreach( $groups as $group ) {
		$groupid = $group->getVar('groupid') ;
		$read_allowed = $moduleperm_handler->getItemIds( 'module_read' , $groupid ) ;
		foreach( $modules as $module ) {
			if( in_array( $module->getVar('mid') , $read_allowed ) ) {
				$GLOBALS['xoopsModule'] =& $module ;
				$module->loadInfo( $module->getVar('dirname') ) ;
				if( ! $module->getInfo('sub') ) continue ; // テスト用テンポラリ（最後は消すこと）
				$menus[$groupid][ $module->getVar('dirname') ] = array(
					'name' => $module->getVar('name') ,
					'dirname' => $module->getVar('dirname') ,
					'url' => '' ,
					'sub' => $module->getInfo('sub') ,
				) ;
			}
		}
	}
	
	// restore
	if( is_object( @$xoopsModuleBackup ) ) {
		$GLOBALS['xoopsModule'] =& $xoopsModuleBackup ;
		$GLOBALS['xoopsModuleConfig'] =& $xoopsModuleBackup ;
	}
	
	ob_start() ;
	var_export( $menus ) ;
	$menus4cache = ob_get_contents() ;
	ob_end_clean() ;
	
	$fp = fopen( $menu_cache_file , 'wb' ) ;
	if( empty( $fp ) ) return ;
	fwrite( $fp , "<?php\n\$menus = ".$menus4cache.";\n?>" ) ;
	fclose( $fp ) ;
}


$united_menus = array() ;
foreach( $groups4assign as $group_tmp ) {
	if( is_array( @$menus[ $group_tmp['id'] ] ) ) $united_menus += $menus[ $group_tmp['id'] ] ;
}

$this->assign( 'xugj_menu_uls' , xugj_assign_display_menu_ul_recursively( $united_menus ) ) ;

function xugj_assign_display_menu_ul_recursively( $level_menus , $dirname = '' ) {

	$ret = "<ul>\n" ;
	foreach( $level_menus as $menu ) {
		if( ! empty( $menu['dirname'] ) ) $dirname = $menu['dirname'] ;
		$ret .= "<li>\n<a href=\"".XOOPS_URL.'/modules/'.$dirname.'/'.$menu['url']."\">".$menu['name']."</a>\n" ;
		if( ! empty( $menu['sub'] ) && is_array( $menu['sub'] ) ) $ret .= xugj_assign_display_menu_ul_recursively( $menu['sub'] , $dirname ) ;
		$ret .= "</li>\n" ;
	}
	return $ret . "</ul>\n" ;
}

// from below modified by jidaikobo - danger zone!

//assign season
$seasonClass = array( 'winter','spring','summer','autumn' ) ;

$year = date( 'Y',time() ) ;
$month = date( 'm',time() ) ;
$date = date( 'd',time() ) ;
$key = $month/3 == 4 ? 0 : $month/3 ;

$this->assign( 'hd_season' ,
	array(
		'season' => $seasonClass[$key] ,
		'today' => $month.$date ,
		'year' => $year ,
		'month' => $month ,
		'day' => $date ,
	)
) ;

// for speed up hack :-)
if( ! empty( $_SESSION['redirect_message'] ) ) {
	$this->assign( 'redirect_title','Message' ) ;
	$this->assign( 'redirect_message',$_SESSION['redirect_message'] ) ;
	$this->assign( 'is_redirected',TRUE ) ;
	unset( $_SESSION['redirect_message'] ) ;
}

//select stylesheet by cookie
//from http://www.xugj.org/modules/d3forum/index.php?topic_id=113
if( $_GET['hd_view_mode'] == 'contrast' ) {
	setcookie('hd_view_mode','contrast');
	$_COOKIE['hd_view_mode'] = 'contrast' ;
} else if( $_GET['hd_view_mode'] == 'large' ) {
	setcookie('hd_view_mode','large');
	$_COOKIE['hd_view_mode'] = 'large' ;
} else if( $_GET['hd_view_mode'] == 'nostyle' ) {
	setcookie('hd_view_mode','nostyle');
	$_COOKIE['hd_view_mode'] = 'nostyle' ;
} else if( $_GET['hd_view_mode'] == 'print' ) {
	setcookie('hd_view_mode','print');
	$_COOKIE['hd_view_mode'] = 'print' ;
} else if( $_GET['hd_view_mode'] == 'normal' ) {
	setcookie('hd_view_mode','');
	$_COOKIE['hd_view_mode'] = '' ;
}
$myquerystring = '' ;
foreach( $_GET as $key => $val ) {
	if( $key == 'hd_view_mode' || $key == 'ml_lang' || is_array( $val ) ) continue ;
	$myquerystring .= '&' . $key . '=' . urlencode( $val ) ;
}
$this->assign( 'myquerystring' , htmlspecialchars( substr( $myquerystring , 1 ) , ENT_QUOTES ) ) ;

//assign page titles - and set personal information's permission
if( file_exists( dirname( __FILE__ ) . '/theme_config.php' ) ) {
	include_once( dirname( __FILE__ ) . '/theme_config.php' ) ;
}else{
	include_once( dirname( __FILE__ ) . '/theme_config.dist.php' ) ;
}
//google_verify - emomo's prepare
$this->_tpl_vars['google_verify_v1'] = _THEME_CONFIG_VERIFY_V1 ;

//xoops_pagetitles
$this_dirname = dirname( $this->_tpl_vars['xoops_requesturi'] ) ;
$this_dirname = $this_dirname == '/' ? '' : $this_dirname ;

if ( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/register.php' ) === 0 ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_REGISTER ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/edituser.php' ) === 0 ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_EDITPROFILE ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/user.php' ) === 0 ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_LOGIN ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],'/modules/cubeUtils/index.php' ) === 0 ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_LOGIN ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/lostpass.php' ) === 0 ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_LOSTPASS ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/userinfo.php' ) === 0 ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_USERINFO ;
/*
	//it was vulnerabe spec. to change /themes/hd_default/templates/user_userinfo.html
	if( _THEME_CONFIG_ALLOW_TO_SHOW_GUEST == '0' && $this->_tpl_vars['xoops_isuser'] == 0 ){//personal information's permission
		$this->_tpl_vars['xoops_contents'] = '<p>'._THEME_XOOPSCONTENT_USERINFO.'</p>' ;
	}
*/
}elseif( $this->_tpl_vars['actionForm']->mContext->mModule->mRender->mTemplateName == 'legacy_notification_delete.html'){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_NOTIFICATIONS_CONFIRM ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/notifications.php' ) === 0 ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_NOTIFICATIONS ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/search.php' ) === 0 && $this->_tpl_vars['legacy_module'] == 'legacy' && $_GET['action'] == 'results' ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_SEARCH_RESULT ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/search.php' ) === 0 && $this->_tpl_vars['legacy_module'] == 'legacy' && $_GET['action'] == 'showall' ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_SEARCH_RESULT_SHOWALL ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/search.php' ) === 0 && $this->_tpl_vars['legacy_module'] == 'legacy' ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_SEARCH ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/readpmsg.php' ) === 0 && $_GET['action'] == 'DeleteOne' ){
	$this->_tpl_vars['xoops_pagetitle'] = sprintf( _THEME_PAGETITLE_DELPMSG , intval( @$_GET['msg_id'] ) ) ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/readpmsg.php' ) === 0 ){
	$this->_tpl_vars['xoops_pagetitle'] = sprintf( _THEME_PAGETITLE_READPMSG , intval( @$_GET['msg_id'] ) ) ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/misc.php' ) === 0 && $_GET['type'] == 'smilies' ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_CLICKASMILIE ;
}elseif( isset( $this->_tpl_vars['lang_siteclosemsg'] ) ){
	$this->_tpl_vars['xoops_pagetitle'] = 'Closed' ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/pmlite.php' ) === 0 ){//private message
	if( isset( $_POST['subject'] ) || isset( $_POST['message'] ) ){
		if( $_POST['subject'] == '' || $_POST['message'] == '' ){
			$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_PM_ERROR ;
		}else{
			$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_PM_SENT ;
		}
	}
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/misc.php' ) === 0 && $_GET['type'] == 'friend' ){//tell a friend
	if( isset( $_POST['fname'] ) || isset( $_POST['fmail'] ) ){
		if( $_POST['fname'] == '' || $_POST['fmail'] == '' ){
			$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_TELLAFRIEND_ERROR ;
		}else{
			$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_TELLAFRIEND_SENT ;
		}
	}else{
		$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_TELLAFRIEND ;
	}
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/misc.php' ) === 0 && $_GET['type'] == 'online' ){//online
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_ONLINE ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/imagemanager.php' ) === 0 && $_GET['op'] == 'upload' ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_IMAGEMANAGER_UPLOAD ;
}elseif( strpos( $this->_tpl_vars['xoops_requesturi'],$this_dirname.'/imagemanager.php' ) === 0 ){
	$this->_tpl_vars['xoops_pagetitle'] = _THEME_PAGETITLE_IMAGEMANAGER ;
}

//xoops_pagetitles - admin section
if( $this->_tpl_vars['xoops_runs_admin_side'] ){
	if( strpos( $this_dirname,'legacy' ) ){
		if( $this->_tpl_vars['xoops_requesturi'] == $this_dirname.'/index.php?action=BlockList' ){
			$this->_tpl_vars['xoops_pagetitle'] = _MI_LEGACY_MENU_BLOCKLIST ;
		}elseif( $this->_tpl_vars['xoops_requesturi'] == $this_dirname.'/index.php?action=PreferenceEdit&amp;confcat_id=1' ){
			$this->_tpl_vars['xoops_pagetitle'] = _MD_AM_GENERAL ;
		}elseif( $this->_tpl_vars['xoops_requesturi'] == $this_dirname.'/index.php?action=InstallList' ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_MODINSTALL ;
		}elseif( strpos( $this->_tpl_vars['xoops_requesturi'], $this_dirname.'/index.php?action=ModuleInstall' ) === 0 && isset( $_POST ) ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_MODINSTALL_SUCCESS ;
		}elseif( strpos( $this->_tpl_vars['xoops_requesturi'], $this_dirname.'/index.php?action=ModuleInstall' ) === 0 ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_MODINSTALL_CONF ;
		}elseif( strpos( $this->_tpl_vars['xoops_requesturi'], $this_dirname.'/index.php?action=ModuleUninstall' ) === 0 && ! empty( $_POST ) ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_MODUNINSTALL_SUCCESS ;
		}elseif( strpos( $this->_tpl_vars['xoops_requesturi'], $this_dirname.'/index.php?action=ModuleUninstall' ) === 0 ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_MODUNINSTALL_CONF ;
		}elseif( $this->_tpl_vars['xoops_requesturi'] == $this_dirname.'/index.php' && ! empty( $_POST ) ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_MODUPDATE_CONF ;
		}elseif( $this->_tpl_vars['xoops_requesturi'] == $this_dirname.'/index.php?action=ModuleList' || $this->_tpl_vars['xoops_requesturi'] == $this_dirname.'/index.php' ){
			$this->_tpl_vars['xoops_pagetitle'] = _MI_LEGACY_MENU_MODULELIST ;
		}elseif( $this->_tpl_vars['xoops_requesturi'] == $this_dirname.'/index.php?action=ImagecategoryList' ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_IMAGECATEGORY_LIST ;
		}elseif( $this->_tpl_vars['xoops_requesturi'] == $this_dirname.'/index.php?action=ImagecategoryEdit' ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_IMAGECATEGORY_NEW ;
		}elseif( strpos( $this->_tpl_vars['xoops_requesturi'], $this_dirname.'/index.php?action=ImagecategoryEdit&amp;imgcat_id' ) === 0 ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_IMAGECATEGORY_EDIT ;
		}elseif( strpos( $this->_tpl_vars['xoops_requesturi'], $this_dirname.'/index.php?action=ImageCreate&amp;imgcat_id' ) === 0 ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_IMAGE_NEW ;
		}elseif( strpos( $this->_tpl_vars['xoops_requesturi'], $this_dirname.'/index.php?action=ImageEdit&amp;image_id' ) === 0 ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_IMAGE_EDIT ;
		}elseif( strpos( $this->_tpl_vars['xoops_requesturi'], $this_dirname.'/index.php?action=ImageDelete&amp;image_id' ) === 0 ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_IMAGE_DELETE ;
		}elseif( strpos( $this->_tpl_vars['xoops_requesturi'], $this_dirname.'/index.php?action=ImagecategoryDelete&amp;imgcat_id' ) === 0 ){
			$this->_tpl_vars['xoops_pagetitle'] = _AD_LEGACY_LANG_IMAGECATEGORY_DELETE ;
		}
	}

	//append xoops_breadcrumbs and delete default breadcrumbs
	if( strpos( $this->_tpl_vars['xoops_requesturi'], $this_dirname.'/index.php?action=Image' ) === 0 ){
		if( $this->_tpl_vars['xoops_requesturi'] != $this_dirname.'/index.php?action=ImagecategoryList' ){
			$this->append( "xoops_breadcrumbs" , array('name' => _MI_LEGACY_MENU_IMAGE_MANAGE ,'url' => "./index.php?action=ImagecategoryList" ) ) ;
		}
	}
	$this->assign( "xoops_contents" , preg_replace( '/<div class="adminnavi">.*?<\/div>/s','',$this->_tpl_vars['xoops_contents'] ) ) ;
}

//append xoops_breadcrumbs for pico
if( $this->_tpl_vars['mytrustdirname'] == 'pico' && $_GET['page'] == 'contentmanager' ){
		array_pop( $this->_tpl_vars['xoops_breadcrumbs'] ) ;
		$this->append( "xoops_breadcrumbs" , array('name' => htmlspecialchars( $this->_tpl_vars['content']['subject'],ENT_QUOTES ) ,'url' => "./index.php?content_id=".intval( $this->_tpl_vars['content']['id'] ) ) ) ;
}

//smarty.template
/*
echo '<pre>' ;
print_r( $this->_smarty_vars ) ;
echo '</pre>' ;
*/

?>