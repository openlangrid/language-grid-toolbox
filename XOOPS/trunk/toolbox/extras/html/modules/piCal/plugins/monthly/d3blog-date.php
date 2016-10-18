<?php
/**
 * @version $Id: d3blog-date.php 228 2007-11-18 16:10:45Z hodaka $
 * @author  Takeshi Kuriyama <kuri@keynext.co.jp>
 */

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit;

if( preg_match( '/[^0-9a-zA-Z_-]/' , $plugin['dirname'] ) ) die( 'Invalid dirname' );

$pluginTactname = basename(dirname(__FILE__));
$pluginQueryname = substr(strstr(basename(__FILE__,'.php'), '-'), 1);

require XOOPS_ROOT_PATH.'/modules/'.$plugin['dirname'].'/include/pical.inc.php';

?>