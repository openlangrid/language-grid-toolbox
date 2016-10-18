//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
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
/**
 * @author kitajima
 */
var QaEditSaveParameterPopupPanel = Class.create();
Object.extend(QaEditSaveParameterPopupPanel.prototype, QaEditInsertParameterPopupPanel.prototype);
Object.extend(QaEditSaveParameterPopupPanel.prototype, {
	submit : function() {
		this.stateManager.setChangedParameter('Parameter', null, 'Change');
		this.stateManager.currentRecord.parameterIds = this.getSelectedParameterIds();
		this.hide();
	},

	getBody : function() {
		var body = new Template(this.Templates.base).evaluate({
			titleLabel : Global.Text.EDIT_PARAMETER,
			closeId : this.getCloseId(),
			statusId : this.Config.Id.STATUS,
			languagesId : this.Config.Id.LANGUAGES,
			languages : this.createLanguageSelectorHtml(),
			questionId : this.Config.Id.QUESTION,
			question : this.record.expressions[this.language],
			editMasterCategory : Global.Text.EDIT_THE_CATEGORIES,
			editMasterCategoryId : this.getMasterId(),
			categoriesArea : this.getCategoriesAreaId(),
			categories : this.createCategoryCheckBoxHtml(),
			saveId : this.getSaveButtonId(),
			save : Global.Text.OK,
			cancelId : this.getCancelButtonId(),
			cancel : Global.Text.CANCEL,
			addParameterButtonId : this.getAddButtonId(),
			removeParameterButtonId : this.getDeleteButtonId(),
			editParameterButtonId : this.getEditButtonId()
		});
		this.drawCompleteFlag = true;
		return body;
	}
});

QaEditSaveParameterPopupPanel.prototype.Templates = {};
QaEditSaveParameterPopupPanel.prototype.Templates.base = QaEditInsertParameterPopupPanel.prototype.Templates.base;
QaEditSaveParameterPopupPanel.prototype.Templates.categoryCheckBox = '<tr><td width="15"><input style="display: none;" class="template-insert-index-radio" value="#{index}" type="radio" #{attribute} name="index" /></td><td width="70">#{index}</td><td>#{typeSelector}</td></tr>';