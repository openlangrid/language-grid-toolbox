/**********************************************************************
* /js/component/service-information.js
* Copyright (C) 2007-2008 Kyoto University
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-
* 1301  USA
***********************************************************************/

var ServiceInformation = Class.create();

ServiceInformation.prototype = {
	ServiceArray : null,

	initialize: function(id) {
		this.ServiceArray = new Array();
		this.viewArea = $(id);
		this.viewArea.innerHTML = Const.Message.Info.licenseAreaMsg;
		this.isInitState = true;
	},

	checkExist: function(serviceName){
		if(this.ServiceArray.indexOf(serviceName) > -1){
			return true;
		}else{
			return false;
		}
	},
	reset: function(){
		this.ServiceArray = new Array();
		this.viewArea.innerHTML = "";
	},
	update: function(serviceProfile) {
		if (this.isInitState) {
			this.viewArea.style.height = "170px";
			this.viewArea.innerHTML = '';
			this.isInitState = false;
			this.ServiceArray = new Array();
		}
		if ($H(serviceProfile).keys().indexOf('GoogleTranslate') != -1) {
			var html = '<p>Powered by Google.<br />Technical words are replaced appropriately by the Language Grid.</p>';
			this.viewArea.innerHTML = html;
			this.ServiceArray.push('Powered by Google');
		}

		$H(serviceProfile).each(function(svcInfo){
			var serviceInfomation = svcInfo.value;
			if(serviceInfomation.serviceName != ""){
				if(!this.checkExist(svcInfo.key)){
					if(this.ServiceArray.length == 0){
						var areaClass = "license-information";
					}else{
						var areaClass = "license-information-with-border";
					}
					var InfoArea = document.createElement('div');
					Element.addClassName(InfoArea,areaClass);
					InfoArea.innerHTML += "<div class='license-title'>" + Const.Label.serviceName + "</div>\n";
					InfoArea.innerHTML += "<div class='license-body'>" + serviceInfomation.serviceName + "</div>\n";
					InfoArea.innerHTML += "<div class='license-title'>" + Const.Label.copyright + "</div>\n";
					var copyright = serviceInfomation.copyright;
					if(copyright == ""){copyright = "-";}
					InfoArea.innerHTML += "<div class='license-body'>" + copyright + "</div>\n";
					InfoArea.innerHTML += "<div class='license-title'>" + Const.Label.licenseInformation + "</div>\n";
					var sLicense = serviceInfomation.license;
					if(sLicense == ""){
						sLicense = "-";
					}else{
						sLicense = this.httpAutoLink(sLicense);
					}
					InfoArea.innerHTML += "<div class='license-body'>" + sLicense + "</div>\n";

					this.viewArea.appendChild(InfoArea);
					this.ServiceArray.push(svcInfo.key);
				}
			}
		}.bind(this));
	},
	httpAutoLink : function(text) {
		return text.replace(/(https?|ftp)(\:\/\/[0-9a-zA-Z\+\$\;\?\.\%\,\!\#\~\*\/\:\@\&\=\_\-]+)/g,
				 '<a href="$1$2" target="_blank">$1$2</a>');
	}
}
