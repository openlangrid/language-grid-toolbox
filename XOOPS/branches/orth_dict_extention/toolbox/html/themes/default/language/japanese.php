<?php
//global
define('_HD_ERROR', 'エラー');
define('_HD_BREADCRUMBS', 'サイト内の現在位置です：');
define('_HD_SKIP_CONTENTS', 'ページの先頭です。本文を読み飛ばして、このサイトのメニューなどを読む');
define('_HD_SKIP_CONTENTS_TARGET', 'ここまでがこのページの内容です。ここからはサイトの共通メニューなどです。');
define('_HD_SKIP_PAGETOP', 'ページの終端です。ページの先頭に戻る');
define('_HD_CHANGE_VIEWMODE', '通常のレイアウトで閲覧する');
define('_HD_SITE_CLOSED', '閉鎖中');
define('_HD_EDIT_FOOTER', 'フッタを編集');
define('_HD_CLOSE_WINDOW', 'このウィンドウを閉じる');
define('_HD_BACK_TO_FRONTPAGE', 'トップページに戻る');
define('_HD_INPUTHELPER','入力支援ON/OFF');

//comment
define('_CM_THREAD_TITLE','このコメントへの返信');
define('_CM_POSTCOMMENT_NEW','コメントを新規投稿する');
define('_CM_THREAD_CHOOSE','スレッド表示');
define('_CM_THREAD_EXPLANATION','現在はツリー構造順で表示しています');
define('_CM_FLAT_CHOOSE','時系列で表示');
define('_CM_FLAT_EXPLANATION','現在はスレッドで表示しています');
define('_CM_TREE_CHOOSE','ツリー構造で表示');
define('_CM_TREE_EXPLANATION','現在は時系列順で表示しています');
define('_CM_OLDER_CHOOSE','古いものから');
define('_CM_NEWER_CHOOSE','新しいものから');
define('_CM_DELETE_TITLE','コメント削除の確認');
define('_CM_ICON_NORMAL','通常');
define('_CM_ICON_DISSATISFACTION','不満');
define('_CM_ICON_SATISFACTION','満足');
define('_CM_ICON_LOWER','不支持');
define('_CM_ICON_UPPER','支持');
define('_CM_ICON_REPORT','報告');
define('_CM_ICON_QUESTION','質問');

//notification
define('_NOT_DELETINGNOTIFICATIONS_EXPLANATION', '選択されたイベントを削除する場合は、「削除ボタン」を押してください。');

//search
define('_SR_SEARCHRESULTS_BY_MODULES','セクションごとの検索結果');
define('_SR_SEARCHRESULTS_SHOWALL','すべての検索結果');
define('_SR_SEARCHRESULTS_BY_USER','%s の検索結果（作成者：%s）');
define('_SR_SEARCHRESULTS_NO_RESULT','該当する項目がありません。');

//user.php
define('_US_LOGGINGU','%sさん、ようこそ。');// no need?
define('_MD_LEGACY_MESSAGE_LOGIN_SUCCESS','{0}さん、ようこそ。');
define('_US_LOGOUT_CONFIRM','ログアウトしてもよろしいですか？');
define('_MD_USER_LANG_WHOSONLINE','ユーザ名をクリックすると権限があればユーザ情報ページを閲覧できます');
define('_THEME_XOOPSCONTENT_USERINFO','ユーザ情報は一般非公開です。閲覧するためには<a href="user.php">ログインしてください</a>。');
define('_MI_CUBE_UTILS_LANG_SSL','SSL ログイン');
define('_MD_USER_LANG_TYPEPASSTWICE','パスワードを変更する場合のみ記入してください');
define('_HD_USER_REGISTRATION_SUCCEEDED','ユーザ登録完了');
define('_MD_USER_LANG_LOSTPASS','パスワード紛失');
define('_MD_USER_LANG_DISPLAY_LANGUAGE', "表示言語");
define('_MD_USER_LANG_CREATE_NEW_ACCOUNT', '新規会員登録');
define('_MD_USER_LANG_CONFIRM_NEW_ACCOUNT', '会員登録確認');
define('_MD_USER_LANG_ACCOUNT_COMPLETION', '会員登録完了');


define('_MD_USER_LANG_GROUP_KEY', "グループキー");
define('_MD_USER_LANG_INDICATEDY', "※表示されます");
define('_MD_USER_ERROR_GROUPKEY', "グループキーが違います");


