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
$bar = null;

$bar .= "<div id='page'><div class='h'><div id='stat'></div></div></div>

<script type='text/javascript'>

scheme1 = new Array ('#86A9CC','#AEC5DD','#D6E2EE','#597088','#DDC5AE','#EEE2D6','#808080','#2C3844','#CCA986','#795633','#000000','#17282B','#016132','#5F9A29','#B4D523','#1C2023','#374132','#878762','#C0D7B2');
scheme2 = new Array ('#98AF85','#BAC9AD','#DCE4D6','#657458','#BAADC9','#DCD6E4','#808080','#323A2C','#9885AF','#67507A','#000000','#0C353A','#1C5964','#2D91A4','#49D9E4','#17282B','#016132','#5F9A29','#B4D523');

genres = new Array(
";

foreach($this->items as $i => $item) 
{
	$NumbersOfItem   = PlayJoomModelStatistics::getCounts($item->id);
	$NumbersOfToal   = PlayJoomModelStatistics::getCounts();
	
	//Create links
	$genresting = base64_encode($item->category_title); 
    $genrelink = 'index.php?option=com_playjoom&view=genre&cat='.$genresting.'&catid='.$item->id.'&Itemid='.JRequest::getVar('Itemid');
    
	if($i +1 == count($this->items))
	{
		$bar .= "[".round($NumbersOfItem / $NumbersOfToal * 100,1).",'".$item->category_title."','','".$genrelink."','".$item->category_title." - Click here to enter this genre']";
	}
	else 
	{
	    $bar .= "[".round($NumbersOfItem / $NumbersOfToal * 100,1).",'".$item->category_title."','','".$genrelink."','".$item->category_title." - Click here to enter this genre'],"; 
	}
}
$bar .= ");";

$bar .= "	
var graph = new mooBarGraph({
	container: $('stat'),
	data: genres,
	width: ".$this->params->get('moobargraph_width').",
	height: ".$this->params->get('moobargraph_height').",
	colors: ".$this->params->get('color_scheme').",
	color: '#1A2944',
	barSpace: 5,
	legend: true,
	legendWidth: 120,
	title: '<h3>Percentages allocated of the genres.<br /><small>put mouse on bar for short info and click to enter this genre</small></h3>'
});

setInterval( function(){ graph2.draw('ajaxdata.php?type=realTime&sleep=0'); }, 1000 );

</script>";
echo $bar;

$CSSSlider = null;
$MootoolsSlider = null;

foreach($this->items as $i => $item) 
{
	$NumbersOfItem = PlayJoomModelStatistics::getCounts($item->id);
	$NumbersOfToal = PlayJoomModelStatistics::getCounts();
	$genre_item    = PlayJoomModelStatistics::getArtistItems($item->id);
	
	/*
	 * Slider settings
	 */
	$MootoolsSlider .= "
	<script type='text/javascript'>
          window.addEvent('domready', function() {
               var myVerticalSlide = new Fx.Slide('vertical_slide".$i."').hide();

               $('v_toggle".$i."').addEvent('click', function(event){
                    event.stop();
                    myVerticalSlide.toggle();
               });
          });
    </script>
               ";
	$CSSSlider .= '#vertical_slide'.$i.',';
    
	//Create links
	$genresting = base64_encode($item->category_title); 
    $genrelink = 'index.php?option=com_playjoom&view=genre&cat='.$genresting.'&catid='.$item->id.'&Itemid='.JRequest::getVar('Itemid');
                                       
	echo '<tr class="row1">';
      echo '<td class="list"><a href="'.$genrelink.'" title="Continue to the genre view">'.$item->category_title.'</a> | '.count($genre_item).' Artists | <a id="v_toggle'.$i.'" href="#">'.JText::_('COM_PLAYJOOM_ALBUM_MORE_INFO').'</a>';
	     echo '<div id="vertical_slide'.$i.'">';
            echo '<ul>';
		         
		         foreach($genre_item as $j => $item_entrie) 
		         {
		         	if ($j < count($genre_item)) 
		            {
		            	//Get Number of Tracks
		            	$NumberOfArtistTrack = PlayJoomModelStatistics::getCounts($item->id, $item_entrie->artist);
		            	
		            	//Create links
		            	$artiststing = base64_encode($item_entrie->artist); 
	                    $artistlink = 'index.php?option=com_playjoom&view=artist&artist='.$artiststing.'&Itemid='.JRequest::getVar('Itemid');
	                    
		              	echo '<li><a href="'.$artistlink.'" title="Continue to the album view">'.$item_entrie->artist.'</a> | '.$NumberOfArtistTrack.' Tracks | '.round($NumberOfArtistTrack / $NumbersOfItem * 100,1).'% </li>';	
		            }
		         	
		         	else 
		            {
		            	$j = $j -1;	
		            }		            
		         }
		         
	        echo '</ul>';
	     echo '</div>';
      echo '</td>';
      echo '<td class="list">'.$NumbersOfItem.'</td>';
      echo '<td class="list">'.round($NumbersOfItem / $NumbersOfToal * 100, 1) .' %</td>';
  echo '</tr>';
}

echo '<tr class="row1">';
      echo '<td class="list"><b>Total Tracks</b></td>';
      echo '<td class="list"><b>'.$NumbersOfToal.'</b></td>';
      echo '<td class="list"><b>100%</b></td>';
echo '</tr>';

$Slider = "
          <script type='text/javascript'>
              window.addEvent('domready', function() {
              ".$MootoolsSlider."
              });
          </script>";

$CSSContent = "<style>".
              $CSSSlider.
             "#bank {
                background: transparent;
                color: #888888;
                padding-left: 10px;
                font-weight: normal;
              }
              </style>";

$document	= JFactory::getDocument();
$document->addCustomTag($MootoolsSlider);
$document->addCustomTag($CSSContent);
