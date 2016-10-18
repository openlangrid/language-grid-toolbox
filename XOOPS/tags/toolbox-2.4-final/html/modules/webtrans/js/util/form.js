/**********************************************************************
* /js/util/form.js
* Copyright (C) 2007-2008 Kyoto University
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-
* 1301  USA
***********************************************************************/
var HelpPanel = Class.create();

HelpPanel.prototype = {
	initialize: function(name,message){
		/*
		this.id	= name+'-help-panel';
		this.buttonId = name+'-help-button';
		this.message = message;
		new Insertion.Bottom($$('body')[0],this.getHTML());
		$(this.id).hide();
		this.setEvent();
		*/
	},

	toggle: function(x,y){
		//Toggler.toggleFastWithPosition(x,y,this.id);
	},

	getHTML: function(){
		/*
		return '<div id="'+this.id+'" class="help-window">'+
				'<div class="help-close"><img src="img/common/help_close.gif" alt="close" onclick="$(\''+this.id+'\').hide();" /></div>'+
				this.message+'<br /><div class="help-close-bottom" onclick="$(\''+this.id+'\').hide();">Close this box</div></div>';
		*/
		return "";
	},

	visible: function(){
		//return $(this.id).visible();
		return 100;
	},

	getWidth: function(){
		//return $(this.id).getWidth();
		return 100;
	},

	setEvent: function(){
		/*
		Event.observe(this.buttonId, 'click', function(event){
			var element = $(Event.element(event));
			var buttonPosition = element.cumulativeOffset();
			var buttonHeight = element.getHeight();
			var buttonWidth = element.getWidth();
			var helpPanelWidth = $$('.help-window')[0].getWidth();
			var bodyPosition = $$('body')[0].cumulativeOffset();
			var x = buttonPosition[0] - bodyPosition[0] - helpPanelWidth + buttonWidth;
			var y = buttonPosition[1] - bodyPosition[1] + buttonHeight;
			var panelId = element.id.substring(0,element.id.length-'button'.length) + 'panel';
			Toggler.toggleFastWithPosition(x,y,panelId);
		});
		*/
	}
};

Toggler = {
	//* @param {String} id
	toggleFast: function(id){
		if(!$(id)){alert(id);}
		$(id).toggle();
	},
	//* @param {Object} x
	//* @param {Object} y
	//* @param {Object} id
	toggleFastWithPosition: function(x,y,id){
		$(id).setStyle({
			position : 'absolute' ,
			left : x+'px' ,
			top : y+'px'
		});
		$(id).toggle();
	},
	//* @param {String} id
	//* @see scriptaculous
	toggleSlide: function(id){
		new Effect.toggle(id,'slide');
	},
	//* @param {String} id
	//* @see scriptaculous
	toggleBlind: function(id){
		new Effect.toggle(id,'blind');
	},
	//* @param {String} id
	//* @see scriptaculous
	toggleAppear: function(id){
		new Effect.toggle(id,'appear');
	},
	//* @param {Object} name
	setToggleEvent: function(name, initializer){
		$('img-open-'+name).hide();
		Event.observe('img-close-'+name, 'click', function(){
			Toggler.toggleFast(name+'-form-area');
			$('img-close-'+name).hide();
			$('img-open-'+name).show();
		});
		Event.observe('img-open-'+name, 'click', function(){
			Toggler.toggleFast(name+'-form-area');
			$('img-close-'+name).show();
			$('img-open-'+name).hide();
			if (!Object.isUndefined(initializer)) {
				initializer;
			}
		});
	}
};
