function Pager(container, perPage, callback) {
	// public fields
	this.clickedContainer = null;
	this.callback = typeof(callback) == "function" ? callback : function() {};
	this.cache = {};
	this.container = $(container);
	this.initCache();
}

Pager.prototype.initCache = function() {
	var pager = this;
	var $c = $(this.container);
	if ($c) {
		// enable capturing phase of event
		$c.observe("mousedown", function(event) {
			pager.clickedContainer = this;
		});
		var pageCache = {};
		var page = $c.down("div");
		if (page) {
			pageCache[page.id] = page;
			pager.link_click(page);
		}
		pager.cache[$c.id] = pageCache;
	}
}

Pager.prototype.redraw = function(link) {
	var container = this.clickedContainer;
	var pages = container.childElements();
	var changedPerPage = link.className.search("perPageLink") != -1;
	var targetPage = this.cache[container.id][link.href];

	if (!changedPerPage && targetPage) {
		// show cached page

		hideAllPages();
		targetPage.show();
	} else {
		// cache new page

		var pager = this;

		new Ajax.Request(link.href, {
			method : "get",
			onComplete : function(httpRequest, json) {
				if (changedPerPage) {
					removeAllPages();
				} else {
					hideAllPages();
				}

				container.insert(httpRequest.responseText);
				var page = $(link.href);
				pager.cache[container.id][link.href] = page;


				pager.callback();
				pager.link_click(page);
			}
		});
	}

	function hideAllPages() {
		pages.each(function(page) {
			page.hide();
		});
	}

	function removeAllPages() {
		pages.each(function(page) {
			page.remove();
		});
	}
};

Pager.prototype.link_click = function(page) {
	var pager = this;
	$$("a.pagePerLink, a.pageLink").each(function(a) {
		if(!a.init) {
			a.init = true;
			a.observe("click", function(event) {
				event.stop();
				pager.redraw(this);
			});
		}
	});
};
