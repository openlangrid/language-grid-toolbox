
window.runOnloadHook = function() {
	$$('input').each(function(elem, index) {
		if (elem.type == 'checkbox') {
			elem.observe('click', function(ev) {
				if (elem.checked) {
					Element.addClassName(elem.parentNode, 'on');
				} else {
					Element.removeClassName(elem.parentNode, 'on');
				}
			});
		}
	});
}

function onLanguageSubmit() {
	var checkedcount = 0;
	$$('input').each(function(elem, index) {
		if (elem.type == 'checkbox' && elem.checked == true) {
			checkedcount++;
		}
	});
	if (checkedcount < 2) {
		alert(Const.Message._MI_DICTIONARY_ERROR_SELECT_AT_LEAST_TWO_LANGUAGES);
		return false;
	}
	return true;
}