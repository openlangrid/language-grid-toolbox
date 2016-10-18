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

var TranslationPanel = Class.create(Observer, {

	sourceId: 'wc-source-body',
	targetId: 'wc-target-body',

	STATIC: {
		init: false
	},

	initialize: function() {
		this.initEventListeners();
	},

	initEventListeners: function() {

		if (!this.STATIC.init) {
			Event.observe(window, 'resize', this.adjustWidth.bind(this));
			this.STATIC.init = true;
		}

//		$(this.sourceId).observe('scroll', function(event) {
//			var dy = $(this.sourceId).scrollTop;
//			$(this.targetId).scrollTop = dy;
//		}.bind(this));
//
//		$(this.targetId).observe('scroll', function(event) {
//			var dy = $(this.targetId).scrollTop;
//			$(this.sourceId).scrollTop = dy;
//		}.bind(this));
	},

	update: function(o) {
		Logger.info('TranslationPanel.update');

		var result = o.getResult();

//		$(this.sourceId).update('');
//		$(this.targetId).update('');
//
//		$(this.sourceId).appendChild(this.createSource(result));
//		$(this.targetId).appendChild(this.createTarget(result));
//
//		this.adjustWidth();
//		this.adjustHeight();
	},

	createSource: function(result) {
		var table = document.createElement('table');
		var tbody = document.createElement('tbody');

		result.each(function(line) {
			if (Model.Translation.isShowTagLine() == false && line.status == 'tag') {
				return;
			}
			var tr = document.createElement('tr');
			var td = document.createElement('td');

			td.appendChild(document.createTextNode(line.source));
			tr.appendChild(td);

			tbody.appendChild(tr);
		});
		table.appendChild(tbody);

		return table;
	},

	createTarget: function(result) {
		var table = document.createElement('table');
		var tbody = document.createElement('tbody');

		result.each(function(line) {
			if (Model.Translation.isShowTagLine() == false && line.status == 'tag') {
				return;
			}
			var tr = document.createElement('tr');

/*
			var checkTd = document.createElement('td');
			checkTd.width = '16';
			var checkBox = document.createElement('input');
			checkBox.type = 'checkbox';
			checkBox.checked = (line.status != 'unfixed');
			checkBox.disabled = (line.status == 'tag');

			checkBox.onclick = function(event) {
//				line.status = (Event.element(event).checked) ? 'fixed' : 'unfixed';
				line.status = ($(checkBox).checked) ? 'fixed' : 'unfixed';
			};

			checkTd.appendChild(checkBox);
			tr.appendChild(checkTd);
 */
//			tr.appendChild(this.createTargetCell(line));
			var td = document.createElement('td');
			td.appendChild(document.createTextNode(line.target));
			tr.appendChild(td);

			tbody.appendChild(tr);
		}.bind(this));
		table.appendChild(tbody);

		return table;
	},

	createTargetCell: function(line) {
		var td = document.createElement('td');
		td.appendChild(document.createTextNode(line.target));

		if (line.status != 'tag') {
			td.onclick = function(event) {
				if ($(td).hasClassName('wc-editing-cell')) {
					return;
				}

				$(td).addClassName('wc-editing-cell');

				var textarea = document.createElement('textarea');
				textarea.appendChild(document.createTextNode(line.target));

				var width = (td.getWidth() - 4) + 'px';
				var height = (td.getHeight() - 8) + 'px';
				$(textarea).setStyle({
					width: width,
					height: height
				});

				td.update('');

				td.appendChild(textarea);
				textarea.focus();

				$(this.targetId).scrollLeft = 0;

				textarea.onblur = function(event) {
					line.target = textarea.value;

					td.removeClassName('wc-editing-cell');
					td.update('');
					td.appendChild(document.createTextNode(line.target));

					this.adjustHeight();
				}.bindAsEventListener(this);
			}.bind(this);
		}

		return td;
	},

	getWidth: function() {
//		var sourceTable = $(this.sourceId).down(0);
//		var targetTable = $(this.targetId).down(0);
//
//		if (!sourceTable || !targetTable) {
//			return 'auto';
//		}
//
//		sourceTable.hide();
//		targetTable.hide();
//
//		$(this.sourceId).setStyle({
//			width: 'auto'
//		});
//
//		$(this.targetId).setStyle({
//			width: 'auto'
//		});
//
//		var sourceWidth = $(this.sourceId).getWidth();
//		var targetWidth = $(this.targetId).getWidth();
//
//		var width = (sourceWidth + targetWidth) / 2;
//
//		sourceTable.show();
//		targetTable.show();
//
//		return width + 'px';
	},

	adjustWidth: function() {
		var width = this.getWidth();

//		$(this.sourceId).setStyle({
//			width: width
//		});
//
//		$(this.targetId).setStyle({
//			width: width
//		});
//
//		var sourceTable = $(this.sourceId).down(0);
//		var targetTable = $(this.targetId).down(0);
//
//		if (!sourceTable || !targetTable) return;
//
//		var sourceTableWidth = sourceTable.getWidth();
//		var targetTableWidth = targetTable.getWidth();
//
//		var width = Math.max(sourceTableWidth, targetTableWidth);
//
//		sourceTable.setStyle({
//			width: width + 'px'
//		});
//
//		targetTable.setStyle({
//			width: width + 'px'
//		});
	},

	adjustHeight: function() {
//		var sourceRows = $(this.sourceId).getElementsByTagName('tr');
//		var targetRows = $(this.targetId).getElementsByTagName('tr');
//
//		for (var i = 0, length = sourceRows.length; i < length; i++) {
//			var sh = sourceRows[i].getHeight();
//			var th = targetRows[i].getHeight();
//
//			if (sh == th) continue;
//
//			var height = Math.max(sh, th);
//
//			sourceRows[i].setStyle({
//				height: height + 'px'
//			});
//
//			targetRows[i].setStyle({
//				height: height + 'px'
//			});
//		}
	}
});