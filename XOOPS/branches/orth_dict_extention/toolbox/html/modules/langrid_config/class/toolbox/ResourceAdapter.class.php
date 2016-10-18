<?php


require_once(XOOPS_ROOT_PATH.'/api/class/client/ResourceClient.class.php');

/*
 * ToolboxAPIのResourceClientクラスのアダプタ（ラッパ）
 */
class ResourceAdapter {

	private $mClient = null;

    function __construct() {
    	$this->mClient = new ResourceClient();
    }

    public function getUserDictionary() {
    	$result = $this->mClient->getAllLanguageResources("DICTIONARY");
    	if ($result['status'] != 'OK') {
    		throw new Exception($result['message']);
    	}

        $result2 = $this->mClient->getAllLanguageResources("NORMALIZED_DICTIONARY");
        if ($result2['status'] != 'OK') {
            throw new Exception($result2['message']);
        }

        $result['contents'] = array_merge($result['contents'], $result2['contents']);

    	$list = array();
    	foreach ($result['contents'] as $vo) {
    		$list[] = array(
				'service_id' => $vo->name,
				'service_type' => 'USER_DICTIONARY',
				'service_name' => $vo->name
			);
			if ($vo->isDeploy) {
	    		$list[] = array(
					'service_id' => $vo->name,
					'service_type' => 'IMPORTED_DICTIONARY',
					'service_name' => $vo->name,
				);
			}
    	}
    	return $list;
    }
    
    public function getParallelTexts() {
    	return $this->getResource('PARALLELTEXT');
    }
    
    public function getTranslationTemplates() {
    	return $this->getResource('TRANSLATION_TEMPLATE');
    }
    
    private function getResource($type) {
    	$result = $this->mClient->getAllLanguageResources($type);
    	if ($result['status'] != 'OK') {
    		throw new Exception($result['message']);
    	}

    	$list = array();
    	foreach ($result['contents'] as $vo) {
    		$list[] = array(
				'service_id' => $vo->name,
				'service_type' => $type,
				'service_name' => $vo->name
			);
    	}
    	return $list;
    }
}
?>