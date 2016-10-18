//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2010  NICT Language Grid Project
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
/* $Id: langrid-main.js 4679 2010-11-04 09:04:22Z kitajima $ */

var addPathWorkspace = null;
var isInitializing = true;

var TimeBegin = new Date();

Event.observe(window,'load',function() {
	$('translation-path-root-panel').innerHTML = "";

	this.addPathWorkspace = new SettingUIWorkspace();
	addPathWorkspace.start(INIT_DATA);
});

function redirect2top(){
	$$(".nowedit").each(function(ele){
		Element.removeClassName(ele,"nowedit");
	});
	window.location.replace(Const.URL.TopPage);
	return;
}
