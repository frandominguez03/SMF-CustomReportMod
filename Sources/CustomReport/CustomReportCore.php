<?php

/**
* @package manifest file for Custom Report Mod
* @author Joker (http://www.simplemachines.org/community/index.php?action=profile;u=226111), original author
* @author Francisco "d3vcho" Domínguez (https://www.simplemachines.org/community/index.php?action=profile;u=422971)
* @copyright Copyright (c) 2018, Francisco Domínguez
* @version 2.0.2
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

class CustomReportCore {
	private $dbInstance;
	private $post_errors;
	private $poster_data;
	private $post_data;
	private $replacements;

	public function __construct() {
		CustomReport::loadClass('CustomReportDB');
		$this->dbInstance = new CustomReportDB();
	}

	public function reportSolved($topicId) {
		global $txt, $board_info, $user_info, $modSettings;

		$result = $this->dbInstance->checkIsTopicSolved($topicId);
		if(empty($result['solved'])) {
			$subject = '[' .$txt['cr_report_solved'] . ']'. ' ' . $result['subject'];
			$body = $txt['cr_report_solved'] . ' ' . $txt['by'] . ' ' . '\''. $user_info['name'] . '\'';

			$msgOptions = array(
				'subject' => $subject,
				'body' => $body,
			);
			$topicOptions = array(
				'id' => $topicId,
				'board' => $modSettings['cr_report_board'],
				'lock_mode' => 1,
				'mark_as_read' => true,
			);
			$posterOptions = array(
				'id' => $user_info['id'],
				'update_post_count' => !empty($modSettings['cr_enable_report_mod_count']) && $board_info['posts_count'],
			);
			createPost($msgOptions, $topicOptions, $posterOptions);
		} else {
			$this->dbInstance->unlockTopic($topicId);
		}

		$isSolved = empty($result['solved']) ? 1 : 0;
		$this->dbInstance->setSolveStatus(array(
			'isSolved' => $isSolved,
			'topicId' => $topicId
		));

		// Back to the post we reported!
		redirectexit('topic=' . $topicId . '.0');
	}

	public function checkSolveStatus($topicId) {
		global $txt, $context, $modSettings;

		if($context['current_board'] !== $modSettings['cr_report_board'] || !$this->isAllowedTo()) {
			$data = array(
				'showButton' => false,
			);
			return $data;
		}

		$isTopicSolved = $this->dbInstance->checkIsTopicSolved($topicId);
		$data = array(
			'text' => empty($isTopicSolved['solved']) ? '[' . $txt['cr_report_solved']. ']' : '[' . $txt['cr_report_unsolved']. ']',
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

	public function CustomReportToModerator2() {
		global $txt, $topic, $board, $user_info, $smcFunc, $context;

		$this->validateUser();

		// No errors, yet.
		$this->post_errors = array();
		$this->poster_data = array();
		$this->post_data = array();

		$this->post_data['msgId'] = (int) $_POST['msg'];
		$this->post_data['topicId'] = $topic;
		$this->post_data['boardId'] = $board;

		// Make sure we have a comment and it's clean.
		if (!isset($_POST['comment']) || $smcFunc['htmltrim']($_POST['comment']) === '')
			$this->post_errors[] = 'no_comment';

		$this->post_data['comment'] = $smcFunc['htmlspecialchars']($_POST['comment'], ENT_QUOTES);

		$this->setUserInfo();
		$this->captchaVerification();

		// Any errors?
		if (!empty($this->post_errors)) {
			loadLanguage('Errors');

			$context['post_errors'] = array();
			foreach ($this->post_errors as $post_error)
				$context['post_errors'][] = $txt['error_' . $post_error];

			return $this->CustomReportToModerator2();
		}

		// Get the basic topic information, and make sure they can see it.
		$message = $this->dbInstance->canSeeTopic(array(
			'topic' => $this->post_data['topicId'],
			'msg' => $this->post_data['msgId']
		));

		// is post already reported
		$this->post_data['id_report_topic'] = $this->dbInstance->isAlreadyReported(array(
			'topic' => $this->post_data['topicId'],
			'msg' => $this->post_data['msgId']
		));

		// Get the poster and reporter names
		$this->post_data['poster_name'] = un_htmlspecialchars($message['real_name']);
		$this->post_data['poster_time'] = $message['poster_time'];
		$this->poster_data['reporter_name'] = un_htmlspecialchars(!$user_info['is_guest'] ? $user_info['name'] : $_POST['guestname']);
		$this->post_data['org_subject'] = $message['subject'];
		$this->post_data['org_body'] = $message['body'];
		$this->post_data['org_id_poster'] = $message['id_poster'];

		$this->poster_data['prev_reported'] = $this->dbInstance->getOldReports(array(
			'topic' => $this->post_data['topicId'],
			'msg' => $this->post_data['msgId']
		));

		$this->createReportContent();
		$this->createReport();

		// set update report status
		$this->dbInstance->setReportStatus(array(
			'newTopicId' => $this->post_data['newTopicId'],
			'idReportTopic' => $this->post_data['id_report_topic'],
			'topic' => $this->post_data['topicId'],
			'msg' => $this->post_data['msgId']
		));

		$this->processEmails();
		// Back to the post we reported!
		redirectexit('reportsent;topic=' . $this->post_data['topicId'] . '.msg' . $this->post_data['msgId'] . '#msg' . $this->post_data['msgId']);
	}

	private function validateUser() {
		global $modSettings, $sourcedir;

		// You must have the proper permissions!
		isAllowedTo('report_any');
		// Make sure they aren't spamming.
		spamProtection('reporttm');

		// Check their session.
		checkSession('request', '', true);

		if(empty($modSettings['cr_report_board']))
			fatal_lang_error('cr_rtm_noboard');

		loadLanguage('Post');
		require_once($sourcedir . '/Subs-Post.php');
	}

	private function setUserInfo() {
		global $user_info, $txt;

		// Guests need to provide their name and email address!
		if ($user_info['is_guest']) {
			$this->poster_data['reporter_name'] = !isset($_POST['guestname']) ? '' : trim($_POST['guestname']);
			$this->poster_data['email'] = !isset($_POST['email']) ? '' : trim($_POST['email']);

			$this->validateName();
			$this->validateEmail();

			isBannedEmail($this->poster_data['email'], 'cannot_post', sprintf($txt['you_are_post_banned'], $txt['guest_title']));
			$user_info['email'] = htmlspecialchars($this->poster_data['email']);
		}
	}

	private function validateName() {
		global $smcFunc, $sourcedir;

		// Validate the name.
		if (!isset($this->poster_data['reporter_name']) || trim(strtr($this->poster_data['reporter_name'], '_', ' ')) == '') {
			$this->post_errors[] = 'no_name';
		} elseif ($smcFunc['strlen']($this->poster_data['reporter_name']) > 25) {
			$this->post_errors[] = 'long_name';
		} else {
			require_once($sourcedir . '/Subs-Members.php');
			if (isReservedName(htmlspecialchars($this->poster_data['reporter_name']), 0, true, false))
				$this->post_errors[] = 'bad_name';
		}
	}

	private function validateEmail() {
		// Validate the email.
		if ($this->poster_data['email'] === '') {
			$this->post_errors[] = 'no_email';
		} elseif (preg_match('~^[0-9A-Za-z=_+\-/][0-9A-Za-z=_\'+\-/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$~', $this->poster_data['email']) == 0) {
			$this->post_errors[] = 'bad_email';
		}
	}

	private function captchaVerification() {
		global $user_info, $modSettings, $sourcedir, $context;

		// Could they get the right verification code?
		if ($user_info['is_guest'] && !empty($modSettings['guests_report_require_captcha'])) {
			require_once($sourcedir . '/Subs-Editor.php');
			$verificationOptions = array(
				'id' => 'report',
			);
			$context['require_verification'] = create_control_verification($verificationOptions, true);
			if (is_array($context['require_verification']))
				$this->post_errors = array_merge($this->post_errors, $context['require_verification']);
		}
	}

	private function createReportContent() {
		global $txt, $modSettings, $scripturl;

		//Content for report post in the report board.
		$this->post_data['subject'] = '';
		$this->post_data['subject'] = $txt['reported_post'] . ' : ' . $this->post_data['org_subject'];

		// http://localhost/forum/smf2/index.php?action=profile;u=1
		$this->post_data['body'] = '[url='. $scripturl .  '?topic=' . $this->post_data['topicId'] . '.msg' . $this->post_data['msgId'] . '#msg' . $this->post_data['msgId'] .']' . $txt['cr_post_link'] . '[/url]<br />';

		$this->post_data['body'] .= $txt['cr_post_created_by'] . ': [url='. $scripturl .  '?action=profile;u=' . $this->post_data['org_id_poster'] .']' . $this->post_data['poster_name'] . '[/url] ' . $txt['at'] . ' ' . timeformat($this->post_data['poster_time']) . '<br /><br />';

		if (!empty($modSettings['cr_quote_reported_post'])) {
			$this->post_data['body'] .= '[quote author=' . $this->post_data['poster_name'] . ' link=topic=' . $this->post_data['topicId'] . '.msg' . $this->post_data['msgId'] . '#msg' . $this->post_data['msgId'] . ' date=' . $this->post_data['poster_time'] . ']' . "\n" . rtrim($this->post_data['org_body']) . "\n" . '[/quote]<br /><br />';
		}

		$this->post_data['body'] .= '<br />' . $txt['report_comment'] . ': ' . '<br />' . $this->post_data['comment'];
		$this->post_data['body'] .= $this->addPreviousReports();
		preparsecode($this->post_data['body']);
	}

	private function createReport() {
		global $modSettings, $user_info, $board_info;

		// set up all options
		$msgOptions = array(
			'id' => 0,
			'subject' => $this->post_data['subject'],
			'body' => $this->post_data['body'],
			'icon' => 'xx',
			'smileys_enabled' => true,
			'attachments' => array(),
			'approved' => true,
		);
		$topicOptions = array(
			'id' => $this->post_data['id_report_topic'],
			'board' => $modSettings['cr_report_board'],
			'poll' => null,
			'lock_mode' => 0,
			'sticky_mode' => null,
			'mark_as_read' => false,
			'is_approved' => true
		);
		$posterOptions = array(
			'id' => $user_info['id'],
			'name' => $this->poster_data['reporter_name'],
			'email' => $user_info['email'],
			'update_post_count' => !$user_info['is_guest'] && !empty($modSettings['cr_enable_report_count']) && $board_info['posts_count'],
		);

		// And at last make a post, yeyy :P!
		createPost($msgOptions, $topicOptions, $posterOptions);
		$this->post_data['newTopicId'] = $topicOptions['id'];
	}

	private function addPreviousReports() {
		global $txt, $scripturl;
		$prev_reported = '';
		if(!empty($this->poster_data['prev_reported'])) {
			$prev_reported = '<br /><br />'. $txt['cr_previous_reports_txt'];

			foreach ($this->poster_data['prev_reported'] as $value) {
				$prev_reported .= '<br />[url='. $scripturl .  '?topic=' . $value .']'. $txt['cr_previous_reports']. '[/url]';
			}
		}
		return $prev_reported;
	}

	private function processEmails() {
		global $modSettings, $scripturl;

		if(isset($modSettings['cr_email_moderators']) && !empty($modSettings['cr_email_moderators'])) {
			$real_mods = $this->dbInstance->getBoardModerators(array(
				'board' => $this->post_data['boardId'],
			));

			$this->replacements = array(
				'TOPICSUBJECT' => $this->post_data['subject'],
				'POSTERNAME' => $this->post_data['poster_name'],
				'REPORTERNAME' => $this->poster_data['reporter_name'],
				'TOPICLINK' => $scripturl . '?topic=' . $this->post_data['topicId'] . '.msg' . $this->post_data['msgId'] . '#msg' . $this->post_data['msgId'],
				'REPORTLINK' => !empty($this->post_data['newTopicId']) ? $scripturl . '?topic=' . $this->post_data['newTopicId'] : '',
				'COMMENT' => $this->post_data['comment'],
			);

			foreach ($real_mods as $key => $value) {
				$this->sendEmailToMod($value);
			}
		}
	}

	private function sendEmailToMod($value) {
		global $modSettings, $language, $user_info;

		// Maybe they don't want to know?!
		if (!empty($value['mod_prefs'])) {
			list(,, $pref_binary) = explode('|', $value['mod_prefs']);

		if (!($pref_binary & 1) && (!($pref_binary & 2)))
			return false;
		}

		$emaildata = loadEmailTemplate('report_to_moderator', $this->replacements, empty($value['lngfile']) || empty($modSettings['userLanguage']) ? $language : $value['lngfile']);

		// Send it to the moderator.
		sendmail($value['email_address'], $emaildata['subject'], $emaildata['body'], $user_info['email'], null, false, 2);
	}

	// Remove topic from custom report table
	public function removeTopics($topics) {
		if(isset($topics) && !empty($topics)) {
			$this->dbInstance->removeTopics($topics);
		}
	}
}

?>
