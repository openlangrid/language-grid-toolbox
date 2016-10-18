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
/* $Id: JumpBox.js 4540 2010-10-07 04:01:57Z uehara $ */

var JumpBox = Class.create();
JumpBox.prototype = {

	initialize : function() {
		this.categorySelector = new JumpSelectorCategory(this._ID_.categorySelector);
		this.forumSelector = new JumpSelectorForum(this._ID_.forumSelector);
		this.topicSelector = new JumpSelectorTopic(this._ID_.topicSelector);

		this.categorySelector.addObserver(this.forumSelector);
		this.forumSelector.addObserver(this.topicSelector);

		$(this._ID_.jumpButton).observe('click', this.onJump.bindAsEventListener(this));
	},

	onJump : function(event) {
		Event.stop(event);

		var id = '';
		var typeCode = '';
		if ($F(this._ID_.topicSelector) > 0) {
			id = $F(this._ID_.topicSelector);
			typeCode = 'post_create';
		} else if ($F(this._ID_.forumSelector) > 0) {
			id = $F(this._ID_.forumSelector);
			typeCode = 'topic_create';
		} else if ($F(this._ID_.categorySelector) > 0) {
			id = $F(this._ID_.categorySelector);
			typeCode = 'forum_create';
		} else {
			id = '0';
			typeCode = 'category_create';
		}

		$(this._ID_.postParam_id).value = id;
		$(this._ID_.postParam_typeCode).value = typeCode;
		$(this._ID_.postForm).submit();
	},

	_ID_ : {
		categorySelector : 'jumpbox_category',
		forumSelector : 'jumpbox_forum',
		topicSelector : 'jumpbox_topic',
		jumpButton : 'jumpbox_button',
		postParam_id : 'id',
		postParam_typeCode : 'type_code',
		postForm : 'jump_box_form'
	}
};

var JumpSelector = Class.create();
JumpSelector.prototype = {

	element : null,

	initialize : function(id) {
		this.observers = new Array();
		this.element = $(id);
		this.element.observe('change', this.onChange.bindAsEventListener(this));
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
//		alert('selfId='+this.element.id+', parentId='+parentId);
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
Object.extend(JumpSelectorCategory.prototype, JumpSelector.prototype);
Object.extend(JumpSelectorCategory.prototype, {

	initialize : function() {
		JumpSelector.prototype.initialize.apply(this, arguments);
		this.clear();
		JumpBoxConst.Resource.categoryList.each(function(category) {
			this.element.appendChild(this._opt(category.id, category.text));
		}.bind(this));
	}
});

var JumpSelectorForum = Class.create();
Object.extend(JumpSelectorForum.prototype, JumpSelector.prototype);
Object.extend(JumpSelectorForum.prototype, {

	initialize : function() {
		JumpSelector.prototype.initialize.apply(this, arguments);
		this.hide();
	},

	constructOptions : function(parentId) {
		JumpSelector.prototype.constructOptions.apply(this, arguments);

		this.element.appendChild(this.getDefaultOpt());

		JumpBoxConst.Resource.forumList.each(function(forum) {
			if (forum.parentId == parentId) {
				this.element.appendChild(this._opt(forum.id, forum.text));
			}
		}.bind(this));
	},

	getDefaultOpt : function() {
		return this._opt(JumpBoxConst.Resource.forumList[0].id, JumpBoxConst.Resource.forumList[0].text);
	}
});

var JumpSelectorTopic = Class.create();
Object.extend(JumpSelectorTopic.prototype, JumpSelector.prototype);
Object.extend(JumpSelectorTopic.prototype, {

	initialize : function() {
		JumpSelector.prototype.initialize.apply(this, arguments);
		this.hide();
	},

	constructOptions : function(parentId) {
		JumpSelector.prototype.constructOptions.apply(this, arguments);

		this.element.appendChild(this.getDefaultOpt());

		JumpBoxConst.Resource.topicList.each(function(topic) {
			if (topic.parentId == parentId) {
				this.element.appendChild(this._opt(topic.id, topic.text));
			}
		}.bind(this));
	},

	getDefaultOpt : function() {
		return this._opt(JumpBoxConst.Resource.topicList[0].id, JumpBoxConst.Resource.topicList[0].text);
	}
});
