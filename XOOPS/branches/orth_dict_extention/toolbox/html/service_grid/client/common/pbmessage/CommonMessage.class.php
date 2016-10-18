<?php
class Message_Common_PartOfSpeech extends Message_Enum_Abstract {
	const noun_common = 0;
	const noun_pronoun = 1;
	const noun_proper = 2;
	const noun_other = 3;
	const noun = 4;
	const verb = 5;
	const adjective = 6;
	const adverb = 7;
	const other = 8;
	const unknown = 9;
	public static function values() {
		return array(
			self::noun_common,
			self::noun_pronoun,
			self::noun_proper,
			self::noun_other,
			self::noun,
			self::verb,
			self::adjective,
			self::adverb,
			self::other,
			self::unknown,
			);
	}
}

class Message_Common_MatchingMethod extends Message_Enum_Abstract {
	const complete = 0;
	const partial = 1;
	const prefix = 2;
	const suffix = 3;
	const regex = 4;
	public static function values() {
		return array(
			self::complete,
			self::partial,
			self::prefix,
			self::suffix,
			self::regex,
		);
	}
}

class Message_Common_Header extends PhpBuf_Message_Abstract {
	public function __construct() {
		$this->setField('name', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 1);
		$this->setField('value', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 2);
	}
	public static function name() {
		return __CLASS__;
	}
}

class Message_Common_Fault extends PhpBuf_Message_Abstract {
	public function __construct() {
		$this->setField('faultCode', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 1);
		$this->setField('faultString', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 2);
		$this->setField('faultDetail', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 3);
	}
	public static function name() {
		return __CLASS__;
	}
}