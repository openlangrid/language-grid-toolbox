//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to share
// files with other users.
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
function check_next(selobj,id){
	jQuery.noConflict();
	var postObj = {};
	postObj['command'] = 'check_next';
	postObj['cid'] = selobj.value;
	if($('my_cid')){var mycid = $('my_cid').value;}
	else{var mycid = 0;}
	postObj['my_cid'] = mycid;
	
	var hash = $H(postObj).toQueryString();
	var Target_url = "?page=ajax";
	new Ajax.Request(Target_url,
		{
			method: "post",
			postBody: hash,
			asynchronous:true,
			onSuccess: function(request) {
				try {
					var responseJSON = request.responseText.evalJSON()
					if (responseJSON.status == 'OK') {
						nxtId = id + 1;
						if($('panel'+nxtId)){
							Element.remove($('panel'+nxtId));
						}
						if(responseJSON.contents == true){
							var myPanel = document.createElement('div');
							myPanel.id = 'panel'+nxtId;
							Element.addClassName(myPanel,"folder_sel");
							myPanel.innerHTML = get_plus_btn(nxtId);
							$('select_panels').appendChild(myPanel);
						}
					}else{
						return false;
					}
				}catch(err){
					return false;
				}
			},
			onFailure: function() {},
			onException: function (request, exception) {}
		}
	);
}

function add_select(id){
	jQuery.noConflict();
	var postObj = {};
	postObj['command'] = 'add_select';
	postObj['id'] = id;
	postObj['pid'] = $('cid'+(id-1)).value;
	if($('my_cid')){var mycid = $('my_cid').value;}
	else{var mycid = 0;}
	postObj['my_cid'] = mycid;

	var hash = $H(postObj).toQueryString();
	var Target_url = "?page=ajax";
	new Ajax.Request(Target_url,
		{
			method: "post",
			postBody: hash,
			asynchronous:true,
			onSuccess: function(request) {
				try {
					var responseJSON = request.responseText.evalJSON()
					if (responseJSON.status == 'OK') {
						if($('panel'+id)){
							$('panel'+id).innerHTML = responseJSON.contents;
							//check_next($('cid'+id),id);
							if(responseJSON.hasNext){
								nxtId = id + 1;
								if($('panel'+nxtId)){
									Element.remove($('panel'+nxtId));
								}
								var myPanel = document.createElement('div');
								myPanel.id = 'panel'+nxtId;
								Element.addClassName(myPanel,"folder_sel");
								myPanel.innerHTML = get_plus_btn(nxtId);
								$('select_panels').appendChild(myPanel);
							}
							preId = id - 1;
							if($('minus'+preId)){
								Element.removeClassName($('minus'+preId) , 'btn');
								Element.addClassName($('minus'+preId), 'btn-disable')
							}
							if($('cid'+preId)){
								$('cid'+preId).disable();
							}
						}
					}else{
						return false;
					}
				}catch(err){
					return false;
				}
			},
			onFailure: function() {},
			onException: function (request, exception) {}
		}
	);
}

function del_select(id){
	jQuery.noConflict();
	if(Element.hasClassName($('minus'+id), 'btn-disable')){
		return ;
	}else{
		$('panel'+id).innerHTML = get_plus_btn(id);

		nxtId = id + 1;
		if($('panel'+nxtId)){
			Element.remove($('panel'+nxtId));
		}
		
		preId = id - 1;
		
		if($('minus'+preId)){
			Element.removeClassName($('minus'+preId) , 'btn-disable');
			Element.addClassName($('minus'+preId), 'btn')
		}
		if($('cid'+preId)){
			$('cid'+preId).enable();
		}
	}
}

function get_plus_btn(id){
	jQuery.noConflict();
	var btn = '<a id="plus'+id+'" class="btn btn-tgr" href="javascript:add_select('+id+');">';
	btn += '<img src="./img/icn_plus.gif"></a>';
	
	return btn;
}

function change_read_perm(editsel){
	jQuery.noConflict();
	readsel = $('read_perm');
	if(readsel.hasChildNodes()){
		var cnode = readsel.lastChild;
		while (cnode){
			var delnode = cnode;
			cnode = cnode.previousSibling;
			readsel.removeChild(delnode);
		}
	}
	if(editsel.value == "public"){
		var adobj = document.createElement("option");
		adobj.appendChild(document.createTextNode(Const.Label.optionPublic));
		adobj.setAttribute("value","public");
		readsel.appendChild(adobj);
	}else{
		var adobj = document.createElement("option");
		adobj.appendChild(document.createTextNode(Const.Label.optionPublic));
		adobj.setAttribute("value","public");
		readsel.appendChild(adobj);
		
		var adobj = document.createElement("option");
		adobj.appendChild(document.createTextNode(Const.Label.optionUser));
		adobj.setAttribute("value","user");
		readsel.appendChild(adobj);
	}
	return true;
}

