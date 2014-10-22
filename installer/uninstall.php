<?php

/*
	Package manifest file for Custom Report Mod

	Author - Joker (http://www.simplemachines.org/community/index.php?action=profile;u=226111)
	License - http://creativecommons.org/licenses/by-sa/3.0/ CC BY-SA 3.0
	
	Version - 1.4 RC2
*/


// If SSI.php is in the same place as this file, and SMF isn't defined...
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');

// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot uninstall - please verify you put this in the same place as SMF\'s index.php.');

remove_integration_function('integrate_pre_include', '$sourcedir/CustomReport.php');
remove_integration_function('integrate_actions', 'SolveAction');
remove_integration_function('integrate_load_permissions', 'CustomReportPermissions');
remove_integration_function('integrate_buffer', 'custom_report_ob');

?>
