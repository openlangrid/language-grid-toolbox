
var AjaxWrapper = Class.create({

	_onSuccess: null,
	_onFailure: null,
	_onComplete: null,

	initialize: function(url, action, options) {
		var options = Object.extend({
			method: 'POST',	
			parameters: '',
			onSuccess: function(){},
			onFailure: function(){},
			onComplete: function(){}
		}, options || null);

		var params = options.parameters.toQueryParams();

		this._onSuccess = options.onSuccess;
		this._onFailure = options.onFailure;
		this._onComplete = options.onComplete;

		sajax_request_type = options.method;
		sajax_do_call(url, [action, options.parameters], this.onComplete.bind(this, params));
	},

	onComplete: function(params, response) {
		try {
			if (response.status != 200) {
//				throw new Error(response.responseText);
				throw new Error('Error!');
			}

			this._onSuccess(params, response);
		} catch (e) {
			this._onFailure(e, params, response);
		} finally {
			this._onComplete();
		}
	}
});
