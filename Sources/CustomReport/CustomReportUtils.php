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
* Contributor(s):
*
*/

if (!defined('SMF'))
	die('Hacking attempt...');

class CustomReportUtils {
	private $dbInstance;

	public function __construct() {}

	public function checkSolveStatus($topicId) {
		global $txt, $context, $modSettings;

		if($context['current_board'] !== $modSettings['report_board_id'] || !$this->isAllowedTo()) {
			$data = array(
				'showButton' => false,
			);
			return $data;
		}

		// Load the class if only required
		CustomReport::loadClass('CustomReportDB');
		$this->$dbInstance = new CustomReportDB();

		$isTopicSolved = $this->$dbInstance->checkIsTopicSolved($topicId);
		$data = array(
			'text' => empty($isTopicSolved['solved']) ? '[' . $txt['report_solved']. ']' : '[' . $txt['report_unsolved']. ']',
			'showButton' => true
		);
		return $data;
	}

	public function isAllowedTo() {
		global $user_info, $modSettings;

		if ($user_info['is_admin']) {
			return true;
		}

		$allowedGroups = explode(',', $modSettings['cr_can_solve_report']);
		$groupsPassed = array_intersect($allowedGroups, $user_info['groups']);

		if (empty($groupsPassed)) {
			return false;
		}
		return true;
	}
}

?>
