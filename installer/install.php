<?php

/**
* @package manifest file for Custom Report Mod
* @version 2.0.3
* @author Joker (http://www.simplemachines.org/community/index.php?action=profile;u=226111), original author
* @author Francisco "d3vcho" Domínguez (https://www.simplemachines.org/community/index.php?action=profile;u=422971)
* @copyright Copyright (c) 2018, Francisco Domínguez
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

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

global $smcFunc, $sourcedir, $modSettings;

if (!array_key_exists('db_add_column', $smcFunc))
	db_extend('packages');

$tables = array(
	'custom_report_mod' => array(
		'columns' => array(
			array(
				'name' => 'id_report_topic',
				'type' => 'int',
				'size' => 10,
				'unsigned' => true,
				'null' => false,
				'default' => '0',
			),
			array(
				'name' => 'id_msg',
				'type' => 'int',
				'size' => 10,
				'unsigned' => true,
				'null' => false,
				'default' => '0',
			),
			array(
				'name' => 'id_topic',
				'type' => 'int',
				'size' => 10,
				'unsigned' => true,
				'null' => false,
				'default' => '0',
				
			),
			array(
				'name' => 'solved',
				'type' => 'tinyint',
				'size' => 3,
				'unsigned' => true,
				'null' => false,
				'default' => '0',
			),
		),
		'indexes' => array(
			array(
				'type' => 'primary',
				'columns' => array('id_report_topic'),
			),
			array(
				'type' => 'key',
				'columns' => array('id_msg'),
			),
			array(
				'type' => 'key',
				'columns' => array('id_topic'),
			),
		),
	),
);

foreach ($tables as $table => $data)
	$smcFunc['db_create_table']('{db_prefix}' . $table, $data['columns'], $data['indexes']);

if (SMF == 'SSI')
	echo 'Database adaptation successful!';

?>
