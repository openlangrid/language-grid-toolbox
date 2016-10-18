
ローカル辞書Webサービス化機能

1. 概要
　ユーザ辞書として作成された辞書データをWebサービス化し外部へ公開する機能を提供します｡
　対訳辞書、用例対訳に対応しています｡

2. 内部構成
 2.1 パッケージング
  modules/dictionary/services ->root
   /database　->データベースアクセス用
   /exception ->Soap例外レスポンス用
   /invoker ->サービス実行のエントリーポイント用
   /model ->Soapレスポンス用モデル
   /service ->サービスの実体用
   /validator ->パラメータのバリデータ用
   /wsdl ->WSDL生成用
   /wsdl/template ->WSDLのテンプレート
   defines.php ->定数定義

3. 実行フロー
　/wsdl/各サービス_wsdl.phpからwsdl取得
　取得したwsdlよりsoapクライアントから/invoker/各サービス.phpへSOAPリクエスト
　/invoker/各サービス.phpから/service/各サービス.phpを実行
　/invoker/各サービス.phpからresponseを送信

4. 発生する例外一覧
　InvalidParameterException
　ProcessFailedException *未対応
　UnsupportedLanguagePairException *未対応

5. 開発資料
 5.1　テスト
　　dictionary/servicesにアクセスすると現在登録されている対訳辞書、用例対訳の一覧がリンクで表示されます。
　　リンクはwsdlの取得用です、このwsdlを使用してクライアントからの呼び出しをテストできます｡

6.　残りタスク　2009/08/7現在
　　BilingualDictionary.getLastUpdate()が動作不能
　　ProcessFailedExceptionが未実装
　　UnsupportedLanguagePairExceptionが未実装

!!!Attention: for developers!!!
　以下のファイル、フォルダ、PHP内コードはテスト用です｡
　この注意文!!! ~ !!!と共に実際の配備、配布時には削除してください

  dictionary/services/test/
　dictionary/services/index.php
　dictionary/templates/service_main.html
　dictionary/xoops_versions.php内
　　$modversion['templates'][6]['file'] = 'service_main.html';
　　$modversion['templates'][6]['description'] = 'Local service deployer test.';
	
!!!
