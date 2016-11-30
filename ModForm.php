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

function ModForm()
{
		global $context, $mbname, $txt, $modSettings, $scripturl;

		loadtemplate('ModForm');
		loadLanguage('ModForm');
		isAllowedTo('view_modform');
		$context['sub_template']  = 'main';
		$context['page_title'] = $mbname . ' - ' . $txt['modform_modname'];
		if (isset($_GET['sa']))
	{

		if ($_GET['sa'] == 'save')
		{
			
			if ((empty($modSettings['disable_visual_verification']) || $modSettings['disable_visual_verification'] != 1) && (empty($_REQUEST['visual_verification_code']) || strtoupper($_REQUEST['visual_verification_code']) !== $_SESSION['visual_verification_code']))
			{
				$_SESSION['visual_errors'] = isset($_SESSION['visual_errors']) ? $_SESSION['visual_errors'] + 1 : 1;
				if ($_SESSION['visual_errors'] > 3 && isset($_SESSION['visual_verification_code']))
					unset($_SESSION['visual_verification_code']);
		
				fatal_lang_error('visual_verification_failed', false);
			}
			elseif (isset($_SESSION['visual_errors']))
			unset($_SESSION['visual_errors']);
			
			$userid = $_POST['userid'];
			if ($userid == '')
				fatal_error($txt['modform_error_nick'], false);
			$name = $_POST['name'];
			if ($name == '')
				fatal_error($txt['modform_error_name'], false);
			$nick = $_POST['nick'];
			if ($nick == '')
				fatal_error($txt['modform_error_nick'], false);
			$message = $_POST['message'];
			if ($message == '')
				fatal_error($txt['modform_error_message'], false);
			$email = $_POST['email'];
			if ($email == '')
				fatal_error($txt['modform_error_email'], false);
			$surname = $_POST['surname'];
			if ($surname == '')
				fatal_error($txt['modform_error_surname'], false);
			$age = $_POST['age'];
			if ($age == '')
				fatal_error($txt['modform_error_age'], false);
			$gender = $_POST['gender'];
			if ($gender == '')
				fatal_error($txt['modform_error_gender'], false);
			$department = $_POST['department'];
			if ($department == '')
				fatal_error($txt['modform_error_department'], false);
			$location = $_POST['location'];
			if ($location == '')
				fatal_error($txt['modform_error_location'], false);
			$duty = $_POST['duty'];
			if ($duty == '')
				fatal_error($txt['modform_error_duty'], false);
			$www = $_POST['www'];
			if ($www == '')
				fatal_error($txt['modform_error_www'], false);


			$message = htmlspecialchars($message, ENT_QUOTES);
			$name = htmlspecialchars($name, ENT_QUOTES);
			$email = htmlspecialchars($email, ENT_QUOTES);
			$surname = htmlspecialchars($surname, ENT_QUOTES);
			$nick = htmlspecialchars($nick, ENT_QUOTES);
			$www = htmlspecialchars($www, ENT_QUOTES);
			$duty = htmlspecialchars($duty, ENT_QUOTES);
			$department = htmlspecialchars($department, ENT_QUOTES);
			$location = htmlspecialchars($location, ENT_QUOTES);
			$gender = htmlspecialchars($gender, ENT_QUOTES);
			$age = htmlspecialchars($age, ENT_QUOTES);
			
			$m = $txt['modform_form'] . $mbname . " \n";
			$m .= $txt['modform_nick'] .$nick . "\n";
			$m .= $txt['modform_name'] . $name . "\n";
			$m .= $txt['modform_surname'] . $surname . "\n";
			$m .= $txt['modform_email'] . $email . "\n";
			$m .= $txt['modform_gender'] . $gender . "\n";
			$m .= $txt['modform_age'] . $age . "\n";
			$m .= $txt['modform_location'] . $location . "\n";
			$m .= $txt['modform_department'] . $department . "\n";
			$m .= $txt['modform_duty'] . $duty . "\n";
			$m .= $txt['modform_www'] . $www . "\n";
			$m .= $txt['modform_message'];
			$m .= $message;
			$m .= "\n";
			
			if(!empty($modSettings['modformmail']))
			{modform_mail($m,$email);}
			if(!empty($modSettings['modformboard']))
			{modform_board($m,$userid,$nick);}
			if(!empty($modSettings['modformpm']))
			{modform_pm($m,$userid,$nick);}
		}
	}
	else
	{
		$context['sub_template']  = 'main';
		$context['page_title'] = $mbname . ' - ' . $txt['modform_modname'];
		$context['visual_verification'] = empty($modSettings['disable_visual_verification']) || $modSettings['disable_visual_verification'] != 1;
		if ($context['visual_verification'])
		{
			$context['use_graphic_library'] = in_array('gd', get_loaded_extensions());
			$context['verificiation_image_href'] = $scripturl . '?action=verificationcode;rand=' . md5(rand());
			if (!isset($_SESSION['visual_verification_code']))
			{
				$character_range = array_merge(range('A', 'H'), array('K', 'M', 'N', 'P'), range('R', 'Z'));
				$_SESSION['visual_verification_code'] = '';
				for ($i = 0; $i < 5; $i++)
					$_SESSION['visual_verification_code'] .= $character_range[array_rand($character_range)];
			}
		}
	}
			
			
		
}
function modform_mail($m,$email)
{
		global $context, $mbname, $webmaster_email, $txt, $sourcedir;
		isAllowedTo('view_modform');
		require_once($sourcedir . '/Subs-Post.php');
		sendmail($webmaster_email, $mbname, $m,$email);
		loadtemplate('ModForm');
		loadLanguage('ModForm');
		$context['sub_template']  = 'send';
		$context['page_title'] = $mbname . $txt['modform_messagesend'];

}

