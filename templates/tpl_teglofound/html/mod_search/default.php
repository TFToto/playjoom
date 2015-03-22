<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_search
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="search<?php echo $moduleclass_sfx ?>">
    <form action="<?php echo JRoute::_('index.php');?>" method="post" class="form-inline">
    		<?php
				$output = '<input name="searchword" placeholder="'.JText::_('MOD_SEARCH_LABEL_TEXT').'" type="text" size="' . $width . '" />';

				if ($button) :
					if ($imagebutton) :
						$button = ' <button class="small button btn btn-primary" onclick="this.form.searchword.focus();"><i class="fa fa-search"></i></button>';
					else :
						$button = ' <button class="small button btn btn-primary" onclick="this.form.searchword.focus();"><i class="fa fa-search"></i>' . $button_text . '</button>';
					endif;
				endif;

				switch ($button_pos) :
					case 'top' :
						$button = $button . '<br />';
						$output = $button . $output;
						break;

					case 'bottom' :
						$button = '<br />' . $button;
						$output = $output . $button;
						break;

					case 'right' :
						$output = $output . $button;
						break;

					case 'left' :
					default :
						$output = $button . $output;
						break;
				endswitch;

				echo $output;
			?>
    	<input type="hidden" name="task" value="search" />
    	<input type="hidden" name="option" value="com_search" />
    	<input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
    </form>
</div>
