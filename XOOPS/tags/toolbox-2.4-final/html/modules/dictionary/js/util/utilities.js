//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
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
var Pair = Class.create();
var StatusProcessor = Class.create();
var EventDispatcher = Class.create();
var BrowserIdentifier = Class.create();

Pair.prototype = {
    initialize: function(first, second){
        this.first = first;
        this.second = second;
    },

    setFirst: function(obj){
        this.first = obj;
    },

    getFirst: function(){
        return this.first;
    },

    setSecond: function(obj){
        this.second = obj;
    },

    getSecond: function(){
        return this.second;
    },

    //* @alias	getFirst
    car: function(){
        return this.getFirst();
    },

    //* @alias	getFirst
    cdr: function(){
        return this.getSecond();
    },

    caar: function(){
        return this.car().car();
    },
    cdar: function(){
        return this.cdr().car();
    },
    cadr: function(){
        return this.car().cdr();
    },
    cddr: function(){
        return this.cdr().cdr();
    },

    caaar: function(){
        return this.car().car().car();
    },
    cadar: function(){
        return this.car().cdr().car();
    },
    caadr: function(){
        return this.car().car().cdr();
    },
    caddr: function(){
        return this.car().cdr().cdr();
    },
    cdaar: function(){
        return this.cdr().car().car();
    },
    cddar: function(){
        return this.cdr().cdr().car();
    },
    cdadr: function(){
        return this.cdr().car().cdr();
    },
    cdddr: function(){
        return this.cdr().cdr().cdr();
    }
};

StatusProcessor.prototype = {
    LIMITATION_ERROR_MESSAGE: "Service Limitation Error",
    LIMITATION_WARNING_MESSAGE: "Service Limitation Warning",

    initialize: function(responseObj, errorMessage, warningMessage){
        this.response = responseObj;
        this.status = this.response.status;
        this.message = this.response.message;
        this.contents = this.response.contents;
        this.warningMessage = warningMessage;
        this.errorMessage = errorMessage;
    },

    check: function(){
        if (this.status == 'ERROR') {
            return this.error();
        }
        else
            if (this.status == 'WARNING') {
                return this.warning();
            }
            else
                if (this.status == 'OK') {
                    return this.ok();
                }
                else {
                    return this.other();
                }
    },

    //* Overridable method
    error: function(){
        if (this.errorMessage != undefined) {
            alert(this.errorMessage);
        }
        else
            if (this.message != undefined) {
                alert(this.message);
            }
            else {
                alert("Error");
            }
        return false;
    },

    //* Overridable method
    warning: function(){
        if (this.warningMessage != undefined) {
            alert(this.warningMessage);
        }
        else
            if (this.message != undefined) {
                alert(this.message);
            }
            else {
                alert("Warning");
            }
        return true;
    },

    //* Overridable method
    ok: function(){
        return true;
    },

    //* Overridable method
    other: function(){
        alert("Error: Unknown status error");
        return false;
    },

    isLimitationError: function(){
        if (this.message.include(this.LIMITATION_ERROR_MESSAGE))
            return true;
        else
            return false;
    },

    isLimitationWarning: function(){
        if (this.message.include(this.LIMITATION_WARNING_MESSAGE))
            return true;
        else
            return false;
    }
};

EventDispatcher.prototype = {

    click: function(DOMObject){

        if (document.createEvent) {

            //* for Firefox

            var evt = document.createEvent("MouseEvents");
            evt.initMouseEvent("click", true, true, window,
				 0, 0, 0, 0, 0, false, false, false, false, 0, null);

            DOMObject.dispatchEvent(evt);
        } else {

			//* for IE

            var evt = document.createEventObject();
            evt.element = function(){
                return evt.srcElement;
            }

            DOMObject.fireEvent('onClick', evt);
        }
    },

	change: function(DOMObject){

        if (document.createEvent) {

            //* for Firefox

            var evt = document.createEvent("HTMLEvents");
            evt.initEvent("change", true, true, window,
				 0, 0, 0, 0, 0, false, false, false, false, 0, null);

            DOMObject.dispatchEvent(evt);
        } else {

			//* for IE

            var evt = document.createEventObject();
            evt.element = function(){
                return evt.srcElement;
            }

            DOMObject.fireEvent('onChange', evt);
        }
    }
}

