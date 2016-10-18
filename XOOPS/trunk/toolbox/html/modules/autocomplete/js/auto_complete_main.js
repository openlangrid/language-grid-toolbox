/* $Id: auto_complete_main.js 3616 2010-04-09 09:32:59Z yoshimura $ */


var AutoComplete = Class.create({
	initialize: function(){},
	Data: {},		// 検索結果データ
	Params: {},
	Event: {},
	Element: {},
	Ajax: {},
	Popup: {},
	ParallelTextWindow: {},
	Config: {
		textareaElementId: "demo",
		languageElementId: "lang"
	},
	start: function() {
		this.Element = new AutoCompleteElement(this);
		this.Ajax = new AutoCompleteAjax(this);
		this.Popup = new AutoCompletePopup(this);
		this.ParallelTextWindow = new AutoCompleteParallelTextWindow(this);
		this.Event = new AutoCompleteEvent(this);
	},
});

//////////////////////////////////////////////////////////////////////////////
if(typeof(getComputedStyle) == 'undefined'){
    function getComputedStyle(element, pseudo){
        return {
            currentStyle : element.currentStyle,
            getPropertyValue : function(prop){
                return this.currentStyle[capitalize(prop)];
            },
            setProperty : function(prop, value){
                this.currentStyle[capitalize(prop)] = value;
            }
        }
    }
}
function capitalize(prop){
    return prop.replace(/-(.)/g, function(m, m1){
        return m1.toUpperCase()
    })
}
// スタイル属性をクローン要素にコピー
function imitateElement(elmOriginal, elmClone){
    var styleOriginal = getComputedStyle(elmOriginal,'');
    var styleClone = getComputedStyle(elmClone,'');
    var copyProps = [
        'width', 'height',
        'font-family', 'font-size', 'line-height', 'letter-spacing', 'word-spacing', 'white-space',
        'text-align']
    copyProps.each(function(prop, idx) {
        elmClone.style[capitalize(prop)] = styleOriginal.getPropertyValue(prop);
    });
    elmClone.style.width = elmOriginal.offsetWidth;
    elmClone.style.height = elmOriginal.offsetHeight;
}
function getScrollPosition() {
	var x = document.documentElement.scrollLeft || document.body.scrollLeft;
	var y = document.documentElement.scrollTop || document.body.scrollTop;
	return {"x": x, "y": y};
}
function getScreenSize() {
	var w = document.documentElement.clientWidth || document.body.clientWidth || document.body.scrollWidth;
	var h = document.documentElement.clientHeight || document.body.clientHeight || document.body.scrollHeight;
	return {"h": h, "w": w, "mh": parseInt(h/2), "mw": parseInt(w/2)};
}
//////////////////////////////////////////////////////////////////////////////


/* イベント */
var AutoCompleteEvent = Class.create({

	initialize: function(Context) {
		this.Context = Context;
		this.attachKeyEvent();
		Context.Ajax.setEventHandler_searched(this.fireSearchedPrefix.bind(this));
		Context.Popup.setEventHandler_Selection(this.fireSelection.bind(this));

		this.isTime = false;
	},

	/* キーイベントを設置 */
	attachKeyEvent: function() {
		Event.observe(this.Context.Element.textareaElement, 'keyup', this.onKeyEventHandler.bindAsEventListener(this));
	},
	/* キーイベントハンドラ */
	onKeyEventHandler: function(event) {
		Event.stop(event);

		var keyCode = ((event.which) ? event.which : event.keyCode);
		if (keyCode == 27) { // Esc
			this._clear();
			return;
		} else if (keyCode == 243) { // E/J
			return;
		}

		this._clear();
		if (this.isTime == false) {
			this.isTime = true;
			setTimeout(this.fireKeyEvent.bind(this), 200);
		}
	},

	/* キーイベント処理 */
	fireKeyEvent: function() {
		this.Context.Params.searchPrefixText = this.Context.Element.getPrefixTextForSearch();
		this.Context.Ajax.search();
		this.isTime = false;
	},

	/* 検索完了イベント処理 */
	fireSearchedPrefix: function() {
		this.Context.Params.isComplete = false;
		this.Context.Popup.show();
	},

	/* 補完候補選択イベント処理 */
	fireSelection: function(ev, item) {
		if (this.Context.Params.isComplete) {
			this.Context.ParallelTextWindow.show(this.Context.Params.item);
			return;
		}
		// 検索用文字列を追加
		this.Context.Params.searchPrefixText += item.value;

		this.Context.Popup.hide();
		if (item.nodeType == 'T') {
			this.Context.Element.insertText(item.value);
			this.Context.Ajax.search();
		} else if (item.nodeType == 'B') {
			this.Context.Element.insertText(item.word);
			this.Context.Ajax.search();
		} else if (item.nodeType == 'L') {
			this.Context.Element.insertText(item.value);
			this.Context.Params.isComplete = true;
			this.Context.Params.item = item;
			this.Context.Popup.show();
//			this.Context.ParallelTextWindow.show(item.node.index);
		}
	},

	/* すべてのポップアップを非表示 */
	_clear: function() {
		this.Context.Popup.hide();
		this.Context.ParallelTextWindow.hide();
	},

	version: {
		number: '1.0.0',
		name: 'AutoCompleteEvent'
	}
});

