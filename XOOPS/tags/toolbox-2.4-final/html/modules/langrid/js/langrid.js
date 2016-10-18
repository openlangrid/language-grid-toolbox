//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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

var langridWorkspace = null;
var settingTabControler = null;
var bBSAddPathWorkspace = null;
var comAddPathWorkspace = null;
var bbsSettingView = null;
var addPathWorkspace = null;
var WebAddPathWorkspace = null;
var isInitializing = true;

var TimeBegin = new Date();

Event.observe(window,'load',function() {
	$('translation-path-root-panel').innerHTML = "";
	switch(NowTab){
		case "bbs":
			this.bBSAddPathWorkspace = new BBSAddPathWorkspace();
			bBSAddPathWorkspace.start();
			break;
		case "communication":
			this.comAddPathWorkspace = new ComAddPathWorkspace();
			comAddPathWorkspace.start();
			break;
		case "bbs_view":
			this.bbsSettingView = new BBSSettingView();
			bbsSettingView.start();
			break;
		case "text":
			this.addPathWorkspace = new AddPathWorkspace();
			addPathWorkspace.start();
			break;
		case "web":
			this.WebAddPathWorkspace = new WebAddPathWorkspace();
			WebAddPathWorkspace.start();
			break;
		case "collabtrans":
			this.addPathWorkspace = new AddPathWorkspaceCollaborativeTranslation();
			addPathWorkspace.start();
			break;
	}
});

function redirect2top(){
	$$(".nowedit").each(function(ele){
		Element.removeClassName(ele,"nowedit");
	});
	window.location.replace(Const.URL.TopPage);
	return;
}
