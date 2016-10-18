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
