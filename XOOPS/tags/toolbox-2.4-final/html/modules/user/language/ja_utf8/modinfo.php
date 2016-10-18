<?php
// Used by Toolbox
define('_MI_USER_CONF_DISCLAIMER_DESC_DEFAULT', "Terms of Use for the NICT Language Grid Toolbox\n\nTo use the Language Grid Toolbox (hereinafter called \"the Service\") that is provided on the website (http://langrid-tool.nict.go.jp/toolbox/) operated by the Language Infrastructure Group of the MASTAR Project at the Knowledge Creating Communication Research Center at the National Institute of Information and Communications Technology (hereinafter called \"Operation Entity\"), users are required to carefully read and agree to the \"Terms of Use for the NICT Language Grid Toolbox\" (hereinafter called \"the Terms of Use\") before use. By using the Service, users acknowledge that they have agreed to all the Terms of Use. If users do not agree to the Terms of Use, they may not use the Service.\n\n1. Purpose of Use\nUsers may use the Service solely for nonprofit purposes. In the Terms of Use, \"the use for nonprofit purposes\" means\n- research use by individuals or use for activities other than profit and profit-making activities,\n- use by public institutions and nonprofit organizations for their main activities or research activities,\n- use by profit organizations for research or corporate social responsibility activities.\n\n2. Service Fee\nThe Service is free of charge.\n\n3. Compliance with the Copyrights and License Conditions\nIn the use of the language resources through the Service, including machine translators and dictionaries that are on the multilingual service infrastructure \"Language Grid\" (hereinafter called \"Language Grid\"), which is operated by the Department of Social Informatics of the Graduate School of Informatics at Kyoto University, users must comply with the copyrights and license conditions of the language resources they use and refrain from any infringement of them. Users must confirm the copyrights and license conditions of each language resource on the setting or search window of the Service before/after use.\n\n4. Handling of Information on the Usage\nUsers must agree that the statistics of their usage of the language resources through the Service will be collected through the Language Grid and always be accessible to the providers of the language resources and computation resources in which these language resources are deployed. The usage statistics do not include any source/translated texts, messages posted on the bulletin board system (BBS), and personal information on the users. The Operation Entity will not obtain any other information apart from the usage statistics through the Service, nor make secondary use of the information without a written agreement with the users.\n\n5. Message Posting\nIn case the Operation Entity finds that a user posts an inappropriate message on the Service, the Operation Entity may take measures, including the deletion of the message without prior or late notice. The Operation Entity may also suspend such a user.\n\n6.  Suspension of the Service\nThe Operation Entity may, at any time, suspend all or any part of the Service without prior notice to users for operational or technical reasons. In the event of suspension, the data on the server operated by the Operation Entity can be lost.\n\n7.  Disclaimer\nUnder no circumstances shall the Operation Entity be liable for any direct or indirect damages, with or without prior notice of the possibility of the damage, related to the use of the Service. The Operation Entity does not warrant the accuracy, security, and usability of any result of the use of the Service, including the translation result. The Operation Entity shall not be liable for nor involved in any contents of the posts made through the Service and any disputes between users.\n\n8.  Governing Law\nThe Terms of Use shall be governed by and interpreted in accordance with the laws of Japan. The Tokyo District Court shall have exclusive jurisdiction over all disputes arising in connection with the Service.\n\n");
define('_MI_USER_CONF_BAD_EMAILS', "ユーザのemailアドレスとして許可される文字列パターン");
//20091224 add
define('_MI_USER_LANG_USER_SUB_PROFILE', "プロファイル管理");
define('_MI_USER_LANG_YES', "はい");
define('_MI_USER_LANG_NO', "いいえ");
define('_MI_USER_LANG_ERROR_NOT_INPUT_TITLE', "項目を表示する場合は、項目名を入力してください。");
define('_MI_USER_LANG_SUB_TITLE_MESSAGE', "項目名は全角１５文字まで表示されます。");
//20100210 add
define('_MI_USER_LANG_SUB_DISPLAY', "追加項目{0}を表示しますか？");
define('_MI_USER_LANG_SUB_TITLE', "追加項目{0}の項目名");
define('_MI_USER_LANG_SUB_LENGTH', "追加項目{0}の最大文字数");
define('_MI_USER_LANG_SUB_DEFAULT', "追加項目{0}の初期値");


