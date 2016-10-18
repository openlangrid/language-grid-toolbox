//  ------------------------------------------------------------------------- //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
//  ------------------------------------------------------------------------- //

function doValidate(elements, type, predicate) {
	var messages = [];
	var fn = eval("validate" + type);

	for (var id in elements) {
		var value = $(id).value.trim();
		if (!fn(value)) {
			messages.push(elements[id] + predicate);
		}
	}

	return messages.join("\n");
}

function validateRequired(str) {
	if (str.length < 1) {
		return false;
	}

	// passed validation
	return true;
}

function validateDate(str) {
	// check format
	if (isNaN(Date.parse(str))) {
		return false;
	}

	// passed validation
	return true;
}

function validateTime(str) {
	// check format
	if (!str.match(/^\d{1,2}:\d{1,2}$/)) {
		return false;
	}

	var pieces = str.split(":");
	var date = new Date(2000, 0, 1, pieces[0], pieces[1], 0);
	if (date.getHours() != pieces[0] || date.getMinutes() != pieces[1]) {
		return false;
	}

	// passed validation
	return true;
}