//legacy
define("_NOT_ACTIVENOTIFICATIONS",  "イベント通知機能");//add
define("_NOT_ACTIVENOTIFICATIONS_EXPLANATION",  "現在、以下のイベント通知が有効になっています。チェックして「削除」をすることで、イベント通知を無効にできます。");//add
define("_NOT_ACTIVENOTIFICATIONS_EXPLANATION_NON",  "イベント通知項目は設定されていません。");//add
define("_MB_LEGACY_SEND_PM",  "alt=\"プライベートメッセージを送る\"");
define("_MB_LEGACY_THEME_BLOCKTITLE", "テーマ選択");
define("_MB_LEGACY_THEME_IMGCHANGE_CANNOT", "JavaScript が有効でないのでテーマのサムネイルは閲覧できません");
define("_MB_LEGACY_RECO_CANNOT", "JavaScript が有効でないのでサイト推薦の機能は利用できません");
define("_MB_LEGACY_DHTMLTEXTAREA_SKIP_START", "テキストエリアの編集支援機能を読み飛ばす");
define("_MB_LEGACY_DHTMLTEXTAREA_SKIP_END", "ここからがテキストエリアです");
define("_MB_LEGACY_DHTMLTEXTAREA_CANNOT", "JavaScript が有効でないので編集支援の機能は利用できません");
define("_MB_LEGACY_SMAILY_CANNOT", "JavaScript が有効でないのでスマイリー入力支援の機能は利用できません。スマイリー変換機能が有効であれば、次のような文字列を入力することで、スマイリー画像に変換されます（ :-D, :-), :-(, :-o, :-?, 8-), :lol:, :-x, :-P ）");

//pm
define('_THEME_PM_MESSAGE_SENT','送信完了');

//Image Manager
define( '_THEME_IMAGEMANAGER_CATEGORY', "カテゴリ");
define( '_THEME_IMAGEMANAGER_NOPERM', "カテゴリを選択してください。");
define( '_THEME_IMAGEMANAGER_NOIMAGE', "このカテゴリにはファイルがありません。");
define( '_THEME_IMAGEMANAGER_NOCAT_GUEST', "イメージマネージャが有効でないため利用できません。サイト管理者にお問い合わせください。");
define( '_THEME_IMAGEMANAGER_NOCAT_ADMIN', "イメージマネージャが有効でないため利用できません。イメージマネージャを利用するためには<a href=\"#\" onclick=\"javascript:window.opener.location='%s/modules/legacy/admin/index.php?action=ImagecategoryList';window.close();\">まずカテゴリを作ってください</a>。");

//admin side
define( '_THEME_ADMIN_BLOCKS', "ブロック管理");
define( '_THEME_ADMIN_GENERAL', "全般設定");
define( '_THEME_ADMIN_MODULES', "モジュール管理");
define( '_THEME_ADMIN_USERS', "ユーザ管理");
define( '_THEME_ADMIN_TITLE', "Administration Mode");

//pagetitle
define( '_THEME_PAGETITLE_EDITPROFILE', 'プロフィールの編集' );
define( '_THEME_PAGETITLE_REGISTER', 'ユーザ登録' );
define( '_THEME_PAGETITLE_LOGIN', 'ログイン' );
define( '_THEME_PAGETITLE_LOSTPASS', 'パスワード・リマインダ' );
define( '_THEME_PAGETITLE_USERINFO', 'アカウント情報' );

define( '_THEME_PAGETITLE_SEARCH', '高度な検索' );
define( '_THEME_PAGETITLE_SEARCH_RESULT', '検索結果' );
define( '_THEME_PAGETITLE_SEARCH_RESULT_SHOWALL', 'すべての検索結果' );
define( '_THEME_PAGETITLE_SEARCH_RESULT_SHOWALLBYUSER', '特定ユーザの検索結果' );
define( '_THEME_PAGETITLE_NOTIFICATIONS', 'イベント通知機能' );
define( '_THEME_PAGETITLE_NOTIFICATIONS_CONFIRM', 'イベント削除の確認' );

define( '_THEME_PAGETITLE_CLICKASMILIE', "顔アイコン(スマイリー)選択");

define( '_THEME_PAGETITLE_PM_ERROR', "プライベートメッセージ送信エラー");
define( '_THEME_PAGETITLE_PM_SENT', "プライベートメッセージを送信");
define( '_THEME_PAGETITLE_READPMSG', 'プライベートメッセージ [%d]' );
define( '_THEME_PAGETITLE_DELPMSG', 'プライベートメッセージ [%d] の削除確認' );

define( '_THEME_PAGETITLE_TELLAFRIEND', '友達に教える' );
define( '_THEME_PAGETITLE_TELLAFRIEND_ERROR', '送信エラー' );
define( '_THEME_PAGETITLE_TELLAFRIEND_SENT', '友達に教えました' );

define( '_THEME_PAGETITLE_ONLINE', 'オンライン状況' );

define( '_THEME_PAGETITLE_IMAGEMANAGER', "イメージマネージャ");
define( '_THEME_PAGETITLE_IMAGEMANAGER_UPLOAD', "イメージマネージャ：アップローダ");


define('_THEME_TOP_UNAME_LB', 'ユーザ名');
define('_THEME_TOP_PASSWD_LB', 'パスワード');
define('_THEME_TOP_AUTOLOGIN_LB', '次回から自動的にログイン ');
define('_THEME_TOP_LOGIN_LB', 'ログイン');
define('_THEME_TOP_LOGOUT_LB', 'ログアウト');
define('_THEME_TOP_NEW_USER_LB', '新規会員登録');
?>