/* 要素 */
var AutoCompleteElement = Class.create({

	initialize: function(Context) {
		this.Context = Context;
		this.textareaElement = this.attachTextArea(Context.Config.textareaElementId);
		this.languageElement = this.attachLanguage(Context.Config.languageElementId);

		this.preEdit = document.createElement('pre');
		this.preEdit.setStyle({position:"absolute", top:"0px", left:"0px", visibility:"hidden"});
		this.caretSpan = document.createElement('span');
		this.caretSpan.innerHTML = '|';
		new Insertion.Bottom($$('body')[0], this.preEdit);
//
//		var glayer = document.createElement('div');
//		glayer.id = 'glayer';
//		new Insertion.Bottom(glayer);
	},

	/* 対象テキストエリアを捕捉 */
	attachTextArea: function(elemId) {
		return $(elemId);
	},

	/* 言語要素を捕捉 */
	attachLanguage: function(elemId) {
		return $(elemId);
	},

	/* 言語を返す */
	getLanguageCode: function() {
		return this.languageElement.value;
	},
	/* テキストを返す */
	getText: function() {
		return this.textareaElement.value;
	},

	/* キャレット位置までのテキストを返す */
	getPrefixText: function() {
		var _caretPos = this._getCaretPos();
		return this.getText().substr(0, _caretPos.end);
	},
	/* キャレット位置算出用テキストを返す */
	getPrefixTextForCaretPosition: function() {
		return this.getPrefixText().replace(/\r\n/g, '<br>').replace(/(\r|\n)/g, '<br>');
	},
	/* 文頭からキャレット位置までのテキストを返す（検索パラメータ用） */
	getPrefixTextForSearch: function() {
		var _caretPos = this._getCaretPos();

		// 文頭を求める
		var text = this.getText().substr(0, _caretPos.end);
		var s = 0;
		if (this.getLanguageCode() == 'ja') {
			s = text.lastIndexOf('。') + 1;
		} else {
			s = text.lastIndexOf('.') + 1;
		}

		var r = text.lastIndexOf('\n') + 1;
		if (s < r) {
			s = r;
		}

		if (s < 0) {
			s = 0;
		}

		var prefix = text.substr(s, _caretPos.end);
		return prefix.replace(/\r\n/g, '').replace(/(\r|\n)/g, '');
	},

	/* キャレット位置の後ろにテキストを追加 */
	insertText: function(text) {
		var _caretPos = this._getCaretPos();
		this.textareaElement.focus();
		var src = this.getText();
		var befor = src.substr(0, _caretPos.start);
		var after = src.substr(_caretPos.start);
		this.textareaElement.value = befor + text + after;
		var s = _caretPos.start + text.length
		this._setCaretPos({start:s, end:s});
	},

	/* キャレット座標を返す */
	getCaretPosition: function() {
		this.textareaElement.focus();
		if (Prototype.Browser.IE) {
			var cp = document.selection.createRange();
			return {x: cp.offsetLeft, y: cp.offsetTop};
		} else {
			imitateElement(this.textareaElement, this.preEdit);
			this.preEdit.innerHTML = this.getPrefixTextForCaretPosition();
			new Insertion.Bottom(this.preEdit, this.caretSpan);
			var pos = Position.cumulativeOffset(this.textareaElement);
			return {x: pos.left+this.caretSpan.offsetLeft, y: pos.top+this.caretSpan.offsetTop};
		}
	},

	/* キャレット位置を返す */
	_getCaretPos: function() {
		this.textareaElement.focus();
		if (Prototype.Browser.IE) {
			var range = document.selection.createRange();
			var clone = range.duplicate();
			clone.moveToElementText(this.textareaElement);
			clone.setEndPoint('EndToEnd', range);
			return {start: clone.text.length - range.text.length, end: clone.text.length - range.text.length + range.text.length};
		} else {
			return {start: this.textareaElement.selectionStart, end: this.textareaElement.selectionEnd};
		}
	},
	/* キャレット位置を設定 */
	_setCaretPos: function(pos) {
		this.textareaElement.focus();
		if (Prototype.Browser.IE) {
			var range = this.textareaElement.createTextRange();
			var tx = this.textareaElement.value.substr(0, pos.start);
			var pl = tx.split(/\n/);
			range.collapse(true);
			range.moveStart('character', pos.start - pl.length + 1);
			range.collapse(false);
			range.select();
		} else {
			this.textareaElement.setSelectionRange(pos.start, pos.end);
		}
	},

	version: {
		number: '1.0.0',
		name: 'AutoCompleteEditor'
	}
});

