<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'FSHARE_AM_LOADED' ) ) {

define( 'FSHARE_AM_LOADED' , 1 ) ;

define('_MD_A_MYMENU_MYTPLSADMIN','テンプレート管理');
define('_MD_A_MYMENU_MYBLOCKSADMIN','ブロック管理/アクセス権限');
define('_MD_A_MYMENU_MYPREFERENCES','一般設定');

// Index (Categories)
define( "_AM_H3_FMT_CATEGORIES" , "%s カテゴリー管理" ) ;
define( "_AM_CAT_TH_ID" , "Id" ) ;
define( "_AM_CAT_TH_TITLE" , "カテゴリー名" ) ;
define( "_AM_CAT_TH_FILES" , "ファイル数" ) ;
define( "_AM_CAT_TH_OPERATION" , "カテゴリ操作" ) ;
define( "_AM_CAT_TH_IMAGE" , "イメージ" ) ;
define( "_AM_CAT_TH_PARENT" , "親カテゴリー" ) ;
define( "_AM_CAT_TH_IMGURL" , "イメージのURL" ) ;
define( "_AM_CAT_MENU_NEW" , "カテゴリーの新規作成" ) ;
define( "_AM_CAT_MENU_EDIT" , "カテゴリーの編集" ) ;
define( "_AM_CAT_INSERTED" , "カテゴリーを追加しました" ) ;
define( "_AM_CAT_UPDATED" , "カテゴリーを更新しました" ) ;
define( "_AM_CAT_BTN_BATCH" , "変更を反映する" ) ;
define( "_AM_CAT_LINK_MAKETOPCAT" , "トップカテゴリーを追加" ) ;
define( "_AM_CAT_LINK_ADDFILES" , "このカテゴリーにファイルを追加" ) ;
define( "_AM_CAT_LINK_EDIT" , "このカテゴリーの編集" ) ;
define( "_AM_CAT_LINK_MAKESUBCAT" , "このカテゴリー下にサブカテゴリー作成" ) ;
define( "_AM_CAT_FMT_NEEDADMISSION" , "未承認ファイルあり (%s ファイル)" ) ;
define( "_AM_CAT_FMT_CATDELCONFIRM" , "カテゴリー %s を削除してよろしいですか？ 配下のサブカテゴリーも含め、ファイルやコメントがすべて削除されます" ) ;


// Admission
define( "_AM_H3_FMT_ADMISSION" , "%s 投稿ファイルの承認" ) ;
define( "_AM_TH_SUBMITTER" , "投稿者" ) ;
define( "_AM_TH_TITLE" , "タイトル" ) ;
define( "_AM_TH_DESCRIPTION" , "説明文" ) ;
define( "_AM_TH_CATEGORIES" , "カテゴリー" ) ;
define( "_AM_TH_DATE" , "最終更新日" ) ;


// File Manager
define( "_AM_H3_FMT_FILEMANAGER" , "%s ファイル管理" ) ;
define( "_AM_TH_BATCHUPDATE" , "チェックしたファイルをまとめて変更する" ) ;
define( "_AM_OPT_NOCHANGE" , "変更なし" ) ;
define( "_AM_JS_UPDATECONFIRM" , "指定された項目についてのみ、チェックしたファイルを変更します" ) ;


// Module Checker
define( "_AM_H3_FMT_MODULECHECKER" , "myAlbum-P 動作チェッカー (%s)" ) ;

define( "_AM_H4_ENVIRONMENT" , "環境チェック" ) ;
define( "_AM_MB_PHPDIRECTIVE" , "PHP設定" ) ;
define( "_AM_MB_BOTHOK" , "両方ok" ) ;
define( "_AM_MB_NEEDON" , "要on" ) ;


define( "_AM_H4_TABLE" , "テーブルチェック" ) ;
define( "_AM_MB_FILESTABLE" , "メインファイルテーブル" ) ;
define( "_AM_MB_DESCRIPTIONTABLE" , "テキストテーブル" ) ;
define( "_AM_MB_CATEGORIESTABLE" , "カテゴリーテーブル" ) ;
define( "_AM_MB_VOTEDATATABLE" , "投票データテーブル" ) ;
define( "_AM_MB_COMMENTSTABLE" , "コメントテーブル" ) ;
define( "_AM_MB_NUMBEROFFILES" , "ファイル総数" ) ;
define( "_AM_MB_NUMBEROFDESCRIPTIONS" , "テキスト総数" ) ;
define( "_AM_MB_NUMBEROFCATEGORIES" , "カテゴリー総数" ) ;
define( "_AM_MB_NUMBEROFVOTEDATA" , "投票総数" ) ;
define( "_AM_MB_NUMBEROFCOMMENTS" , "コメント総数" ) ;


define( "_AM_H4_CONFIG" , "設定チェック" ) ;
define( "_AM_MB_PIPEFORIMAGES" , "ファイル処理プログラム" ) ;
define( "_AM_MB_DIRECTORYFORFILES" , "メインファイルディレクトリ" ) ;
define( "_AM_MB_DIRECTORYFORTHUMBS" , "サムネイルディレクトリ" ) ;
define( "_AM_ERR_LASTCHAR" , "エラー: 最後の文字は'/'でなければなりません" ) ;
define( "_AM_ERR_FIRSTCHAR" , "エラー: 最初の文字は'/'でなければなりません" ) ;
define( "_AM_ERR_PERMISSION" , "エラー: まずこのディレクトリをつくって下さい。その上で、書込可能に設定して下さい。Unixではchmod 777、Windowsでは読み取り専用属性を外します" ) ;
define( "_AM_ERR_NOTDIRECTORY" , "エラー: 指定されたディレクトリがありません." ) ;
define( "_AM_ERR_READORWRITE" , "エラー: 指定されたディレクトリは読み出せないか書き込めないかのいずれかです。その両方を許可する設定にして下さい。Unixではchmod 777、Windowsでは読み取り専用属性を外します" ) ;
define( "_AM_ERR_SAMEDIR" , "エラー: メインファイル用ディレクトリとサムネイル用ディレクトリが一緒です。（その設定は不可能です）" ) ;
define( "_AM_LNK_CHECKGD2" , "GD2(truecolor)モードが動くかどうかのチェック" ) ;
define( "_AM_MB_CHECKGD2" , "（このリンク先が正常に表示されなければ、GD2モードでは動かないものと諦めてください）" ) ;
define( "_AM_MB_GD2SUCCESS" , "成功しました!<br />おそらく、このサーバのPHPでは、GD2(true color)モードでファイルを生成可能です。" ) ;


define( "_AM_H4_FILELINK" , "メインファイルとサムネイルのリンクチェック" ) ;
define( "_AM_MB_NOWCHECKING" , "チェック中 ." ) ;
define( "_AM_FMT_FILENOTREADABLE" , "メインファイル (%s) が読めません." ) ;
define( "_AM_FMT_THUMBNOTREADABLE" , "サムネイルファイル (%s) が読めません." ) ;
define( "_AM_FMT_NUMBEROFDEADFILES" , "ファイルのないレコードが %s 個ありました。" ) ;
define( "_AM_FMT_NUMBEROFDEADTHUMBS" , "サムネイルが %s 個未作成です" ) ;
define( "_AM_FMT_NUMBEROFREMOVEDTMPS" , "テンポラリを %s 個削除しました" ) ;
define( "_AM_LINK_REDOTHUMBS" , "サムネイル再構築" ) ;
define( "_AM_LINK_TABLEMAINTENANCE" , "テーブルメンテナンス" ) ;



// Redo Thumbnail
define( "_AM_H3_FMT_RECORDMAINTENANCE" , "myAlbum-P ファイルメンテナンス (%s)" ) ;

define( "_AM_FMT_CHECKING" , "%s をチェック中 ... " ) ;

define( "_AM_FORM_RECORDMAINTENANCE" , "サムネイルの再構築など、ファイルデータの各種メンテナンス" ) ;

define( "_AM_MB_FAILEDREADING" , "ファイルの読み込み失敗" ) ;
define( "_AM_MB_CREATEDTHUMBS" , "サムネイル作成完了" ) ;
define( "_AM_MB_BIGTHUMBS" , "サムネイルを作成できないので、コピーしました" ) ;
define( "_AM_MB_SKIPPED" , "スキップします" ) ;
define( "_AM_MB_SIZEREPAIRED" , "(登録されていたピクセル数を修正しました)" ) ;
define( "_AM_MB_RECREMOVED" , "このレコードは削除されました" ) ;
define( "_AM_MB_FILENOTEXISTS" , "ファイルがありません" ) ;
define( "_AM_MB_FILERESIZED" , "サイズ調整しました" ) ;

define( "_AM_TEXT_RECORDFORSTARTING" , "処理を開始するレコード番号" ) ;
define( "_AM_TEXT_NUMBERATATIME" , "一度に処理するファイル数" ) ;
define( "_AM_LABEL_DESCNUMBERATATIME" , "この数を大きくしすぎるとサーバのタイムアウトを招きます" ) ;

define( "_AM_RADIO_FORCEREDO" , "サムネイルがあっても常に作成し直す" ) ;
define( "_AM_RADIO_REMOVEREC" , "ファイルがないレコードを削除する" ) ;
define( "_AM_RADIO_RESIZE" , "今のピクセル数設定よりも大きな画像はサイズを切りつめる" ) ;

define( "_AM_MB_FINISHED" , "完了" ) ;
define( "_AM_LINK_RESTART" , "再スタート" ) ;
define( "_AM_SUBMIT_NEXT" , "次へ" ) ;



// Batch Register
define( "_AM_H3_FMT_BATCHREGISTER" , "myAlbum-P ファイル一括登録 (%s)" ) ;


// GroupPerm Global
define( "_AM_MD_ALBM_GROUPPERM_GLOBAL" , "各グループの権限設定" ) ;
define( "_AM_MD_ALBM_GROUPPERM_GLOBALDESC" , "グループ個々について、権限を設定します" ) ;
define( "_AM_MD_ALBM_GPERMUPDATED" , "権限設定を変更しました" ) ;


// Import
define( "_AM_H3_FMT_IMPORTTO" , '%s へのファイルインポート' ) ;
define( "_AM_FMT_IMPORTFROMFSHAREP" , 'myAblum-Pモジュール: 「%s」 からの取り込み（カテゴリー単位）' ) ;
define( "_AM_FMT_IMPORTFROMIMAGEMANAGER" , 'イメージ・マネージャからの取り込み（カテゴリー単位）' ) ;
define( "_AM_CB_IMPORTRECURSIVELY" , 'サブカテゴリーもインポートする' ) ;
define( "_AM_RADIO_IMPORTCOPY" , 'ファイルのコピー（コメントは引き継がれません）' ) ;
define( "_AM_RADIO_IMPORTMOVE" , 'ファイルの移動（コメントを引き継ぎます）' ) ;
define( "_AM_MB_IMPORTCONFIRM" , 'インポートします。よろしいですか？' ) ;
define( "_AM_FMT_IMPORTSUCCESS" , '%s ファイルをインポートしました' ) ;


// Export
define( "_AM_H3_FMT_EXPORTTO" , '%s から他モジュール等へのファイルエクスポート' ) ;
define( "_AM_FMT_EXPORTTOIMAGEMANAGER" , 'イメージ・マネージャへの書き出し（カテゴリー単位）' ) ;
define( "_AM_FMT_EXPORTIMSRCCAT" , 'コピー元カテゴリー' ) ;
define( "_AM_FMT_EXPORTIMDSTCAT" , 'コピー先カテゴリー' ) ;
define( "_AM_CB_EXPORTRECURSIVELY" , 'サブカテゴリーもエクスポートする' ) ;
define( "_AM_CB_EXPORTTHUMB" , 'サムネイルファイルの方をエクスポートする' ) ;
define( "_AM_MB_EXPORTCONFIRM" , 'エクスポートします。よろしいですか？' ) ;
define( "_AM_FMT_EXPORTSUCCESS" , '%s ファイルをエクスポートしました' ) ;


}

?>