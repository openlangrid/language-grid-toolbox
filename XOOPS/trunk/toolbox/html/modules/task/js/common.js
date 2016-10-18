//  ------------------------------------------------------------------------- //
// This is a module for Language Grid Toolbox. This allows a user to
// manage translation tasks.
// Copyright (C) 2010  CITY OF KYOTO
//  ------------------------------------------------------------------------- //

// extend String
String.prototype.trim = function() {
	return this.replace(/$\s+|\s+$/g, "");
}
/*
Event.observe(window, "load", function() {
	document.observe("click", function(event) {
		$$(".hover_menu").invoke("hide");
	});
});
*/