function disable_select_c(element){
	jQuery.noConflict();
	var postObj = {};
	postObj['command'] = 'disable_c';
	postObj['cid'] = element.value;
	
	
	var hash = $H(postObj).toQueryString();
	var Target_url = "?page=ajax";
	new Ajax.Request(Target_url,
		{
			method: "post",
			postBody: hash,
			asynchronous:true,
			onSuccess: function(request) {
				try {
					var responseJSON = request.responseText.evalJSON()
					if (responseJSON.status == 'OK') {
						var read_check_box_array = document.getElementsByName('read_permission[]');
						for(count = 0; count < read_check_box_array.length; count++){
							read_check_box_array[count].checked = false;
						}
						var edit_check_box_array = document.getElementsByName('edit_permission[]');
						for(count = 0; count < edit_check_box_array.length; count++){
							read_check_box_array[count].checked  = false;
							edit_check_box_array[count].checked  = false;
							edit_check_box_array[count].disabled = true;
						}
						
						if(responseJSON.read_type=='user'){
							for(count = 0; count < read_check_box_array.length; count++){
								read_check_box_array[count].disabled = true;
								
							}
						}else if(responseJSON.read_type=='protected'){
							var read_permission=responseJSON.read_permission;
							for(count = 0; count < read_check_box_array.length; count++){
								
								for(count2 = 0; count2 < read_permission.length; count2++){
									if(read_check_box_array[count].value!=read_permission[count2]){
										read_check_box_array[count].disabled = true;
										
									}else{
										read_check_box_array[count].disabled = false;
									}
								}
							}	
						}else {
							for(count = 0; count < read_check_box_array.length; count++){
								read_check_box_array[count].disabled = false;
								
							}
						}
					}else{
						return false;
					}
				}catch(err){
					return false;
				}
			},
			onFailure: function() {},
			onException: function (request, exception) {}
		}
	);
}


function disable_add_c(id){
	jQuery.noConflict();
	var postObj = {};
	postObj['command'] = 'disable_c';
	postObj['pid'] = $('cid'+(id-1)).value;
	
	if($('my_cid')){var mycid = $('my_cid').value;}
	else{var mycid = 0;}
	postObj['my_cid'] = mycid;


	var hash = $H(postObj).toQueryString();
	var Target_url = "?page=ajax";
	new Ajax.Request(Target_url,
		{
			method: "post",
			postBody: hash,
			asynchronous:true,
			onSuccess: function(request) {
				try {
					var responseJSON = request.responseText.evalJSON()
					if (responseJSON.status == 'OK') {
						var read_check_box_array = document.getElementsByName('read_permission[]');
						for(count = 0; count < read_check_box_array.length; count++){
							read_check_box_array[count].checked = false;
						}
						var edit_check_box_array = document.getElementsByName('edit_permission[]');
						for(count = 0; count < edit_check_box_array.length; count++){
							read_check_box_array[count].checked  = false;
							edit_check_box_array[count].checked  = false;
							edit_check_box_array[count].disabled = true;
						}
						//console.log(responseJSON.read_type);
						if(responseJSON.read_type=='user'){
							for(count = 0; count < read_check_box_array.length; count++){
								read_check_box_array[count].disabled = true;
								
							}
						}else if(responseJSON.read_type=='protected'){
							//console.log(read_check_box_array);
							var read_permission=responseJSON.read_permission;
							for(count = 0; count < read_check_box_array.length; count++){
								//console.log("--------"+read_check_box_array[count]+"-----------");
								for(count2 = 0; count2 < read_permission.length; count2++){
									//console.log(read_permission[count2]);
									//console.log(read_check_box_array[count].value);
									if(read_check_box_array[count].value!=read_permission[count2]){
										read_check_box_array[count].disabled = true;	
									}else{
										read_check_box_array[count].disabled = false;
									}
								}
							}	
						}else {
							for(count = 0; count < read_check_box_array.length; count++){
								read_check_box_array[count].disabled = false;
								
							}
						}
					}else{
						return false;
					}
				}catch(err){
					return false;
				}
			},
			onFailure: function() {},
			onException: function (request, exception) {}
		}
	);
}

function disable_del_c(id){
	jQuery.noConflict();
	var postObj = {};
	postObj['command'] = 'disable_c';
	postObj['cid'] = $('cid'+(id-1)).value;
	

	
	var hash = $H(postObj).toQueryString();
	var Target_url = "?page=ajax";
	new Ajax.Request(Target_url,
		{
			method: "post",
			postBody: hash,
			asynchronous:true,
			onSuccess: function(request) {
				try {
					var responseJSON = request.responseText.evalJSON()
					if (responseJSON.status == 'OK') {
						var read_check_box_array = document.getElementsByName('read_permission[]');
						for(count = 0; count < read_check_box_array.length; count++){
							read_check_box_array[count].checked = false;
						}
						var edit_check_box_array = document.getElementsByName('edit_permission[]');
						for(count = 0; count < edit_check_box_array.length; count++){
							read_check_box_array[count].checked  = false;
							edit_check_box_array[count].checked  = false;
							edit_check_box_array[count].disabled = true;
						}
						if(responseJSON.read_type=='user'){
							for(count = 0; count < read_check_box_array.length; count++){
								read_check_box_array[count].disabled = true;
								
							}
						}else if(responseJSON.read_type=='protected'){
							var read_permission=responseJSON.read_permission;
							for(count = 0; count < read_check_box_array.length; count++){
								for(count2 = 0; count2 < read_permission.length; count2++){
									if(read_check_box_array[count].value!=read_permission[count2]){
										read_check_box_array[count].disabled = true;	
									}else{
										read_check_box_array[count].disabled = false;
									}
								}
							}	
						}else {
							for(count = 0; count < read_check_box_array.length; count++){
								read_check_box_array[count].disabled = false;
								
							}
						}
					}else{
						return false;
					}
				}catch(err){
					return false;
				}
			},
			onFailure: function() {},
			onException: function (request, exception) {}
		}
	);
}
