<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009  NICT Language Grid Project
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
require_once(XOOPS_ROOT_PATH.'/modules/langrid/php/langrid-client.php');
require_once(dirname(__FILE__).'/../Validator.php');
require_once(dirname(__FILE__).'/../../language/' . $xoopsConfig['language'] . '/main.php');

$instance = new WebPageTranslationMultiPost();
echo $instance->translate();

class WebPageTranslationMultiPost
{
	private $option = array();

	function __construct() {
	}

	function translate(){
		$from = $_POST['from'];
		$to = $_POST['to'];
		$html = $_POST['html'];

		if(!is_array($html)){
			$html = array();
		}
		$ret = $this->doTranslate($from, $to, $html);
		return json_encode($ret);
	}

	function doTranslate($from, $to, $html){
		$result = array();
		try{
			if(!isset($_POST['from'])){
				throw new Exception("Paramater 'Source Language Code' is required.");
			}
			if(!isset($_POST['to'])){
				throw new Exception("Paramater 'Target Language Code' is required.");
			}
			if(!isset($_POST['html'])){
				throw new Exception("Paramater 'Content' is required.");
			}

			$source = implode("\n",$_POST['html']);
			$dSourceLang = $_POST['from'];
			$dTargetLang = $_POST['to'];
			if(get_magic_quotes_gpc()) {
				$dSource = stripslashes($dSource);
				$dSourceLang = stripslashes($dSourceLang);
				$dTargetLang = stripslashes($dTargetLang);
			}
			$sourceLang = str_replace("&amp;", "&", htmlspecialchars($dSourceLang));
			$targetLang = str_replace("&amp;", "&", htmlspecialchars($dTargetLang));

			$validater = new Validator();
			if(!$validater->validateSupportedSourceLanguage($sourceLang)){
				throw new Exception("No translator is assigned for this translation path.");
			}
			if(!$validater->validateSupportedTargetLanguage($targetLang)){
				throw new Exception("No translator is assigned for this translation path.");
			}
			$client = new LangridClient(array(
				'sourceLang' => $sourceLang,
				'targetLang' => $targetLang
			));

			$response = $client->translate($source);
			if($response == null || $response['status'] == 'ERROR'){
				$result = array(
					"status"=>"ERROR",
					"targetText"=>"ERROR",
					"backText"=>$response['message']
				);
			}else{
				$txt = explode("\n",$response['contents']['targetText']['contents']);
				foreach($txt as $tg){
					$result[] = array(
						"status"=>"OK",
						"targetText"=>$tg,
						"backText"=>""
					);
				}
			}
		}catch(Exception $e){
			$result = array();
			$result['status'] = 'ERROR';
			$result['translate'] = $e->getMessage();
			$result['backtranslate'] = $e->getMessage();
		}
		return array('results' => $result, 'profile' => "");
	}

	private function getContents($res_num,$context) {
		$request_url = XOOPS_URL.'/modules/'.basename(dirname(__FILE__)).'/ajax/service-client-translation.php';
		$res = file_get_contents($request_url,false,stream_context_create($context));
		$txt = base64_decode($res);
		$dist = unserialize($txt);
		$this->result[$res_num] = $dist;
		sleep(1);
	}

/*
	function test($in){
		$txt = base64_decode($in);
//		$dist = json_decode($txt, false);
		$dist = unserialize($txt);
//		print_r($dist);
		return $dist;
	}
*/
	// load to langrid module config.
	private function _getXoopsModuleConfig() {
		$module_handler= & xoops_gethandler('module');
		$psModule = $module_handler->getByDirname('langrid');
		$config_handler =& xoops_gethandler('config');
		$config =& $config_handler->getConfigsByCat(0, $psModule->mid());

		if ($config == null) {
			die('Failed to retrieve config.['.__FILE__.'('.__LINE__.')]');
		}
		return $config;
	}

	private function default_sig_handler($signo) {
		switch ($signo) {
			case SIGTERM:
				//echo "shutdown...\n";
				exit;
				break;
			case SIGHUP:
				//echo "reboot...\n";
				break;
			case SIGUSR1:
				//echo "SIGUSER($signo)\n";
				break;
			default:
				//echo "Other signal: " . $signo . "\n";
				break;
		}
	}
}

define('MAX_PROCESS_NUM', 10);
class MultiRequest {
	private $urls;
	private $maxProcessNum;

	public function __construct($urls, $maxProcessNum = MAX_PROCESS_NUM) {
		declare(ticks = 1);
		$this->urls = $urls;
		$this->maxProcessNum = $maxProcessNum;

		pcntl_signal(SIGTERM, array($this, 'default_sig_handler'));
		pcntl_signal(SIGHUP,  array($this, 'default_sig_handler'));
		pcntl_signal(SIGUSR1, array($this, 'default_sig_handler'));

		$this->processWork = array($this, 'defaultWork');
	}

	public function setSignalHandler($signal, $handler, $restart_syscall = true) {
		return pcntl_signal($signal, $handler, $restart_syscall);
	}
	private function default_sig_handler($signo) {
		switch ($signo) {
			case SIGTERM:
				//echo "shutdown...\n";
				exit;
				break;
			case SIGHUP:
				//echo "reboot...\n";
				break;
			case SIGUSR1:
				//echo "SIGUSER($signo)\n";
				break;
			default:
				//echo "Other signal: " . $signo . "\n";
				break;
		}
	}

	public function setProcessWork($callback) {
		$this->processWork = $callback;
	}

	private function defaultWork($url) {
		$data = file_get_contents($url);
		//echo "[" . time() . "]" . $data . "\n";
		sleep(1);
	}

	public function run() {
		foreach ($this->urls as $url) {
			$pid = pcntl_fork();
			if ($pid == -1) {
				throw new Exception('Failed forc process.');
			} else if ($pid) {
				$pids[$pid] = TRUE;
				if (count($pids) >= $this->maxProcessNum) {
					unset($pids[pcntl_waitpid(-1, $status, WUNTRACED)]);
				}
			} else {
				pcntl_alarm(120);
				if (is_array($this->processWork)) {
					$obj  = $this->processWork[0];
					$func = $this->processWork[1];
					$obj->$func($url);
				} else {
					$function = $this->processWork;
					$function($url);
				}
				exit;
			}
		}
		while (count($pids) > 0) {
			unset($pids[pcntl_waitpid(-1, $status, WUNTRACED)]);
		}
	}
}
?>