/* Popup */
var AutoCompletePopup = Class.create({

	initialize: function(Context) {
		this.Context = Context;
		this.wrapper = document.createElement('div');
		this.wrapper.addClassName('autocomplete_popup');
		this._setStyle();

		this.container = document.createElement('ul');
		this.wrapper.appendChild(this.container);

		this.hide();
		new Insertion.After($$('body')[0], this.wrapper);
	},

	/* 補完候補のひとつを選択イベントを設定 */
	setEventHandler_Selection: function(fnHandler) {
		this._selection = fnHandler;
	},

	show: function() {
		this.hide();
		this._position = this.Context.Element.getCaretPosition();
		this._setStyle();
		if (this.Context.Params.isComplete) {
			this.container.appendChild(this._getShowParallelLine());
		} else {
			$A(this.Context.Data).each(function(line, index) {
				this.container.appendChild(this._getLine(line));
			}.bind(this));
		}
		this.wrapper.show();
	},

	hide: function() {
		this.wrapper.hide();
		this.container.innerHTML = '';
	},

	/* 選択肢の１行を生成して返す */
	_getLine: function(line) {
		var li = document.createElement('li');
		// 選択肢にClickイベントを設定
		Event.observe(li, 'click', this._selection.bindAsEventListener(this, line));
		li.innerHTML = line.value;
		if (line.nodeType == 'B') {
			li.innerHTML = line.word;
		}
		return li;
	},

	_getShowParallelLine: function() {
		var li = document.createElement('li');
		Event.observe(li, 'click', this._selection.bindAsEventListener(this));
		li.innerHTML = AutoComplateDefines.Label.ShowWindow;

		var close = document.createElement('img');
		close.setAttribute('src', AutoComplateDefines.URL.MODULE_URL + '/image/icon_close.gif');
		close.setAttribute('alt', 'close');
		Event.observe(close, 'click', function() {
			this.hide();
		}.bindAsEventListener(this));
		li.appendChild(close);

		return li;
	},

	/* ポップアップのCSS */
	_setStyle: function() {
		var style = {
			position: "absolute",
			backgroundColor: "#ddd",
			border: "1px solid #bbb",
			top: this._position.y+(5)+"px",
			left: this._position.x+(5)+"px",
			width: "auto",
			height: "auto"
		};
		this.wrapper.setStyle(style);
	},

	_position: {x:0, y:0},

	version: {
		number: '1.0.0',
		name: 'AutoCompletePopup'
	}
});

