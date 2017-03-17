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

require_once(dirname(__FILE__).'/Toolbox_BBS_AbstractManager.class.php');

class Toolbox_BBS_TagManager extends Toolbox_BBS_AbstractManager {

	public function __construct($modname) {
		parent::__construct($modname);
	}

	public function getTagSetList($sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$mCriteria =& new CriteriaCompo();
		$objects =& $this->m_tagSetsHandler->getObjects($mCriteria, $limit, $offset);

		$tagSets = array();
		foreach ($objects as &$object) {
			$tagSets[] = $this->tagSetObject2responseVO($object);
		}

		return $this->getResponsePayload($tagSets);
	}

	public function getTagSet($tagSetId) {
		$object = $this->m_tagSetsHandler->get($tagSetId);
		if ($object) {
			return $this->getResponsePayload($this->tagSetObject2responseVO($object));
		}
		return $this->getErrorResponsePayload('not found');
	}

	public function addTagSet($expressions) {
		$tagSetObj = $this->m_tagSetsHandler->create(true);
		if (!$this->m_tagSetsHandler->insert($tagSetObj, true)) {
			return $this->getErrorResponsePayload('error in insert TagSet.');
		}
		foreach ($expressions as $exp) {
			$expObj = $this->m_tagSetExpressionsHandler->create(true);
			$expObj->set('tag_set_id', $tagSetObj->get('tag_set_id'));
			$expObj->set('language_code', $exp->language);
			$expObj->set('expression', $exp->expression);
			if (!$this->m_tagSetExpressionsHandler->insert($expObj, true)) {
				return $this->getErrorResponsePayload('error in insert TagSetExpression.');
			}
		}

		$tagSet = $this->tagSetObject2responseVO($tagSetObj);
		return $this->getResponsePayload($tagSet);
	}

	public function updateTagSet($tagSetId, $expressions) {
		$tagSetObj = $this->m_tagSetsHandler->get($tagSetId);
		if (!$tagSetObj) {
			return $this->getErrorResponsePayload('not found update target.');
		}
		if ($tagSetObj->mExpressions) {
			foreach ($tagSetObj->mExpressions as $setExpObj) {
				if (!$this->m_tagSetExpressionsHandler->delete($setExpObj, true)) {
					return $this->getErrorResponsePayload('Error in update-delette TagSetExpression.');
				}
			}
		}
		foreach ($expressions as $exp) {
			$expObj = $this->m_tagSetExpressionsHandler->create(true);
			$expObj->set('tag_set_id', $tagSetObj->get('tag_set_id'));
			$expObj->set('language_code', $exp->language);
			$expObj->set('expression', $exp->expression);
			if (!$this->m_tagSetExpressionsHandler->insert($expObj, true)) {
				return $this->getErrorResponsePayload('error in update-insert TagSetExpression.');
			}
		}

		$tagSetObj = $this->m_tagSetsHandler->get($tagSetId);
		$tagSet = $this->tagSetObject2responseVO($tagSetObj);
		return $this->getResponsePayload($tagSet);
	}

	public function deleteTagSet($tagSetId) {
		$tagSetObj = $this->m_tagSetsHandler->get($tagSetId);
		if ($tagSetObj) {
			if ($tagSetObj->mExpressions) {
				foreach ($tagSetObj->mExpressions as $setExpObj) {
					if (!$this->m_tagSetExpressionsHandler->delete($setExpObj, true)) {
						return $this->getErrorResponsePayload('Error in delette TagSetExpression.');
					}
				}
			}
			if ($tagSetObj->mTags) {
				foreach ($tagSetObj->mTags as $tagObj) {
					if ($tagObj->mExpressions) {
						foreach ($tagObj->mExpressions as $tagExpObj) {
							if (!$this->m_tagExpressionsHandler->delete($tagExpObj, true)) {
								return $this->getErrorResponsePayload('Error in delete TagExpression.');
							}
						}
					}
					if (!$this->m_tagsHandler->delete($tagObj, true)) {
						return $this->getErrorResponsePayload('Error in delete Tag.');
					}
				}
			}
			if (!$this->m_tagSetsHandler->delete($tagSetObj, true)) {
				return $this->getErrorResponsePayload('Error in delete TagSet.');
			}
		}
	}

	public function getTagList($tagSetId, $sortOrder = null, $orderBy = null, $offset = null, $limit = null) {
		$mCriteria = new CriteriaCompo();
		$mCriteria->add(new Criteria('tag_set_id', $tagSetId));
		$objects = $this->m_tagsHandler->getObjects($mCriteria);

		$tags = array();
		foreach ($objects as $object) {
			$tags[] = $this->tagObject2responseVO($object);
		}
		return $this->getResponsePayload($tags);
	}

	public function getTag($tagSetId, $tagId) {
		$object = $this->m_tagsHandler->get($tagSetId, $tagId);
		if ($object) {
			return $this->getResponsePayload($this->tagObject2responseVO($object));
		}
		return $this->getErrorResponsePayload('not found');
	}

