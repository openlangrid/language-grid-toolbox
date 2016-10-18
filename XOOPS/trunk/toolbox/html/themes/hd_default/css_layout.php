<?php
header('Content-Type: text/css'); 

//read config
if( file_exists( dirname( __FILE__ ) . '/theme_config.php' ) ) {
	include_once( dirname( __FILE__ ) . '/theme_config.php' ) ;
}else{
	include_once( dirname( __FILE__ ) . '/theme_config.dist.php' ) ;
}

//font-size
if( _THEME_CONFIG_FONTSIZE_UNIT == 'pixel' ) {
	$font_size = @$_COOKIE['hd_view_mode'] == 'large' ? _THEME_CONFIG_BASE_FONTSIZE * _THEME_CONFIG_FONTSIZE_MAGNIFICATION . 'px' : _THEME_CONFIG_BASE_FONTSIZE . 'px' ;
}else{
	$font_size = @$_COOKIE['hd_view_mode'] == 'large' ? _THEME_CONFIG_BASE_FONTSIZE * _THEME_CONFIG_FONTSIZE_MAGNIFICATION . '%' : _THEME_CONFIG_BASE_FONTSIZE . '%' ;
}
?>/*============================================================================*/
/* LAYOUT */
	/* Header */
	/* Breadcrumb */
	/* Content */
	/* Center LR Column */
	/* Left Right Column */
	/* Left Right Column Title */
	/* Left Right Column Content */
	/* Footer */
/* COLORING */
	/* hyper-link coloring */
	/* table coloring */
	/* XOOPS CSS - remaining for compatibility   */
/* XOOPS MAIN MENU */
	/* forms */
	/* XOOPS item */
	/* XOOPS Code & Quote */
	/* XOOPS com */
	/* XoopsComments */
	/* dhtmltextarea - just for menu skip */
/* XOOPS DIALOGUE MSG */
	/* errorMsg */
	/* confirmMsg */
	/* resultMsg */
	/* redirectMsg */
	/* ctrlMsg */
/* ANHCOR ICONS */
/* normal headings */
/* XOOPS_DIALOGUE */
/* XOOPS ADMIN SIDE */
	/* admin ctrl */
	/* admin header and footer */
	/* admin headings */
	/* admin left column */
	/* admin left column anchor */
	/* admin table */
	/* admin error */
	/* ModuleContents */
	/* table coloring for admin */
	/* block and modules control pannel */
/* HEADLINES SETTINGS */
/*============================================================================*/
/* LAYOUT */

body {
<?php  echo '	font-size: ' . $font_size . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_BODY_BACKGROUND_COLOR . ";\n" ?>
<?php  echo '	color: ' . _THEME_CONFIG_BODY_COLOR . ";\n" ?>
	text-align: center;
	font-family: Meiryo, Helvetica, sans-serif;
	line-height: 140%;
}
body.ShowBlockL0R0 #Wrapper {
	width: 100%;
}
body.ShowBlockL1R0 #Wrapper {
	width: 100%;
}
body.ShowBlockL0R1 #Wrapper {
	width: 100%;
	float: left;
<?php  echo '	margin-right: -' . _THEME_CONFIG_RIGHTCOLLUMN_WIDTH . ";\n" ?>
}
body.ShowBlockL1R1 #Wrapper {
	width: 100%;
	float: left;
<?php  echo '	margin-right: -' . _THEME_CONFIG_RIGHTCOLLUMN_WIDTH . ";\n" ?>
}

body.ShowBlockL0R0 #CenterColumn {
	width: 100%;
}
body.ShowBlockL1R0 #CenterColumn {
	width: 100%;
<?php  // echo '	margin-left: -' . _THEME_CONFIG_LEFTCOLLUMN_WIDTH . ";\n" ?>
}
body.ShowBlockL0R1 #CenterColumn {
<?php  echo '	margin-right: ' . _THEME_CONFIG_RIGHTCOLLUMN_WIDTH . ";\n" ?>
}
body.ShowBlockL1R1 #CenterColumn {
	width: 100%;
	float: right;
<?php  echo '	margin-left: -' . _THEME_CONFIG_LEFTCOLLUMN_WIDTH . ";\n" ?>
}

