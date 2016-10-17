//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
function getDefaultMorphologicalAnalyzer(lang){
	switch(lang){
		case 'ja':return 'Mecab';break;
		case 'ko':return 'Klt';break;
		case 'zh':return 'ICTCLAS';break;
		case 'en':return 'TreeTagger';break;
		case 'de':return 'TreeTagger';break;
		case 'fr':return 'TreeTagger';break;
		case 'it':return 'TreeTagger';break;
		case 'es':return 'TreeTagger';break;
		case 'nl':return 'TreeTagger';break;
		case 'ru':return 'TreeTagger';break;
		case 'bg':return 'TreeTagger';break;
		case 'pt':return 'TreeTagger';break;
		default:return '';break;
	}
}
