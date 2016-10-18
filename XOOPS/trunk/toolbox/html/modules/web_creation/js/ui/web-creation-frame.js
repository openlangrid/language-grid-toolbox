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

var WebCreationFrame = Class.create({

	/**
	 * Constructor
	 */
	initialize: function() {
		this.createButtons();
		this.createLanguageSelectors();
		this.createObservers();
	},

	/**
	 * Create All Buttons
	 */
	createButtons: function() {
		var factory = UiFactory.getInstance();

		this.loadHtmlByFileButton = factory.createButton('wc-load-html-by-file-button', new LoadHtmlByFileAction());
		this.loadWorkspaceButton = factory.createButton('wc-load-workspace-button', new LoadWorkspaceAction());
		this.saveWorkspaceButton = factory.createButton('wc-save-workspace-button', new SaveWorkspaceAction());

		this.loadHtmlByUrlButton = factory.createButton('wc-load-html-by-url-button', new LoadHtmlByUrlAction());

		this.displayButton = factory.createButton('wc-display-button', new DisplayAction('wc-url-input'));
		this.translationButton = factory.createButton('wc-translation-button', new TranslationAction());

		this.loadApplyTemplateButton = factory.createButton('wc-load-apply-template-button', new LoadApplyTemplateAction());

		this.createToggleButtons();
		this.createExpandButtons();
		this.createContractButtons();

		this.createSourceControlButtons();
		this.createTargetControlButtons();

		this.createTemplateControlButtons();
	},

	/**
	 * Create Toggle Buttons
	 */
	createToggleButtons: function() {
		var factory = UiFactory.getInstance();

		this.translationToggleButton = factory.createToggleButton('wc-translation-area-toggle-button', new ToggleAction('wc-translation-area-wrapper'));
		this.templateToggleButton = factory.createToggleButton('wc-template-toggle-button', new ToggleAction('wc-template-area'));
	},

	/**
	 * Create Expand Buttons
	 */
	createExpandButtons: function() {
		var factory = UiFactory.getInstance();

		var expandAction = new ExpandAction();
		this.sourceExpandButton = factory.createExpandButton('wc-source-expand-button', expandAction);
		this.targetExpandButton = factory.createExpandButton('wc-target-expand-button', expandAction);
	},

	/**
	 * Create Contract Buttons
	 */
	createContractButtons: function() {
		var factory = UiFactory.getInstance();

		var contractAction = new ContractAction();
		this.sourceContractionButton = factory.createContractButton('wc-source-contract-button', contractAction);
		this.targetContractionButton = factory.createContractButton('wc-target-contract-button', contractAction);
	},

	/**
	 * Create Source Control Buttons
	 */
	createSourceControlButtons: function() {
		var factory = UiFactory.getInstance();

		this.sourceDisplayButton = factory.createButton('wc-source-display-button', new SourceDisplayAction());
		this.sourceSaveHtmlButton = factory.createButton('wc-source-save-html-button', new SaveSourceHtmlAction());
		this.sourceDownloadHtmlButton = factory.createButton('wc-source-download-html-button', new SourceDownloadHtmlAction());
	},

	/**
	 * Create Target Control Buttons
	 */
	createTargetControlButtons: function() {
		var factory = UiFactory.getInstance();

		this.targetDisplayButton = factory.createButton('wc-target-display-button', new TargetDisplayAction());
		this.targetSaveHtmlButton = factory.createButton('wc-target-save-html-button', new SaveTargetHtmlAction());
		this.targetDownloadHtmlButton = factory.createButton('wc-target-download-html-button', new TargetDownloadHtmlAction());

		this.targetSelectAllButton = factory.createToggleButton('wc-target-all-fix-button', new AllFixedAction());
		this.targetShowTagButton = factory.createToggleButton('wc-target-show-tag-button', new ShowTagLineAction());
	},

	/**
	 * Create Template Control Buttons
	 */
	createTemplateControlButtons: function() {
		var factory = UiFactory.getInstance();

		this.templateToggleButton = factory.createButton('wc-template-toggle-button', new ToggleTemplateAction());
		this.templateLoadButton = factory.createButton('wc-template-load-button', new LoadTemplateAction());
		this.templateSaveButton = factory.createButton('wc-template-save-button', new SaveTemplateAction());
		this.templateAddButton = factory.createButton('wc-template-add-button', new AddTemplateAction());},

	/**
	 * Create Source & Target Language Selectors
	 */
	createLanguageSelectors: function() {
		var sourceLanguageSelector = new HtmlSelector('wc-source-language-selector', new ChangeSourceLanguageAction());
		sourceLanguageSelector.setContents(LanguageUtil.toObject(Model.Language.getSourceLanguages()));
		sourceLanguageSelector.setValue(Model.Language.getSourceLanguage());

		var targetLanguageSelector = new HtmlSelector('wc-target-language-selector', new ChangeTargetLanguageAction());
		this.languageSelectors = new LanguageSelectors(sourceLanguageSelector, targetLanguageSelector);

		Model.Language.addObserver(this.languageSelectors);

		Model.Language.setChanged();
		Model.Language.notifyObservers();
	},

	createObservers: function() {
		Model.License.addObserver(new LicensePanel());
		Model.Translation.addObserver(new TranslationPanel());
		Model.ApplyTemplate.addObserver(new ApplyTemplatePanel());
		Model.Template.addObserver(new TemplatePanel());
	}
});