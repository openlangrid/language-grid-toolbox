<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2010  NICT Language Grid Project
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
/* $Id: Import_RemoveServiceAction.class.php 4654 2010-10-28 06:37:35Z yoshimura $ */

require_once(MY_MODULE_PATH.'/class/action/AjaxAction.class.php');
require_once(MY_MODULE_PATH.'/class/manager/ImportedServicesAdapter.class.php');

class Import_RemoveServiceAction extends AjaxAction {

	/**
	 * @overwrite
	 */
    public function __construct() {
    	parent::__construct();
    }

    /**
     * @overwrite
     */
    public function execute() {
    	parent::execute();

    	try {
    		$contents = $this->update();
	    	$this->buildSuccessResult($contents);
    	} catch (Exception $e) {
    		$this->buildErrorResult($e->getMessage());
    	}
    }

    protected function update() {
    	$adp = new ImportedServicesAdapter();
    	$serviceId = $_POST['serviceId'];
    	$service = $adp->removeService($serviceId);
    	return array('service' => array('id'=>$serviceId));
    }
}
?>