	public function addTag($tagSetId, $expressions) {
		$tagSetObj = $this->m_tagSetsHandler->get($tagSetId);
		if ($tagSetObj) {
			$tagObj = $this->m_tagsHandler->create(true);
			$tagObj->set('tag_set_id', $tagSetObj->get('tag_set_id'));
			if (!$this->m_tagsHandler->insert($tagObj, true)) {
				return $this->getErrorResponsePayload('Error in insert Tag.');
			}
			foreach ($expressions as $exp) {
				$tagExpObj = $this->m_tagExpressionsHandler->create(true);
				$tagExpObj->set('tag_id', $tagObj->get('tag_id'));
				$tagExpObj->set('language_code', $exp->language);
				$tagExpObj->set('expression', $exp->expression);
				if (!$this->m_tagExpressionsHandler->insert($tagExpObj, true)) {
					return $this->getErrorResponsePayload('Error in insert TagExpression.');
				}
			}
			$tag = $this->tagObject2responseVO($tagObj);
			return $this->getResponsePayload($tag);
		} else {
			return $this->getErrorResponsePayload('not found tagset.');
		}
	}

	public function updateTag($tagSetId, $tagId, $expressions) {
		$tagObj = $this->m_tagsHandler->get($tagSetId, $tagId);
		if (!$tagObj) {
			return $this->getErrorResponsePayload('not found update target.');
		}
		if ($tagObj->mExpressions) {
			foreach ($tagObj->mExpressions as $expObj) {
				if (!$this->m_tagExpressionsHandler->delete($expObj, true)) {
					return $this->getErrorResponsePayload('Error in update-delette TagExpression.');
				}
			}
		}
		foreach ($expressions as $exp) {
				$tagExpObj = $this->m_tagExpressionsHandler->create(true);
				$tagExpObj->set('tag_id', $tagObj->get('tag_id'));
				$tagExpObj->set('language_code', $exp->language);
				$tagExpObj->set('expression', $exp->expression);
				if (!$this->m_tagExpressionsHandler->insert($tagExpObj, true)) {
					return $this->getErrorResponsePayload('Error in update-insert TagExpression.');
				}
		}

		$tagObj = $this->m_tagsHandler->get($tagSetId, $tagId);
		$tag = $this->tagObject2responseVO($tagObj);
		return $this->getResponsePayload($tag);
	}

	public function deleteTag($tagSetId, $tagId) {
		$tagObj = $this->m_tagsHandler->get($tagSetId, $tagId);
		if ($tagObj) {
			if ($tagObj->mExpressions) {
				foreach ($tagObj->mExpressions as $expObj) {
					if (!$this->m_tagExpressionsHandler->delete($expObj, true)) {
						return $this->getErrorResponsePayload('Error in delette TagExpression.');
					}
				}
			}
			if (!$this->m_tagsHandler->delete($tagObj, true)) {
				return $this->getErrorResponsePayload('Error in delete Tag.');
			}
		}
		return true;
	}

	public function deleteTags($tagSetId) {
		$tagSetObj = $this->m_tagSetsHandler->get($tagSetId);
		if ($tagSetObj) {
			if ($tagSetObj->mTags) {
				foreach ($tagSetObj->mTags as $tagObj) {
					if ($tagObj->mExpressions) {
						foreach ($tagObj->mExpressions as $tagExpObj) {
							if (!$this->m_tagExpressionsHandler->delete($tagExpObj, true)) {
								return $this->getErrorResponsePayload('Error in delete TagExpression.');
							}
						}
					}
					if (!$this->m_tagsHandler->delete($tagObj, true)) {
						return $this->getErrorResponsePayload('Error in delete Tag.');
					}
				}
			}
		}
		return true;
	}

	public function truncate() {
		$tables = array(
			'_tag_expressions',
			'_tags',
			'_tag_set_expressions',
			'_tag_sets',
			'_tag_relations'
		);
		foreach ($tables as $table) {
			$sql = 'TRUNCATE TABLE '.$this->db->prefix($this->m_modName.$table);
			$this->db->queryF($sql);
		}
		return true;
	}

	public function bindTag($postId, $tagIds) {

		foreach ($tagIds as $tagSetId => $tagId) {
			$tagObj = $this->m_tagsHandler->get($tagSetId, $tagId);
			if ($tagObj && $tagId) {
				$tagRelationObj = $this->m_tagRelationsHandler->create(true);
				$tagRelationObj->set('tag_set_id', $tagObj->get('tag_set_id'));
				$tagRelationObj->set('tag_id', $tagObj->get('tag_id'));
				$tagRelationObj->set('post_id', $postId);
				$this->m_tagRelationsHandler->insert($tagRelationObj, true);
			}
		}
		return true;
	}

	public function deleteBindTag($postId) {
		return $this->m_tagRelationsHandler->deleteByPostId($postId);
	}

}
?>