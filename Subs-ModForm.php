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
if (!defined('SMF'))
	die('Hacking attempt...');
function ModFormhook(&$actionArray)
{
	global $modSettings;
	if(!empty($modSettings['modformetkin']))
	$actionArray['modform'] = array('ModForm.php', 'ModForm');
}
function ModForm_admin_areas(&$admin_areas)
{
	global $txt;
	loadLanguage('ModForm');
	$admin_areas['config']['areas']['modsettings']['subsections']['ModForm'] = array($txt['modform_modform']);
}
function ModForm_Buttons(&$menu_buttons)
{
		global $txt,$scripturl,$modSettings;
	if(!empty($modSettings['modformetkin'])){
	  	loadLanguage('ModForm');
	$fnd = 0;
	reset($menu_buttons);
	while((list($key, $val) = each($menu_buttons)) && $key != 'pm')
		$fnd++;
	$fnd++;
	$menu_buttons = array_merge(
		array_slice($menu_buttons, 0, $fnd),
		array(
        'modform' => array(
                'title' => $txt['modform_modname'],
                'href' => $scripturl . '?action=modform',
                'show' => AllowedTo('view_modform'),
				'icon' => '',
				'sub_buttons' => array(
                   
                ),
          ),
		),
		array_slice($menu_buttons, $fnd, count($menu_buttons) - $fnd)
	);
	}
}
function ModForm_Settings(&$sub_actions)
{
	$sub_actions['ModForm'] = 'ModFormSettings';
}
function ModFormSettings($return_config = false)
	{
		global $txt, $scripturl,$smcFunc,$sourcedir, $context;
		require_once($sourcedir . '/ManageServer.php');
		isAllowedTo('manage_permissions');	
		$boards = array();
		$request = $smcFunc['db_query']('order_by_board_order', '
			SELECT b.id_board, b.name AS board_name, c.name AS cat_name
			FROM {db_prefix}boards AS b
				LEFT JOIN {db_prefix}categories AS c ON (c.id_cat = b.id_cat)',
			array(
			)
		);
		while ($row = $smcFunc['db_fetch_assoc']($request))
			$boards[$row['id_board']] = $row['cat_name'] . ' - ' . $row['board_name'];
		$smcFunc['db_free_result']($request);
		$config_vars = array(
			  	array('check', 'modformetkin'),
				'',
				array('check', 'modformmail'),
				'',
				array('check', 'modformboard'),
				array('select', 'modform_boardid', $boards,),
				'',
				array('check', 'modformpm'),
				array('int', 'modform_pmid'),
				'',
				array('title', 'view_modform'),
				array('permissions', 'view_modform', 'subtext' => $txt['permissionhelp_view_modform']),
				
		);
	if ($return_config)
			return $config_vars;

		$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=ModForm';
		$context['settings_title'] = $txt['modform_modform'];

		// Saving?
		if (isset($_GET['save']))
		{
			saveDBSettings($config_vars);
			redirectexit('action=admin;area=modsettings;sa=ModForm');
		}

		prepareDBSettingContext($config_vars);
	}
?>