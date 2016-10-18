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
    
    
    
    car: function(){
        return this.getFirst();
    },
    
    
    
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
    
    
    
    ok: function(){
        return true;
    },
    
    
    
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
        
            
        	
            var evt = document.createEvent("MouseEvents");
            evt.initMouseEvent("click", true, true, window,
				 0, 0, 0, 0, 0, false, false, false, false, 0, null);
            
            DOMObject.dispatchEvent(evt);
        } else {
			
			
			
            var evt = document.createEventObject();
            evt.element = function(){
                return evt.srcElement;
            }
            
            DOMObject.fireEvent('onClick', evt);
        }
    },
	
	change: function(DOMObject){
		
        if (document.createEvent) {
        
            
            
            var evt = document.createEvent("HTMLEvents"); 
            evt.initEvent("change", true, true, window,
				 0, 0, 0, 0, 0, false, false, false, false, 0, null);
            
            DOMObject.dispatchEvent(evt);
        } else {
			
			
			
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
		case 500: description = "please report this error to Language Grid developers (playground@langrid.org)."; break;
		case 502: description = "please retry your action."; break;
		case 504: description = "please check your proxy setting."; break;
	}
	alert(httpObj.status+' '+httpObj.statusText+': '+description);
}