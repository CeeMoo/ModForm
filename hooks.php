<?php
/**
 * Konusal ModForm Modu
 * Version: 1.0
 * Official support: http://smf.konusal.com
 * Author: ^SNRJ^
 * 2016
 * 
 * version smf 2.0*
 */
// If SSI.php is in the same place as this file, and SMF isn't defined, this is being run standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');
$hooks = array(
	'integrate_pre_include' => '$sourcedir/Subs-ModForm.php',
	'integrate_admin_areas' => 'ModForm_admin_areas',
	'integrate_actions'=>'ModFormhook',
	'integrate_modify_modifications' => 'ModForm_Settings',
	'integrate_menu_buttons' => 'ModForm_Buttons',
);
// Adding or removing them?
if (!empty($context['uninstalling']))
	$call = 'remove_integration_function';
else
	$call = 'add_integration_function';
// Do the deed
foreach ($hooks as $hook => $function)
	$call($hook, $function);


// If we're using SSI, tell them we're done
if (SMF == 'SSI')
	echo 'Database changes are complete!';

?>


