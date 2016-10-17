var VoiceGenerator = Class.create( {
	lang : 'en',
	cache : null,
	CACHE_LIMIT : 40,
	results : null,
	
	initialize : function(lang) {
		this.cache = new Hashtable();
		this.lang = lang;
		this.results = new Array();
	},
	
	generateAll : function(sentences) {
		this.results = new Array();
		this.generateIter(sentences, 0);
	},
	
	setLang : function(lang) {
		this.lang = lang;
	},
	
	getSentence : function(sentence) {
		for(var i=0; i<this.results['sentence'].length; i++) {
			if(this.results['sentence'][i]==sentence) {
				this.showLoading(sentence);
				
				this.clear();
				var src = '';
				src += this.makesrc(this.results['voiceFile'][i]);
				src += this.makeDownload(this.results['voiceFile'][i]);
				src += this.makeDownloadAll(this.results['whole']);
				document.getElementById('translation-voicearea').innerHTML = src;
				
			
			}
		}
	},
	
	generateIter: function(sentences, i) {
		if(sentences.length==0) {
			this.clear();
			this.generateWholeSentence();
			return;
		}
	
		var params = $H({
			sentence : sentences[0],
			lang : this.lang 
		}).toQueryString();
	
	
		var filename = '';
		
		if(sentences[0]=='' || sentences[0]=='[[#ret]]') {
			sentences.shift();
			this.generateIter(sentences, i);
			return;
		}
		
		this.showGenerating(sentences[0]);
		
		new Ajax.Request(
					'./php/ajax/voicegenerate.php'
				, {
					method : 'post'
					, parameters : params
					, onSuccess : function(response){
						try {
							var obj = response.responseText.evalJSON();
							if(obj==null) {
								alert('Could not Generate Voice:'+ sentence);
							}
							
							var filename = obj[0].data;
							
							this.results[i] = new Array();
							this.results[i]['sentence'] = sentences[0];
							this.results[i]['voiceFile'] = filename;
							
							sentences.shift();
							this.generateIter(sentences, i+1);
							
						}catch(e){
							
						}
					}.bind(this)
				}
			);
	},
	
	generateWholeSentence : function() {
		
		var files = new Array();
		var sentences = new Array();
		
		for(var i=0; i<this.results.length; i++) {
			files.push(this.results[i]['voiceFile']);
			sentences.push(this.results[i]['sentence']);
		}
		this.results = new Array()
		this.results['sentence'] = sentences;
		this.results['voiceFile'] = files;
		
		
		var params = $H({
			files : files.toJSON(),
			sentences : sentences.toJSON()
		}).toQueryString();
	
	
		new Ajax.Request(
					'./php/ajax/appendWaves.php'
				, {
					method : 'post'
					, parameters : params
					, onSuccess : function(response){
						
						this.clear();
						var filename = response.responseText;
						var src = this.makesrc(filename);
						src += this.makeDownloadAll(filename);
						document.getElementById('translation-voicearea').innerHTML = src;
						this.results['whole'] = filename;
						
					}.bind(this)
				}
			);
	},
	
	makesrc : function(filename) {
		var obj = '';
		
		obj += '<object id="voice_obj" classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="400" height="20" CODEBASE="http://www.apple.com/qtactivex/qtplugin.cab">'+"\n"; //QuickTime
 		
 		obj += this.makeParam('width', '350');
 		obj += this.makeParam('height', '20');
 		obj += this.makeParam('autostart', 'false');
 		obj += this.makeParam('repeat', 'false');
 		obj += this.makeParam('loop', 'false');
 		obj += this.makeParam('src', './wav/'+filename);
 		obj += this.makeEmbed(filename);
 		
		obj += '</object>';
		
		return obj;
	},
	
	makeParam : function(name, value) {
		return '<param name="'+name+'" value="'+value+'"></param>'+"\n";
	},
	
	makeEmbed : function(filename) {
		var obj = '';
		
		obj += '<embed';
		obj += ' id="voice_embed"';
		obj += ' src="./wav/'+filename+'"';
		obj += ' width="380"';
		obj += ' height="20"';
		obj += ' autostart="false"';
		obj += ' repeat="false"';
		obj += ' loop="false"';
		obj += ' pluginspage="http://www.apple.co.jp/quicktime/download/"';
		obj += '>';
		return obj;
		
	},
	
	makeDownload : function(filename) {
		var obj = '';
		
		obj += '<a type="application/octet-stream" title="Download the Sentence" href="';
		obj += './wav/' + filename;
		obj += '">';
		obj += '<IMG SRC = "./img/download.jpg" ALT="Download the Sentence" WIDTH="18" HEIGHT="18">';
		obj += '</a>';
		return obj;
	},
	
	makeDownloadAll : function(filename) {
		var obj = '';
		
		obj += '<a type="application/octet-stream" title="Download All Sentences" href="';
		obj += './wav/' + filename;
		obj += '">';
		obj += '<IMG SRC = "./img/downloadall.jpg" ALT="Download All Sentences" WIDTH="18" HEIGHT="18">';
		obj += '</a>';
		return obj;
	},
	
	//clear the voice frame. added 5/27
	clear : function() {
		document.getElementById('translation-voicearea').innerHTML = '';
	},
	
	showGenerating : function(sentence) {
		var buf = '';
		
		buf += '<img src="./img/loading.gif" />'; 
		buf += 'Now Generating Voice... ';
		
		buf += '"'+sentence.substr(0, 12);
		if(sentence.length>12) {
			buf += '...';
		}
		buf += '"';
		
		document.getElementById('translation-voicearea').innerHTML = buf;
	},
	
	showLoading : function(sentence) {
		var buf = '';
		
		buf += '<img src="./img/loading.gif" />'; 
		buf += 'Now Loading Voice... ';
		
		buf += '"'+sentence.substr(0, 12);
		if(sentence.length>12) {
			buf += '...';
		}
		buf += '"';
		
		document.getElementById('translation-voicearea').innerHTML = buf;
	}
});