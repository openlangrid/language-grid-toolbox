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
/* $Id: dialog-panel.js 4583 2010-10-13 09:53:46Z yoshimura $ */

/* ダイアログ本体 */

var DialogPanel = Class.create();
Object.extend(DialogPanel.prototype, Panel.prototype);
Object.extend(DialogPanel.prototype, {

	idPrefix: 'dialog_',

	headPanel:null,
	bodyPanel:null,
	footPanel:null,

	initialize: function(id, options) {
		Panel.prototype.initialize.apply(this, arguments);
		this.options = options;
		this.element = $(document.createElement('div'));
		this.element.id = this.idPrefix + id;
		this.element.addClassName('popup-dialog');

		Panel.prototype.hide.apply(this, arguments);
	},

	draw: function() {

		this.headPanel = new HeadPanel(this.options, {hideHandler: this.close.bind(this)});
		this.element.appendChild(this.headPanel.draw());

		this.bodyPanel = new BodyPanel(this.options);
		this.element.appendChild(this.bodyPanel.draw());

		this.footPanel = new FootPanel(this.options, {okHandler: function(){}, cancelHandler:this.close.bind(this), hideHandler: this.close.bind(this)});

		return this.element;
	},

	show: function() {
		Panel.prototype.show.apply(this, arguments);
		this.headPanel.show();
		this.bodyPanel.show();
//		this.footPanel.show();		// フッタ(操作ボタン)はファイルリストの読み込みが完了してから表示する。
	},

	hide: function() {
		this.headPanel.hide();
		this.bodyPanel.hide();
		this.footPanel.hide();
		Panel.prototype.hide.apply(this, arguments);
	}
});

