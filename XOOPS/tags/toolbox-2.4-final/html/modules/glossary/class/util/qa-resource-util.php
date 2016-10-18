<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// glossaries.
// Copyright (C) 2010  CITY OF KYOTO
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
require_once XOOPS_ROOT_PATH.'/api/IResourceVO.interface.php';
require_once dirname(__FILE__).'/../manager/qa-permission-manager.php';

/**
 * @author kitajima
 */
class QaResourceUtil {
	
	public static function sortByNameAsc($a, $b) {
		if ($a['name'] == $b['name']) {
			return 0;
		}
		return ($a['name'] < $b['name']) ? -1 : 1;
	}
	
	/**
	 * 
	 * @param unknown_type $resource
	 * @param unknown_type $records
	 * @param int[] $categoryIds
	 * @return array
	 */
	public static function buildResource($resource, $records = array(), $categoryIds = array()) {
		$permissionManager = new QaPermissionManager;
		return array(
			'name' => $resource->name,
			'categoryIds' => $categoryIds,
			'languages' => $resource->languages,
			'permission' => array(
				'read' => array(
					'type' => ($resource->readPermission->type == 'PUBLIC')
								? 'all' : $resource->readPermission->type
				),
				'edit' => array(
					'type' => ($resource->editPermission->type == 'PUBLIC')
								? 'all' : $resource->editPermission->type
				)
			),
			'creator' => array(
				'name' => $resource->creator
			),
			'meta' => array(
				'permission' => $permissionManager->getMyPermissionFromResource($resource),
				'updateDate' => formatTimestamp($resource->lastUpdate, 's'),
				'updateTime' => $resource->lastUpdate,
				'entries' => $resource->entryCount 
			),
			'records' => $records
		);
	}
}
?>