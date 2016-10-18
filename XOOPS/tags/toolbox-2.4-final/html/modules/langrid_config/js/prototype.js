Array.prototype.deepClone = function() {
	var ret = [];

	for (var i = 0, length = this.length; i < length; i++) {
		if (this[i] instanceof Array) {
			ret[i] = this[i].deepClone();
		} else {
			ret[i] = this[i];
		}
	}
	
	return ret;
};