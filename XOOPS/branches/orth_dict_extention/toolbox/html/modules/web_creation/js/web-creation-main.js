//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2010  NICT Language Grid Project
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

Event.observe(window, 'load', function() {
	new WebCreationFrame();
	new AutoSaveManager();
});

if (Prototype.Browser.WebKit) {
	window.onbeforeunload = function(event) {
		if (Model.EditState.isEdited()) {
			return event.returnValue = Resource.NOSAVE_CONFIRM_MSG;
		}
	};
} else {
	Event.observe(window, 'beforeunload', function(event) {
		if (Model.EditState.isEdited()) {
			return event.returnValue = Resource.NOSAVE_CONFIRM_MSG;
		}
	}.bindAsEventListener(this));
}
