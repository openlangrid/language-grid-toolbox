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
/* $Id: apfilesharingdialog-body-panel.js 4583 2010-10-13 09:53:46Z yoshimura $ */

var apfilesharingDialogBodyPanel = Class.create();
Object.extend(apfilesharingDialogBodyPanel.prototype, BodyPanel.prototype);
Object.extend(apfilesharingDialogBodyPanel.prototype, {

	idPrefix: 'apfilesharingdialog-dialog-container-',

	jsonData: null,

	initialize: function(options) {
		BodyPanel.prototype.initialize.apply(this, arguments);
	},

	draw: function() {
		this.update(new Template(this.Templates.init).evaluate({}));
		this.initEventListener();
		this.load();
		return this.element;
	},

	initEventListener: function() {
		this.addEvent('UploadButton_onClick', this._getUploadButtonId(), 'click', this.Event.onUploadClicked.bindAsEventListener(this));
	},

	load: function() {
		new Ajax.Request(
			apfilesharingDialog_Global.Url.FileList,
			{
				onSuccess: function(xml) {
					this.onSuccess_draw(xml.responseText.evalJSON());
				}.bind(this)
			}
		);
	},

	reload: function(cid) {
		new Ajax.Request(
			new Template(apfilesharingDialog_Global.Url.FolderList).evaluate({id:cid}),
			{
				onSuccess: function(xml) {
					this.onSuccess_draw(xml.responseText.evalJSON());
				}.bind(this)
			}
		);
	},

	onSuccess_draw: function(jsonData) {
		this.jsonData = jsonData;
		var html = '';

//		apfilesharingDialogFrame.setCurrentFolderId(jsonData.current);

		var navi = '';
		jsonData.parents.each(function(p, i){
			apfilesharingDialogFrame.setCurrentFolder(p);

			var linkid = this._getParentFolderId(p.id);
			var folderLbl = p.name;
			if (p.id == 1) {
				folderLbl = apfilesharingDialog_Global.Text.RootFolerLabel;
			}
			navi += new Template(this.Templates.naviItem).evaluate({
				name: folderLbl,
				id: linkid,
				href: link = new Template(apfilesharingDialog_Global.Url.FolderList).evaluate({id: p.id})
			});
			if (jsonData.parents.length > i + 1) {
				navi += '&nbsp;&gt;&nbsp;';
			}
			this.addEvent(linkid, linkid, 'click', this.Event.onSubFolder.bindAsEventListener(this, p));
		}.bind(this));

		html += new Template(this.Templates.navi).evaluate({DIR_NAVI: navi});
		html += new Template(this.Templates.uploadButton).evaluate({
			UPLOAD_BUTTON_ID: this._getUploadButtonId(),
			FILE_UPLOAD_BTN_LAB : this.options.Text.UploadFileButton || 'upload'
		});
		html += new Template(this.Templates.noUploadMsg).evaluate({
			ID: this._getUploadButtonId()+'_msg',
			TEXT: ''
		});
		html += new Template(this.Templates.Table.head).evaluate({TABLE_ID: this._ID_.TABLE_ID});
		html += new Template(this.Templates.Table.thead).evaluate({
			COL_SELECT : this.options.Text.Col.Select || '',
			COL_NAME : this.options.Text.Col.Name || '',
			COL_DESC : this.options.Text.Col.Desc || '',
			COL_READ : this.options.Text.Col.Read || '',
			COL_EDIT : this.options.Text.Col.Edit || '',
			COL_UPDATER : this.options.Text.Col.Updater || '',
			COL_UPDATE : this.options.Text.Col.Update || ''
		});

		jsonData.list.each(function(row, i) {
			var opt, css, link;
			if (row.type == 'folder') {
				opt = '-';
				css = this._CSS_.TYPE_FOLDER;
				link = new Template(apfilesharingDialog_Global.Url.FolderList).evaluate({id: row.id});
				linkid = this._getSubFolderId(row.id);
				this.addEvent(linkid, linkid, 'click', this.Event.onSubFolder.bindAsEventListener(this, row));
			} else {
				var optid = this._getOptionId(row.id);
				opt = new Template(this.Templates.selector).evaluate({id: optid, val: row.id, name: 'opname'});
				this.addEvent(optid, optid, 'click', this.Event.onSelectedFile.bindAsEventListener(this, row));
				css = this._CSS_.TYPE_FILE;
				link = new Template(apfilesharingDialog_Global.Url.FileDonwload).evaluate({id: row.id});
				linkid = '';
			}
			var canRead = (row.canRead ? apfilesharingDialog_Global.Msg.HasPermissionIcon : '-');
			var canEdit = (row.canEdit ? apfilesharingDialog_Global.Msg.HasPermissionIcon : '-');
			html += new Template(this.Templates.Table.tbody).evaluate({
				SELECT_OPTION: opt,
				file_name: row.name,
				description: row.description,
//				can_read: row.readPermission,
//				can_edit: row.editPermission,
				can_read: canRead,
				can_edit: canEdit,
				updater: row.updater,
				update_datetime: row.updateDatetime,
				file_name_type_css: css,
				file_link_url: link,
				file_link_id: linkid
			});
		}.bind(this));

		html += new Template(this.Templates.Table.foot).evaluate({});

		this.update(html);
//		this.Event.onLoaded.bind(this);
		this.Event.onLoaded.apply(this, arguments);
		this.startEventObserving();
	},

	_drawInit: function() {

	},

	_ID_ : {
		UPLOAD_BUTTON_ID : '-upload-button-id',
		TABLE_ID : 'dialog-table-id',
		SELECT_OPT : '-select-opt-cid-',
		SUBFOLDER_PREFIX: '-subfolder-',
		PARFOLDER_PREFIX: '-parent-'
	},

	_CSS_ : {
		TYPE_FILE : 'file-icon',
		TYPE_FOLDER : 'folder-icon'
	},

	_getUploadButtonId: function() {
		return this.options.Id + this._ID_.UPLOAD_BUTTON_ID;
	},
	_getOptionId: function(cid) {
		return this.options.Id + this._ID_.SELECT_OPT + cid;
	},
	_getSubFolderId: function(cid) {
		return this.options.Id + this._ID_.SUBFOLDER_PREFIX + cid;
	},
	_getParentFolderId: function(cid) {
		return this.options.Id + this._ID_.PARFOLDER_PREFIX + cid;
	},

	onFileListLoaded: function() {
		if (apfilesharingDialogFrame.getCurrentFolder().canEdit) {
			Element.removeClassName(this._getUploadButtonId(), 'button-upload-dis');
			Element.addClassName(this._getUploadButtonId(), 'button-upload');
			Element.hide(this._getUploadButtonId()+'_msg');
			$(this._getUploadButtonId()+'_msg').innerHTML = '';
		} else {
			Element.removeClassName(this._getUploadButtonId(), 'button-upload');
			Element.addClassName(this._getUploadButtonId(), 'button-upload-dis');
			Element.show(this._getUploadButtonId()+'_msg');
			$(this._getUploadButtonId()+'_msg').innerHTML = apfilesharingDialog_Global.Msg.PermissionErrorNoEdit;
		}
	}
});

