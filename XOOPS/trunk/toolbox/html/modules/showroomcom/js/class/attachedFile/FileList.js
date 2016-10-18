//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2010 CITY OF KYOTO All Rights Reserved.
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
var FileList = Class.create();
FileList.prototype = {
	
	initialize : function() {
		this.lightpop = new FileListPopUp();
		$$(".DownloadFileButton").each(function(downloadButton) {
			downloadButton.observe("click",this.downloadButtonClickEvent.bindAsEventListener(this));
		}.bind(this));
	},
	
	downloadButtonClickEvent : function(e) {
//		console.info(event);
		var postId = null;
		
		if(window.ActiveXObject){
			//for(var pr in window.event){
				//alert(pr);
			//}
		
						postId=window.event.srcElement.id.match(/\d+/);
				 	postId=postId[0];
				 }
		else{
			postId=e.currentTarget.id.match(/\d+/);//IEでエラー
		//for (var pr in e.srcElement){
			//alert(pr);
		//}
		//alert(postId);
		}
		
		this.showPopup(postId);
	//		this.lightpop.setBody(post_id);
	//		this.lightpop.show();
	//		return false;
	},
	
	showPopup : function(postId) {
		this.lightpop.setBody(postId);
		this.lightpop.show();
	}
	
};

var FileListPopUp = Class.create();
Object.extend(FileListPopUp.prototype, LightPopupPanel.prototype);
Object.extend(FileListPopUp.prototype, {
	//id : "langrid-imported-services-light-popup-panel",
	id: "list",
	panelId : "panel",
	maskId : "mask",
	opacity:0.7,
	body:null,
	//objFileListPopUp : this,
	
	//	initialize : function() {
	//		if (!this.element) {
	//			new Insertion.Bottom($$('body')[0], new Template(Templates.ImportedServices.PopupPanel.base).evaluate({
	//				id : this.id,
	//				panelId : this.panelId,
	//				maskId : this.maskId
	//			}));
	//			this.element = $(this.id);
	//		}
	//
	//		this.panel = $(this.panelId);
	//		this.mask = $(this.maskId);
	//
	//		this.initEventListeners();
	//
	//		this.hide();
	//	},
	
	getBody : function(){
		return this.body;
	},
	
	setBody : function(post_id){
		this.body="<div class='langrid-popup-container'><div class='fileListTableTitle'>"+Const.Label.captionFileList+"</div>";
		this.body+="<div class='fileListTableContainer'><table><tbody id='tbodyUpload'>";
		this.body+="<tr>";
		this.body+="<td class='fileListTitles'><h2>"+Const.Label.captionFileName+"</h2></td>";
		this.body+="<td class='fileListTitles file-list-size'><h2>"+Const.Label.captionFileSize+"</h2></td>";
		var tdCnt=2;
		if(!$('screenId')){
			this.body+="<td class='fileListTitles file-list-delete'><h2>"+Const.Label.deleteFile+"</h2></td>";
			tdCnt+=1;
		}
		this.body+='</tr>';
		this.body+="<tr><td colspan='"+tdCnt+"'><div class='fileListTableTitleUnderLine'></div></td></tr>"
		for(i=0;i<FileListHash[post_id].length;i++){
			this.body+="<tr id='trUpload"+FileListHash[post_id][i]["ID"]+"'>";
			this.body+="<td class='fileListTableData'><a href='?page=attachedFile&mode=download&id="+FileListHash[post_id][i]["ID"]+"'>"+FileListHash[post_id][i]["FileName"]+"</a></td>";
			var fSize=FileListHash[post_id][i]["FileSize"];
			if(fSize>1) fSize=Math.floor(fSize);
			this.body+="<td class='fileListTableData'>"+fSize+"KB</td>";
			if(!$('screenId')){
				this.body+="<td class='fileListTableData'><a href='javascript:void(0)' onClick='deleteFile("+FileListHash[post_id][i]["ID"]+","+post_id+")'>"+Const.Label.deleteFile+"</a></td>";
			}
			this.body+='</tr>';
		}

		this.body+="</tbody></table></div>";
		this.body+="<span id='imageContainer'></span>";
		this.body+='<p align="center"><button id="langrid-imported-services-light-popup-cancel-button" class="langrid-common-button" onClick="hidePop()">'+Const.Label.captionClose+'</button></p>';
		this.body+="<div class='spaceButtom'></div>";
		this.body+='</div>';

	},

	show : function() {
		this.setupMask();
		this.panel.update(this.getBody());
		this.element.show();
		this.adjustPanel();
		this.startEventObserving();
		this.onShowPanel();
	}
});

function hidePop(){
	//$("langrid-imported-services-light-popup-panel").hide();
	$("list").hide();
}


var targetID=0;
var targetPostID=0;

function deleteFile(id, post_id){
	//	alert(id);
	if (!confirm(Const.Message.confirmDelete)) {
		return;
	}
	$("imageContainer").innerHTML=Const.Images.loading;
	var url='index.php';
	var mode='delete';
	targetID=id;
	targetPostID=post_id;
	//alert(id);//debug
	new Ajax.Request(url, {
		method: "GET",
		parameters: 'page=attachedFile&mode='+mode+'&id='+id,
		onSuccess:function(httpObj){
			//リストからファイルレコードを消す処理
			//      $('tbodyUpload').removeChild($('trUpload'+targetID));
			$('trUpload'+targetID).parentNode.removeChild($('trUpload'+targetID));
			FileListCount-=1;
			//      if(FileListCount<=0) document.removeChild($('DownloadFileButton'+targetPostID));
			if(FileListCount<=0) $('tdDownloadFileButton').parentNode.removeChild($('tdDownloadFileButton'));
			for(i=0;i<FileListHash[targetPostID].length;i++){
				if(FileListHash[targetPostID][i]["ID"]==targetID){
					delete FileListHash[targetPostID][i];
					break;
				}
			}
		},
		onFailure:function(httpObj){
			alert(Const.Message.errorDeleteFile);
		}
	});

	$("imageContainer").innerHTML="";

}
