//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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
Event.observe(window, 'load', function(){
	
	var mainFramePopupPanelLanugagePanelId = 'langrid-imported-services-light-popup-language-area';
	var mainFramePopupPanelStatusMessageId = 'langrid-imported-services-light-status-message-area';
	var mainFramePopupPanelErrorMessageId = 'langrid-imported-services-light-error-message-area';
	var mainFrameOnAddServiceFireEventName = 'add:service';
	var mainFrameOnEditServiceFireEventName = 'edit:service';
	
	var mainFrame = new ImportedServicesPanel({
		
		id : 'langrid-imported-services-main',
		
		mode : 'admin',

		addServiceButton : $('langrid-imported-services-add-service-button'),
		editServiceButton : $('langrid-imported-services-edit-service-button'),
		removeServiceButton : $('langrid-imported-services-remove-service-button'),
		
		onAddServiceFireEventName : mainFrameOnAddServiceFireEventName,
		onEditServiceFireEventName : mainFrameOnEditServiceFireEventName,
		
		statusArea : $('langrid-imported-services-status-message-area'),
		
		addServicePopupPanel : new AddServicePopupPanel({
			id : 'langrid-imported-services-light-popup-panel-wrapper',
			panelId : 'langrid-imported-services-light-popup-panel',
			maskId : 'langrid-imported-services-light-popup-mask',
			
			languageSelectIdPrefix : 'langrid-imported-services-light-popup-language-select-',
			serviceTypeSelectId : 'langrid-imported-services-light-popup-service-type-select',

			formId : 'langrid-imported-services-light-popup-form',
			tableId : 'langrid-imported-services-light-popup-table',
			serviceNameId : 'langrid-imported-services-light-popup-service-name-input-area',
			
			languagePanelId : mainFramePopupPanelLanugagePanelId,

			statusMessageId : mainFramePopupPanelStatusMessageId,
			errorMessageId : mainFramePopupPanelErrorMessageId,
			
			onAddServiceFireEventName : mainFrameOnAddServiceFireEventName,
			
			languagePathsPanel : new LanguagePathsPanel({
				id : mainFramePopupPanelLanugagePanelId,
				addButtonId : 'langrid-imported-services-light-popup-add-language-button',
				removeButtonId : 'langrid-imported-services-light-popup-remove-language-button',

				fromLanguagesPrefixId : 'langrid-imported-services-light-popup-language-path-from-',
				languagesLinkedPrefixId : 'langrid-imported-services-light-popup-language-path-link-',
				toLanguagesPrefixId : 'langrid-imported-services-light-popup-language-path-to-',

				errorMessageId : mainFramePopupPanelErrorMessageId
			}),
			
			languageSelectorsPanel : new LanguageSelectorsPanel({
				id : mainFramePopupPanelLanugagePanelId,
				addButtonId : 'langrid-imported-services-light-popup-add-language-button',
				removeButtonId : 'langrid-imported-services-light-popup-remove-language-button',

				languagePrefixId : 'langrid-imported-services-light-popup-language-',
				errorMessageId : mainFramePopupPanelErrorMessageId
			}),

			endpointUrlId : 'langrid-imported-services-light-popup-endpoint-url-input-area',
			providerId : 'langrid-imported-services-light-popup-provider-input-area',
			copyrightId : 'langrid-imported-services-light-popup-copyright-input-area',
			licenseId : 'langrid-imported-services-light-popup-license-input-area',
			
			cancelButtonId : 'langrid-imported-services-light-popup-cancel-button',
			submitButtonId : 'langrid-imported-services-light-popup-submit-button',

			opacity : 4
		}),
		editServicePopupPanel :	new EditServicePopupPanel({
			id : 'langrid-imported-services-light-popup-panel-wrapper',
			panelId : 'langrid-imported-services-light-popup-panel',
			maskId : 'langrid-imported-services-light-popup-mask',
			
			languageSelectIdPrefix : 'langrid-imported-services-light-popup-language-select-',
			serviceTypeSelectId : 'langrid-imported-services-light-popup-service-type-select',

			formId : 'langrid-imported-services-light-popup-form',
			tableId : 'langrid-imported-services-light-popup-table',
			serviceNameId : 'langrid-imported-services-light-popup-service-name-input-area',
			
			languagePanelId : 'langrid-imported-services-light-popup-language-area',

			statusMessageId : mainFramePopupPanelStatusMessageId,
			errorMessageId : 'langrid-imported-services-light-error-message-area',
			
			onEditServiceFireEventName : mainFrameOnEditServiceFireEventName,
			
			languagePathsPanel : new LanguagePathsPanel({
				id : 'langrid-imported-services-light-popup-language-area',
				addButtonId : 'langrid-imported-services-light-popup-add-language-button',
				removeButtonId : 'langrid-imported-services-light-popup-remove-language-button',

				fromLanguagesPrefixId : 'langrid-imported-services-light-popup-language-path-from-',
				languagesLinkedPrefixId : 'langrid-imported-services-light-popup-language-path-link-',
				toLanguagesPrefixId : 'langrid-imported-services-light-popup-language-path-to-',

				languagePrefixId : 'langrid-imported-services-light-popup-language-selectors-',
				errorMessageId : 'langrid-imported-services-light-error-message-area'
			}),
			
			languageSelectorsPanel : new LanguageSelectorsPanel({
				id : 'langrid-imported-services-light-popup-language-area',
				addButtonId : 'langrid-imported-services-light-popup-add-language-button',
				removeButtonId : 'langrid-imported-services-light-popup-remove-language-button',

				languagePrefixId : 'langrid-imported-services-light-popup-language-',
				errorMessageId : 'langrid-imported-services-light-error-message-area'
			}),

			endpointUrlId : 'langrid-imported-services-light-popup-endpoint-url-input-area',
			providerId : 'langrid-imported-services-light-popup-provider-input-area',
			copyrightId : 'langrid-imported-services-light-popup-copyright-input-area',
			licenseId : 'langrid-imported-services-light-popup-license-input-area',
			
			cancelButtonId : 'langrid-imported-services-light-popup-cancel-button',
			submitButtonId : 'langrid-imported-services-light-popup-submit-button',

			opacity : 4
		}),
		
		tablePanel : new ImportedServicesTablePanel({
			id : 'langrid-imported-services-table-wrapper',
			rowIdPrefix : 'imported-services-table-row-id-',
			tableId : 'imported-services-table',
			rowSelectedClassName : 'imported-services-row-selected'
		})
	});
});