<?php

/**
* @package manifest file for Custom Report Mod
* @version 2.0
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

global $txt;

// General mod strings
$txt['cr_post_made_by'] = 'Post made by';

$txt['cr_post_report_board'] = 'Reported by';
$txt['cr_post_link'] = 'Post Link';
$txt['cr_report_solved'] = 'Mark Solved';
$txt['cr_report_unsolved'] = 'Mark Unsolved';

$txt['cr_rtm_noboard'] = 'Admin forgot to create the report board.';

// Permission Strings
$txt['cr_permissionname_can_solve_report'] = 'Can mark reports as solved';
$txt['cr_permissionhelp_can_solve_report'] = 'This will allow user to mark the reports as solved.';

$txt['cr_admin_panel_title'] = 'Custom report';
$txt['cr_admin_panel'] = 'Custom report mod admin panel';
$txt['cr_general_settings'] = 'General Settings';
$txt['cr_general_settings_desc'] = 'You can make all global settings for custom reports mod from here.';

// General settings text keys
$txt['cr_enable_mod'] = 'Enable custom report mod.';
$txt['cr_report_board'] = 'Board id to create post report.';
$txt['cr_quote_reported_post'] = 'Quote the content of reported post';
$txt['cr_quote_reported_post_info'] = 'Disabling this option will just post a link to reported post.';
$txt['cr_enable_report_count'] = 'Count Posts of Report Board';
$txt['cr_enable_report_count_info'] = 'Raises the post count of users when they report a post.';
$txt['cr_enable_report_mod_count'] = 'Count the post when moderator solves a report';
$txt['cr_enable_report_mod_count_info'] = 'Raises the post count of moderators when they solve a report.';
$txt['cr_enable_large_report_field'] = 'Use bigger input field for writing reports';
$txt['cr_enable_large_report_field_info'] = 'Instead of single line, it gives you are large text area while reporting posts';
$txt['cr_email_moderators'] = 'Send email to moderators';
$txt['cr_email_moderators_info'] = 'Send email to board moderators when a post is reported';

$txt['cr_permission_settings'] = 'Permission Settings';
$txt['cr_permission_settings_desc'] = 'You can make all group based permission settings for custom reports mod from here.';
$txt['cr_perm_cr_can_solve_report'] = 'Groups can mark reported topic solved';

$txt['cr_submit'] = 'Submit';

?>
