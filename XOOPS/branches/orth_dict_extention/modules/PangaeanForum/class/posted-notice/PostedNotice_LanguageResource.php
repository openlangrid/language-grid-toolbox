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
/* $Id: PostedNotice_LanguageResource.php 4540 2010-10-07 04:01:57Z uehara $ */
/*
 * １回のリクエスト処理で複数ユーザにメールを送信するため、Define定義のリソース管理機構は利用できない。
 * ＊ユーザごとに言語が違う。
 * ＊Defineは定数なので、同じ定義の定数を重複してロードできない。（当然といえば当然だ。）
 */

class PostedNotice_LanguageResource {

	private $defaultLang = 'en';

	private $mSubject = array(
		'en' => 'New messages in Toolbox{IF_SINGLE_MSG}\'{TOPIC}\'has new message.',
		'ja' => 'Toolboxに新しいメッセージがあります{IF_SINGLE_MSG}\'{TOPIC}\' にメッセージが投稿されました'
	);

	private $mHeader = array(
		'en'      => 'The following message was posted.',
		'ja'      => '以下のメッセージが投稿されました。'
	);

	private $mBody = array(
		'en'      => '{BR}=============================={BR}Written By {AUTHOR}{BR}=============================={BR}{BR}{BODY}{BR}URL: {TOPIC_LINK}{BR}{BR}------------------------------{BR}[{DATE}]{BR}{BR}{CATEGORY}{BR}-> {FORUM}{BR}--> {TOPIC}{BR}{BR}',
		'ja'      => '{BR}=============================={BR}{AUTHOR} さんの書込み{BR}=============================={BR}{BR}{BODY}{BR}URL: {TOPIC_LINK}{BR}{BR}------------------------------{BR}[{DATE}]{BR}{BR}{CATEGORY}{BR}-> {FORUM}{BR}--> {TOPIC}{BR}{BR}'
	);

	private $mDateFormat = array(
		'en'      => 'd M Y H:i',
		'ja'      => 'Y/m/d H:i'
	);

	public function getSubject($language) {
		return $this->_get($language, $this->mSubject);
	}

	public function getHeader($language) {
		return $this->_get($language, $this->mHeader);
	}

	public function getBody($language) {
		return $this->_get($language, $this->mBody);
	}

	public function getDateFormat($language) {
		return $this->_get($language, $this->mDateFormat);
	}

	private function _get($language, $array) {
		if (isset($array[$language])) {
			return $array[$language];
		} else {
			return $array[$this->defaultLang];
		}
	}
}
?>
