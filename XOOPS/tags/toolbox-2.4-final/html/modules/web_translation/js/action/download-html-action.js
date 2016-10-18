//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

var DownloadHtmlAction = Class.create(AbstractAction, {
	
	/**
	 * 
	 */
	initialize: function() {
		
	},
	
	execute: function() {
		Logger.info('DownloadHtmlAction.execute');
		
		var form = this.createForm();
		document.body.appendChild(form);
		form.submit();
	},
	
	createForm: function() {
		var form = document.createElement('form');
		form.setAttribute('action', Resource.Url.DOWNLOAD_HTML);
		form.setAttribute('method', 'post');
		form.setAttribute('style', 'display: none;');
		
		var inputContents = document.createElement('input');
		inputContents.setAttribute('type', 'hidden');
		inputContents.setAttribute('name', 'contents');
		inputContents.setAttribute('value', this.getContents());
		
		form.appendChild(inputContents);

		var inputFileName = document.createElement('input');
		inputFileName.setAttribute('type', 'hidden');
		inputFileName.setAttribute('name', 'fileName');
		inputFileName.setAttribute('value', this.getFileName());
		
		form.appendChild(inputFileName);
		
		return form;
	},
	
	/**
	 * @return String File Name
	 */
	getFileName: function() {
		return 'web_creation.html';
	},
	
	/**
	 * @return String Contents
	 */
	getContents: function() {}
});

var SourceDownloadHtmlAction = Class.create(DownloadHtmlAction, {
	
	/**
	 * @override
	 */
	getContents: function() {
		return Model.Translation.getSourceAsString();
	}
});

var TargetDownloadHtmlAction = Class.create(DownloadHtmlAction, {
	
	/**
	 * @override
	 */
	getContents: function() {
		return Model.Translation.getTargetAsString();
	}
	
});