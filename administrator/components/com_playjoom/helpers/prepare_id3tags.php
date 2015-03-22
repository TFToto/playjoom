<?php
/**
 * Contains the prepare methods for the PlayJoom track management.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2014 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoom Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die;

/**
 * PlayJoom component helper.
 */
abstract class ID3TagsHelper {

	static public function getID3Tags($ThisFileInfo) {

		$dispatcher	= JDispatcher::getInstance();
		//$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'ThisFileInfo content: '.print_r($ThisFileInfo,true), 'priority' => JLog::WARNING, 'section' => 'admin')));

		$unkown_txt = JText::_('COM_PLAYJOOM_ADD_TRACK_UNKNOWN');

		$track_mime_value = (!empty($ThisFileInfo['mime_type']) ? ($ThisFileInfo['mime_type']) : null);
		$track_bitrate_value = (!empty($ThisFileInfo['audio']['bitrate']) ? round($ThisFileInfo['audio']['bitrate']) : 0);
		$track_sample_value = (!empty($ThisFileInfo['audio']['sample_rate']) ? ($ThisFileInfo['audio']['sample_rate']) : 0);
		$track_channels_value = (!empty($ThisFileInfo['audio']['channels']) ? ($ThisFileInfo['audio']['channels']) : 0);
		$track_channelmode_value = (!empty($ThisFileInfo['audio']['channelmode']) ? ($ThisFileInfo['audio']['channelmode']) : null);
		$track_filesize_value = (!empty($ThisFileInfo['filesize']) ? ($ThisFileInfo['filesize']) : 0);
		$track_length_value = (!empty($ThisFileInfo['playtime_seconds']) ? ($ThisFileInfo['playtime_seconds']) : null);
		$track_fileformat_value = (!empty($ThisFileInfo['fileformat']) ? ($ThisFileInfo['fileformat']) : 'unkown_fileformat');

		//Check track number of a track
		if (isset($ThisFileInfo['id3v1']['track'])) {
			$track_number_value = $ThisFileInfo['id3v1']['track'];
		} else {
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'No track number value in ID3tag existing!', 'priority' => JLog::WARNING, 'section' => 'admin')));
			$file_name = pathinfo($ThisFileInfo['filename']);
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'String for to get number: '.$file_name['filename'], 'priority' => JLog::INFO, 'section' => 'admin')));
			$possible_numbers = ereg_replace("[^0-9]", "", $file_name['filename']);
			if ($possible_numbers) {
				$track_number_value = preg_replace("/^0+/",  "", $possible_numbers);
			} else {
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'No numbers in file string present.', 'priority' => JLog::WARNING, 'section' => 'admin')));
				$track_number_value = 0;
			}
		}

		//name of album selection
		if (!empty($ThisFileInfo['comments_html']['album'][0])) {
			$track_album_value = html_entity_decode($ThisFileInfo['comments_html']['album'][0]);
		} else {
			$album_name = basename($ThisFileInfo['filepath']);
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'String for album name: '.$album_name, 'priority' => JLog::INFO, 'section' => 'admin')));
			if($album_name) {
				$track_album_value = $album_name;
			} else {
				$track_album_value = $unkown_txt;
			}
		}

		//name of artist selection
		if (!empty($ThisFileInfo['comments_html']['artist'][0])) {
			$track_artist_value = html_entity_decode($ThisFileInfo['comments_html']['artist'][0]);
		} else {
			$track_artist_value = $unkown_txt;
		}

		//name of title selection
		if (!empty($ThisFileInfo['comments_html']['title'][0])) {
			$track_title_value = html_entity_decode($ThisFileInfo['comments_html']['title'][0]);
		} else {
			$track_title = pathinfo($ThisFileInfo['filename']);
			$name_without_int = preg_replace("/[0-9]/", "", $track_title['filename']);

			if ($name_without_int) {
				$track_title_value = ltrim ($name_without_int,' - ');
			} else {
				$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'No track title! Get default track name unkown.', 'priority' => JLog::WARNING, 'section' => 'admin')));
				$track_title_value = $unkown_txt;
			}
		}

		//Year of title selection
		if (!empty($ThisFileInfo['id3v1']['year'])) {
			$track_year_value = $ThisFileInfo['id3v1']['year'];
		} else {
			$album_name = basename($ThisFileInfo['filepath']);
			$possible_year = ereg_replace("[^0-9]", "", $album_name);
			$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Possible album release could be: '.$possible_year, 'priority' => JLog::INFO, 'section' => 'admin')));

			if (strlen((string)$possible_year) == 4 && $possible_year >= 1900) {
				$track_year_value = $possible_year;
			} else {
				$track_year_value = 1900;
			}
		}

		$track_genre_value = (!empty($ThisFileInfo['id3v1']['genre']) ? ($ThisFileInfo['id3v1']['genre']) : 'no');

		/*
		* prepare track alias
		* result variable -> $track_alias_value
		*/
		if (isset($ThisFileInfo['id3v1']['artist'])) {
			$artistsafe = JApplication::stringURLSafe($ThisFileInfo['id3v1']['artist']);
		} else {
			$artistsafe = JApplication::stringURLSafe($unkown_txt);
		}

		if (isset($ThisFileInfo['id3v1']['title'])) {
			$titlesafe = JApplication::stringURLSafe($ThisFileInfo['id3v1']['title']);
		} else {
			$titlesafe = JApplication::stringURLSafe($unkown_txt);
		}
		$track_alias_value = $artistsafe .'-'. $titlesafe . '.' .$track_fileformat_value;

		//Build values array
		$id3_values = array(
				"album" => $track_album_value,
				"artist" => $track_artist_value,
				"title" => $track_title_value,
				"genre" => $track_genre_value,
				"alias" => $track_alias_value,
				"number" => $track_number_value,
				"mime" => $track_mime_value,
				"bitrate" => $track_bitrate_value,
				"samplerate" => $track_sample_value,
				"channels" => $track_channels_value,
				"channelmode" => $track_channelmode_value,
				"filesize" => $track_filesize_value,
				"length" => $track_length_value,
				"year" => $track_year_value
		);

		return $id3_values;
	}
}