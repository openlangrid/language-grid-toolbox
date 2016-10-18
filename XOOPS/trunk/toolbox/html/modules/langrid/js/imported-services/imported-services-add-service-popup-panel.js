//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
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
/**
 * @author kitajima
 */
var AddServicePopupPanel = Class.create();
Object.extend(AddServicePopupPanel.prototype, LightPopupPanel.prototype);
Object.extend(AddServicePopupPanel.prototype, {

	formId : '',

	serviceNameId : '',

	serviceTypeSelectId : '',

	languagePanelId : '',

	languagePanel : null,

	languageSelectorsPanel : null,
	languagePathsPanel : null,

	endpointUrlId : '',
	providerId : '',
	copyrightId : '',
	licenseId : '',

	submitButtonId : '',
	cancelButtonId : '',

	languageSelectIdPrefix : '',

	onAddServiceFireEventName : '',

	addedService : null,

	errorMessage : '',

	initEventListeners : function() {
		this.addEventCache(window, 'scroll', 'onScrollWindowEvent');
		this.addEventCache(window, 'resize', 'onResizeWindowEvent');

		this.addEventCache(this.serviceTypeSelectId, 'change', 'onChangeServiceTypeEvent');
		this.addEventCache(this.formId, 'submit', 'onSubmitFormEvent');
		this.addEventCache(this.cancelButtonId, 'click', 'onClickCancelButtonEvent');
	},

	getBody : function() {
		var html = new Array();

		html.push(new Template(Templates.ImportedServices.PopupPanel.addService.header).evaluate({
			formId : this.formId,
			title : Config.Text.ADD_SERVICE
		}));

		html.push(new Template(Templates.ImportedServices.PopupPanel.addService.body).evaluate({
			serviceName : Config.Text.SERVICE_NAME
			, serviceNameId : this.serviceNameId
			, serviceType : Config.Text.SERVICE_TYPE
			, serviceTypeComboBox : this.createServiceTypeComboBox(ServiceType.DICTIONARY)
			, language : Config.Text.LANGUAGE
			, languagePanelId : this.languagePanelId
			, endpointUrl : Config.Text.ENDPOINT_URL
			, endpointUrlId : this.endpointUrlId
			, provider : Config.Text.PROVIDER
			, providerId : this.providerId
			, copyright : Config.Text.COPYRIGHT
			, copyrightId : this.copyrightId
			, license :  Config.Text.LICENSE
			, licenseId : this.licenseId
		}));
		html.push(new Template(Templates.ImportedServices.PopupPanel.addService.footer).evaluate({
			requiredField : Config.Text.REQUIRED_FIELD
			, errorMessageId : this.errorMessageId
			, statusMessageId : this.statusMessageId
			, cancelButtonId : this.cancelButtonId
			, cancel : Config.Text.CANCEL
			, submitButtonId : this.submitButtonId
			, submit : Config.Text.IMPORT
		}));

		this.changeState(ServiceType.DICTIONARY);

		return html.join('');
	},

	onShowPanel : function() {
		this.languagePanel.draw();
	},

	onChangeServiceTypeEvent : function(event) {
		this.changeState(this.getSelectedServiceType());
		this.languagePanel.draw();
	},

	onSubmitFormEvent : function(event) {
		Event.stop(event);

		if (this.isSubmitButtonDisabled()) {
			return;
		}

		if (!this.validate()) {
			this.setErrorMessage(this.getErrorMessage());
			return;
		}

		this.setErrorMessage('');

		this.setSubmitButtonDisabled(true);
		this.setCancelButtonDisabled(true);
		this.doSubmit();
	},

	setSubmitButtonDisabled : function(disabled) {
		if (disabled) {
			$(this.submitButtonId).addClassName(Config.ClassName.BUTTON_DISABLED);
		} else {
			$(this.submitButtonId).removeClassName(Config.ClassName.BUTTON_DISABLED);
		}
	},

	isSubmitButtonDisabled : function() {
		return !!$(this.submitButtonId).hasClassName(Config.ClassName.BUTTON_DISABLED);
	},

	validate : function() {
		this.errorMessage = null;
		if ($(this.serviceNameId).value.blank()) {

			this.errorMessage = Config.Text.STRING_IS_BLANK.replace(/\{0\}/g, Config.Text.SERVICE_NAME);
		} else if (!this.languagePanel.validate()) {

			this.errorMessage = this.languagePanel.getErrorMessage();
		} else if ($(this.endpointUrlId).value.blank()) {

			this.errorMessage = Config.Text.STRING_IS_BLANK.replace(/\{0\}/g, Config.Text.ENDPOINT_URL);
		} else if (this.isInvalidUrl($(this.endpointUrlId).value)) {
			this.errorMessage = Config.Text.THE_INPUT_URL_IS_INVALID;
		}

		return !this.errorMessage;
	},

	isInvalidUrl : function(url) {
// TODO: 2010-09-02 EBMT用暫定対応（URL妥当性チェックを無効）
		return false;
//		return !url.match(/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/);
	},

	getErrorMessage : function() {
		return this.errorMessage;
	},

	getParameters : function() {
		var parameters = {
			serviceName : $(this.serviceNameId).value,
			serviceType : $(this.serviceTypeSelectId).value,
			endpointUrl : $(this.endpointUrlId).value,
			provider :  $(this.providerId).value,
			copyright : $(this.copyrightId).value,
			license :  $(this.licenseId).value
		};
		this.languagePanel.getSeriarizedLanguages().each(function(language, i){
			parameters['languages[' + i + ']'] = language;
		}.bind(this));

		return parameters;
	},

	doSubmit : function() {
		this.setStatusMessage(Config.Image.NOW_LOADING + Config.Text.NOW_IMPORTING);
		new Ajax.Request(Config.Url.ADD_SERVICE, {
			postBody : $H(this.getParameters()).toQueryString(),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				if (response.status.toUpperCase()  == 'ERROR') {
					throw new Error(response.message);
				}
				this.addedService = response.contents.service;
				this.hide();
				document.fire(this.onAddServiceFireEventName);
			}.bind(this),
			onFailure : function(transport) {
				this.setStatusMessage('');
				this.setErrorMessage(e.message);
			}.bind(this),
			onException : function(request, e) {
				this.setStatusMessage('');
				this.setErrorMessage(e.message);
			}.bind(this),
			onComplete : function() {
				this.setSubmitButtonDisabled(false);
				this.setCancelButtonDisabled(false);
			}.bind(this)
		})
	},

	onClickCancelButtonEvent : function(event) {
		Event.stop(event);
		if (this.isCancelButtonDisabled()) {
			return;
		}
		this.hide();
	},


	setCancelButtonDisabled : function(disabled) {
		if (disabled) {
			$(this.cancelButtonId).addClassName(Config.ClassName.BUTTON_DISABLED);
		} else {
			$(this.cancelButtonId).removeClassName(Config.ClassName.BUTTON_DISABLED);
		}
	},

	isCancelButtonDisabled : function() {
		return !!$(this.cancelButtonId).hasClassName(Config.ClassName.BUTTON_DISABLED);
	},


	changeState : function(serviceType) {
		switch (serviceType) {
		case ServiceType.TRANSLATOR:
			this.languagePanel = this.languagePathsPanel;
			this.languagePanel.init();
			break;
		case ServiceType.DICTIONARY:
		default:
			this.languagePanel = this.languageSelectorsPanel;
			this.languagePanel.init();
			break;
		}
	},

	getSelectedServiceType : function() {
		return $(this.serviceTypeSelectId).value;
	},

	createServiceTypeComboBox : function(selected) {
		var html = new Array();

		// Header
		html.push(new Template(Templates.ComboBox.header).evaluate({
			id : this.serviceTypeSelectId
		}));

		// Dictionary
		html.push(new Template(Templates.ComboBox.body).evaluate({
			value : ServiceType.DICTIONARY,
			name : Config.Text.DICTIONARY,
			selected : (selected == ServiceType.DICTIONARY)
				? Config.Html.Attribute.SELECTED : ''
		}));

		// Translator
		html.push(new Template(Templates.ComboBox.body).evaluate({
			value : ServiceType.TRANSLATOR,
			name : Config.Text.TRANSLATOR,
			selected : (selected == ServiceType.TRANSLATOR)
				? Config.Html.Attribute.SELECTED : ''
		}));

		// Footer
		html.push(Templates.ComboBox.footer);
		return html.join('');
	},

	setLanguages : function(languages) {
		this.languageSelectorsPanel.setLanguages(languages);
		this.languagePathsPanel.setLanguages(languages);
	},

	getAddedService : function() {
		return this.addedService;
	}
});