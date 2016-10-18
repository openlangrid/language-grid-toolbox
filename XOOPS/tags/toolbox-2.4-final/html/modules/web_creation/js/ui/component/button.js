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

/**
 * 
 */
var AbstractButton = Class.create({
	
	id: null,
	
	ENABLED_CLASS_NAME: 'wc-basic-button',
	DISABLED_CLASS_NAME: 'wc-basic-button-disabled',

	/**
	 * Constructor
	 */
	initialize: function(id, action) {
		this.id = id;
		this.action = action || new NullAction();
		this.action._parent = this;
		this.initEventListeners();
	},
	
	initEventListeners: function() {
		$(this.id).observe('click', this.onClick.bind(this));
	},
	
	/**
	 * @param Event event
	 */
	onClick: function(event) {
		if (this.isEnabled()) {
			this.action.execute();
		}
	},
	
	/**
	 * @param bool disabled
	 * @return void
	 */
	setEnabled: function(flag) {
		
		if (flag) {
			// enabled
			$(this.id).addClassName(this.ENABLED_CLASS_NAME);
			$(this.id).removeClassName(this.DISABLED_CLASS_NAME);
			return;
		}

		// disabled
		$(this.id).addClassName(this.DISABLED_CLASS_NAME);
		$(this.id).removeClassName(this.ENABLED_CLASS_NAME);
	},
	
	/**
	 * @return bool
	 */
	isEnabled: function() {
		return !$(this.id).hasClassName(this.DISABLED_CLASS_NAME);
	},
	
	/**
	 * 
	 */
	update: function(html) {
		$(this.id).update(html);
	},
	
	/**
	 * 
	 */
	show: function() {
		$(this.id).show();
	},
	
	/**
	 * 
	 */
	hide: function() {
		$(this.id).hide();
	}
});

/**
 * Basic Button
 */
var BasicButton = Class.create(AbstractButton, {});

var BasicExpandButton = Class.create(BasicButton, {});

var BasicContractButton = Class.create(BasicButton, {});

var BasicToggleButton = Class.create(BasicButton, {});