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
* emanuele45 (https://github.com/emanuele45)
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
		CustomReport::loadClass('CustomReportDB');
		CustomReport::loadClass('CustomReportCore');

		CustomReport::$CustomReportCore->reportSolved($topic);
	}
}

?>
