<?php
/**
 * @package Joomla 1.6.x
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 *
 * @PlayJoom Component
 * @copyright Copyright (C) 2010-2012 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$session =& JFactory::getSession(); //Will require it for session support

require_once('components/com_playjoom/helpers/id3/getid3.php');


// Initialize getID3 engine
$getID3 = new getID3;
$getID3->setOption(array('encoding' => 'UTF-8'));
	
	$listdirectory = (isset($_REQUEST['listdirectory']) ? $_REQUEST['listdirectory'] : '.');
	$listdirectory = realpath($listdirectory); // get rid of /../../ references
	$currentfulldir = $listdirectory.'/';

	if (GETID3_OS_ISWINDOWS) {
		$currentfulldir = str_replace('\\', '/', $listdirectory.'/');
	}

	if ($handle = @opendir($listdirectory)) {

		//Discover the folder
		while ($file = readdir($handle)) {
			$currentfilename = $listdirectory.DIRECTORY_SEPARATOR.$file;
			set_time_limit(30); // allocate another 30 seconds to process this file - should go much quicker than this unless intense processing (like bitrate histogram analysis) is enabled
			//echo ' .'; // progress indicator dot
			flush();  // make sure the dot is shown, otherwise it's useless

			switch ($file) {
				case '..':
					$ParentDir = realpath($file.'/..').'/';
					if (GETID3_OS_ISWINDOWS) {
						$ParentDir = str_replace('\\', '/', $ParentDir);
					}
					$DirectoryContents[$currentfulldir]['dir'][$file]['filename'] = $ParentDir;
					continue 2;
					break;

				case '.':
					// ignore
					continue 2;
					break;
			}
			// symbolic-link-resolution enhancements by davidbullock״ech-center*com
			$TargetObject     = realpath($currentfilename);  // Find actual file path, resolve if it's a symbolic link
			$TargetObjectType = filetype($TargetObject);     // Check file type without examining extension

			if ($TargetObjectType == 'dir') {

				$DirectoryContents[$currentfulldir]['dir'][$file]['filename'] = $file;

			} elseif ($TargetObjectType == 'file') {
				
				$fileinformation = $getID3->analyze($currentfilename);

				getid3_lib::CopyTagsToComments($fileinformation);

				if (!empty($fileinformation['fileformat'])) {
					$DirectoryContents[$currentfulldir]['known'][$file] = $fileinformation;
				} else {
					$DirectoryContents[$currentfulldir]['other'][$file] = $fileinformation;
					$DirectoryContents[$currentfulldir]['other'][$file]['playtime_string'] = '';
				}
			
			}
		}
		closedir($handle);
		
		flush();

		$columnsintable = 14;
		
		echo '<table class="adminlist">';  

		$row = 0;
		foreach ($DirectoryContents as $dirname => $val) {
			
			if (isset($DirectoryContents[$dirname]['dir']) && is_array($DirectoryContents[$dirname]['dir'])) {
				uksort($DirectoryContents[$dirname]['dir'], 'MoreNaturalSort');
				foreach ($DirectoryContents[$dirname]['dir'] as $filename => $fileinfo) {
					
					//Set cookie for backstep data        		        
			        $session->set('backstep', html_entity_decode(realpath($dirname.$filename), ENT_QUOTES));
			        
					$row = $row + 1;
					echo '<tr class="row'.$row % 2 .'">';
					if ($filename == '..') {
						echo '<td colspan="'.$columnsintable.'">';
						//echo '<form action="'.JRoute::_('index.php?option=com_playjoom&view=addtracks&layout=edit').'" method="post">';
						echo '<a href="'.JRoute::_('index.php?option=com_playjoom&view=addtracks&layout=edit&amp;select=server&amp;tmpl=component&listdirectory='.$session->get('backstep')).'"><img src="components/com_playjoom/images/j_button1_prev.png" alt="back" /></a>
                              <a href="'.JRoute::_('index.php?option=com_playjoom&view=addtracks&layout=edit&amp;select=server&amp;tmpl=component&listdirectory='.DIRECTORY_SEPARATOR).'"><img src="components/com_playjoom/images/j_button1_home.png" alt="home" /></a>
						      &nbsp;&nbsp;Parent directory: ';
						echo '<input type="text" name="listdirectory" size="70" value="';
						if (GETID3_OS_ISWINDOWS) {
							echo html_entity_decode(str_replace('\\', '/', realpath($listdirectory)), ENT_QUOTES);
						} else {
							echo html_entity_decode(realpath($listdirectory), ENT_QUOTES);
						}
						echo '"> <input type="submit" value="Go">';
						//echo '</form></td>';
						echo '</td>';
					} else {
						echo '<td colspan="'.$columnsintable.'"><img src="components/com_playjoom/images/folder.png" alt="folder" />&nbsp;&nbsp;<a href="'.JRoute::_('index.php?option=com_playjoom&view=addtracks&layout=edit&amp;select=server&amp;tmpl=component&listdirectory='.urlencode($dirname.$filename)).'"><b>'.$filename.'</b></a></td>';
					}
					echo '</tr>';
				}
			}
			if (isset($DirectoryContents[$dirname]['known']) && is_array($DirectoryContents[$dirname]['known'])) {
				
				echo '<tr class="row'.$row % 2 .'">';
			    echo '<th>Filename</th>';
			    echo '<th>File Size</th>';
			    echo '<th>Format</th>';
			    echo '<th>Playtime</th>';
			    echo '<th>Artist</th>';
			    echo '<th>Title</th>';
			    echo '</tr>';
				uksort($DirectoryContents[$dirname]['known'], 'MoreNaturalSort');
				foreach ($DirectoryContents[$dirname]['known'] as $filename => $fileinfo) {
					
					switch($fileinfo['fileformat']) {
						case 'mp3':
							$rowentry = '<img src="components/com_playjoom/images/sound.png" alt="sound file" />&nbsp;&nbsp;'.$filename;
					    break;
					    case 'jpg':
							$rowentry = '<img src="components/com_playjoom/images/picture.png" alt="picture file" />&nbsp;&nbsp;'.$filename.'</a>';
					    break;
					    default:
							$rowentry = '<img src="components/com_playjoom/images/page.png" alt="other file" />&nbsp;&nbsp;'.$filename;
						break;
					}
					$row = $row + 1;
					echo '<tr class="row'.$row % 2 .'">';
					echo '<td>'.$rowentry.'</td>';
					echo '<td>&nbsp;'.PlayJoomHelper::ByteValue($fileinfo['filesize']).' '.PlayJoomHelper::UnitValue($fileinfo['filesize']).'B</td>';
					echo '<td>&nbsp;'.$fileinfo['fileformat'].'</td>';
					echo '<td>'.(!empty($fileinfo['playtime_seconds']) ? (PlayJoomHelper::Playtime($fileinfo['playtime_seconds']).' Min.') : null).'</td>';
					echo '<td align="left">&nbsp;'.(isset($fileinfo['comments_html']['artist']) ? implode('<br>', $fileinfo['comments_html']['artist']) : (isset($fileinfo['video']['resolution_x']) ? @$fileinfo['video']['resolution_x'].'x'.@$fileinfo['video']['resolution_y'] : '')).'</td>';
					echo '<td align="left">&nbsp;'.(isset($fileinfo['comments_html']['title'])  ? implode('<br>', $fileinfo['comments_html']['title'])  : (isset($fileinfo['video']['frame_rate'])   ? number_format(@$fileinfo['video']['frame_rate'], 3).'fps'                   : '')).'</td>';
					
					echo '</tr>';
				}
			}

			if (isset($DirectoryContents[$dirname]['other']) 
		  && is_array($DirectoryContents[$dirname]['other'])) {
				
		  	    uksort($DirectoryContents[$dirname]['other'], 'MoreNaturalSort');
				foreach ($DirectoryContents[$dirname]['other'] as $filename => $fileinfo) {
					$row = $row + 1;
					echo '<tr class="row'.$row % 2 .'">';
					echo '<td><img src="components/com_playjoom/images/page_white_text.png" alt="white page" />&nbsp;&nbsp;'.$filename.'</td>';
					echo '<td>&nbsp;';
					if (isset($fileinfo['filesize']) 
					       && $fileinfo['filesize'] != ''){
						echo PlayJoomHelper::ByteValue($fileinfo['filesize']).' '.PlayJoomHelper::UnitValue($fileinfo['filesize']).'B</td>';
					}
					echo '<td>&nbsp;</td>';
					echo '<td>&nbsp;'.(isset($fileinfo['playtime_string']) ? $fileinfo['playtime_string'] : null).'</td>';
					echo '<td>&nbsp;</td>'; // Artist
					echo '<td>&nbsp;</td>'; // Title
					
					echo '</tr>';
				}
			}	
		}
		echo '</table>';
	} else {
		echo '<b>ERROR: Could not open directory: <u>'.$currentfulldir.'</u></b><br>';
	}

function RemoveAccents($string) {
	return strtr(
		strtr(
			$string,
			"\x8A\x8E\x9A\x9E\x9F\xC0\xC1\xC2\xC3\xC4\xC5\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF\xD1\xD2\xD3\xD4\xD5\xD6\xD8\xD9\xDA\xDB\xDC\xDD\xE0\xE1\xE2\xE3\xE4\xE5\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF1\xF2\xF3\xF4\xF5\xF6\xF8\xF9\xFA\xFB\xFC\xFD\xFF",
			'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy'
		),
		array(
			"\xDE" => 'TH',
			"\xFE" => 'th',
			"\xD0" => 'DH',
			"\xF0" => 'dh',
			"\xDF" => 'ss',
			"\x8C" => 'OE',
			"\x9C" => 'oe',
			"\xC6" => 'AE',
			"\xE6" => 'ae',
			"\xB5" => 'u'
		)
	);
}

function MoreNaturalSort($ar1, $ar2) {
	if ($ar1 === $ar2) {
		return 0;
	}
	$len1     = strlen($ar1);
	$len2     = strlen($ar2);
	$shortest = min($len1, $len2);
	if (substr($ar1, 0, $shortest) === substr($ar2, 0, $shortest)) {
		// the shorter argument is the beginning of the longer one, like "str" and "string"
		if ($len1 < $len2) {
			return -1;
		} elseif ($len1 > $len2) {
			return 1;
		}
		return 0;
	}
	$ar1 = RemoveAccents(strtolower(trim($ar1)));
	$ar2 = RemoveAccents(strtolower(trim($ar2)));
	$translatearray = array('\''=>'', '"'=>'', '_'=>' ', '('=>'', ')'=>'', '-'=>' ', '  '=>' ', '.'=>'', ','=>'');
	foreach ($translatearray as $key => $val) {
		$ar1 = str_replace($key, $val, $ar1);
		$ar2 = str_replace($key, $val, $ar2);
	}

	if ($ar1 < $ar2) {
		return -1;
	} elseif ($ar1 > $ar2) {
		return 1;
	}
	return 0;
}