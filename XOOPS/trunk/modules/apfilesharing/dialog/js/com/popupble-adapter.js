//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user
// to open or save files on the File Sharing function.
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
/* $Id: popupble-adapter.js 4583 2010-10-13 09:53:46Z yoshimura $ */

/* エレメントにポップアップ機能を追加するアダプタ */
var PopupbleAdapter = Class.create();
Object.extend(PopupbleAdapter.prototype, Panel.prototype);
Object.extend(PopupbleAdapter.prototype, {

	WIDTH: '400',

	initialize: function(element, options) {
		this.element = element;
		this.options = options;
		this.id = element.id;
		this.WIDTH = options.dialogWidth;
	},

	fetch: function() {
		var si = this.Utils.screenInfomation();
		var x, y;
		x = (si.nowHeight/2 + si.y) - 200;
		y = (si.width/2) - (this.WIDTH/2);
		this._setupStyle(x, y);
	},

	show: function() {
		Panel.prototype.show.apply(this, arguments);
	},

	_setupStyle: function(x, y) {
		this.element.setStyle({
			position: 'absolute',
			zIndex: 100,
			width: this.WIDTH+'px',
			backgroundColor: '#f5f5f5',
			left: y+'px',
			top: x+'px'
		});
	}

});

PopupbleAdapter.prototype.Utils = {

	screenInfomation : function() {
		var screen = new Object();

		screen.width     = document.body.clientWidth  || document.documentElement.clientWidth;    // 横幅
		screen.nowHeight = document.documentElement.clientHeight;    // 現在表示している画面の高さ
		screen.height    = document.body.clientHeight || document.body.scrollHeight;    // 画面の高さ
		screen.x = document.body.scrollLeft || document.documentElement.scrollLeft;    // 横の移動量
		screen.y = document.body.scrollTop || document.documentElement.scrollTop;      // 縦の移動量

		return screen;
	}

//	getScrollPosition: function() {
//		var x = document.documentElement.scrollLeft || document.body.scrollLeft;
//		var y = document.documentElement.scrollTop || document.body.scrollTop;
//		return {"x": x, "y": y};
//	},
//
//	getScreenSize: function() {
//		var w = document.documentElement.clientWidth || document.body.clientWidth || document.body.scrollWidth;
//		var h = document.documentElement.clientHeight || document.body.clientHeight || document.body.scrollHeight;
//		return {"h": h, "w": w, "mh": parseInt(h/2), "mw": parseInt(w/2)};
//	},
//
//	getDocumentSize: function() {
//		var w = document.documentElement.scrollWidth || document.body.scrollWidth;
//		var h = document.documentElement.scrollHeight || document.body.scrollHeight;
//		return {"h": h, "w": w};
//	},
}