<?php
$renderOption['type'] = 'noheader';

// question ID
$questionId = @$_GET['questionId'];
if ($questionId < 1) {
	exit('questionId is required.');
}

$factory = new ShopQuestionFactory();
$question = $factory->findById($questionId);
if (!$question) {
	exit;
}

// page
$page = @$_GET['page'];
if (!$page) {
	$page = 1;
}

// get answers
$answers = ShopAnswerList::findByQuestion($question, $page);
$answer = $answers->getCurrentAnswer();
if (!$answer) {
	exit;
}

// resource name
$resourceName = @$_GET['resourceName'];

// highlighter
$languageManager = new BilingualManager();
$highlighter = new TermHighlighter($languageManager, $resourceName, $answer);

// for <div>'s ID
$_showUrl = "{$xoopsTpl->get_template_vars('mod_url')}/" . SUB_MODULE . "/?action=_show&resourceName=" . $resourceName;

$xoopsTpl->assign(array(
	'_showUrl' => $_showUrl,
	'pager' => $answers,
	'answer' => $answer,
	'highlighter' => $highlighter,
	'question' => $question
));
