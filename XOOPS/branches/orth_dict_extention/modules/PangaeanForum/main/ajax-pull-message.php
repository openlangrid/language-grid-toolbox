<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
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
require_once dirname(__FILE__).'/../class/manager/post-manager.php';
require_once dirname(__FILE__).'/../class/page/bbs-ajax-pull-message-page.php';
require_once dirname(__FILE__).'/../class/database/dao/dao-factory.php';

// debug
define('AJAX_PULL_DEBUG_MODE', false);

$return = array(
	'status' => 'SUCCESS',
	'message' => '',
	'contents' => array()
);

try {
	$issetCheckParameters = array(
		'offset', 'topicId', 'limit', 'timestamp'
	);
	foreach($issetCheckParameters as $p) {
		if (!isset($_POST[$p])) {
			throw new Exception($p.' is required.');
		}
	}

	$daoFactory = DAOFactory::getInstance();
	$user = Toolbox::getCurrentUser();
	$dao = $daoFactory->createTopicAccessLogDAO($_POST['topicId'], $user->getId());
	$dao->doLogging();

	$page = new BBSAjaxPullMessagePage($_POST['topicId'], $_POST['offset']
							, $_POST['limit'], $_POST['timestamp']);
	if (!$page->validate()) {
		throw new Exception($page->getErrorMessage());
	}
	$posts = $page->getPosts();
	$result = $page->getResults();

	// user
	$users = $dao->getOnlineUsers();
//	var_dump($users);
	$onlineUsers = array();
	foreach ($users as $user) {
		$onlineUsers[] = $user->toArray();
	}

function cmp($a, $b)
{
	$a = ($a['name'] != '') ? $a['name'] : $a['fullName'];
	$b = ($b['name'] != '') ? $b['name'] : $b['fullName'];
    return strnatcmp($a, $b);
}

	usort($onlineUsers, 'cmp');

	$return['contents'] = array(
		'messages' => $posts,
		'results' => $result,
		'timestamp' => $page->getMaxTimestamp(),
		'pager' => $page->getPager($result),
		'onlineUsers' => $onlineUsers
	);
} catch (SQLException $e) {
	$return['status'] = 'ERROR';
	$return['message'] = 'System error occured.';
} catch (Exception $e) {
	$return['status'] = 'ERROR';
	$return['message'] = $e->getMessage();
}

echo json_encode($return);

?>