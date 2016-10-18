<?php
ini_set('display_errors', 1);
require_once 'Log.php';
class Logger
{
	var $mailConf = array(
//							'to' => 'yoshimura@eip.co.jp ,koyama@eip.co.jp',
//							'to' => 'yoshimura@eip.co.jp ,koyama@eip.co.jp, kitajima@eip.co.jp',
							'to' => 'kitajima@eip.co.jp',
							'from' => 'yoshimura@dendoubako.com',
							'subject' => '[InTest]Playground-in-Developer SOAP-Request details.'
						);
	var $fileConf = array(
							'mode' => '0777',
							'path' => ''
						);
	var $logMail;
	var $logFile;

	function __construct() {
		$to = $this->mailConf['to'];
		$this->logMail = Log::singleton('mail', $to, 'Playground', $this->mailConf, PEAR_LOG_ERR);

//		$logfm = "/tmp/playground-info-".date(YmdH)."0000.log";
		$logfm = dirname(__FILE__)."/../../tmp/playground-info-".date(YmdH)."0000.log";
		$this->logFile = Log::singleton('file', $logfm, 'Playground', $this-fileConf, PEAR_LOG_DEBUG);
	}

	function errorMail($message) {
		$out = array(
			'ERROR-INFO' => $message,
			'DEBUG-BACKTRACE' => debug_backtrace(false),
			'PHP-SESSIONs' => $_SESSION,
			'SERVER' => $_SERVER
		);
		$this->logMail->log($out, PEAR_LOG_ERR);
		$this->logFile->log($out, PEAR_LOG_ERR);
	}

	function info($message) {
		$this->logFile->log($message, PEAR_LOG_DEBUG);
	}
}
//$conf = array('mode'=>0777);
//$logObj = &Log::singleton('file', '/tmp/test.log', 'ident', $conf, PEAR_LOG_NOTICE);

?>