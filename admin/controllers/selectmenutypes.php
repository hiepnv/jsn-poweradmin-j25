<?php
/**
 * @version     $Id$
 * @package     JSN_PowerAdmin
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');

class PoweradminControllerSelectmenutypes extends JControllerForm
{
	/**
	 * Sets the type of the menu item currently being editted.
	 *
	 * @return	void
	 * @since	1.6
	 */
	function setType()
	{
		// Initialise variables.
		$app		= JFactory::getApplication();

		// Get the posted values from the request.
		$data    = array();
		$data['type'] = json_decode(base64_decode(JRequest::getVar('params', '')));
		$data['menutype'] = JRequest::getVar('menutype', '');

		// Get the type.
		$type     = $data['type'];

		$title	  = isset($type->title) ? $type->title : null;
		$recordId = isset($type->id) ? $type->id : 0;

		if ($title != 'alias' && $title != 'separator' && $title != 'url') {
			$title = 'component';
		}

		$app->setUserState('com_menus.edit.item.type',	$title);
		if ($title == 'component') {
			if (isset($type->request)) {
				$component = JComponentHelper::getComponent($type->request->option);
				$data['component_id'] = $component->id;

				$app->setUserState(
					'com_menus.edit.item.link',
					'index.php?' . JURI::buildQuery((array)$type->request));
			}
		}
		// If the type is alias you just need the item id from the menu item referenced.
		else if ($title == 'alias') {
			$app->setUserState('com_menus.edit.item.link', 'index.php?Itemid=');
		}

		unset($data['request']);
		$data['type'] = $title;
		if (JRequest::getCmd('fieldtype') == 'type') {
			$data['link'] = $app->getUserState('com_menus.edit.item.link');
		}

		//Save the data in the session.
		$app->setUserState('com_menus.edit.item.data', $data);

		$this->type = $type;
		$this->setRedirect(JRoute::_('index.php?option=com_menus&&view=item&layout=edit'.$this->getRedirectToItemAppend($recordId), false));
	}
}
?>