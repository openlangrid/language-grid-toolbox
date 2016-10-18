<?php

$_GET['ml_lang']='raw';

require_once "../../../mainfile.php";

if (! function_exists('debugLog')) {
    function debugLog($message) {
        $a = XOOPS_TRUST_PATH.'/log/toolbox-debug.'.date('Ymd').'.log';
        
        list($micro, $Unixtime) = explode(" ", microtime());
        $sec = $micro + date("s", $Unixtime);
        $dt = date("Y-m-d g:i:", $Unixtime).$sec;
        
        error_log($dt.' - '.$message . PHP_EOL, 3, $a);
    }
    function log_debug($message) {
        $a = XOOPS_TRUST_PATH.'/log/lc-toolbox-debug.'.date('Ymd').'.log';
        
        list($micro, $Unixtime) = explode(" ", microtime());
        $sec = $micro + date("s", $Unixtime);
        $dt = date("Y-m-d g:i:", $Unixtime).$sec;
        
        error_log($dt);
        error_log(print_r($message, 1), 3, $a);
    }
}

require_once XOOPS_ROOT_PATH . "/header.php";
require_once XOOPS_MODULE_PATH . "/user/class/ActionFrame.class.php";

$root =& XCube_Root::getSingleton();

$actionName = isset($_GET['action']) ? trim($_GET['action']) : "UserList";

$moduleRunner = new User_ActionFrame(true);
$moduleRunner->setActionName($actionName);

$root->mController->mExecute->add(array(&$moduleRunner, 'execute'));
$root->mController->execute();
require_once XOOPS_ROOT_PATH . "/footer.php";
?>
