<?php
require_once(dirname(__FILE__).'/../class/ImportPageDictionaryAdapter.class.php');
class ImportDictionary extends LanguageGridAjaxRunner {
	private $mAdapter = null;
	function dispatch($action, $params) {
		$data = $this->getParameters($params);
		$this->mAdapter = new ImportPageDictionaryAdapter($data['title_db_key']);
		switch ($action) {
			case 'ImportDictionary:Add':
				$method = 'addDictionary';
				break;
			case 'ImportDictionary:Load':
				$method = 'loadDictionary';
				break;
			case 'ImportDictionary:Delete':
				$method = 'deleteDictionary';
				break;
		}
		$result = $this->{$method}($data);
		return $result;
	}

	private function addDictionary($data) {
		try {
			if ($this->mAdapter->add($data['title'])) {
				$message = 'Success';
			} else {
				$message = 'Error.';
			}
		} catch (Exception $e) {
			$message = $e->getMessage();
		}

		$return = array(
			'status' => ($message == 'Success' ? 'OK' : 'Error'),
			'message' => $message,
			'contents' => array(
				'title' => $data['title']
			)
		);

		return $return;
	}

	private function loadDictionary($data) {
		try {
			$this->mAdapter->loadDictionary($data['title'], $data['title_db_key']);
			$message = 'Success';
		} catch (Exception $e) {
			$message = $e->getMessage();
		}

		$return = array(
			'status' => ($message == 'Success') ? 'OK' : 'Error',
			'message' => $message,
			'contents' => array()
		);
		return $return;
	}
	private function deleteDictionary($data) {
		$this->mAdapter->remove($data['title']);
	}
}

?>
