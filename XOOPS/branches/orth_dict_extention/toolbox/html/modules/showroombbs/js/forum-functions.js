//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to view
// contents of BBS without logging into Toolbox.
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
function formSubmit(formName) {
	try {
		$$('#bbs-splash span')[0].show();
	} catch (e) {
		;
	}
	document[formName].submit();
	return false;
}
function modifySubmit() {
	var errorMessages = new Array();
	$$('.bbs-required').each(function(element){
		if (!element.value) {
			errorMessages.push(Const.Message.errorMessageRequired.replace('%s', element.title));
		}
	});
	if (errorMessages.length > 0) {
		alert(errorMessages.join("\n"));
	} else {
		document.modifyForm.submit();
	}
	return false;
}