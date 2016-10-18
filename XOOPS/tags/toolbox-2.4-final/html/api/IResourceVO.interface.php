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

class ToolboxVO_Resource_LanguageResource {
	var $name;
	var $type;
	var $languages;
	var $readPermission;
	var $editPermission;
	var $creator;
	var $lastUpdate;
	var $entryCount;
	var $isDeploy;
}
class ToolboxVO_Resource_Expression {
	var $language;
	var $expression;
}
class ToolboxVO_Resource_Permission {
	var $type;
	var $userId;
	var $groupId;
}
class ToolboxVO_Dictionary_DictionaryRecord {
	var $id;
	var $expressions;
	var $priority;
}
class ToolboxVO_ParalleText_ParallelTextRecord {
	var $id;
	var $expressions;
}

class ToolboxVO_QA_QARecord {
	public $id;				// int
	public $question;		// ToolboxVO_Resource_Expression[]
	public $answers;		// ToolboxVO_QA_Answer[][]
	public $categoryIds;	// int[]
	public $creationDate;
	public $updateDate;
}

class ToolboxVO_QA_Answer {
	public $id = null;		// int
	public $expression = array();	// ToolboxVO_Resource_Expression[]
	public $creationDate = 0;
}

class ToolboxVO_QA_QACategory {
	public $id;				// int
	public $language;		// String
	public $name;			// ToolboxVO_Resource_Expression[]
	public $qCount;			// int
}

class ToolboxVO_Glossary_GlossaryRecord {
	public $id;				// int
	public $term;			// ToolboxVO_Resource_Expression[]
	public $definition;		// ToolboxVO_Resource_Expression[][]
	public $categoryIds;	// int[]
	public $creationDate;
	public $updateDate;
}

class ToolboxVO_Glossary_Definition {
	public $id = null;		// int
	public $expression = array();	// ToolboxVO_Resource_Expression[]
}

class ToolboxVO_Glossary_GlossaryCategory {
	public $id;				// int
	public $name;			// ToolboxVO_Resource_Expression[]
}

class ToolboxVO_TranslationTemplate_TranslationTemplateRecord {
	public $id;				// int
	public $expressions;	// ToolboxVO_Resource_Expression[]
	public $wordSetIds;		// int[]
	public $categoryIds;	// int[]
	public $creationDate;	// Date(int)
	public $upateDate;		// Date(int)
}

class ToolboxVO_TranslationTemplate_BoundWordSet {
	public $id;				// int
	public $name;			// ToolboxVO_Resource_Expression[]
	public $type;			// String
	public $words;			// ToolboxVO_TranslationTemplate_BoundWord[]
	public $recordCount;	// int
}

class ToolboxVO_TranslationTemplate_BoundWord {
	public $id;				// int
	public $expressions;	// ToolboxVO_Resource_Expression[]
}

class ToolboxVO_TranslationTemplate_TranslationTemplateCategory {
	public $id;				// id
	public $name;			// ToolboxVO_Resource_Expression[]
	public $recordCount;	// int
}

?>
