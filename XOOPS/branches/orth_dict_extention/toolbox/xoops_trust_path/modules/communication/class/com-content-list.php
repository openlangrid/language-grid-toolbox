<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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

require_once 'com-content.php';


class Com_ContentList {
	
	private $contents;
	
	private function __construct($contents) {
		$this -> contents = $contents;	
	}
	
	public function length(){
		return count($this -> contents);
	}
	
	public function datas() {
		return $this -> contents;
	}
	
	function prevContentIdOf($targetContent) {
		$content = $this -> prevContentOf($targetContent); 
		return $content ? $content -> getContentId() : "";
	}
	
	function prevContentOf($targetContent) {
		if(!$targetContent || $targetContent->getType() == 'blank') {
			return end($this -> contents) ? end($this -> contents) : null;
		}
		$isAppear = false;
		foreach(array_reverse($this -> contents) as $con) {
			if($isAppear) return $con;
			if($con->getContentId() == $targetContent->getContentId()) $isAppear = true; 
		}
		return null; 
	}
	
	function nextContentIdOf($targetContent) {
		$content = $this -> nextContentOf($targetContent);
		return $content ? $content -> getContentId() : "";
	}
	
	function nextContentOf($targetContent) {
		if(!$targetContent || $targetContent->getType() == 'blank') {
			return reset($this -> contents) ? reset($this -> contents) : $targetContent;
		}
		$isAppear = false;
		foreach($this -> contents as $con) {
			if($isAppear) return $con;
			if($con->getContentId() == $targetContent->getContentId()) $isAppear = true; 
		}
		return null;
	}
	
	function firstContentId() {
		$f = $this -> firstContent();
		return $f ? $f -> getContentId() : null;
	}
	
	function lastContentId() {
		$l = $this -> lastContent();
		return  $l ? $l -> getContentId() : null;
	}
	
	function firstContent() {
		return count($this -> contents) ? $this -> contents[0] : null;
	}
	
	function lastContent() {
		return count($this -> contents) ? $this -> contents[$this->length() - 1] : null;
	}

	static public function getAvailableListByTopicId($topicId) {
		return new Com_ContentList(Com_Content::findAvailableContentsByTopicId($topicId));
	}
	
	static public function getListByTopicId($topicId) {
		return new Com_ContentList(Com_Content::findAllByTopicId($topicId));
	}
}
?>