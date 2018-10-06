<?php

/**
 * @package manifest file for Custom Report Mod
 * @author Joker (http://www.simplemachines.org/community/index.php?action=profile;u=226111), original author
 * @author Francisco "d3vcho" Dom?nguez (https://www.simplemachines.org/community/index.php?action=profile;u=422971)
 * @copyright Copyright (c) 2018, Francisco Dom?nguez
 * @version 2.0.3
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
$txt['cr_post_created_by'] = 'Создана';

$txt['cr_post_reported_by'] = 'Жалоба от';
$txt['cr_post_link']        = 'Ссылка на сообщение';
$txt['cr_solve_report']     = 'Отметить рассмотренным';
$txt['cr_unsolve_report']   = 'Отметить нерассмотренным';
$txt['cr_report_solved']    = 'Отмечено рассмотренным';

$txt['cr_rtm_noboard'] = 'Не указан раздел для жалоб.';

// Permission Strings
$txt['cr_permission_settings']      = 'Права доступа';
$txt['cr_permission_settings_desc'] = 'Вы можете изменить права доступа относящиеся к моду жалоб.';
$txt['cr_perm_cr_can_solve_report'] = 'Группы, которые могут отмечать рассмотренными темы по жалобам';

$txt['cr_admin_panel_title']     = 'Жалобы на сообщения';
$txt['cr_admin_panel']           = 'Настройки мода жалоб';
$txt['cr_general_settings']      = 'Настройки';
$txt['cr_general_settings_desc'] = 'Здесь вы можете изменить настройки мода жалоб';

// General settings text keys
$txt['cr_enable_mod']                     = 'Включить ';
$txt['cr_report_board']                   = 'Выберите раздел для отправки жалоб';
$txt['cr_quote_reported_post']            = 'Цитировать содержимое сообщения на которое пожаловались';
$txt['cr_quote_reported_post_info']       = 'Если отключить, в жалобе будет только ссылка на сообщение';
$txt['cr_enable_report_count']            = 'Подсчитывать сообщения пользователей';
$txt['cr_enable_report_count_info']       = 'Увеличивать счетчик сообщений пользователей, когда они отправляют жалобы';
$txt['cr_enable_report_mod_count']        = 'Подсчитывать сообщения модераторов';
$txt['cr_enable_report_mod_count_info']   = 'Увеличивать счетчик сообщений модераторов, когда они отмечают жалобы рассмотренными';
$txt['cr_enable_large_report_field']      = 'Использовать поле ввода увеличенного размера при отправке жалоб';
$txt['cr_enable_large_report_field_info'] = 'Вместо одной строки будет отображаться большое поле для ввода текста';
$txt['cr_email_moderators']               = 'Отправлять e-mail модераторам';
$txt['cr_email_moderators_info']          = 'Будут отправляться e-mail модераторам раздела при создании жалоб';

$txt['cr_previous_reports']     = 'Жалоба';
$txt['cr_previous_reports_txt'] = 'Предыдущие жалобы на это сообщение';
$txt['cr_submit']               = 'Отправить';

?>
