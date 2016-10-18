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
/**
 * @author kitajima
 */
var TransportWrapper = Class.create();
TransportWrapper.prototype = {
	_response : null,
	_transport : null,

	/**
	 * Constructor
	 */
	initialize : function(transport) {
		this.setTransport(transport);
		this.setResponse(transport.responseText.evalJSON());
	},

	/**
	 * wrapper
	 */
	getLicenses : function() {
		return this.getResponse().licenseInformation;
	},
	getStatus : function() {
		this.getResponse().status;
	},
	getBackText : function() {
		if(this.getResponse().contents[this.getTargetLanguageCode()][0]){
			if(this.getResponse().contents[this.getTargetLanguageCode()][0].status.toUpperCase() == 'WARNING'){
				return this.getResponse().contents.backText.contents;
			}else{
				return "";
			}
		}else{
			if(this.getResponse().contents[this.getTargetLanguageCode()].backTranslation.contents){
				return this.getResponse().contents[this.getTargetLanguageCode()].backTranslation.contents;
			}else{
				return "";
			}
		}
	},
	getTargetText : function() {
		if(this.getResponse().contents[this.getTargetLanguageCode()][0]){
			if(this.getResponse().contents[this.getTargetLanguageCode()][0].status.toUpperCase() == 'WARNING'){
				return this.getResponse().contents.targetText.contents;
			}else{
				return "";
			}
		}else{
			if(this.getResponse().contents[this.getTargetLanguageCode()].translation.contents){
				return this.getResponse().contents[this.getTargetLanguageCode()].translation.contents;
			}else{
				return "";
			}
		}
	},
	setBackText : function(backText) {
		if(this.getResponse().contents[this.getTargetLanguageCode()].backTranslation == "undefined" || this.getResponse().contents[this.getTargetLanguageCode()].backTranslation == null){
			this.getResponse().contents[this.getTargetLanguageCode()].backTranslation = {};
		}
		this.getResponse().contents[this.getTargetLanguageCode()].backTranslation.contents = backText;
		return this;
	},
	setTargetText : function(targetText) {
		if(this.getResponse().contents[this.getTargetLanguageCode()].translation == "undefined" || this.getResponse().contents[this.getTargetLanguageCode()].translation == null){
			this.getResponse().contents[this.getTargetLanguageCode()].translation = {};
		}
		this.getResponse().contents[this.getTargetLanguageCode()].translation.contents = targetText;
		return this;
	},
	getParameters : function() {
		return this.getTransport().request.body.toQueryParams();
	},
	getSourceLanguageCode : function() {
		return this.getParameters().sourceLanguageCode;
	},
	setSourceLanguageCode : function(sourceLanguageCode) {
		this.getParameters().sourceLanguageCode = sourceLanguageCode;
		return this;
	},
	getTargetLanguageCode : function() {
		return this.getParameters().targetLanguageCode;
	},
	setTargetLanguageCode : function(targetLanguageCode) {
		this.getParameters().targetLanguageCode = targetLanguageCode;
		return this;
	},
	isError : function() {
		if(this.getResponse().contents.targetText.status){
			return this.getResponse().contents.targetText.status.toUpperCase() == 'ERROR';
		}else{
			return false;
		}
	},
	isWarning : function() {
		return this.getResponse().contents.targetText.status.toUpperCase() == 'WARNING';
	},
	isSourceWarning : function() {
		if(this.getResponse().contents[this.getTargetLanguageCode()][0]){
			return this.getResponse().contents[this.getTargetLanguageCode()][0].status.toUpperCase() == 'WARNING'
		}else{
			return false;
		}
	},
	getMessage : function() {
		return this.getResponse().contents.targetText.message;
	},

	/**
	 * getter/setter
	 */
	getResponse : function() {
		return this._response;
	},
	setResponse : function(response) {
		this._response = response;
		return this;
	},
	getTransport : function() {
		return this._transport;
	},
	setTransport : function(transport) {
		this._transport = transport;
		return this;
	}
};