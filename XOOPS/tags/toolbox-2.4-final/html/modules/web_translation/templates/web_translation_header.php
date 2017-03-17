<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //
require_once('../../../mainfile.php');
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8">
<script language="JavaScript">
<!--
var f = parent.window.document.getElementById('targetFrame');
function changeTarget(num) {
	if (num == 1) {
		f.src = "<?php echo $_GET["sourceKey"]; ?>";
	} else {
		f.src = "<?php echo $_GET["targetKey"]; ?>";
	}
};
// -->
</script></head>
<body>
<div style="margin-top: 4px;">
<span style="margin-left:10px">
<?php echo _MI_WEB_CREATION_DISPLAY_ORIGINAL_WEB_PAGE; ?>:
</span>
<span>
<input type="radio" id="1" name="lang" onclick="javascript:changeTarget(1);"/>
	<?php echo $_GET["sourceLang"]; ?> (<?php echo _MI_WEB_CREATION_ORIGINAL_TEXT; ?>)
<input type="radio" id="2" name="lang" onclick="javascritp:changeTarget(2)" checked/>
	<?php echo $_GET["targetLang"]; ?> (<?php echo _MI_WEB_CREATION_TRANSLATION; ?>)
</span>
</div>
</body>
</html>
