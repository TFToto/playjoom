/** @license
 *
 * JavaScript AJAX library for PlayJoom
 * ----------------------------------------------
 * http://www.playjoom.org
 *
 * Copyright (c) 2015, Teglo. All rights reserved.
 * Code provided under GPL License:
 * http://www.playjoom.org/en/about/licenses/gnu-general-public-license.html
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

		},
		getGenrelist : function() {
			
			jQuery.ajax(_root_url + '&format=' + _format, {
				dataType: _format
			}).then(
				function(data) {

					jQuery.each(data.genre_list, function(index,genres) {

						//Create Genre titles
						var ObjH2 = jQuery('<h2>' + Object.getOwnPropertyNames(this)[0] + '</h2>');
						ObjH2.addClass('genres_title');
						jQuery('.albumsview').append(ObjH2);
						
						var cover_url = null	
						var ObjUl = jQuery('<ul></ul>');
						
						//Create album list for current genre
						jQuery.each(genres, function(i,item) {

							//console.log(item);
							jQuery.each(item, function() {

								//var id = Object.keys(this)[1];
								//var id_value = this[id];
								//console.log(id + " "  + id_value);
							
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
									dataType: _format,
									beforeSend: function(){
										ObjUl.addClass('list_of_albums1');
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


							
							
							//console.log(item);
							
							//var jsonObj = jQuery.parseJSON(item);
							//console.log(jsonObj);
							
							//get cover item
							
						});
						//jQuery('.albumsview').append(ObjUl);
					});
					//jQuery('.genresview').append(ObjH2);
			});
		},
		getGenrelist_zwo : function() {
			
			jQuery.ajax(_root_url + '&format=' + _format, {
				dataType: _format
			}).then(
				function(data) {

					var ObjUl = jQuery('<ul></ul>');
					//ObjUl.addClass('list_of_albums');

					jQuery.each(data.genre_list, function(i) {
						
						//Create Genre titles
						var ObjH2 = jQuery('<h2>' + Object.getOwnPropertyNames(this)[0] + '</h2>');
						ObjH2.addClass('genres_title');
						jQuery('.albumsview').append(ObjH2);

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
						
						return jQuery.ajax(cover_url + '&coverview=' + _view, {
							timeout: _timeout_cover,
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

					jQuery('.albumsview').append(ObjUl);
			});
		},
		getAlbumlist : function() {
			
			jQuery.ajax(_root_url + '&format=' + _format, {
				dataType: _format
			}).then(
				function(data) {

					var ObjUl = jQuery('<ul></ul>');
					//ObjUl.addClass('list_of_albums');

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
						
						return jQuery.ajax(cover_url + '&coverview=' + _view, {
							timeout: _timeout_cover,
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

					jQuery('.albumsview').append(ObjUl);
			});
		},
		getAlbumTablelist : function() {
			
			jQuery.ajax(_root_url + '&format=' + _format, {
				dataType: _format
			}).then(
				function(data) {

					//var ObjTb = jQuery('<table></table>');

					jQuery.each(data.album_list, function(i) {

						var row = jQuery("<tr />")
						jQuery("#albumtablelist").append(row);
						 
						row.append(jQuery("<td>" + i + "</td>"));
						row.append(jQuery("<td>" + this.artist + "</td>"));
						row.append(jQuery("<td>" + this.album + "</td>"));
						row.append(jQuery("<td>" + this.category_title + "</td>"));
						row.append(jQuery("<td>" + this.year + "</td>"));
						 
						 //get cover item
						var album_name_base64 = jQuery.base64('encode',this.album);
						var artist_name_base64 = jQuery.base64('encode',this.artist);
						var category_name_base64 = jQuery.base64('encode',this.category_title);
						var coverdata_link = '&option=com_playjoom&view=coverdata&format=' + _format + '&coverid=' + this.cover_id  + '&album=' + album_name_base64 + '&artist=' + artist_name_base64 + '&cat=' + category_name_base64 + '&catid=' + this.catid;

						var cover_url = _root_url + coverdata_link

						//var Objli = jQuery('<li></li>');
						//var Objimg = jQuery('<img></img>');
						//var Obja = jQuery('<a></a>');
						//var Objaimg = jQuery('<a></a>');

						//Objli.addClass('genre_item')

						//Obja.addClass('ui-all');
						//Obja.attr('tabindex', '-1');

						//Obja.text(this.album + ' - ' + this.year);
						//Objli.addClass('genre_item')

						//var _catid = this.catid;

						return jQuery.ajax(cover_url + '&coverview=' + _view, {
							dataType: _format,
							success: function(coverdata) {

								var album_link =
												_root_url + 
												'&view=album' +
												'&album=' + album_name_base64 +
												'&artist=' + artist_name_base64 +
												'&cat=' + category_name_base64 +
												'&catid=' + _catid +
												'&itemid=' + _itemid;

								//Obja.addClass('pj_cover_link')
								//Obja.attr('href',album_link)
								//Obja.attr('title','Continue to the album view')

								//Objaimg.attr('href',album_link)
								//Objaimg.attr('title','Continue to the album view')

								//Objimg.addClass('pj_cover')
								//Objimg.attr('width',coverdata.image_width)
								//Objimg.attr('height',coverdata.image_height)
								//Objimg.attr('src',coverdata.image_code + coverdata.image_data)

								//Objli.append(Objaimg);
								//Objaimg.append(Objimg).fadeIn(_fadeInTime);
								//Objli.append(Obja);
								//ObjUl.append(Objli);
							}
						});
					});

					jQuery('.albumsview').append(ObjUl);
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