body.ShowBlockL1R0 #CenterWrapper {
<?php //echo '	margin-left: ' . _THEME_CONFIG_LEFTCOLLUMN_WIDTH . ";\n" ?>
}
body.ShowBlockL1R1 #CenterWrapper {
<?php  echo '	margin-right: ' . _THEME_CONFIG_RIGHTCOLLUMN_WIDTH . ";\n" ?>
<?php  echo '	margin-left: ' . _THEME_CONFIG_LEFTCOLLUMN_WIDTH . ";\n" ?>
}

div#Container {
	text-align: left;
	margin: 0 auto;
	padding: 0;
<?php
if( _THEME_CONFIG_MAIN_SIDE_BORDER != '' ){
	echo '	border-left:' . _THEME_CONFIG_MAIN_SIDE_BORDER . ";\n" ;
	echo '	border-right:' . _THEME_CONFIG_MAIN_SIDE_BORDER . ";\n" ;
}
echo '	width: ' . _THEME_CONFIG_MAIN_WIDTH . ";\n" ;
echo '	color: ' . _THEME_CONFIG_CONTAINER_COLOR . ";\n" ;
echo '	background-color: ' . _THEME_CONFIG_CONTAINER_BACKGROUND_COLOR . ";\n" ;
?>
}

div#CenterWrapper {
	padding: 10px;
}

/* Header */
div#Header {
	width: 100%;
	height: 100px;
	border-bottom: #b1b1b1 solid 2px;
	font-family: Verdana, sans-serif;
<?php  echo '	color: ' . _THEME_CONFIG_HEADER_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_HEADER_BACKGROUND_COLOR . ";\n" ?>
}
div#Header div#Sitename {
	font-weight: bold;
	padding: 22px 0 10px 10px;
	font-size: 210%;
	line-height: 100%;
}
div#Header div#Logo {
	float: left;
	padding: 10px 25px 0 20px;
}
div#Slogan {
	font-size: 120%;
	padding: 0 0 0 10px;
}
div#Header a:link,
div#Header a:visited {
<?php  echo '	color: ' . _THEME_CONFIG_HEADER_COLOR . ";\n" ?>
	text-decoration: none;
}

/* Breadcrumb */
div#breadcrumb,
div#theme_breadcrumbs {
	width: 100%;
	margin-bottom: 5px;
	border-bottom: #DDD solid 1px;
}

/* Content */
div#CenterColumn  h2.BlockTitle {
/*  border-bottom: #DDD dashed 1px; */
}
div#CenterColumn div.BlockContent,
div#ModuleContents {
	font-size: 100%;
	line-height: 1.6;
	padding: 0;
	clear: both;
}
p {
	margin: 10px 0;
/*	clear: both; */
	margin-bottom: 10px;
}

/* Center LR Column */
div#CenterLColumn {
	float: left;
	width: 49%;
}
div#CenterRColumn {
	float: right;
	width: 49%;
}

div.CenterCblock {
	clear: both;
	margin-bottom: 20px;
}
div.CenterLblock {
	clear: both;
}
div.CenterRblock {
	clear: both;
}

/* Left Right Column */
div#LeftColumn {
	clear:both;
	overflow: auto; /* ugly? */
<?php  echo '	width: ' . _THEME_CONFIG_LEFTCOLLUMN_WIDTH . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_COLUMN_BACKGROUND_COLOR . ";\n" ?>
}
div#RightColumn {
<?php  echo '	width: ' . _THEME_CONFIG_RIGHTCOLLUMN_WIDTH . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_COLUMN_BACKGROUND_COLOR . ";\n" ?>
}

div.LeftBlock,
div.RightBlock {
	padding: 10px 0;
}

/* Left Right Column Title */
div#LeftColumn h2.BlockTitle,
div#RightColumn h2.BlockTitle {
<?php  echo '	color: ' . _THEME_CONFIG_COLUMN_TITLE_DARK_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_COLUMN_TITLE_DARK_BACKGROUND_COLOR . ";\n" ?>
	border-bottom: 1px #a0a0a0 solid;
	border-top: 1px #a0a0a0 solid;
	margin: 0 0 5px 0;
	padding: 3px 10px;
	font-size: 110%;
}
div#LeftColumn h3,
div#RightColumn h3,
div#LeftColumn h4,
div#RightColumn h4 {
	margin: 5px 0;
	font-size: 100%;
}

