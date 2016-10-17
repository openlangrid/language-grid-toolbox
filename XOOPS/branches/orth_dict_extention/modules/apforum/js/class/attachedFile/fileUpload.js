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
//新規メッセージ投稿画面に、
//ファイルアップロードのＵＩと機能を提供する。

Event.observe(window, 'load', function(){
	//現状では関数を呼び出すだけになっている
	//ファイルアップロードの機能をまとめたクラスを作る
	AddUpload();
});

var CntUpload = 0;
var UsedNumber=[];
var BaseInputName='uploadfile';
var OrderID=[];

function AddUpload() {
	var typeCode = document.getElementsByName("type_code").item(0).value;
	
	var allowedTypeCode = ['post_create', 'post_edit', 'post_reply', 'topic_create'];
	
	if (allowedTypeCode.indexOf(typeCode) == -1) {
		return;
	}
	
	if (!ValidateUploadCount('add')) {
		if (CntUpload!=0) {
			alert(Const.Message.OverLimitCount.replace('{0}', FileCountLimit));
		}
		return false;
	}
	
	var uploadNumber = GetUseNumber();
	
	var tr = document.createElement("tr");
	tr.id = 'trUpload' + uploadNumber;
	//var tdTitle = document.createElement("td");
	//tdTitle.innerHTML = Const.Label.captionUpload;
	var tdFile = document.createElement("td");
	tdFile.width = "30";
	var inputFile = document.createElement("input");
	inputFile.type = "file";
	inputFile.size = "70";
	inputFile.name = BaseInputName + uploadNumber;
	inputFile.id = BaseInputName + uploadNumber;
//inputFile.onchange=ShowMinusButton(inputFile.id,0);
	
	var tdAddBtn = document.createElement("td");
	tdAddBtn.width = "50";
	tdAddBtn.align='center';
	var a = document.createElement("a");
	a.href = "javascript:void(0)";
	Event.observe(a, "click", AddUpload);
	var img = document.createElement("img");
	img.src = "./images/icn_add.gif";
	
	var tdSubBtn = document.createElement("td");
	tdSubBtn.width = "50";
	//tdSubBtn.align='center';
	
	var aSub = document.createElement("a");
	aSub.href = "javascript:void(0)";
	aSub.id="MinusButton"+uploadNumber;
	//aSub.visibility=false;
	Event.observe(aSub, "click", SubUpload.bindAsEventListener(this,uploadNumber));
	var imgSub = document.createElement("img");
	imgSub.src = "./images/icn_minus.gif";
	
	//imgSub.visibility=false;
	imgSub.id="ImageMinussbutton"+uploadNumber;
			inputFile.onchange=ShowMinusButton.bind(this,inputFile,imgSub.id);

	if(CntUpload==0){
		imgSub.style.display="none";
	}
	
	
	tdFile.appendChild(inputFile);
	
	a.appendChild(img);
	aSub.appendChild(imgSub);
	
	tdAddBtn.appendChild(a);
	//tdAddBtn.appendChild(aSub);
	tdSubBtn.appendChild(aSub);
	//tr.appendChild(tdTitle);
	tr.appendChild(tdFile);
	tr.appendChild(tdAddBtn);
	
	tr.appendChild(tdSubBtn);
	
	$("tbodyUpload").appendChild(tr);
	
	RegulateUseNumber(uploadNumber,'add');
	CntUpload += 1;

	if($('tdDownloadFileButton')){
		$('tdDownloadFileButton').rowSpan=CntUpload+1;
	}
	return false;
	
}

function ShowMinusButton(inputID,imageID){
	//alert(imagetID);
	if(inputID){
		$(imageID).style.display="";
	}
	//if(inputID==null) return;
	//var val=$(inputID).value;
	//if(val) aID.visible=true;
}
	
function SubUpload(){
	//alert(arguments[1]);
	var number=arguments[1];
	var valResult=ValidateUploadCount('sub');
	var targetObj=$('trUpload'+number);
	$('tbodyUpload').removeChild(targetObj);
	CntUpload-=1;
		RegulateUseNumber(number,'sub');

	if(!valResult) AddUpload();
	
//  if(CntUpload==1){
		//for(i=0;i<FileCountLimit;i++){
			//if(UsedNumber[i]){
				//if($(BaseInputName+i).value=="") $("ImageMinussbutton"+i).style.display="none";
			//}
		//}
		if($(BaseInputName+OrderID[0]).value=="") $("ImageMinussbutton"+OrderID[0]).style.display="none";
	//}
  
	if($('tdDownloadFileButton')){
		$('tdDownloadFileButton').rowSpan=CntUpload+1;
	}
	return false;
}

function ValidateUploadCount(mode){
	var tmpCnt=CntUpload;
	var ret=true;
	if(mode=='add'){
		tmpCnt+=1;
		if(tmpCnt+FileListCount>FileCountLimit) ret=false;
	}
	else{
		tmpCnt-=1;
		if(tmpCnt<=0) ret=false;
	}
	return ret;
}

	
function GetUseNumber(){
	var ret=null;
		//var i=0;

	if(UsedNumber[0]==undefined){
		ret=0;
	}
	else{
		for(i=0;i<FileCountLimit;i++){
			if(UsedNumber[i]==false){
				//UsedNumber[i]=true;
				ret=i;
				break;
			}
		}
	}
		
	return ret;
}

function RegulateUseNumber(number,mode){
	if(UsedNumber[0]==undefined){
		for(i=0;i<FileCountLimit;i++){
			UsedNumber[i]=false;
		}
		SetOrder(number,"init");
	}
	
	if(mode=='add'){
		UsedNumber[number]=true;
		SetOrder(number,mode);
	}
	else{
		UsedNumber[number]=false;
		SetOrder(number,mode);
	}
}

function SetOrder(number,mode){
	if(mode=="init"){
		for(i=0;i<FileCountLimit;i++){
			OrderID[i]=null;
		}
	}
	else if(mode=="add"){
				for(i=0;i<FileCountLimit;i++){
					if(OrderID[i]==null){
						OrderID[i]=number;
						break;
					}
				}
	}
	else{
		var i=0;
					for(i=0;i<FileCountLimit;i++){
						if(OrderID[i]==number){
						//OrderID[i]=null;
						break;
						}
					}
					
					for(j=i;j<FileCountLimit-1;j++){
						OrderID[j]=OrderID[j+1];
					}
					
					OrderID[FileCountLimit-1]=null;
	}
}


