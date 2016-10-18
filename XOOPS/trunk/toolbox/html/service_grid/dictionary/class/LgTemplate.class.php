<?php

class LgTemplate {

	private $output;
	private $assign;
	private $templatePath;

	public function __construct($output, $templatePath) {
		$this->output = $output;
		$this->assign = new StdClass();
		$this->templatePath = $templatePath;
	}

	public function addJS($js) {
		$base = '<script charset="utf-8" type="text/javascript" src="%s"></script>';

		foreach ($js as $j) {
			$script = sprintf($base, $j);
			$this->output->addScript($script);
		}
	}

	public function addCSS($css) {
		$base = '<link rel="stylesheet" type="text/css" href="%s" />';

		foreach ($css as $c) {
			$script = sprintf($base, $c);
			$this->output->addScript($script);
		}
	}

	public function assign($key, $value) {
		$this->assign->{$key} = $value;
	}

	public function loadTemplate($filename) {
		$html = $this->fetch($filename);
		$this->output->addHTML($html);
	}

	private function fetch($filename) {
		$filename = $this->templatePath.'/'.$filename;

		if (!is_file($filename)) {
			return '';
		}

		ob_start();
		require_once($filename);
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	private function addScript($script) {
		$this->output->addScript($script);
	}
}
?>
