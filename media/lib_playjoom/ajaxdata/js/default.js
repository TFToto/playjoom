/** @license
 *
 * JavaScript AJAX library for PlayJoom
 * ----------------------------------------------
 * http://www.playjoom.org
 *
 * Copyright (c) 2010-2016, Teglo. All rights reserved.
 * Code provided under GPL License:
 * https://www.playjoom.org/en/about/licenses/gnu-general-public-license.html
 *
 * Version: 0.0.1
 */
var ajaxdata = ajaxdata || (function(){
	
	var _setups = {};

	return {
		init : function(config) {

			//defines a few variables 
			_root_url      = getDefault(config.root_url,false,false,false);
			_view          = getDefault(config.view,false,false,false);
			_format        = getDefault(config.format,'json',false,false);
			_fadeInTime    = getDefault(config.fadeInTime,800,false,false);
			_timeout_cover = getDefault(config.timeout_cover,5000,false,false);
			_itemid        = getDefault(config.itemid,false,false,true);
			
			console.log('show size: '+ showViewPortSize(true));

		},
		getGenrelist : function() {
			
			jQuery.ajax(_root_url + '&format=' + _format, {
				dataType: _format
			}).then(
				function(data) {

					jQuery.each(data.genre_list, function(index,genres) {

						//Create Genre titles
						var ObjH2 = jQuery(
								'<h2>' + 
								'<a title="Continue to the genre view"' +
								'href="' + _root_url + '?option=com_playjoom&view=genre' +
								'&catid=' + this.catid +
								'&Itemid=' + _itemid + '">' +
								Object.getOwnPropertyNames(this)[0] +
								'</a>' +
								'</h2>'
						);
						ObjH2.addClass('genres_title');
						jQuery('.albumsview').append(ObjH2);
						
						//console.log(this.catid);
						var cover_url = null	
						var ObjUl = jQuery('<ul></ul>');
						
						//Create album list for current genre
						jQuery.each(genres, function(i,item) {

							//breakup loop if child have just the catid
							if ( i == 'catid' ) {
								 return false;
							}

							jQuery.each(item, function() {
							
								var album_name_base64 = jQuery.base64('encode',this.album);
								var artist_name_base64 = jQuery.base64('encode',this.artist);
								var category_name_base64 = jQuery.base64('encode',this.category_title);
								var coverdata_link = '&option=com_playjoom&view=coverdata&format=' + _format + '&coverid=' + this.cover_id  + '&album=' + album_name_base64 + '&artist=' + artist_name_base64 + '&cat=' + category_name_base64 + '&catid=' + this.catid + '&Itemid=' + _itemid;

								var cover_url = _root_url + coverdata_link

								var Objli = jQuery('<li></li>');
								var Objimg = jQuery('<img></img>');
								var Obja = jQuery('<a></a>');
								var Objaimg = jQuery('<a></a>');
								
								//Objli.addClass('genre_item')

								//ObjUl.attr('role', 'menuitem');

								Obja.addClass('ui-all');
								Obja.attr('tabindex', '-1');

								Obja.text(this.album + ' - ' + this.year);
								//Objli.addClass('genre_item')

								var _catid = this.catid;
								var album_link =
									_root_url + 
									'&view=album' +
									'&album=' + album_name_base64 +
									'&artist=' + artist_name_base64 +
									'&cat=' + category_name_base64 +
									'&catid=' + _catid +
									'&itemid=' + _itemid;
								
								jQuery('.albumsview').append(ObjUl);
								
								return jQuery.ajax(cover_url + '&coverview=' + _view, {
									timeout: _timeout_cover,
									cache:false,
									dataType: _format,
									beforeSend: function(){
										ObjUl.addClass('list_of_albums');
										ObjUl.attr('role', 'menuitem');
										Objli.addClass('loading_class');
										ObjUl.append(Objli);
									},
									error: function() {
										Objli.removeClass('loading_class');
										Objli.addClass('default_cover_class');

										Obja.addClass('pj_cover_link');
										Obja.attr('href',album_link);
										Obja.attr('title','Continue to the album view');

										Objaimg.attr('href',album_link);
										Objaimg.attr('title','Continue to the album view');
										Objli.append(Obja);
									},
									success: function(coverdata) {

										Objli.removeClass('loading_class');
										Objli.addClass('genre_item');

										Obja.addClass('pj_cover_link');
										Obja.attr('href',album_link);
										Obja.attr('title','Continue to the album view');

										Objaimg.attr('href',album_link);
										Objaimg.attr('title','Continue to the album view');

										Objimg.addClass('pj_cover');
										Objimg.attr('width',coverdata.image_width);
										Objimg.attr('height',coverdata.image_height);
										Objimg.attr('src',coverdata.image_code + coverdata.image_data);

										Objli.append(Objaimg);
										Objaimg.append(Objimg).fadeIn(_fadeInTime);
										Objli.append(Obja);
									}
								});
							});
						});
					});
			});
		},
		getAlbumlist : function() {
			
			var current_width = jQuery('.panel').width();
			var current_height = jQuery('.panel').height();
			console.log("Width: " + current_width + ", height: " + current_height)
			//console.log(showViewPortSize(true));
			
			jQuery.ajax(_root_url + '&format=' + _format, {
				dataType: _format
			}).then(
				function(data) {

					var ObjUl = jQuery('<ul></ul>');
					jQuery('.albumsview').append(ObjUl);
					
					jQuery.each(data.album_list, function(i) {

						//get cover item
						var album_name_base64 = jQuery.base64('encode',this.album);
						var artist_name_base64 = jQuery.base64('encode',this.artist);
						var category_name_base64 = jQuery.base64('encode',this.category_title);
						var coverdata_link = '&option=com_playjoom&view=coverdata&format=' + _format + '&coverid=' + this.cover_id  + '&album=' + album_name_base64 + '&artist=' + artist_name_base64 + '&cat=' + category_name_base64 + '&catid=' + this.catid + '&Itemid=' + _itemid;

						var cover_url = _root_url + coverdata_link

						var Objli = jQuery('<li></li>');
						var Objimg = jQuery('<img></img>');
						var Obja = jQuery('<a></a>');
						var Objaimg = jQuery('<a></a>');

						Obja.addClass('ui-all');
						Obja.attr('tabindex', '-1');

						Obja.text(this.album + ' - ' + this.year);

						var _catid = this.catid;
						var album_link =
							_root_url + 
							'&view=album' +
							'&album=' + album_name_base64 +
							'&artist=' + artist_name_base64 +
							'&cat=' + category_name_base64 +
							'&catid=' + _catid +
							'&itemid=' + _itemid;
					
						return jQuery.ajax(cover_url + '&coverview=' + _view, {
							timeout: _timeout_cover,
							dataType: _format,
							beforeSend: function(){
								ObjUl.addClass('list_of_albums');
								ObjUl.attr('role', 'menuitem');
								Objli.addClass('loading_class');
								ObjUl.append(Objli);
								
								var position = Objli.position();
								//var current_width = jQuery('.albumsview').innerWidth();
								//var current_height = jQuery('.albumsview').innerHeight();
								
								console.log("left: " + position.left + ", top: " + position.top);
								//console.log("Width: " + current_width + ", height: " + current_height)
								
							},
							error: function() {
								Objli.removeClass('loading_class');
								Objli.addClass('default_cover_class');

								Obja.addClass('pj_cover_link');
								Obja.attr('href',album_link);
								Obja.attr('title','Continue to the album view');

								Objaimg.attr('href',album_link);
								Objaimg.attr('title','Continue to the album view');
								Objli.append(Obja);
							},
							success: function(coverdata) {

								Objli.removeClass('loading_class');
								Objli.addClass('genre_item');

								Obja.addClass('pj_cover_link');
								Obja.attr('href',album_link);
								Obja.attr('title','Continue to the album view');

								Objaimg.attr('href',album_link);
								Objaimg.attr('title','Continue to the album view');

								Objimg.addClass('pj_cover');
								Objimg.attr('width',coverdata.image_width);
								Objimg.attr('height',coverdata.image_height);
								Objimg.attr('src',coverdata.image_code + coverdata.image_data);

								Objli.append(Objaimg);
								Objaimg.append(Objimg).fadeIn(_fadeInTime);
								Objli.append(Obja);
							}
						});
					});
			});
		}
	};
}());

/**
 * Function for to get a default value if it's not set.
 *
 */ 
getDefault = function(value, newValue, overwriteNull, overwriteZero) {

    if (typeof (value) === 'undefined') {
        return newValue;
    } else if (value === null && overwriteNull === true) {
        return newValue;
    } else if (value === 0 && overwriteZero === true) {
        return newValue;
    } else {
        return value;
    }
};

showViewPortSize = function(display) {
    if(display) {
      var height = jQuery(window).height();
      var width = jQuery(window).width();
      jQuery('body').prepend('<div id=".albumsview" style="z-index:9999;position:fixed;top:40px;left:5px;color:#fff;background:#000;padding:10px">Height: '+height+'<br>Width: '+width+'</div>');
      jQuery(window).resize(function() {
        height = jQuery(window).height();
        width = jQuery(window).width();
        jQuery('.albumsview').html('Height: '+height+'<br>Width: '+width);
      });
    }
};