BrowserIdentifier = {
	getBrowserName: function(){
		if(/opera/i.test(navigator.userAgent)) return 'Opera';
		else if(/msie/i.test(navigator.userAgent)) return 'Internet Explorer';
		else if(/chrome/i.test(navigator.userAgent)) return "Google Chrome";
		else if(/safari/i.test(navigator.userAgent)) return "Safari";
		else if(/firefox/i.test(navigator.userAgent)) return "Firefox";
		else if(/gecko/i.test(navigator.userAgent)) return "Gecko";
		else return navigator.userAgent;
	},

	getBrowserDetail: function(){
		return navigator.userAgent;
	},

	isIE: function(){
		return BrowserIdentifier.getBrowserName() == 'Internet Explorer';
	},

	isIE6: function(){
		if(/msie 6/i.test(navigator.userAgent)) return true;
		else false;
	},

	isIE7: function(){
		if(/msie 7/i.test(navigator.userAgent)) return true;
		else false;
	},

	isFF: function(){
		return BrowserIdentifier.getBrowserName() == 'Firefox';
	},

	isFF2: function(){
		if(/firefox\/2/i.test(navigator.userAgent)) return true;
		else false;
	},

	isFF3: function(){
		if(/firefox\/3/i.test(navigator.userAgent)) return true;
		else false;
	},

	isSafari: function(){
		return BrowserIdentifier.getBrowserName() == 'Safari';
	},

	isOpera: function(){
		return BrowserIdentifier.getBrowserName() == 'Opera';
	},

	isChrome: function(){
		return BrowserIdentifier.getBrowserName() == "Google Chrome";
	},

	isGecko: function(){
		return BrowserIdentifier.getBrowserName() == "Gecko";
	}
}

function getOrdinalNumber(number){
    var nn = number % 100;
    var n1 = nn % 10;
    var n2 = (nn - n1) / 10
    if (n2 != 1) {
        if (n1 == 1)
            return number + 'st';
        else
            if (n1 == 2)
                return number + 'nd';
            else
                if (n1 == 3)
                    return number + 'rd';
                else
                    return number + 'th';
    }
    else {
        return number + 'th';
    }
}

function handleHTTPStatusCode(httpObj){
	var description = '';
	switch(httpObj.status){
		case 500: description = "please report this error to administorator"; break;
		case 502: description = "please retry your action."; break;
		case 504: description = "please check your proxy setting."; break;
	}
	alert(httpObj.status+' '+httpObj.statusText+': '+description);
}


function swapElement(elementA, elementB) {
	var cloneA = elementA.cloneNode(true);
	elementA.parentNode.replaceChild(elementB.cloneNode(true), elementA);
	elementB.parentNode.replaceChild(cloneA, elementB);
}

function getOrCreateLastStyleSheet() {
	if (document.styleSheets.length) {
		return document.styleSheets[document.styleSheets.length - 1];
	} else {
		var isMSIE = /*@cc_on!@*/false;
		var sheet;
		if (isMSIE) {  // for IE8
			sheet = document.createStyleSheet();
		} else {  // for FireFox, Opera, Safari, Crome
			var head = document.getElementsByTagName('head')[0];
			if (head == null) { return; }
			var style = document.createElement('style');
			head.appendChild(style);
			sheet = style.sheet;
		}
		return sheet;
	}
}

