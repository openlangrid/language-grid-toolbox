<?php
class ShopHeader{

	private $mod_url;
	private $resourceName;
	private $myPage;
	private $header;
	private $divHeader;

	const CATEGORY = 'category';
	const QUESTION = 'question';
	const LANG = 'lang';
	const ANSWER = 'answer';

	public function __construct($myPage,$resourceName){
		$this->mod_url = XOOPS_URL.'/modules/'.$GLOBALS['mydirname'];

		$this->myPage = $myPage;
		$this->resourceName = $resourceName;

		$styleArray = array();

		switch ($myPage){

			case self::CATEGORY:
				$styleArray = array('top');
				break;
			case self::QUESTION;
				$styleArray = array('list');
				break;
			case self::LANG;
				$styleArray = array('select');
				break;
			case self::ANSWER;
				$styleArray = array('detail','answer_show');
				break;
		}

		$this->makeMyHeader($styleArray);
		$this->makeDivHeader();
	}

	private function makeMyHeader($styleArray){
		$this->header = <<< HEADER
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<link href="{$this->mod_url}/css/qacommon.css" rel="stylesheet" type="text/css" />
HEADER;

	   foreach ($styleArray As $css){

	   	$this->header .= "	<link href=\"{$this->mod_url}/css/{$css}.css\" rel=\"stylesheet\" type=\"text/css\" />\n";

	   }

		$this->header .= '<script type="text/javascript" src="'.XOOPS_URL.'/common/lib/prototype.js"></script>';
		$this->header .= '<script type="text/javascript" src="'.XOOPS_MODULE_URL.'/'.$GLOBALS['mydirname'].'/js/pager.js"></script>';

		if ($this->myPage == self::ANSWER){

			$this->header .= "	<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=false\"></script>\n";

			$this->header .= "	<script type=\"text/javascript\">".
							 "	//<![CDATA[\n".
							 "		Event.observe(window, \"load\", function() {\n".
							 "			var pager = new Pager(\"list_container\", 1, initTermLink);\n\n".
							 "			// #BackButton\n".
							 "			$(\"BackButton\").observe(\"click\", function(event) {\n".
							 "				event.stop();\n".
							 "				history.back();\n".
							 "			});\n".
							 "			// #PrintButton\n".
							 "			$(\"PrintButton\").observe(\"click\", function(event) {\n".
							 "				event.stop();\n".
							 "				print();\n".
							 "			});\n".
							 "		});\n".
							 "	//]]>\n".
							 "	</script>\n";
		} elseif ($this->myPage == self::QUESTION) {
			$this->header .= "	<script type=\"text/javascript\">\n".
							 "		<!--//\n".
							 "			Event.observe(window, \"load\", function()  {\n".
							 "				Pager.prototype.redraw = function(link) {\n".
							 "					var container = this.clickedContainer;\n".
							 "					var targetPage = this.cache[container.id][link.href];\n".
							 "					if (targetPage) {\n".
							 "						hideAllPages();\n".
							 "						targetPage.show();\n".
							 "					} else {\n".
							 "						var pager = this;\n".
							 "						new Ajax.Request(link.href, {\n".
							 "							method : \"get\",\n".
							 "							onComplete : function(httpRequest, json) {\n".
							 "								hideAllPages();\n".
							 "								container.insert(httpRequest.responseText);\n".
							 "								container.childElements().each(function (page) {\n".
							 "									pager.cache[container.id][page.id] = page;\n".
							 "								});\n".
							 "								pager.callback(link.href);\n".
							 "								pager.link_click();\n".
							 "							}\n".
							 "						});\n".
							 "					}\n".
							 "					function hideAllPages() {\n".
							 "						container.childElements().each(function(page) {\n".
							 "							page.hide();\n".
							 "						});\n".
							 "					}\n".
							 "				};\n".
							 "				var pager = new Pager(\"CategoryArea\", 3, init_list);\n".
							 "				$(\"CategoryArea\").childElements().each(function (elm) {\n".
                             "					pager.cache[\"CategoryArea\"][elm.id] = elm;\n".
							 "				});\n".
							 "			});\n".
							 "			function init_list() {\n".
							 "			}\n".
							 "		//-->\n".
							 "	</script>\n";
        } else {
			$this->header .= "	<script type=\"text/javascript\">\n".
							 "		<!--//\n".
							 "			Event.observe(window, \"load\", function()  {\n".
							 "				var pager = new Pager(\"CategoryArea\", 3, init_list);\n".
							 "			});\n".
							 "			function init_list() {\n".
							 "			}\n".
							 "		//-->\n".
							 "	</script>\n";
		}
		$this->header .= "</head>\n";

	}

	private function makeDivHeader(){
		$selectLangUrl = "{$this->mod_url}/lang/?action=list&resourceName={$this->resourceName}&mainLang={$_COOKIE['selectedLanguage']}";
		$homeUrl = "{$this->mod_url}/category/?action=list&resourceName={$this->resourceName}";
		$selectLangClass = "";
		$homeClass = "";
		$jsVoid = "javascript:;";

		if ($this->myPage == self::LANG){
			$selectLangUrl = $jsVoid;
			$selectLangClass = "down";
		}

		if ($this->myPage == self::CATEGORY){
			$homeUrl = $jsVoid;
			$homeClass = "down";

		}

		$this->divHeader = 	"<div id=\"header\">\n".
							"	<h1>\n".
							"		<span class=\"main\">{$this->resourceName}</span>\n".
							"		<span class=\"sub\"></span>\n".
							"	</h1>\n";

		$this->divHeader .= "	<div id=\"HeaderButtonArea\">\n".
      						"		<a id=\"LanguageButton\" name=\"LanguageButton\" class=\"GlobalButton {$selectLangClass} \" href=\"{$selectLangUrl}\">\n".
      						"			<span class=\"left\"></span>\n".
      						"			<span class=\"center\">\n".
      						"				<span id=\"LanguageIcon\"></span>\n".
      						"				<span class=\"main\">".STF_LABEL_SELECTLANGUAGE."</span>\n".
      						"				<span class=\"sub\"></span>\n".
      						"			</span>\n".
      						"			<span class=\"right\"></span>\n".
      						"		</a>\n";
		$this->divHeader .=  "		<a id=\"HomeButton\" name=\"HomeButton\" class=\"GlobalButton {$homeClass}\" href=\"{$homeUrl}\">\n".
							"			<span class=\"left\"></span>\n".
							"			<span class=\"center\">\n".
							"				<span id=\"HomeIcon\"></span>\n".
							"				<span class=\"main\">".STF_LABEL_HOME."</span>\n".
							"				<span class=\"sub\"></span>\n".
							"			</span>\n".
							"			<span class=\"right\"></span>\n".
							"		</a>\n".
							"	</div>\n".
							"</div>\n";
	}

	public function getHeader(){
		return $this->header;
	}

	public function getDivHeader(){
		return $this->divHeader;
	}
}
