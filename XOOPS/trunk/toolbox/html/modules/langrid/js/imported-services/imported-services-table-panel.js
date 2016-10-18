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
var ImportedServicesTablePanel = Class.create();
Object.extend(ImportedServicesTablePanel.prototype, TablePanel.prototype);
Object.extend(ImportedServicesTablePanel.prototype, {

	eventCache : new Hash(),

	id : null,
	rowIdPrefix : null,
	tableId : null,
	rowSelectedClassName : null,
	services : new Array(),

	init : function() {
		this.loadServices();
	},

	initEventListener : function() {
		this.eventCache.set('onClickRowEvent', this.onClickRowEvent.bindAsEventListener(this));
		this.services.each(function(service){
			$(this.rowIdPrefix + service.id).observe('click', this.eventCache.get('onClickRowEvent'));
		}.bind(this));
	},

	/**
	 * Services
	 *
	 * status :
	 * message :
	 * contents : {
	 * 	services : [{
	 * 		id : String
	 * 		name : String
	 * 		type : String
	 * 		endpointUrl : ''
	 * 		provider : ''
	 * 		copyright : ''
	 * 		license : ''
	 * 		languagePaths : [
	 * 			{
	 * 				from : {name : Japanese, code : ja},
	 * 				to : {name : English, code : en},
	 * 				bidirectional : true or false
	 * 			}
	 * 		]
	 * 		languages : [
	 * 			{
	 * 				code : ja,
	 * 				name : Japanese
	 * 			},
	 * 		]
	 * }]
	 * }
	 */
	loadServices : function() {
		this.element.innerHTML = Config.Image.NOW_LOADING + Config.Text.NOW_LOADING;
		new Ajax.Request(Config.Url.LOAD_SERVICES, {
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				this.services = response.contents.services;
				this.draw();
			}.bind(this),
			onException : function(transport, e) {
//				this.element.innerHTML = e.message;
//				console.error(e);
			}.bind(this),
			onFailure : function(e) {
//				this.element.innerHTML = e.message;
//				console.error(e);
			}.bind(this)
		});
	},

	addService : function(service) {
		this.services.push(service);
		this.draw();
	},

	editService : function(service) {
		this.services.each(function(thisService, i){
			if (service.id != thisService.id) {
				return;
			}

			this.services[i] = service;
			throw $break;
		}.bind(this));
		this.draw();
	},

	removeService : function(serviceId) {
		this.services.each(function(service, i){
			if (service.id == serviceId) {
				this.services.splice(i, 1);
				throw $break;
			}
		}.bind(this));
		this.draw();
	},

	getSelectedService : function() {
		var serviceId;

		this.getRowElements().each(function(rowElement){
			if (!rowElement.hasClassName(this.rowSelectedClassName)) {
				return;
			}
			serviceId = rowElement.id.replace(this.rowIdPrefix, '');
			throw $break;
		}.bind(this));
		if (!serviceId) {

			return null;
		}

		this.services.each(function(thisService) {

			if (serviceId != thisService.id) {
				return;
			}

			service = thisService;
			throw $break;
		});

		return service || null;
	},

	draw : function() {

		try {
			this.stopEventListener();
		} catch (e) {
			;
		}

		if (!this.services.length) {

			this.element.update(Config.Text.NO_IMPORTED_SERVICES);
			return;
		}

		var html = new Array();

		// header
		html.push(this.createHeader());

		// body
		this.services.each(function(service) {
			html.push(this.createBody(service));
		}.bind(this));

		// footer
		html.push(this.createFooter());

		this.element.update(html.join(''));

		this.initEventListener();
	},

	stopEventListener : function() {
		this.services.each(function(service){
			$(this.rowIdPrefix + service.id).stopObserving('click', this.eventCache.get('onClickRowEvent'));
		}.bind(this));
	},

	onClickRowEvent : function(event) {
		var rowId;
		var eventElement = Event.element(event);
		this.getRowElements().each(function(trElement){
			trElement.removeClassName(this.rowSelectedClassName);

			if (!rowId && trElement.descendants().indexOf(eventElement) != -1) {
				rowId = trElement.id;
			}
		}.bind(this));

		$(rowId).addClassName(this.rowSelectedClassName);
		document.fire(Config.FireEventName.TABLE_ROW_CLICKED);
	},

	getRowElements : function() {
		return $$('#' + this.tableId + ' tr');
	},

	createHeader : function() {
		return new Template(Templates.ImportedServices.Table.header).evaluate({
			tableId : this.tableId,
			serviceName : Config.Text.SERVICE_NAME,
			serviceType : Config.Text.SERVICE_TYPE,
			languages : Config.Text.LANGUAGES,
			endpointUrl : Config.Text.ENDPOINT_URL,
			provider : Config.Text.PROVIDER,
			copyright : Config.Text.COPYRIGHT,
			registrationDate : Config.Text.REGISTRATION_DATE
		});
	},

	createBody : function(service) {

		var languages = new Array();
		var serviceType = '';

		switch (service.type) {
		case ServiceType.DICTIONARY:
			service.languages.each(function(language){
				languages.push(language.name);
			});
			serviceType = Config.Text.DICTIONARY;
			break;
		case ServiceType.TRANSLATOR:
			service.languagePaths.each(function(languagePath){
				var connector = (languagePath.bidirectional) ? Config.Text.BIDIRECTIONAL :  Config.Text.MONODIRECTIONAL;
				languages.push(languagePath.from.name + connector + languagePath.to.name);
			});
			serviceType = Config.Text.TRANSLATOR;
			break;
		}

		return new Template(Templates.ImportedServices.Table.body).evaluate({
			rowId : this.rowIdPrefix + service.id,
			serviceName : service.name,
			serviceType : serviceType,
			languages : languages.join(', '),
			endpointUrl : service.endpointUrl,
			provider : service.provider || Config.Text.NULL_TABLE_VALUE,
			copyright : service.copyright || Config.Text.NULL_TABLE_VALUE,
			registrationDate : service.registrationDate
		});
	},

	createFooter : function() {
		return Templates.ImportedServices.Table.footer;
	},

	changeMode : function(mode) {
		switch (mode) {
		case Mode.ADMIN:
			break;
		case Mode.USER:
			break;
		}
	}
});