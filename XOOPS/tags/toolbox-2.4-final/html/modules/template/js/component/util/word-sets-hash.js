//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
/**
 * @author kitajima
 */
var WordSetsHash = Class.create();
Object.extend(WordSetsHash.prototype, Hash.prototype);
Object.extend(WordSetsHash.prototype, {
	
	textType : null,
	
	isEnum : function(id) {
		var set = this.get(id);
		
		return (set) && (set.type == 'enum');
	},
	
	getName : function(id, language) {
		var language = language || '';
		var set = this.get(id);
		if (!set) {
			return Global.Text.BLANK;
		}
		if (set.type != 'enum') {
			return set.type;
		}
		
		var name = set.expressions[language];
		
		
		return (name) ? name : Global.Text.BLANK;
	},
	
	getTextType : function() {
		if (!this.textType) {
			this.each(function(pair) {
				if (pair.value.type == 'text') {
					this.textType = pair.value;
					throw $break;
				}
			}.bind(this));
		}
		
		return this.textType;
	}
});