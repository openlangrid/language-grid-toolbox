//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
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


Language = {
	
	languageTagArray: [
		['sq','Albanian'],
		['ar','Arabic'],
		['bg','Bulgarian'],
		['ca','Catalan'],
		['zh-CN','Chinese(CN)'],
		['hr','Croatian'],
		['cs','Czech'],
		['da','Danish'],
		['nl','Dutch'],
		['en','English'],
		['et','Estonian'],
		['fi','Finnish'],
		['fr','French'],
		['gl','Galician'],
		['de','German'],
		['el','Greek'],
		['iw','Hebrew'],
		['hi','Hindi'],
		['hu','Hungarian'],
		['id','Indonesian'],
		['it','Italian'],
		['ja','Japanese'],
		['ko','Korean'],
		['lv','Latvian'],
		['lt','Lithuanian'],
		['mt','Maltese'],
		['no','Norwegian'],
		['pl','Polish'],
		['pt','Portuguese'],
		['ro','Romanian'],
		['ru','Russian'],
		['sr','Serbian'],
		['sk','Slovak'],
		['sl','Slovenian'],
		['es','Spanish'],
		['sv','Swedish'],
		['th','Thai'],
		['tr','Turkish'],
		['uk','Ukrainian'],
		['vi','Vietnamese'],

		['zh','Chinese'],
		['zh-TW','Chinese(TW)'],
		['tl','Tagalog']
	],

	_tagToIndex: [],
	_nameToIndex: [],

	_rtl: ['ar', 'iw', 'he'],

	multilingualLanguageName: {
							ja: {bg: 'ブルガリア語',de: 'ドイツ語',en: '英語',es: 'スペイン語',fr: 'フランス語',
								it: 'イタリア語',ja: '日本語',ko: '韓国語',pt: 'ポルトガル語',zh: '中国語', nl: 'オランダ語', ru: 'ロシア語'},
							en: {bg: 'Bulgarian',de: 'German',en: 'English',es: 'Spanish',fr: 'French',
								it: 'Italian',ja: 'Japanese',ko: 'Korean',pt: 'Portuguese',zh: 'Chinese', nl: 'Dutch', ru:'Russian'}
						},
						
	nationalFlagArray: new Array(
						new Pair('en','img/gif/us.gif'),
						new Pair('es','img/gif/es.gif'),
						new Pair('ja','img/gif/jp.gif'),
						new Pair('ko','img/gif/kr.gif'),
						new Pair('zh','img/gif/cn.gif'),
						new Pair('pt','img/gif/pt.gif')
						),
	
	
	separatorArray: {	bg: new Array('.','?','!'),
						de: new Array('.','?','!'),
						en: new Array('.','?','!'), 
						es: new Array('.','?','!'),
						fr: new Array('.','?','!'),
						it: new Array('.','?','!'),
						ja: new Array('。','．','.','？','！','?','!'),
						ko: new Array('.','?','!'),
						pt: new Array('.','?','!'),
						nl: new Array('.','?','!'),
						ru: new Array('.','?','!'),
						zh: new Array('。','？','?','!','！','．','.')
						},
						
	
	
	getNameByTag: function(tag){
		var index = this._tagToIndex[tag];
		if (typeof(index) == 'undefined') {
			return tag;
		}
		return this.languageTagArray[index][1];
	},
	
	getMultilingualLanguageNameByTag: function(tag, baseLanguage){
		if(this.multilingualLanguageName[baseLanguage][tag]) return this.multilingualLanguageName[baseLanguage][tag];
		return null;
	},
	
	
	
	getTagByName: function(name){
		var index = this._nameToIndex[name];
		if (typeof(index) == 'undefined') {
			return name;
		}
		return this.languageTagArray[index][0];
	},

	
	
	
	getTextDirection: function(tag) {
		if (this._rtl.indexOf(tag) >= 0) {
			return 'rtl';
		}
		return 'ltr';
	},
	
	getFlagSrcByTag: function(tag){
		for(i=0;i<this.nationalFlagArray.length;i++){
			if(tag == this.nationalFlagArray[i].getFirst()){
				return this.nationalFlagArray[i].getSecond();
			}
		}
		
		return tag;
	},
	
	
	
	getSeparatorArrayByTag: function(tag){
		if(this.separatorArray[tag] == null || this.separatorArray[tag] == 'undefined'){
			
			return new Array('.','?','!');
		} else {
			return this.separatorArray[tag];
		}
	}
};

var i;
for (i = 0; i < Language.languageTagArray.length; i++) {
	Language._tagToIndex[ Language.languageTagArray[i][0] ] = i;
	Language._nameToIndex[ Language.languageTagArray[i][1] ] = i;
}
delete(i);

