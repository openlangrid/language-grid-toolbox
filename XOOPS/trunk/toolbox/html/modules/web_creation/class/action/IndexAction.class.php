<?php
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

require_once APP_ROOT_PATH.'/class/action/AbstractAction.class.php';
require_once APP_ROOT_PATH.'/class/toolbox/TranslatorSettingAdapter.class.php';

class IndexAction extends AbstractAction {

	private $languages;

	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 *
	 */
	public function execute() {
		parent::execute();

		$settingAdapter = new TranslatorSettingAdapter();
		$languages = $settingAdapter->getLanguages();
		$this->languagePairs = json_encode($languages);

		$this->sourceLanguage = 'en';
		$this->targetLanguage = $languages['en'][0];
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	public function executeView($render) {
		$render->setTemplateName('web_creation_main.html');

		$render->setAttribute('resource', json_encode($this->getResource()));
		$render->setAttribute('languagePairs', $this->languagePairs);
		$render->setAttribute('sourceLanguage', $this->sourceLanguage);
		$render->setAttribute('targetLanguage', $this->targetLanguage);

		$this->load($render);
	}

	/**
	 *
	 */
	private function getResource() {
		$constants = get_defined_constants(true);

		$resource = array();

		$prefix = '_MI_WEB_CREATION_';

		$exp = array('NAME');

		foreach ($constants['user'] as $key => $value) {
			if (strpos($key, $prefix) === 0) {
				$key = '@@'.$key;
				$key = str_replace('@@'.$prefix, '', $key);

				if (!in_array($key, $exp)) {
					$resource[$key] = $value;
				}
			}
		}

		$resource['Image'] = array(
			'NOW_LOADING' => '<img src="./image/ajax.gif" width="24" height="24" />',
			'TRASH' => '<img src="./image/icon/trash.gif" width="15" height="20" />'
		);

		$resource['Template'] = array(
			'LICENSE' => $this->loadTemplate('license.html'),
			'LOAD_HTML_BY_FILE_DIALOG' => $this->loadTemplate('load-html-dialog.html'),
			'LOAD_HTML_DIALOG' => $this->loadTemplate('load-html-dialog.html'),
			'LOAD_APPLY_TEMPLATE_DIALOG' => $this->loadTemplate('load-apply-template-dialog.html'),
			'LOAD_TEMPLATE_DIALOG' => $this->loadTemplate('load-template-dialog.html'),
			'LOAD_WORKSPACE_DIALOG' => $this->loadTemplate('load-workspace-dialog.html'),
			'SAVE_HTML_DIALOG' => $this->loadTemplate('save-html-dialog.html'),
			'SAVE_TEMPLATE_DIALOG' => $this->loadTemplate('save-template-dialog.html'),
			'SAVE_WORKSPACE_DIALOG' => $this->loadTemplate('save-workspace-dialog.html')
		);

		$resource['Url'] = array(
			'DOWNLOAD_HTML' => './?ml_lang=raw&action=downloadHtml',
			'LOAD_HTML_BY_FILE' => './?ml_lang=raw&action=loadHtmlByFile',
			'LOAD_HTML_BY_URL' => './?ml_lang=raw&action=loadHtmlByUrl' . '&ml_lang=raw',
			'LOAD_TEMPLATE' => './?ml_lang=raw&action=loadTemplate',
			'LOAD_WORKSPACE' => './?ml_lang=raw&action=loadWorkspace',
			'READ_HTML_BY_FILE' => './?ml_lang=raw&action=readHtmlByFile',
			'READ_TEMPLATE' => './?ml_lang=raw&action=readTemplate',
			'READ_WORKSPACE' => './?ml_lang=raw&action=readWorkspace',
			'SAVE_CACHE' => './?ml_lang=raw&action=saveCache',
			'SAVE_HTML' => './?ml_lang=raw&action=saveHtml',
			'SAVE_TEMPLATE' => './?ml_lang=raw&action=saveTemplate',
			'SAVE_WORKSPACE' => './?ml_lang=raw&action=saveWorkspace',
			'TRANSLATION' => './?ml_lang=raw&action=translation' . '&ml_lang=raw',
		);

		require XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php';
		$resource['Language'] = $LANGRID_LANGUAGE_ARRAY;

		return $resource;
	}

	private function loadTemplate($template) {
		$contents = file_get_contents(APP_ROOT_PATH.'/js/template/'.$template);

		preg_match_all('/<{\$smarty.const.([^}]+)}>/', $contents, $matches);

		foreach ($matches[0] as $i => $match) {
			$replace = $matches[1][$i];
			if (defined($replace)) {
				$replace = constant($replace);
			}
			$contents = str_replace($match, $replace, $contents);
		}

		return $contents;
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	private function load($render) {
		$this->loadJs($render);
		$this->loadJsDialog($render);
		$this->loadCss($render);
		$this->loadHowToUse($render);
		$this->loadUserCss($render);
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	private function loadJs($render) {
		require_once dirname(__FILE__).'/../../include/js.php';

		$suffix = $this->getSuffix();

		$header = $render->getAttribute('xoops_module_header');
		foreach ($js as $j) {
			$header .= '<script src="'.$j.$suffix.'"></script>'."\n";
		}

		$render->setAttribute('xoops_module_header', $header);
	}

	/**
	 * <#if locale="ja">
	 * ファイル共有ダイアログJSをロード
	 * <#/if>
	 * @param unknown_type $render
	 */
	private function loadJsDialog($render) {
		require_once XOOPS_ROOT_PATH.'/modules/filesharing/dialog/include_js.php';

		$suffix = $this->getSuffix();

		$header = <<< EOF
<script><!--
jQuery.noConflict();
//--></script>
EOF;
		$header .= $render->getAttribute('xoops_module_header');
		foreach ($dialogjavascripts as $j) {
			$header .= '<script src="'.XOOPS_URL.'/modules/filesharing/dialog'.$j.$suffix.'"></script>'."\n";
		}
		$header .= '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/filesharing/dialog/css/filesharingdialog.css" />'."\n";
		$header .= '<link rel="stylesheet" type="text/css" media="screen" href="'.XOOPS_URL.'/modules/filesharing/dialog/css/glayer.css" />'."\n";

		$render->setAttribute('xoops_module_header', $header);
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	private function loadUserCss($render) {
		$header = '<link rel="stylesheet" type="text/css" media="screen" href="./css/user_style.css"></link>';
		$render->setAttribute('user_define_header', $header);
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	protected function loadCss($render) {
		require_once dirname(__FILE__).'/../../include/css.php';

		$suffix = $this->getSuffix();

		$header = $render->getAttribute('xoops_module_header');
		foreach($css as $c) {
			$header .= '<link rel="stylesheet" type="text/css" media="screen" href="./css'.$c.$suffix.'"></link>';
		}

		$render->setAttribute('xoops_module_header', $header);
	}

	private function getSuffix() {
		return (APP_DEBUG_MODE) ? '?'.time() : '';
	}

	/**
	 *
	 * @param unknown_type $render
	 */
	private function loadHowToUse($render) {
		$render->setAttribute('howToUse', 'how-to-use/'._MI_WEB_CREATION_HOW_TO_USE);
		$render->setAttribute('customized', 'ieice');
//		$render->setAttribute('showSettingLink', 'user');
	}
}
?>
