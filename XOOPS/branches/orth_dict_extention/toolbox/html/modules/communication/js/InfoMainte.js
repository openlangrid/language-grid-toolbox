//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2010 CITY OF KYOTO All Rights Reserved.
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

var localInfoManager = null;

function initInfoManager(){
	localInfoManager = new InfoManager("impage");
	loadInfoData();
}

function saveInfoData(){
	var data = {};
	try {
		$$('.bbs-pull-radio-button').each(function(element){
			if (element.checked) {
				data["updateType"] = element.value;
			}
		});
		var items = Object.toJSON(data);
		localInfoManager.saveItems($F("moduleId"), $F("screenId"), items);
	} catch (e) {
		;
	}
}
function loadInfoData(){
	var items = localInfoManager.loadItems($F("moduleId"), $F("screenId"));
	if (items.get('updateType')) {
		$$('.bbs-pull-radio-button').each(function(element){
			if (element.value == items.get('updateType')) {
				element.checked = true;
			} else {
				element.checked = false;
			}
		});
	}
	document.fire('refreshButton:changed');
}

Event.observe(window, 'load', initInfoManager);
Event.observe(window, 'unload', saveInfoData);
