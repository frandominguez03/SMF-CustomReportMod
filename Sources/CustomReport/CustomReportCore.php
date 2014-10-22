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

if (!defined('SMF'))
	die('Hacking attempt...');

class CustomReportCore {
	public function __construct() {}

	public function reportSolved($topicId) {
		global $txt, $board_info, $user_info, $modSettings;

		$result = CustomReport::$CustomReportDB->checkIsTopicSolved($topicId);
		if(empty($result['solved'])) {
			$subject = '[' .$txt['report_solved'] . ']'. ' ' . $result['subject'];
			$body = $txt['report_solved'] . ' ' . $txt['by'] . ' ' . '\''. $user_info['name'] . '\'';

			$msgOptions = array(
				'subject' => $subject,
				'body' => $body,
			);
			$topicOptions = array(
				'id' => $topicId,
				'board' => $modSettings['report_board_id'],
				'lock_mode' => 1,
				'mark_as_read' => true,
			);
			$posterOptions = array(
				'id' => $user_info['id'],
				'update_post_count' => !empty($modSettings['enable_report_mod_count']) && $board_info['posts_count'],
			);
			createPost($msgOptions, $topicOptions, $posterOptions);
		} else {
			CustomReport::$CustomReportDB->unlockTopic($topicId);
		}

		$isSolved = empty($result['solved']) ? 1 : 0;
		CustomReport::$CustomReportDB->setSolveStatus(array(
			'isSolved' => $isSolved,
			'topicId' => $topicId
		));
	}
}

?>
