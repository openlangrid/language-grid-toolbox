//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
/* $Id: NaviBox.js 4540 2010-10-07 04:01:57Z uehara $ */

var NaviBox = Class.create();
NaviBox.prototype = {

	initialize : function() {
		this.categorySelector = new JumpSelectorCategory(this._ID_.categorySelector, NaviSelectorConst.Resource, NaviSelectorConst.Selected.categoryId);
		this.forumSelector = new JumpSelectorForum(this._ID_.forumSelector, NaviSelectorConst.Resource, NaviSelectorConst.Selected.forumId);
		this.topicSelector = new JumpSelectorTopic(this._ID_.topicSelector, NaviSelectorConst.Resource, NaviSelectorConst.Selected.topicId);

		this.categorySelector.addObserver(this.forumSelector);
		this.forumSelector.addObserver(this.topicSelector);

		this.categorySelector.notify();
		this.forumSelector.notify();
		this.topicSelector.notify();
	},

	_ID_ : {
		categorySelector : 'jumpbox_category',
		forumSelector : 'jumpbox_forum',
		topicSelector : 'jumpbox_topic'
	}
};

var NaviSelector = Class.create();
NaviSelector.prototype = {

	element : null,
	resource : null,
	selectedId : null,

	initialize : function(id, resource, selectedId) {
		this.observers = new Array();
		this.element = $(id);
		this.element.observe('change', this.onChange.bindAsEventListener(this));
		this.resource = resource;
		this.selectedId = selectedId;
	},

	onChange : function(event) {
		Event.stop(event);
		this.notify();
	},

	addObserver : function(observer) {
		this.observers.push(observer);
	},

	notify : function() {
		this.observers.each(function(observer) {
			observer.update(this.element.value);
		}.bind(this));
	},

	update : function(parentId) {
		if (parentId > 0) {
			this.show();
			this.constructOptions(parentId);
		} else {
			this.clear();
			this.hide();
		}

		this.notify();
	},

	constructOptions : function(parentId) {
		this.clear();
	},

	clear : function() {
		this.element.innerHTML = '';
	},

	_opt : function(val, label, selected) {
		var option = document.createElement('option');
		option.setAttribute('value', val);
		if (selected) {
			option.setAttribute('selected', 'selected');
		}
		option.innerHTML = label;
		return option;
	},

	show : function() {
//		$(this.element.id + '_wrapper').show();
		this.element.disabled = false;
	},

	hide : function() {
//		$(this.element.id + '_wrapper').hide();
		this.element.disabled = true;
	}
}

var JumpSelectorCategory = Class.create();
Object.extend(JumpSelectorCategory.prototype, NaviSelector.prototype);
Object.extend(JumpSelectorCategory.prototype, {

	initialize : function() {
		NaviSelector.prototype.initialize.apply(this, arguments);
		this.clear();
		this.resource.categoryList.each(function(category) {
			this.element.appendChild(this._opt(category.id, category.text, (category.id == this.selectedId)));
		}.bind(this));
	}
});

var JumpSelectorForum = Class.create();
Object.extend(JumpSelectorForum.prototype, NaviSelector.prototype);
Object.extend(JumpSelectorForum.prototype, {

	initialize : function() {
		NaviSelector.prototype.initialize.apply(this, arguments);
		this.hide();
	},

	constructOptions : function(parentId) {
		NaviSelector.prototype.constructOptions.apply(this, arguments);

		this.element.appendChild(this.getDefaultOpt());

		this.resource.forumList.each(function(forum) {
			if (forum.parentId == parentId) {
				this.element.appendChild(this._opt(forum.id, forum.text, (forum.id == this.selectedId)));
			}
		}.bind(this));
	},

	getDefaultOpt : function() {
		return this._opt(this.resource.forumList[0].id, this.resource.forumList[0].text);
	}
});

var JumpSelectorTopic = Class.create();
Object.extend(JumpSelectorTopic.prototype, NaviSelector.prototype);
Object.extend(JumpSelectorTopic.prototype, {

	initialize : function() {
		NaviSelector.prototype.initialize.apply(this, arguments);
		this.hide();
	},

	constructOptions : function(parentId) {
		NaviSelector.prototype.constructOptions.apply(this, arguments);

		this.element.appendChild(this.getDefaultOpt());

		this.resource.topicList.each(function(topic) {
			if (topic.parentId == parentId) {
				this.element.appendChild(this._opt(topic.id, topic.text, (topic.id == this.selectedId)));
			}
		}.bind(this));
	},

	getDefaultOpt : function() {
		return this._opt(this.resource.topicList[0].id, this.resource.topicList[0].text);
	}
});
