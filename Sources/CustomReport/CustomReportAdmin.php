<?php

/**
* @package manifest file for Like Posts
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
* Contributor(s): Big thanks to all contributor(s)
*
*/
 

if (!defined('SMF'))
	die('Hacking attempt...');

class CustomReportAdmin {
	private $dbInstance;

	public function __construct() {}

	public function generalSettings($return_config = false) {
		global $txt, $context, $sourcedir;

		require_once($sourcedir . '/ManageServer.php');
		$reportBoards = CustomReport::$CustomReportDB->reportBoardsOptions();

		$general_settings = array(
			array('check', 'cr_enable_mod', 'onclick' => 'document.getElementById(\'cr_report_board_id\').disabled = !this.checked;'),
			array('select', 'cr_report_board', $reportBoards),
			array('check', 'cr_quote_reported_post', 'subtext' => $txt['cr_quote_reported_post_info']),
			array('check', 'cr_enable_report_count', 'subtext' => $txt['cr_enable_report_count_info']),
			array('check', 'cr_enable_report_mod_count', 'subtext' => $txt['cr_enable_report_mod_count_info']),
			array('check', 'cr_enable_large_report_field', 'subtext' => $txt['cr_enable_large_report_field_info'])
		);

		$context['page_title'] = $txt['cr_admin_panel'];
		$context['sub_template'] = 'cr_admin_general_settings';
		$context['custom_report']['tab_name'] = $txt['cr_general_settings'];
		$context['custom_report']['tab_desc'] = $txt['cr_general_settings_desc'];
		prepareDBSettingContext($general_settings);
	}

	public function saveGeneralSettings() {
		global $sourcedir;

		/* I can has Adminz? */
		isAllowedTo('admin_forum');
		checkSession('request', '', true);

		$reportBoards = CustomReport::$CustomReportDB->reportBoardsOptions();
		$general_settings = array(
			array('check', 'cr_enable_mod'),
			array('select', 'cr_report_board', $reportBoards),
			array('check', 'cr_quote_reported_post'),
			array('check', 'cr_enable_report_count'),
			array('check', 'cr_enable_report_mod_count'),
			array('check', 'cr_enable_large_report_field')
		);

		require_once($sourcedir . '/ManageServer.php');
		saveDBSettings($general_settings);
		redirectexit('action=admin;area=customreport;sa=generalsettings');
	}

	public function permissionSettings() {
		global $txt, $context, $sourcedir;

		require_once($sourcedir . '/Subs-Membergroups.php');
		$context['custom_report']['groups_permission_settings'] = array(
			'cr_can_solve_report'
		);
		$context['custom_report']['groups'] = list_getMembergroups(null, null, 'id_group', 'regular');

		$context['page_title'] = $txt['cr_admin_panel'];
		$context['sub_template'] = 'cr_admin_permission_settings';
		$context['custom_report']['tab_name'] = $txt['cr_permission_settings'];
		$context['custom_report']['tab_desc'] = $txt['cr_permission_settings_desc'];
	}

	public function savePermissionsettings() {
		global $context;

		/* I can has Adminz? */
		isAllowedTo('admin_forum');
		checkSession('request', '', true);
		unset($_POST['submit']);

		// set up the vars for groups and guests permissions
		$context['custom_report']['groups_permission_settings'] = array(
			'cr_can_solve_report'
		);

		$permissionKeys = array(
			'cr_can_solve_report',
		);

		// Array to be saved to DB
		$general_settings = array();
		foreach($_POST as $key => $val) {
			if(in_array($key, $context['custom_report']['groups_permission_settings'])) {
				// Extract the user permissions first
				if(array_filter($_POST[$key], 'is_numeric') === $_POST[$key]) {
					if(($key1 = array_search($key, $permissionKeys)) !== false) {
						unset($permissionKeys[$key1]);
					}
					$_POST[$key] = implode(',', $_POST[$key]);
					$general_settings[] = array($key, $_POST[$key]);
				}
			}
		}

		// Remove the keys which were saved previously but removed this time
		if(!empty($permissionKeys)) {
			foreach ($permissionKeys as $value) {
				$general_settings[] = array($value, '');
			}
		}

		if(!empty($guestPermissionKeys)) {
			foreach ($guestPermissionKeys as $value) {
				$general_settings[] = array($value, '');
			}
		}
		CustomReport::$CustomReportDB->updatePermissions($general_settings);
		redirectexit('action=admin;area=customreport;sa=permissionsettings');
	}
}

?>
