//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2010-2013  NICT Language Grid Project
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

var ServiceButton = Class.create();
ServiceButton.prototype = {
	__CSS_ON: 'setting_btn_on',
	__CSS_OFF:'setting_btn_off',
	_serviceInfo : null,
	_parentElem : null,
	_this_id : null,
	_buttonElem : null,
	_buttonArea : null,
	_seperator: '#',

	initialize: function() {
		// nothing to do
	},
	
	makeServiceButton: function(parentElem, serviceInfo, clickHandler, controller) {
		this._parentElem = parentElem;
		this._serviceInfo = serviceInfo;
		this._this_id = parentElem.id + this._seperator + this._serviceInfo.service_id;

		this._buttonArea = document.createElement('div');
		this._buttonArea.id = parentElem.id + this._seperator + 'btArea' + this._seperator + this._serviceInfo.service_id;
		this._buttonElem = this._showButton(this._buttonArea);
		this._buttonElem.observe('click', clickHandler.bindAsEventListener(controller));

		return this._buttonElem;
	},
	
	setButtonMode: function(mode) {
		switch (mode) {
		case 'active':
			this._buttonElem.removeClassName(this.__CSS_OFF);
			this._buttonElem.addClassName(this.__CSS_ON);
			this._buttonArea.show();
			break;
			
		case 'display':
			this._buttonElem.removeClassName(this.__CSS_ON);
			this._buttonElem.addClassName(this.__CSS_OFF);
			this._buttonArea.show();
			break;
			
		case 'hide':
			this._buttonElem.removeClassName(this.__CSS_ON);
			this._buttonElem.addClassName(this.__CSS_OFF);
			this._buttonArea.hide();
			break;
		}
	},
	
	isSupportedLanguage: function(src, tgt, flow) {
		var ret = false;
		
		var pairs = this._getSupportedLanguage();

		if (flow == 'both') {

			var comp = src + '2' + tgt;
			var revs = tgt + '2' + src;
			if (pairs.indexOf(comp) != -1 && pairs.indexOf(revs) != -1) {
				ret = true;
			}
		} else if (flow == 'left'){
			var comp = src + '2' + tgt;
			if (pairs.indexOf(comp) != -1) {
				ret = true;
			}
		} else {
			ret = false;
		}
		
		return ret;
	},
	
	isSupportedLanguageExt: function(src, tgt, flow) {
		var ret = false;

		if(src != '' && tgt != ''){
			var pairs = this._getSupportedLanguage();
			if (flow == 'both') {
				var comp = src + '2' + tgt;
				var revs = tgt + '2' + src;
				if (pairs.indexOf(comp) > -1 && pairs.indexOf(revs) > -1) {
					ret = true;
				}
			} else if (flow == 'left'){
				var comp = src + '2' + tgt;
				if (pairs.indexOf(comp) > -1) {
					ret = true;
				}
			}else{
				ret = false;
			}
		} else if (src != '' || tgt != '') {
			var pairs = ","+this._serviceInfo.supported_languages_paths+",";
			if(src != ''){var skey = ","+src+"2";}
			if(tgt != ''){var skey = "2"+tgt+",";}

			if (pairs.indexOf(skey) > -1) {
				ret = true;
			}
			if (flow == 'both') {
				if(src != ''){var r_skey = "2"+src+",";}
				if(tgt != ''){var r_skey = ","+tgt+"2";}
				if (pairs.indexOf(r_skey) > -1) {
					ret = true;
				}
			}
		} else {
			ret = false;
		}
		return ret;
	},
	
	_getMatchLangTgt: function(src, flow) {
		var ret = Array();
		var skey =new RegExp("^" + src + "2.+$");
		var r_skey =new RegExp("^.+2" + src + "$");

		var pairs = this._getSupportedLanguage();
		for(i=0;i<pairs.length;i++){
			if(pairs[i].match(skey)) {
				var tmp = pairs[i].split("2");
//				if(tmp[1] != src){
					ret.push(tmp[1]);
//				}
			}else{
				if (flow == 'both') {
					if(pairs[i].match(r_skey)) {
						var tmp = pairs[i].split("2");
//						if(tmp[1] != src){
							ret.push(tmp[1]);
//						}
					}
				}
			}
		}
		return ret;
	},
	_getSupportedLanguage: function() {
		var pathTokens = this._serviceInfo.supported_languages_paths.split(',');
		return pathTokens;
	},
	_showButton: function(btnArea) {
		var bElem = document.createElement('a');
		bElem.id = this._this_id;
		Element.addClassName(bElem,this.__CSS_OFF);
		var txt = this._serviceInfo.service_name;
		bElem.innerHTML = txt;
		btnArea.appendChild(bElem);

		var info = document.createElement('a');
		Element.addClassName(info,'btn-service-info');
		info.innerHTML = '[i]';
		info.observe('click', this._showPopup.bindAsEventListener(this));
		btnArea.appendChild(info);

		var br = document.createElement('br');
		Element.addClassName(br,'clear');
		btnArea.appendChild(br);
		this._parentElem.appendChild(btnArea);

		return bElem;
	},

	_showPopup: function(ev) {
		if(!$('baloon-' + this._serviceInfo.service_id)){
			this._loadPopup();
		}

		Event.stop(ev);
		var infobtn = Event.element(ev);
		var pos = infobtn.cumulativeOffset();

		var top = pos[1];
		var left = pos[0] + 20;

		var balElem = $('baloon-' + this._serviceInfo.service_id);
		if(balElem.style.display == 'none'){
			$$(".popnowopen").each(function(ele){
				Element.removeClassName(ele,"popnowopen");
				Element.hide(ele);
			});
			Element.addClassName(balElem,"popnowopen");
			Element.show(balElem);

			var vp = document.viewport.getDimensions();
			var vp_sc = document.viewport.getScrollOffsets();

			if((top + balElem.offsetHeight - vp_sc.top) > vp.height){
				top = vp.height - balElem.offsetHeight + vp_sc.top;
			}

			if((left + balElem.offsetWidth - vp_sc.left) > vp.width){
				if((left - 30) > balElem.offsetWidth){
					left = left - 30 - balElem.offsetWidth;
				}else{
					left = vp.width - balElem.offsetWidth + vp_sc.left;
				}
			}

			balElem.setStyle('position:absolute; left:'+left+'px; top:'+top+'px');

		}else{
			Element.removeClassName(balElem,"popnowopen");
			Element.hide(balElem);
		}
	},

	_hidePopup: function(ev) {
		Event.stop(ev);
		var balElem = $('baloon-' + this._serviceInfo.service_id);
		balElem.hide();
	},

	_loadPopup: function(){
		var controller = this;
		var postObj = {serviceId:this._serviceInfo.service_id};
		var hash = $H(postObj).toQueryString();

//		new Ajax.Request('./ajax/load-service-info.php', {
		new Ajax.Request(Resource.url.loadServiceInfo, {
			method: 'post',
			parameters: hash,
			controller: controller,
			asynchronous:false,
			onSuccess: function(httpObj) {
				try {
					var responseJSON = httpObj.responseText.evalJSON()
					if (responseJSON.status != 'OK') {
						alert(responseJSON.message);
						return;
					}

					var popup = document.createElement('div');
					popup.id = 'baloon-'+this._serviceInfo.service_id;
					popup.className = 'subwindow-border';
					popup.style.position = 'absolute';
					popup.style.display = 'none';
					popup.innerHTML = responseJSON.contents;

					$('contents_body').appendChild(popup);
				} catch (e) {
					alert(e);
				}
			}.bind(this),
			onFailure: function(httpObj) {
				$('setting_error_message').innerHTML = '<pre>'+httpObj.responseText+'</pre>';
			},
			onComplete: function() {}
		});
	}
};