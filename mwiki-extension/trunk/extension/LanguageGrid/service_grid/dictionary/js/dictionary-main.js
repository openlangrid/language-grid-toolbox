
var dictionaryMain = null;
var dictionaryEditState = null;
var uploadDictionary = null;

window.runOnloadHook = function() {
	try {
		dictionaryMain = new DictionaryMain();
		dictionaryMain.start();

		dictionaryEditState = new DictionaryEditState();
	} catch (e) {
		alert(e.toSource());
	}
}

var DictionaryMain = Class.create({
	initialize: function() {
		this.dataWrapper = new ContentsDataWrapper();
		this.makeDataTable = new MakeDataTable('dictionary-table');
        this.dictEntryCounter = new DictEntryCounter('dictionary-entry-counter');
		this.selectedRowNumber = -1;
	},

	start: function() {

		Event.observe('search_button', 'click', this._onSearch.bindAsEventListener(this));

		Event.observe('add-record', 'click', this._onAddRecord.bindAsEventListener(this));
		Event.observe('delete-record', 'click', this._onDeleteRecord.bindAsEventListener(this));

		Event.observe('Save', 'click', this._onSave.bindAsEventListener(this));

		Event.observe('lang1', 'change', this._onLanguageChange.bindAsEventListener(this));
		Event.observe('lang2', 'change', this._onLanguageChange.bindAsEventListener(this));
		Event.observe('lang1_asc', 'click', this._onSort.bindAsEventListener(this));
		Event.observe('lang1_desc', 'click', this._onSort.bindAsEventListener(this));
		Event.observe('lang2_asc', 'click', this._onSort.bindAsEventListener(this));
		Event.observe('lang2_desc', 'click', this._onSort.bindAsEventListener(this));

		Event.observe('dictionary-table', 'click', function(event) {
			$$('tr.selectedDictionaryRow').each(function(elem) {
				Element.removeClassName(elem, 'selectedDictionaryRow');
			});
			var tr = Event.element(event).up('tr');
			this.selectedRowNumber = tr.id.split('_')[1];
			Element.addClassName(tr, 'selectedDictionaryRow');
		}.bind(this));

		Event.observe('dictionary-table', 'dblclick', function(event) {
			if (this.selectedRowNumber < 0) {

			} else {
				var td = Event.element(event);
				var row = td.id.split('_')[1];
				var col = '';
				if (Element.hasClassName(td, 'lang1')) {
					col = $F('lang1');
				} else {
					col = $F('lang2');
				}
				var inspector = new InputInspector('dictionary-table', this.dataWrapper);
				inspector.show(td, row, col);
			}
		}.bind(this));

		this._doRefresh();
	},

	_doRefresh: function() {
		var loader = new LoadContents(this._onLoaded.bind(this));
		loader.load();
	},

	_onSearch: function(ev) {
		var loader = new LoadContents(this._onLoaded.bind(this));
		loader.search();
	},

	_onLoaded: function(response) {
		this.dataWrapper.setData(response.contents);
		this.makeDataTable.show(this.dataWrapper);
		this.__resetSort();
        this.dictEntryCounter.show(response.contents.count);
	},

	_onSaved: function(response) {
		this.dataWrapper.setData(response.contents);
		this.makeDataTable.show(this.dataWrapper);
		dictionaryEditState.hideEdit();
        this.dictEntryCounter.show(response.contents.count);
	},

	_onLanguageChange: function(ev) {
		this._onLanguageSelectorOptions(ev);
		this.makeDataTable.show(this.dataWrapper);
		this.__resetSort();
	},

	_onLanguageSelectorOptions: function(ev) {
		var sender = Event.element(ev);
		var senderLang = $F(sender);
		var target = (sender.id == 'lang1') ? $('lang2') : $('lang1');
		var targetLang = $F(target);

		target.update('');
		LanguageSelectorOptions.each(function(obj, i) {
			if (obj.tag != senderLang) {
				var opt = document.createElement('option');
				opt.setAttribute('value', obj.tag);
				opt.innerHTML = obj.name;
				target.appendChild(opt);
			}
		}.bind(this));

		if (senderLang != targetLang) {
			target.value = targetLang;
		}
	},

	_onSort: function(ev) {
		var elem = Event.element(ev);
		$$('a.sortheader').each(function(obj, index) {
			if (elem.id == obj.id) {
				Element.addClassName(obj, 'sortactive');
			} else {
				Element.removeClassName(obj, 'sortactive');
			}
		});
		this.dataWrapper.sort(elem.id);
		this.makeDataTable.show(this.dataWrapper);
	},

	__resetSort: function() {
		$$('a.sortheader').each(function(obj, index) {
			Element.removeClassName(obj, 'sortactive');
		});
	},

	_onAddRecord: function(ev) {
		this.dataWrapper.addRecord();
		this.makeDataTable.show(this.dataWrapper);
	},

	_onDeleteRecord: function(ev) {
		this.dataWrapper.deleteRecord(this.selectedRowNumber);
		this.makeDataTable.show(this.dataWrapper);
	},

	_onSave: function(ev) {
		var saver = new SaveContents(this._onSaved.bind(this));
		saver.submit(this.dataWrapper);
	},

	_showUploadPanel: function(ev) {
		var element = $('upload-dictionary-edit');
		var cellPosition = element.cumulativeOffset();
		var bodyPosition = $$('body')[0].cumulativeOffset();

		uploadDictionary = new UploadDictionary();
		uploadDictionary.showPane(cellPosition[0]-bodyPosition[0]+20,cellPosition[1]-bodyPosition[1]-60);
	},

	test: function() {
		alert('test');
	}
});

