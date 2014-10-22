<?php

/**
* @package manifest file for Custom Report Mod
* @version 1.4
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


class CustomReport {
	protected static $instance;

	public static $sourceFolder = '/CustomReport/';

	/**
	 * Singleton method
	 *
	 * @return LikePosts
	 * @return LikePosts
	 */
	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new CustomReport();
			loadLanguage('CustomReport');
		}
		return self::$instance;
	}

	public function __construct() {}

	/**
	 * @param string $className
	 */
	public static function loadClass($className) {
		global $sourcedir;

		switch($className) {
			case 'CustomReportAdmin':
				if (self::$CustomReportAdmin === null) {
					require_once ($sourcedir . self::$sourceFolder . '/' . $className . '.php');
					self::$CustomReportAdmin = new CustomReportAdmin();
				}
				break;

			default:
				break;
		}
	}

	public static function addActionContext(&$actions) {
		self::loadClass('LikePostsRouter');
		$actions['report_solved'] = array(self::$sourceFolder . 'LikePostsRouter.php', 'LikePostsRouter::ReportSolved');
	}

	public static function addAdminPanel(&$admin_areas) {
		global $txt;

		$admin_areas['config']['areas']['likeposts'] = array(
			'label' => $txt['lp_menu'],
			'file' => '/LikePosts/LikePostsRouter.php',
			'function' => 'routeLikePostsAdmin',
			'icon' => 'administration.gif',
			'subsections' => array(),
		);
	}

	public static function loadPermissions(&$permissionGroups, &$permissionList) {
		global $context;

		$permissionGroups['membergroup']['simple'] = array('can_solve_report');
		$permissionGroups['membergroup']['classic'] = array('can_solve_report');
		$permissionList['membergroup']['can_solve_report'] = array(false, 'member_admin', 'moderate_general');

		$context['non_guest_permissions'] = array_merge(
			$context['non_guest_permissions'],
			array('can_solve_report')
		);
	}
}
CustomReport::getInstance();

?>