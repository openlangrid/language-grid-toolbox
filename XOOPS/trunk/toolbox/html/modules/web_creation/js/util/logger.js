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

var Logger = {
	log: function(val) {},
	info: function(val) {},
	warn: function(val) {},
	error: function(val) {},
	group: function(val) {},
	groupEnd: function(val) {},
	trace: function(val) {},
	dir: function(val) {}
};

if (DEBUG_MODE && !!console) {
	if (Prototype.Browser.WebKit) {
		Logger.log = console.log.bind(console);
		Logger.info = console.info.bind(console);
		Logger.warn = console.warn.bind(console);
		Logger.error = console.error.bind(console);
		Logger.group = console.group.bind(console);
		Logger.groupEnd = console.groupEnd.bind(console);
		Logger.trace = console.trace.bind(console);
		Logger.dir = console.dir.bind(console);
	} else {
		Logger.log = console.log;
		Logger.info = console.info;
		Logger.warn = console.warn;
		Logger.error = console.error;
		Logger.group = console.group;
		Logger.groupEnd = console.groupEnd;
		Logger.trace = console.trace;
		Logger.dir = console.dir;
	}
}