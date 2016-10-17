<?php
require_once(MYEXTPATH.'/service_grid/db/handler/UserDictionaryDbHandler.class.php');
class LoadDictionary extends LanguageGridAjaxRunner {
	function dispatch($action, $params) {

		$data = array();
		try {
			$tokens = explode('&', urldecode($params));
			foreach ($tokens as $item) {
				$keyval = explode('=', $item);
				$data[$keyval[0]] = $keyval[1];
			}
		} catch(Exception $e) { }

		$idUtil =& new LanguageGridArticleIdUtil();
		$dictId = $idUtil->getDictionaryIdByPageTitle($data['title_db_key']);
		$dbHandler =& new UserDictionaryDbHandler();

		if ($data['word']) {
			$data['dictionaryId'] = $dictId;
			$contents = $dbHandler->doSearch($data);
		} else {
			$contents = $dbHandler->doRead($dictId);
		}
        
		$dict = ($dbHandler->getUserDictionary($dictId));
		$response = array(
//			'lang1' => $data['lang1'],
//			'lang2' => $data['lang2'],
			'data' => array_merge($contents, array()),
            'count' => count($contents),
			'updateDate' => $dict->get('update_date')
		);

		return $response;
	}
}
?>
