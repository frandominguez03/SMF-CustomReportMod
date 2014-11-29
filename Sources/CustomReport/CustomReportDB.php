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

if (!defined('SMF')) {
	die('Hacking attempt...');
}

class CustomReportDB {
	public function __construct() {}

	/*
	* to update permission settings from admin panel
	* @param array $replaceArray
	*/
	public function updatePermissions($replaceArray) {
		global $smcFunc;

		$smcFunc['db_insert']('replace',
			'{db_prefix}settings',
			array('variable' => 'string-255', 'value' => 'string-65534'),
			$replaceArray,
			array('variable')
		);
		cache_put_data('modSettings', null, 90);
	}

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

	public function reportBoardsOptions() {
		global $smcFunc, $modSettings;

		$reportBoards = array();
		$request = $smcFunc['db_query']('order_by_board_order', '
			SELECT b.id_board, b.name AS board_name, c.name AS cat_name
			FROM {db_prefix}boards AS b
				LEFT JOIN {db_prefix}categories AS c ON (c.id_cat = b.id_cat)
			WHERE redirect = {string:empty_string}' . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? '
			AND b.id_board != {int:recycle_board_id}' : ''),
			array(
				'empty_string' => '',
				'recycle_board_id' => $modSettings['recycle_board'],
			)
		);
		while ($row = $smcFunc['db_fetch_assoc']($request))
			$reportBoards[$row['id_board']] = $row['cat_name'] . ' - ' . $row['board_name'];

		$smcFunc['db_free_result']($request);
		return $reportBoards;
	}

	public function canSeeTopic($data) {
		global $smcFunc;

		$request = $smcFunc['db_query']('', '
			SELECT m.id_topic, m.subject, m.body, m.id_member AS id_poster, m.poster_name, mem.real_name, m.poster_time
			FROM {db_prefix}messages AS m
				LEFT JOIN {db_prefix}members AS mem ON (m.id_member = mem.id_member)
			WHERE m.id_msg = {int:id_msg}
				AND m.id_topic = {int:current_topic}
			LIMIT 1',
			array(
				'current_topic' => $data['topic'],
				'id_msg' => $data['msg'],
			)
		);
		if ($smcFunc['db_num_rows']($request) == 0)
			fatal_lang_error('no_board', false);

		$message = $smcFunc['db_fetch_assoc']($request);
		$smcFunc['db_free_result']($request);
		return $message;
	}

	public function isAlreadyReported($data) {
		global $smcFunc;

		$idReportTopic = 0;
		$request = $smcFunc['db_query']('', '
			SELECT c.id_report_topic
			FROM {db_prefix}custom_report_mod AS c
			WHERE c.id_msg = {int:id_msg}
			AND c.id_topic = {int:current_topic}
			LIMIT 1',
			array(
				'id_msg' => $data['msgId'],
				'current_topic' => $data['topic'],
			)
		);
		if ($smcFunc['db_num_rows']($request) > 0)
			list ($idReportTopic) = $smcFunc['db_fetch_row']($request);

		$smcFunc['db_free_result']($request);
		return $idReportTopic;
	}

	public function setReportStatus($data) {
		global $smcFunc;

		if(empty($data['idReportTopic'])) {
			$smcFunc['db_insert']('',
			'{db_prefix}custom_report_mod',
				array(
					'id_report_topic' => 'int', 'id_msg' => 'int', 'id_topic' => 'int',
				),
				array(
					$data['idReportTopic'], $data['msgId'], $data['topic'],
				),
			array('')
			);
		} else {
			// Opps someone is making a reply, quickly mark this as unsolved
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}custom_report_mod
				SET solved = {int:is_solved}
				WHERE id_report_topic = {int:topic}',
				array(
					'topic' => $data['idReportTopic'],
					'is_solved' => 0,
				)
			);
		}
	}
}

?>
