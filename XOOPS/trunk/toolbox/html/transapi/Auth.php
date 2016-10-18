<?php
require_once "../mainfile.php";

switch (true) {
    case !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']):
        header('WWW-Authenticate: Basic realm="Enter username and password."');
        header('Content-Type: text/plain; charset=utf-8');
        die('Authorization failed.');
}

function getCurrentUserId(){
        $userId = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        $db=Database::getInstance();
        $tbl=$db->prefix('users');
        $db->prepare("select uid from " . $tbl . " where uname = ? and pass = ?");
        $db->bind_param("ss", $userId, md5($password));
        $result = $db->execute();
        while ($row = $db->fetchArray($result)) {
                return $row["uid"];  // get first row
        }
	header("HTTP/1.0 403");
	die("Authorization failed.");
}