var CSSUtil = {
	isMSIE: /*@cc_on!@*/false,
	addStyleRule: function(selector, declaration) {
		var sheet = getOrCreateLastStyleSheet();
		this.isMSIE ? 
			sheet.addRule(selector, declaration) :
			sheet.insertRule(selector + '{' + declaration + '}', sheet.cssRules.length);				
	},

	removeStyleRule: function(selector, declaration) {
		var sheet = getOrCreateLastStyleSheet();
		$A(sheet.rules || sheet.cssRules).each(function(rule, i){
			if(CSSUtil.equalsSelectorAndDeclaration(rule, selector, declaration)) {
				CSSUtil.isMSIE ? sheet.removeRule(i) : sheet.deleteRule(i);
			}
		});
	},
	
	isSetStyleRule: function(selector, declaration) {
		var sheet = getOrCreateLastStyleSheet();
		return $A(sheet.rules || sheet.cssRules).any(function(rule, i){
			return CSSUtil.equalsSelectorAndDeclaration(rule, selector, declaration);
		});
	},
	
	equalsSelectorAndDeclaration: function(rule, selector, declaration) {
		return rule.selectorText.gsub(/\s/, "") == selector.gsub(/\s/, "") && 
			   rule.style.cssText.gsub(/\s/, "").sub(/;$/,"").toLowerCase() == declaration.gsub(/\s/, "").sub(/;$/,"").toLowerCase();
	},
	
	dump: function() {
		var sheet = getOrCreateLastStyleSheet();
		var text = $A(sheet.rules || sheet.cssRules).map(function(rule, i){
			return rule.selectorText + " {\n" +
				rule.style.cssText.split(/;\s*/).map(function(e){ 
					return e ? "\t" + e + ";" : "";
				}).compact().join("\n") +
			"\n}";
		}).join("\n");
		
		if(!$("cssdump")) {
			$(document.body).insert(new Element("div", {id: "cssdump"}).setStyle({textAlign: "left"}));
		}
		$("cssdump").update(text.gsub(/\n/, "<br>").gsub(/\t/, "&nbsp;&nbsp;&nbsp;&nbsp;"));
	}
};

var Dictionary = {
		
	isValidName: function(dictionaryName){

//		var pattern = /^([a-zA-Z0-9_-]*\.)*[a-zA-Z0-9_-]*$/;
//		var pattern = /^([a-zA-Z0-9_-]*(\.| ))*[a-zA-Z0-9_-]*$/;
		var pattern = /^([a-zA-Z0-9-]*(\.| ))*[a-zA-Z0-9-]*$/;

		if (dictionaryName.length < 4) {
			return false;
		} else if (!dictionaryName.match(/^.*[A-Za-z].*$/)) {
			return false;
		} else if (dictionaryName.toString().match(pattern) == null) {
			return false;
		} else {
			return true;
		}
	}
}

// テンプレート管理
var TMPL = {
	getColumnsForTableDictionaries: function() {
		return arguments.callee.cash ||
			   (arguments.callee.cash = this.$.down(".table-dictionaries .columns").innerHTML);
	},
	
	getHeaderCellForTableWords: function() {
		return arguments.callee.cash ||
			   (arguments.callee.cash = this.$.down(".table-words .headerCell").innerHTML);
	},
	
	getHeaderRemoveCellForTableWords: function() {
		return arguments.callee.cash ||
			   (arguments.callee.cash = this.$.down(".table-words .headerRemoveCell").innerHTML);
	},
	
	getCellForTableWords: function() {
		return arguments.callee.cash ||
			   (arguments.callee.cash = this.$.down(".table-words .recordCell").innerHTML);
	},
	
	getRemoveCellForTableWords: function() {
		return arguments.callee.cash ||
			   (arguments.callee.cash = this.$.down(".table-words .removeCell").innerHTML);
	},
	
	getNewCellForTableWords: function() {
		return arguments.callee.cash ||
			   (arguments.callee.cash = this.$.down(".table-words .newRecordCell").innerHTML);
	},
	
	getHeaderCellForTableSearchResult: function() {
		return arguments.callee.cash ||
			   (arguments.callee.cash = this.$S.down(".table-search-result .headerCell").innerHTML);
	},
	
	getHeaderResourceNameCellForTableSearchResult: function() {
		return arguments.callee.cash ||
			   (arguments.callee.cash = this.$S.down(".table-search-result .headerResourceName").innerHTML);
	},
	
	getResourceNameCellForTableSearchResult: function() {
		return arguments.callee.cash ||
			   (arguments.callee.cash = this.$S.down(".table-search-result .resourceNameCell").innerHTML);
	}
};

Event.observe(window, 'load', function(){
	TMPL.$ = $("templates");
	TMPL.$S = $("search-view-template");
});
