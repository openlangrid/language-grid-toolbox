//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2013  Department of Social Informatics, Kyoto University
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

/*******************************************************************************************/

document.onclick = function(e){

	    if (!e) e = window.event;
		var ad_panel=document.getElementById('addictionary');
		if (adAddwordPopup.adChkClickArea(adAddwordPopup.adGetClickPos(e),ad_panel)){
			adAddwordPopup.adClosePanel();
		}
	return;
}

document.oncontextmenu = function(e){
	    if (!e) e = window.event;
		if(adAddwordPopup.adGetText()==""){
			return;
		}
		var ad_panel=document.getElementById('addictionary');
		
		yb=Number(adAddwordPopup.adDivHeight.replace("px", ""));
		yw=adAddwordPopup.getBrowserHeight();
		spsy=adAddwordPopup.adGetScrollPosY();
		xr=Number(adAddwordPopup.adDivWidth.replace("px", ""));
		xw=adAddwordPopup.getBrowserWidth();
		spsx=adAddwordPopup.adGetScrollPosX();
	    xypos=new Array((xw-xr)/2+spsx,(yw-yb)/2+spsy);
		adAddwordPopup.adOpenPanel(adAddwordPopup.adSetStyle(xypos,ad_panel));
        return false; 
}

adOpenPanelBtnClick=function(){

	var ad_panel=document.getElementById('addictionary');

	yb=Number(adAddwordPopup.adDivHeight.replace("px", ""));
	yw=adAddwordPopup.getBrowserHeight();
	spsy=adAddwordPopup.adGetScrollPosY();
	xr=Number(adAddwordPopup.adDivWidth.replace("px", ""));
	xw=adAddwordPopup.getBrowserWidth();
	spsx=adAddwordPopup.adGetScrollPosX();
    xypos=new Array((xw-xr)/2+spsx,(yw-yb)/2+spsy);
	adAddwordPopup.adOpenPanel(adAddwordPopup.adSetStyle(xypos,ad_panel));
    return; 
	
}


/*******************************************************************************************/
/*******************************************************************************************/
/*******************************************************************************************/
var adAddwordPopup = function() {
	this.init();
}

/* setting */
adAddwordPopup.xbtn = 300;
adAddwordPopup.ybtn = 200;
adAddwordPopup.scd2close = 1;
adAddwordPopup.adDivHeight = '150px';
adAddwordPopup.adDivWidth = '550px';
adAddwordPopup.adDivBGC="paleturquoise";
/* setting */


adAddwordPopup.current = null;
//EventListener
/*PopupMenu.addEventListener = function(element, name, observer, capture) {
    if (typeof element == 'string') {
        element = document.getElementById(element);
    }
    if (element.addEventListener) {
        element.addEventListener(name, observer, capture);
    } else if (element.attachEvent) {
        element.attachEvent('on' + name, observer);
    }
};*/
//prototype
adAddwordPopup.prototype ={
	init: function() {
	}
};
adAddwordPopup.adOpenPanel=function(ad_panel){
	var sourceLanguage = $F('displaylanguage');

	var ad_mode = 0;

	var param ='sourceLanguage=' + encodeURIComponent(sourceLanguage)
			+ '&ad_mode=' + ad_mode;
	var aj = new Ajax.Request( 
		"main/ajax_ad_dictionary.php", 
		{ 
			method: "post", 
			parameters: param,
			asynchronous:true, 
			onSuccess: function(request) { 
			}, 
			onComplete: function(request) {
				ad_panel.innerHTML = adAddwordPopup.adCreateHTML4adOpenPanel(request.responseText);
			}, 
			onFailure: function(request) { 
			}, 
			onException: function (request) { 
			} 
		}
	);

};
adAddwordPopup.adClosePanel=function(){
	var ad_panel=document.getElementById('addictionary');
	ad_panel.innerHTML = "";
	ad_panel.style.visibility='hidden';
};
adAddwordPopup.adAddExec=function() { 

	var sourceLanguage = $F('displaylanguage').replace(/(^[\s]+)|([\s]+$)/g, "");
	var targetLanguage = $F('targetlanguage').replace(/(^[\s]+)|([\s]+$)/g, "");

	var sourceExpression = $F('sl_text').replace(/(^[\s]+)|([\s]+$)/g, "");
	var targetExpression = $F('tl_text').replace(/(^[\s]+)|([\s]+$)/g, "");
	document.getElementById('sl_text').value=sourceExpression;
	document.getElementById('tl_text').value=targetExpression;
	var dictionaryName = $F('targetDictionary');

	var ad_panel=document.getElementById('addictionary');
	var vchk = 0;
	if (sourceExpression==""){
		document.getElementById('sl_text').style.backgroundColor="red";
		vchk=1;
	}else{
		document.getElementById('sl_text').style.backgroundColor="";
	}
	if (targetExpression==""){
		document.getElementById('tl_text').style.backgroundColor="red";
		vchk=1;
	}else{
		document.getElementById('tl_text').style.backgroundColor="";
	}
	if (vchk==1){
		alert(" Please fill in all textboxes. ");
		return;
	}
	confirmtext= document.getElementById('sl_text').value + ' -> '
				+ document.getElementById('tl_text').value;
    var flg=confirm(confirmtext); 
    if (!flg) { 
        return;
    }

	var ad_mode = 1;
	var param = 'dictionaryName=' + encodeURIComponent(dictionaryName)
				+ '&sourceExpression=' + encodeURIComponent(sourceExpression)
				+ '&targetExpression=' + encodeURIComponent(targetExpression)
				+ '&sourceLanguage=' + encodeURIComponent(sourceLanguage)
				+ '&targetLanguage=' + encodeURIComponent(targetLanguage)
				+ '&ad_mode=' + ad_mode;

	var aj = new Ajax.Request( 
		"main/ajax_ad_dictionary.php", 
		{ 
			method: "post", 
			parameters: param,
			asynchronous:true, 
			onSuccess: function(request) { 
			}, 
			onComplete: function(request) {
				ad_panel.innerHTML = adAddwordPopup.adSuccessExec(); 
			}, 
			onFailure: function(request) { 
			}, 
			onException: function (request) { 
			} 
		}
	); 
	document.getElementById('sl_text').value="";
	document.getElementById('tl_text').value="";
	document.getElementById('sl_text').style.backgroundColor="";
	document.getElementById('tl_text').style.backgroundColor="";

	setTimeout("adAddwordPopup.adClosePanel()",adAddwordPopup.scd2close*1000);
};



