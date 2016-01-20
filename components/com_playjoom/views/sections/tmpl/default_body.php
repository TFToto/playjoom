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
 * @copyright Copyright (C) 2010-2011 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

JPluginHelper::importPlugin('playjoom');

$dispatcher	= JDispatcher::getInstance();

$counter = null;

$coverwidth =  $this->params->get('maxsize_cover');
$coverstate =  $this->params->get('show_cover');

switch($this->params->get('show_section')) {
		                case 'artist' :
		                               foreach($this->items as $i => $item) {

		                               $artiststing = base64_encode($item->artist);
		                               $artistlink = 'index.php?option=com_playjoom&view=artist&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');

		                               $genresting = base64_encode($item->category_title);
		                               $genrelink = 'index.php?option=com_playjoom&view=genre&cat='.$genresting.'&catid='.$item->catid.'&Itemid='.JRequest::getVar('Itemid');
		                               $counter = $counter + 1;
		                               echo '<tr class="row'. $i % 2 .'">';
                                           echo '<td class="sectiontableentry">'.$counter .'</td>';
                                           echo '<td class="list-'. $item->artist .'"><a href="'. $artistlink .'">'. $item->artist .'</td>';
                                           echo '<td class="list-'. $item->category_title .'"><a href="'. $genrelink .'">'. $item->category_title .'</td>';
                                       echo '</tr>';

		                               }
		                break;
		                case 'album' :
		                	           foreach($this->items as $i => $item) {

		                               	  //Check for albumname as sampler
		                               	  if (PlayJoomHelper::checkForSampler($item->album, $item->artist)) {
		                               	  	  $artistname = JText::_('COM_PLAYJOOM_ALBUM_SAMPLER');
		                               	  } else {
		                               	  	  $artistname = $item->artist;
		                               	  }

		                               	  //Create strings
		                               	  $albumsting = base64_encode($item->album);
		                               	  $genresting = base64_encode($item->category_title);
		                               	  $artiststing = base64_encode($item->artist);

		                               	  //Create links
		                               	  $albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');
		                                  $genrelink = 'index.php?option=com_playjoom&view=genre&cat='.$genresting.'&catid='.$item->catid.'&Itemid='.JRequest::getVar('Itemid');
                                          $artistlink = 'index.php?option=com_playjoom&view=artist&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');

		                                  if ($coverstate == 1) {
				                 			  $coverthumb = PlayJoomHelper::getCoverThumb($item->album, $item->artist,JPATH_BASE.DS.'tmp'.DS.'tmp_img_albumtumb'.$i, $coverwidth, $i).'<br />';
				                 		  }
				                 		  else {
				                 			  $coverthumb = null;
				                 		  }

                                          $genre = null;
                                          $counter = $counter + 1;
                                          echo '<tr class="row'. $i % 2 .'">';
                                            echo '<td class="sectiontableentry">'.$counter .'</td>';
                                            echo '<td class="list-'.$artistname.'"><a href="'.$artistlink.'">'.$artistname.'</a></td>';
                                            echo '<td valign="top" class="list-'.$item->album.'"><a href="'.$albumlink.'" title="Continue to the album view" rel="Image '.$counter.'" class="image">'.$coverthumb.$item->album.'</a></td>';
                                            echo '<td class="list-'.$item->category_title.'"><a href="'.$genrelink.'" title="Continue to the genre view">'.$item->category_title.'</a></td>';
                                            echo '<td class="list-'.$item->year.'">'.$item->year.'</td>';
                                          echo '</tr>';
                                       }
		                break;
		                case 'year' :
		                               ?>
		                               <?php foreach($this->items as $i => $item): ?>
                                         <tr class="row<?php echo $i % 2; ?>">
                                           <td class="sectiontableentry"><?php echo $i+1; ?></td>
                                           <td class="list-<?php echo $item->year; ?>"><?php echo $item->year; ?></td>
                                         </tr>
                                       <?php endforeach; ?>
		                               <?php
		                break;
		                default :
		                	     foreach($this->items as $i => $item) {

				                 	if (JFile::exists($item->pathatlocal.DIRECTORY_SEPARATOR.$item->file)) {

				                 		if ($coverstate == 1) {
				                 			$coverthumb = PlayJoomHelper::getCoverThumb($item->album, $item->artist, JPATH_BASE.DS.'tmp'.DS.'tmp_img_albumtumb'.$i, $coverwidth, $i).'<br />';
				                 		}
				                 		else {
				                 			$coverthumb = null;
				                 		}

				                 		//Check for albumname as sampler
		                               	if (PlayJoomHelper::checkForSampler($item->album, $item->artist)) {
		                               	     $artistname = JText::_('COM_PLAYJOOM_ALBUM_SAMPLER');
		                               	} else 	{
		                               	  	 $artistname = $item->artist;
		                               	}

		                               	//Create strings
		                                $albumsting = base64_encode($item->album);
		                                $genresting = base64_encode($item->category_title);
		                                $artiststing = base64_encode($item->artist);

		                                //Create links
		                                $albumlink = 'index.php?option=com_playjoom&view=album&album='.$albumsting.'&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');
		                                $genrelink = 'index.php?option=com_playjoom&view=genre&cat='.$genresting.'&catid='.$item->catid.'&Itemid='.JRequest::getVar('Itemid');
		                                $artistlink = 'index.php?option=com_playjoom&view=artist&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');

		                                $counter = $counter + 1;
		                          	    echo '<tr class="row'. $i % 2 .'">';
		                          	      echo '<td class="sectiontableentry">'.$counter .'</td>';
		                          	      echo '<td class="list-'.$artistname.'"><a href="'.$artistlink.'">'.$artistname.'</a></td>';
		                          	      echo '<td valign="top" class="list-'.$item->album.'"><a href="'.$albumlink.'" title="Continue to the album view">'.$coverthumb.$item->album.'</a></td>';

		                          	      //Plugins integration
                                          $this->events = new stdClass();
                                          $results = $dispatcher->trigger('onPrepareTrackLink', array(&$item, $this->params));
	                                      $this->events->PrepareTrackLink = trim(implode("\n", $results));

	                                      $results = $dispatcher->trigger('onBeforeTrackLink', array(&$item, $this->params));
	                                      $this->events->BeforeTrackLink = trim(implode("\n", $results));

	                                      $results = $dispatcher->trigger('onAfterTrackLink', array(&$item, $this->params));
	                                      $this->events->AfterTrackLink = trim(implode("\n", $results));

		                          	      if(JPluginHelper::isEnabled('playjoom','trackcontrol')==false)
		                          	      {
		                          	      	  echo '<td class="list-'.$item->title.'">'.$item->title.'</td>';
		                          	      }
		                          	      else
		                          	      {
		                          	      	  echo '<td class="list-'.$item->title.'">';
		                          	      	  echo $this->events->BeforeTrackLink;
		                          	      	  echo $this->events->PrepareTrackLink;
		                          	      	  echo $this->events->AfterTrackLink;
		                          	      	  echo '</td>';
		                          	      }
		                          	      echo '<td class="list-'.$item->category_title.'"><a href="'.$genrelink.'" title="Continue to the genre view">'.$item->category_title.'</td>';
		                          	      echo '<td class="list-'.$item->year.'">'.$item->year.'</td>';
		                          	    echo '</tr>';
				                 	}
				                 }
}