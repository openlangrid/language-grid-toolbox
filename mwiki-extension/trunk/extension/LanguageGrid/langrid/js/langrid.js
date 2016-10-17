//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
var addPathWorkspace = null;
var PageAddPathWorkspace = null;

var isInitializing = true;

window.runOnloadHook = function() {
	$('translation-path-root-panel').innerHTML = "";
	this.PageAddPathWorkspace = new PageAddPathWorkspace();
	PageAddPathWorkspace.start();
}

function redirect2top(){
	$$(".nowedit").each(function(ele){
		Element.removeClassName(ele,"nowedit");
	});
	window.location.replace(Const.URL.TopPage);
	return;
}