<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'FSHARE_MB_LOADED' ) ) {

define( 'FSHARE_MB_LOADED' , 1 ) ;

//%%%%%%		Module Name 'myAlbum-P'		%%%%%

// *** Used by Toolbox ***
define('_MD_ALBM_FILEMANAGEMENT', 'ファイル共有');
define('_MD_ALBM_HOW_TO_USE_LINK', 'filesharing_ja.html');
define('_MD_ALBM_UPDATENOW', '更新');

// only "Y/m/d" , "d M Y" , "M d Y" can be interpreted
define('_MD_ALBM_DTFMT_YMDHI', 'Y/m/d H:i');

define('_MD_ALBM_DIRECTCATSEL', 'カテゴリ選択');
define('_MD_ALBM_THEREARE', '<b>%s</b> 個のファイルがあります');
define('_MD_ALBM_LATESTLIST', '最新のファイル');
define('_MD_ALBM_ADDFILE', 'ファイルのアップロード');
define('_MD_ALBM_AM_FILENAVINFO', '%s 番 ～ %s 番を表示 (全 %s ファイル)');
define('_MD_ALBM_EDITTHISFILE', '編集');
define('_MD_ALBM_NEW', '新着');
define('_MD_ALBM_UPDATED', '更新');
define('_MD_ALBM_POPULAR', '高ヒット');
define('_MD_ALBM_SUBMITTER', '投稿者');
define('_MD_ALBM_MOREFILES', '%s さんがアップロードしたファイル');
define('_MD_ALBM_SORTBY', '並び替え:');
define('_MD_ALBM_FILENAME', 'ファイル名');
define('_MD_ALBM_DATE', '日時');
define('_MD_ALBM_CURSORTEDBY', '現在の並び順: %s');
define('_MD_ALBM_FILENAMEATOZ', 'ファイル名 (昇順)');
define('_MD_ALBM_FILENAMEZTOA', 'ファイル名 (降順)');
define('_MD_ALBM_DATEOLD', '日時 (昇順)');
define('_MD_ALBM_DATENEW', '日時 (降順)');
define('_MD_ALBM_FILEDESC', '説明');
define('_MD_ALBM_FILECAT', 'カテゴリ');
define('_MD_ALBM_SELECTFILE', 'ファイル');
define('_MD_ALBM_NOIMAGESPECIFIED', 'ファイルが未選択です。アップロードするファイルを選択して下さい。');
define('_MD_ALBM_FILEERROR', 'ファイルが見つからないか容量制限を越えています。');
define('_MD_ALBM_FILEREADERROR', 'アップロードされたファイルを読み出せません。');
define('_MD_ALBM_FILEDEL', 'このファイルを削除してよろしいですか？');
define('_MD_ALBM_AM_ADMITTING', 'ファイルを承認しました');
define('_MD_ALBM_AM_LABEL_REMOVE', 'チェックしたファイルを削除');
define('_MD_ALBM_AM_JS_REMOVECONFIRM', '削除してよろしいですか');
define('_MD_ALBM_AM_BUTTON_UPDATE', '変更');
define('_MD_ALBM_AM_DEADLINKMAINFILE', 'メインファイルが存在しません');
define('_MD_ALBM_BTN_SELECTALL', '全選択');
define('_MD_ALBM_BTN_SELECTNONE', '選択解除');
define('_MD_ALBM_FMT_FILENUM', '%s ファイル / ページ');
define('_MD_ALBM_AM_LABEL_ADMIT', 'チェックしたファイルを承認');
define('_MD_ALBM_AM_BUTTON_ADMIT', '承認');
define('_MD_ALBM_AM_BUTTON_EXTRACT', '検索');
define('_MD_ALBM_VALIDFILE', '承認');
define('_MD_ALBM_DELETINGFILE', '削除中…');
define('_MD_ALBM_STORETIMESTAMP', '日時を変更しない');
define('_MD_ALBM_DESCRIPTION', '説明');
define('_MD_ALBM_DESCRIPTIONC', '説明');
define('_MD_ALBM_RECEIVED', 'ファイルのアップロードに成功しました。');
define('_MD_ALBM_NOMATCH', '選択されたフォルダにはファイルがありません');
define('_MD_ALBM_SUBMIT', '投稿');
define('_MD_ALBM_MUSTREGFIRST', '申し訳ありませんがアクセス権限がありません。<br>登録するか、ログイン後にお願いします。');
define('_MD_ALBM_MUSTADDCATFIRST', 'アップロードする前にカテゴリを作成してください．');
define('_MD_ALBM_GROUPPERM_GLOBAL', 'グループの権限');
define('_MD_ALBM_DBUPDATED', 'データベース更新に成功!');
define('_MD_ALBM_CATDELETED', 'カテゴリは消去されました');
define('_MD_ALBM_NAME', '名前');
define('_MD_ALBM_NEWUPDATE', '最新の更新');
define('_MD_ALBM_LASTUPDATEC', '更新日');
define('_MD_ALBM_FILEUPLOAD', 'ファイルアップロード');
define('_MD_ALBM_MAIN', 'ファイル共有トップ');
define('_MD_ALBM_MAXSIZE', 'サイズ上限');
define('_MD_ALBM_FILEEDITUPLOAD', 'ファイル編集');
define('_MD_ALBM_FILEDELETE', 'ファイル削除');
define('_MD_ALBM_NEWFILES', 'ファイル一覧');
define('_MD_ALBM_INPUT_ALERT_JS', '%1を選択してください。');
define('_MD_ALBM_FILESIZE_UNIT', 'MB');
define('_MD_ALBM_BTN_OK', 'OK');
define('_MD_ALBM_AM_BUTTON_REMOVE', '削除');
define('_MD_ALBM_BTN_BACK', '戻る');
//20100129 add
define('_MD_ALBM_ADDFOLDER', 'フォルダの作成');
define('_MD_ALBM_FOLDER_CREATE', 'フォルダ作成');
define('_MD_ALBM_READ', '閲覧');
define('_MD_ALBM_EDIT', '編集');
define('_MD_ALBM_FOLDER', 'フォルダ');
define('_MD_ALBM_FOLDER_NAME', 'フォルダ名');
define('_MD_ALBM_NO_FOLDER_NAME_ERROR', 'フォルダ名を入力してください。');
define('_MD_ALBM_PARENT_FOLDER', '親フォルダ');
define('_MD_ALBM_EDIT_PERMISSION', '編集を許可');
define('_MD_ALBM_READ_PERMISSION', '閲覧を許可');
define('_MD_ALBM_FOR_ALL_USERS', '全ユーザ');
define('_MD_ALBM_FOR_THE_CURRENT_USER_ONLY', '現在のユーザのみ');
define('_MD_ALBM_NOT_FOLDER_PERMISSION', 'このフォルダにファイルを保存する権限がありません．');
define('_MD_ALBM_NOT_PARENT_FOLDER_PERMISSION', '親フォルダに編集権限がありません。');
define('_MD_ALBM_OWNER', '所有ユーザ');
define('_MD_ALBM_FOLDER_EDIT', 'フォルダ情報編集');
define('_MD_ALBM_FOLDER_DELETE', 'フォルダ削除');
define('_MD_ALBM_NOT_DELETE_PERMISSION', '配下に編集できないフォルダかファイルがあるため削除できません。');
define('_MD_ALBM_NOT_MOVE_FOLDER', '配下に編集できないフォルダかファイルがあるため移動できません。');
define('_MD_ALBM_FOLDER_SAME_NAME_ERROR', '既に同名のフォルダが存在します。');
define('_MD_ALBM_FILE_SAME_NAME_ERROR', '既に同名のファイルが存在します。');
define('_MD_ALBM_BTN_CLOSE', '閉じる');
//20100205 add
define('_MD_COPYRIGHT_LB', 'Copyright(C) 2009-2010 Language Grid Project, NICT. All rights reserved.');

// Use is Dialog
define('_MI_FILESHARINGDIALOG_DIALOG_TITLE', 'ファイルを選択');
define('_MI_FILESHARINGDIALOG_UPLOAD_BUTTON', 'ファイルをアップロード');
define('_MI_FILESHARINGDIALOG_TABLE_COL_SELECT', '');
define('_MI_FILESHARINGDIALOG_TABLE_COL_NAME', 'ファイル名');
define('_MI_FILESHARINGDIALOG_TABLE_COL_DESC', '説明');
define('_MI_FILESHARINGDIALOG_TABLE_COL_READ', '読み込み');
define('_MI_FILESHARINGDIALOG_TABLE_COL_EDIT', '書き込み');
define('_MI_FILESHARINGDIALOG_TABLE_COL_UPDATER', '作成者');
define('_MI_FILESHARINGDIALOG_TABLE_COL_UPDATE', '最終保存日');
define('_MI_FILESHARINGDIALOG_OK_BUTTON', 'OK');
define('_MI_FILESHARINGDIALOG_CANCEL_BUTTON', 'キャンセル');
define('_MI_FILESHARINGDIALOG_SAVE_DIALOG_TITLE', 'ファイルを保存');
define('_MI_FILESHARINGDIALOG_SAVE_FILE_NAME', 'ファイル名');
define('_MI_FILESHARINGDIALOG_SAVE_FILE_DESC', 'ファイルの説明');
define('_MI_FILESHARINGDIALOG_SAVE_PERM', '権限');
define('_MI_FILESHARINGDIALOG_SAVE_READ_PERM', '閲覧を許可');
define('_MI_FILESHARINGDIALOG_SAVE_EDIT_PERM', '編集を許可');
define('_MI_FILESHARINGDIALOG_SAVE_PERM_OPT_PUBLIC', '全ユーザ');
define('_MI_FILESHARINGDIALOG_SAVE_PERM_OPT_USER', '現在のユーザのみ');
define('_MI_FILESHARINGDIALOG_SAVE_OK_BUTTON', '保存');
define('_MI_FILESHARINGDIALOG_ROOT_FOLDER_LABEL', 'トップ');
define('_MI_FILESHARINGDIALOG_ERROR_PERMISSION_NO_EDIT', 'このフォルダにファイルを保存する権限がありません。');

// *** Use is not confirmed by Toolbox ***
define( '_MD_ALBM_NEXT_BUTTON' , '次へ' ) ;
define( '_MD_ALBM_REDOLOOPDONE' , '終了' ) ;
define( '_MD_ALBM_BTN_SELECTRVS' , '選択反転' ) ;
define( '_MD_ALBM_AM_ADMISSION' , 'ファイルの承認' ) ;
define( '_MD_ALBM_AM_FILEMANAGER' , 'ファイルの管理' ) ;
define( '_MD_ALBM_AM_LABEL_MOVE' , 'チェックしたファイルを移動する' ) ;
define( '_MD_ALBM_AM_BUTTON_MOVE' , '移動' ) ;
define( '_MD_ALBM_RADIO_ROTATETITLE' , '画像回転' ) ;
define( '_MD_ALBM_RADIO_ROTATE0' , '回転しない' ) ;
define( '_MD_ALBM_RADIO_ROTATE90' , '右に90度回転' ) ;
define( '_MD_ALBM_RADIO_ROTATE180' , '180度回転' ) ;
define( '_MD_ALBM_RADIO_ROTATE270' , '左に90度回転' ) ;

// New MyAlbum 1.0.1 (and 1.2.0)
define( '_MD_ALBM_REDOTHUMBS','サムネイルの再構築(<a href="redothumbs.php">再スタート</a>)');
define( '_MD_ALBM_REDOTHUMBS2','サムネイルの再構築');
define( '_MD_ALBM_REDOTHUMBSINFO','大きな数値を入力するとサーバータイムアウトの原因になります。');
define( '_MD_ALBM_REDOTHUMBSNUMBER','一度に処理するサムネールの数');
define( '_MD_ALBM_REDOING','再構築しました: ');
define( '_MD_ALBM_BACK','戻る');

// New MyAlbum 1.0.0
define( '_MD_ALBM_FILEBATCHUPLOAD','サーバにアップロード済ファイルの一括登録');
define( '_MD_ALBM_MAXPIXEL','サイズ上限');
define( '_MD_ALBM_FILEPATH','Path:');
define( '_MD_ALBM_TEXT_DIRECTORY','ディレクトリ');
define( '_MD_ALBM_DESC_FILEPATH','ファイルの含まれるディレクトリを絶対パスで指定して下さい');
define( '_MD_ALBM_MES_INVALIDDIRECTORY','指定されたディレクトリからファイルを読み出せません');
define( '_MD_ALBM_MES_BATCHDONE','%s ファイルを登録しました');
define( '_MD_ALBM_MES_BATCHNONE','指定されたディレクトリにファイルがみつかりませんでした');
define( '_MD_ALBM_BATCHBLANK','タイトル部を空欄にした場合、ファイル名をタイトルとします');
define( '_MD_ALBM_DELETEFILE','削除?');
define( '_MD_ALBM_MOVINGFILE','移動しました');

define( '_MD_ALBM_POSTERC','投稿: ');
define( '_MD_ALBM_DATEC','日時: ');
define( '_MD_ALBM_EDITNOTALLOWED','コメントを編集する権限がありません！');
define( '_MD_ALBM_ANONNOTALLOWED','匿名ユーザは投稿できません。');
define( '_MD_ALBM_THANKSFORPOST','ご投稿有り難うございます。');
define( '_MD_ALBM_DELNOTALLOWED','コメントを削除する権限がありません!');
define( '_MD_ALBM_GOBACK','戻る');
define( '_MD_ALBM_AREYOUSURE','このコメントとその下部コメントを削除：よろしいですか？');
define( '_MD_ALBM_COMMENTSDEL','コメント削除完了！');

// End New

define( '_MD_ALBM_THANKSFORINFO','ご投稿頂いたファイルの公開はできるだけ早く検討します。');
define( '_MD_ALBM_BACKTOTOP','最初のファイルへ戻る');
define( '_MD_ALBM_THANKSFORHELP','ご協力有難うございます。');
define( '_MD_ALBM_FORSECURITY','セキュリティの観点からあなたのIPアドレスを一時的に保存します。');

define( '_MD_ALBM_MATCH','合致');
define( '_MD_ALBM_ALL','全て');
define( '_MD_ALBM_ANY','どれでも');

define( '_MD_ALBM_TOPRATED','高評価');

define( '_MD_ALBM_POPULARITYLTOM','ヒット数 (低→高)');
define( '_MD_ALBM_POPULARITYMTOL','ヒット数 (高→低)');
define( '_MD_ALBM_TITLEATOZ','タイトル (A → Z)');
define( '_MD_ALBM_TITLEZTOA','タイトル (Z → A)');
define( '_MD_ALBM_RATINGLTOH','評価 (低→高)');
define( '_MD_ALBM_RATINGHTOL','評価 (高→低)');
define( '_MD_ALBM_LIDASC','レコード番号昇順');
define( '_MD_ALBM_LIDDESC','レコード番号降順');

define( '_MD_ALBM_NOSHOTS','サムネイルなし');

define( '_MD_ALBM_EMAILC','Email');
define( '_MD_ALBM_CATEGORYC','カテゴリ');
define( '_MD_ALBM_TELLAFRIEND','友人に知らせる');
define( '_MD_ALBM_SUBJECT4TAF','面白いファイルを見つけました');
define( '_MD_ALBM_HITSC','ヒット数');
define( '_MD_ALBM_RATINGC','評価');
define( '_MD_ALBM_ONEVOTE','投票数 1');
define( '_MD_ALBM_NUMVOTES','投票数 %s');
define( '_MD_ALBM_ONEPOST','コメント数');
define( '_MD_ALBM_NUMPOSTS','コメント数 %s');
define( '_MD_ALBM_COMMENTSC','コメント数');
define( '_MD_ALBM_RATETHISFILE','投票する');
define( '_MD_ALBM_MODIFY','変更');
define( '_MD_ALBM_VSCOMMENTS','コメントを見る/送る');


define( '_MD_ALBM_VOTEAPPRE','投票を受け付けました');
define( '_MD_ALBM_THANKURATE','当サイト %s へのご投票、ありがとうございました');
define( '_MD_ALBM_VOTEONCE','同一ファイルへの投票は一度だけにお願いします。');
define( '_MD_ALBM_RATINGSCALE','評価は 1 から 10 までです： 1 が最低、 10 が最高');
define( '_MD_ALBM_BEOBJECTIVE','客観的な評価をお願いします。点数が1か10のみだと順位付けの意味がありません');
define( '_MD_ALBM_DONOTVOTE','自分が登録したファイルは投票できません。');
define( '_MD_ALBM_RATEIT','投票する!');

define( '_MD_ALBM_ALLPENDING','すべての投稿ファイルは確認のため仮登録となります。');

define( '_MD_ALBM_RANK','ランク');
define( '_MD_ALBM_CATEGORY','カテゴリ');
define( '_MD_ALBM_SUBCATEGORY','サブカテゴリ');
define( '_MD_ALBM_HITS','ヒット');
define( '_MD_ALBM_RATING','評価');
define( '_MD_ALBM_VOTE','投票');
define( '_MD_ALBM_TOP10','%s のトップ10'); // %s はカテゴリのタイトル

define( '_MD_ALBM_TITLE','タイトル');
define( '_MD_ALBM_POPULARITY','ヒット数');
define( '_MD_ALBM_FOUNDIN','見つかったのはここ:');
define( '_MD_ALBM_PREVIOUS','前');
define( '_MD_ALBM_NEXT','次');

define( '_MD_ALBM_CATEGORIES','カテゴリ');

define( '_MD_ALBM_CANCEL','キャンセル');

define( '_MD_ALBM_NORATING','評価が選択されてません。');
define( '_MD_ALBM_CANTVOTEOWN','自分の投稿ファイルには投票できません。<br>投票には全て目を通します');
define( '_MD_ALBM_VOTEONCE2','選択ファイルへの投票は一度だけにお願いします。<br>投票にはすべて目を通します。');

//%%%%%%	Module Name 'MyAlbum' (Admin)	  %%%%%

define( '_MD_ALBM_FILESWAITING','投稿されたファイルの承認: 承認待ファイル数');
define( '_MD_ALBM_FILEMANAGER','ファイル管理');
define( '_MD_ALBM_CATEDIT','カテゴリの追加・編集');
define( '_MD_ALBM_CHECKCONFIGS','モジュールの状態チェック');
define( '_MD_ALBM_BATCHUPLOAD','ファイル一括登録');
define( '_MD_ALBM_GENERALSET','一般設定');

define( '_MD_ALBM_DELETE','削除');
define( '_MD_ALBM_NOSUBMITTED','新規の投稿ファイルはありません。');
define( '_MD_ALBM_ADDMAIN','トップカテゴリを追加');
define( '_MD_ALBM_IMGURL','画像のURL (画像の高さはあらかじめ50pixelに): ');
define( '_MD_ALBM_ADD','追加');
define( '_MD_ALBM_ADDSUB','サブカテゴリの追加');
define( '_MD_ALBM_IN','');
define( '_MD_ALBM_MODCAT','カテゴリ変更');
define( '_MD_ALBM_MODREQDELETED','変更要請を削除');
define( '_MD_ALBM_IMGURLMAIN','画像URL (画像の高さはあらかじめ50pixelに): ');
define( '_MD_ALBM_PARENT','親カテゴリ:');
define( '_MD_ALBM_SAVE','変更を保存');
define( '_MD_ALBM_CATDEL_WARNING','カテゴリと同時にここに含まれるファイルおよびコメントが全て削除されますがよろしいですか？');
define( '_MD_ALBM_YES','はい');
define( '_MD_ALBM_NO','いいえ');
define( '_MD_ALBM_NEWCATADDED','新カテゴリ追加に成功!');
define( '_MD_ALBM_ERROREXIST','エラー: 提供されるファイルはすでにデータベースに存在します。');
define( '_MD_ALBM_ERRORTITLE','エラー: タイトルが必要です!');
define( '_MD_ALBM_ERRORDESC','エラー: 説明が必要です!');
define( '_MD_ALBM_WEAPPROVED','ファイルデータベースへのリンク要請を承認しました。');
define( '_MD_ALBM_THANKSSUBMIT','ご投稿有り難うございます。');
define( '_MD_ALBM_CONFUPDATED','設定を更新しました。');

}

?>
