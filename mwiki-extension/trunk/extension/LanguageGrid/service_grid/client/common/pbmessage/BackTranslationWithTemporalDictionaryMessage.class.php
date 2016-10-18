<?php

class Message_BackTranslationWithTemporalDictionary_TranslateRequest extends PhpBuf_Message_Abstract {
	public function __construct() {
		$this->setField('headers', PhpBuf_Type::MESSAGE, PhpBuf_Rule::REPEATED, 1, Message_Common_Header::name());
		$this->setField('sourceLang', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 2);
		$this->setField('intermediateLang', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 3);
		$this->setField('source', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 4);
		$this->setField('temporalDictionary', PhpBuf_Type::MESSAGE, PhpBuf_Rule::REPEATED, 5, Message_BilingualDictionary_Translation::name());
		$this->setField('dictionaryTargetLang', PhpBuf_Type::STRING, PhpBuf_Rule::OPTIONAL, 6);
	}
	public static function name() {
		return __CLASS__;
	}
}

class Message_BackTranslationWithTemporalDictionary_TranslateResponse extends PhpBuf_Message_Abstract {
	public function __construct() {
		$this->setField('headers', PhpBuf_Type::MESSAGE, PhpBuf_Rule::REPEATED, 1, Message_Common_Header::name());
		$this->setField('fault', PhpBuf_Type::MESSAGE, PhpBuf_Rule::OPTIONAL, 2, Message_Common_Fault::name());
//		$this->setField('result', PhpBuf_Type::MESSAGE, PhpBuf_Rule::OPTIONAL, 3, Message_BackTransltionResult::name());
		$this->setField('intermediate', PhpBuf_Type::STRING, PhpBuf_Rule::OPTIONAL, 3);
		$this->setField('target', PhpBuf_Type::STRING, PhpBuf_Rule::OPTIONAL, 4);
	}
	public static function name() {
		return __CLASS__;
	}
}

//class Message_BackTransltionResult extends PhpBuf_Message_Abstract {
//	public function __construct() {
//		$this->setField('intermediate', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 1);
//		$this->setField('target', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 2);
//	}
//	public static function name() {
//		return __CLASS__;
//	}
//}
?>