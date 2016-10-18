<?php
// Module Info

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'FSHARE_MI_LOADED' ) ) {

define( 'FSHARE_MI_LOADED' , 1 ) ;

// *** Used by Toolbox *** 
// The name of this module
define('_MD_ALBM_FSHARE_NAME', '[en]File sharing[/en][ja]ファイル共有[/ja]');
// A brief description of this module
define('_MD_ALBM_FSHARE_DESC', 'ファイルをアップロードし、共有するためのモジュールです。');

define('_MD_ALBM_CFG_DEFAULTORDER', 'フォルダ表示でのデフォルト表示順');
define('_MD_ALBM_CFG_FSIZE', '最大ファイルサイズ');
define('_MD_ALBM_CFG_DESCFSIZE', 'アップロード時のファイルサイズ上限（MB）<br />（サーバで設定されたサイズより大きいファイルはアップロードできません）');
//20100129 add
define('_MD_ALBM_CFG_ROOT_EDIT', 'ルートフォルダ編集権限');
define('_MD_ALBM_OPT_EDIT_ADMIN', '管理者');
define('_MD_ALBM_OPT_EDIT_ALL', '全て');


// *** Use is not confirmed by Toolbox ***
define( "_MD_ALBM_OPT_USENAME" , "名前" ) ;
define( "_MD_ALBM_OPT_USEUNAME" , "ユーザID" ) ;

// Names of blocks for this module (Not all module has blocks)
define("_MD_ALBM_BNAME_RECENT","最近のファイル");
define("_MD_ALBM_BNAME_HITS","人気ファイル");
define("_MD_ALBM_BNAME_RANDOM","ピックアップファイル");
define("_MD_ALBM_BNAME_RECENT_P","最近のファイル(画像付)");
define("_MD_ALBM_BNAME_HITS_P","人気ファイル(画像付)");

// Config Items
define( "_MD_ALBM_CFG_FILESPATH" , "ファイルの保存先ディレクトリ" ) ;
define( "_MD_ALBM_CFG_DESCFILESPATH" , "XOOPSインストール先からのパスを指定（最初の'/'は必要、最後の'/'は不要）<br />Unixではこのディレクトリへの書込属性をONにして下さい" ) ;
define( "_MD_ALBM_CFG_THUMBSPATH" , "サムネイルファイルの保存先ディレクトリ" ) ;
define( "_MD_ALBM_CFG_DESCTHUMBSPATH" , "「ファイルの保存先ディレクトリ」と同じです" ) ;
define( "_MD_ALBM_CFG_POPULAR" , "'POP'アイコンがつくために必要なヒット数" ) ;
define( "_MD_ALBM_CFG_NEWDAYS" , "'new'や'update'アイコンが表示される日数" ) ;
define( "_MD_ALBM_CFG_NEWFILES" , "トップページで新規ファイルとして表示する数" ) ;
define( "_MD_ALBM_CFG_PERPAGE" , "1ページに表示されるファイル数" ) ;
define( "_MD_ALBM_CFG_DESCPERPAGE" , "選択可能な数字を | で区切って下さい<br />例: 10|20|50|100" ) ;
define( "_MD_ALBM_CFG_ALLOWNOIMAGE" , "ファイルのない投稿を許可する" ) ;
define( "_MD_ALBM_CFG_MAKETHUMB" , "サムネイルを作成する" ) ;
define( "_MD_ALBM_CFG_DESCMAKETHUMB" , "「生成しない」から「生成する」に変更した時には、「サムネイルの再構築」が必要です。" ) ;
define( "_MD_ALBM_CFG_THUMBSIZE" , "サムネイル画像サイズ(pixel)" ) ;
define( "_MD_ALBM_CFG_THUMBRULE" , "サムネイル生成法則" ) ;
define( "_MD_ALBM_CFG_WIDTH" , "最大画像幅" ) ;
define( "_MD_ALBM_CFG_DESCWIDTH" , "ファイルアップロード時に自動調整されるメイン画像の最大幅。<br />GDモードでTrueColorを扱えない時には単なるサイズ制限" ) ;
define( "_MD_ALBM_CFG_HEIGHT" , "最大画像高" ) ;
define( "_MD_ALBM_CFG_DESCHEIGHT" , "最大幅と同じ意味です" ) ;
define( "_MD_ALBM_CFG_MIDDLEPIXEL" , "シングルビューでの最大画像サイズ" ) ;
define( "_MD_ALBM_CFG_DESCMIDDLEPIXEL" , "幅x高さ で指定します。<br />（例 480x480）" ) ;
define( "_MD_ALBM_CFG_ADDPOSTS" , "ファイルを投稿した時にカウントアップされる投稿数" ) ;
define( "_MD_ALBM_CFG_DESCADDPOSTS" , "常識的には0か1です。負の値は0と見なされます" ) ;
define( "_MD_ALBM_CFG_CATONSUBMENU" , "サブメニューへのトップカテゴリーの登録" ) ;
define( "_MD_ALBM_CFG_NAMEORUNAME" , "投稿者名の表示" ) ;
define( "_MD_ALBM_CFG_DESCNAMEORUNAME" , "名前かユーザIDか選択して下さい" ) ;
define( "_MD_ALBM_CFG_VIEWCATTYPE" , "一覧表示の表示タイプ" ) ;
define( "_MD_ALBM_CFG_COLSOFTABLEVIEW" , "テーブル表示時のカラム数" ) ;
define( "_MD_ALBM_CFG_ALLOWEDEXTS" , "アップロード許可するファイル拡張子" ) ;
define( "_MD_ALBM_CFG_DESCALLOWEDEXTS" , "ファイルの拡張子を、jpg|jpeg|gif|png のように、'|' で区切って入力して下さい。<br />すべて小文字で指定し、ピリオドや空白は入れないで下さい。<br />意味の判っている方以外は、phpやphtmlなどを追加しないで下さい" ) ;
define( "_MD_ALBM_CFG_ALLOWEDMIME" , "アップロード許可するMIMEタイプ" ) ;
define( "_MD_ALBM_CFG_DESCALLOWEDMIME" , "MIMEタイプを、image/gif|image/jpeg|image/png のように、'|' で区切って入力して下さい。<br />MIMEタイプによるチェックを行わない時には、ここを空欄にします" ) ;
define( "_MD_ALBM_CFG_USESITEIMG" , "イメージマネージャ統合での[siteimg]タグ" ) ;
define( "_MD_ALBM_CFG_DESCUSESITEIMG" , "イメージマネージャ統合で、[img]タグの代わりに[siteimg]タグを挿入するようになります。<br />利用モジュール側で[siteimg]タグが有効に機能するようになっている必要があります" ) ;

define( "_ALBUM_OPT_CALCFROMWIDTH" , "指定数値を幅として、高さを自動計算" ) ;
define( "_ALBUM_OPT_CALCFROMHEIGHT" , "指定数値を高さとして、幅を自動計算" ) ;
define( "_ALBUM_OPT_CALCWHINSIDEBOX" , "幅か高さの大きい方が指定数値になるよう自動計算" ) ;

define( "_MD_ALBM_OPT_VIEWLIST" , "説明文付リスト表示" ) ;
define( "_MD_ALBM_OPT_VIEWTABLE" , "テーブル表示" ) ;


// Sub menu titles
define("_MD_ALBM_TEXT_SMNAME1","投稿");
define("_MD_ALBM_TEXT_SMNAME2","高人気");
define("_MD_ALBM_TEXT_SMNAME3","トップランク");
define("_MD_ALBM_TEXT_SMNAME4","自分の投稿");

// Names of admin menu items
define("_MD_ALBM_FSHARE_ADMENU0","投稿されたファイルの承認");
define("_MD_ALBM_FSHARE_ADMENU1","ファイル管理");
define("_MD_ALBM_FSHARE_ADMENU2","カテゴリ管理");
define("_MD_ALBM_FSHARE_ADMENU_GPERM","各グループの権限");
define("_MD_ALBM_FSHARE_ADMENU3","動作チェッカー");
define("_MD_ALBM_FSHARE_ADMENU4","ファイル一括登録");
define("_MD_ALBM_FSHARE_ADMENU5","サムネイルの再構築");
define("_MD_ALBM_FSHARE_ADMENU_IMPORT","ファイルインポート");
define("_MD_ALBM_FSHARE_ADMENU_EXPORT","ファイルエクスポート");
define("_MD_ALBM_FSHARE_ADMENU_MYBLOCKSADMIN","ブロック・アクセス権限");
define("_MD_ALBM_FSHARE_ADMENU_MYTPLSADMIN","テンプレート管理");

// Text for notifications
define('_MI_FSHARE_GLOBAL_NOTIFY', 'モジュール全体');
define('_MI_FSHARE_GLOBAL_NOTIFYDSC', 'myAlbum-Pモジュール全体における通知オプション');
define('_MI_FSHARE_CATEGORY_NOTIFY', 'カテゴリー');
define('_MI_FSHARE_CATEGORY_NOTIFYDSC', '選択中のカテゴリーに対する通知オプション');
define('_MI_FSHARE_FILE_NOTIFY', 'ファイル');
define('_MI_FSHARE_FILE_NOTIFYDSC', '表示中のファイルに対する通知オプション');

define('_MI_FSHARE_GLOBAL_NEWFILE_NOTIFY', '新規ファイル登録');
define('_MI_FSHARE_GLOBAL_NEWFILE_NOTIFYCAP', '新規にファイルが登録された時に通知する');
define('_MI_FSHARE_GLOBAL_NEWFILE_NOTIFYDSC', '新規にファイルが登録された時に通知する');
define('_MI_FSHARE_GLOBAL_NEWFILE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: 新たにファイルが登録されました');

define('_MI_FSHARE_CATEGORY_NEWFILE_NOTIFY', 'カテゴリ毎の新ファイル登録');
define('_MI_FSHARE_CATEGORY_NEWFILE_NOTIFYCAP', 'このカテゴリに新たにファイルが登録された時に通知する');
define('_MI_FSHARE_CATEGORY_NEWFILE_NOTIFYDSC', 'このカテゴリに新たにファイルが登録された時に通知する');
define('_MI_FSHARE_CATEGORY_NEWFILE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: 新たにファイルが登録されました');

}

?>