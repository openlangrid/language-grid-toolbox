<?php
/**
 * @version $Id: d3blog.php 146 2007-09-19 00:38:32Z hodaka $
 * @author  Takeshi Kuriyama <kuri@keynext.co.jp>
 * @remarks   Rename this to mydirname.php.
 */

if(!defined('XOOPS_ROOT_PATH')) exit();

$mydirname = basename(__FILE__,'.php');

require XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/include/xmobile.inc.php';

?>