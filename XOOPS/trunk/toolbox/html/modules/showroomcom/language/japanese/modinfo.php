<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) $mydirname = 'd3forum' ;
$constpref = '_MI_' . strtoupper( $mydirname ) ;

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( $constpref.'_LOADED' ) ) {

define( $constpref.'_LOADED' , 1 ) ;

// The name of this module
define($constpref."_NAME","�ե������");

// A brief description of this module
define($constpref."_DESC","XOOPS�ե������⥸�塼��");

// Names of blocks for this module (Not all module has blocks)
define($constpref."_BNAME_LIST_TOPICS","�ȥԥå�����");
define($constpref."_BDESC_LIST_TOPICS","���ѥ֥�å������Խ��פ��͡��ʵ�ǽ���뤳�Ȥ��Ǥ��ޤ�");
define($constpref."_BNAME_LIST_POSTS","��ư���");
define($constpref."_BNAME_LIST_FORUMS","�ե���������");

// admin menu
define($constpref.'_ADMENU_CATEGORYACCESS','���ƥ��꡼��������');
define($constpref.'_ADMENU_FORUMACCESS','�ե�����ย������');
define($constpref.'_ADMENU_ADVANCEDADMIN','���ɥХ󥹴���');
define($constpref.'_ADMENU_POSTHISTORIES','����Խ�/�������');
define($constpref.'_ADMENU_MYLANGADMIN' , '�8�������' ) ;
define($constpref.'_ADMENU_MYTPLSADMIN' , '�ƥ�ץ졼�ȴ���' ) ;
define($constpref.'_ADMENU_MYBLOCKSADMIN' , '�֥�å�����/������������' ) ;
define($constpref.'_ADMENU_MYPREFERENCES' , '��������' ) ;

// configurations
define($constpref.'_TOP_MESSAGE','�ե������ȥåפΥ�å�����');
define($constpref.'_TOP_MESSAGEDEFAULT','<h1 class="d3f_title">�ե������ȥå�</h1><p class="d3f_welcome">��̣�Τ���ե������ؤ��Ҥ����ä��$���</p>');
define($constpref.'_SHOW_BREADCRUMBS','�ѥ󤯤���ɽ������');
define($constpref.'_DEFAULT_OPTIONS','��ƥ��ץ����Υǥե������');
define($constpref.'_DEFAULT_OPTIONSDSC','�����ȥԥå����ֿ��ν����֤�ͭ��ȤʤäƤ��륪�ץ����򥫥��(,)�Ƕ��ڤä����Ϥ��ޤ���<br />���󥹥ȡ���ľ��� smiley,xcode,br,number_entity �ȤʤäƤ��ޤ���<br />����¾�� special_entity html attachsig u2t_marked ������ǽ�Ǥ�');
define($constpref.'_ALLOW_HTML','�����ʸ���HTML���Ĥ���');
define($constpref.'_ALLOW_HTMLDSC','�����ʸ��HTML�ü�ʸ����Ĥ��ޤ���������¿��˵�Ĥ���ȡ�Script Insertion �ȼ�-�ˤĤʤ���ޤ�');
define($constpref.'_ALLOW_TEXTIMG','�����ʸ��γ��������Ĥ���');
define($constpref.'_ALLOW_TEXTIMGDSC','�����ʸ��[img]�����ǳ�����Ȥβ����ɽ��������ȡ����Υ����Ȥ�ˬ��Ԥ�IP��User-Agent��ȴ����뤳�ȤˤĤʤ���ޤ�');
define($constpref.'_ALLOW_SIG','��̾��Ϳ���Ĥ���');
define($constpref.'_ALLOW_SIGDSC','�����ʸ�β���˽�̾���Ĥ�����褦�ˤʤ�ޤ�');
define($constpref.'_ALLOW_SIGIMG','��̾��γ��������Ĥ���');
define($constpref.'_ALLOW_SIGIMGDSC','��̾��[img]�����ǳ�����Ȥβ����ɽ��������ȡ����Υ����Ȥ�ˬ��Ԥ�IP��User-Agent��ȴ����뤳�ȤˤĤʤ���ޤ�');
define($constpref.'_USE_VOTE','��ɼ��ǽ�����Ѥ���');
define($constpref.'_USE_SOLVED','���ѵ�ǽ�����Ѥ���');
define($constpref.'_ALLOW_MARK','���ܥȥԥå���ǽ�����Ѥ���');
define($constpref.'_ALLOW_HIDEUID','�桼����̾p�򱣤�����Ƥ��뤳�Ȥ��Ĥ���');
define($constpref.'_POSTS_PER_TOPIC','�ȥԥå��������ƿ�');
define($constpref.'_POSTS_PER_TOPICDSC','��ƿ���ο����ã�����ȥԥå��ϼ�ưŪ�˥�å�����ޤ�');
define($constpref.'_HOT_THRESHOLD','�͵��ȥԥå���ƿ�');
define($constpref.'_HOT_THRESHOLDDSC','��9��夬�äƤ���ץ���åɤ��ɤ�����Ƚ�Ǥ�����Ȥʤ���ƿ�Ǥ�');
define($constpref.'_TOPICS_PER_PAGE','�ȥԥå�����Ǥ�ɽ���ȥԥå���');
define($constpref.'_TOPICS_PER_PAGEDSC','');
define($constpref.'_VIEWALLBREAK','�ȥԥå�����ǤΥڡ���ʬ��ñ��');
define($constpref.'_VIEWALLBREAKDSC','');
define($constpref.'_SELFEDITLIMIT','�����Խ��������ߥå�(��)');
define($constpref.'_SELFEDITLIMITDSC','���̥桼������ʬ����Ƥ��Խ������硢��Ƥ��Ƥ��鲿�äޤ����Ƥ��ѹ����Ĥ��뤫�����̥桼���ˤ�뼫���Խ���ػߤ������0�����');
define($constpref.'_SELFDELLIMIT','���ʺ����ߥå�(��)');
define($constpref.'_SELFDELLIMITDSC','���̥桼������ʬ����Ƥ�����硢��Ƥ��Ƥ��鲿�äޤǺ����Ĥ��뤫�����$������̥桼���ϡ����β��˥쥹�ݥ󥹤ΤĤ��Ƥ��ޤä���ƤϺ��Ǥ��ޤ��󡣰��̥桼���ˤ�뼫�ʺ���ػߤ������0�����');
define($constpref.'_CSS_URI','�⥸�塼����CSS��URI');
define($constpref.'_CSS_URIDSC','���Υ⥸�塼�����Ѥ�CSS�ե������URI�����Хѥ��ޤ������Хѥ��ǻ��ꤷ�ޤ����ǥե���Ȥ� {mod_url}/index.php?page=main_css �Ǥ���');
define($constpref.'_IMAGES_DIR','���᡼���ե�����ǥ��쥯�ȥ�');
define($constpref.'_IMAGES_DIRDSC','���Υ⥸�塼���ѤΥ��᡼������Ǽ���줿�ǥ��쥯�ȥ��⥸�塼��ǥ��쥯�ȥ꤫������Хѥ��ǻ��ꤷ�ޤ����ǥե���Ȥ�images�Ǥ���');
define($constpref.'_BODY_EDITOR','��ʸ�Խ����ǥ���');
define($constpref.'_BODY_EDITORDSC','WYSIWYG���ǥ����ϡ�HTML�������Ĥ���ե������ǤΤ�ͭ��ˤʤ�ޤ���HTML�������Ĥ��ʤ��ե������Ǥ�̵����xoopsdhtml�Ȥʤ�ޤ���');
define($constpref.'_ANONYMOUS_NAME','�����ȥ桼���Υǥե����̾');
define($constpref.'_ANONYMOUS_NAMEDSC','����������ƥե�����˺ǽ�����Ϥ���Ƥ���̾p�Ǥ�����ƿ̾���󡢤�ʢ���äѤ�����');
define($constpref.'_ICON_MEANINGS','��ơʥ�������ˤΰ�̣�Ť�');
define($constpref.'_ICON_MEANINGSDSC','��Ƥ�-�ʤ��뤿���j���Ǥ����ѥ���(|)�Ƕ��ڤäƤ��$������ǽ餬0�Ǽ���1���ֹ椬�����Ƥ�졢posticon(���).gif����������Ȥ����Ѥ����ޤ�');
define($constpref.'_ICON_MEANINGSDEF','�ʤ�|�̾�|����|��­|����|�夲|���|����');
define($constpref.'_GUESTVOTE_IVL','��ƤؤΥ�������ɼ');
define($constpref.'_GUESTVOTE_IVLDSC','�������(post)�ؤΥ����Ȥˤ����ɼ��ػߤ������0����ɼ���Ĥ�����ϡ�Ʊ��IP����κ���ɼ��ػߤ����ÿ����ꤷ�ޤ���');
define($constpref.'_ANTISPAM_GROUPS','SPAM��ƥe��å���Ԥ����롼��');
define($constpref.'_ANTISPAM_GROUPSDSC','�̾��t��̤j��Ǥ���SPAM��ƥe��å���Ԥ��٤����롼�פ�������Τ�j�򤷤Ƥ��$�����');
define($constpref.'_ANTISPAM_CLASS','SPAM��ƥe��å��ѥ��饹');
define($constpref.'_ANTISPAM_CLASSDSC','�ǥե���Ȥ� default �Ǥ��������ȤˤĤ��Ƥ�SPAM��ƥe��å���Ԥ�ʤ����Ϥ��������ˤ��ޤ���');


// Notify Categories
define($constpref.'_NOTCAT_TOPIC', 'ɽ����Υȥԥå�'); 
define($constpref.'_NOTCAT_TOPICDSC', 'ɽ����Υȥԥå����Ф������Υ��ץ����');
define($constpref.'_NOTCAT_FORUM', 'ɽ����Υե������'); 
define($constpref.'_NOTCAT_FORUMDSC', 'ɽ����Υե��������Ф������Υ��ץ����');
define($constpref.'_NOTCAT_CAT', 'ɽ����Υ��ƥ���');
define($constpref.'_NOTCAT_CATDSC', 'ɽ����Υ��ƥ�����Ф������Υ��ץ����');
define($constpref.'_NOTCAT_GLOBAL', '�⥸�塼��t��');
define($constpref.'_NOTCAT_GLOBALDSC', '�ե������⥸�塼��t�Τˤ��������Υ��ץ����');

// Each Notifications
define($constpref.'_NOTIFY_TOPIC_NEWPOST', '�ȥԥå������');
define($constpref.'_NOTIFY_TOPIC_NEWPOSTCAP', '���Υȥԥå�����Ƥ����ä��������Τ���');
define($constpref.'_NOTIFY_TOPIC_NEWPOSTSBJ', '[{X_SITENAME}] {X_MODULE}:{TOPIC_TITLE} �ȥԥå������ {POST_TITLE}');

define($constpref.'_NOTIFY_FORUM_NEWPOST', '�ե�����������');
define($constpref.'_NOTIFY_FORUM_NEWPOSTCAP', '���Υե���������Ƥ����ä��������Τ���');
define($constpref.'_NOTIFY_FORUM_NEWPOSTSBJ', '[{X_SITENAME}] {X_MODULE}:{FORUM_TITLE} �ե����������� {POST_TITLE}');

define($constpref.'_NOTIFY_FORUM_NEWTOPIC', '�ե�������⿷�ȥԥå�');
define($constpref.'_NOTIFY_FORUM_NEWTOPICCAP', '���Υե������ˤ����ƿ����ȥԥå���Ω�Ƥ�줿�������Τ���');
define($constpref.'_NOTIFY_FORUM_NEWTOPICSBJ', '[{X_SITENAME}] {X_MODULE}:{FORUM_TITLE} �ե�������⿷�ȥԥå� {TOPIC_TITLE}');

define($constpref.'_NOTIFY_CAT_NEWPOST', '���ƥ��������');
define($constpref.'_NOTIFY_CAT_NEWPOSTCAP', '���Υ��ƥ������Ƥ����ä��������Τ���');
define($constpref.'_NOTIFY_CAT_NEWPOSTSBJ', '[{X_SITENAME}] {X_MODULE}:{CAT_TITLE} ���ƥ�������� {POST_TITLE}');

define($constpref.'_NOTIFY_CAT_NEWTOPIC', '���ƥ����⿷�ȥԥå�');
define($constpref.'_NOTIFY_CAT_NEWTOPICCAP', '���Υ��ƥ���ˤ����ƿ����ȥԥå���Ω�Ƥ�줿�������Τ���');
define($constpref.'_NOTIFY_CAT_NEWTOPICSBJ', '[{X_SITENAME}] {X_MODULE}:{CAT_TITLE} ���ƥ����⿷�ȥԥå� {TOPIC_TITLE}');

define($constpref.'_NOTIFY_CAT_NEWFORUM', '���ƥ����⿷�ե������');
define($constpref.'_NOTIFY_CAT_NEWFORUMCAP', '���Υ��ƥ���ˤ����ƿ��ե�����बΩ�Ƥ�줿�������Τ���');
define($constpref.'_NOTIFY_CAT_NEWFORUMSBJ', '[{X_SITENAME}] {X_MODULE}:{CAT_TITLE} ���ƥ����⿷�ե������ {FORUM_TITLE}');

define($constpref.'_NOTIFY_GLOBAL_NEWPOST', '�����t��');
define($constpref.'_NOTIFY_GLOBAL_NEWPOSTCAP', '���Υ⥸�塼��t�ΤΤ����줫����Ƥ����ä��������Τ���');
define($constpref.'_NOTIFY_GLOBAL_NEWPOSTSBJ', '[{X_SITENAME}] {X_MODULE}: ��� {POST_TITLE}');

define($constpref.'_NOTIFY_GLOBAL_NEWTOPIC', '���ȥԥå�t��');
define($constpref.'_NOTIFY_GLOBAL_NEWTOPICCAP', '���Υ⥸�塼��t�ΤΤ����줫�˿����ȥԥå���Ω�Ƥ�줿�������Τ���');
define($constpref.'_NOTIFY_GLOBAL_NEWTOPICSBJ', '[{X_SITENAME}] {X_MODULE}: ���ȥԥå� {TOPIC_TITLE}');

define($constpref.'_NOTIFY_GLOBAL_NEWFORUM', '���ե������t��');
define($constpref.'_NOTIFY_GLOBAL_NEWFORUMCAP', '���Υ⥸�塼��t�ΤΤ����줫�˿��ե�����बΩ�Ƥ�줿�������Τ���');
define($constpref.'_NOTIFY_GLOBAL_NEWFORUMSBJ', '[{X_SITENAME}] {X_MODULE}: ���ե������ {FORUM_TITLE}');

define($constpref.'_NOTIFY_GLOBAL_NEWPOSTFULL', '���tʸ');
define($constpref.'_NOTIFY_GLOBAL_NEWPOSTFULLCAP', '���tʸ�����Τ��ޤ������оݤϥ⥸�塼��t�Ρ�');
define($constpref.'_NOTIFY_GLOBAL_NEWPOSTFULLSBJ', '[{X_SITENAME}] {POST_TITLE}');
define($constpref.'_NOTIFY_GLOBAL_WAITING', '��ǧ�Ԥ�');
define($constpref.'_NOTIFY_GLOBAL_WAITINGCAP', '��ǧ���פ�����ơ��Խ����Ԥ�줿�������Τ��ޤ������������');
define($constpref.'_NOTIFY_GLOBAL_WAITINGSBJ', '[{X_SITENAME}] {X_MODULE}: ��ǧ�Ԥ� {POST_TITLE}');

}

?>