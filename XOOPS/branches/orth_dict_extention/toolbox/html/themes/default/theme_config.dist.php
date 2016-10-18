<?php
//Personal Information Permission
//it was vulnerabe spec. to change /themes/hd_default/templates/user_userinfo.html
//define('_THEME_CONFIG_ALLOW_TO_SHOW_GUEST','0'); //trun 1 to allow.

//width
define('_THEME_CONFIG_MAIN_WIDTH','100%');//acceptable 'px'
define('_THEME_CONFIG_MAIN_SIDE_BORDER','');//ex) 1px #aaa solid
define('_THEME_CONFIG_LEFTCOLLUMN_WIDTH','220px');
define('_THEME_CONFIG_RIGHTCOLLUMN_WIDTH','220px');

//colors
define('_THEME_CONFIG_HEADER_COLOR','#111');
define('_THEME_CONFIG_HEADER_BACKGROUND_COLOR','#fff'); //acceptable transparent
define('_THEME_CONFIG_BODY_COLOR','#111');//text color
define('_THEME_CONFIG_BODY_BACKGROUND_COLOR','#fff');
define('_THEME_CONFIG_H1_COLOR','#111');
define('_THEME_CONFIG_H1_BACKGROUND_COLOR','#fff');
define('_THEME_CONFIG_H2_COLOR','#111');
define('_THEME_CONFIG_H2_BACKGROUND_COLOR','#f8f8f8');
define('_THEME_CONFIG_H3_COLOR','#111');
define('_THEME_CONFIG_H3_BACKGROUND_COLOR','#fff');
define('_THEME_CONFIG_CONTAINER_COLOR','#111');
define('_THEME_CONFIG_CONTAINER_BACKGROUND_COLOR','#fff');
define('_THEME_CONFIG_COLUMN_TITLE_DARK_COLOR','#111');
define('_THEME_CONFIG_COLUMN_TITLE_DARK_BACKGROUND_COLOR','#f8f8f8');
define('_THEME_CONFIG_COLUMN_COLOR','#111');
define('_THEME_CONFIG_COLUMN_BACKGROUND_COLOR','#fff');
define('_THEME_CONFIG_FOOTER_COLOR','#111');
define('_THEME_CONFIG_FOOTER_BACKGROUND_COLOR','#f8f8f8');
define('_THEME_CONFIG_ERROR_COLOR','#111');
define('_THEME_CONFIG_ERROR_BACKGROUND_COLOR','#fcc');
define('_THEME_CONFIG_COMFIRM_COLOR','#136C99');
define('_THEME_CONFIG_COMFIRM_BACKGROUND_COLOR','#ddffdf');
define('_THEME_CONFIG_CTRL_COLOR','#111');
define('_THEME_CONFIG_CTRL_BACKGROUND_COLOR','#fafafa');
define('_THEME_CONFIG_RESULT_COLOR','#333');
define('_THEME_CONFIG_RESULT_BACKGROUND_COLOR','#ccc');
define('_THEME_CONFIG_REDIRECT_COLOR','#111');
define('_THEME_CONFIG_REDIRECT_BACKGROUND_COLOR','#fafafa');
define('_THEME_CONFIG_CODE_COLOR','#111');
define('_THEME_CONFIG_CODE_BACKGROUND_COLOR','#fafafa');
define('_THEME_CONFIG_QUOTE_COLOR','#111');
define('_THEME_CONFIG_QUOTE_BACKGROUND_COLOR','#fafafa');

//color settings for administration mode
define('_THEME_CONFIG_ADMIN_COLOR','#fff');
define('_THEME_CONFIG_ADMIN_BACKGROUND_COLOR','#222');
define('_THEME_CONFIG_ADMIN_HEADING_COLOR','#fff');
define('_THEME_CONFIG_ADMIN_HEADING_BACKGROUND_COLOR','#666');
define('_THEME_CONFIG_ADMIN_LEFTCOLUMN_HEADING_BACKGROUND_COLOR','#222');
define('_THEME_CONFIG_ADMIN_TIPS_COLOR','#111');
define('_THEME_CONFIG_ADMIN_TIPS_BACKGROUND_COLOR','#fafafa');
define('_THEME_CONFIG_ADMIN_TIPS_CSS','border: 1px #aaa solid;');

