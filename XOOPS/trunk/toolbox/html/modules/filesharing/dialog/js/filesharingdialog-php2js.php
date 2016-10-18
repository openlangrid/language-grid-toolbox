<?php
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
/* $Id: filesharingdialog-php2js.php 4583 2010-10-13 09:53:46Z yoshimura $ */

error_reporting(0);
require_once('../../../../mainfile.php');
?>
var FileSharingDialog_Global = {
	Url: {
		FileList: '<?php echo XOOPS_URL . '/modules/filesharing/dialog/php/file.php' ?>',
		FolderList: '<?php echo XOOPS_URL . '/modules/filesharing/dialog/php/file.php?cid=#{id}' ?>',
		FileDonwload: '<?php echo XOOPS_URL . '/modules/filesharing/?page=file_dl&lid=#{id}' ?>',
		FileUpload: '<?php echo XOOPS_URL . '/modules/filesharing/upload.php?cid=#{id}' ?>'
	},
	Text: {
		dialogTitle: '<?php echo _MI_FILESHARINGDIALOG_DIALOG_TITLE;  ?>',
		UploadFileButton: '<?php echo _MI_FILESHARINGDIALOG_UPLOAD_BUTTON ?>',
		Col: {
			Select: '<?php echo _MI_FILESHARINGDIALOG_TABLE_COL_SELECT;  ?>',
			Name: '<?php echo _MI_FILESHARINGDIALOG_TABLE_COL_NAME; ?>',
			Desc: '<?php echo _MI_FILESHARINGDIALOG_TABLE_COL_DESC; ?>',
			Read: '<?php echo _MI_FILESHARINGDIALOG_TABLE_COL_READ; ?>',
			Edit: '<?php echo _MI_FILESHARINGDIALOG_TABLE_COL_EDIT; ?>',
			Updater: '<?php echo _MI_FILESHARINGDIALOG_TABLE_COL_UPDATER; ?>',
			Update: '<?php echo _MI_FILESHARINGDIALOG_TABLE_COL_UPDATE; ?>'
		},
		OkButton: '<?php echo _MI_FILESHARINGDIALOG_OK_BUTTON ?>',
		CancelButton: '<?php echo _MI_FILESHARINGDIALOG_CANCEL_BUTTON ?>',
		SaveDialogTitle: '<?php echo _MI_FILESHARINGDIALOG_SAVE_DIALOG_TITLE ?>',
		SaveFileName: '<?php echo _MI_FILESHARINGDIALOG_SAVE_FILE_NAME ?>',
		SaveFileDesc: '<?php echo _MI_FILESHARINGDIALOG_SAVE_FILE_DESC ?>',
		SavePermission: '<?php echo _MI_FILESHARINGDIALOG_SAVE_PERM ?>',
		SaveReadPermission: '<?php echo _MI_FILESHARINGDIALOG_SAVE_READ_PERM ?>',
		SaveEditPermission: '<?php echo _MI_FILESHARINGDIALOG_SAVE_EDIT_PERM ?>',
		SavePermOptPublic: '<?php echo _MI_FILESHARINGDIALOG_SAVE_PERM_OPT_PUBLIC ?>',
		SavePermOptUser: '<?php echo _MI_FILESHARINGDIALOG_SAVE_PERM_OPT_USER ?>',
		SaveOkButton: '<?php echo _MI_FILESHARINGDIALOG_SAVE_OK_BUTTON ?>',
		RootFolerLabel: '<?php echo _MI_FILESHARINGDIALOG_ROOT_FOLDER_LABEL ?>'
	},
	Msg: {
		PermissionErrorNoEdit : '<?php echo _MI_FILESHARINGDIALOG_ERROR_PERMISSION_NO_EDIT ?>',
		HasPermissionIcon : '<img src="<?php echo XOOPS_URL.'/modules/filesharing/dialog/images/icon/check.png' ?>" alt="hasPermission" border="0"/>'
	}
};