var updateDate = '';

var LoadContents = Class.create({
	initialize: function(loadedCallbackFunc) {
		this.ajaxUrl = "LanguageGridAjaxController::invoke";
		this.ajaxAction = "Dictionary:Load";
		this.loadedCallbackFunc = loadedCallbackFunc;
	},
	load: function() {
		var params = {
			title_db_key: Const.Wiki.TitleDBKey
		}
		var postData = $H(params).toQueryString();
		sajax_request_type = 'GET';
		sajax_do_call(this.ajaxUrl, [this.ajaxAction, postData], this._onSuccess.bind(this));
	},
	search: function() {
		var params = {
			title_db_key: Const.Wiki.TitleDBKey,
			word: $F('word'),
			search_lang: $F('search_lang')
		}
		var postData = $H(params).toQueryString();
		sajax_request_type = 'GET';
		sajax_do_call(this.ajaxUrl, [this.ajaxAction, postData], this._onSuccess.bind(this));
	},
	_onSuccess: function(httpResponse) {
		if (httpResponse == null) {
			alert('HTTP-Error. Http response is empty.');
			return;
		}
		if (httpResponse.status != "200") {
			alert('HttpResponse.status = ' + httpResponse.status + " [" + httpResponse.statusText + "]");
			return;
		}
		var response = httpResponse.responseText.evalJSON();
		try {
			updateDate = response.updateDate || '';
			this.loadedCallbackFunc(response);
		} catch (e) {alert(e.toSource())}
	}
});

var SaveContents = Class.create({
	initialize: function(savedCallbackFunc) {
		this.ajaxUrl = "LanguageGridAjaxController::invoke";
		this.ajaxAction = "Dictionary:Save";
		this.savedCallbackFunc = savedCallbackFunc;
	},
	submit: function(dataWrapper) {
		var params = dataWrapper.getData();
		params.title_db_key = Const.Wiki.TitleDBKey;
//		params.update_date = updateDate;
		var postData = JSON.stringify(params);
		sajax_request_type = 'POST';
		sajax_do_call(this.ajaxUrl, [this.ajaxAction, postData], this._onSuccess.bind(this));
	},
	_onSuccess: function(httpResponse) {
		if (httpResponse == null) {
			alert('HTTP-Error. Http response is empty.');
			return;
		}
		if (httpResponse.status != "200") {
			alert('HttpResponse.status = ' + httpResponse.status + " [" + httpResponse.statusText + "]");
			return;
		}
		var response = httpResponse.responseText.evalJSON();
		try {
			updateDate = response.updateDate || '';
			this.savedCallbackFunc(response);
		} catch (e) {alert(e.toSource())}
	}
});

