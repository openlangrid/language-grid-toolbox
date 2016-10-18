<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
require_once XOOPS_ROOT_PATH.'/api/class/client/ResourceClient.class.php';

/**
 * @author kitajima
 */
class ClientFactory {
	
	private $instance;

	protected function __construct() {
	}

	/**
	 * 
	 * @param String $moduleName
	 * @return ClientFactory
	 */
	public static function getFactory($moduleName) {
		switch (strtoupper($moduleName)) {
		case 'TRANSLATION_TEMPLATE':
			return new TranslationTemplateClientFactory();
		case 'GLOSSARY':
			return new GlossaryClientFactory();
		default:
			return new QaClientFactory();
		}
	}

	/**
	 * 
	 * @return ResourceClient
	 */
	public function createResourceClient() {
		return new ResourceClient();
	}

	/**
	 * 
	 * @return IQAClient
	 */
	public function createModuleClient() {
		return null;
	}
}

/**
 * 
 * @author kitajima
 *
 */
class QaClientFactory extends ClientFactory {
	
	/**
	 * (non-PHPdoc)
	 * @see html/modules/qa/class/factory/ClientFactory#createModuleClient()
	 */
	public function createModuleClient() {
		require_once XOOPS_ROOT_PATH.'/api/class/client/QAClient.class.php';
		return new QaClient();
	}
}

/**
 * 
 * @author kitajima
 *
 */
class GlossaryClientFactory extends ClientFactory {
	
	/**
	 * (non-PHPdoc)
	 * @see html/modules/qa/class/factory/ClientFactory#createModuleClient()
	 */
	public function createModuleClient() {
		require_once XOOPS_ROOT_PATH.'/api/class/client/GlossaryClient.class.php';
		return new GlossaryClient();
	}
}

/**
 * 
 * @author kitajima
 *
 */
class TranslationTemplateClientFactory extends ClientFactory {
	
	/**
	 * (non-PHPdoc)
	 * @see html/modules/qa/class/factory/ClientFactory#createModuleClient()
	 */
	public function createModuleClient() {
		require_once XOOPS_ROOT_PATH.'/api/class/client/TranslationTemplateClient.class.php';
		return new TranslationTemplateClient();
	}
}
?>