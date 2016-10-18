<?php

require_once dirname(__FILE__).'/abstract-manager.php';
require_once dirname(__FILE__).'/../model/group.php';
require_once dirname(__FILE__).'/../translation/translation.php';
require_once dirname( __FILE__ ) . "/post-manager.php";
require_once dirname( __FILE__ ) . "/topic-manager.php";
require_once dirname( __FILE__ ) . "/forum-manager.php";
require_once dirname( __FILE__ ) . "/category-manager.php";

class GroupManager extends AbstractManager {

	private $postManager;
	private $topicManager;
	private $forumManager;
	private $categoryManager;
	
	public function __construct() {
		parent::__construct();
		
		$this->postManager = new PostManager();
		$this->topicManager = new TopicManager();
		$this->forumManager = new ForumManager();
		$this->categoryManager = new CategoryManager();

	}
	
	public function getGroupsById($id, $type){
		$groups = $this->getGroups();
		$groupArrays = array();
		$auth = array();
		$parent_auth = array();
		if($type === 'reply'){
			$auth = $this->postManager->getPostAuth($id);
			$parent_auth = $this->postManager->getPostAuth($this->postManager->getReplyId($id));
		}else if($type === 'post') {
			$auth = $this->postManager->getPostAuth($id);
			$parent_auth = $this->topicManager->getTopicAuth($this->postManager->getParentId($id));
		}else if ($type === 'topic') {
			$auth = $this->topicManager->getTopicAuth($id);
			$parent_auth = $this->forumManager->getForumAuth($this->topicManager->getParentId($id));
		}else if($type ===  'forum') {
			$auth = $this->forumManager->getForumAuth($id);
			$parent_auth =  $this->categoryManager->getCategoryAuth($this->forumManager->getParentId($id));
		}else if($type === 'category') {
			$auth = $this->categoryManager->getCategoryAuth($id);

		}
		foreach($groups as $group){
			if($type === 'category'||in_array($group->getId(),$parent_auth)){
				$isDisabled = '0';
			}else{
				$isDisabled = '1';
			}
			
			if(in_array($group->getId(),$auth)){
				$isSelected = '1';
			}else{
				$isSelected = '0';
			}
			

			$groupArrays[] = array('id'		  => $group->getId(),
								   'name'	  => $group->getName(),
								   'disabled' => $isDisabled, 
								   'selected' => $isSelected
								   );
		}
		
		return $groupArrays;
	}
	
	public function getGroupsByParentId($parentId, $type){
		//	echo "parentId:".$parentId." type:".$type;
	
		$groups = $this->getGroups();
		$groupArrays = array();
		$parent_auth = array();
		if($type === 'reply'){
			$parent_auth = $this->postManager->getPostAuth($parentId);
		}else if($type === 'post') {
			$parent_auth = $this->topicManager->getTopicAuth($parentId);
		}else if ($type === 'topic') {
			$parent_auth = $this->forumManager->getForumAuth($parentId);
		}else if($type ===  'forum') {
			$parent_auth = $this->categoryManager->getCategoryAuth($parentId);
		}

		foreach($groups as $group){
			if($type === 'category'||in_array($group->getId(),$parent_auth)){
				$isDisabled = '0';
			}else{
				$isDisabled = '1';
			}
			
			$groupArrays[] = array('id' => $group->getId(),'name' => $group->getName(),'disabled' => $isDisabled);
		}
		
		return $groupArrays;
	}
	
	public function getGroupsByChildren($id,$type){
		$groups = $this->getGroups();
		
		$children_auth = array();
		
		if ($type === 'topic') {
			$child_ids = $this->topicManager->getChildIds($id);
			foreach($child_ids as $child_id){				
				$child_auth = $this->postManager->getPostAuth($child_id);
				$children_auth = array_merge($children_auth,$child_auth);
			}
		}else if($type ===  'forum') {
			$child_ids = $this->forumManager->getChildIds($id);
			foreach($child_ids as $child_id){
				$child_auth = $this->topicManager->getTopicAuth($child_id);
				$children_auth = array_merge($children_auth,$child_auth);
			}
		}else if($type === 'category'){
			$child_ids = $this->categoryManager->getChildIds($id);
			foreach($child_ids as $child_id){
				$child_auth = $this->forumManager->getForumAuth($child_id);
				$children_auth = array_merge($children_auth,$child_auth);
			}
		}
		$children_auth = array_unique($children_auth);


		return $children_auth;
	}
	
	public function getGroupsBySelected($id,$type,$selectedIds){
		$groups = $this->getGroups();
		$parent_auth = array();
		$groupArrays = array();
		if($type === 'reply'){
			$parent_auth = $this->postManager->getPostAuth($id);
		}else if($type === 'post') {
			$parent_auth = $this->topicManager->getTopicAuth($id);
		}else if ($type === 'topic') {
			$parent_auth = $this->forumManager->getForumAuth($id);
		}else if($type ===  'forum') {
			$parent_auth =  $this->categoryManager->getCategoryAuth($id);
		}
		foreach($groups as $group){
			if($type === 'category'||in_array($group->getId(),$parent_auth)){
				$isDisabled = '0';
			}else{
				$isDisabled = '1';
			}
			
			if(in_array($group->getId(),$selectedIds)){
				$isSelected = '1';
			}else{
				$isSelected = '0';
			}
			

			$groupArrays[] = array('id'		  => $group->getId(),
								   'name'	  => $group->getName(),
								   'disabled' => $isDisabled, 
								   'selected' => $isSelected
								   );
		
		}
		return $groupArrays;
	}
	
	public function getGroupCount(){
		return count($this->getGroups());
	}
	

}