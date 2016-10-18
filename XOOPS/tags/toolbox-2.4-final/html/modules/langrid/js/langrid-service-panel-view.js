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

var ServicePanelView = Class.create();

Object.extend(ServicePanelView.prototype, ServicePanel.prototype);
Object.extend(ServicePanelView.prototype, {
	makeAndBindTranslationCombinationCheckbox: function(controller, handler) {
		var checkbox = document.createElement('input');
		checkbox.id = this._this_id + ':combination';
		checkbox.setAttribute('type', 'checkbox');
		checkbox.setAttribute('value', 'on');
		checkbox.setAttribute('disabled', 'disabled');
		//checkbox.observe('click', handler.bindAsEventListener(controller, checkbox));
		Event.observe(checkbox, 'click', handler.bindAsEventListener(controller, checkbox));

		var span = document.createElement('span');
		Element.setStyle(span, 'margin-left:5px;');
		span.appendChild(checkbox);
		span.appendChild(document.createTextNode(Const.Label.CombinationCheckBox));

		if (this._afterInsertTargetElement != null) {
			new Insertion.After(this._afterInsertTargetElement, span);
		} else {
			this._thisPanel.appendChild(span);
		}
		return checkbox;
	},
	_initEvent: function() {
		this._thisPanel = document.createElement('div');
		this._thisPanel.id = this._this_id;
		Element.addClassName(this._thisPanel, 'clearfix');
		
		var controller = this;
		this._langridServiceInformations.each(function(service, index){
			var button = new ServiceButtonView();
			button.makeServiceButton(controller._thisPanel, service, controller._onServiceButtonSelectHandler, controller);
			controller._serviceButtonArray[service.service_id] = button;
		});
		this._interElement.appendChild(this._thisPanel);
	}
});
var ServiceButtonView = Class.create();
Object.extend(ServiceButtonView.prototype, ServiceButton.prototype);
Object.extend(ServiceButtonView.prototype, {
	makeServiceButton: function(parentElem, serviceInfo, clickHandler, controller) {
		this._parentElem = parentElem;
		this._serviceInfo = serviceInfo;
		this._this_id = this._parentElem.id + ':' + this._serviceInfo.service_id;
	//	this._makePopupLayer();
		this._buttonElem = this._showButton();

//		this._buttonElem.observe('click', clickHandler.bindAsEventListener(controller));
		return this._buttonElem;
	},
	setButtonMode: function(mode) {
		if (mode == 'active') {
			Element.removeClassName(this._buttonElem, this.__CSS_OFF);
			Element.addClassName(this._buttonElem, this.__CSS_ON);
			this._buttonElem.show();
		} else if (mode == 'display') {
			Element.removeClassName(this._buttonElem, this.__CSS_ON);
			Element.addClassName(this._buttonElem, this.__CSS_OFF);
//			this._buttonElem.show();
			this._buttonElem.hide();
		} else if (mode == 'hide') {
			Element.removeClassName(this._buttonElem, this.__CSS_ON);
			Element.addClassName(this._buttonElem, this.__CSS_OFF);
			this._buttonElem.hide();
		}
	}
});