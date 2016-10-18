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

var AbstractSingletonPane = Class.create();
AbstractSingletonPane.prototype = {

	initialize: function(table) {
		if($('singleton-pane')) {
			$('singleton-pane').remove();
		}
		
		if($('mask-pane')) {
			$('mask-pane').remove();
		}

		this.paneID = 'singleton-pane';
		this.maskID = 'mask-pane';
		var pane = '<div class="input-box" id="' + this.paneID + '" style="z-index:10;"></div>';
		pane += '<div id="' + this.maskID + '" style="z-index:5;" class="pop-mask"></div>';
		this.table = table;
		
		try {
			new Insertion.Bottom($$('body')[0], pane);
			this.hidePane();
		} catch(e) {
			;
		}
	},

	showPane : function(x, y) {

		if (this.notShowPane()) {
			return false;
		}

		$(this.paneID).setStyle({
			position: 'absolute' ,
			left : x + 'px' ,
			top : y + 'px'
		});

		$(this.paneID).innerHTML = this.getPane();
		$(this.paneID).show();
		this.onShowPane();
	},

	setStatus : function(message, index) {
		if (!index) index = 0; 
		$('singleton-pane-status-' + index).innerHTML = message;
	},

	notShowPane : function() {
		return false;
	},

	onShowPane : function() {
		return;
	},

	getPane : function() {
		return;
	},

	submit : function() {
		return;
	},

	hidePane: function(){
		$(this.paneID).hide();
		$(this.maskID).hide();
	}
};