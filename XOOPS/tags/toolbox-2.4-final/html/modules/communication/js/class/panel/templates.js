//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
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
var Templates = {
	ComboBox : {
		header : '<select id="#{id}">',
		body : '<option value="#{value}" #{selected}>#{name}</option>',
		footer : '</select>'
	},
	span : '<span class="#{className}" id="#{id}">#{value}</span>',
	button : '<button id="#{id}" class="langrid-common-button">#{value}</button>',
	ImportedServices : {
		Table : {
			header : '<table id="#{tableId}">'
					+ '<thead><tr>'
					+ '<th class="service-name">#{serviceName}</th><th class="service-type">#{serviceType}</th>'
					+ '<th>#{languages}</th><th class="service-url">#{endpointUrl}</th>'
					+ '<th class="service-provider">#{provider}</th><th class="service-copyright">#{copyright}</th>'
					+ '<th class="service-registration">#{registrationDate}</th>'
					+ '</tr></thead>',
			body : '<tr id="#{rowId}">'
					+ '<td>#{serviceName}</td><td>#{serviceType}</td>'
					+ '<td>#{languages}</td><td>#{endpointUrl}</td>'
					+ '<td>#{provider}</td><td>#{copyright}</td>'
					+ '<td>#{registrationDate}</td>' + '</tr>',
			footer : '</table>'
		},
		PopupPanel : {
			base : '<div id="#{id}"><div id="#{panelId}"></div>'+'<div id="#{maskId}"></div></div>',
			addService : {
				header : '<div class="langrid-popup-container"><h1>#{title}</h1><form id="#{formId}"><table>',
				body : '<tr><th><h2>#{serviceName}*</h2></th><td><input type="text" value="" id="#{serviceNameId}" /></td></tr>'
						+ '<tr><th><h2>#{serviceType}</h2></th><td>#{serviceTypeComboBox}</td></tr>'
						+ '<tr><th><h2>#{language}*</h2></th><td id="#{languagePanelId}"></tr>'
						+ '<tr><th><h2>#{endpointUrl}*</h2></th><td><input id="#{endpointUrlId}" type="text" value="" /></td></tr>'
						+ '<tr><th><h2>#{provider}</h2></th><td><input id="#{providerId}" type="text" value="" /></td></tr>'
						+ '<tr><th><h2>#{copyright}</h2></th><td><input id="#{copyrightId}" type="text" value="" /></td></tr>'
						+ '<tr><th><h2>#{license}</h2></th><td><textarea id="#{licenseId}"></textarea></td></tr>',
				footer : '</table>'
						+ '<div class="langrid-popup-panel-attention">#{requiredField}</div>'
						+ '<div class="langrid-message-area"><div id="#{statusMessageId}"></div><div id="#{errorMessageId}"></div></div>'
						+ '<div class="langrid-imported-services-popup-button-area clearfix"><div>'
						+ '<button id="#{submitButtonId}" type="submit" class="langrid-common-button float-right">#{submit}</button>'
						+ '<button id="#{cancelButtonId}" class="langrid-common-button float-left">#{cancel}</button>'
						+ '</div></div>'
						+ '</form></div>'
			},
			editService : {
				header : '<div class="langrid-popup-container"><h1>#{title}</h1><form id="#{formId}"><table>',
				body : '<tr><th><h2>#{serviceName}</h2></th><td>#{serviceNameValue}</td></tr>'
						+ '<tr><th><h2>#{serviceType}</h2></th><td>#{serviceTypeValue}</td></tr>'
						+ '<tr><th><h2>#{language}</h2></th><td id="#{languagePanelId}"></td></tr>'
						+ '<tr><th><h2>#{endpointUrl}*</h2></th><td><input id="#{endpointUrlId}" type="text" value="#{endpointUrlValue}" /></td></tr>'
						+ '<tr><th><h2>#{provider}</h2></th><td><input id="#{providerId}" type="text" value="#{providerValue}" /></td></tr>'
						+ '<tr><th><h2>#{copyright}</h2></th><td><input id="#{copyrightId}" type="text" value="#{copyrightValue}" /></td></tr>'
						+ '<tr><th><h2>#{license}</h2></th><td><textarea id="#{licenseId}">#{licenseValue}</textarea></td></tr>',
				footer : '</table>'
						+ '<div class="langrid-popup-panel-attention">#{requiredField}</div>'
						+ '<div class="langrid-message-area"><div id="#{statusMessageId}"></div><div id="#{errorMessageId}"></div></div>'
						+ '<div class="langrid-imported-services-popup-button-area clearfix"><div>'
						+ '<button id="#{submitButtonId}" type="submit" class="langrid-common-button float-right">#{submit}</button>'
						+ '<button id="#{cancelButtonId}" class="langrid-common-button float-left">#{cancel}</button>'
						+ '</div></div>'
						+ '</form></div>'
			}
		}
	}
};