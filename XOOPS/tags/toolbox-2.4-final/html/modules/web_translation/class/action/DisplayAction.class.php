<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

require_once APP_ROOT_PATH.'/class/action/AbstractAction.class.php';
class DisplayAction extends AbstractAction {
	
	private $source;
	private $target;
	private $sourceLang;
	private $targetLang;
	public function execute() {
		$this->source = $this->getParameter('sourceKey');
		$this->target = $this->getParameter('targetKey');
		$this->sourceLang = $this->getParameter('sourceLang');
		$this->targetLang = $this->getParameter('targetLang');
	}
	
	public function executeView() {
		$sourceUrl = XOOPS_URL.'/modules/'.APP_DIR_NAME.'/?action=displayCache&key='.$this->source;
		$targetUrl = XOOPS_URL.'/modules/'.APP_DIR_NAME.'/?action=displayCache&key='.$this->target;
		header('Content-Type: text/html; charset=utf-8;');
		echo '<html><head>';
		echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';
		echo '<title>'.'</title></head>';
		echo '<frameset rows="30,*" frameborder=1 framespacing=0 cols="*" id="frameSet">';
		echo '<frame src="./templates/web_translation_header.php?sourceKey='.urlencode($sourceUrl).'&targetKey='.urlencode($targetUrl).'&sourceLang='.$this->sourceLang.'&targetLang='.$this->targetLang.'" marginwidth=0 marginheight=0>';
		echo '<frame src="'.$targetUrl.'" id="targetFrame" name="frame">';
		echo '<noframes></noframes></frameset></html>';		
		die();
	}
}
?>