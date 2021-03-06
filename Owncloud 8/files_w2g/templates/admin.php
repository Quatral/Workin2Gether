<?php

namespace OCA\files_w2g;

$value = "008887"; 		//Default color, like it or hate it
$font_value = "FFFFFF"; //Default font-color

$naming = "rule_username";
//-------------------------------------------- Get colors from database ------------------------------------------------------

//backcolor
$query = \OCP\DB::prepare("SELECT * FROM *PREFIX*appconfig where configkey='color' and appid='files_w2g' LIMIT 1");
$result = $query->execute()->fetchAll();
if(count($result)>=1)
		$value = $result[0]['configvalue'];

//fontcolor
$query = \OCP\DB::prepare("SELECT * FROM *PREFIX*appconfig where configkey='fontcolor' and appid='files_w2g' LIMIT 1");
$result = $query->execute()->fetchAll();
if(count($result)>=1)
		$font_value = $result[0]['configvalue'];
		

//Lockey by name rule
$query = \OCP\DB::prepare("SELECT * FROM *PREFIX*appconfig where configkey='suffix' and appid='files_w2g' LIMIT 1");
$result = $query->execute()->fetchAll();
if(count($result)>=1)
	$naming = $result[0]['configvalue'];		
		
//Locked files
$exist = \OCP\DB::prepare("SHOW TABLES LIKE '*PREFIX*".app::table."'")->execute()->fetchAll();
if($exist!=null)
	$result = \OCP\DB::prepare("SELECT * FROM *PREFIX*".app::table)->execute()->fetchAll();

	
	
//----------------------------------------------------------------------------------------------------------------------------

\OCP\Util::addscript(app::name, 'admin');
\OCP\Util::addscript(app::name, 'jscolor');

?>
<div class="section" id="multiaccess_settings">
<fieldset class="personalblock">
	<h2><?php p($l->t('Manage multiaccess')) ?></h2>
	
	<?php
	p($l->t("Here you can set the colors for the locked files."));
	echo $naming;
	echo '
	<br><br>
	<u>'.$l->t("Background color").':</u><br>
	#<input id="multicolor" class="color" type="text" value="'.$value.'" style="width:180px;" name="multicolor" original-title="'.$l->t("choose a valid html color").'"></input>
	&nbsp;<input id="submitColor" type="submit" value="'.$l->t("Save").'" name="submitColor" original-title=""></input><br><br>
	
	<u>'.$l->t("Font color").':</u><br>
	#<input id="multifontcolor" class="color" type="text" value="'.$font_value.'" style="width:180px;" name="multifontcolor" original-title="'.$l->t("choose a valid html color").'"></input>
	&nbsp;<input id="submitfontcolor" type="submit" value="'.$l->t("Save").'" name="submitfontcolor" original-title=""></input><br><br>
	';
	
	if(count($result)>0)
	{
	echo '<div id="lockfield"><u>'.$l->t("Locked files").':</u><br>	
		<select multiple="multiple" size="6" style="height:100px;" id="select_lock">';
		for($i=1;$i<=count($result);$i++)
			echo '<option value="'.$result[$i-1]['name'].'">'.rtrim(str_replace('$','/',$result[$i-1]['name']),'/').'</option>';
		echo '</select><br><input id="clearthis" type="submit" value="'.$l->t("Unlock this file").'" name=clearthis"></input>
		<input id="clearall" type="submit" value="'.$l->t("Unlock all files").'" name=clearall"></input></div>
		';
	}
	else
		echo $l->t("There are no locked files at the moment");
	
	echo '<br><u>'.
	$l->t("'Locked by' suffix").':</u><br><br>
	<div>
		<p><input id="rule_username" type="radio" name="suffix" ';if($naming=="rule_username") echo 'checked="checked"'; echo '><label for="rule_username">'.$l->t("username").'</label><br><em>'.$l->t("Shows the real username i.e. admin, or LDAP UUID").'</em></input></p>
		<p><input id="rule_displayname" type="radio" name="suffix" ';if($naming=="rule_displayname") echo 'checked="checked"'; echo '><label for="rule_username">'.$l->t("display name").'</label><br><em>'.$l->t("Shows the full displayed name, i.e. John Doe").'</em></input></p>
	</div>
	';
	
	echo '<br>
	<p id="suffixupdated" style="background-color:#27AE60;display:inline-block;opacity:0;color:#eee;padding:5px;border-radius:5px;">
	'.$l->t("Suffix change has been saved").'
	</p>
	';
	
	echo '<br><p style="font-size:10px">('.$l->t("Old file locks won't be affected by this").')</p>';
	
	?>
</fieldset></div>