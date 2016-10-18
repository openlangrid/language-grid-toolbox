<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:	 function
 * Name:	 legacy_profile
 * Version:  1.0
 * Date:	 Feb 01, 2011
 * Author:	 HIKAWA Kilica
 * Purpose:  Profile data show
 * Input:	 User_EditUserForm	actionForm
 * 			 int	uid: user id
 *			 string	action: 'view', 'edit', etc
 *			 string	template:	template name
 * Examples: {legacy_profile uid=3 action=edit actionForm=$actionForm}
 * -------------------------------------------------------------
 */
function smarty_function_legacy_profile($params, &$smarty)
{
	$uid = isset($params['uid']) ? intval($params['uid']) : Legacy_Utils::getUid();
	if($uid===0){
		return;
	}
	$profileActionForm = isset($params['actionForm']) ? $params['actionForm'] : null;
	$action = isset($params['action']) ? $params['action'] : 'view';
	$template = isset($params['template']) ? $params['template'] : 'profile_inc_data_view.html';

	$defArr = null;
	XCube_DelegateUtils::call(
		'Legacy_Profile.GetDefinition',
		new XCube_Ref($defArr),
		$action
	);

	$profile = null;
	XCube_DelegateUtils::call(
		'Legacy_Profile.GetProfile',
		new XCube_Ref($profile),
		$uid
	);

    $language = D3LanguageManager::getInstance()->getSelectedLanguageTag();
    $flds = array('label', 'description');
    foreach ($defArr as &$v) {
        foreach ($flds as $f)
        $v->mVars[$f]['value'] = _smarty_function_legacy_profile_get_l10n_resource($language, $v->mVars[$f]['value']);
    }

	//render template
	$render = new XCube_RenderTarget();
	$render->setTemplateName($template);
	$render->setAttribute('legacy_buffertype',XCUBE_RENDER_TARGET_TYPE_MAIN);
	$render->setAttribute('profileForm', $profileActionForm);
	$render->setAttribute('profile', $profile);
	$render->setAttribute('defArr', $defArr);
	XCube_Root::getSingleton()->getRenderSystem('Legacy_RenderSystem')->render($render);

	echo $render->getResult();
}

function _smarty_function_legacy_profile_get_l10n_resource($target, $resource) {
    $matches = FALSE;
    if (preg_match_all('/\[([a-zA-Z-]+)\](.*?)\[\/[a-zA-Z-]+\]/',
                       $resource, $matches, PREG_SET_ORDER) === FALSE) {
        return $resource;
    }

    foreach ($matches as $v) {
        if ($v[1] == $target) {
            return $v[2];
        }
    }

    return $resource;
}


?>
