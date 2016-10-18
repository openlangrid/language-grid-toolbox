//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides user management
// functions.
// Copyright (C) 2009  NICT Language Grid Project
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
//var url = "subProfileRead.php"
//new Ajax.Request(url, {
//	method : "GET",
//	onSuccess : setValue,
//	onFailure : function(httpObj) {
//	alert("error");
//	}
//});

function setValue(httpObj) {
	var resTxt=httpObj.responseText.replace(/@left@/g,"[");
	resTxt=resTxt.replace(/@right@/g,"]");
	var obj = resTxt.evalJSON();
	var c = 3;
//	var keys = ["_display", "_title", "_length", "_default"];
//	var keysLen = keys.length;
	for ( var i = 1; i <= c; i++) {
//		for ( var j = 0; j < keysLen; j++) {
//			var key = "sub" + i + keys[j]
//
//		}
		var val = null;
		if (obj["sub" + i + "_display"] == 1) {
			$("sub" + i + "Yes").checked = true;
			$("sub" + i + "No").checked = false;
		}

		if(obj["sub" + i + "_title"]!==undefined) {
			val=obj["sub" + i + "_title"];
		}
		else {
			val = "";
		}
		$("sub" + i + "Title").value = val;

		if(obj["sub" + i + "_length"]!==undefined) {
			val = obj["sub" + i + "_length"];
		}
		else {
			val=0;
		}
		$("sub" + i + "Length").value=val;

		if(obj["sub" + i + "_default"]!==undefined) {
			val=obj["sub" + i + "_default"];
		}
		else {
			val="";
		}
		$("sub" + i + "Default").value = val;
	}
}

function validateValue(){
	var c = 4;
	var flg=true;
	for ( var i = 1; i < c; i++) {
		// alert(obj["sub"+i+"_display"]);
		if ($("sub" + i + "Yes").checked) {
			if($("sub" + i + "Title").value ==""){
				flg=false;
				break;
			}
		}
		var val=parseInt($("sub" + i + "Length").value);
		if(isNaN(val)) val=0;
		$("sub" + i + "Length").value=val;
	}
	if(flg) document.frmUserProfile.submit();
	else alert(Const.Message.notInputTitle);

}