Object.extend(apfilesharingDialogBodyPanel.prototype.Templates, BodyPanel.prototype.Templates);
Object.extend(apfilesharingDialogBodyPanel.prototype.Templates, {
	navi: '<div class="area-fl clearfix">#{DIR_NAVI}</div>',
	naviItem: '<span><a href="#{href}" id="#{id}">#{name}</a></span>',
	uploadButton: '<p class="area-fr"><a id="#{UPLOAD_BUTTON_ID}" class="button button-upload">#{FILE_UPLOAD_BTN_LAB}</a></p>',
	noUploadMsg: '<p class="area-fr" id="#{ID}">#{TEXT}</p>',
	Table: {
		head: '<div class="scroll-wrapper" style="clear: both;"><table id="#{TABLE_ID}" cellspacing="0" cellpadding="0" border="0" style="clear: both; width: 100%; font-size: 85%;">',
		thead: '<thead class="table-sortable-head"><tr>'
			+ '<th class="case" nowrap="nowrap">#{COL_SELECT}</th>'
			+ '<th class="case" nowrap="nowrap">#{COL_NAME}</th>'
			+ '<th class="case" nowrap="nowrap">#{COL_DESC}</th>'
			+ '<th class="case" nowrap="nowrap">#{COL_READ}</th>'
			+ '<th class="case" nowrap="nowrap">#{COL_EDIT}</th>'
			+ '<th class="case" nowrap="nowrap">#{COL_UPDATER}</th>'
			+ '<th class="case" nowrap="nowrap">#{COL_UPDATE}</th>'
			+ '</tr></thead>',
		tbody: '<tbody><tr>'
			+ '<td class="case" nowrap="nowrap">#{SELECT_OPTION}</th>'
			+ '<td class="case" nowrap="nowrap"><h4 class="#{file_name_type_css}"><a id="#{file_link_id}" href="#{file_link_url}">#{file_name}</a></h4></th>'
			+ '<td class="case" nowrap="nowrap">#{description}</th>'
			+ '<td class="case" nowrap="nowrap">#{can_read}</th>'
			+ '<td class="case" nowrap="nowrap">#{can_edit}</th>'
			+ '<td class="case" nowrap="nowrap">#{updater}</th>'
			+ '<td class="case" nowrap="nowrap">#{update_datetime}</th>'
			+ '</tr></tbody>',
		foot: '</table></div>'
	},
	selector: '<input type="radio" name="#{name}" id="#{id}" value="#{val}" />'
});

apfilesharingDialogBodyPanel.prototype.Event = {

	onLoaded: function() {
		apfilesharingDialogFrame.notifyLoadedFileLists();
	},

	onUploadClicked: function(event) {
		Event.stop(event);
		if (apfilesharingDialogFrame.getCurrentFolder().canEdit) {
			Event.observe(window, 'focus', this.Event.onUploadAfterReload.bindAsEventListener(this));
			var url = new Template(apfilesharingDialog_Global.Url.FileUpload).evaluate({id: this.jsonData.current});
			window.open(url, 'upload', '');
		}
	},

	onSelectedFile: function(event, item) {
//		var elem = Event.element(event);
//		var fileid = $F(elem.id);
		apfilesharingDialogFrame.notifyFileSelect(item);
	},

	onSubFolder: function(event, item) {
		Event.stop(event);
//		alert($H(item).toSource());
		this.reload(item.id);
	},

	onUploadAfterReload: function(event) {
		Event.stop(event);
		Event.stopObserving(window, 'focus', this.Event.onUploadAfterReload.bindAsEventListener(this));
		this.reload(this.jsonData.current);
	}
}