var ContentsDataWrapper = Class.create({
	initialize: function() {
		this.jsonData = null;
		this.newRowCount = 0;
	},
	setData: function(jsonData) {
		this.jsonData = jsonData;
	},
	getData: function() {
		return this.jsonData;
	},
	addRecord: function() {
		var obj = {row: "new" + this.newRowCount++, isNew: "1"};
		$A(DictionaryLanguages).each(function(lang){
			obj[lang] = "";
		});
		this.jsonData.data.unshift(obj);
		dictionaryEditState.showEdit();
	},
	deleteRecord: function(rowNumber) {
		$A(this.jsonData.data).each(function(data, index) {
			if (rowNumber == index) {
				data.isDelete = true;
				throw $break;
			}
		}.bind(this));
		dictionaryEditState.showEdit();
	},
	editText: function(row, col, text) {
		$A(this.jsonData.data).each(function(data, index) {
			if (data.row == row) {
				data[col] = text;
				data.isEdit = true;
				throw $break;
			}
		}.bind(this));
	},
	sort: function(mode) {
		if (mode == 'lang1_asc') {
			key = $F('lang1');
			this.jsonData.data = this.jsonData.data.sort(function(a, b) {
				if(isNaN(a[key])){
					return (a[key] > b[key])? 1:-1;
				} else {
					return a[key] - b[key];
				}
			});
		} else if (mode == 'lang1_desc') {
			key = $F('lang1');
			this.jsonData.data = this.jsonData.data.sort(function(a, b) {
				if(isNaN(b[key])){
					return (b[key] > a[key])? 1:-1;
				} else {
					return b[key] - a[key];
				}
			});
		} else if (mode == 'lang2_asc') {
			key = $F('lang2');
			this.jsonData.data = this.jsonData.data.sort(function(a, b) {
				if(isNaN(a[key])){
					return (a[key] > b[key])? 1:-1;
				} else {
					return a[key] - b[key];
				}
			});
		} else if (mode == 'lang2_desc') {
			key = $F('lang2');
			this.jsonData.data = this.jsonData.data.sort(function(a, b) {
				if(isNaN(b[key])){
					return (b[key] > a[key])? 1:-1;
				} else {
					return b[key] - a[key];
				}
			});
		}
	}
});

var MakeDataTable = Class.create({
	initialize: function(target) {
		this.tableElemId = target;
		this.tableElem = null;
	},
	show: function(dataWrapper) {
		var lang1 = $F('lang1');
		var lang2 = $F('lang2');

		var contents = dataWrapper.getData();
		this.tableElem = document.createElement('tbody');
		$A(contents.data).each(function(data, index) {
			var hash = $H(data);
			var row = data.row;

			var tr = document.createElement('tr');
			tr.id = "rowNumber_" + index;

			var dt1 = document.createElement('td');
			//dt1.innerHTML = data.lang1;
			dt1.innerHTML = hash.get(lang1);
			dt1.id = lang1 + '_' + row;
			Element.addClassName(dt1, 'lang1');
			tr.appendChild(dt1);

			var dt2 = document.createElement('td');
			//dt2.innerHTML = data.lang2;
			dt2.innerHTML = hash.get(lang2);
			dt2.id = lang2 + '_' + row;
			Element.addClassName(dt2, 'lang2');
			tr.appendChild(dt2);

			if (data.isDelete) {
				//tr.hide();
				Element.hide(tr);
			}

			this.tableElem.appendChild(tr);
		}.bind(this));
		//$(this.tableElemId).innerHTML = '';
		var tbody = $(this.tableElemId).down('tbody');
		if (tbody) {
			Element.remove(tbody);
		}
		$(this.tableElemId).appendChild(this.tableElem);
	}
});

var DictEntryCounter = Class.create({
    initialize: function(target) {
        this.counterElemId = target;
        this.counterElem = null;
    },
    show: function(count) {
        $(this.counterElemId).innerHTML = count;
    }
});
