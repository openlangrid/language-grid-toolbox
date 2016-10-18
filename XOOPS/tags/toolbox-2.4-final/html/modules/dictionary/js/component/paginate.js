// controllerNameのクラスにchangePer関数とreload関数を実装する
var Pager = Class.create({
	initialize: function(paginateInfo, controllerName) {
		this.totalNum = parseInt(paginateInfo.totalNum);
		this.perPage = parseInt(paginateInfo.perPage);
		this.currentPageNo = parseInt(paginateInfo.pageNo);
		this.controllerName = controllerName;
	},
	
	_pageNoList: function() {
		return $R(this._firstPageNo(), this._lastPageNo()).toArray();
	},
	
	_pageNoSlicedList: function() {
		var start = this.currentPageNo - Pager.PAGE_LINK_DISPNUM/2 - 1;
		if(start < 0) start = 0;
		var end = this.currentPageNo + Pager.PAGE_LINK_DISPNUM/2 - 1;
		if(end > this._lastPageNo()) end = this._lastPageNo();
		
		if(end - start < Pager.PAGE_LINK_DISPNUM) {
			if(start < Pager.PAGE_LINK_DISPNUM/2) end = Pager.PAGE_LINK_DISPNUM;
			else start = this._lastPageNo() - Pager.PAGE_LINK_DISPNUM; 
		}
		return this._pageNoList().slice(start, end);
	},
	
	_firstPageNo: function() {
		return 1;
	},
	
	_lastPageNo: function() {
		if(this.perPage == -1 || this.totalNum == 0) return 1;
		var num = parseInt(this.totalNum / this.perPage);
		return this.totalNum % this.perPage == 0 ? num : num + 1;
	},
	
	_nextPageNo: function() {
		return this.currentPageNo + 1;
	},
	
	_prevPageNo: function() {
		return this.currentPageNo - 1;
	},
	
	_hasNextPage: function() {
		return this._lastPageNo() > this.currentPageNo;
	},
	
	_hasPrevPage: function() {
		return this.currentPageNo > 1;
	},
	
	_nextPageLink: function() {
		return this._generateLinkOrText(this._hasNextPage(), this._nextPageNo(), Pager.LABEL_NEXT);
	},
	
	_prevPageLink: function() {
		return this._generateLinkOrText(this._hasPrevPage(), this._prevPageNo(), Pager.LABEL_PREV);
	},
	
	_pageNoLink: function(pageNo) {
		return this._generateLinkOrText(pageNo != this.currentPageNo, pageNo, pageNo);
	},
	
	_generateLinkOrText: function(isLink, targetPageNo, label) {
		var param = {'pageNo': targetPageNo, 'label': label, 'controller': this.controllerName};
		return isLink ? 
			Pager.TMPL_PAGE_LINK.interpolate(param) :
			"<span>#{label}</span>".interpolate(param);
	},
	
	printPageInfo: function(target) {
		var offset = (this.currentPageNo-1) * this.perPage + 1;
		var str = (offset) + " - ";
		if(offset + this.perPage - 1 > this.totalNum || this.perPage == -1) {
			str += this.totalNum;
		} else {
			str += (offset + this.perPage - 1);
		}
		str += " of " + this.totalNum;
		target.insert(Pager.TMPL_PAGE_INFO.interpolate({value: str}));
	},
	
	printNumberOfResult: function(target) {
		var perPage = this.perPage;
		var links = Pager.PER_PAGES.map(function(e){
			if(!Object.isArray(e)) e = [e, e];
			return (perPage == e[1]) ? "<span>" + e[0] + "</span>" :
					Pager.TMPL_PERPAGE_LINK.interpolate({perPage: e[1], 'controller': this.controllerName, label: e[0]});
		}.bind(this)).join("&nbsp;|&nbsp;");
		var str = "Number of results " + links;
		target.insert(Pager.TMPL_PERPAGE_INFO.interpolate({value: str}));
	},
	
	printPageLinks: function(target) {
		var pageList = this._pageNoSlicedList();
		var str = this._prevPageLink() + "&nbsp;";
		if(this._firstPageNo() != pageList.first()) str += this._pageNoLink(this._firstPageNo()) + "&nbsp;...&nbsp;"
		str += pageList.map(function(pageNo) {
			return this._pageNoLink(pageNo);
		}.bind(this)).join("&nbsp;");
		if(pageList.last() != this._lastPageNo()) str += "&nbsp;...&nbsp;" + this._pageNoLink(this._lastPageNo());
		str += "&nbsp;" + this._nextPageLink();
		target.insert(Pager.TMPL_PAGE_LINKS.interpolate({value: str}));
	},
	
	paginate: function(target) {
		target.update("");
		this.printPageInfo(target);
		this.printNumberOfResult(target);
		this.printPageLinks(target);
	}
});

Object.extend(Pager, {
	TMPL_PAGE_INFO: "<span class='page-no'>#{value}</span>",
	TMPL_PERPAGE_INFO: "<span class='limit-link'>#{value}</span>",
	TMPL_PERPAGE_LINK: "<a href='javascript:#{controller}.changePerPage(#{perPage});'>#{label}</a>",
	TMPL_PAGE_LINK: "<a href='javascript:#{controller}.reload(#{pageNo});'>#{label}</a>",
	TMPL_PAGE_LINKS: "<span class='page-link'>#{value}</span>",
	LABEL_NEXT: "Next >",
	LABEL_PREV: "< Prev",
	//PER_PAGES: [5, 10, 20, 50, ["All", -1]],
	PER_PAGES: [5, 10, 20, 50],
	PAGE_LINK_DISPNUM: 10
});
