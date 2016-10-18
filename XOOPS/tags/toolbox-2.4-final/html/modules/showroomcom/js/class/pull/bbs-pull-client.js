//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to view
// contents of BBS without logging into Toolbox.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
var BBSPullClient = Class.create();
Object.extend(BBSPullClient.prototype, Observable.prototype);
Object.extend(BBSPullClient.prototype, {

	// 外からセットする
	MANUAL_INTERVAL_TIME : 0,
	AUTO_INTERVAL_TIME : 0,

	// 現在、Ajaxが走ってるか
	running : false,
	
	// 定期更新を作動させるもの
	executer : null,

	// 状態
	state : null,

	// メッセージ
	contents : null,

	// トピックID
	topicId : 1,

	// オフセット
	offset : 0,

	// Timestamp
	timestamp : 0,

	// 取得する限界値
	limit : 20,
	
	// リフレッシュボタン
	refreshButton : null,
	
	// オンラインユーザー数
	onlineUsers : null,
	
	// online user area
	onlineUsersArea : null,
	
	// 保持しておく結果
	results : 0,
	
	changeFlag : false,
	updatedIds : null,
	
	fileListController : null,

	/**
	 * コンストラクタ
	 */
	initialize : function(topicId, offset, limit, timestamp) {
		Observable.prototype.initialize.apply(this, arguments);
		this.contents = new Hash();
		this.offset = offset;
		this.topicId = topicId;
		this.limit = limit;
		this.timestamp = timestamp;
		this.onlineUsersArea = $('bbs-online-users-area');
		this.updatedIds = new Array();

		this.MANUAL_INTERVAL_TIME = Const.Pull.manualUpdateIntevalTime;
		this.AUTO_INTERVAL_TIME = Const.Pull.autoUpdateIntervalTime;

//		this.MANUAL_INTERVAL_TIME = 10;
//		this.AUTO_INTERVAL_TIME = 10;
	},

	/**
	 * 定期更新開始
	 */
	start : function() {
		var intervalTime = (this.state == 'auto') ?
					this.AUTO_INTERVAL_TIME : this.MANUAL_INTERVAL_TIME;
		this.executer = new PeriodicalExecuter(this.run.bind(this), intervalTime);
	},
	
	/**
	 * リスタートする
	 */
	restart : function() {
		try {
			if (!this.executer) {
				throw new Error();
			}
			this.executer.stop();
		} catch (e) {
			;
		}
		this.start();
	},
	
	/**
	 * 走らせる
	 */
	run : function() {
		this.update();
	},
	
	/**
	 * POSTパラメータを返す
	 */
	getParameters : function() {
		return {
			timestamp : this.timestamp,
			limit : this.limit,
			offset : this.offset,
			topicId : this.topicId
		};
	},

	/**
	 * return template
	 * @param Object permission
	 * @return Template template
	 */
	createMessageTemplate : function(permission, isNew) {
		var permission = Object.extend({
			'edit' : true,
			'modify' : false,
			'delete' : true,
			'reply' : true
		}, permission || {});
		var html = new Array();
		if (isNew) {
			html.push(this.Templates.ROW_HEADER_WRAPPER_BEGIN);
		}
		html.push(this.Templates.HEADER);
		if (permission['delete']) {
			html.push(this.Templates.DELETE);
		}
		if (permission['edit']) {
			html.push(this.Templates.EDIT);
		}
		if (permission['modify']) {
			html.push(this.Templates.MODIFY);
		}
		if (permission['reply']) {
			html.push(this.Templates.REPLY);
		}
		html.push(this.Templates.FOOTER);
		if (isNew) {
			html.push(this.Templates.ROW_WRAPPER_END);
		}
		return new Template(html.join(''));
	},
	
	/**
	 * 
	 */
	createMessageHtml : function(post, isNew) {
		var message = post.message;
		if (post.deleteFlag || !post.translationFlag) {
			message = '<span class="bbs-invalid-post">' + message + '</span>';
		}
		var fileIcon = '';
		if (post.files && post.files.length > 0) {
			fileIcon = '<a id="DownloadFileButton-' + post.id + '" class="DownloadFileButton" href="javascript:void(0)" onclick="return false;"><img alt="File List" src="./images/attach_icon.gif"></a>';
		}
		
		var updatedMessage = '　New';
		if (post.deleteFlag) {
			updatedMessage = '　Deleted';
		} else if (!isNew) {
			updatedMessage = '　Updated';
		}
			
		return this.createMessageTemplate(post.permission, isNew).evaluate({
			id : post.id
			, updatedMessage : updatedMessage
			, language : post.language.name
			, date : post.date
			, order : post.order
			, userId : post.user.id
			, userName : post.user.name
			, message :  message
			, fileIcon : fileIcon

			, labelPostedOn : Const.Label.postedOn
			, labelRemove : Const.Label.remove
			, labelModify : Const.Label.modify
			, labelEdit : Const.Label.edit
			, labelReply : Const.Label.reply
		});
	},
	
	createBodyHtml : function(post, isNew) {
		var html = new Array();
		if (isNew) {
			html.push(new Template(this.Templates.ROW_BODY_WRAPPER_BEGIN).evaluate({
				order : post.order
			}));
		}
		var message = post.message;
		if (post.deleteFlag || !post.translationFlag) {
			message = '<span class="bbs-invalid-post">' + message + '</span>';
		}
		html.push(new Template(this.Templates.BODY).evaluate({
			message : message
		}));
		if (isNew) {
			html.push(this.Templates.ROW_WRAPPER_END);
		}
		return html.join('');
	},
	
	/**
	 * @return String evaluated HTML Template
	 */
	createOnlineUserHtml : function() {
		var html = new Array();
		html.push(this.Templates.onlineUsers.HEADER);
		this.onlineUsers.each(function(user) {
			html.push(new Template(this.Templates.onlineUsers.BODY).evaluate({
				id : user.id
				, icon : user.icon
				, name : user.name || user.fullName
			}));
		}.bind(this));
		html.push(this.Templates.onlineUsers.FOOTER);
		return html.join('');
	},
	
	updateAndRefresh : function() {
		this.update(true);
	},
	
	/**
	 * 
	 */
	update : function(forceRefresh) {
		if (this.running) {
			return;
		}
		this.running = true;
		new Ajax.Request(BBSPullMessageConfig.Url.AJAX_PATH, {
			postBody : $H(this.getParameters()).toQueryString(),
			onSuccess : this.onSuccess.bindAsEventListener(this, forceRefresh),
			onException : function(request, exception) {
//				console.error(exception);
			}.bind(this),
			onFailure : function(transport) {
//				console.error(transport);
			}.bind(this),
			onComplete : function() {
				this.running = false;
			}.bind(this)
		});
	},
	
	/**
	 * ページをリフレッシュする
	 */
	refresh : function() {
		if (this.contents.size() > 0) {
			this.updatedIds.each(function(updatedId){
				$('bbs-update-' + updatedId).update('');
			});
			this.updatedIds = new Array();
			// Message
			this.contents.each(function(content) {
				var html = new Array();
				var body = new Array();
				var post = content.value;

				this.updatedIds.push(post.id);
				FileListHash[post.id] = new Array();
				if (post.files) {
					post.files.each(function(file) {
						FileListHash[post.id].push({
							ID : file.id,
							FileName : file.name,
							FileSize : file.size
						});
					}.bind(this));
				}

				var isNew = !($('post-number-' + post.order));
				html.push(this.createMessageHtml(post, isNew));
				body.push(this.createBodyHtml(post, isNew));
				if (isNew) {
					html.push(body.join(''));
					new Insertion.Bottom($('bbs-post-list-table'), html.join(''));
				} else {
					var area = $('post-number-' + post.order);
					while (!!area.firstChild) {
						area.removeChild( area.firstChild );
					}
					new Insertion.Bottom(area, html.join(''));
					var area = $('post-body-number-' + post.order);
					while (!!area.firstChild) {
						area.removeChild( area.firstChild );
					}
					new Insertion.Bottom(area, body.join(''));
				}
			}.bind(this));

			$$('.DownloadFileButton').each(function(element){
//				console.info(element);
				element.observe('click', this.fileListController.downloadButtonClickEvent.bindAsEventListener(this.fileListController));
			}.bind(this));
		}

		// Pager
		$$('.bbs-pager-wrapper').each(function(element, i){
			if (this.pager && this.pager != '') {
				element.innerHTML = this.pager;
				if (i == 0) {
					$$('.bbs-pager').each(function(elem){
						elem.setStyle({
							paddingTop : 0
						});
					});
				}
			}
		}.bind(this));

		// Online users
		this.onlineUsersArea.innerHTML = this.createOnlineUserHtml();
		$('bbs-online-users-area-table').setStyle({
			width : this.onlineUsers.length * 40 + 'px'
		});

		document.fire('refreshButton:disabled');
		this.contents = new Hash();
		this.changeFlag = false;
	},
	
	/**
	 * @param Object transport
	 */
	onSuccess : function(transport, forceRefresh) {
		var response = transport.responseText.evalJSON();
		if (response.status.toUpperCase() == 'ERROR') {
			throw new Error(response.message);
		}
		this.mergeContents(response.contents.messages);

		if (response.contents.messages.length > 0) {
			this.setChanged();
		}

		this.pager = response.contents.pager;

		if (this.isUsersChanged(this.onlineUsers, response.contents.onlineUsers)) {
			this.setChanged();
		}
		this.onlineUsers = response.contents.onlineUsers;

		if (Math.ceil(this.results / this.limit) != Math.ceil(response.contents.results / this.limit)) {
			this.setChanged();
		}
		this.results = response.contents.results;
		this.timestamp = response.contents.timestamp;
		if (this.state == 'auto' || !!forceRefresh) {
			this.refresh();
		} else if (this.hasChanged()) {
			document.fire('refreshButton:abled');
		}
	},
	
	/**
	 * 現在のコンテンツとマージする
	 */
	mergeContents : function(contents) {
		contents.each(function(content){
			this.contents.set(content.id, content);
		}.bind(this));
	},

	/**
	 * 現在の状態を返す
	 */
	getState : function() {
		return this.state;
	},
	
	/**
	 * Stateの変更時
	 */
	setState : function(newState) {
		if (this.state == newState) {
			return;
		}
		if(newState == 'auto') {
			document.fire('refreshButton:disabled');
		}
		this.restart();
			
		this.state = newState;
	},
	
	/**
	 * 変更あるかないか
	 */
	hasChanged : function() {
		return this.changeFlag;
	},
	
	setChanged : function() {
		this.changeFlag = true;
	},
	
	/**
	 * 
	 */
	isUsersChanged : function(oldUsers, newUsers) {
		if (!oldUsers || oldUsers.length != newUsers.length) {
			return true;
		}
		var oldUserIds = new Array();
		oldUsers.each(function(oldUser){
			oldUserIds.push(oldUser.id);
		});
		var newUserIds = new Array();
		newUsers.each(function(newUser){
			newUserIds.push(newUser.id);
		});
		var userIds = oldUserIds.concat(newUserIds);
		var length = userIds.uniq().length;
		if (length != oldUserIds.length && length != newUserIds.length) {
			return true;
		}
	},
	
	setFileListController : function(fileListController) {
		this.fileListController = fileListController;
	},
	
	setOnlineUsers : function(onlineUsers) {
		this.onlineUsers = onlineUsers;
	},
	
	setResults : function(results) {
		this.results = results;
	}
});

