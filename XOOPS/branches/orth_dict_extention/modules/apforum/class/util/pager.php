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
class Pager {

	private $options = array();

	public function __construct($options) {
		$this->options = array_merge(
		array(
				'currentPage' => 1,
				'perPage' => 20,
				'delta' => 5,
				'totalItems' => 0
		),
		$options
		);

		$this->options['totalPages'] = ceil($this->options['totalItems'] / $this->options['perPage']);

		if(intval($this->options['currentPage']) <= 0){
			$this->options['currentPage'] = 1;
		}
		if(intval($this->options['currentPage']) > $this->options['totalPages']){
			$this->options['currentPage'] = $this->options['totalPages'];
		}
	}
	public function hasPreview() {
		return ($this->options['currentPage'] > 1);
	}
	public function getPreviewNumber() {
		return $this->options['currentPage'] - 1;
	}
	public function hasNext() {
		return ($this->options['currentPage'] < $this->options['totalPages']);
	}
	public function getNextNumber() {
		return $this->options['currentPage'] + 1;
	}
	public function getCurrentPage() {
		return $this->options['currentPage'];
	}
	public function getTotalItems() {
		return $this->options['totalItems'];
	}
	public function getTotalPages() {
		return $this->options['totalPages'];
	}
	public function getPerPage() {
		return $this->options['perPage'];
	}
	public function toArray() {
		$return = array();

		$before = $this->options['currentPage'] - 1;
		$after = $this->options['totalPages'] - $this->options['currentPage'];

		for ($i = 1; $i <= $this->options['totalPages']; $i++) {

			if ($this->options['totalPages'] > $this->options['delta'] * 2 + 3) {
				if ($before >= $this->options['delta'] + 2 && $i == 2) {
					$return[$i] = 'SKIP';
					$i = $this->options['currentPage'] - $this->options['delta'];
					continue;
				}
				if ($after >= $this->options['delta'] + 2 && $i == $this->options['currentPage'] + $this->options['delta'] - 1) {
					$return[$i] = 'SKIP';
					$i = $this->options['totalPages'] - 1;
					continue;
				}
			}

			if ($i == $this->options['currentPage']) {
				$return[$i] = 'CURRENT';
			} else {
				$return[$i] = $i;
			}
		}
		return $return;
	}

	/**
	 *
	 * @param int $topicId
	 * @return String html
	 */
	public function toHtml($topicId) {
		$html = array();
		$count = 0;
		if ($this->getTotalPages() < 2) {
			return '';
		}
		$html[] = '<div class="page_index" style="clear: both;">';
		$html[] = '<div class="bbs-pager" style="padding: 15px 0;">';
		$html[] = '<ul class="clearfix" style="width: #{width}px;">';
		if ($this->hasPreview()) {
			$html[] = '<li><a href="./?topicId='.$topicId.'&page='.$this->getPreviewNumber().'&view='.$this->getPerPage().'">&lt;&lt; Previous</a></li>';
		}
		foreach ($this->toArray() as $key => $item) {
			$html[] = '<li>';
			if ($item == 'CURRENT') {
				$html[] = '<span>'.$this->getCurrentPage().'</span>';
			} else if ($item == 'SKIP') {
				$html[] = '<span>...</span>';
			} else {
				$html[] = '<a href="./?topicId='.$topicId.'&page='.$item.'&view='.$this->getPerPage().'">'.$item.'</a>';
			}
			$html[] = '</li>';
			$count++;
		}
		if ($this->hasNext()) {
			$html[] = '<li><a href="./?topicId='.$topicId.'&page='.$this->getNextNumber().'&view='.$this->getPerPage().'">Next &gt;&gt;</a></li>';
		}
		$html[] = '</ul>';
		$html[] = '</div>';
		$html[] = '</div>';

		$html = implode('', $html);
		$width = $count * 40 + 200;
		$html = str_replace('#{width}', $width, $html);

		return $html;
	}
}

?>
