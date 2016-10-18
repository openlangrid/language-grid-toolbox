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

var ContractAction = Class.create(AbstractAction, {
	
	CONTRACTION_POINT: 20,
	
	/**
	 * 
	 */
	initialize: function() {
	
	},
	
	execute: function() {
		Logger.info('ContractAction.execute');

		this.contract('wc-source-body');
		this.contract('wc-target-body');
	},
	
	getNextHeight: function(element) {
		var height = ($(element).getHeight() - this.CONTRACTION_POINT);
		return (height < this.CONTRACTION_POINT) ? this.CONTRACTION_POINT : height;
	},
	
	contract: function(element) {
		$(element).setStyle({
			height: this.getNextHeight(element) + 'px'
		});
	}
});