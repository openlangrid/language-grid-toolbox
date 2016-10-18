<?php

class MetaTranslator {
	
	private $sourceLang;
	private $noTranslations;
	private $tags_begin=array();
	private $tags_end=array();
	const TAG_BEGIN="<notranslate>";
	const TAG_END="</notranslate>";
	const TAG_RELAXED='__________';
	const LANG_TAG_BEGIN='[';
	const LANG_TAG_END=']';
	const SOURCE_LANG_ORDER = 0;
	const TARGET_LANG_ORDER = 1;
	const SOURCE_ORDER = 2;
	
	/**
	 * Constructor
	 * @param $sourceLang SourceLanguage
	 */
	public function __construct($sourceLang) {
		$this->setSourceLanguage($sourceLang);
	}
	
	public function metaTranslate($func, $param, $order, $meta=true, $attach=true) {
		$sourceLang=$param[$order[SOURCE_LANG_ORDER]];
		$targetLang=$param[$order[TARGET_LANG_ORDER]];
		$source=$param[$order[SOURCE_ORDER]];
		$encode=$this->preTranslation($source, $meta);
		$trans=$this->doTranslate($func, $param, $order, $sourceLang, $targetLang, $encode);
		if ($trans==null) {
			return null;
		}
		$newparam=$param;
		$newparam[$order[SOURCE_ORDER]]=$trans['contents'];
		$decode=$this->postTranslation($func, $newparam, $order, $meta, $attach);
		$trans['contents'] = $decode;
		return $trans;
	}
	
	/**
	 * PreProcess
	 * @param $source  Translated sentence
	 * @param $meta  MetaTranslate or not
	 * @return Encoded sentence
	 */
	public function preTranslation($source, $meta=true) {
		$this->noTranslations=array();
		$escEncode='';		
		/* no-MetaTranslate */
		if ($meta) {
			$this->tags_begin[]='[relax]';
			$this->tags_begin[]=TAG_BEGIN;
			$this->tags_end[]='[/relax]';
			$this->tags_end[]=TAG_END;
		}
		/* Encoding */
		$escEncode=$this->encodeEscapes_relaxed($source);			
		return $escEncode;
	}
	
	/**
	 * PostProcess
	 * @param  $func  Translator
	 * @param  $param  Parameters for $func
	 * @param  $order  Parameter position : $param[$order[0]]=SourceLanguage,  $param[$order[1]]=TargetLanguage, $param[$order[2]]=Source
	 * @param  $meta  MetaTranslate or not
	 * @param  $attach  Whether Description is necessary : Unnecessary->false
	 * @param  $resultFunc  Such function that $resultFunc($func($param)) returns sentence
	 * @return Sentence
	 */
	public function postTranslation($func, $param, $order, $meta=true, $attach=true) {
		$source=$param[$order[SOURCE_ORDER]];		
		/* attach Descriptions */
		if ($meta) {
			$this->attachDescriptions($func, $param, $order, $meta, $attach);
		}
		/* Decode */
		$result=$this->decodeEscapes($source);	
		return $result;
	}
	
	/**
	 * Replacing Intermediate Code into Escapes
	 * @param $source  Encoded sentence
	 * @return Decoded sentence
	 */
	private function decodeEscapes($source) {
		for($i=0; $i<count($this->noTranslations); $i++) {
			$source=str_ireplace($this->noTranslations[$i]['code'],
			$this->noTranslations[$i]['escape'], $source);
		}
		return $source;
	}
	
