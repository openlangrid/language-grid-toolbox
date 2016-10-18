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
function SearchUser() {
	 $("containerSearchImg").style.visibility="visible";
	var param = "";
	param = "searchWord=" + encodeURIComponent($("txtSearchWord").value);
	param += "&searchType="+$("selectSearchType").options[$("selectSearchType").selectedIndex].value;
	var url = "../search/search.php";
	new Ajax.Request(
			url,
			{
				method : "POST",
				parameters : param,
				onSuccess : function(httpObj) {
					var obj = httpObj.responseText.evalJSON();
					setTable(obj);
					TableSort.init();
				},
				onFailure : function(httpObj) {
					var msg = Const.Message.error_occured + "\n";
					msg += httpObj.status + "\n";
					msg += httpObj.statusText;
					alert(msg);
				},
				onComplete : function(){
					$("containerSearchImg").style.visibility="hidden";
				}
			});

	return false;
}

function setTable(obj) {
	var tblHTML = [];
	var arrContentsName = [ "uname", "name" ];
	var arrTitle = [];
	var tableContainer = $("containerTableResult");
	$("result_title").innerHTML = Const.Label.search_result;
	if (obj["contents"].length < 1) {
		tableContainer.innerHTML = Const.Message.search_not_match;
		return false;
	}
	for ( var i = 0; i < obj["title"].length; i++) {
		arrTitle.push(obj["title"][i]);
	}
    var defs = obj['contents'][0]['definitions'];
    for ( i = 0; i < defs.length; i++ ) {
        arrContentsName.push(defs[i]['field_name']);
    }
	tblHTML.push('<table id="userList-table" class="load-resource-body-table body_title" cellspacing="0" cellpadding="0" border="0" width="820">');

	//header
	tblHTML.push('<thead id="userList-head">');
	tblHTML.push("<tr class='searchResultTitle'>");
	tblHTML.push('<td class="tableBlankCell" style="border-width: 0px;"></td>');

	for (i = 0; i < arrContentsName.length; i++) {
		var label = Const.Label["title_" + arrContentsName[i]];
		if (label != undefined) {
			var td = "<td class='nocase' style='text-align: center;'>" +  label  +  "</td>";
			tblHTML.push(td);
		}
	}
	for (i = 0; i < arrTitle.length; i++) {
		var td = '<td class="nocase" style="text-align: center;">' +  arrTitle[i].escapeHTML() +  "</td>";
		tblHTML.push(td);
	}
	tblHTML.push("</tr>");
	tblHTML.push("</thead>");


	tblHTML.push('<tbody id="userList-body">');
	for (i = 0; i < obj["contents"].length; i++) {
		tblHTML.push('<tr>');
		var n = i + 1;
		var indexTD = '<td class="userSearchTD" width="20px">' + n + '</td>';
		tblHTML.push(indexTD);
		var contents = obj["contents"][i];

		for ( var j = 0; j < arrContentsName.length; j++) {
			var contentsName = arrContentsName[j];
			var contentsText = "";
			if ((contentsName == "uname") || (contentsName == "name")){
				contentsText = contents[contentsName];
			}
			else {
				contentsText = contents["values"][contentsName];
			}

			if (contentsText == undefined) continue;

				var arrHilight = null;
				if (contents["hilight"] != undefined) {
					arrHilight = contents["hilight"][contentsName];
				}
				var tmp = "";
				var startIndex = 0;
				if (arrHilight) {
					for ( var k = 0; k < arrHilight.length; k += 2) {
						var start = arrHilight[k];
						var count = arrHilight[k + 1];
						var txtStart = contentsText.substr(startIndex, start - startIndex).escapeHTML();
						var hilightTxt = contentsText.substr(start, count).escapeHTML();
						tmp += txtStart + "<span style='background-color:red;color:yellow;'>" + hilightTxt + "</span>";
						startIndex = start + count ;
					}
					var endText = contentsText.substr(startIndex).escapeHTML();
					contentsText = tmp + endText;
				}
				else {
					contentsText = contentsText.escapeHTML();
				}
				if (contentsName == "uname") {
					contentsText = "<a href='" + Const.Label.xoops_url + "/userinfo.php?uid=" + contents["id"] + "'>" + contentsText + "</a>";
				}

			tblHTML.push('<td class="userSearchTD">' + contentsText + "</td>");
		}
		tblHTML.push("</tr>");
	}

	tblHTML.push("</tbody></table>");

	$("containerTableResult").innerHTML = tblHTML.join("\n");
	return true;
}

function ClearValue() {
	$("txtSearchWord").value = "";
//	$("selectSearchType").selectedIndex = 0;
	SearchUser();

}