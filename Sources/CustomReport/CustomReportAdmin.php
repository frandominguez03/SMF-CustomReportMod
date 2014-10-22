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

		$general_settings = array(
			array('check', 'cr_enable_mod'),
			array('select', 'cr_report_board'),
			array('check', 'cr_quote_reported_post'),
			array('check', 'cr_enable_report_count'),
			array('check', 'cr_enable_report_mod_count'),
			array('check', 'cr_enable_large_report_field')
		);

		require_once($sourcedir . '/ManageServer.php');
		saveDBSettings($general_settings);
		redirectexit('action=admin;area=likeposts;sa=generalsettings');
	}
}

?>
