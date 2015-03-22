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
 * @copyright Copyright (C) 2010 by www.teglo.info
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<fieldset class="batch">
	<legend><?php echo JText::_('COM_PLAYJOOM_ADD_TRACK');?></legend>
	
<h3><?php echo JText::_( 'COM_PLAYJOOM_CHOOSE_FOLDER_HEAD' ); ?></h3>

			<?php
			$dirName   = $this->state->get('list.dirName');
					// Now let's get the dirs
					if (isset($_GET['dir'])){
						$dirName = $_GET['dir'];
						$os = "";
						if (is_dir("c:/")){
							$os = "win";							
						}
					} else {
						// Now let's see if we are on linux or Windows
						$os = "";
						if (is_dir("c:/")){
							$os = "win";							
						}
						if ($os !== "win"){
							$dirName = "/";
						} else {
							$dirName = "c:";
						}
					}
				?>
					
					<?php 
						if ($os == "win"){
							// Now let's let them select other drives
							//echo '<form name="browserForm" action="browse.php?lang=en&prefix='. $prefix. '" method="get">';
							echo '<input type="hidden" name="lang" value="en">';
							echo '<select name="dir" onChange="submit()">';
							$ctr=99;
							while($ctr<123){
								echo '<option ';
								if ($dirName == chr($ctr). ":"){ echo " selected "; }
								echo ' value="'. chr($ctr). ':">'. chr($ctr). ':</option>';
								$ctr++;
							}
							echo '</select> ';
							//echo '</form>';
						}
					?>

					<input type="text" value="<?php echo $dirName; ?>" name="dirName" size="20"> 			
					
				
	
				<br />
				<br />
				<?php
		
					if (is_dir($dirName) and is_readable($dirName)){
						$d = @dir($dirName);
						echo '<a href="index.php?option=com_playjoom&view=audiotracks">Back to root folder</a><br>';
						while($entry = @$d->read()) {
							$dirArray[] = $entry;
						}
						$d->close();
						sort($dirArray);
						for ($i=0; $i < count($dirArray); $i++){
							// Let's make sure this isn't the local directory we're looking at
							if ($dirArray[$i] == "." || $dirArray[$i] == "..") { continue;}
							if (is_dir($dirName. "/". $dirArray[$i])){
								if ($dirName == "/"){ $dir = $dirName. $dirArray[$i]; } else { $dir = $dirName. "/". $dirArray[$i]; }
								echo '&nbsp; &nbsp;<a href="index.php?option=com_playjoom&view=audiotracks&dir='.$dir.'">'. "/". $dirArray[$i]. "</a><br>";
							}
						}
					}
					
				?>
	
</fieldset>      
