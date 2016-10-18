//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
var editDictionary;
var searchDictionary;



var left=0;
function xyScroll(headerTableWrapperId, bodyId) {
//  var shift = $(bodyId).scrollLeft;
//  if(left != shift) {
//  	$(headerId).scrollLeft = shift;
//  }
	$(headerTableWrapperId).style.left = ($(bodyId).scrollLeft * (-1)) + "px";
}
function adjustTableWidth(headerId, bodyId, headerTableId, bodyTableId, headerTableWrapperId) {

	try {

		$(headerTableWrapperId).style.left = 0;

		var header = $(headerId);
		var body = $(bodyId);

		var headerTable = $(headerTableId);
		var bodyTable = $(bodyTableId);
		var cell = bodyTable.rows[0].cells;
		var wkWidth;
		var tableWidth = 0;
		for (var i = 0; i < cell.length; i++) {
			wkWidth = cell[i].offsetWidth;
			headerTable.rows[0].cells[i].width = wkWidth;
			cell[i].width = wkWidth ;
			tableWidth += wkWidth;
		}
		bodyTable.width = tableWidth;
		headerTable.width = tableWidth;
		headerTable.style.width = bodyTable.offsetWidth;

		if (body.getHeight() > 265) {
			header.style.width = "905px";
		} else {
			header.style.width = "920px";
		}

//		body.style.width = "920px";

		if (tableWidth + 19 < 920) {
			body.style.width = tableWidth + 19 + "px";
		} else {
			body.style.width = "920px";
		}

	} catch (e) {
		;
	}
}
function redirect2top(){
	window.location.replace('./');
	return;
}

function showDictionariesView () {
	$("pane2").hide();
	$("pane1").show();
}

function showSearchView () {
	SearchViewController.beforeAppear();
	$("pane2").show();
	$("pane1").hide();
}
