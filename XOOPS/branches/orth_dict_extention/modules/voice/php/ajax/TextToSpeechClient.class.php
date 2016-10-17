<?php

require_once(dirname(__FILE__).'/../../../langrid/php/service/ServiceClient.class.php');
class TextToSpeechClient extends ServiceClient {
	private $url = 'http://langrid.nict.go.jp/langrid-1.2/wsdl/VoiceText';
	
	public function __construct() {
		parent::__construct($this->url);
	}
	
	protected function makeBindingHeader($parameters){
		return '';
	}
	
	public function getVoice($operation, $params) {
		$this->removeFile();
		
		$response=$this->call($operation, $params);
		
		
		$base=base64_decode($response['contents']->audio);
		
		$filename=$this->generateFile($base);
		
		
		return $filename;
	}
	
	private function removeFile() {
		$files = array();
		if ($handle = opendir('../../wav/')) {
		    while (false !== ($file = readdir($handle))) {
		    	if(strpos($file, '.wav')!==false) {
		        	$files[] = $file;
		        }
		    }
		    closedir($handle);
		}
		
		
		$now=$this->getMTime();
		
		for($i=0; $i<count($files); $i++) {
			$utc=substr($files[$i], 0, 10);
			$now=substr($now, 0, 10);
				
			$diff=$now-$utc;
			if($diff>60) {
				unlink('../../wav/'.$files[$i]);
			}
		}
	}
	

	private function generateFile($base) {
		$now=$this->getMTime();
		
		$fp=fopen('../../wav/'.$now.'.wav', 'wb');
		fwrite($fp, $base);
		fclose($fp);
		
		return $now.'.wav';
	}

	private function getMTime() {
		$time = microtime();
		
		$split = explode(' ', $time);
		
		$mili = substr($split[0], 2, 4) ;
		
		return $split[1] . $mili; 
	}
}

?>