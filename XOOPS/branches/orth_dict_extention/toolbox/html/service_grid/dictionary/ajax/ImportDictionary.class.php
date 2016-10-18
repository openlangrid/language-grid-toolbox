<?php
require_once(dirname(__FILE__).'/../class/ImportPageDictionaryAdapter.class.php');
class ImportDictionary extends LanguageGridAjaxRunner {

	private $mAdapter = null;

	function dispatch($action, $params) {
		$data = $this->getParameters($params);

		$title = $data['title'];

		$this->mAdapter = new ImportPageDictionaryAdapter($data['title_db_key']);

		switch ($action) {
			case 'ImportDictionary:Add':
				$method = 'addDictionary';
				break;
			case 'ImportDictionary:Delete':
				$method = 'deleteDictionary';
				break;
		}

		$result = $this->{$method}($title);

		return $result;
	}

	private function addDictionary($title) {
		try {
			if ($this->mAdapter->add($title)) {
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
				'title' => $title
			)
		);

		return $return;
	}

	private function deleteDictionary($title) {
		$this->mAdapter->remove($title);
	}
}

?>
