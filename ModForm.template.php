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
function template_main()
{
	global $scripturl, $txt, $context;

	echo '
<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
';

	if ($context['visual_verification'])
	{
		echo '
	function refreshImages()
	{
		// Make sure we are using a new rand code.
		var new_url = new String("', $context['verificiation_image_href'], '");
		new_url = new_url.substr(0, new_url.indexOf("rand=") + 5);

		// Quick and dirty way of converting decimal to hex
		var hexstr = "0123456789abcdef";
		for(var i=0; i < 32; i++)
			new_url = new_url + hexstr.substr(Math.floor(Math.random() * 16), 1);';

		if ($context['use_graphic_library'])
			echo '
		document.getElementById("verificiation_image").src = new_url;';
		else
			echo '
		document.getElementById("verificiation_image_1").src = new_url + ";letter=1";
		document.getElementById("verificiation_image_2").src = new_url + ";letter=2";
		document.getElementById("verificiation_image_3").src = new_url + ";letter=3";
		document.getElementById("verificiation_image_4").src = new_url + ";letter=4";
		document.getElementById("verificiation_image_5").src = new_url + ";letter=5";';
		echo '
	}';
	}

	echo '
// ]]></script>
	
		<div class="cat_bar">
			<h3 class="catbg">
				',$txt['modform_modform'],' ',$txt['modform_welcome'],' ', $context['user']['name'] , '
			</h3>
		</div>
		<form method="POST" action="', $scripturl, '?action=modform&sa=save">
		<span class="clear upperframe"><span></span></span>
			<div class="roundframe">
		  <dl class="settings">

		   <dt><b>',$txt['modform_from_name'],'</b></dt>
			<dd><input type="text" name="name" size="25" /></dd>

		   <dt><b>',$txt['modform_from_surname'],'</b></dt>
			<dd><input type="text" name="surname" size="25" /></dd> 

		   <dt><b>',$txt['modform_from_location'],'</b></dt>
			<dd><input type="text" name="location" size="25" /></dd> 

		   <dt><b>',$txt['modform_from_age'],'</b></dt>
			<dd><input type="text" name="age" size="25" /></dd> 

		   <dt><b>',$txt['modform_from_gender'],'</b></dt>
			<dd><input type="text" name="gender" size="25" /></dd>

		   <dt><b>',$txt['modform_from_email'],'</b></dt>
			<dd> <input type="text" name="email" size="25" /></dd>

		   <dt><b>',$txt['modform_from_www'],'</b></dt>
			<dd><input type="text" name="www" size="25" /></dd>

			<dt><b>',$txt['modform_from_duty'],'</b></dt>
			<dd><input type="text" name="duty" size="30" /></dd>

			<dt><b>',$txt['modform_from_department'],'</b></dt>
			<dd><input type="text" name="department" size="30" /></dd>

			<dt><b><br />',$txt['modform_from_message'],'</b>',$txt['modform_explanations'],'</dt>
			<dd> <textarea rows="6" name="message" cols="50"></textarea> </dd>

			
			<dt><b><br />',$txt['modform_mod_read'],'</b><b>'. $context['forum_name'] .'</b> ',$txt['modform_explanation'],'</dt>
			<dd><fieldset><legend><b>',$txt['modform_rules'],'</b></legend>
			',$txt['modform_rules_desc'],'</fieldset></dd>';
		  
			if ($context['visual_verification'])
			{
				echo '
								<dt>
									<b>', $txt['verification'], ':</b>
									<div class="smalltext"> ', $txt['visual_verification_description'], '</div>
								</dt>
								<dd>';
				if ($context['use_graphic_library'])
					echo '
									<img src="', $context['verificiation_image_href'], '" alt="', $txt['visual_verification_description'], '" id="verificiation_image" /><br />';
				else
					echo '
									<img src="', $context['verificiation_image_href'], ';letter=1" alt="', $txt['visual_verification_description'], '" id="verificiation_image_1" />
									<img src="', $context['verificiation_image_href'], ';letter=2" alt="', $txt['visual_verification_description'], '" id="verificiation_image_2" />
									<img src="', $context['verificiation_image_href'], ';letter=3" alt="', $txt['visual_verification_description'], '" id="verificiation_image_3" />
									<img src="', $context['verificiation_image_href'], ';letter=4" alt="', $txt['visual_verification_description'], '" id="verificiation_image_4" />
									<img src="', $context['verificiation_image_href'], ';letter=5" alt="', $txt['visual_verification_description'], '" id="verificiation_image_5" />';
				echo '
									<input type="text" name="visual_verification_code" size="30" tabindex="', $context['tabindex']++, '" />

								</dd>';
			}
			
		echo '

			<dt><input type="hidden" value="', $context['user']['id'] , '" name="userid" />
			<input type="hidden" name="nick" value="', $context['user']['name'] , '"/>
			</dt>
			<dd><input type="submit" value="',$txt['modform_submit'],'" name="submit" /></dd>
			</dl>
		 </div>
			<span class="lowerframe"><span></span></span>
		</form>
		<br />
		<div align="center">
		',$txt['modform_copyright'],'
		</div>';
}
function template_send()
{
	global $scripturl, $txt;
echo '	
	<div class="cat_bar">
		<h3 class="catbg">
			',$txt['modform_messegesend'],'
		</h3>
	</div>
	<span class="clear upperframe"><span></span></span>
	<div class="roundframe">
		',$txt['modform_application_click'],' <a href="', $scripturl, '">',$txt['modform_application_return'] ,'
	</div>
	<span class="lowerframe"><span></span></span>';
}
?>