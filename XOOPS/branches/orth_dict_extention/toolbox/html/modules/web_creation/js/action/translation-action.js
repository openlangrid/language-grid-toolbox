//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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

var TranslationAction = Class.create(AbstractAction, {

	errorMessages: null,
	state: 0,

	Waiting: 0,
	Translating: 1,

	processId: 0,
	cancelIds: [],

	/**
	 *
	 */
	initialize: function() {

	},

	valid: function() {
		this.errorMessages = [];

		if (Model.Translation.isEmpty()) {
			this.errorMessages.push('');
		}

		return (this.errorMessages.length == 0);
	},

	execute: function() {
		Logger.info('TranslationAction.execute');

		if (this.state == this.Translating) {
			this.cancelIds.push(this.processId);
			this.onComplete();
			return;
		}

		if (!this.valid()) {
//			var m = this.errorMessages;
//			alert(m.join('\n'));
			return;
		}

		this.send();
	},

	getParameters: function() {
		var p = {
			sourceLanguage: Model.Language.getSourceLanguage(),
			targetLanguage: Model.Language.getTargetLanguage(),
			processId: ++this.processId
		};

		Model.Translation.getResult().each(function(line, i) {
			p['result[' + i + '][status]'] = line.status;
			p['result[' + i + '][source]'] = line.source;
			p['result[' + i + '][target]'] = line.target;
		});

		var pairIdx = 0;
		Model.ApplyTemplate.get().each(function(template, i) {
			template.pairs.each(function(pair, j) {
				p['templates[' + pairIdx + '][source]'] = pair.source;
				p['templates[' + pairIdx + '][target]'] = pair.target;
				pairIdx++;
			});

		});

		return p;
	},

	send: function() {
		// this.setEnabled(false);
		this.state = this.Translating;
		this._parent.update(Resource.CANCEL);
		$('wc-translation-ajax-image').show();

		new Ajax.Request(Resource.Url.TRANSLATION, {
			postBody: Object.toQueryString(this.getParameters()),
			onSuccess: this.onSuccess.bind(this),
			onException: this.onException.bind(this),
			onFailure: this.onFailure.bind(this),
			onComplete: this.onComplete.bind(this)
		});
	},

	onSuccess: function(transport) {
		Logger.info('TranslationAction.onSuccess');

		var processId = transport.request.body.toQueryParams().processId;

		if (this.cancelIds.indexOf(processId-0) != -1) return;

		var response = transport.responseText.evalJSON();

		Model.License.set($H(response.contents.licenses));
		Model.License.setChanged();
		Model.License.notifyObservers();

		Model.Translation.setResult(response.contents.result);
		Model.Translation.setChanged();
		Model.Translation.notifyObservers();

		Model.EditState.setChange(true);
	},

	onException: function(t, e) {
		Logger.error(e);
		alert(e.message);
	},

	onFailure: function(e) {
		Logger.error(e);
		alert(e.message);
	},

	onComplete: function() {
		this.state = this.Waiting;
		this._parent.update(Resource.TRANSLATE);
		// this.setEnabled(true);
		$('wc-translation-ajax-image').hide();
		Logger.info('TranslationAction.onComplete');
	}
});
