//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// glossaries.
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
/**
 * @author kitajima
 */
var LightPopupPanel = Class.create();
Object.extend(LightPopupPanel.prototype, Panel.prototype);
Object.extend(LightPopupPanel.prototype, {

	id : 'light-popup-panel-wrapper',

	panelId : 'light-popup-panel',
	panel : null,
	maskId : 'light-popup-panel-mask',
	mask : null,

	opacity : 0.7,

	WIDTH : '410',
	
	initialize : function() {
		Panel.prototype.initialize.apply(this, arguments);
		this.init();
	},

	init : function() {
		if (!this.element) {
			new Insertion.Bottom($$('body')[0], new Template('<div id="#{id}"><div id="#{panelId}"></div>' + '<div id="#{maskId}"></div></div>').evaluate({
				id : this.id,
				panelId : this.panelId,
				maskId : this.maskId
			}));
			this.element = $(this.id);
		}

		this.panel = $(this.panelId);
		this.mask = $(this.maskId);
		this.initEventListeners();
		this.hide();
		this.initStyle();
	},
	
	initStyle : function() {
		this.panel.setStyle({
			border : '2px solid #0667B4'
			, padding : '4px'
			, zIndex : 10
			, position : 'absolute'
			, background : '#f5f5f5'
		});
		this.mask.setStyle({
			background : '#000'
			, position : 'absolute'
			, zIndex : 5
			, filter : 'alpha(opacity=' + (this.opacity * 10) + ')'
			, MozOpacity : this.opacity / 10
			, opacity : this.opacity / 10
		});
	},

	initEventListeners : function() {
		this.addEvent('onScrollWindowEvent', window, 'scroll', this.onScrollWindowEvent.bindAsEventListener(this));
		this.addEvent('onResizeWindowEvent', window, 'resize', this.onResizeWindowEvent.bindAsEventListener(this));
	},

	show : function() {
		this.setupMask();
		this.panel.setStyle({
			width : this.WIDTH + 'px'
		})
		this.panel.update(this.getBody());
		this.element.show();
		this.setupPanel();
		this.startEventObserving();
		this.onShowPanel();
	},

	onShowPanel : function() {
	},

	hide : function() {
		this.stopEventObserving();
		this.element.hide();
		this.panel.update('');
		this.onHidePanel();
	},

	onHidePanel : function() {
	},
	
	setupMask : function() {
		this.mask.setStyle({
			top : this.getWindowScrollOffsets().top+'px'
			, left: this.getWindowScrollOffsets().left+'px'
			, width: this.getWindowDimensions().width + 'px'
			, height: this.getWindowDimensions().height + 'px'
		});

	},

	setupPanel : function() {
		var vp = this.getWindowDimensions();
		var vp_sc = this.getWindowScrollOffsets();
		var left = ((vp.width - this.panel.offsetWidth) / 2) + vp_sc.left;
		var top = ((vp.height - this.panel.offsetHeight) / 2) + vp_sc.top;

		if((top + this.panel.offsetHeight - vp_sc.top) > vp.height){
			top = vp.height - this.panel.offsetHeight + vp_sc.top;
		}

		if((left + this.panel.offsetWidth - vp_sc.left) > vp.width){
			left = vp.width - this.panel.offsetWidth + vp_sc.left;
		}

		this.panel.setStyle({
			left : left + 'px'
			,top : top + 'px'
		});
	},

	getBody : function() {
		return '';
	},

	onScrollWindowEvent : function(event) {
		this.mask.setStyle({
			top : this.getWindowScrollOffsets().top+'px'
			, left : this.getWindowScrollOffsets().left+'px'
		});
	},

	onResizeWindowEvent : function(event) {
		this.mask.setStyle({
			width : this.getWindowDimensions().width + 'px'
			,height : this.getWindowDimensions().height + 'px'
		});
	},

	getWindowDimensions : function() {
		return document.viewport.getDimensions();
	},

	getWindowScrollOffsets : function() {
		return document.viewport.getScrollOffsets();
	}
});