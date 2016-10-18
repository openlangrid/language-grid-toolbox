<?php
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

require_once(dirname(__FILE__).'/../../ITextTranslationClient.interface.php');
require_once(dirname(__FILE__).'/Toolbox_AbstractClient.class.php');
require_once(dirname(__FILE__).'/../../class/manager/Toolbox_TextTranslationManager.class.php');

class TextTranslationClient extends Toolbox_AbstractClient implements ITextTranslationClient {

	public function __construct() {
		parent::__construct();
	}

	public function translate($sourceLang, $targetLang, $source, $translationBindingSetId) {
		$manager =& new Toolbox_TextTranslationManager();
		return $manager->translate($sourceLang, $targetLang, $source, $translationBindingSetId);
	}

	public function backTranslate($sourceLang, $targetLang, $source, $translationBindingSetId) {
		$manager =& new Toolbox_TextTranslationManager();
		return $manager->backTranslate($sourceLang, $targetLang, $source, $translationBindingSetId);
	}
}
?>