/* Left Right Column Content */
div#LeftColumn .BlockContent,
div#RightColumn .BlockContent,
div#LeftColumn .BlockContent p,
div#RightColumn .BlockContent p {
	text-align: left;
}
div#LeftColumn .BlockContent,
div#RightColumn .BlockContent{
	padding: 0 10px;
}

/* Footer */
div#BacktoTop {
	text-align: right;
	margin: 10px 0px 5px;
	clear: both;
	font-size: 80%;
}
div#Footer {
	clear: both;
	width: 100%;
	margin: 0 auto;
	border-bottom: 1px #a0a0a0 solid;
	border-top: 1px #a0a0a0 solid;
<?php  echo '	color: ' . _THEME_CONFIG_FOOTER_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_FOOTER_BACKGROUND_COLOR . ";\n" ?>
}
div#Footer p {
	margin: 0 10px;
	padding: 10px 0;
	text-align: left;
}

div#Banner {
	clear: both;
	width: 100%;
	margin: 0 auto;
	padding: 20px 0;
}

/*============================================================================*/
/* clearfix http://www.positioniseverything.net/ */
.clearfix:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}

.clearfix {
/*	display: inline-table; [hd: 0788] */
	display: inline-block;
}

/* Hides from IE-mac \*/
* html .clearfix {height: 1%;}
.clearfix {display: block;}
/* End hide from IE-mac */

/*============================================================================*/
/* COLORING */
/* hyper-link coloring */

a:link   { color: #336699;}
a:visited { color: #9966cc;}
a:active { color: #cc6666;}
a:hover  { color: #cc6666;}
a:hover  { text-decoration: none;}
div#LeftColumn  a:link { color: #336699;}
div#RightColumn a:link { color: #336699;}
div#LeftColumn  a:visited { color: #336699;}
div#RightColumn a:visited { color: #336699;}
div#LeftColumn  a:hover { color: #336699;}
div#RightColumn a:hover { color: #336699;}
.ctrlMsg a:link { color: #336699;}
.ctrlMsg a:visited { color: #336699;}
.ctrlMsg a:hover { color: #336699;}

/*============================================================================*/
/* table coloring */

.head {
<?php  echo '	color: ' . _THEME_CONFIG_THEAD_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_THEAD_BACKGROUND_COLOR . ";\n" ?>
}
.even,
tr.even td {
<?php  echo '	color: ' . _THEME_CONFIG_EVEN_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_EVEN_BACKGROUND_COLOR . ";\n" ?>
}
.odd,
tr.odd td {
<?php  echo '	color: ' . _THEME_CONFIG_ODD_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ODD_BACKGROUND_COLOR . ";\n" ?>
}
.foot {}

table.outer th,
table.outer1 th,
table.outer2 th,
table.outer3 th{
<?php  echo '	color: ' . _THEME_CONFIG_TH_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_TH_BACKGROUND_COLOR . ";\n" ?>
}
table.outer thead tr th,
table.outer1 thead tr th,
table.outer2 thead tr th,
table.outer3 thead tr th {
<?php  echo '	color: ' . _THEME_CONFIG_THEAD_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_THEAD_BACKGROUND_COLOR . ";\n" ?>
}
table.outer thead tr td,
table.outer1 thead tr td,
table.outer2 thead tr td,
table.outer3 thead tr td {
<?php  echo '	background-color: ' . _THEME_CONFIG_THEAD_TD_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_THEAD_TD_BACKGROUND_COLOR . ";\n" ?>
}

/*============================================================================*/
/* XOOPS CSS - remaining for compatibility   */
/* img {border: 0;} */

#xoopsHiddenText {
	visibility: hidden;
	color: #000000;
	font-weight: normal;
	font-style: normal;
	text-decoration: none;
}
.pagneutral {
	font-size: 10px;
	width: 16px;
	height: 19px;
	text-align: center;
	background-image: url(./images/pagneutral.gif);
}
.pagact {
	font-size: 10px;
	width: 16px;
	height: 19px;
	text-align: center;
	background-image: url(./images/pagact.gif);
}
.paginact {
	font-size: 10px;
	width: 16px;
	height: 19px;
	text-align: center;
	background-image: url(./images/paginact.gif);
}

/*============================================================================*/
/* XOOPS MAIN MENU */
#mainmenu,
#usermenu {
	margin: 0 -10px;
	padding: 0;
	text-align: left;
}
#mainmenu li,
#usermenu li {
	display: inline;
}
#mainmenu a {
	text-decoration: none;
	display: block;
	border-bottom: #DDD solid 1px;
	margin: 0 1px;
}
#mainmenu a:hover {background-color: #ccc}
#mainmenu a.menuTop {padding: 5px 10px;}
#mainmenu a.menuMain {padding: 5px 10px;}
#mainmenu a.menuSub {padding: 5px 5px 5px 15px;}

#usermenu a {
	text-decoration: none;
	display: block;
	border-bottom: #DDD solid 1px;
	margin: 0 1px;
	padding: 5px 10px;
}
#usermenu a:hover { background-color: #ccc}
#usermenu a.menuTop {}
#usermenu a.highlight {
	background-color: #FCC;
}

/* forms */
#legacy_xoopsform_block_uname,
#legacy_xoopsform_block_pass,
#legacy_xoopsform_query_block,
#xoops_theme_select{
	width: 120px;
}

