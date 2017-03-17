<?php
abstract class AbstractManager {
	protected $root = null;
	protected $db = null;
	protected $uid = null;
	public function __construct() {
		$this->getAccessUser();
	}
	protected function getResponsePayload($contents=array(), $status='OK', $message='NoError') {
		$response = array(
			'status'=>$status,
			'message'=>$message,
			'contents'=>$contents);
		return $response;
	}
	protected function getErrorResponsePayload($message, $status='Error') {
		$response = array(
			'status'=>$status,
			'message'=>$message,
			'contents'=>'');
		return $response;
	}
	protected function getAccessUser() {
		$this->uid = 0;
	}
}
?>