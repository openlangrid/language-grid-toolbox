//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
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

var TransPathPanel = Class.create(TranslationPathPanel, {
	_saveSetting: function(save_data) {
		var controller = this;
		var postObj = {};
		postObj['mode'] = 'ALL';
		$A(save_data).each(function(setting, index){
			postObj['data['+index+']'] = setting;
		});
//		postObj['data'] = save_data;
		var hash = $H(postObj).toQueryString();
		new Ajax.Request('./ajax/save-trans-setting.php', {
			method: 'post',
			parameters: hash,
			asynchronous:false,
			controller: controller,
			onSuccess: function(httpObj) {
				var responseJSON = httpObj.responseText.evalJSON();
				if(responseJSON.status == 'SESSIONTIMEOUT'){
					redirect2top();
					return;
				}
				this.errorView(false);
				var data = this._buildeData();
				var Lang1 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],data.lang1);
				var Flow = this.getdataArrayValue(this._CONST_FLOW_OPS,data.flow);
				if(data.lang4 != ""){
					var Lang2 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],data.lang4);
				}else if(data.lang3 != ""){
					var Lang2 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],data.lang3);
				}else{
					var Lang2 = this.getdataArrayValue(this._langridServiceInformations['_sourceLanguageArray'],data.lang2);
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
				//var id = responseJSON.contents[index];
				//this.updatePrimaryId(id);
				if(this._PanelMode == "new"){
					$('top-message-area').innerHTML = save_message;
					$('top-message-area').show();
					var data = this._buildeData();
					var idx = this._PathArray.length;
					var pathPanel = new TranslationPathPanel();
					pathPanel.makeTranslationPathPanel_A(idx, this._rootPanel,
						this._langridServiceInformations,
						this._PathArray,
						this._default_Settings,
						data);
					this._PathArray.push(pathPanel);
					for(var i=0;i < this._PathArray.length;i++){
						if(this._PathArray[i] == this){
							this._PathArray.splice(i,1);
							break;
						}
					}
					this._sortPanelArray();
				}else{
					if(this._isDelete == 'yes'){
						for(var i=0;i < this._PathArray.length;i++){
							if(this._PathArray[i] == this){
								this._PathArray.splice(i,1);
								break;
							}
						}
					}else{
						this.updateViewPanel();
						this.showMessageBox(save_message);
					}
					//Translation path "English <> Japanese" is added.
					//Translation path "English <> Japanese" is changed.
				}
				//addPathWorkspace._resetFilter();
			}.bind(this),
			onFailure: function(httpObj) {
				$('setting_error_message').innerHTML = '<per>' + httpObj.responseText + '</pre>';
			},
			onComplete: function() {
				$$(".nowedit").each(function(ele){
					if($(ele.id+':edit')){$(ele.id+':edit').hide();}
					if($(ele.id+':view')){$(ele.id+':view').show();}
					Element.removeClassName(ele,"nowedit");
					var save_btn = $(ele.id+':save');
					Element.removeClassName(save_btn,"btn");
					Element.addClassName(save_btn,"btn-disable");
					var save_img = $(ele.id+':save-img');
					save_img.setAttribute('src','./images/icon/icn_save_disable.gif');
				});
				if(this._PanelMode == 'new'){
					this._addPanel.innerHTML = "";
					this._addPanel.hide();
					$('div-add-path-area').show();
				}
				addPathWorkspace._resetFilter();
			}.bind(this)
		});
	},
	_onSaveButtonClicked: function(ev) {
		if(!Element.hasClassName(Event.findElement(ev,'a'),'btn-disable')){
			var validMessage = '';
			var pairs = Array();
			$A(this._PathArray).each(function(panelElem, index){
				panelElem.errorView(false);
				if(panelElem != this){
					if (Element.visible(panelElem._selfPanel)) {
						pairs.push(panelElem.getLanguagePairFromView());
					}
				}
			}.bind(this));
			pairs = pairs.flatten();
			var MyPair = this.getLanguagePair();
			/*
			for(var i = 0;i < MyPair.length;i++){
				if (pairs.indexOf(MyPair[i]) > -1) {
					validMessage = Const.Message.SaveOnError2 + ' ['+this.__util(MyPair[i])+']';
					Element.scrollTo($(this._selfPanel));
					this.errorView(true);
					this.showMessageBox(validMessage);
					return;
				}
			}
			*/
			var isAll = false;
			$A(this._PathArray).each(function(panelElem, index){
				if(panelElem.elementsIds.panel != "panel-N" && panelElem._primaryId == ''){
					isAll = true;
					throw $break;
				}
			});
			if(isAll){
				this._makeSaveDataAll();
			}else{
				this._makeSaveData(this);
			}
		}
	}
});

