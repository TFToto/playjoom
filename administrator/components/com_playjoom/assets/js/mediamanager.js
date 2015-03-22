/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * JMediaManager behavior for media component
 *
 * @package		Joomla.Extensions
 * @subpackage  Media
 * @since		1.5
 */
(function() {
var MediaManager = this.MediaManager = {

	initialize: function()	{
		
		this.folderframe	= document.id('folderframe');
		this.folderpath		= document.id('folderpath');

		this.updatepaths	= $$('input.update-folder');

		this.frame		= window.frames['folderframe'];
		this.frameurl	= this.frame.location.href;
		
		this.selectedfolder = document.getElementById('selectedfolder');
	},

	submit: function(task)	{
		form = window.frames['folderframe'].document.id('mediamanager-form');
		form.task.value = task;
		if (document.id('username')) {
			form.username.value = document.id('username').value;
			form.password.value = document.id('password').value;
		}
		form.submit();
	},

	onloadframe: function() {
		// Update the frame url
		this.frameurl = this.frame.location.href;

		var folder = this.getFolder();
		if (folder) {
			this.updatepaths.each(function(path){ path.value =folder; });
			this.folderpath.value = basepath+'/'+folder;
			this.selectedfolder.value = basepath+'/'+folder;
		} else {
			this.updatepaths.each(function(path){ path.value = ''; });
			this.folderpath.value = basepath;
			this.selectedfolder.value = basepath;
		}

		document.id(viewstyle).addClass('active');

		a = this._getUriObject(document.id('uploadForm').getProperty('action'));
		q = new Hash(this._getQueryObject(a.query));
		q.set('folder', folder);
		var query = [];
		q.each(function(v, k){
			if (v != null) {
				this.push(k+'='+v);
			}
		}, query);
		a.query = query.join('&');

		if (a.port) {
			document.id('uploadForm').setProperty('action', a.scheme+'://'+a.domain+':'+a.port+a.path+'?'+a.query);
		} else {
			document.id('uploadForm').setProperty('action', a.scheme+'://'+a.domain+a.path+'?'+a.query);
		}
	},

	oncreatefolder: function()	{
		if (document.id('foldername').value.length) {
			document.id('dirpath').value = this.getFolder();
			Joomla.submitbutton('createfolder');
		}
	},

	setViewType: function(type) {
		
		var url	 = this.frame.location.search.substring(1);
		var args	= this.parseQuery(url);
		
		document.id(type).addClass('active');
		document.id(viewstyle).removeClass('active');
		viewstyle = type;
		var folder = this.getFolder();
		this._setFrameUrl('index.php?option=com_playjoom&view=mediaList&tmpl=component&folder='+args['folder']+'&layout='+type);
	},

	refreshFrame: function() {
		this._setFrameUrl();
	},

	getFolder: function() {
		var url	 = this.frame.location.search.substring(1);
		var args	= this.parseQuery(url);

		return this.base64_decode(args['folder']);
	},
	
	parseQuery: function(query) {
		var params = new Object();
		if (!query) {
			return params;
		}
		var pairs = query.split(/[;&]/);
		for ( var i = 0; i < pairs.length; i++ )
		{
			var KeyVal = pairs[i].split('=');
			if ( ! KeyVal || KeyVal.length != 2 ) {
				continue;
			}
			var key = unescape( KeyVal[0] );
			var val = unescape( KeyVal[1] ).replace(/\+ /g, ' ');
			params[key] = val;
	   }
	   return params;
	},

	_setFrameUrl: function(url) {
		if (url != null) {
			this.frameurl = url;
		}
		this.frame.location.href = this.frameurl;
	},

	_getQueryObject: function(q) {
		var vars = q.split(/[&;]/);
		var rs = {};
		if (vars.length) vars.each(function(val) {
			var keys = val.split('=');
			if (keys.length && keys.length == 2) rs[encodeURIComponent(keys[0])] = encodeURIComponent(keys[1]);
		});
		return rs;
	},

	_getUriObject: function(u){
		var bits = u.match(/^(?:([^:\/?#.]+):)?(?:\/\/)?(([^:\/?#]*)(?::(\d*))?)((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[\?#]|$)))*\/?)?([^?#\/]*))?(?:\?([^#]*))?(?:#(.*))?/);
		return (bits)
			? bits.associate(['uri', 'scheme', 'authority', 'domain', 'port', 'path', 'directory', 'file', 'query', 'fragment'])
			: null;
	},
	
	base64_decode: function (data) {
	    // Decodes string using MIME base64 algorithm  
	    // 
	    // version: 1109.2015
	    // discuss at: http://phpjs.org/functions/base64_decode
	    // +   original by: Tyler Akins (http://rumkin.com)
	    // +   improved by: Thunder.m
	    // +      input by: Aman Gupta
	    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // +   bugfixed by: Onno Marsman
	    // +   bugfixed by: Pellentesque Malesuada
	    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // +      input by: Brett Zamir (http://brett-zamir.me)
	    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // -    depends on: utf8_decode
	    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
	        ac = 0,
	        dec = "",
	        tmp_arr = [];
	 
	    if (!data) {
	        return data;
	    }
	 
	    data += '';
	 
	    do { // unpack four hexets into three octets using index points in b64
	        h1 = b64.indexOf(data.charAt(i++));
	        h2 = b64.indexOf(data.charAt(i++));
	        h3 = b64.indexOf(data.charAt(i++));
	        h4 = b64.indexOf(data.charAt(i++));
	 
	        bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;
	 
	        o1 = bits >> 16 & 0xff;
	        o2 = bits >> 8 & 0xff;
	        o3 = bits & 0xff;
	 
	        if (h3 == 64) {
	            tmp_arr[ac++] = String.fromCharCode(o1);
	        } else if (h4 == 64) {
	            tmp_arr[ac++] = String.fromCharCode(o1, o2);
	        } else {
	            tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
	        }
	    } while (i < data.length);
	 
	    dec = tmp_arr.join('');
	    dec = this.utf8_decode(dec);
	 
	    return dec;
	},
	
	utf8_decode: function (str_data) {
	    // Converts a UTF-8 encoded string to ISO-8859-1  
	    // 
	    // version: 1109.2015
	    // discuss at: http://phpjs.org/functions/utf8_decode
	    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
	    // +      input by: Aman Gupta
	    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    // +   improved by: Norman "zEh" Fuchs
	    // +   bugfixed by: hitwork
	    // +   bugfixed by: Onno Marsman
	    // +      input by: Brett Zamir (http://brett-zamir.me)
	    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	    var tmp_arr = [],
	        i = 0,
	        ac = 0,
	        c1 = 0,
	        c2 = 0,
	        c3 = 0;
	 
	    str_data += '';
	 
	    while (i < str_data.length) {
	        c1 = str_data.charCodeAt(i);
	        if (c1 < 128) {
	            tmp_arr[ac++] = String.fromCharCode(c1);
	            i++;
	        } else if (c1 > 191 && c1 < 224) {
	            c2 = str_data.charCodeAt(i + 1);
	            tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
	            i += 2;
	        } else {
	            c2 = str_data.charCodeAt(i + 1);
	            c3 = str_data.charCodeAt(i + 2);
	            tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
	            i += 3;
	        }
	    }
	 
	    return tmp_arr.join('');
	}
};
})(document.id);

window.addEvent('domready', function(){
	// Added to populate data on iframe load
	MediaManager.initialize();
	MediaManager.trace = 'start';
	document.updateUploader = function() { MediaManager.onloadframe(); };
	MediaManager.onloadframe();
});