// Use is not confirmed by Toolbox
define( '_MI_USER_ADMENU_AVATAR_MANAGE', "アバター管理" );
define( '_MI_USER_ADMENU_GROUP_LIST', "ユーザーグループ管理" );
define( '_MI_USER_ADMENU_LIST', "ユーザー管理" );
define( '_MI_USER_ADMENU_MAIL', "一斉メール送信" );
define( '_MI_USER_ADMENU_MAILJOB_MANAGE', "メールジョブ管理" );
define( '_MI_USER_ADMENU_RANK_LIST', "ユーザーランク管理" );
define( '_MI_USER_ADMENU_USER_SEARCH', "ユーザー検索" );
define( '_MI_USER_BLOCK_LOGIN_DESC', "ログインフォームを表示します" );
define( '_MI_USER_BLOCK_LOGIN_NAME', "ログイン" );
define( '_MI_USER_BLOCK_NEWUSERS_DESC', "新しい登録ユーザの一覧を表示します" );
define( '_MI_USER_BLOCK_NEWUSERS_NAME', "新しい登録ユーザ" );
define( '_MI_USER_BLOCK_ONLINE_DESC', "オンライン状況を表示します" );
define( '_MI_USER_BLOCK_ONLINE_NAME', "オンライン状況" );
define( '_MI_USER_BLOCK_TOPUSERS_DESC', "投稿数のランキングを表示します" );
define( '_MI_USER_BLOCK_TOPUSERS_NAME', "投稿数ランキング" );
define( '_MI_USER_CONF_ACTV_ADMIN', "管理者が確認してアカウントを有効にする" );
define( '_MI_USER_CONF_ACTV_AUTO', "自動的にアカウントを有効にする" );
define( '_MI_USER_CONF_ACTV_GROUP', "アカウント有効化依頼のメールの送信先グループ" );
define( '_MI_USER_CONF_ACTV_GROUP_DESC', "「管理者が確認してアカウントを有効にする」設定になっている場合のみ有効です" );
define( '_MI_USER_CONF_ACTV_TYPE', "新規登録ユーザアカウントの有効化の方法" );
define( '_MI_USER_CONF_ACTV_USER', "ユーザ自身の確認が必要(推奨)" );
define( '_MI_USER_CONF_ALLOW_REGISTER', "新規ユーザの登録を許可する" );
define( '_MI_USER_CONF_ALW_RG_DESC', "「はい」を選択すると新規ユーザの登録を許可します。" );
define( '_MI_USER_CONF_AVATAR_HEIGHT', "アバター画像の最大高さ(ピクセル)" );
define( '_MI_USER_CONF_AVATAR_MAXSIZE', "アバター画像の最大ファイルサイズ(バイト)" );
define( '_MI_USER_CONF_AVATAR_MINPOSTS', "アバターアップロード権を得るための発言数" );
define( '_MI_USER_CONF_AVT_MIN_DESC', "ユーザが自分で作成したアバターをアップロードするために必要な最低投稿数を設定してください。" );
define( '_MI_USER_CONF_AVATAR_WIDTH', "アバター画像の最大幅(ピクセル)" );
define( '_MI_USER_CONF_AVTR_ALLOW_UP', "アバター画像のアップロードを許可する" );
define( '_MI_USER_CONF_BAD_EMAILS_DESC', "それぞれの文字列の間は|で区切ってください。大文字小文字は区別しません。正規表現が使用可能です。" );
define( '_MI_USER_CONF_BAD_UNAMES', "ユーザ名として使用できない文字列" );
define( '_MI_USER_CONF_BAD_UNAMES_DESC', "それぞれの文字列の間は|で区切ってください。大文字小文字は区別しません。正規表現が使用可能です。" );
define( '_MI_USER_CONF_CHGMAIL', "ユーザ自身のEmailアドレス変更を許可する" );
define( '_MI_USER_CONF_DISCLAIMER', "利用許諾文" );
define( '_MI_USER_CONF_DISCLAIMER_DESC', "ユーザの新規登録ページに表示する利用許諾文を入力してください。" );
define( '_MI_USER_CONF_DISPDSCLMR', "利用許諾文を表示する" );
define( '_MI_USER_CONF_DISPDSCLMR_DESC', "「はい」にするとユーザの新規登録ページに利用許諾の文章を表示します。" );
define( '_MI_USER_CONF_MAXUNAME', "ユーザ名の最大文字数(byte)" );
define( '_MI_USER_CONF_MINPASS', "パスワードの最低文字数" );
define( '_MI_USER_CONF_MINUNAME', "ユーザ名の最低文字数(byte)" );
define( '_MI_USER_CONF_NEW_NTF_GROUP', "通知先グループ" );
define( '_MI_USER_CONF_NEW_USER_NOTIFY', "新規ユーザ登録の際にメールにて知らせを受け取る" );
define( '_MI_USER_CONF_SELF_DELETE', "ユーザが自分自身のアカウントを削除できる" );
define( '_MI_USER_CONF_SELF_DELETE_CONF', "アカウント削除前の確認メッセージ" );
define( '_MI_USER_CONF_SELF_DELETE_CONFIRM_DEFAULT', "ユーザアカウントを本当に削除しても良いですか？\nアカウントを削除した場合、全てのユーザ情報が失われます。" );
define( '_MI_USER_CONF_SSLLOGINLINK', "SSLログインページへのURL" );
define( '_MI_USER_CONF_SSLPOST_NAME', "SSLログイン時に使用するPOST変数の名称" );
define( '_MI_USER_CONF_UNAME_TEST_LEVEL', "ユーザ名として使用可能な文字の設定を行います。文字制限の程度を選択してください。" );
define( '_MI_USER_CONF_UNAME_TEST_LEVEL_NORMAL', "中" );
define( '_MI_USER_CONF_UNAME_TEST_LEVEL_STRONG', "強（アルファベットおよび数字のみ）←推奨" );
define( '_MI_USER_CONF_UNAME_TEST_LEVEL_WEAK', "弱（漢字・平仮名も使用可）" );
define( '_MI_USER_CONF_USE_SSL', "ログインにSSLを使用する" );
define( '_MI_USER_CONF_USERCOOKIE', "ユーザ名の保存に使用するクッキーの名称" );
define( '_MI_USER_CONF_USERCOOKIE_DESC', "このクッキーにはユーザ名のみが保存され、ユーザのPCのハードディスク中に1年間保管されます。このクッキーを使用するかしないかはユーザ自身が選択できます。" );
define( '_MI_USER_CONF_GROUP_ID_DESC', "Group IDをコンマ区切りで入力してください" );
define( '_MI_USER_CONF_GROUP_ID', "グループID" );
define( '_MI_USER_KEYWORD_AVATAR_MANAGE', "アバター カスタムアバター システムアバター  一覧 リスト 編集 変更 削除" );
define( '_MI_USER_KEYWORD_CREATE_AVATAR', "アバター カスタムアバター システムアバター 新規作成 アップロード" );
define( '_MI_USER_KEYWORD_CREATE_GROUP', "新規作成 ユーザーグループ" );
define( '_MI_USER_KEYWORD_CREATE_RANK', "ランク ユーザーランク" );
define( '_MI_USER_KEYWORD_CREATE_USER', "新規登録" );
define( '_MI_USER_KEYWORD_GROUP_LIST', "グループ 一覧 リスト 編集 変更  削除 ユーザー ユーザグループ 権限 パーミッション 追加 メンバー" );
define( '_MI_USER_KEYWORD_MAILJOB_LINK_LIST', "Mailjob link list" );
define( '_MI_USER_KEYWORD_MAILJOB_MANAGE', "Mailjob manage" );
define( '_MI_USER_KEYWORD_USER_LIST', "一覧 リスト 編集 変更 削除" );
define( '_MI_USER_KEYWORD_USER_SEARCH', "ユーザー 検索" );
define( '_MI_USER_LANG_MAILJOB_LINK_LIST', "Mailjob link list" );
define( '_MI_USER_MENU_CREATE_AVATAR', "アバターの新規作成" );
define( '_MI_USER_MENU_CREATE_GROUP', "グループの新規作成" );
define( '_MI_USER_MENU_CREATE_RANK', "ランクの新規作成" );
define( '_MI_USER_MENU_CREATE_USER', "ユーザーの新規作成" );
define( '_MI_USER_NAME', "[en]User module[/en][ja]ユーザーモジュール[/ja]" );
define( '_MI_USER_NAME_DESC', "ユーザーアカウントに関する処理を行う基盤モジュール" );

?>