function modform_board($m,$userid,$nick)
{
	global $context, $mbname,$smcFunc, $sourcedir,$txt, $modSettings;
	isAllowedTo('view_modform');
	require_once($sourcedir . '/Subs-Post.php');
		$result = $smcFunc['db_query']('',"
		SELECT 
			subject, body 
		FROM {db_prefix}messages 
		 ORDER BY RAND() LIMIT 1");
		if ($smcFunc['db_num_rows']($result) != 0)
		{
			$row2 =  $smcFunc['db_fetch_assoc']($result);


						$msgOptions = array(
									'id' => 0,
									'subject' => $txt['modform_yeni'],
									'body' => $m,
									'icon' => 'xx',
									'smileys_enabled' => 1,
									'attachments' => array(),
								);
								$topicOptions = array(
									'id' => 0,
									'board' => $modSettings['modform_boardid'],
									'poll' => null,
									'lock_mode' => null,
									'sticky_mode' => null,
									'mark_as_read' => false,
								);
								$posterOptions = array(
									'id' => $userid,
									'name' => $nick,
									'email' => '',
									'update_post_count' => (($userid == 0) ? 0 : 1),
								);

			createPost($msgOptions, $topicOptions, $posterOptions);
		}

		$smcFunc['db_free_result']($result);
		loadtemplate('ModForm');
		loadLanguage('ModForm');
		$context['sub_template']  = 'send';
		$context['page_title'] = $mbname . $txt['modform_messagesend'];

}

function modform_pm($m,$userid,$nick)
{
	global $txt,$context,$mbname,$modSettings,$sourcedir;
	isAllowedTo('view_modform');
	require_once($sourcedir . '/Subs-Members.php');
	$pm_register_body =  $m;

    $pmfrom = array(
      'id' => $userid,
      'name' => $nick,
      'username' => $nick,
    );
    $pmto = array(
      'to' => array($modSettings['modform_pmid']),
      'bcc' => array()
    );
    sendpm($pmto,$txt['modform_yeni'],$pm_register_body, 0, $pmfrom);
    loadtemplate('ModForm');
	loadLanguage('ModForm');
	$context['sub_template']  = 'send';
	$context['page_title'] = $mbname . $txt['modform_messagesend'];
}


?>