/* XOOPS item */
.item {}
.itemHead {
	padding: 3px;
	background-color: #666;
	color: #FFF;
}
.itemInfo {
	text-align: right;
	padding: 3px;
	background-color: #EFEFEF;
}
.itemTitle a {
	font-weight: bold;
	font-variant: small-caps;
	color: #FFF;
	background-color: transparent;
}
.itemPoster {
	font-size: 90%;
}
.itemPostDate {
	font-size: 90%;
}
.itemStats {
	font-size: 90%;
}
.itemBody {
	padding-left: 0px;
}
.itemText {
	margin-top: 5px;
	margin-bottom: 5px;
	line-height: 100%;
}
.itemText: first-letter {}
.itemFoot {
	text-align: right;
	padding: 3px;
	background-color: #EFEFEF;
}
.itemAdminLink {
	font-size: 90%;
}
.itemPermaLink {
	font-size: 90%;
}
/* XOOPS Code & Quote */
div.xoopsCode {
<?php  echo '	color: ' . _THEME_CONFIG_CODE_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_CODE_BACKGROUND_COLOR . ";\n" ?>
	border: 1px inset #000080;
	font-family: "Courier New",Courier,monospace;
	padding: 0px 6px 6px 6px;
}
div.xoopsQuote {
<?php  echo '	color: ' . _THEME_CONFIG_QUOTE_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_QUOTE_BACKGROUND_COLOR . ";\n" ?>
	border: #336699 solid 1px;
	padding: 3px;
}

/* XOOPS com */
.comTitle {
	font-weight: bold;
	margin-bottom: 2px;
}
.comText {
	padding: 2px;
}
.comUserStat {
	font-size: 10px;
	color: #2F5376;
	font-weight: bold;
	border: 1px solid silver;
	background-color: #ffffff;
	margin: 2px;
	padding: 2px;
}
.comUserStatCaption {
	font-weight: normal;
}
.comUserStatus {
	margin-left: 2px;
	margin-top: 10px;
	color: #2F5376;
	font-weight: bold;
	font-size: 10px;
}
.comUserRank {
	margin: 2px;
}
.comUserRankText {
	font-size: 10px;font-weight: bold;
}
.comUserRankImg {
	border: 0;
}
.comUserName {}
.comUserImg {
	margin: 2px;
}
.comDate {
	font-weight: normal;
	font-style: italic;
	font-size: smaller;
}
.comDateCaption {
	font-weight: bold;
	font-style: normal;
}

/* XoopsComments */
div.XoopsCommentsInfo{}

