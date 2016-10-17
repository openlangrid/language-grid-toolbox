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
/* $Id: apfilesharingdialog-main.js 4708 2010-11-09 08:38:56Z kitajima $ */

var apfilesharingDialog = Class.create();
Object.extend(apfilesharingDialog.prototype, Panel.prototype);
Object.extend(apfilesharingDialog.prototype, {

	defaultOptions: {
		Id: '',
		dialogMode: 'Open',									// Open 参照 | Save 保存
		dialogBuilderClass: apfilesharingDialogBuilder,		// ダイアログ組み立てクラス
		onOk: function(){},
		willHide: function(){},
		onFileSelect: function(){},
		Text: {
			dialogTitle: 'File Sharing Dialog',
			UploadFileButton: 'File Upload',
			Col: {
				Select: 'Select',
				Name: 'File name',
				Desc: 'Description',
				Read: 'Read permission',
				Edit: 'Edit permission',
				Updater: 'Last updater',
				Update: 'Last update'
			},
			OkButton: 'Ok',
			CancelButton: 'Cancel',
			SaveFileName: 'File name',
			SaveFileDesc: 'File description',
			SavePermission: 'Permission',
			SaveReadPermission: 'Read permission',
			SaveEditPermission: 'Edit permission'
		}
	},
	options : {},

	dailog: null,

	initialize: function(id, options) {
		Panel.prototype.initialize.apply(this, arguments);

		apfilesharingDialogFrame.clear();

		// 実行optionsを設定
		Object.extend(this.options, this.defaultOptions);

		// テキストリソースを上書き
		if (apfilesharingDialog_Global.Text) {
			Object.extend(this.options.Text, apfilesharingDialog_Global.Text);
		}

		// ダイアログモードによるビルダのスイッチ
		if (options.dialogMode == 'Save') {
			options.dialogBuilderClass = apfilesharingSaveDialogBuilder;
		}

		// ユーザoptionsを上書き
		Object.extend(this.options, options || {});
		this.id = id;
		this.options.Id = id;

		this.element = this.getElement();

		this.hide();

		apfilesharingDialogFrame.setOkEvent(this.options.onOk);
		apfilesharingDialogFrame.setHideEvent(this.options.willHide);
		apfilesharingDialogFrame.setFileSelectEvent(this.options.onFileSelect);
	},

	show: function() {
		this.dialogBuilder = new this.options.dialogBuilderClass(this.id, this.options);
//		this.getElement().innerHTML = this.dialogBuilder.build();
		this.dialog = this.dialogBuilder.build();
		this.getElement().update(this.dialog.getElement());
		this.dialog.show();
		Panel.prototype.show.apply(this, arguments);
	},

	hide: function() {
		apfilesharingDialogFrame.notifyDialogClose();
		Panel.prototype.hide.apply(this, arguments);
	}
});

