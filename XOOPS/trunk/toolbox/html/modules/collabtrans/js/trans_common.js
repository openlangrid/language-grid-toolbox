
    function cycle() {
        var i = 0; labels = arguments.slice.call();
        return function() { return labels[i++%labels.length]; }
    }
	
    function replaceClassName(element, name1, name2) {
        element = $(element);
        if(element.hasClassName(name1)) {
            element.removeClassName(name1);
            element.addClassName(name2);
        } else if(element.hasClassName(name2)) {
            element.removeClassName(name2);
            element.addClassName(name1);
        }
    }
    
    function params(form) {
        if (typeof form == "string") {
        	return Form.serialize($(document.forms[form])).toQueryParams();
        } else {
        	return Form.serialize(form? $(form) : document.forms["main"]).toQueryParams();	
        }
    }
    
    


function FontResizer(targetSelectors, options) {
	var options = options || {};
	var min = options.min || FontResizer.DEFAULT_FONT_SIZE_MIN;
	var max = options.max || FontResizer.DEFAULT_FONT_SIZE_MAX;
	if(!options.value) options.value = FontResize.DEFAULT_VALUE;
	
	function getDefaultFontSize(element) {
		var dummy = new Element("span").update("a");
		element.insert(dummy);
		var height = dummy.getHeight();
		dummy.remove();
		return height;
	}
	function setFontSize(addPixel, firstElement) {
		var currentSize = parseInt(firstElement.getStyle("fontSize"));
		if(!currentSize) currentSize = getDefaultFontSize(firstElement);
		var size = currentSize + addPixel;
		return function(element) {
			if(min < size && size < max)
				element.style.fontSize = size + 'px';
		}
	};
	var addFontSizePlus = setFontSize.curry(options.value);
	var addFontSizeMinus = setFontSize.curry(-options.value);
	
	function delegator(setFontFunc) {
		targetSelectors.each(function(selector) {
			$$(selector).inject(setFontFunc, function(func, e, index) {
				if(index == 0) func = func(e);
				func(e);
				return func;
			});
		});
	}
	
	var position = 0;
		
	return {
		toSmall: function(){ position--; delegator(addFontSizeMinus) },
		toLarge: function(){ position++; delegator(addFontSizePlus) },
		getPosition: function() { return position },
		restorePosition: function(positionValue) {
			var obj = this;
			positionValue.abs().times(function(v){
				if(positionValue > 0) obj.toLarge();
				else				  obj.toSmall();
			});
			position = positionValue;
		}
	}
}
FontResizer.DEFAULT_FONT_SIZE_MIN = 10;
FontResizer.DEFAULT_FONT_SIZE_MIN = 100;
FontResizer.DEFAULT_VALUE = 2;


Element.addMethods({
	btnEnable: function(element) {
		Element.removeClassName(element, "btn-disable");
		Element.addClassName(element, "btn");
	},
	btnDisable: function(element) {
		Element.removeClassName(element, "btn");
		Element.addClassName(element, "btn-disable");
	},
	btnEnabled: function(element) {
		return !Element.hasClassName(element, "btn-disable") && !Element.hasClassName(element, "btn-s-disable");
	},
	btnDisabled: function(element) {
		return Element.hasClassName(element, "btn-disable") || Element.hasClassName(element, "btn-s-disable");
	}
});

function autofitTextarea(element){
	var currentHeight = parseInt(element.getStyle("height"));
	if(element.scrollHeight > currentHeight){
		element.setStyle({"height": element.scrollHeight + 'px'});
	} else {
		while (true){
			currentHeight -= 5; 
			element.setStyle({"height": currentHeight + 'px'});
			if(element.scrollHeight > currentHeight) break;
		}
		element.setStyle({"height": element.scrollHeight + 'px'});
		
	}
	return element;
}