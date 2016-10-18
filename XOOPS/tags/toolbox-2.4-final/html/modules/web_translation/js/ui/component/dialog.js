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

var Dialog = Class.create({

	id: 'wc-dialog',

	MAIN_ID: 'wc-dialog-main',
	MASK_ID: 'wc-dialog-mask',
	
	TITLE_ID: 'wc-dialog-title',
	BODY_ID: 'wc-dialog-body',
	SET_ID: 'wc-dialog-button-set',
	
	body: null,
	delegate: null,
	
	buttonSetManager: null,

	// 0 to 1.0
	OPACITY : 0.6,
	
	// pixel
	WIDTH : 410,
	
	STATIC: {
		init: false
	},
	
	/**
	 * Contructor
	 */
	initialize : function(obj) {
		Logger.info('Dialog.initialize');
		Logger.info(obj);

		var obj = Object.extend({
			title: '',
			body: '',
			buttonSet: null,
			delegate: null
		}, obj || {});
		
		this.setTitle(obj.title);
		this.setBody(obj.body);
		this.setButtonSet(obj.buttonSet);
		this.setDelegate(obj.delegate);
		
		this.initEventListeners();
	},

	/**
	 * setup window event
	 */
	initEventListeners : function() {
		if (!this.STATIC.init) {
			Event.observe(window, 'scroll', this.onScrollWindowEvent.bind(this));
			Event.observe(window, 'resize', this.onResizeWindowEvent.bind(this));
			this.STATIC.init = true;
		}
		
		$(Dialog.ButtonSetManager.OK_ID).onclick = function(event) {
			if (this.buttonSetManager.okButton.isEnabled()) {
				this.getDelegate().okClicked(event);
			}
		}.bind(this);

		$(Dialog.ButtonSetManager.CANCEL_ID).onclick = function(event) {
			if (this.buttonSetManager.cancelButton.isEnabled()) {
				this.getDelegate().cancelClicked(event);
			}
		}.bind(this);
	},

	/**
	 * 
	 */
	show : function() {
		this.setupMask();
		this.update();
		$(this.id).show();
		this.setupPanel();
	},

	/**
	 * hide
	 */
	hide: function() {
		this.clear();
		$(this.id).hide();
		this.update();
		this.getDelegate().onHidePanel();
	},
	
	/**
	 * build
	 */
	update: function() {
		$(this.TITLE_ID).update(this.title);
		$(this.BODY_ID).update(this.body);
		this.buttonSetManager.show();
	},
	
	/**
	 * clear all
	 */
	clear: function() {
		this.setBody('');
		this.setTitle('');
		this.setButtonSet(null);
		this.update();
		this.buttonSetManager.show();
	},

	/**
	 * 
	 */
	setTitle : function(title) {
		this.title = title;
	},

	/**
	 * 
	 */
	setBody : function(body) {
		this.body = body;
	},
	
	/**
	 * 
	 */
	setButtonSet: function(buttonSet) {
		this.buttonSetManager = Dialog.ButtonSetManager.getInstance(buttonSet);
	},

	/**
	 * background
	 */
	setupMask : function() {
		$(this.MASK_ID).setStyle({
			filter : 'alpha(opacity=' + (this.OPACITY * 10) + ')'
			, position: 'absolute'
			, zIndex: 5
			, MozOpacity : this.OPACITY / 10
			, opacity : this.OPACITY
			, top : this.getWindowScrollOffsets().top + 'px'
			, left: this.getWindowScrollOffsets().left + 'px'
			, width: this.getWindowDimensions().width + 'px'
			, height: this.getWindowDimensions().height + 'px'
		});
	},

	/**
	 * addjust position
	 */
	setupPanel : function() {
		Logger.info('Dialog.setupPanel');
		
		$(this.MAIN_ID).setStyle({
			width : this.WIDTH + 'px'
			, position: 'absolute'
			, zIndex: 10
		});

		var vp = this.getWindowDimensions();
		var vp_sc = this.getWindowScrollOffsets();
		var left = ((vp.width - $(this.MAIN_ID).offsetWidth) / 2) + vp_sc.left;
		var top = ((vp.height - $(this.MAIN_ID).offsetHeight) / 2) + vp_sc.top;

		if((top + $(this.MAIN_ID).offsetHeight - vp_sc.top) > vp.height){
			top = vp.height - $(this.MAIN_ID).offsetHeight + vp_sc.top;
		}

		if((left + $(this.MAIN_ID).offsetWidth - vp_sc.left) > vp.width){
			left = vp.width - $(this.MAIN_ID).offsetWidth + vp_sc.left;
		}

		$(this.MAIN_ID).setStyle({
			left: left + 'px'
			, top: top + 'px'
		});
	},

	/**
	 * 
	 */
	onScrollWindowEvent : function(event) {
		$(this.MASK_ID).setStyle({
			top : this.getWindowScrollOffsets().top+'px'
			, left : this.getWindowScrollOffsets().left+'px'
		});
	},

	/**
	 * 
	 */
	onResizeWindowEvent : function(event) {
		$(this.MASK_ID).setStyle({
			width : this.getWindowDimensions().width + 'px'
			, height : this.getWindowDimensions().height + 'px'
		});
	},

	/**
	 * 
	 */
	getWindowDimensions : function() {
		return document.viewport.getDimensions();
	},

	/**
	 * 
	 */
	getWindowScrollOffsets : function() {
		return document.viewport.getScrollOffsets();
	},
	
	getDelegate: function() {
		var delegate = Object.extend({
			okClicked: function(){},
			cancelClicked: function(){},
			onHidePanel: function(){}
		}, this.delegate || {});
		
		return delegate;
	},
	
	/**
	 * @param delegate
	 */
	setDelegate: function(delegate) {
		Logger.info('Dialog.setDelegate');
		Logger.info(delegate);

		this.delegate = delegate;
	}
});

Dialog.ButtonSet = {
	OK_CANCEL : 1
};

Dialog.ButtonSetManager = Class.create({
	
	OK_ID: 'wc-dialog-ok',
	CANCEL_ID: 'wc-dialog-cancel',
	
	okButton: null,
	cancelButton: null,
	
	/**
	 * 
	 */
	getInstance: function(buttonSet) {
		switch (buttonSet) {
		case Dialog.ButtonSet.OK_CANCEL:
			return new Dialog.OkCancelButtonSetManager();
		default:
			return new Dialog.NullButtonSetManager();
		}
	},
	
	/**
	 * 
	 */
	show: function() {
		this.hideAll();
	},
	
	hideAll: function() {
		$(this.OK_ID).show();
		$(this.CANCEL_ID).show();
	}
});

// static
Dialog.ButtonSetManager.OK_ID = Dialog.ButtonSetManager.prototype.OK_ID;
Dialog.ButtonSetManager.CANCEL_ID = Dialog.ButtonSetManager.prototype.CANCEL_ID;
Dialog.ButtonSetManager.getInstance = Dialog.ButtonSetManager.prototype.getInstance;

Dialog.NullButtonSetManager = Class.create(Dialog.ButtonSetManager, {
	
	/**
	 * 
	 */
	show: function($super) {
		$super();
	}
});

Dialog.OkCancelButtonSetManager = Class.create(Dialog.ButtonSetManager, {

	initialize: function($super) {
		$super();
		this.okButton = UiFactory.getInstance().createButton(this.OK_ID);
		this.cancelButton = UiFactory.getInstance().createButton(this.CANCEL_ID);
	},
	
	/**
	 * 
	 */
	show: function($super) {
		$super();
		this.okButton.show();
		this.cancelButton.show();
	}
});