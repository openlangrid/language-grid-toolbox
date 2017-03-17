<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
require_once(dirname(__FILE__).'/../AbstractDao.class.php');

class TranslationOptionObject extends AbstractDaoObject {

	function TranslationOptionObject() {
		$this->initVar('option_id');
		$this->initVar('set_id');
		$this->initVar('user_id');
		$this->initVar('lite_flag');
		$this->initVar('rich_flag');
		$this->initVar('create_user_id');
		$this->initVar('update_user_id');
		$this->initVar('create_time');
		$this->initVar('update_time');
	}
	
}


class TranslationOptionDaoImpl extends AbstractDao implements ServiceGridTranslationOptionDAO {

	var $mTable = 'translation_option';
	var $mPrimary = "option_id";
	var $mClass = "TranslationOptionObject";

	/**
	 * Construct
	 */
	function __construct($db) {
		parent::__construct($db);
	}

	public function queryBySetId($setId) {
		$wheres = array('set_id' => $setId);
		
		$objects = parent::search($wheres);

		return $this->objects2objects($objects);
	}

	public function insert($translationOptionObj) {
		$data = (array)$translationOptionObj->getVars();

		$data['create_time'] = time();
		$data['create_user_id'] = $translationOptionObj->get('user_id');

		$optionId = parent::insert($data, true);

		return $optionId;
	}

	public function update($translationOptionObj) {
		$data = (array)$this->convertTranslationOption($translationOptionObj)->getVars();
		return parent::update($data);
	}

	/**
	 *
	 */
	private function objects2objects($objects) {
		if ($objects == null || is_array($objects) === false) {
			return array();
		}
		$contents = array();
		foreach ($objects as $object) {
			$contents[] = $this->convertOptionObject($object);
		}
		return $contents;
	}

	private function convertTranslationOption($optionObject) {
		$translationOptionObject = new TranslationOptionObject();

		$translationOptionObject->set('option_id', $optionObject->getOptionId());
		$translationOptionObject->set('set_id', $optionObject->getSetId());
		$translationOptionObject->set('lite_flag', $optionObject->getLiteFlag());
		$translationOptionObject->set('rich_flag', $optionObject->getRichFlag());
		$translationOptionObject->set('create_user_id', $optionObject->getCreateUserId());
		$translationOptionObject->set('update_user_id', $optionObject->getUpdateUserId());
		$translationOptionObject->set('create_time', $optionObject->getCreateTime());
		$translationOptionObject->set('update_time', $optionObject->getUpdateTime());

		return $translationOptionObject;
	}
	
	/**
	 * オブジェクトからVOに変換する。
	 * @param unknown_type $translationSetObj
	 * @return ServiceGridTranslationSet
	 */
	private function convertOptionObject($translationOptionObj) {
		$translationOption = new ServiceGridTranslationOption();

		$translationOption->setOptionId($translationOptionObj->get('option_id'));
		$translationOption->setSetId($translationOptionObj->get('set_id'));
		$translationOption->setUserId($translationOptionObj->get('user_id'));
		$translationOption->setLiteFlag($translationOptionObj->get('lite_flag'));
		$translationOption->setRichFlag($translationOptionObj->get('rich_flag'));
		$translationOption->setCreateUserId($translationOptionObj->get('create_user_id'));
		$translationOption->setUpdateUserId($translationOptionObj->get('update_user_id'));
		$translationOption->setCreateTime($translationOptionObj->get('create_time'));
		$translationOption->setUpdateTime($translationOptionObj->get('update_time'));
		return $translationOption;
	}
}
?>