ExceptionWordProcessor = { 
	
	exceptionWords: {
		dot: [
			/*a:*/ new Array('a.a.O.','Abb.','a.c.','accel.','A.C.D.','A.D.','ad lib.','ahd.','Al.','a.m.','Anm.','Anon.','approx.','Apr.','Apt.','art.','Aug.','av.','Ave.'),
			/*b:*/ new Array('B.C.','Bd.','Bde.','bld.','bldg.','Blvd.','b.i.d.','bzw.'),
			/*c:*/ new Array('ca.','cf.','chap.','chaps.','cho.','Co.','c.o.d.','col.','col Ped.','Corp.','corp.','cresc.'),
			/*d:*/ new Array('D.C.','Dec.','decresc.','ders.','dept.','d.h.','dimin.','do.','Dr.','D.S.'),
			/*e:*/ new Array('ea.','ed.','eds.','e.g.','enc.','env.','e.o.m.','et al.','etc.','exp.','ex.'),
			/*f:*/ new Array('f.','Feb.','ff.','fig.','Fl.','figs.','fol.','Fri.'),
			/*g:*/ new Array('Gl.','govt.'),
			/*h:*/ new Array('Hg.','hg.','Hgg.','Hrsg.','hmhge.','hrsg.'),
			/*i:*/ new Array('ib.','ibid.','id.','i.e.','Inc.','inc.','inv.','i.q.'),
			/*j:*/ new Array('Jan.','Jg.','Jul.','Jun.','Jr.'),
			/*k:*/ new Array(),
			/*l:*/ new Array('l.','l. H.','ll.','Ln.','l.R','Ltd.','ltd.'),
			/*m:*/ new Array('M.','Mar.','M.D.','mdse.','Messers.','mhd.','M.O.','mo.','Mon.','m.p.h.','Mr.','Mrs.','Ms.'),
			/*n:*/ new Array('n.','N.A.','n.b.','n.d.','nd.','nhd.','Nm.','nn.','no.','nos.','Nov.','n.p.','Nr.','N.S.','n.s.'),
			/*o:*/ new Array('o.','o.B.','O.B.','Oct.','od.','o.J.','O.K.','o.O.','op.cit.'),
			/*p:*/ new Array('p.','par.','pars.','p.c.','Ph.D.','pl.','p.m.','pmk.','P.O.','p.o.','po.','policli.','P.P.','pp.','Prof.','p.s.','P.S.','pseud.'),
			/*q:*/ new Array('q.i.d.','qtr.','q.v.'),
			/*r:*/ new Array('r.','rall.','r.& v.','R.C.D.','Rd.','Re.','rec.','REG.','Ret.','r. H.','rinforz.','rinfz.','rit.','ritard.','Rm.','Rp.'),
			/*s:*/ new Array('S.','s.','s.a.','Sat.','sec.','Seg.','Sep.','Sept.','SFOR.','Sig.','s.l.','smorz.','Sp.','sp. A','spp.','so.','St.','st.','Sun.','Syn.'),
			/*t:*/ new Array('Taf.','T.B.','T.H.I.','Thu.','t.i.d.','trans.','transl.','Tue'),
			/*u:*/ new Array('u.a.','U.K','U.S.A','U.S.','ut.','U.V.','UVs.'),
			/*v:*/ new Array('vgl.','viz.','Vol.','vol.','volz.','V.S.','vs.'),
			/*w:*/ new Array('W.C.','WC.','Wed.','wk.','wks.'),
			/*x:*/ new Array(),
			/*y:*/ new Array('y.','y.o.b.'),
			/*z:*/ new Array('Z.','z.B.'),
			/*other:*/ new Array('ubers.')
		],
		question:[],
		exclamation:[]
	},
	
	config: [
		{name:'dot',symbol:'.',rule:'[[#dot]]'},
		{name:'question',symbol:'?',rule:'[[#question]]'},
		{name:'exclamation',symbol:'!',rule:'[[#exclamation]]'}
	],
	
	/**
	 * @param {String} text
	 * @param {String} symbol
	 */
	encodeExceptionWord: function(text,symbol){
		try{
			//text = this.encodeURL(text);
			
			for(i=0;i<this.config.size();i++){
				if(this.config[i].symbol == symbol){
					var flatArray = this.exceptionWords[this.config[i].name].flatten();
					var head = 0;
					var tail = 0;
					while((tail = text.indexOf(' ',head)) != -1){
						if(flatArray.include(text.substring(head,tail))){
							if(head == 0 || text.substring(head-1).startsWith(' ')){
								var substr = text.substring(head,tail);
								var textHead = text.substring(0,head);
								var textTail = text.substring(tail);
								var dummyArray = substr.split('.');
								for(j=0;j<dummyArray.size()-1;j++){
									substr = substr.replace('\#{symbol}'.interpolate(this.config[i]),this.config[i].rule);
								}
								text = textHead.concat(substr).concat(textTail);
							}
						}
						head = tail+1;
					}
					if(flatArray.include(text.substring(head))){
						if(head == 0 || text.substring(head-1).startsWith(' ')){
							var substr = text.substring(head);
							var textHead = text.substring(0,head);
							var dummyArray = substr.split('.');
							for(j=0;j<dummyArray.size()-1;j++){
								//alert(j+' '+substr);
								substr = substr.replace('\#{symbol}'.interpolate(this.config[i]),this.config[i].rule);
							}
							if(head==0) text = substr;
							else text = textHead.concat(substr);
						}
					}
					break;
				}
			}
			logger.info('I302','System','ExceptionWordProcessor.encodeExceptionWord',null,3);
			return text;
		} catch(e){
			alert(text);
			logger.error('E302','System','ExceptionWordProcessor.encodeExceptionWord',text,3);
		}
	},
	
	/**
	 * @param {String} text
	 */
	decodeExceptionWord: function(text){
		for(i=0;i<this.config.size();i++){
			var dummyArray = text.split(this.config[i].rule);
			for(j=0;j<dummyArray.size()-1;j++){
				text = text.replace(this.config[i].rule,this.config[i].symbol);
			}
		}
		return text;
	},
	
	/**
	 * @param {String} text
	 */
	encodeURL: function(text){
		try{
		var urlArray = ExceptionWordProcessor.urlget(text);
		var index = 0;
		var subtext = text.substring(index); 
		var i=0;
		for(;i<urlArray.size();i+=1){
			var index = text.indexOf(urlArray[i]);
			var textHead = text.substring(0,index);
			var textTail = text.substring(index+(urlArray[i].length));
			var substr = urlArray[i];
			
			var j=0;
			for(;j<this.config.size();j+=1){
				var subindex = 0;
				var count = 0;
				subindex=substr.indexOf(this.config[j].symbol,subindex);
				while(subindex != -1){
					count++;
					subindex++;
					subindex=substr.indexOf(this.config[j].symbol,subindex);
				}
				var k=0;
				for(;k<count;k+=1){
					substr = substr.replace('\#{symbol}'.interpolate(this.config[j]),this.config[j].rule);
				}
			}
			
			text = textHead + substr + textTail;
		}
			logger.info('I302','System','ExceptionWordProcessor.encodeURL',text,3);
		return text;
		} catch(e){
			alert(text);
			logger.error('E302','System','ExceptionWordProcessor.encodeURL',text,3);
		}
	},
	
	/**
	 * @param {String} text
	 */
	encodeDecimalNumber: function(text){
		try{
			var decimalArray = text.match(/[0-9]*\.[0-9]+/g);
			if(decimalArray){
				for(i=0;i<decimalArray.size();i++){
					var index = text.indexOf(decimalArray[i]);
					var textHead = text.substring(0,index);
					var textTail = text.substring(index+(decimalArray[i].length));
					var substr = decimalArray[i];
					
					var j=0;
					for(;j<this.config.size();j+=1){
						var subindex = 0;
						var count = 0;
						subindex=substr.indexOf(this.config[j].symbol,subindex);
						while(subindex != -1){
							count++;
							subindex++;
							subindex=substr.indexOf(this.config[j].symbol,subindex);
						}
						var k=0;
						for(;k<count;k+=1){
							substr = substr.replace('\#{symbol}'.interpolate(this.config[j]),this.config[j].rule);
						}
					}
			
					text = textHead + substr + textTail;
				}
			}
			
			logger.info('I302','System','ExceptionWordProcessor.encodeDecimalNumber',text,3);
			return text;
		} catch(e){
			alert(text);
			logger.error('E302','System','ExceptionWordProcessor.encodeDecimalNumber',text,3);
		}
	},
	
	/**
	 * @param {String} str
	 */
	urlget: function(str){
		try{
			var url = str.match(/s?https?:\/\/[-_.!~*'()a-zA-Z0-9;\/?:@&=+$,%#]+/g);
			if(url == undefined){
				logger.info('I302','System','urlget',null,3);
				return new Array();
			}
			logger.info('I302','System','urlget',null,3);
			return url;
		} catch(e){
			logger.error('E302','System','urlget',str,3);
		}
	}
}
