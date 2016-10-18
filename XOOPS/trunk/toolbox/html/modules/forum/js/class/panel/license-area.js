//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
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
var LicenseArea = Class.create();
LicenseArea.prototype = {
	
	element : null,
	licenses : null,
	
	initialize : function(id) {
		this.element = $(id);
		this.licenses = new Hash();
	},
	addLicenses : function(licenses) {
		this.licenses = this.licenses.merge(new Hash(licenses));
		this.draw();
	},
	draw : function() {
		var html = new Array();
		var i = 0;
		if (this.licenses.keys().indexOf('GoogleTranslate') != -1) {
			i = 1;
			html.push('<p>Powered by Google.<br />Technical words are replaced appropriately by the Language Grid.</p>');
		}
		this.licenses.each(function(pair) {
			var license = pair.value;
			if (!license.serviceName) {
				return;
			}
			var addMessage = '';
			html.push(this.getTemplate().evaluate({
				className : (i > 0) ? 'license-information-with-border' : 'license-information'
				, message : addMessage
				, serviceNameLabel : Const.Label.serviceName
				, serviceName : license.serviceName
				, copyrightLabel : Const.Label.copyright
				, copyright : license.serviceCopyright || '-'
				, informationLabel : Const.Label.licenseInformation
				, information : (!!license.serviceLicense)
					? this.httpAutoLink(license.serviceLicense) : '-'
			}));
			i++;
		}.bind(this));
		this.element.innerHTML = html.join('');
	},
	getTemplate : function() {
		return new Template('<div class="#{className}">'
			+ '#{message}'
			+ '<div class="license-title">#{serviceNameLabel}</div>'
			+ '<div class="license-body">#{serviceName}</div>'
			+ '<div class="license-title">#{copyrightLabel}</div>'
			+ '<div class="license-body">#{copyright}</div>'
			+ '<div class="license-title">#{informationLabel}</div>'
			+ '<div class="license-body">#{information}</div>'
			+ '</div>');
	},
	clear : function() {
		this.licenses = new Hash();
		this.element.innerHTML = '';
	},
	httpAutoLink : function(text) {
		return text.replace(/(https?|ftp)(\:\/\/[0-9a-zA-Z\+\$\;\?\.\%\,\!\#\~\*\/\:\@\&\=\_\-]+)/g,
				 '<a href="$1$2" target="_blank">$1$2</a>');
	}
};