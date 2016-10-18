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

var ApplyTemplatePanel = Class.create(Observer, {

	id: 'wc-apply-template-body',

	initialize: function() {
//		var wrapper = document.createElement('div');
		var wrapper = new Element('div', {});

		wrapper.update(new Template('<span class="#{css}">#{text}</span>').evaluate({
			text: Resource.APPLY_TEMPLATE_INIT_MSG,
			css: 'initialize-text'
		}));
		$(this.id).update(wrapper);
	},

	update: function(o, arg) {
		Logger.info('ApplyTemplatePanel.update');

		var wrapper = document.createElement('div');
		o.get().reverse(false).each(function(t) {
			var div = document.createElement('div');
			var image = document.createElement('img');
			image.setAttribute('src', './image/icon/trash.gif');

			div.appendChild(document.createTextNode(t.name));
			div.appendChild(image);

			image.onclick = function() {
				Model.ApplyTemplate.remove(t);
				Model.ApplyTemplate.setChanged();
				Model.ApplyTemplate.notifyObservers();
			};

			wrapper.appendChild(div);
		}.bind(this));

		$(this.id).update(wrapper);
	}
});