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
$instance = new WebPageTranslationMultiPost();
echo $instance->translate();

define('MAX_PROCESS_NUM', 10);
define('PROCESS_TIMEOUT', 120);
class WebPageTranslationMultiPost
{
	private $proxy = "";
	private $option = array();
	private $result = array();

	function __construct($maxProcessNum = MAX_PROCESS_NUM) {
		$this->moduleConfig = $this->_getXoopsModuleConfig();

		$this->proxy = "";
		if ( trim($this->moduleConfig["proxy_host"]) != "") {
			$this->proxy = 'tcp://'.trim($this->moduleConfig["proxy_host"]);
			if(trim($this->moduleConfig["proxy_port"]) != "" && is_numeric(trim($this->moduleConfig["proxy_port"]))) {
				$this->proxy .= ":".trim($this->moduleConfig["proxy_port"]);
			}
		}

		$this->maxProcessNum = $maxProcessNum;

		pcntl_signal(SIGTERM, array($this, 'default_sig_handler'));
		pcntl_signal(SIGHUP,  array($this, 'default_sig_handler'));
		pcntl_signal(SIGUSR1, array($this, 'default_sig_handler'));

		$this->processWork = array($this, 'getContents');
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

		for($i=0; $i<count($html); $i++){
			$param = array(
				'from' => $from,
				'to' => $to,
				'html' => $html[$i]
			);
			$data = http_build_query($param, "", "&");

			//header
			$header = array(
				"Content-Type: application/x-www-form-urlencoded",
				"Content-Length: ".strlen($data)
			);

			$context = array(
				"http" => array(
					"method"  => "POST",
					"header"  => implode("\r\n", $header),
					"content" => $data
				)
			);

			if($this->proxy != ""){
				$context["http"]["proxy"] = $this->proxy;
			}

			/*
			$curly[$i] = curl_init();

			curl_setopt($curly[$i],CURLOPT_URL,XOOPS_URL.'/modules/'.basename(dirname(__FILE__)).'/ajax/service-client-translation.php');

			curl_setopt($curly[$i],CURLOPT_HEADER,false);
			curl_setopt($curly[$i],CURLOPT_POST,true);
			curl_setopt($curly[$i],CURLOPT_RETURNTRANSFER,true);

			if ($param != ""){
				curl_setopt($curly[$i],CURLOPT_POSTFIELDS,$param);
			}
			curl_multi_add_handle($mh, $curly[$i]);
			*/
			$pid = pcntl_fork();
			if ($pid == -1) {
				throw new Exception('Failed forc process.');
			} else if ($pid) {
				$pids[$pid] = TRUE;
				if (count($pids) >= $this->maxProcessNum) {
					unset($pids[pcntl_waitpid(-1, $status, WUNTRACED)]);
				}
			} else {
				pcntl_alarm(PROCESS_TIMEOUT);
				if (is_array($this->processWork)) {
					$obj  = $this->processWork[0];
					$func = $this->processWork[1];
					$obj->$func($i,$context);
				} else {
					$function = $this->processWork;
					$function($i,$context);
				}
				exit;
			}
		}

		/*
		$running = null;
		do {
			curl_multi_exec($mh, $running);
		} while($running > 0);

		foreach($curly as $id => $c) {
			$result[$id] = $this->test(curl_multi_getcontent($c));
			curl_multi_remove_handle($mh, $c);
		}

		curl_multi_close($mh);
		*/
		while (count($pids) > 0) {
			unset($pids[pcntl_waitpid(-1, $status, WUNTRACED)]);
		}

		return array('results' => $this->result, 'profile' => "");
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
