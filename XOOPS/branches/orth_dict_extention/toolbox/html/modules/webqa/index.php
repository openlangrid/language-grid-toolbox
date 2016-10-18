<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
require('../../mainfile.php');

//$root =& XCube_Root::getSingleton(); // お約束の一行
//var_dump($root->mContext->mModule->mXoopsModule->get('dirname')); //ディレクトリ名
//var_dump($root->mContext->mModule->mXoopsModule->get('mid')); //mid
//var_dump($root->mContext->mModule->mXoopsModule->get('name')); //モジュール名

echo '<a href="'.XOOPS_URL.'/modules/webqa/public/getlanguage_post.php">getlanguage_post.php</a>';
echo '<br>';

echo '<a href="'.XOOPS_URL.'/modules/webqa/public/getlanguage_search.php">getlanguage_search.php</a>';
echo '<br>';

echo '<a href="'.XOOPS_URL.'/modules/webqa/public/search.php?word=test&use_lang=en&order=date&num=10page=1">search.php</a>';
echo '<br>';

echo '<a href="'.XOOPS_URL.'/modules/webqa/public/load.php?id=2&use_lang=en">load.php</a>';
echo '<br>';

echo '<a href="'.XOOPS_URL.'/modules/webqa/public/getcategory.php">getcategory.php</a>';
echo '<br>';

//echo '<a href="'.XOOPS_URL.'/modules/webqa/public/post.php?use_lang=en&title=january&question=february">post.php</a>';
echo '<a href="'.XOOPS_URL.'/modules/webqa/public/post.php?use_lang=ja&title=一月&question=なんで">post.php</a>';
echo '<br>';

echo '<a href="'.XOOPS_URL.'/modules/webqa/index_backgroundtest.php">index_backgroundtest.php</a>';
echo '<br>';

?>