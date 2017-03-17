<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
$messages = array();

/* *** English *** */
$messages['en'] = array(
	'tab-label-dictionary' => 'Dictionary'
	,'tab-label-setting' => 'Setting'
	,'import_page_dictionary' => 'Import page dictionary'
	,'page-title-dictionary' => 'PageDict'
	
	// Dictionary
	// * Common
	,'lg:Save' => 'Save'
	,'lg:Cancel' => 'Cancel'
	// * Translation
	,'lg:Please_create_translation_path_settings_first.' => 'Please create translation path settings first.'
	,'lg:Translation' => 'Translation'
	,'lg:Translate' => 'Translate'
	,'lg:Lite' => 'Escape translation of quoted phrases'
	,'lg:Rich' => 'Display original words'
	,'lg:Clear' => 'Clear'
	,'lg:%d_Sec.' => '%d sec.'
	,'lg:Stop' => 'Stop'
	,'lg:The_size_of_text_area_cannot_be_reduced_anymore.' => 'The size of text area cannot be reduced anymore.'
	,'lg:The_font_size_cannot_be_reduced_anymore.' => 'The font size cannot be reduced anymore.'
	// * Back-Translation
	,'lg:Back_Translation' => 'Back translation'
	// * Page Dictionary
	,'lg:Page_Dictionary' => 'Page dictionary'
	,'lg:Add_record' => 'Add record'
	,'lg:Delete_record' => 'Delete record'
	,'lg:Add/Delete_language' => 'Add/Delete language'
	,'lg:Download' => 'Download'
	,'lg:Updated' => 'Updated'
	,'lg:Search' => 'Search'
	,'lg:Entry_count' => 'Dictionary entry count'
	// * Include Page Dictionary
	,'lg:Include_Page_Dictionary' => 'Other page dictionary'
	,'lg:Include_Dictionary' => 'Include'
	,'lg:Include' => 'Include'
	,'lg:Now_including...' => 'Now including...'
	// * Import Page Dictionary
	,'lg:Import_dictionary' => 'Import dictionary'
	,'lg:Import' => 'Import'
	,'lg:Now_importing...' => 'Now importing...'
	,'lg:Title' => 'Article title'
	,'lg:Delete' => 'Delete'
	,'lg:The_file_format_is_invalid.' => 'The file format is invalid.'
	// * Upload Page Dictionary
	,'lg:Upload_dictionary' => 'Upload dictionary'
	,'lg:Upload' => 'Upload'
	,'lg:Upload_file' => 'Dictionary file'
	// * License Information
	,'lg:License_Information' => 'License information'
	,'lg:Service_Name' => 'Service name'
	,'lg:Copyright' => 'Copyright'

	// Select Language 
	,'lg:Create_Page_Dictionary' => 'Create page dictionary'
	,'lg:Create' => 'Create'
	,'lg:Add/Delete_Languages' => 'Add/Delete languages'
	,'lg:At_least_two_languages_are_required_in_the_dictionary.' => 'At least two languages are required in the dictionary.'
	,'lg:The_dictionary_was_not_saved_after_the_last_change.' => 'The dictionary was not saved after the last change.'
);

$messages['ja'] = array(
	'tab-label-dictionary' => '辞書'
	,'tab-label-setting' => '翻訳設定'
	,'import_page_dictionary' => '辞書のインポート'
	,'page-title-dictionary' => 'ページ辞書'
	
	// ----
	// Dictionary
	// * Common
	,'lg:Save' => '保存'
	,'lg:Cancel' => 'キャンセル'
	// * Translation
	,'lg:Please_create_translation_path_settings_first.' => '翻訳パスを作成してください。'
	,'lg:Translation' => '翻訳'
	,'lg:Translate' => '翻訳'
	,'lg:Lite' => '引用符付きフレーズの翻訳回避'
	,'lg:Rich' => '原語表示'
	,'lg:Clear' => 'クリア'
	,'lg:%d_Sec.' => '%d 秒'
	,'lg:Stop' => '中止'
	,'lg:The_size_of_text_area_cannot_be_reduced_anymore.' => 'テキストエリアのサイズをこれ以上小さくすることはできません。'
	,'lg:The_font_size_cannot_be_reduced_anymore.' => '文字のサイズをこれ以上小さくすることはできません。'
	// * Back-Translation
	,'lg:Back_Translation' => '折り返し翻訳'
	// * Page Dictionary
	,'lg:Page_Dictionary' => 'ページ辞書'
	,'lg:Add_record' => '行の追加'
	,'lg:Delete_record' => '行の削除'
	,'lg:Add/Delete_language' => '言語の追加・削除'
	,'lg:Download' => 'ダウンロード'
	,'lg:Updated' => '更新'
	,'lg:Search' => '検索'
    ,'lg:Entry_count' => '辞書エントリー数'
	// * Include Page Dictionary
	,'lg:Include_Page_Dictionary' => 'ページ辞書の読み込み'
	,'lg:Include_Dictionary' => '辞書の読み込み'
	,'lg:Include' => '読み込む'
	,'lg:Now_including...' => '読み込み中...'
	// * Import Page Dictionary
	,'lg:Import_dictionary' => '辞書のインポート'
	,'lg:Import' => 'インポート'
	,'lg:Now_importing...' => 'インポート中...'
	,'lg:Title' => '記事タイトル'
	,'lg:Delete' => '削除'
	,'lg:The_file_format_is_invalid.' => 'ファイル形式が正しくありません'
	// * Upload Page Dictionary
	,'lg:Upload_dictionary' => '辞書のアップロード'
	,'lg:Upload' => 'アップロード'
	,'lg:Upload_file' => '辞書ファイル'
	// * License Information
	,'lg:License_Information' => 'ライセンス情報'
	,'lg:Service_Name' => 'サービス名'
	,'lg:Copyright' => '著作権情報'

	// Select Language 
	,'lg:Create_Page_Dictionary' => 'ページ辞書の作成'
	,'lg:Create' => '作成'
	,'lg:Add/Delete_Languages' => '言語の追加・削除'
	,'lg:At_least_two_languages_are_required_in_the_dictionary.' => '二つ以上の言語を選択してください。'
	,'lg:The_dictionary_was_not_saved_after_the_last_change.' => '最後の変更から保存されていません。'
);
