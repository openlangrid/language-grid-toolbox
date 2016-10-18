<?php

require_once dirname(__FILE__).'/xoops-module-dao.php';

class ImportModuleData {
	
	private $errorMessages;
	
	private static $columnMap = array(
		'category_access' => array(
			'cat_id',
			'uid',
			'groupid',
			'`all`',
			'can_post',
			'can_edit',
			'can_delete',
			'post_auto_approved',
			'can_makeforum',
			'is_moderator'
		),
		'forum_access' => array(
			'forum_id',
			'uid',
			'groupid',
			'`all`',
			'can_post',
			'can_edit',
			'can_delete',
			'post_auto_approved',
			'is_moderator'
		),
		'topic_access' => array(
			'topic_id',
			'groupid',
			'`all`',
			'can_post',
			'can_edit',
			'can_delete',
			'uid'
		),
		'categories' => array(
			'cat_id',
			'cat_title_ja',
			'cat_desc_ja',
			'cat_title_en',
			'cat_desc_en',
			'cat_original_language',
			'pid',
			'cat_title',
			'cat_desc',
			'cat_topics_count',
			'cat_posts_count',
			'cat_last_post_id',
			'cat_last_post_time',
			'cat_topics_count_in_tree',
			'cat_posts_count_in_tree',
			'cat_last_post_id_in_tree',
			'cat_last_post_time_in_tree',
			'cat_depth_in_tree',
			'cat_order_in_tree',
			'cat_path_in_tree',
			'cat_unique_path',
			'cat_weight',
			'cat_options',
			'create_date',
			'update_date',
			'delete_flag'
		),
		'forums' => array(
			'forum_id',
			'cat_id',
			'uid',
			'forum_title_ja',
			'forum_title_en',
			'forum_desc_ja',
			'forum_desc_en',
			'forum_original_language',
			'forum_external_link_format',
			'forum_title',
			'forum_desc',
			'forum_topics_count',
			'forum_posts_count',
			'forum_last_post_id',
			'forum_last_post_time',
			'forum_weight',
			'forum_options',
			'create_date',
			'update_date',
			'delete_flag'
		),
		'topics' => array(
			'topic_id',
			'uid',
			'forum_id',
			'topic_title_ja',
			'topic_title_en',
			'topic_original_language',
			'topic_views',
			'topic_external_link_id',
			'topic_title',
			'topic_first_uid',
			'topic_first_post_id',
			'topic_first_post_time',
			'topic_last_uid',
			'topic_last_post_id',
			'topic_last_post_time',
			'topic_posts_count',
			'topic_locked',
			'topic_sticky',
			'topic_solved',
			'topic_invisible',
			'topic_votes_sum',
			'topic_votes_count',
			'create_date',
			'update_date',
			'delete_flag'
		),
		'posts' => array(
			'post_id',
			'topic_id',
			'uid',
			'poster_ip',
			'post_text_ja',
			'post_text_en',
			'post_original_language',
			'post_time',
			'pid',
			'modified_time',
			'uid_hidden',
			'modifier_ip',
			'subject',
			'html',
			'smiley',
			'xcode',
			'br',
			'number_entity',
			'special_entity',
			'icon',
			'attachsig',
			'invisible',
			'approval',
			'votes_sum',
			'votes_count',
			'depth_in_tree',
			'order_in_tree',
			'path_in_tree',
			'unique_path',
			'guest_name',
			'guest_email',
			'guest_url',
			'guest_pass_md5',
			'guest_trip',
			'post_text',
			'post_text_waiting',
			'post_order',
			'reply_post_id',
			'delete_flag',
			'update_date'
		),
		'users2topics' => array(
			'uid',
			'topic_id',
			'u2t_time',
			'u2t_marked',
			'u2t_rsv'
		),
		'post_votes' => array(
			'vote_id',
			'post_id',
			'uid',
			'vote_point',
			'vote_time',
			'vote_ip'
		),
		'post_histories' => array(
			'history_id',
			'post_id',
			'history_time',
			'data'
		),
		'topic_modify_log' => array(
			'modify_id',
			'topic_id',
			'topic_title',
			'language',
			'user_id',
			'ip',
			'modify_time'
		),
		'post_modify_log' => array(
			'modify_id',
			'post_id',
			'text',
			'language',
			'user_id',
			'ip',
			'modify_time'
		),
		'categories_body' => array(
			'cat_id',
			'language_code',
			'title',
			'`description`'
		),
		'forums_body' => array(
			'forum_id',
			'language_code',
			'title',
			'`description`'
		),
		'topics_body' => array(
			'topic_id',
			'language_code',
			'title',
			'`description`'
		),
		'posts_body' => array(
			'post_id',
			'language_code',
			'title',
			'`description`',
			'`update_time`'
		),
		'bbs_correct_edit_history' => array(
			'bbs_id',
			'bbs_item_type_cd',
			'language_code',
			'history_count',
			'proc_type_cd',
			'bbs_text',
			'user_id',
			'create_date',
			'delete_flag'
		),
		'topic_access_log' => array(
			'topic_id',
			'user_id',
			'last_access_time'
		),
		'post_file' => array(
			'id',
			'post_id',
			'file_name',
			'file_data',
			'file_size'
		)
	);
	
	public function __construct() {
		$this->errorMessages = array();
	}

	public function isPost() {
		return ($_SERVER['REQUEST_METHOD'] == 'POST');
	}

	public function isSuccess() {
		return ($this->isPost() && count($this->getErrorMessages()) == 0);
	}

	public function addErrorMessage($m) {
		$this->errorMessages[] = $m;
	}

	public function getErrorMessages() {
		return $this->errorMessages;
	}

	public function validate($from) {
		$this->errorMessages = array();
		
		if ($from == '') {
			$this->errorMessages[] = _MD_SBBS_MSG_EMPTY_MODULE;
		} else if (!$this->isModuleExists($from)) {
			$this->errorMessages[] = _MD_SBBS_MSG_NO_MODULE;
		}
		
		return count($this->errorMessages) == 0;
	}
	
	private function isModuleExists($moduleName) {
		$dao = new XoopsModuleDAO();
		return $dao->isModuleExists($moduleName);
	}
	
	public function import($from, $to) {
		$dao = new XoopsModuleDAO();
		foreach (self::$columnMap as $table => $columns) {
			$dao->cloneTableData($from.'_'.$table, $to.'_'.$table, $columns);
		}
	}
	
	public function get($key) {
		return isset($_POST[$key]) ? $_POST[$key] : '';
	}
}
?>