adAddwordPopup.adChangeDict=function() { 

	var dictionaryName = $F('targetDictionary');
	var sourceLanguage = $F('displaylanguage');
	
	var ad_mode = 2;
	var param = 'dictionaryName=' + encodeURIComponent(dictionaryName)
				+'&sourceLanguage=' + encodeURIComponent(sourceLanguage)
				+ '&ad_mode=' + ad_mode;
	var aj = new Ajax.Request( 
		"main/ajax_ad_dictionary.php", 
		{ 
			method: "post", 
			parameters: param,
			asynchronous:true, 
			onSuccess: function(request) { 
			}, 
			onComplete: function(request) {
				$('adtargetlanguage').innerHTML = adAddwordPopup.adCreateHTML4adChangeDict(request.responseText); 
			}, 
			onFailure: function(request) { 
			}, 
			onException: function (request) { 
			} 
		}
	); 
};
adAddwordPopup.adCreateHTML4adOpenPanel=function (reqtext){
	eval("var json = " + reqtext);
	var html="";
	html=html
		+'<div id="adword2dictionary" ></div>'
		+'<table class="ad_tbl">'
		+ '<tr><td colspan="3" align=right><a href="javascript:void(0)" onclick="adAddwordPopup.adClosePanel()"> close </a></td></tr>';

	html=html
		+'<tr><th width="150">' + $F('ad_lbl_SourceLanguage') + '</th>'
		+'<th width="150">' + $F('ad_lbl_TargetLanguage') + '</th>'
		+'<th width="200">' + $F('ad_lbl_DictionaryName') + '</th></tr>';

	html=html
		+'<tr><td align="left"><label>'
		+ '<option value="' + json.labelSourceLang + '">' + json.labelSourceLang + '</option>'
		+'</label></td>'
		+'<td><div id="adtargetlanguage">'
		+'<select style="float:left;" name="lang" id="targetlanguage">")';
	for(i=0; i < json.selectTargetLang.length; i++){
		if(json.selectTargetLang[i].langTag != $F('displaylanguage')){
			html= html + '<option value="' + json.selectTargetLang[i].langTag + '">' + json.selectTargetLang[i].langVal + '</option>';
		}
	}
	html=html
		+'</select>'
	html=html
		+'</div></td>'
		+'<td>'
		+'<select style="float:left;" name="dict" id="targetDictionary" onchange="adAddwordPopup.adChangeDict()">';
	for(i=0; i < json.selectDictionary.length; i++){
		html= html + '<option value="' + json.selectDictionary[i].dictVal + '">' + json.selectDictionary[i].dictVal + '</option>';
	}
	html=html
		+'</select>'
		+'</td></tr>';

	html=html
		+'<tr>'
		+'<td align="left">'
		+'<input type="text" size="20" name="sl_text" id="sl_text" value="' +adAddwordPopup.adGetText() + '" />'
		+'</td>'
		+'<td align="left">'
		+'<input type="text" size="20" name="tl_text" id="tl_text" value="" />'
		+'</td>'
		+'<td align=center height="25px">'
		+'<input  type="button" class="ad_btn_blue01" onclick="adAddwordPopup.adAddExec()" value="' + $F('ad_lbl_AddWordBtn') + '" />'
		+'</td>'
		+'</tr>';

	html=html
		+'</table>';

	return html;
};

