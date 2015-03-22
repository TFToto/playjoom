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
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * PlayJoom Component Controller
 */

class PlayJoomController extends JControllerLegacy
{
public function display($cachable = false, $urlparams = false)
	{

        // Get the document object.
        $document = JFactory::getDocument();
        $dispatcher	= JDispatcher::getInstance();

		// Set the default view name and format from the Request.
		$vName	 = JRequest::getWord('view', 'login');
		$vFormat = $document->getType();
		$lName	 = JRequest::getWord('layout', 'default');
		$uri = JFactory::getURI();

		$url = 'index.php?option=com_content&task=article.add&return='.base64_encode($uri).'&id=0';

		if ($view = $this->getView($vName, $vFormat)) {

			// Do any specific processing by view.
			switch ($vName) {

				// Handle view specific models.
				case 'adminplaylists':

					// If the user is a guest, redirect to the login page.
					$user = JFactory::getUser();
					if ($user->get('guest') == 1) {
						// Redirect to login page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($uri), false));
						return;
					}
					$model = $this->getModel($vName);
					break;

               case 'adminplaylist':

					// If the user is a guest, redirect to the login page.
					$user = JFactory::getUser();
					if ($user->get('guest') == 1) {
						// Redirect to login page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($uri), false));
						return;
					}
					$model = $this->getModel($vName);
					break;

			   case 'addtoplaylist':

					// If the user is a guest, redirect to the login page.
					$user = JFactory::getUser();
					if ($user->get('guest') == 1) {
						// Redirect to login page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=login&tmpl=component&return='.base64_encode($uri), false));
						return;
					}
					$model = $this->getModel($vName);
					break;

			    case 'album':
			    	$cachable = false;
			    	$safeurlparams = array('catid'=>'INT','id'=>'INT','cid'=>'ARRAY','year'=>'INT','month'=>'INT','limit'=>'INT','limitstart'=>'INT',
			    			'showall'=>'INT','return'=>'BASE64','filter'=>'STRING','filter_order'=>'CMD','filter_order_Dir'=>'CMD','filter-search'=>'STRING','print'=>'BOOLEAN','lang'=>'CMD');

			    	parent::display($cachable,$safeurlparams);

			    	return $this;
			    break;

			    case 'artist':
			    	$cachable = false;
			    	$safeurlparams = array('catid'=>'INT','id'=>'INT','cid'=>'ARRAY','year'=>'INT','month'=>'INT','limit'=>'INT','limitstart'=>'INT',
			    			'showall'=>'INT','return'=>'BASE64','filter'=>'STRING','filter_order'=>'CMD','filter_order_Dir'=>'CMD','filter-search'=>'STRING','print'=>'BOOLEAN','lang'=>'CMD');

			    	parent::display($cachable,$safeurlparams);

			    	return $this;
			    break;

			    case 'alphabetical':
			    	$cachable = false;
			    	$safeurlparams = array('catid'=>'INT','id'=>'INT','cid'=>'ARRAY','year'=>'INT','month'=>'INT','limit'=>'INT','limitstart'=>'INT',
			    			'showall'=>'INT','return'=>'BASE64','filter'=>'STRING','filter_order'=>'CMD','filter_order_Dir'=>'CMD','filter-search'=>'STRING','print'=>'BOOLEAN','lang'=>'CMD');

			    	parent::display($cachable,$safeurlparams);

			    	return $this;
			    	break;

			    case 'broadcast':

			    	if (!JRequest::getVar('format')) {
			    		$this->setRedirect(JRoute::_('index.php?option=com_playjoom&view=broadcast&format=raw&id='.JRequest::getVar('id'), false));
			    	}

			    	$model = $this->getModel($vName);
			    	break;
			    case 'download':
			    	$URLquery = null;
			    	if (!JRequest::getVar('format')) {
			    		if (JRequest::getVar('source') != '') {
			    			$URLquery .= '&source='.JRequest::getVar('source');
			    		}
			    		if (JRequest::getVar('listid') != '') {
			    			$URLquery .= '&listid='.JRequest::getVar('listid');
			    		}
			    		if (JRequest::getVar('name') != '') {
			    			$URLquery .= '&name='.JRequest::getVar('name');
			    		}
			    		if (JRequest::getVar('artist') != '') {
			    			$URLquery .= '&artist='.JRequest::getVar('artist');
			    		}
			    		$dispatcher->trigger('onEventLogging', array(array('method' => __METHOD__.":".__LINE__, 'message' => 'Create link: index.php?option=com_playjoom&view=download&format=raw'.$URLquery, 'priority' => JLog::INFO, 'section' => 'site')));

			    		$this->setRedirect(JRoute::_('index.php?option=com_playjoom&view=download&format=raw'.$URLquery, false));
			    	}

			    	$model = $this->getModel($vName);
			    	break;

			    case 'infoabout':
			    	$cachable = false;
			    	$safeurlparams = array('catid'=>'INT','id'=>'INT','cid'=>'ARRAY','year'=>'INT','month'=>'INT','limit'=>'INT','limitstart'=>'INT',
			    			'showall'=>'INT','return'=>'BASE64','filter'=>'STRING','filter_order'=>'CMD','filter_order_Dir'=>'CMD','filter-search'=>'STRING','print'=>'BOOLEAN','lang'=>'CMD');

			    	parent::display($cachable,$safeurlparams);

			    	return $this;
			    	break;

			    case 'playlist':
			    	$URLquery = null;
			    	if (!JRequest::getVar('format')) {
			    		if (JRequest::getVar('source') != '') {
			    			$URLquery .= '&source='.JRequest::getVar('source');
			    		}
			    		if (JRequest::getVar('listid') != '') {
			    			$URLquery .= '&listid='.JRequest::getVar('listid');
			    		}
			    		if (JRequest::getVar('trackfilterid') != '') {
			    			$URLquery .= '&trackfilterid='.JRequest::getVar('trackfilterid');
			    		}
			    		if (JRequest::getVar('name') != '') {
			    			$URLquery .= '&name='.JRequest::getVar('name');
			    		}
			    		if (JRequest::getVar('attachment_playlist') != '') {
			    			$URLquery .= '&attachment_playlist='.JRequest::getVar('attachment_playlist');
			    		}
			    		if (JRequest::getVar('orderplaylist') != '') {
			    			$URLquery .= '&orderplaylist='.JRequest::getVar('orderplaylist');
			    		}
			    		if (JRequest::getVar('artist') != '') {
			    			$URLquery .= '&artist='.JRequest::getVar('artist');
			    		}
			    		$this->setRedirect(JRoute::_('index.php?option=com_playjoom&view=playlist&format=raw'.$URLquery, false));
			    	}

			    	$model = $this->getModel($vName);
			    	break;

			    	case 'suggestionsearch':
			    		$URLquery = null;
			    		if (!JRequest::getVar('format')) {

			    			if (JRequest::getVar('term') != '') {
			    				$URLquery .= '&term='.JRequest::getVar('term');
			    			}
			    			$this->setRedirect(JRoute::_('index.php?option=com_playjoom&view=suggestionsearch&format=raw'.$URLquery, false));
			    		}

			    		$model = $this->getModel($vName);
			    	break;

				default:
					$cachable = true;
					$safeurlparams = array('catid'=>'INT','id'=>'INT','cid'=>'ARRAY','year'=>'INT','month'=>'INT','limit'=>'INT','limitstart'=>'INT',
                        'showall'=>'INT','return'=>'BASE64','filter'=>'STRING','filter_order'=>'CMD','filter_order_Dir'=>'CMD','filter-search'=>'STRING','print'=>'BOOLEAN','lang'=>'CMD');

					parent::display($cachable,$safeurlparams);

                    return $this;
					break;
			}

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	}

    function vote()
	{
		$user_rating = JRequest::getInt('user_rating', -1);

		if ( $user_rating > -1 ) {
			$url = JRequest::getString('url', '');
			$id = JRequest::getVar('track_id');
			$viewName = JRequest::getString('view', $this->default_view);
			$model = $this->getModel($viewName);

			if ($model->storeVote($id, $user_rating)) {
				$this->setRedirect($url, JText::_('COM_PLAYJOOM_TRACK_VOTE_SUCCESS'));
			}
			else {
				$this->setRedirect($url, JText::_('COM_PLAYJOOM_TRACK_VOTE_FAILURE'));
			}
		}
	}
}