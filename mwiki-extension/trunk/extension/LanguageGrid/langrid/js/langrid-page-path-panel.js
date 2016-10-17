//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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

var PagePathPanel = Class.create();

Object.extend(PagePathPanel.prototype, TranslationPathPanel.prototype);
Object.extend(PagePathPanel.prototype, {
	_createControllPanel: function() {
		var blockPanel = document.createElement('div');
		blockPanel.id = this.elementsIds.panel + ':controll';
		Element.addClassName(blockPanel, 'trans-path-edit-area');

		var save = document.createElement('button');
		save.id = this.elementsIds.panel + ":save";
		if(this._PanelMode != 'new'){
			save.appendChild(document.createTextNode(Const.Label.Save));
		}else{
			save.appendChild(document.createTextNode(Const.Label.AddButton));
			Element.addClassName(save,"btn-disable");
			try {
			save.setAttribute('disabled', 'yes');
			} catch (e) {}
		}
		$(save).observe('click', this._onSaveButtonClicked.bind(this));
		this._saveButton = save;

		subpanel = document.createElement('span');
		Element.setStyle(subpanel, "margin-left: 20px;");

		if(this._PanelMode != 'new'){
			var del = document.createElement('a');
			del.id = this.elementsIds.panel + ":del";
			del.innerHTML += Const.Label.DeleteButton;
			$(del).observe('click', this._onDeleteButtonClicked.bind(this));
			subpanel.appendChild(del);

			bar = document.createTextNode(' | ');
			subpanel.appendChild(bar);
		}

		var cancel = document.createElement('a');
		cancel.id = this.elementsIds.panel + ":cancel";
		cancel.innerHTML += Const.Popup.BTN_CANCEL;
		$(cancel).observe('click', this._onCancelButtonClicked.bind(this));
		subpanel.appendChild(cancel);

		blockPanel.appendChild(save);
		blockPanel.appendChild(subpanel);

		return blockPanel;
	},
	_saveSetting: function(save_data) {
		var controller = this;

		save_data.title_db_key = Const.Wiki.TitleDBKey;
		sajax_request_type = 'POST';
		sajax_do_call('LanguageGridAjaxController::invoke', ['Setting:Save', save_data], function(httpObj) {
			var responseJSON = httpObj.responseText.evalJSON();
			if(responseJSON.status == 'SESSIONTIMEOUT'){
				redirect2top();
				return;
			}
try {
			controller.errorView(false);
			var data = controller._buildeData();
			var Lang1 = controller.getdataArrayValue(controller._langridServiceInformations['_sourceLanguageArray'],data.lang1);
			var Flow = controller.getdataArrayValue(controller._CONST_FLOW_OPS,data.flow);
			if(data.lang4 != ""){
				var Lang2 = controller.getdataArrayValue(controller._langridServiceInformations['_sourceLanguageArray'],data.lang4);
			}else if(data.lang3 != ""){
				var Lang2 = controller.getdataArrayValue(controller._langridServiceInformations['_sourceLanguageArray'],data.lang3);
			}else{
				var Lang2 = controller.getdataArrayValue(controller._langridServiceInformations['_sourceLanguageArray'],data.lang2);
			}

			if(this._PanelMode == "new"){
				var save_message = Const.Message.AddedMessage;
			}else{
				var save_message = Const.Message.SavedMessage;
			}
			save_message = save_message.replace("%SRC",Lang1);
			save_message = save_message.replace("%FLW",Flow);
			save_message = save_message.replace("%TGT",Lang2);

			$A(controller._PathArray).each(function(panelElem, index){
				panelElem.errorView(false);
				if(responseJSON.contents[panelElem.elementsIds.panel] && responseJSON.contents[panelElem.elementsIds.panel] != "undefined" && responseJSON.contents[panelElem.elementsIds.panel] != ""){
					var id = responseJSON.contents[panelElem.elementsIds.panel];
					panelElem.updatePrimaryId(id);
				}
			});

			if(controller._PanelMode == "new"){
				$('top-message-area').innerHTML = save_message;
				$('top-message-area').show();

				var data = controller._buildeData();

				var idx = controller._PathArray.length;
				var pathPanel = new PagePathPanel();
				pathPanel.makeTranslationPathPanel_A(idx, controller._rootPanel,
					controller._langridServiceInformations,
					controller._PathArray,
					controller._default_Settings,
					data);
				controller._PathArray.push(pathPanel);
				for(var i=0;i < controller._PathArray.length;i++){
					if(controller._PathArray[i] == controller){
						controller._PathArray.splice(i,1);
						break;
					}
				}
				controller._sortPanelArray();
			}else{
				if(controller._isDelete == 'yes'){
					for(var i=0;i < controller._PathArray.length;i++){
						if(controller._PathArray[i] == controller){
							controller._PathArray.splice(i,1);
							break;
						}
					}
				}else{
					controller.updateViewPanel();
					controller.showMessageBox(save_message);
				}
			}
			$$(".nowedit").each(function(ele){
				if($(ele.id+':edit')){$(ele.id+':edit').hide();}
				if($(ele.id+':view')){$(ele.id+':view').show();}
				Element.removeClassName(ele,"nowedit");
				var save_btn = $(ele.id+':save');
				Element.removeClassName(save_btn,"btn");
				Element.addClassName(save_btn,"btn-disable");
			});
			if(controller._PanelMode == 'new'){
				controller._addPanel.innerHTML = "";
				controller._addPanel.hide();
				$('div-add-path-area').show();
			}
			PageAddPathWorkspace._resetFilter();
} catch (e) {alert(e.toSource())}
		});

	}
});
