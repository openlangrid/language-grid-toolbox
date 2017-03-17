<?php
error_reporting(E_ALL);
require '../../../mainfile.php';
require_once XOOPS_ROOT_PATH.'/modules/communication/config.php';
require_once XOOPS_ROOT_PATH.'/modules/communication/helper.php';

//include XOOPS_TRUST_PATH.'/modules/communication/test/com-message-simple.php';
//include XOOPS_TRUST_PATH.'/modules/communication/test/com-message.php';
//include XOOPS_TRUST_PATH.'/modules/communication/test/com-attach-content.php';

//include XOOPS_TRUST_PATH.'/modules/communication/test/file.php';
//include XOOPS_TRUST_PATH.'/modules/communication/test/com-content.php';


/*
require_once XOOPS_TRUST_PATH.'/modules/communication/class/com-content-list.php';

require_once XOOPS_TRUST_PATH.'/modules/communication/class/com-topic.php';
require_once XOOPS_TRUST_PATH.'/modules/communication/class/com-forum.php';
require_once XOOPS_TRUST_PATH.'/modules/communication/class/com-category.php';




echo "test start".'<br>';

echo '---------------------------------------------------------<br>';
function testComContentGoogleMap() {
	$content = Com_ContentGoogleMap::createWithParams(1, array(
		'content_title' => 'testタイトル',
		'uid' => 1,
		'mime_type' => 'image/jpeg',
		'file_name' => 'testファイル',
		'data' => 'aaaaaa'
	));
	return $content->insert();
}
//echo testComContentGoogleMap().'<br>';

echo '---------------------------------------------------------<br>';
function testComContent() {
	//echo Com_Content::findById(33) -> getType().'<br>';
	//echo Com_Content::findById(24) -> getType().'<br>';
	
	foreach(Com_Content::findAllByTopicId(1) as $content) {
		echo $content->getLabelForOption().'<br>';
	}
}
echo testComContent().'<br>';

echo '---------------------------------------------------------<br>';
function testComContentList() {
	$contentList = Com_ContentList::getListByTopicId(1);
	$content = Com_Content::findById(2);
	//$content = null;

	echo $contentList -> prevContentOf($content) -> getLabelForOption();
	echo '<br>';
	if($content && $content -> getType() != 'blank') echo $content -> getLabelForOption();
	echo '<br>';
	echo $contentList -> nextContentOf($content) -> getLabelForOption();
	echo '<br>';
}
echo testComContentList().'<br>';
/*
echo '---------------------------------------------------------<br>';
function testComTopic() {
	$topic = Com_Topic::findByTopicId(1);
	echo $topic -> id.'<br>';
	echo $topic -> getTitleForSelectedLanguage().'<br>';
	echo $topic -> getTitleForLanguage('ja').'<br>';
	echo $topic -> getTitleForLanguage('en').'<br>';
}
echo testComTopic().'<br>';

echo '---------------------------------------------------------<br>';
function testComForum() {
	$forum = Com_Forum::findByForumId(1);
	echo $forum -> id.'<br>';
	echo $forum -> getTitleForSelectedLanguage().'<br>';
	echo $forum -> getTitleForLanguage('ja').'<br>';
	echo $forum -> getTitleForLanguage('en').'<br>';
	$forum1 = Com_Forum::findByForumId(Com_Topic::findByTopicId(1)->getForumId());
	$forum2 = Com_Topic::findByTopicId(1)->getForum();
	echo $forum1 -> getTitleForSelectedLanguage() == $forum2 -> getTitleForSelectedLanguage().'<br>';
}
echo testComForum().'<br>';

echo '---------------------------------------------------------<br>';
function testComCategory() {
	$category = Com_Category::findByCategoryId(1);
	echo $category -> id.'<br>';
	echo $category -> getTitleForSelectedLanguage().'<br>';
	echo $category -> getTitleForLanguage('ja').'<br>';
	echo $category -> getTitleForLanguage('en').'<br>';
	$cat1 = Com_Category::findByCategoryId(Com_Forum::findByForumId(1)->getCategoryId());
	$cat2 = Com_Forum::findByForumId(1)->getCategory();
	echo $cat1 -> getTitleForSelectedLanguage() == $cat2 -> getTitleForSelectedLanguage().'<br>';
}
echo testComCategory().'<br>';

echo '---------------------------------------------------------<br>';
function testComAttachContent() {
	$content = Com_ContentList::getListByTopicId(1) -> firstContent();

	$attach = Com_Attach_Content::createWithParams(array(
		'post_id'      => 1,
		'content_id'   => $content -> getContentId(),
		'image_id'     => null,
		'x_coordinate' => 100,
		'y_coordinate' => 150
	));
	echo $attach -> insert();
	echo '<br>';
	$attach = Com_Attach_Content::findByMessageId(1);
	echo $attach -> update();
	echo '<br>';
	
	$attach = Com_Attach_Content::createWithParams(array(
		'post_id'      => 1,
		'content_id'   => $content -> getContentId(),
		'image_id'     => null,
		'x_coordinate' => 100,
		'y_coordinate' => 150
	));
	echo $attach -> insert();
	echo '<br>';
	
	$attach = Com_Attach_Content::findByMessageId(1);
	$attach -> setParams(array(
		'x_coordinate' => 200, 'y_coordinate' => 250
	));
	echo $attach -> update();
	echo '<br>';


}
echo testComAttachContent().'<br>';


echo "test end";
*/
?>