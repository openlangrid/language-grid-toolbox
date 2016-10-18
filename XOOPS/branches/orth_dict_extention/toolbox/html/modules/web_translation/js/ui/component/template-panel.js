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

var TemplatePanel = Class.create(Observer, {

	id: 'wc-template-body',

	initialize: function() {
		this.initEventListeners();
		this.initText();
	},

	initEventListeners: function() {
		Event.observe(window, 'resize', this.adjustWidth.bind(this));
	},

	initText: function() {
//		var wrapper = document.createElement('div');
		var wrapper = new Element('div', {});

		wrapper.update(new Template('<span class="#{css}">#{text}</span>').evaluate({
			text: Resource.ADD_TEMPLATE_INIT_MSG,
			css: 'initialize-text'
		}));
		$('wc-template-area').update(wrapper);
	},

	update: function(o, arg) {
		Logger.info('TemplatePanel.update');

		var table = document.createElement('table');
		var tbody = document.createElement('tbody');

		Model.Template.get().reverse(false).each(function(template){
			tbody.appendChild(this.createRow(template));
		}.bind(this));
		table.appendChild(tbody);

		$('wc-template-area').update(table);
		this.adjustWidth();
	},

	createRow: function(template) {
		var tr = document.createElement('tr');

		tr.appendChild(this.createCheckCell(template));
		tr.appendChild(this.createEditableCell(template, 'source'));
		tr.appendChild(this.createEditableCell(template, 'target'));
		tr.appendChild(this.createDeleteCell(template));

		return tr;
	},

	createCheckCell: function(template) {
		var td = document.createElement('td');
		var input = document.createElement('input');
		input.setAttribute('type', 'checkbox');

		if (template.checked) {
			input.setAttribute('checked', 'checked');
		}

		input.onclick = function(event) {
//			template.checked = Event.element(event).checked;
			template.checked = $(input).checked;

			Model.Template.setChanged();
			Model.Template.notifyObservers();
		};

		td.appendChild(input);
		return td;
	},

	createEditableCell: function(template, type) {
		var contents = template[type];

		var td = document.createElement('td');
		td.className = 'wc-editable-cell';

		if (!contents) {
			contents = '---';
		}

		td.appendChild(document.createTextNode(contents));

		td.onclick = function(event) {
			Logger.info('TemplatePanel.createEditableCell::onclick');

			if ($(td).hasClassName('wc-editing-cell')) return;

			$(td).addClassName('wc-editing-cell');
			var textarea = document.createElement('textarea');
			textarea.appendChild(document.createTextNode(template[type]));

			var width = (td.getWidth() - 16) + 'px';
			var height = (td.getHeight() - 17) + 'px';
			$(textarea).setStyle({
				width: width,
				height: height
			});

			$(td).update(textarea);
			textarea.focus();

			textarea.onblur = function(event) {
				Logger.info('TemplatePanel.createEditableCell::onblur');

				template[type] = textarea.value;

				Model.Template.setChanged();
				Model.Template.notifyObservers();
			};
		};

		return td;
	},

	createDeleteCell: function(template) {
		var td = document.createElement('td');
		td.className = 'wc-delete-button-cell';

		var button = document.createElement('span');
		button.className = 'wc-basic-button';
		button.appendChild(document.createTextNode(Resource.DELETE));
		button.onclick = function(event) {
			Logger.info('TemplatePanel.update::deleteButton.onclick');

			Model.Template.remove(template);
			Model.Template.setChanged();
			Model.Template.notifyObservers();
		};

		td.appendChild(button);

		return td;
	},

	adjustWidth: function() {
		var cells = $$('#wc-template-area .wc-editable-cell');
		if (!cells.length) return;
		var avg = (cells[0].getWidth() + cells[1].getWidth()) / 2;
		cells.each(function(cell) {
			$(cell).setStyle({
				width: avg + 'px'
			});
		});
	}
});