ul#XoopsCommentsNavigation{
	padding: 0;
	margin: 5px 0;
}
ul#XoopsCommentsNavigation li{
	display: inline;
}
ul.XoopsCommentsThread{
	padding: 0;
	margin: 5px 0 15px;
}
ul.XoopsCommentsThread li{
	padding: 2px 0;
	list-style: none;
}
ul.XoopsCommentsThread li img{
	vertical-align: middle;
}
h3.XoopsCommentsTitle{
	padding: 3px 6px;
	background-color: #ddd;
}
h3.XoopsCommentsTitle img{
	vertical-align: middle;
}
h4.XoopsCommentsThreadTitle{
	border-bottom: 1px #aaa solid;
}
div.XoopsCommentsText{
	clear: both;
	padding: 3px 6px;
}
div.XoopsCommentsSub{
	position: relative;
	padding: 2px 6px;
	background-color: #eee;
}
div.XoopsCommentsCtrl{
	position: absolute;
	top: 2px;
	right: 6px;
}
hr.XoopsCommentsDivision{
	margin: 10px 0;
}

/* dhtmltextarea - just for menu skip */
.dhtmltextarea{
	position: relative;
}

/*============================================================================*/
/* XOOPS DIALOGUE MSG */
/* errorMsg */
.errorMsg {
<?php  echo '	color: ' . _THEME_CONFIG_ERROR_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ERROR_BACKGROUND_COLOR . ";\n" ?>
	text-align: left;
	border-top: 1px solid #aaa;
	border-left: 1px solid #aaa;
	border-right: 1px solid #aaa;
	border-bottom: 1px solid #aaa;
	font-weight: bold;
	padding: 15px 25px 10px;
}
/* confirmMsg */
.confirmMsg {
<?php  echo '	color: ' . _THEME_CONFIG_COMFIRM_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_COMFIRM_BACKGROUND_COLOR . ";\n" ?>
	text-align: left;
	border-top: 1px solid #aaa;
	border-left: 1px solid #aaa;
	border-right: 1px solid #aaa;
	border-bottom: 1px solid #aaa;
	font-weight: bold;
	padding: 15px 25px 10px;
}
/* resultMsg */
.resultMsg {
<?php  echo '	color: ' . _THEME_CONFIG_RESULT_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_RESULT_BACKGROUND_COLOR . ";\n" ?>
	text-align: left;
	border-top: 1px solid #aaa;
	border-left: 1px solid #aaa;
	border-right: 1px solid #aaa;
	border-bottom: 1px solid #aaa;
	font-weight: bold;
	padding: 15px 25px 10px;
}
/* redirectMsg */
.redirectMsg {
<?php  echo '	color: ' . _THEME_CONFIG_REDIRECT_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_REDIRECT_BACKGROUND_COLOR . ";\n" ?>
	text-align: left;
	border-top: 1px solid #aaa;
	border-left: 1px solid #aaa;
	border-right: 1px solid #aaa;
	border-bottom: 1px solid #aaa;
	font-weight: bold;
	padding: 15px 25px 10px;
}
/* ctrlMsg */
.ctrlMsg {
<?php  echo '	color: ' . _THEME_CONFIG_CTRL_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_CTRL_BACKGROUND_COLOR . ";\n" ?>
	text-align: left;
	border-top: 1px solid #aaa;
	border-left: 1px solid #aaa;
	border-right: 1px solid #aaa;
	border-bottom: 1px solid #aaa;
	padding: 15px 25px 10px;
}

/*============================================================================*/
/* ANHCOR ICONS */

#CenterColumn a[href^="http:"]:after{
	margin: 0 2px;
	content: url(images/_common/ouklink.png);
}
#CenterColumn a[href^="https:"]:after {
	margin: 0 2px;
	content: url(images/_common/lock.png);
}
<?php
echo '#CenterColumn a[href^="http://'.$this_site.'"]:after {'."\n" ;
echo '	margin: 0;'."\n" ;
echo '	content: "";'."\n" ;
echo '}' ;
?>

/*============================================================================*/
/* XOOPS_DIALOGUE */
.xoops_dialogue{
<?php  echo '	color: ' . _THEME_CONFIG_DIALOGUE_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_DIALOGUE_BACKGROUND_COLOR . ";\n" ?>
}
#dialogue_title{
	padding: 3px 5px;
	text-align: left;
	font-weight: bold;
