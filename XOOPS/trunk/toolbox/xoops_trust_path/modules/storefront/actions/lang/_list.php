<?php

$DEFAULT_LIMIT =5;

$page = @$_GET{'page'};
$perPage = @$_GET{'perPage'};
$resourceName = @$_GET['resourceName'];
$mainLang = @$_GET['mainLang'];

$offset = 0;

require_once dirname(__FILE__).'/../../class/common_util.php';

$languageNameMap = CommonUtil::getLocalizedLanguageNameMap();

$languageArray = CommonUtil::getLanguageListFromResource($resourceName);


if (isset($page) && isset($perPage) ) {
	$offset = ($perPage * ($page -1)) ;

}else{
	
	$page = 1;
	$perPage = $DEFAULT_LIMIT;
	
}

$lastPage = floor(count($languageArray)/$perPage);

if ((count($languageArray)%$perPage)>0){
	
	$lastPage +=1;
	
}

if ($lastPage == 0){
	
	$lastPage = 1;
	
}

$pageLangArray = array();

For ($i = $perPage * ($page -1);$i < $perPage * $page;$i++){
	
	if ($i < count($languageArray)){
		array_push($pageLangArray,$languageArray[$i]);
	}
}

$xoopsTpl->assign('page',$page);
$xoopsTpl->assign('lastPage',$lastPage);
$xoopsTpl->assign('perPage',$perPage);
$xoopsTpl->assign('resourceName',$resourceName);
$xoopsTpl->assign('mainLang',$mainLang);
$xoopsTpl->assign('languageNameMap',$languageNameMap);
$xoopsTpl->assign('languageArray',$pageLangArray);

?>