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

class CustomReportDB {
	public function __construct() {}

	public function checkIsTopicSolved($topicId) {
		global $smcFunc;

		$request = $smcFunc['db_query']('', '
			SELECT t.id_topic, t.id_first_msg, m.subject, c.solved
			FROM {db_prefix}topics AS t
			INNER JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_first_msg)
			INNER JOIN {db_prefix}custom_report_mod AS c ON (c.id_report_topic = t.id_topic)
			WHERE t.id_topic = {int:current_topic}
			LIMIT 1',
			array(
				'current_topic' => $topicId,
			)
		);
		$message = $smcFunc['db_fetch_assoc']($request);
		$smcFunc['db_free_result']($request);

		return $message;
	}

	public function unlockTopic($topicId) {
		global $smcFunc;

		$smcFunc['db_query']('', '
			UPDATE {db_prefix}topics
			SET locked = {int:locked}
			WHERE id_topic = {int:topic}',
			array(
				'locked' => 0,
				'topic' => $topicId,
			)
		);
	}

	public function setSolveStatus($data) {
		global $smcFunc;

		$smcFunc['db_query']('', '
			UPDATE {db_prefix}custom_report_mod
			SET solved = {int:is_solved}
			WHERE id_report_topic = {int:topic}',
			array(
				'is_solved' => $data['isSolved'],
				'topic' => $data['topicId'],
			)
		);
	}
}

?>
