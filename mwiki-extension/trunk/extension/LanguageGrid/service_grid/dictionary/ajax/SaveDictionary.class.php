<?php
require_once(MYEXTPATH.'/service_grid/db/handler/UserDictionaryDbHandler.class.php');
define("DUMPDIR", MYEXTPATH.'/logs/dictionary/');

class SaveDictionary extends LanguageGridAjaxRunner {
	function dispatch($action, $params) {
        global $wgUser, $wgTitle;
        
		$dbHandler =& new UserDictionaryDbHandler();
        
		$post = json_decode($params, true);
		$data = json_decode($post['data'], true);

        $titleDbKey = $post['title_db_key'];
        $updateDate = $post['updateDate'];
        $userName = str_replace(":", ".", $wgUser->getName());
        
        
		$idUtil =& new LanguageGridArticleIdUtil();
		$dictId = $idUtil->getDictionaryIdByPageTitle($post['title_db_key']);

		$contents = $dbHandler->update($dictId, $data);
		$dict = ($dbHandler->getUserDictionary($dictId));

        
        $dumpContents =& $dbHandler->doDownload($dictId);
        $dumpData = array();
        foreach($dumpContents as $row) {
            foreach($row as $key => $cell) {
                if ($key != 'row') {
                    $dumpData[] = $cell."\t";
                }
            }
            $dumpData[] = "\n";
        }
        $utf16LEcontent = chr(255).chr(254).mb_convert_encoding(implode('', $dumpData), "UTF-16LE", "UTF-8");

        $filename = $titleDbKey . "_" . $updateDate . "_" . $userName;
        $fp = fopen(DUMPDIR.$filename, 'w');
        fwrite($fp, $utf16LEcontent);
        fclose($fp);

        
		$response = array(
			'lang1' => $post['lang1'],
			'lang2' => $post['lang2'],
			'data' => $contents,
            'count' => count($contents),
			'updateDate' => $dict->get('update_date')
		);

		return $response;
	}
}
?>
