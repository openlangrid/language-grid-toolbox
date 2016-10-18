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
var ImportedServicesPanel = Class.create();
Object.extend(ImportedServicesPanel.prototype, Panel.prototype);
Object.extend(ImportedServicesPanel.prototype, {

	addServiceButton : null,
	editServiceButton : null,

	addServicePopupPanel : null,
	editServicePopupPanel : null,
	tablePanel : null,

	// fire event
	onAddServiceFireEventName : null,
	onEditServiceFireEventName : null,

	// status area
	statusArea : null,

	init : function() {
		this.initEventListener();
		this.loadSupportedLanguages();
	},

	initEventListener : function() {
		this.addServiceButton.observe('click', this.onClickAddServiceButtonEvent.bindAsEventListener(this));
		this.editServiceButton.observe('click', this.onClickEditServiceButtonEvent.bindAsEventListener(this));
		this.removeServiceButton.observe('click', this.onClickRemoveServiceButtonEvent.bindAsEventListener(this));

		document.observe(this.onAddServiceFireEventName, this.onAddServiceEvent.bindAsEventListener(this));
		document.observe(this.onEditServiceFireEventName, this.onEditServiceEvent.bindAsEventListener(this));
		document.observe(Config.FireEventName.TABLE_ROW_CLICKED, this.onRowSelected.bindAsEventListener(this));
	},

	onClickAddServiceButtonEvent : function(event) {
		if (this.addServiceButton.hasClassName(Config.ClassName.BUTTON_DISABLED)) {
			return;
		}

		this.addServicePopupPanel.show();
	},

	onAddServiceEvent : function(event) {
		this.tablePanel.addService(this.addServicePopupPanel.getAddedService());
	},

	onClickEditServiceButtonEvent : function(event) {
		var service = this.tablePanel.getSelectedService();
		if (this.editServiceButton.hasClassName(Config.ClassName.BUTTON_DISABLED)) {
			return;
		}
		this.editServicePopupPanel.setService(service);
		this.editServicePopupPanel.show();
	},

	onEditServiceEvent : function(event) {
		this.tablePanel.editService(this.editServicePopupPanel.getService());
		this.onRowUnselected();
	},

	onClickRemoveServiceButtonEvent : function(event) {
		var service = this.tablePanel.getSelectedService();
		if (this.removeServiceButton.hasClassName(Config.ClassName.BUTTON_DISABLED)) {
			return;
		}
		if (!confirm(Config.Text.ARE_YOU_REALLY_SURE_YOU_WANT_TO_REMOVE_THE_SERVICE)) {
			return;
		}
		this.doRemoveService(service.id);
	},

	doRemoveService : function(serviceId) {
		this.setStatusMessage(Config.Image.NOW_LOADING + Config.Text.NOW_REMOVING);
		this.onRowUnselected();
		this.tablePanel.stopEventListener();
		var parameter = {
			serviceId : serviceId
		};
		new Ajax.Request(Config.Url.REMOVE_SERVICE, {
			postBody : $H(parameter).toQueryString(),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				this.tablePanel.removeService(serviceId);
				this.setStatusMessage('');
				this.onRowUnselected();
			}.bind(this),
			onFailure : function(transport) {
			}.bind(this),
			onException : function(transport, e) {
			}.bind(this)
		});
	},

	onRowSelected : function() {
		this.editServiceButton.removeClassName(Config.ClassName.BUTTON_DISABLED);
		this.removeServiceButton.removeClassName(Config.ClassName.BUTTON_DISABLED);
	},

	onRowUnselected : function() {
		this.editServiceButton.addClassName(Config.ClassName.BUTTON_DISABLED);
		this.removeServiceButton.addClassName(Config.ClassName.BUTTON_DISABLED);
	},

	setStatusMessage : function(message) {
		this.statusArea.update(message || '');
	},

	loadSupportedLanguages : function() {
		new Ajax.Request(Config.Url.LOAD_SUPPORTED_LANGUAGES, {
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				this.addServicePopupPanel.setLanguages(response.contents.languages);
				this.editServicePopupPanel.setLanguages(response.contents.languages);
				this.addServiceButton.removeClassName(Config.ClassName.BUTTON_DISABLED);
			}.bind(this),
			onFailure : function(transport) {

			}.bind(this),
			onException : function(transport, e) {

			}.bind(this)
		});
	}
});