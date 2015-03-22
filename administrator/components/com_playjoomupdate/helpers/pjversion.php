<?php
/**
 * Contains the PlayJoom version helper methods.
 *
 * PlayJoom and the basic package Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and details.
 *
 * @package PlayJoom.Admin
 * @subpackage helpers.playjoomupdate
 * @link http://playjoom.teglo.info
 * @copyright Copyright (C) 2010-2013 by www.teglo.info. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @PlayJoomUpdate Component
 * @date $Date$
 * @revision $Revision$
 * @author $Author$
 * @headurl $HeadURL$
 */

defined('_JEXEC') or die;

/**
 * Version information class for the PlayJoom Server.
 *
 * @package  PlayJoom.Admin
 * @since    0.9
 */
final class PJVersion {

	/** @var  string  Product name. */
	public $PRODUCT = 'PlayJoom';

	/** @var  string  Release version. */
	public $RELEASE = '0.10';

	/** @var  string  Maintenance version. */
	public $DEV_LEVEL = '1';

	/** @var  string  Development STATUS. */
	public $DEV_STATUS = 'Beta';

	/** @var  string  Build number. */
	public $BUILD = '';

	/** @var  string  Code name. */
	public $CODENAME = 'Mars';

	/** @var  string  Release date. */
	public $RELDATE = '22-March-2015';

	/** @var  string  Release time. */
	public $RELTIME = '14:00';

	/** @var  string  Release timezone. */
	public $RELTZ = 'GMT';

	/** @var  string  Copyright Notice. */
	public $COPYRIGHT = 'Copyright (C) 2010 - 2015 by teglo, Inc. All rights reserved.';

	/** @var  string  Link text. */
	public $URL = '<a href="http://playjoom.teglo.info">PlayJoom</a> is Free Software released under the GNU General Public License.';

	/**
	 * Compares two a "PHP standardized" version number against the current PlayJoom version.
	 *
	 * @param   string  $minimum  The minimum version of the PlayJoom which is compatible.
	 *
	 * @return  bool    True if the version is compatible.
	 *
	 * @see     http://www.php.net/version_compare
	 * @since   1.0
	 */
	public function isCompatible($minimum) {

		return version_compare(JVERSION, $minimum, 'ge');
	}

	/**
	 * Method to get the help file version.
	 *
	 * @return  string  Version suffix for help files.
	 *
	 * @since   1.0
	 */
	public function getHelpVersion()
	{
		return '.' . str_replace('.', '', $this->RELEASE);
	}

	/**
	 * Gets a "PHP standardized" version string for the current PlayJoom.
	 *
	 * @return  string  Version string.
	 *
	 * @since   1.5
	 */
	public function getShortVersion()
	{
		return $this->RELEASE . '.' . $this->DEV_LEVEL;
	}

	/**
	 * Gets a version string for the current PlayJoom with all release information.
	 *
	 * @return  string  Complete version string.
	 *
	 * @since   1.5
	 */
	public function getLongVersion()
	{
		return $this->PRODUCT . ' ' . $this->RELEASE . '.' . $this->DEV_LEVEL . ' '
				. $this->DEV_STATUS . ' [ ' . $this->CODENAME . ' ] ' . $this->RELDATE . ' '
				. $this->RELTIME . ' ' . $this->RELTZ;
	}

	/**
	 * Returns the user agent.
	 *
	 * @param   string  $component    Name of the component.
	 * @param   bool    $mask         Mask as Mozilla/5.0 or not.
	 * @param   bool    $add_version  Add version afterwards to component.
	 *
	 * @return  string  User Agent.
	 *
	 * @since   1.0
	 */
	public function getUserAgent($component = null, $mask = false, $add_version = true)
	{
		if ($component === null)
		{
			$component = 'Framework';
		}

		if ($add_version)
		{
			$component .= '/' . $this->RELEASE;
		}

		// If masked pretend to look like Mozilla 5.0 but still identify ourselves.
		if ($mask)
		{
			return 'Mozilla/5.0 ' . $this->PRODUCT . '/' . $this->RELEASE . '.' . $this->DEV_LEVEL . ($component ? ' ' . $component : '');
		}
		else
		{
			return $this->PRODUCT . '/' . $this->RELEASE . '.' . $this->DEV_LEVEL . ($component ? ' ' . $component : '');
		}
	}
}