adAddwordPopup.adCreateHTML4adChangeDict=function (reqtext){

	var json = eval(reqtext);
	var html;
	
	html = '<select style="float:left;" name="lang" id="targetlanguage">")';
	
	for(i=0; i < json.length; i++){
		if(json[i].langTag != $F('displaylanguage')){
			html= html + '<option value="' + json[i].langTag + '">' + json[i].langVal + '</option>';
		}
	}
	html = html + '</select>';

	return html;
};

adAddwordPopup.adGetClickPos=function(e){
	    if (window.opera) {
	        x = e.clientX;
	        y = e.clientY;
	    } else if (document.all) {
	        x = document.body.scrollLeft + event.clientX;
	        y = document.body.scrollTop + event.clientY;
	    } else if (document.layers || document.getElementById) {
	        x = e.pageX;
	        y = e.pageY;
	    }
	    xypos=new Array(x,y);
	    return xypos;
};

adAddwordPopup.adGetScrollPosY=function(){
	return (document.documentElement.scrollTop || document.body.scrollTop);   

};
adAddwordPopup.adGetScrollPosX=function(){
	return (document.documentElement.scrollLeft || document.body.scrollLeft);   

};

adAddwordPopup.getBrowserWidth=function(){
if ( window.innerWidth ) { return window.innerWidth; }
else if ( document.documentElement && document.documentElement.clientWidth != 0 ) { return document.documentElement.clientWidth; }
else if ( document.body ) { return document.body.clientWidth; }
return 0;
}
adAddwordPopup.getBrowserHeight=function(){
if ( window.innerHeight ) { return window.innerHeight; }
else if ( document.documentElement && document.documentElement.clientHeight != 0 ) { return document.documentElement.clientHeight; }
else if ( document.body ) { return document.body.clientHeight; }
return 0;
}


adAddwordPopup.adGetText=function(){
    IE='\v'=='v';
    var SelectedText;
    if(IE){
        SelectedText = document.selection.createRange().text;
    }
    else{
        SelectedText = window.getSelection().toString();
    }
    return SelectedText.replace(/(^[\s]+)|([\s]+$)/g, "");
};

adAddwordPopup.adChkClickArea=function(xypos,ad_panel){
	x=xypos[0];
	y=xypos[1];
	yt=Number(ad_panel.style.top.replace("px", ""));
	yb=Number(ad_panel.style.height.replace("px", ""));
	xl=Number(ad_panel.style.left.replace("px", ""));
	xr=Number(ad_panel.style.width.replace("px", ""));

	xra=Number(adAddwordPopup.adDivWidth.replace("px", ""));
	xwa=adAddwordPopup.getBrowserWidth();
	spsx=adAddwordPopup.adGetScrollPosX();
	
	if(xl==(xwa-xra)/2+spsx){
		ad_panel.style.left = xl+1 + 'px';
		return false;
	}else if  ((y<yt)||(y>yb+yt)||(x<xl) || (x > xr+xl)){
		return true;
	}else{
		return false;
	}
};


adAddwordPopup.adSetStyle=function(xypos,ad_panel){
	x=xypos[0];
	y=xypos[1];		
	ad_panel.style.height = adAddwordPopup.adDivHeight;
	ad_panel.style.width  = adAddwordPopup.adDivWidth;
	ad_panel.style.top  = y + 'px';
	ad_panel.style.left = x + 'px';
	ad_panel.style.backgroundColor =  adAddwordPopup.adDivBGC;
	ad_panel.style.visibility='visible';
	return ad_panel;
};

adAddwordPopup.adSuccessExec=function(){

	var html = '<table class="ad_tbl">'
		+'<td align="center" valign="middle"><label>'
		+ $F('ad_lbl_SuccessToAddWord') 
		+'</label></td>'
		+ '</table>';
	return html;
}
