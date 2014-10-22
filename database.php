<?php
/*
	Package manifest file for Custom Report Mod

	Author - Joker (http://www.simplemachines.org/community/index.php?action=profile;u=226111)
	License - http://creativecommons.org/licenses/by-sa/3.0/ CC BY-SA 3.0
	
	Version - 1.3
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