//colors of table
define('_THEME_CONFIG_THEAD_COLOR','#111');
define('_THEME_CONFIG_THEAD_BACKGROUND_COLOR','#eee');
define('_THEME_CONFIG_THEAD_TD_COLOR','#111');
define('_THEME_CONFIG_THEAD_TD_BACKGROUND_COLOR','#f8f8f8');
define('_THEME_CONFIG_TH_COLOR','#111');
define('_THEME_CONFIG_TH_BACKGROUND_COLOR','#f8f8f8');
define('_THEME_CONFIG_ODD_COLOR','#111');
define('_THEME_CONFIG_ODD_BACKGROUND_COLOR','#fff');
define('_THEME_CONFIG_EVEN_COLOR','#111');
define('_THEME_CONFIG_EVEN_BACKGROUND_COLOR','#f8f8f8');
define('_THEME_CONFIG_TFOOT_COLOR','#111');
define('_THEME_CONFIG_TFOOT_BACKGROUND_COLOR','#ccc');

//colors of table - admin section
define('_THEME_CONFIG_ADMIN_THEAD_COLOR','#111');
define('_THEME_CONFIG_ADMIN_THEAD_BACKGROUND_COLOR','#666');
define('_THEME_CONFIG_ADMIN_THEAD_TD_COLOR','#111');
define('_THEME_CONFIG_ADMIN_THEAD_TD_BACKGROUND_COLOR','#666');
define('_THEME_CONFIG_ADMIN_TH_COLOR','#fff');
define('_THEME_CONFIG_ADMIN_TH_BACKGROUND_COLOR','#666');
define('_THEME_CONFIG_ADMIN_ODD_COLOR','#111');
define('_THEME_CONFIG_ADMIN_ODD_BACKGROUND_COLOR','#fafafa');
define('_THEME_CONFIG_ADMIN_EVEN_COLOR','#111');
define('_THEME_CONFIG_ADMIN_EVEN_BACKGROUND_COLOR','#ddd');
define('_THEME_CONFIG_ADMIN_TFOOT_COLOR','#111');
define('_THEME_CONFIG_ADMIN_TFOOT_BACKGROUND_COLOR','#ccc');


//dialogue window
define('_THEME_CONFIG_DIALOGUE_COLOR','#111');
define('_THEME_CONFIG_DIALOGUE_BACKGROUND_COLOR','#eee');
define('_THEME_CONFIG_DIALOGUE_TITLE_COLOR','#fff');
define('_THEME_CONFIG_DIALOGUE_TITLE_BACKGROUND_COLOR','#666');

//font-size
define('_THEME_CONFIG_FONTSIZE_UNIT','percentage');//use 'percentage' or 'pixel'
define('_THEME_CONFIG_BASE_FONTSIZE','95');
define('_THEME_CONFIG_FONTSIZE_MAGNIFICATION','1.5');

//verify-v1 - for Google Webmaster Tools
define('_THEME_CONFIG_VERIFY_V1','');

//User Agent - from GIJOE's dbtheme
if( stristr( $_SERVER['HTTP_USER_AGENT'] , 'Opera' ) ) {
	$ua_type = 'Opera' ;
} else if( stristr( $_SERVER['HTTP_USER_AGENT'] , 'MSIE' ) ) {
	$ua_type = 'IE' ;
} else {
	$ua_type = 'NN' ;
}

//this site
$this_site  = htmlspecialchars( $_SERVER['SERVER_NAME'], ENT_QUOTES ) ;

?>