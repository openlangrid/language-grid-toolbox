<?php

class HTTPRPC_Message_Request extends PhpBuf_Message_Abstract {
	public function __construct(){
		$this->setField('serviceName', PhpBuf_Type::STRING, PhpBuf_Rule::REQUIRED, 1);
	}
	public static function name(){
		return __CLASS__;
	}
}