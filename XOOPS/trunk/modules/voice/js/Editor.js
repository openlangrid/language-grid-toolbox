//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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
var DocumentEditor = Class.create( {
	editor : null,
	langCode : null,
	sizeDefault : 50,
	id : null,
	postfixEditorId : '_editor',
	highlightedFunction : null,
	target : null,
	backTarget : null,
	timerId : null,
	targets : [],
	TIME_OUT_ERROR_CODE : "SAeou8oe9ugnjqka",
	sentenceCount : 0,

	initialize : function(elementId, langCode, disable, addedHTML){
		this.id = elementId;
		if(addedHTML == null){
			addedHTML = "";
		}
		this.editor = new YAHOO.widget.SimpleEditor(elementId, {
			animate : false
//			, dompath : false
//			, plainText : true
//			, ptags : true
//			, allowNoEdit : true
			// for ms-word2007
			, filterWord : true
			, disabled : disable
			, width : '99%'
			, css : "html {height: 95%;}"
				+ "body {height: 100%; background-color: #fff;"
				+ "font:15px/1.22 arial,helvetica,clean,sans-serif;"
				+ "*font-size:15px; *font:15px;}"
				+ ".highlight{background-color:#FFFF30;}"
			, html : "<html>" +
					"<head><title>{TITLE}</title>"
				+ "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />"
				+ "<style>{CSS}</style>"
				+ "<style>{HIDDEN_CSS}</style>"
				+ "<style>{EXTRA_CSS}</style>"
				+ "</head>"
				+ "<body "
				+ "id='yui-" + elementId + "' "
				+ "onload='document.body._rteLoaded = true;'>"
				+ addedHTML
				+ "{CONTENT}"
				+ "</body>"
				+ "</html>"
		});

		this.editor.render();
		this.langCode = langCode;
	},
	
	getSentenceCount : function() {
		return this.sentenceCount;
	},

	setEditorText : function(sentenceNumber, aContent){
		content = this.convertDisplayValue(this.id, sentenceNumber, aContent);
		//alert(content.innerHTML);
		if(content.tagName.toLowerCase() != 'br'){
			//content.observe('click', function(event){}
			Event.observe(content,'click', function(event){
				var ele = event.element();
				var number = ele.id.split(':')[1];
				var ts = this.getTaggedSentences();
				
				this.highlightSentence(ele.id);
				
				if(this.getLangCode() == $('targetLang').value){
					var sentence = [];
					sentence[0] = ts[number].innerHTML;
					
					translator.voiceGenerator.setLang($('targetLang').value);
					translator.voiceGenerator.getSentence(sentence[0]);
				}
				for(var i = 0; i < this.targets.length; i++){
					this.targets[i].highlightSentence(ele.id);
				}
		    }.bind(this));
		   this.sentenceCount++;
		}

		var eBody = this.getRealEditorElement();
		if(eBody.innerHTML == "&nbsp;"){
			eBody.innerHTML = '';
		}

		if(eBody.innerHTML != '' && eBody.lastChild.innerHTML != ""
			&& eBody.lastChild.tagName.toLowerCase() != 'br'
			&& content.tagName.toLowerCase() != 'br')
		{
			eBody.appendChild(this.getDocument().createTextNode(" "));
		}
		eBody.appendChild(content);
	},

	getRealEditorElement : function(){
		var iframe = $(this.id + this.postfixEditorId);
	    if(iframe.contentDocument) {
	    	return iframe.contentDocument.body;
	    }else{
	    	return iframe.Document.body;
	    }
	},

	getEditorText : function(){
		var text = this.editor.getEditorHTML();
		text = this.editor.cleanHTML(text);
		text = text.replace(/\n/g, " ");
		text = text.replace(/<br>/gi, "\n");
		text = text.replace(/<\/dt>/gi, "\n");
		text = text.replace(/<\/dd>/gi, "\n");
		text = text.replace(/<\/li>/gi, "\n");
		text = text.replace(/<\/div>/gi, "\n");
		text = text.replace(/<\/p>/gi, "\n");
		text = text.replace(/<\/h[0-9]>/gi, "\n");
		// for ms-word2007
		text = text.replace(/<p class="MsoNormal">/gi, "\n");
		text = text.replace(/<hr>/gi, "\n");
		text = text.unescapeHTML();
		text = text.replace(/<\/?[^>]+>/gi, " ");
		text = text.replace(/ {2,}/gi, " ");
		// for ms-word2007
		text = text.replace(/\/\*.*\*\/.*MsoNormalTable.*;}/gi, " ");
		text = text.strip();
		return text;
	},

	getTaggedSentences : function(){
		var re = this.getRealEditorElement();
		return re.getElementsByTagName('span');
	},

	highlightSentence : function(id){
		var number = id.split(':')[1];
		var ts = this.getTaggedSentences();

		for(var i = 0; i < ts.length; i++){
			ts[i].className = '';
		}
		if(number > ts.length){
			return;
		}
		if(ts[number] != null && ts[number].className != null
				&& ts[number].className != undefined)
		{
			ts[number].className = 'highlight';
		}
	},

	getLangCode : function(){
		return this.langCode;
	},

	setLangCode : function(langCode){
		this.langCode = langCode;
	},

	clearText : function(){
		this.editor.clearEditorDoc();
	},

	isEmpty : function(){
		if($(this.id)){
			this.editor.saveHTML();
			var html = "";
			var ele = this.getRealEditorElement();
			if(ele.hasChildNodes()){
				var now_node = ele.firstChild;
				do{
					if(now_node.tagName){
						if(now_node.tagName.toLowerCase() != 'br'){
							html += now_node.innerHTML;
						}
					}else{
						if(now_node.nodeType == 3){
							html += now_node.nodeValue;
						}
					}
					now_node = now_node.nextSibling;
				}while(now_node)
			}
			html = html.replace(/\&nbsp;/g,"");
			html = html.replace(/<br>/g,"");
			html = html.replace(/[\s・ｽ@]/g,"");
			return html == "";
		}else{
			return true;
		}
		
		//this.editor.saveHTML();
		//return this.editor.cleanHTML(this.editor.getEditorHTML()) == "";
	},

	addTarget : function(target){
		this.targets.push(target);
	},

	getDocument : function(){
		if(Prototype.Browser.IE){
			var iframe = $(this.id + this.postfixEditorId);
		    if(iframe.contentDocument) {
		    	return iframe.contentDocument;
		    }else{
		    	return iframe.Document;
		    }
		}else{
			return document;
		}
	},

	convertDisplayValue : function(id, number, value){
		var doc = this.getDocument();
		if(value == "[[#ret]]"){
			return doc.createElement('br');
		}
		var element = doc.createElement('span');
		
		Element.extend(element);
		element.id = id + ":" + number;
		if(value.match("^" + this.TIME_OUT_ERROR_CODE)){
			element.style.color = "red";
			value = value.replace(this.TIME_OUT_ERROR_CODE, "");
		}
		value = value.replace(/&#039;/gi, "'");
		value = value.unescapeHTML();
		value = value.replace(/<\/?[^>]+>/gi, " ");
		value = value.replace(/ {2,}/gi, " ");
		value = value.strip();
		//element.update(value);
		Element.update(element,value);
		return element;
	}
});


