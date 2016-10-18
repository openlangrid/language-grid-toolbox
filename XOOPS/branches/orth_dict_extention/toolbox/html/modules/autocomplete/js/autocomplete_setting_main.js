//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides accurate
// translation using the autocomplete feature based on parallel texts and
// translation template.
// Copyright (C) 2010  CITY OF KYOTO
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
/** $Id: autocomplete_setting_main.js 3578 2010-03-30 11:00:34Z yoshimura $ */
Event.observe(window, 'load', function(){

	Event.observe('save_btn', 'click', function(){
		var ws = new SaveSettingClass();
		ws.submit();
	});

	Event.observe('cancel_btn', 'click', function(){
		document.location.href = './?page=setting';
	});

	Event.observe('selectall', 'change', function(){
		new SelectAllClass(this.checked);
	});

	$$('.list input').each(function(elem, idx) {
		if (elem.type == 'checkbox') {
			Event.observe(elem, 'change', function() {
				$('save_btn').removeClassName('btn_gray01');
				$('save_btn').addClassName('btn_blue01');
			});
		}
	});
});

var SaveSettingClass = Class.create({
	initialize: function() {

	},
	submit: function() {
		var parameters = Form.serializeElements($('autocompleteform').getInputs('checkbox'));
		new Ajax.Request('./?page=setting&ajax=save', {
			postBody : parameters,
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				if (response.status != 'OK') {
					alert(Const.Message.Error.SaveError);
				}
			}.bind(this),
			onException : function() {
			}.bind(this),
			onFailure : function() {
			}.bind(this),
			onComplete : function() {
				$('save_btn').removeClassName('btn_blue01');
				$('save_btn').addClassName('btn_gray01');
			}.bind(this)
		});
	},
	showMessage: function(message) {

	}
});

var SelectAllClass = Class.create({
	initialize: function(checked) {
		$$('.list input').each(function(elem, idx) {
			if (elem.type == "checkbox") {
				elem.checked = checked;
			}
		});
	}
});