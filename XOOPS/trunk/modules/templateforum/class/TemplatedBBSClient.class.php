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
require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');

class TemplatedBBSClient extends BBSClient {
    public function createTopicTemplateType($topicId, $templateTypes) {
        $manager = new Toolbox_BBS_TopicsTemplateTypeCreateManager($this->m_modname);
        return $manager->create($topicId, $templateTypes);
    }
}

class ToolboxVO_BBS_TopicsTemplateTypeExpression {
	var $topicId;
	var $languageCode;
	var $templateType;
}

class Toolbox_BBS_TopicsTemplateTypeCreateManager extends Toolbox_BBS_AbstractManager {
	protected $m_topicsTemplateTypeHandler;

    public function __construct($modname) {
		parent::__construct($modname);
        $this->m_topicsTemplateTypeHandler
            = new BBS_TopicsTemplateTypeHandler($this->db, $modname);
	}

    function create($topicId, $expressions) {
        foreach ($expressions as $exp) {
            $obj =& $this->m_topicsTemplateTypeHandler->create(true);
            $obj->set('topic_id', $topicId);
            $obj->set('language_code', $exp->languageCode);
            $obj->set('template_type', $exp->templateType);

			if (!$this->m_topicsTemplateTypeHandler->insert($obj, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}

			$this->_setCreateLog($obj->get('topic_id'), $exp->languageCode, $exp->templateType);
        }
    }

	private function _setCreateLog($id, $language, $title) {
		$this->setLog($id, EnumBBSItemTypeCode::$topicTitle, $language, EnumProcessTypeCode::$new, $title);
	}
}

class BBS_TopicsTemplateTypeObject extends XoopsSimpleObject {
    function BBS_TopicsTemplateTypeObject() {
		$this->initVar('topic_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('language_code', XOBJ_DTYPE_STRING, '', true, 16);
		$this->initVar('template_type', XOBJ_DTYPE_STRING, '', true, 255);
    }
}

class BBS_TopicsTemplateTypeHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = "";
	var $mModName = "";
	var $mPrimary = "topic_id";
	var $mClass = "BBS_TopicsTemplateTypeObject";
	var $mPrimaryAry = array('topic_id', 'language_code');

	public function __construct(&$db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_topics_template_type");
		$this->mModName = $moduleName;
	}
}