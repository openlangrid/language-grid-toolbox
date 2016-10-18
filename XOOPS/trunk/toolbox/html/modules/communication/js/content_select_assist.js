//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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

ContentCache = (function() {
	var htmlCache;
	return {
		store: function(html) {
			if(this.isEnabled() && !htmlCache) htmlCache = html;
		},
		restore: function() {
			if(htmlCache) {
				$("content_container").update(htmlCache);
				this.clear();
			}
		},
		clear: function(){ htmlCache = undefined; },
		hasCache: function() { return !!htmlCache },
		isEnabled: function() { return !!params()["contentId"] }
	};
})();


// select with prev button
function showPrevContentWithSetParam() {
	clearSelectContent();
	showPrevContent(_onSelectContent);
}

// select with next button
function showNextContentWithSetParam() {
	clearSelectContent();
	showNextContent(_onSelectContent);
}

function _onSelectContent(contentId) {
	$("attachementContentId").setValue(contentId);
	$("unselect_link").show();
	$("back_selected_content_link").hide();
	_updateMarkerLinkLabel();
}
