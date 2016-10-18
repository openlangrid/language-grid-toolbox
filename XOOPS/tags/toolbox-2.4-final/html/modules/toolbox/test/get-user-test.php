<?php
require_once dirname(__FILE__).'/../../../mainfile.php';
require_once dirname(__FILE__).'/../toolbox.php';
$users = ToolBox::getAllUsers();
var_dump($users);
?>