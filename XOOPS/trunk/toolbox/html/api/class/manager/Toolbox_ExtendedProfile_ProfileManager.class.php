<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009  NICT Language Grid Project
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

require_once(dirname(__FILE__).'/Toolbox_AbstractManager.class.php');

class Toolbox_ExtendedProfile_ProfileManager extends Toolbox_AbstractManager {

	protected $handler = null;

	public function __construct() {
		parent::__construct();
	}

	public function getProfile($uid) {
		$object = $this->getProfileByUid($uid);
		if ($object) {
			return $this->getResponsePayload($this->ProfileObject2ResponseVO($object));
		} else {
			return $this->getErrorResponsePayload('No user');
		}
	}

	protected function getProfileByUid($uid) {
        $profile = null;
        XCube_DelegateUtils::call(
            'Legacy_Profile.GetProfile',
            new XCube_Ref($profile),
            $uid
        );
		return $profile;
	}

	protected function ProfileObject2ResponseVO($object) {
		$profile = new ToolboxVO_ExtendedProfile_Profile();
		$profile->id = $object->get('uid');
        $profile->definitions = array();
        $profile->values = array();
        foreach ($object->mDef as $fld => $defs) {
        	if(!$defs->get('show_form')) continue;
        	
            $num = $defs->get('field_id') - 1;
            $profile->definitions[$num] = array(
                'field_name' => $fld,
                'label' => $defs->get('label'),
            );
            $profile->values[$fld] = $object->get($fld);
        }

		return $profile;
	}
}
?>