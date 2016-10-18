//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2010 CITY OF KYOTO All Rights Reserved.
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
Object.extend(LightPopupPanel.prototype,Panel.prototype);
Object.extend(LightPopupPanel.prototype, {
	
	id : null,
	
	panelId : null,
	panel : null,
	maskId : null,
	mask : null,
	
	eventCaches : new Hash(),
	
	// ステータス、エラーメッセージを表示するID
	statusMessageId : null,
	errorMessageId : null,
	
	opacity : null,

	/**
	 * 初期化処理
	 */
	initialize : function() {
		if (!this.element) {
			new Insertion.Bottom($$('body')[0], new Template(Templates.ImportedServices.PopupPanel.base).evaluate({
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
	},
	
	/**
	 * イベントの初期化
	 */
	initEventListeners : function() {
		this.addEventCache(window, 'scroll', 'onScrollWindowEvent');
		this.addEventCache(window, 'resize', 'onResizeWindowEvent');
	},

	/**
	 * ポップアップをShowする
	 */
	show : function() {
		this.setupMask();
		this.panel.update(this.getBody());
		this.element.show();
		this.adjustPanel();
		this.startEventObserving();
		this.onShowPanel();
	},
	
	/**
	 * ポップアップがShowしたときに呼ばれるイベント
	 * onFocus、サブクラスでのポップアップ内のイベントセットなどにどうぞ
	 */
	onShowPanel : function() {
		
	},

	/**
	 * ポップアップをHideする
	 */
	hide : function() {
		this.stopEventObserving();
		this.element.hide();
		this.onHidePanel();
	},

	/**
	 * ポップアップがHideしたときに呼ばれるイベント
	 * サブクラスでのポップアップ内のイベント解除などにどうぞ
	 */
	onHidePanel : function() {
		
	},
	
	/**
	 * ポップアップ以外を黒くする
	 */
	setupMask : function() {
//		mdiv.style.filter = 'alpha(opacity=' + (this.opacity * 10) + ')';
//		mdiv.style.MozOpacity = this.opacity / 10;
//		mdiv.style.opacity = this.opacity / 10;
		this.mask.setStyle({
			filter : 'alpha(opacity=' + (this.opacity * 10) + ')'
			, MozOpacity : this.opacity / 10
			, opacity : this.opacity / 10
			, top : this.getWindowScrollOffsets().top+'px'
			, left: this.getWindowScrollOffsets().left+'px'
			, width: this.getWindowDimensions().width + 'px'
			, height: this.getWindowDimensions().height + 'px'
		});
		
	},
	
	/**
	 * ポップアップのパネルの位置を真ん中にする
	 */
	adjustPanel : function() {
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
	
	/**
	 * ポップアップの中身
	 * 要Override
	 */
	getBody : function() {
		return '';
	},
	
	/**
	 * Windowがスクロールされたときに呼ばれるイベント
	 */
	onScrollWindowEvent : function(event) {
		this.mask.setStyle({
			top : this.getWindowScrollOffsets().top+'px'
			, left : this.getWindowScrollOffsets().left+'px'
		});
	},
	
	/**
	 * Windowがリサイズされたときに呼ばれるイベント
	 */
	onResizeWindowEvent : function(event) {
		this.mask.setStyle({
			width : this.getWindowDimensions().width + 'px'
			,height : this.getWindowDimensions().height + 'px'
		});
	},

	/**
	 * Windowの幅を返す
	 */
	getWindowDimensions : function() {
		return document.viewport.getDimensions();
	},
	
	/**
	 * WindowのScroll位置を返す
	 */
	getWindowScrollOffsets : function() {
		return document.viewport.getScrollOffsets();
	},
	
	/**
	 * StatusメッセージとError Messageを消去
	 */
	clearMessageArea : function() {
		this.setStatusMessage();
		this.setErrorMessage();
	},

	/**
	 * Status MessageをSetする
	 */
	setStatusMessage : function(message) {
		$(this.statusMessageId).update(message || '');
	},

	/**
	 * Error MessageをSetする
	 */
	setErrorMessage : function(message) {
		$(this.errorMessageId).update(message || '');
	}
});