<?php  echo '	color: ' . _THEME_CONFIG_DIALOGUE_TITLE_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_DIALOGUE_TITLE_BACKGROUND_COLOR . ";\n" ?>
}
#dialogue_title img{
	vertical-align: middle;
	margin-right: 5px;
}

/*============================================================================*/
/* XOOPS ADMIN SIDE */

/* admin ctrl */
#admin_ctrl{
	border-bottom: 1px #666 solid;
	background-color: #666;
	text-align: right;
	padding: 5px 10px;
	line-height: 100%;
}
#admin_ctrl a{
	color: #fff;
	font-weight: bold;
	text-decoration: none;
}
#admin_ctrl a:hover{
	color: #113;
}
#admin_ctrl a:before {
	margin: 0 3px;
	content: url(images/_common/menulist.gif);
}
#admin_ctrl a.direct:before {
	margin: 0 2px;
	content: url(images/_common/menulist_direct.gif);
}
#admin_ctrl a.end_of_shortcut{
	margin-right: 10px;
}

/* admin header and footer */
.AdminMode #Container{
	width: 100%;
	border: none;
}
.AdminMode #Header{
	height: 60px;
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_BACKGROUND_COLOR . ";\n" ?>
}
.AdminMode #Header #Logo{
	margin: 0;
	padding: 0 10px;
}
.AdminMode div#Header div#Sitename {
	font-size: 150%;
<?php  echo '	color: ' . _THEME_CONFIG_ADMIN_COLOR . ";\n" ?>
}
.AdminMode div#Footer {
<?php  echo '	color: ' . _THEME_CONFIG_ADMIN_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_BACKGROUND_COLOR . ";\n" ?>
}
.AdminMode div#Footer p a{
<?php  echo '	color: ' . _THEME_CONFIG_ADMIN_COLOR . ";\n" ?>
}

/* admin headings */
.AdminMode #CenterWrapper h2 {
	line-height: 100%;
	font-size: 110%;
	padding: 6px;
	border: none;
	margin: 0;
<?php  echo '	color: ' . _THEME_CONFIG_ADMIN_HEADING_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_HEADING_BACKGROUND_COLOR . ";\n" ?>
}
.AdminMode #CenterWrapper h3 {
	line-height: 100%;
	font-size: 110%;
	padding: 6px;
	border-bottom: none !important;
<?php  echo '	border-left: 8px' . _THEME_CONFIG_ADMIN_HEADING_BACKGROUND_COLOR . " solid;\n" ?>
}
.AdminMode #CenterWrapper h4{
	margin: 10px 0 0;
<?php  echo '	border-bottom: 1px' . _THEME_CONFIG_ADMIN_HEADING_BACKGROUND_COLOR . " solid;\n" ?>
}

/* admin left column */
.AdminMode #LeftColumn h2.BlockTitle{
	font-size: 110%;
	margin: -10px 0 0;
	padding: 6px 10px;
	line-height: 100%;
<?php  echo '	color: ' . _THEME_CONFIG_ADMIN_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_LEFTCOLUMN_HEADING_BACKGROUND_COLOR . ";\n" ?>
}
.AdminMode #LeftColumn div.adminmenu_block_main{
	margin: 0 -10px;
	clear: left;
	border-top: 1px #aaa solid;
}
.AdminMode div.adminmenu_block_main img{
	padding: 4px 4px 2px 5px;
	float: left;
}

/* admin left column anchor */
.AdminMode #LeftColumn div.adminmenu_block_main a.adminmenu_block_main_module_name{
	font-weight: bold;
	color: #222;
	padding: 4px;
	display: block;
	text-decoration: none;
}
.AdminMode #LeftColumn div ul,
.AdminMode #LeftColumn div li{
	list-style: none;
	margin:0 -5px;
	padding:0;
<?php if($ua_type='IE') {echo '	border: 1px #fff solid;';} ?>
}
.AdminMode #LeftColumn div li a{
	color: #222;
	padding: 3px 5px;
	display: block;
	text-decoration: none;
}
.AdminMode #LeftColumn div a:hover{
	color: #fff;
	background-color: #999;
}

