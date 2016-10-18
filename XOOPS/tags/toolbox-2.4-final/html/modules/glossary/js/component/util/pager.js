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
var Pager = Class.create();
Pager.prototype = {
	
	totalItems : 0,
	perPage : 10,
	delta : 3,

	currentPage : 0,

	previewId : 'preview',
	pagerIdPrefix : 'page-',
	nextId : 'next',

	commonClassName : 'pager-element',
	clickableClassName : 'pager-clickable',
	disableClassName : 'pager-disable',

	initialize : function() {
		
	},
	
	isLastComplete : function() {
		return (this.totalItems % this.perPage == 0);
	},
	
	isFirstPage : function() {
		return (this.currentPage == 0);
	},
	
	isLastPage : function() {
		return ((this.currentPage - 0 + 1) == this.getTotalPages());
	},
	
	getTotalPages : function() {
		return Math.ceil(this.totalItems / this.perPage);
	},
	
	getStart : function() {
		return ((this.currentPage) * this.perPage);
	},

	getLength : function() {
		return (this.getStart() + Math.min(this.totalItems - this.getStart(), this.perPage));
	},
	
	// ki ta na i
	getHtml : function() {

		var totalPages = this.getTotalPages();
		var before = this.currentPage;
		var after = totalPages - this.currentPage - 1;
		
		var html = [];
		var previewAssign = {
			id : this.previewId,
			className : this.clickableClassName + ' ' + this.commonClassName
		};
		if (this.isFirstPage()) {
			previewAssign.className = this.disableClassName;
		}
		html.push(new Template(this.Templates.header).evaluate(previewAssign));
		for (var i = 0; i < totalPages; i++) {
			if (totalPages > this.delta * 2 + 1) {
				if (before >= this.delta + 2 && i == 1) {
					html.push(this.Templates.blank);
					i = this.currentPage - this.delta;
					continue;
				}
				if (after >= this.delta + 2 && i == this.currentPage + this.delta - 1) {
					html.push(this.Templates.blank);
					i = totalPages - 2;
					continue;
				}
			}

			var assign = {
				id : this.pagerIdPrefix + i,
				className : this.clickableClassName + ' ' + this.commonClassName,
				no : i + 1
			};

			if (i == this.currentPage) {
				assign.className = this.disableClassName + ' ' + this.commonClassName;
			}

			html.push(new Template(this.Templates.body).evaluate(assign));
		}
		var nextAssign = {
			id : this.nextId,
			className : this.clickableClassName + ' ' + this.commonClassName
		};
		if (this.isLastPage()) {
			nextAssign.className = this.disableClassName;
		}
		html.push(new Template(this.Templates.footer).evaluate(nextAssign));
		return html.join('');
	}
};

Pager.prototype.Templates = {
	header : '<ul class="qa-common-pager clearfix"><li><span id="#{id}" class="#{className}">&lt; ' + Global.Text.PREVIEW + '</span></li>',
	body : '<li><span id="#{id}" class="#{className}">#{no}</span></li>',
	footer : '<li><span id="#{id}" class="#{className}">' + Global.Text.NEXT + ' &gt;</span></li></ul>',
	blank : '<li>...</li>'
};