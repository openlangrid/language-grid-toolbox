//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox.
//
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
/* $Id: language-utils.js 3662 2010-06-16 02:22:17Z yoshimura $ */

var LanguageUtils = {
	sort : function(languages, order) {
		switch (order) {
		case 'desc':
			languages.sort(this._sortDesc);
			break;
		default:
			languages.sort(this._sortAsc);
			break;
		}
	},

	_sortAsc : function(a, b) {
		for (var key in Global.Language) {
			if (key == a) {
				if (key == b) {
					return 0;
				}
				return -1;
			} else if (key == b) {
				return 1;
			}
		}
	},

	_sortDesc : function(a, b) {
		for (var key in Global.Language) {
			if (key == a) {
				if (key == b) {
					return 0;
				}
				return 1;
			} else if (key == b) {
				return -1;
			}
		}
	}
};