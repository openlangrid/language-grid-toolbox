//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
var BBSPostPage = Class.create();
BBSPostPage.prototype = {

	loadType : null,
	pullClient : null,

	radioButtonClassName : 'bbs-pull-radio-button',

	// BBS refresh button
	refreshButton : null,

	/**
	 * 
	 * @return
	 */
	initialize : function(topicId, offset, limit, timestamp, users, results) {
		this.refreshButton = $('bbs-pull-refresh');
		this.initEventListener();
		this.pullClient = new BBSPullClient(topicId, offset, limit, timestamp);
		this.pullClient.setOnlineUsers(users);
		this.pullClient.setResults(results);
		this.pullClient.setState(this.getLoadType());
		this.pullClient.start();
	},

	/**
	 * 
	 * @return
	 */
	initEventListener : function() {
		$$('.bbs-pull-radio-label').each(function(element) {
			element.observe('click', this.loadTypeClickEvent.bindAsEventListener(this));
		}.bind(this));
		this.refreshButton.observe('click', this.refreshButtonClickEvent.bindAsEventListener(this));
		Event.observe(document, 'refreshButton:changed', this.loadTypeClickEvent.bindAsEventListener(this))
		Event.observe(document, 'refreshButton:disabled', this.setRefreshButtonDisabled.bind(this, true));
		Event.observe(document, 'refreshButton:abled', this.setRefreshButtonDisabled.bind(this, false));
	},

	/**
	 * 
	 * @param disabledFlag
	 * @return
	 */
	setRefreshButtonDisabled : function(disabledFlag) {
		if (disabledFlag) {
			this.refreshButton.addClassName('toolbox-common-button-disabled');
		} else {
			this.refreshButton.removeClassName('toolbox-common-button-disabled');
		}
	},
	
	/**
	 * 
	 * @param event
	 * @return
	 */
	refreshButtonClickEvent : function(event) {
		if (this.refreshButton.hasClassName('toolbox-common-button-disabled')) {
			return;
		}
		this.pullClient.updateAndRefresh();
		this.refreshButton.addClassName('toolbox-common-button-disabled');
	},
	
	/**
	 * 
	 * @param event
	 * @return
	 */
	loadTypeClickEvent : function(event) {
		this.pullClient.setState(this.getLoadType());
	},
	
	/**
	 * 
	 * @return
	 */
	getLoadType : function() {
		var loadType;
		$$('.' + this.radioButtonClassName).each(function(element){
			if (element.checked) {
				loadType = element.value;
				throw $break;
			}
		}.bind(this));
		
		return loadType;
	},
	
	setFileListController : function(fileListController) {
		this.pullClient.setFileListController(fileListController);
	}
}