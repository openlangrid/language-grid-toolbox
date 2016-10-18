
var LicenseArea = Class.create({

	_licenseArea: null,

	initialize: function(licenseArea) {
		this._licenseArea = licenseArea;
	},

	filter: function(licenseInformation) {
		$H(licenseInformation).each(function(pair) {
			if (!pair.value.serviceName) {
				delete licenseInformation[pair.key];
			}
		});
	},

	update: function(licenseInformation) {
		this._licenseArea.update('');

		this.filter(licenseInformation);

		$H(licenseInformation).each(function(pair, i) {
			var license = pair.value;

			var wrap = document.createElement('div');
			if (i) wrap.className = 'licence-box-with-border';

			var name = this.createBox(Const.Message.LicenseInformation, license.serviceName);
			wrap.appendChild(name);

			var copyright = this.createBox(Const.Message.ServiceName, license.serviceCopyright);
			wrap.appendChild(copyright);

			var information = this.createBox(Const.Message.Copyright, license.serviceLicense);
			wrap.appendChild(information);

			this._licenseArea.appendChild(wrap);
		}.bind(this));
	},

	createBox: function(title, body) {
		var box = document.createElement('div');

		var titleBox = document.createElement('div');
		titleBox.appendChild(document.createTextNode(title));
		titleBox.className = 'license-title';
		box.appendChild(titleBox);

		var bodyBox = document.createElement('div');
		bodyBox.innerHTML = this.httpAutoLink(body || '-');
		bodyBox.className = 'license-body';
		box.appendChild(bodyBox);

		return box;
	},

	httpAutoLink : function(text) {
		return text.replace(/(https?|ftp)(\:\/\/[0-9a-zA-Z\+\$\;\?\.\%\,\!\#\~\*\/\:\@\&\=\_\-]+)/g,
				 '<a href="$1$2" target="_blank">$1$2</a>');
	}
});
