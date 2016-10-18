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
var EditServicePopupPanel = Class.create();
Object.extend(EditServicePopupPanel.prototype, AddServicePopupPanel.prototype);
Object.extend(EditServicePopupPanel.prototype, {

	service : null,

	initEventListeners : function() {
		this.addEventCache(window, 'scroll', 'onScrollWindowEvent');
		this.addEventCache(window, 'resize', 'onResizeWindowEvent');

		this.addEventCache(this.formId, 'submit', 'onSubmitFormEvent');
		this.addEventCache(this.cancelButtonId, 'click', 'onClickCancelButtonEvent');
	},

	onShowPanel : function() {
		switch (this.service.type) {
		case ServiceType.DICTIONARY:
		case ServiceType.MORPHOLOGICALANALYSIS:
			this.languagePanel.setSelectedLanguages(this.service.languages);
			break;
		case ServiceType.TRANSLATOR:
			this.languagePanel.setSelectedLanguagePaths(this.service.languagePaths);
			break;
		}
		this.languagePanel.draw();
	},

	validate : function() {
		this.errorMessage = null;
		if (!this.languagePanel.validate()) {

			this.errorMessage = this.languagePanel.getErrorMessage();
		} else if ($(this.endpointUrlId).value.blank()) {

			this.errorMessage = Config.Text.STRING_IS_BLANK.replace(/\{0\}/g, Config.Text.ENDPOINT_URL);
		} else if (this.isInvalidUrl($(this.endpointUrlId).value)) {
			this.errorMessage = Config.Text.THE_INPUT_URL_IS_INVALID;
		}

		return !this.errorMessage;
	},

	getParameters : function() {

		var parameters = {
			serviceId : this.service.id,
			endpointUrl : $(this.endpointUrlId).value,
			provider :  $(this.providerId).value,
			copyright : $(this.copyrightId).value,
			license :  $(this.licenseId).value,
			basicUserid : $(this.basicUseridId).value,
			basicPasswd : $(this.basicPasswdId).value
		};
		this.languagePanel.getSubmitValue().each(function(language, i){
			parameters['languages[' + i + ']'] = language;
		}.bind(this));

		return parameters;
	},

	doSubmit : function() {
		this.setStatusMessage(Config.Image.NOW_LOADING + Config.Text.NOW_SAVING);
		new Ajax.Request(Config.Url.EDIT_SERVICE, {
			postBody : $H(this.getParameters()).toQueryString(),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				if (response.status.toUpperCase()  == 'ERROR') {
					throw new Error(response.message);
				}
				this.service = response.contents.service;
				this.hide();
				document.fire(this.onEditServiceFireEventName);
			}.bind(this),
			onFailure : function(transport) {
				this.setStatusMessage('');
				this.setErrorMessage(Config.Message.ON_AJAX_SERVER_ERROR);
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

	getBody : function() {
		var html = new Array();

		html.push(new Template(Templates.ImportedServices.PopupPanel.editService.header).evaluate({
			formId : this.formId,
			title : Config.Text.EDIT_SERVICE
		}));

		html.push(new Template(Templates.ImportedServices.PopupPanel.editService.body).evaluate({
			serviceName : Config.Text.SERVICE_NAME
			, serviceNameValue : this.service.name.escapeHTML()
			, serviceType : Config.Text.SERVICE_TYPE
			, serviceTypeValue : this.getServiceTypeLabel(this.service.type)
			, language : Config.Text.LANGUAGE
			, languagePanelId : this.languagePanelId
			, endpointUrl : Config.Text.ENDPOINT_URL
			, endpointUrlId : this.endpointUrlId
			, endpointUrlValue : this.service.endpointUrl.escapeHTML()
			, provider : Config.Text.PROVIDER
			, providerId : this.providerId
			, providerValue : this.service.provider.escapeHTML()
			, copyright : Config.Text.COPYRIGHT
			, copyrightId : this.copyrightId
			, copyrightValue : this.service.copyright.escapeHTML()
			, license :  Config.Text.LICENSE
			, licenseId : this.licenseId
			, licenseValue : this.service.license
			, basicUserid : Config.Text.BASIC_USERID
			, basicUseridId : this.basicUseridId
			, basicUseridValue : this.service.basicUserid
			, basicPasswd : Config.Text.BASIC_PASSWD
			, basicPasswdId : this.basicPasswdId
			, basicPasswdValue : this.service.basicPasswd
		}));
		html.push(new Template(Templates.ImportedServices.PopupPanel.editService.footer).evaluate({
			requiredField : Config.Text.REQUIRED_FIELD
			, errorMessageId : this.errorMessageId
			, statusMessageId : this.statusMessageId
			, cancelButtonId : this.cancelButtonId
			, cancel : Config.Text.CANCEL
			, submitButtonId : this.submitButtonId
			, submit : Config.Text.SAVE
		}));

		this.changeState(this.service.type);

		return html.join('');
	},

	changeState : function(serviceType) {
		switch (serviceType) {
		case ServiceType.TRANSLATOR:
			this.languagePanel = this.languagePathsPanel;
			this.languagePanel.getSubmitValue = this.languagePanel.getSeriarizedLanguages;
			break;
		case ServiceType.DICTIONARY:
			this.languagePanel = this.languageSelectorsPanel;
			this.languagePanel.init(2);
			this.languagePanel.getSubmitValue = this.languagePanel.getSeriarizedLanguages;
			break;
		case ServiceType.MORPHOLOGICALANALYSIS:
			this.languagePanel = this.languageSelectorsPanel;
			this.languagePanel.init(1);
			this.languagePanel.getSubmitValue = this.languagePanel.getArrayedLanguages;
			break;
		default:
		}
	},

	getServiceTypeLabel : function(serviceType) {
		var a;
		switch (serviceType) {
		case ServiceType.TRANSLATOR:
			a = Config.Text.TRANSLATOR;
			break;
		case ServiceType.DICTIONARY:
			a = Config.Text.DICTIONARY;
			break;
		case ServiceType.MORPHOLOGICALANALYSIS:
			a = Config.Text.MORPHOLOGICALANALYSIS;
			break;
		default:
		}
		return a;
	},

	getService : function(service) {
		return this.service;
	},

	setService : function(service) {
		this.service = service;
	}
});