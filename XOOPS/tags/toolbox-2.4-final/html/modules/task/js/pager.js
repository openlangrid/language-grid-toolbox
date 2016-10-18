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
				pager.cache[container.id][link.href] = $(link.href);

				pager.callback(link.href);
				pager.link_click();
			}
		});
	}

	function hideAllPages() {
		container.childElements().each(function(page) {
			page.hide();
		});
	}

	function removeAllPages() {
		container.childElements().each(function(page) {
			pager.cache[container.id][page.id] = null;
			page.remove();
		});
	}
};

Pager.prototype.link_click = function() {
	var pager = this;
	$$("a.perPageLink, a.pageLink").each(function(a) {
		a.observe("click", function(event) {
			event.stop();
			pager.redraw(this);
		});
	});
};
