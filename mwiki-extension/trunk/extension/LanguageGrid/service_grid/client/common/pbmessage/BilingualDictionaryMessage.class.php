<?php

class Message_BilingualDictionary_Translation extends PhpBuf_Message_Abstract {
	public function __construct() {
		$this->setField('headWord', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 1);
		$this->setField('targetWords', PhpBuf_Type::STRING, PhpBuf_Rule::REPEATED, 2);
	}
	public static function name() {
		return __CLASS__;
	}
}

class Message_BilingualDictionary_SearchRequest extends PhpBuf_Message_Abstract {
	public function __construct() {
		$this->setField('headers', PhpBuf_Type::MESSAGE, PhpBuf_Rule::REPEATED, 1, Message_Common_Header::name());
		$this->setField('headLang', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 2);
		$this->setField('targetLang', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 3);
		$this->setField('headWord', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 4);
		$this->setField('mathingMethod', PhpBuf_Type::MESSAGE, PhpBuf_Rule::REQUIRED, 5, Message_Common_MatchingMethod::name());
	}
	public static function name() {
		return __CLASS__;
	}
}

class Message_BilingualDictionary_SearchResponse extends PhpBuf_Message_Abstract {
	public function __construct() {
		$this->setField('headers', PhpBuf_Type::MESSAGE, PhpBuf_Rule::REPEATED, 1, Message_Common_Header::name());
		$this->setField('fault', PhpBuf_Type::MESSAGE, PhpBuf_Rule::OPTIONAL, 2, Message_Common_Fault::name());
		$this->setField('result', PhpBuf_Type::Message, PhpBuf_Rule::REPEATED, 3, Message_BilingualDictionary_Translation::name());
	}
	public static function name() {
		return __CLASS__;
	}
}
