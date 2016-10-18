<?php

class MultiSentence {

	const D1 = '';

	private $mJoinedSource = '';
	private $mSourceArray = array();

    public function __construct($source) {
    	if ($source != null && is_array($source)) {
    		$this->mSourceArray = $source;
    	} else {
    		$mJoinedSource = $source;
    	}
    }

	public function toJoin() {

	}

	public function toArray() {

	}

	private function _join() {

	}

}
?>