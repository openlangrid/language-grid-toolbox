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
/* $Id: categories-hash.js 3662 2010-06-16 02:22:17Z yoshimura $ */

var CategoriesHash = Class.create();
Object.extend(CategoriesHash.prototype, Hash.prototype);
Object.extend(CategoriesHash.prototype, {
	getName : function(id, language) {
		var language = language || '';
		var name = this.get(id)[language];
		var sourceLanguage;
		var source;
		if (!name) {
			name = Global.Text.BLANK;
			sourceLanguage = this.get(id).language
			if (language != sourceLanguage) {
				source = (this.get(id)[sourceLanguage] || Global.Text.BLANK);
				name += '(' + source + ')';
			}
		}
		return name;
	}
});