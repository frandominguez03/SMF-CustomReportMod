<?php

/**
* @package manifest file for Custom Report Mod
* @version 2.0.1
* @author Joker (http://www.simplemachines.org/community/index.php?action=profile;u=226111)
* @copyright Copyright (c) 2014, Siddhartha Gupta
* @license http://www.mozilla.org/MPL/MPL-1.1.html
*/

/*
* Version: MPL 1.1
*
* The contents of this file are subject to the Mozilla Public License Version
* 1.1 (the "License"); you may not use this file except in compliance with
* the License. You may obtain a copy of the License at
* http://www.mozilla.org/MPL/
*
* Software distributed under the License is distributed on an "AS IS" basis,
* WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
* for the specific language governing rights and limitations under the
* License.
*
* The Initial Developer of the Original Code is
*  Joker (http://www.simplemachines.org/community/index.php?action=profile;u=226111)
* Portions created by the Initial Developer are Copyright (C) 2012
* the Initial Developer. All Rights Reserved.
*
* Contributor(s):
*
*/

if (!defined('SMF')) {
	die('Hacking attempt...');
}

function routeCustomReportAdmin() {
	global $txt, $context;

	/* I can has Adminz? */
	isAllowedTo('admin_forum');

	CustomReport::loadClass('CustomReportDB');
	CustomReport::loadClass('CustomReportAdmin');
	loadtemplate('CustomReportAdmin');

	$context['page_title'] = $txt['cr_admin_panel'];
	$defaultActionFunc = 'generalSettings';

	// Load tabs menu, text etc for the admin panel
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['cr_admin_panel'],
		'tabs' => array(
			'generalsettings' => array(
				'label' => $txt['cr_general_settings'],
				'url' => 'generalsettings',
			),
			'permissions' => array(
				'label' => $txt['cr_permission_settings'],
				'url' => 'permissionsettings',
			)
		),
	);
	$context[$context['admin_menu_name']]['tab_data']['active_button'] = isset($_REQUEST['sa']) ? $_REQUEST['sa'] : 'generalsettings';

	$subActions = array(
		'generalsettings' => 'generalSettings',
		'savegeneralsettings' => 'saveGeneralSettings',
		'permissionsettings' => 'permissionSettings',
		'savepermissionsettings' => 'savePermissionsettings'
	);

	//wakey wakey, call the func you lazy
	if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) && method_exists(CustomReport::$CustomReportAdmin, $subActions[$_REQUEST['sa']]))
		return CustomReport::$CustomReportAdmin->$subActions[$_REQUEST['sa']]();

	// At this point we can just do our default.
	CustomReport::$CustomReportAdmin->$defaultActionFunc();
}

class CustomReportRouter {
	public function __construct() {}

	public static function reportSolved() {
		global $topic, $sourcedir;

		// Make all the checks
		isAllowedTo('can_solve_report');
		checkSession('request', '', true);
		checkSubmitOnce('check');

		loadLanguage('Post');
		require_once($sourcedir . '/Subs-Post.php');
		CustomReport::loadClass('CustomReportCore');

		CustomReport::$CustomReportCore->reportSolved($topic);
	}
}

?>