BBSPullClient.prototype.Templates = {
	ROW_HEADER_WRAPPER_BEGIN : '<tr style="font-size: 110%;" id="post-number-#{order}">',
	ROW_BODY_WRAPPER_BEGIN : '<tr id="post-body-number-#{order}">',
	HEADER : '<td class="list_line01" style="padding 2px 8px;">'
		+ '#{order}: <a href="../../userinfo.php?uid=#{userId}">#{userName}</a>'
		+ ' (#{language}) #{labelPostedOn} #{date} #{fileIcon} <span style="color: #f00;" id="bbs-update-#{id}">#{updatedMessage}</span></td>'
		+ '<td class="list_line01" style="padding 2px 8px;">'
		+ '<ul class="btn_set01"style="margin-bottom: 0;">'
	, DELETE : ''
		+ '<li>'
		+ '<a href="./?page=post-delete&postId=#{id}">'
		+ '<div class="btn_gray01">'
		+ '<span style="text-align:center; width:60px; padding: 2px 0;">'
		+ '#{labelRemove}'
		+ '</span>'
		+ '</div>'
		+ '</a>'
		+ '</li>'
	, EDIT : ''
		+ '<li>'
		+ '<form action="./?page=preview" method="post" name="editMessageLinkForm#{order}">'
		+ '<input type="hidden" name="type_code" value="post_edit" />'
		+ '<input type="hidden" name="id" value="#{id}" />'
		+ '<a href="#" onclick="document.editMessageLinkForm#{order}.submit(); return false;">'
		+ '<div class="btn_blue01">'
		+ '<span style="text-align:center; width:60px; padding: 2px 0;">'
		+ '#{labelEdit}'
		+ '</span>'
		+ '</div>'
		+ '</a>'
		+ '</form>'
		+ '</li>'
	, MODIFY : ''
		+ '<li>'
		+ '<a href="./?page=post-modify&postId=#{id}">'
		+ '<div class="btn_blue01">'
		+ '<span style="text-align:center; width:60px; padding: 2px 0;">'
		+ '#{labelModify}'
		+ '</span>'
		+ '</div>'
		+ '</a>'
		+ '</li>'
	, REPLY : ''
		+ '<li>'
		+ '<form action="./?page=preview" method="post" name="replyMessageLinkForm#{order}">'
		+ '<input type="hidden" name="type_code" value="post_reply" />'
		+ '<input type="hidden" name="id" value="#{id}" />'
		+ '<a href="#" onclick="document.replyMessageLinkForm#{order}.submit(); return false;">'
		+ '<div class="btn_blue01">'
		+ '<span style="text-align:center; width:60px; padding: 2px 0;">'
		+ '#{labelReply}'
		+ '</span>'
		+ '</div>'
		+ '</a>'
		+ '</form>'
		+ '</li>'
	, FOOTER : '</ul></td>'
	, BODY : '<td colspan="2">#{message}</td>'
	, ROW_WRAPPER_END : '</tr>'
	, onlineUsers : {
		HEADER : '<table id="bbs-online-users-area-table"><tr>'
		, BODY : '<td><a target="_blank" href="../../userinfo.php?uid=#{id}"><img width="30" src="#{icon}" alt="#{name}" title="#{name}" /></a></td>'
		, FOOTER : '</tr></table>'
	}
};