
/*
<#if lang="ja">
コンテンツの編集状態を管理するクラス
未保存状態で遷移するとダイアログが出る。
</#if>
 */
var DictionaryEditState = Class.create({
	initialize: function() {
		this.updatelabelid = 'now-edit';
		this.flag = false;
		if($('now-edit')) {
			$('now-edit').hide();
		}
		Event.observe(window, 'beforeunload', this._onBeforUnload.bind(this));
	},
	showEdit: function() {
		this.flag = true;
		if($('now-edit')) {
			$('now-edit').show();
		}
	},
	hideEdit: function() {
		this.flag = false;
		if($('now-edit')) {
			$('now-edit').hide();
		}
	},
	isEdit: function() {
		return this.flag;
	},
	_onBeforUnload: function(event) {
		if (this.isEdit()) {
			return event.returnValue = Const.Message._MI_DICTIONARY_NO_SAVE_MOVE;
		}
	}
});