/* 対訳ポップアップ */
var AutoCompleteParallelTextWindow = Class.create({

	initialize: function(Context) {
		this.Context = Context;
		this.wrapper = document.createElement('div');
		this.wrapper.addClassName('autocomplete_popup_window');
		this._setStyle();

		this.container = document.createElement('span');
		this.wrapper.appendChild(this.container);

		this.hide();
		new Insertion.After($$('body')[0], this.wrapper);
	},

	show: function(item) {
		this.Context.Ajax.load({index: item.node.index, keyword: this.Context.Params.searchPrefixText}, this._loaded.bind(this));
	},

	hide: function() {
		Glayer.show();
		Glayer.hide();
		this.wrapper.innerHTML = '';
		this.wrapper.hide();
	},

	_loaded: function(response) {
		this.response = response;
		Glayer.show();
		this._setStyle();

		// 言語セレクタを初期化
		this.sourceLanguage = this.Context.Element.getLanguageCode();
		this.targetLanguage = "en";
		this._languageSelectors.sourceLanguageSelector = this.__makeLanguageSelect(this.response.languages, this.sourceLanguage);
		this._languageSelectors.targetLanguageSelector = this.__makeLanguageSelect(this.response.languages, this.targetLanguage);

		this.wrapper.appendChild(this._titleBar());
		this.contents = this._contents();
		this.wrapper.appendChild(this.contents);

		this.wrapper.show();
	},

	_titleBar: function() {
		var elem = document.createElement('div');
		elem.addClassName('clearfix');
		var closeBtn = document.createElement('img');
		closeBtn.setAttribute('src', AutoComplateDefines.URL.MODULE_URL + '/image/icon_close.gif');
		closeBtn.setStyle({float: "right"});
		Event.observe(closeBtn, 'click', this.hide.bindAsEventListener(this));
		elem.appendChild(closeBtn);

		return elem;
	},

	_contents: function() {
		var div = document.createElement('div');

		this.table = document.createElement('table');
		var thead = this._makeTHead();
		this.tbody = this._makeTBody();

		this.table.appendChild(thead);
		this.table.appendChild(this.tbody);

		div.appendChild(this.table);

		return div;
	},

	_makeTHead: function() {
		var thead = document.createElement('thead');
		var tr = document.createElement('tr');

		var sTh = document.createElement('th');
		sTh.appendChild(this._languageSelectors.sourceLanguageSelector);

		var tTh = document.createElement('th');
		tTh.appendChild(this._languageSelectors.targetLanguageSelector);

		tr.appendChild(sTh);
		tr.appendChild(tTh);

		thead.appendChild(tr);

		return thead;
	},

	__makeLanguageSelect: function(languages, lang) {
		var select = document.createElement('select');
		$H(languages).each(function(language, i) {
			var op = document.createElement('option');
			op.setAttribute('value', language[0]);
			op.innerHTML = language[1];
			if (language[0] == lang) {
				op.setAttribute('selected', 'yes');
			}
			select.appendChild(op);
		});
		Event.observe(select, 'change', this._onChangedLanguage.bindAsEventListener(this));
		return select;
	},

	_makeTBody: function(vo) {
		var vo = this.response.vo;
		var tbody = document.createElement('tbody');
		var tr = document.createElement('tr');

		vo.expressions.each(function(exp, i) {
			if (exp.language == this.sourceLanguage) {
				var td = document.createElement('td');
				var text = document.createTextNode(exp.expression);
				td.appendChild(text);
				tr.appendChild(td);
			}
		}.bind(this));

		vo.expressions.each(function(exp, i) {
			if (exp.language == this.targetLanguage) {
				var td = document.createElement('td');
				var text = document.createTextNode(exp.expression);
				td.appendChild(text);
				tr.appendChild(td);
			}
		}.bind(this));

		tbody.appendChild(tr);

		return tbody;
	},

	_onChangedLanguage: function(event) {
		this.sourceLanguage = this._languageSelectors.sourceLanguageSelector.value;
		this.targetLanguage = this._languageSelectors.targetLanguageSelector.value;

		var newTBody = this._makeTBody();
		this.table.replaceChild(newTBody, this.tbody);
		this.tbody = newTBody;
	},

	_setStyle: function() {
		var pos = getScreenSize();
		var scl = getScrollPosition();
		var style = {
			position: "absolute",
			backgroundColor: "#ffffff",
			border: "1px solid #ddd",
			top: (pos.mh - 100 + scl.y)+"px",
			left: (pos.mw - 350)+"px",
			width: "700px",
			height: "100px"
		};
		this.wrapper.setStyle(style);
	},

	_position: {x:0, y:0},

	_languageSelectors: {
		sourceLanguageSelector: function(){},
		targetLanguageSelector: function(){}
	},

	_parallelTextcontainers: {
		sourceText: function(){},
		targetText: function(){}
	},

	version: {
		number: '1.0.0',
		name: 'AutoCompleteParallelTextPopup'
	}
});

/* Ajax */
var AutoCompleteAjax = Class.create({

	initialize: function(Context) {
		this.Context = Context;
	},

	/* 検索完了時イベントハンドラを設定 */
	setEventHandler_searched: function(fnHandler) {
		this._searched = fnHandler;
	},

	search: function() {
		var postData = $H({
			language: this.Context.Element.getLanguageCode(),
			keyword: this.Context.Params.searchPrefixText
		}).toQueryString();
		new Ajax.Request(AutoComplateDefines.URL.MODULE_URL + '/?page=ajax&ajax=search',
		{
			method: 'POST',
			postBody: postData,
			onSuccess: function(httpObj) {
				var response = httpObj.responseText.evalJSON();
				this.Context.Data = response;
				// 検索完了イベントハンドラを発火
				this._searched();
			}.bind(this),
			onFailure: function(httpObj) {
			},
		});
	},

	load: function(params, callback) {
		var postData = $H(params).toQueryString();
		new Ajax.Request(AutoComplateDefines.URL.MODULE_URL + '/?page=ajax&ajax=load',
		{
			method: 'POST',
			postBody: postData,
			onSuccess: function(httpObj) {
				var response = httpObj.responseText.evalJSON();
				callback(response);
			}.bind(this),
			onFailure: function(httpObj) {
			},
		});
	},

	version: {
		number: '1.0.0',
		name: 'AutoCompleteAjax'
	}
});


Event.observe(window, 'load', function(){
	var ja = Class.create(AutoComplete, {Config: {textareaElementId: "demo_ja",languageElementId: "lang_ja"}});
	var en = Class.create(AutoComplete, {Config: {textareaElementId: "demo_en",languageElementId: "lang_en"}});
	new ja().start();
	new en().start();
});
