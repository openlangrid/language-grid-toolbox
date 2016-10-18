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

class Toolbox_BBS_PostCreateEditManager extends Toolbox_BBS_AbstractManager {

	public function __construct($modname) {
		parent::__construct($modname);
	}

	/*
	 * Create post(message)
	 */
	function create($topicId, $language, $expressions, $attachments = null, $parentMessageId = null) {

		$object =& $this->m_postHandler->create(true);
		$object->set('topic_id', $topicId);
		$object->set('uid', $this->root->mContext->mXoopsUser->get('uid'));
		$object->set('poster_ip', $this->getIp());
		$object->set('post_original_language', $language);
		$time = time();
		$object->set('post_time', $time);
		if(is_numeric($parentMessageId)){
			$object->set('reply_post_id', $parentMessageId);
		}
		$object->set('update_date', $time);
		$object->set('post_order', $this->m_postHandler->getPostOrder($topicId));

		if (!$this->m_postHandler->insert($object, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		
		$w = new BBS_PostFileObject();
		if ($attachments != null && is_array($attachments)) {
			foreach ($attachments as $attachment) {
				$file =& $this->m_postFileHandler->create(true);
				$file->set('post_id', $object->get('post_id'));
				$file->set('file_data', file_get_contents($attachment->location, FILE_BINARY));
				$file->set('file_name', $attachment->name);
				$file->set('file_size', $attachment->size);
				if (!$this->m_postFileHandler->insert($file, true)) {
					throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
				}
			}
		}
		foreach ($expressions as $exp) {
			$body =& $this->m_postBodyHandler->create(true);
			$body->set('post_id', $object->get('post_id'));
			$body->set('language_code', $exp->language);
			$body->set('description', $exp->body);
			$body->set('update_time', $time);

			if (!$this->m_postBodyHandler->insert($body, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			$this->_setCreateLog($object->get('post_id'), $exp->language, $exp->body);
		}
		
		return $this->getResponsePayload($this->postObject2responseVO($object));
	}

	/*
	 * Edit post(message)
	 */
	function update($postId, $expressions) {
		$time = time();
		$this->postUpdate($postId, $time);
		foreach ($expressions as $exp) {
			$pkArray = array('post_id'=>$postId, 'language_code'=>$exp->language);
			$body =& $this->m_postBodyHandler->get($pkArray);
			$body->set('description', $exp->body);
			$body->set('update_time', $time);
			if (!$this->m_postBodyHandler->insert($body, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			$this->_setEditLog($postId, $exp->language, $exp->body);
		}

		$postObj =& $this->m_postHandler->get($postId);
		return $this->getResponsePayload($this->postObject2responseVo($postObj));
	}

	/*
	 * Modify post(message)
	 */
	function modify($postId, $expressions) {
		$time = time();
		$this->postUpdate($postId, $time);
		foreach ($expressions as $exp) {
			$pkArray = array('post_id'=>$postId, 'language_code'=>$exp->language);
			$body =& $this->m_postBodyHandler->get($pkArray);
			$body->set('description', $exp->body);
			$body->set('update_time', $time);
			if (!$this->m_postBodyHandler->insert($body, true)) {
				throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
			}
			$this->_setModifyLog($postId, $exp->language, $exp->body);
		}

		$postObj =& $this->m_postHandler->get($postId);
		return $this->getResponsePayload($this->postObject2responseVo($postObj));
	}

	/*
	 * Remove forum
	 */
	function remove($postId) {
		$this->postUpdate($postId);
		$object =& $this->m_postHandler->get($postId);
		if (!$this->m_postHandler->delete($object, true)) {
			throw new Exception('SQL Error['.__FILE__.'('.__LINE__.')]');
		}
		return $this->getResponsePayload(true);
		//return true;
	}

	function postUpdate($postId, $time = null) {
		if ($time == null) {
			$time = time();
		}
		$object =& $this->m_postHandler->get($postId);
		$object->set('update_date', $time);
		$this->m_postHandler->insert($object, true);
	}

	private function _setCreateLog($id, $language, $body) {
		$this->setLog($id, EnumBBSItemTypeCode::$post, $language, EnumProcessTypeCode::$new, $body);
	}

	private function _setEditLog($id, $language, $body) {
		$this->setLog($id, EnumBBSItemTypeCode::$post, $language, EnumProcessTypeCode::$edit, $body);
	}

	private function _setModifyLog($id, $language, $body) {
		$this->setLog($id, EnumBBSItemTypeCode::$post, $language, EnumProcessTypeCode::$modify, $body);
	}

	private function getIp() {
		$ip = $_SERVER["REMOTE_ADDR"];
		if (get_magic_quotes_gpc()) {
			$ip = stripslashes( $ip );
		}
		return $ip;
	}
}
?>