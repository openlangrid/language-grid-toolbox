<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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

require_once XOOPS_ROOT_PATH.'/api/class/client/LangridAccessClient.class.php';
require_once dirname(__FILE__).'/langrid-client-proxy.php';

class LangridAccessClientAdapter extends LangridClientProxy {

	/**
	 * real process
	 */
	protected function doTranslate($sourceText) {
		$langridClient = new LangridAccessClient();
		$translationResult = $langridClient->backTranslate(
			$this->getSourceLanguageCode(), $this->getTargetLanguageCode()
			, $sourceText, 'SITE');	// TODO: langrid_config
		$result = $translationResult;
		$result['contents'] = $translationResult['contents'][0];
		$result['contents']->backText['contents'] = $matches[0].($result['contents']->targetResult);
		$result['contents']->targetText['contents'] = $matches[0].($result['contents']->intermediateResult);
		$result['contents']->{$this->getTargetLanguageCode()} = array(
			'translation' => array(
				'contents' => $result['contents']->intermediateResult
			),
			'backTranslation' => array(
				'contents' => $result['contents']->targetResult
			)
		);
		$result['licenseInformation'] = array();
		foreach ($result['contents']->translationInvocationInfo as $serviceId => $license) {
			$result['licenseInformation'][$serviceId] = array(
				'serviceName' => $license->serviceName,
				'serviceCopyright' => $license->copyright,
				'serviceLicense' => $license->license
			);
		}
		return $result;
	}

	/**
	 * adapter
	 */
	protected function adapter($result) {
		return $result;
	}
}
?>