<?php
header('Content-Type: text/css'); 
header('Cache-control: must-revalidate');
header('Expires: '.gmdate('D, d M Y H:i:s', time() + 3600).' GMT');

//read config
if( file_exists( dirname( __FILE__ ) . '/theme_config.php' ) ) {
  include_once( dirname( __FILE__ ) . '/theme_config.php' ) ;
}else{
  include_once( dirname( __FILE__ ) . '/theme_config.dist.php' ) ;
}
?>/*============================================================================*/
/* d3forum */
/* pico */
/*============================================================================*/
/* d3forum */

div.d3f_head,
div.d3f_head * {
<?php  echo '	color: ' . _THEME_CONFIG_H2_COLOR . " !important;\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_H2_BACKGROUND_COLOR . " !important;\n" ?>
}
h2.d3f_head{
	padding-top: 5px !important;
	padding-bottom: 5px !important;
}
div.d3f_wrap h2 {
	margin: 0 !important;
	padding: 0 !important;
	border: none !important;
	background-color: transparent !important;
	clear: none !important;
}
div.d3f_head img[src$="blank.gif"] {
	display: none;
}
div.d3f_info_val,div.d3f_head{
	overflow: auto;
}

/*============================================================================*/
/* pico */
#pico_body{
	padding: 0;
	margin: 0;
}
.bottom_of_content_body{
	text-align: right;
	padding: 10px 0 10px;
	margin: 0 0 10px;
	border-bottom: 1px #aaa solid;
}
.pico_form_table input[name="vpath"]{   width: 350px;}
.pico_form_table input[name="subject"]{ width: 350px;}
.pico_form_table textarea[name="body"]{ width: 350px;}
.pico_form_table textarea#htmlheader{   width: 350px;} /* lose by inline stylesheet */

/*============================================================================*/
/* d3blog */
/*
form#d3blogForm input[name="title"]{             width: 300px !important;}
form#d3blogForm textarea[name="contents"]{       width: 300px !important;}
form#d3blogForm textarea[name="trackback_url"]{  width: 300px !important;}
*/