	/**
	 * Attaching Descriptions
	 */
	private function attachDescriptions($func, $param, $order, $meta, $attach) {
		$sourceLang=$param[$order[SOURCE_LANG_ORDER]];
		$targetLang=$param[$order[TARGET_LANG_ORDER]];
		/* for all noTranslations */
		for ($i = 0; $i < count($this->noTranslations); $i++) {
			if ($attach) {
				/* Recursive MetaTranslation */
				$meta=new MetaTranslator($sourceLang);
				$encode=$meta->preTranslation($this->noTranslations[$i]['escape']);
				$trans=$this->doTranslate($func, $param, $order, $sourceLang, $targetLang, $encode);
				$newparam=$param;
				$newparam[$order[SOURCE_ORDER]]=$trans['contents'];
				$description=$meta->postTranslation($func, $newparam, $order, true, true);
				/* attaching */
				if($this->canAttach($this->noTranslations[$i]['escape'], $description)) {
					$this->noTranslations[$i]['escape'].='('.$description.')';
				}
			}
			$tagnum=$this->noTranslations[$i]['tag'];
	  		if ($tagnum!=0) {
				$this->noTranslations[$i]['escape']=$this->tags_begin[$tagnum].
				$this->noTranslations[$i]['escape'].$this->tags_end[$tagnum];
			} else {
				$this->noTranslations[$i]['escape']=TAG_RELAXED.
				$this->noTranslations[$i]['escape'].TAG_RELAXED;
			}
		}
	}
	/**
	 * Whether to Attach Descriptions
	 */
	private function canAttach($escape, $description) {
		/* punctuations */
		$pattern='[\.| |．|,|，|　|:|：|;|。|、|!|！|\?|？]';	
		$comp1=@preg_replace($pattern, '', $escape);
		$comp2=@preg_replace($pattern, '', $description);
		return $comp1!==$comp2;
	}
	private function doTranslate($func, $param, $order, $sourceLang, $targetLang, $source) {
		$param[$order[SOURCE_LANG_ORDER]]=$sourceLang;
		$param[$order[TARGET_LANG_ORDER]]=$targetLang;
		$param[$order[SOURCE_ORDER]]=$source;	
		$result = $this->translateHtml($func, $param, $order, $sourceLang, $targetLang, $source);
		return $result;
	}
	private function encodeEscapes_relaxed($source) {
		$array=explode(TAG_RELAXED, $source);
		$relaxed='';
		for ($i = 0; $i < count($array) - 1; $i++) {
			$relaxed.=$array[$i];
			if ($i % 2 == 0) {
				$relaxed.=$this->tags_begin[0];
			} else {
				$relaxed.=$this->tags_end[0];
			}
		}
		$relaxed.=$array[count($array)-1];	
		return $this->encodeEscapes_strict($relaxed);
	}
	private function encodeEscapes_strict($source) {
		$encode='';
		$escape='';
		$indent=0;
		$id=0;
		$tagStart=0;
		$tagEnd=0;
		$buf='';
		for ($i = 0; $i < mb_strlen($source); $i++) {
			$buf.=mb_substr($source, $i, 1);
			/* escape begins */
			$len=$this->startsWith($this->tags_begin, $buf);
			if ($len != -1) {
				/* before tag, do not escape */
				if ($indent == 0) {
					$encode.=mb_substr($buf, 0, mb_strlen($buf)-$len);
					$tagStart=$i;
				} else {
					$escape.=$buf;
				}
				$buf='';
				$indent++;
				continue;
			}
			/* escape ends */
			$len=$this->startsWith($this->tags_end, $buf, $tagNumber);
			if ($len != -1) {
				$indent--;
				$escape.=$buf;
				/* after tag, do not escape */
				if ($indent == 0) {
					$escape = mb_substr($escape, 0, mb_strlen($escape)-$len);
					$escape = $this->removeDescription($escape);
					$escape = trim($escape);
					$tagEnd = $i;
					$interCode = $this->getInterCode($tagStart, $tagEnd);
					$this->noTranslations[$id]=array('escape'=>$escape, 'code'=>$interCode, 'tag'=>$tagNumber);
					$id++;
					$encode.=' '.$interCode.' ';
					$escape='';
				}
				$buf = '';
				continue;
			}
		}
		return $encode.$buf;
	}
	private function startsWith($array, $str, &$num=null) {
		for ($i=0; $i<count($array); $i++) {
			if (strpos($str, $array[$i])!==false) {
				$num=$i;
				return mb_strlen($array[$i]);
			}
		}
		return -1;
	}
	private function removeDescription($source) {
		$ret='';
		$indent=0;			
		for ($i=0; $i<mb_strlen($source); $i++) {
			$buf=mb_substr($source, $i, 1);
			if ($buf=='(') {
				$indent++;
			}
			if ($indent==0) {
				$ret.=$buf;
			}
			if ($buf==')') {
				$indent--;
			}
		}
		return $ret;
	}
	private function getInterCode($tagStart, $tagEnd) {
		$alt=dechex($tagStart).'x'.dechex($tagEnd);
		$search=array('0','1','2','3','4','5','6','7','8','9');
		$replace=array('g','h','i','j','k','l','m','n','o','p');
		$result=str_replace($search, $replace, $alt);
		return 'xxx'.$result.'xxx';
	}
	public function setSourceLanguage($sourceLang) {
		$this->sourceLang=$sourceLang;
	}
	public function getSourceLanguage() {
		return $this->sourceLang;
	}
	public function translateHtml($func, $param, $order, $sourceLang, $targetLang, $source) {
		$unTag = $this->removeTags($source);
		$param[$order[SOURCE_ORDER]] = $unTag;
		$result = $this->translate($func, $param, $sourceLang, $targetLang, $unTag);
		return $result;
	}
	private function removeTags($source) {
		return preg_replace('/<[^<]+?>/', '', $source);
	}
	private function translate($func, $param) {
		return call_user_func_array($func, $param);
	}
}
?>
