//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

var LoadHtmlByUrlAction = Class.create(AbstractAction, {

	/**
	 * Constructor
	 */
	initialize: function() {

	},

	/**
	 * @override
	 */
	execute: function() {
		Logger.info('LoadHtmlByUrlAction.execute')

		if (!this.valid()) {
			alert(Resource.INPUT_URL_IS_INVALID);
			return false;
		}

		this.send();
	},

	send: function() {
		$('wc-url-ajax-image').show();

		this.setEnabled(false);
		new Ajax.Request(Resource.Url.LOAD_HTML_BY_URL, {
			postBody: Object.toQueryString(this.getParameters()),
			onSuccess: this.onSuccess.bind(this),
			onException: this.onException.bind(this),
			onFailure: this.onFailure.bind(this),
			onComplete: this.onComplete.bind(this)
		});
	},

	getParameters: function() {
		var url = $('wc-url-input').value;

		var parameters = {
			url: url
		};

		return parameters;
	},

	valid: function() {
		var url = this.getParameters().url;
		var validator = new UrlValidator(url);

		return validator.valid();
	},

	onSuccess: function(transport) {
		Logger.info('Ajax.Request.onSuccess::' + Resource.Url.LOAD_HTML_BY_URL);
		var response = transport.responseText.evalJSON();

		if (response.status.toUpperCase() == 'ERROR') {
			throw new Error(response.message);
		}

		response.contents.each(function(line) {
			if (line.status == 'tag') {
				line.target = line.source;
			}
		});

		Model.Translation.setResult(response.contents);
		Model.Translation.setChanged();
		Model.Translation.notifyObservers();

		Model.EditState.setChange(true);
	},

	onException: function(t, e) {
		Logger.error('Ajax.Request.onException::' + Resource.Url.LOAD_HTML_BY_URL);
		Logger.error(arguments[0]);
		Logger.error(arguments[1]);

		alert(e.message);
	},

	onFailure: function() {
		Logger.error('Ajax.Request.onFailure::' + Resource.Url.LOAD_HTML_BY_URL);
		Logger.error(arguments[0]);
		Logger.error(arguments[1]);
	},

	onComplete: function() {
		Logger.info('Ajax.Request.onComplete::' + Resource.Url.LOAD_HTML_BY_URL);
		$('wc-url-ajax-image').hide();
		this.setEnabled(true);
	}
});