/* admin table */
.AdminMode table.outer{
	margin: 10px 0;
	border-top: 1px #bbb solid;
	border-left: 1px #bbb solid;
	border-collapse: collapse;
}
.AdminMode table.outer th{
	text-align: center;
<?php  echo '	color: ' . _THEME_CONFIG_ADMIN_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_HEADING_BACKGROUND_COLOR . ";\n" ?>
}
.AdminMode table.outer th,
.AdminMode table.outer td {
	border-bottom: 1px #bbb solid;
	border-right: 1px #bbb solid;
}
.legacy_list_description{
	color: #333;
	font-size: 85%;
}


/* admin error */
div.error{
	color: #900;
	background-color: #eee;
	padding: 5px;
}
/* ModuleContents */
form.odd{
	border				:1px #aaa solid;
}

/* table coloring for admin */
.AdminMode .head {
<?php  echo '	color: ' . _THEME_CONFIG_ADMIN_TH_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_TH_BACKGROUND_COLOR . ";\n" ?>
}
.AdminMode .even,
.AdminMode tr.even td {
<?php  echo '	color: ' . _THEME_CONFIG_ADMIN_EVEN_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_EVEN_BACKGROUND_COLOR . ";\n" ?>
}
.AdminMode .odd,
.AdminMode tr.odd td {
<?php  echo '	color: ' . _THEME_CONFIG_ADMIN_ODD_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_ODD_BACKGROUND_COLOR . ";\n" ?>
}
.AdminMode .foot {}

.AdminMode table.outer th {
<?php  echo '	color: ' . _THEME_CONFIG_ADMIN_TH_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_TH_BACKGROUND_COLOR . ";\n" ?>
}
.AdminMode table.outer thead tr th {
<?php  echo '	color: ' . _THEME_CONFIG_ADMIN_THEAD_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_THEAD_BACKGROUND_COLOR . ";\n" ?>
}
.AdminMode table.outer thead tr td {
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_THEAD_TD_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_THEAD_TD_BACKGROUND_COLOR . ";\n" ?>
}

/* block and modules control pannel */
.AdminMode td.legacy_blockside div {
	display: inline;
	padding: 1px;
}
.AdminMode td.legacy_blockside div *{
	vertical-align: middle;
}
.AdminMode td.legacy_blockside div.active {
	background-color: #edd;
}
.AdminMode td.legacy_blockside,
.AdminMode td.blockposition {
	white-space: nowrap !important;
}
.AdminMode tr.active td{
	background-color: #edd;
}
.AdminMode .tips {
<?php  echo '	color: ' . _THEME_CONFIG_ADMIN_TIPS_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_ADMIN_TIPS_BACKGROUND_COLOR . ";\n" ?>
<?php  echo _THEME_CONFIG_ADMIN_TIPS_CSS ?>
	padding: 10px 15px;
}

/*============================================================================*/
/* HEADLINES SETTINGS */
/* normal headings */
h1,
h2,
h3,
h4,
h5,
h6 {
	font-family: Verdana, sans-serif;
}

div#CenterColumn h1 {
<?php  echo '	color: ' . _THEME_CONFIG_H1_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_H1_BACKGROUND_COLOR . ";\n" ?>
	font-size: 150%;
	margin: 0 0 10px;
	padding: 0;
}
div#CenterColumn h2 {
<?php  echo '	color: ' . _THEME_CONFIG_H2_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_H2_BACKGROUND_COLOR . ";\n" ?>
	clear: both;
	font-size: 110%;
	margin: 15px 0 10px;
	padding: 4px 15px;
	border-top: 1px #a0a0a0 solid;
	border-bottom: 1px #a0a0a0 solid;
}
div#CenterColumn h3 {
	font-size: 110%;
	margin: 15px 0 5px;
	padding: 2px 6px;
	border-bottom: 1px  #999 solid;
<?php  echo '	color: ' . _THEME_CONFIG_H3_COLOR . ";\n" ?>
<?php  echo '	background-color: ' . _THEME_CONFIG_H3_BACKGROUND_COLOR . ";\n" ?>
}
h4,
h5 {
	font-size: 110%;
}
