<?php

	if($_POST[amazonok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'no' WHERE `id` = '1'");
	if($_POST[amazonnok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'yes' WHERE `id` = '1'");

	if(!current_user_can('level_10')) {

		echo '<div id="message" class="updated fade"><p>'.__("<strong>Note</strong><br />You need administrator rights to use myTreasures!",$myTreasuresTextdomain).'</p></div>';
		return;

	} elseif($myTreasures_options[option25] != 'doneit') {

		include("mytreasuresinstall.php");

	} elseif($myTreasures_options[changelog] != $myTreasuresPluginVersion) {

		include("mytreasureschangelog.php");

	} elseif($myTreasures_options[option20] != 'no' && $myTreasures_options[option20] != 'yes') {

		echo "<div class=\"wrap\"><h2>myTreasures</h2><p>".__("Dear user,<br /><br />the development of myTreasures takes up a lot of time and I offer it to you free of charge. But of course the webserver and the traffic have to paid for. If you allow this installation to post an Amazon Partner link (just a plain text link saying \"Amazon.de\" that will only be displayed in the Detail view) it would be a reward for my work. If anyone buys anything using that link I get credited 5%.<br /><br />There are no costs for you! If you'd like to contribute in another way, please have a look at the Info page.<br /><br />Would you like to activate the Amazon link and support the development of myTreasures?",$myTreasuresTextdomain)."</p><div class=\"submit\"><form action=\"\" method=\"post\" style=\"display: inline;\"><input type=\"submit\" name=\"amazonok\" value=\" ".__("Yes, please activate",$myTreasuresTextdomain)." \">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"amazonnok\" value=\" ".__("No thanks, I don't want the Amazon link",$myTreasuresTextdomain)." \"></form></div></div>";

	} else {

		$path = "../wp-content/mytreasures/";

		if(!is_writeable($path)) {

			echo '<div id="message" class="updated fade"><p><strong>'.__("Please create a folder \"mytreasures\" in \"/wp-content/\" with writings rights!",$myTreasuresTextdomain).'</strong></p></div>';

		} else {

			if(strlen($_POST[name]) > 0) {

				$check_doubletitle = mysql_num_rows(mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `name` = '".$_POST[name]."'"));

			}

			if(strlen($_POST[short]) > 0 && preg_match("/^([a-zA-Z0-9]+)$/",$_POST[short])) {

				$check_doubleshort = mysql_num_rows(mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `short` = '".strtolower($_POST[short])."'"));

			}

			if(($check_doubletitle == "0" || $check_doubletitle != "0" && $_POST[override]) && $check_doubleshort == "0" && strlen($_POST[name]) > 0 && strlen($_POST[field01]) > 0 && preg_match("/^([a-zA-Z0-9]+)$/",$_POST[short])) { 

				if($_POST[feature_sort1]) { $feature_sort1 = $_POST[feature_sort1]; }
				if($_POST[feature_sort2]) { $feature_sort2 = $_POST[feature_sort2]; }
				if($_POST[feature_sort3]) { $feature_sort3 = $_POST[feature_sort3]; }
				if($_POST[feature_sort4]) { $feature_sort4 = $_POST[feature_sort4]; }
				if($_POST[feature_sort5]) { $feature_sort5 = $_POST[feature_sort5]; }
				if($_POST[feature_tracklist] != '1') { $_POST[feature_tracklist] = "0"; }
				mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` (`name`, `short`, `view`, `field01`, `field02`, `field03`, `field04`, `field05`, `field06`, `field07`, `field08`, `field09`, `field10`, `field11`, `field12`, `field13`, `field14`, `field15`, `field16`, `field17`, `field18`, `field19`, `field20`, `feature_sort1`, `feature_sort2`, `feature_sort3`, `feature_sort4`, `feature_sort5`, `feature_tracklist`) VALUES ('".$_POST[name]."', '".strtolower($_POST[short])."', '".$_POST[view]."', '".$_POST[field01]."', '".$_POST[field02]."', '".$_POST[field03]."', '".$_POST[field04]."', '".$_POST[field05]."', '".$_POST[field06]."', '".$_POST[field07]."', '".$_POST[field08]."', '".$_POST[field09]."', '".$_POST[field10]."', '".$_POST[field11]."', '".$_POST[field12]."', '".$_POST[field13]."', '".$_POST[field14]."', '".$_POST[field15]."', '".$_POST[field16]."', '".$_POST[field17]."', '".$_POST[field18]."', '".$_POST[field19]."', '".$_POST[field20]."', '$feature_sort1', '$feature_sort2', '$feature_sort3', '$feature_sort4', '$feature_sort5', '$_POST[feature_tracklist]')");
				echo '<div id="message" class="updated fade"><p><strong>'.sprintf(__("Media type <i>%s</i> was created successfully!",$myTreasuresTextdomain),$_POST[name]).'</strong></p></div>';
				$_POST = false;

			}

			if($_GET[action] == 'edit') {

			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '$_GET[id]'");
			$result01 = mysql_fetch_array($query01);
			
			if($result01[id]) {

?>

<div class="wrap">
<h2><?php echo __("Edit media type",$myTreasuresTextdomain); ?></h2>

<?php

				if($_POST[changefields] && strlen($_POST[change_feature1]) > 0 && strlen($_POST[change_feature2]) > 0) { 

					$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `type` = '$result01[id]'");
					while($result02 = mysql_fetch_array($query02)) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET `$_POST[change_feature1]` = '".$result02[$_POST[change_feature2]]."', `$_POST[change_feature2]` = '".$result02[$_POST[change_feature1]]."' WHERE `id` = '$result02[id]'"); }
					mysql_query("UPDATE `".$wpdb->prefix."mytreasures_type` SET `$_POST[change_feature1]` = '".$result01[$_POST[change_feature2]]."', `$_POST[change_feature2]` = '".$result01[$_POST[change_feature1]]."' WHERE `id` = '$result01[id]'");
					echo '<div id="message" class="updated fade"><p><strong>'.sprintf(__("You've switched <i>%s</i> and <i>%s</i> successfully!",$myTreasuresTextdomain),$result01[$_POST[change_feature1]],$result01[$_POST[change_feature2]]).'</strong></p></div>';
					$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '$_GET[id]'");
					$result01 = mysql_fetch_array($query01);

				}
				
				if(strlen($_POST[name]) > 0 && strlen($_POST[field01]) > 0 && strlen($_POST[edit]) > 0) { 

					if($_POST[feature_sort1]) { $feature_sort1 = $_POST[feature_sort1]; }
					if($_POST[feature_sort2]) { $feature_sort2 = $_POST[feature_sort2]; }
					if($_POST[feature_sort3]) { $feature_sort3 = $_POST[feature_sort3]; }
					if($_POST[feature_sort4]) { $feature_sort4 = $_POST[feature_sort4]; }
					if($_POST[feature_sort5]) { $feature_sort5 = $_POST[feature_sort5]; }
					if($_POST[feature_tracklist] != '1') { $_POST[feature_tracklist] = "0"; }
					mysql_query("UPDATE `".$wpdb->prefix."mytreasures_type` SET `view` = '$_POST[view]', `feature_tracklist` = '$_POST[feature_tracklist]', `feature_sort1` = '$feature_sort1', `feature_sort2` = '$feature_sort2', `feature_sort3` = '$feature_sort3', `feature_sort4` = '$feature_sort4', `feature_sort5` = '$feature_sort5', `name` = '".$_POST[name]."', `field01` = '".$_POST[field01]."', `field02` = '".$_POST[field02]."', `field03` = '".$_POST[field03]."', `field04` = '".$_POST[field04]."', `field05` = '".$_POST[field05]."', `field06` = '".$_POST[field06]."', `field07` = '".$_POST[field07]."', `field08` = '".$_POST[field08]."', `field09` = '".$_POST[field09]."', `field10` = '".$_POST[field10]."', `field11` = '".$_POST[field11]."', `field12` = '".$_POST[field12]."', `field13` = '".$_POST[field13]."', `field14` = '".$_POST[field14]."', `field15` = '".$_POST[field15]."', `field16` = '".$_POST[field16]."', `field17` = '".$_POST[field17]."', `field18` = '".$_POST[field18]."', `field19` = '".$_POST[field19]."', `field20` = '".$_POST[field20]."' WHERE `id` = '$result01[id]'");
					echo '<div id="message" class="updated fade"><p><strong>'.sprintf(__("The media type <i>%s</i> was edited successfully!",$myTreasuresTextdomain),stripslashes($_POST[name])).'</strong></p></div>';

				}  else {

					if(!$_POST[field01]) { $_POST[field01] = $result01[field01]; }
					if(!$_POST[field02]) { $_POST[field02] = $result01[field02]; }
					if(!$_POST[field03]) { $_POST[field03] = $result01[field03]; }
					if(!$_POST[field04]) { $_POST[field04] = $result01[field04]; }
					if(!$_POST[field05]) { $_POST[field05] = $result01[field05]; }
					if(!$_POST[field06]) { $_POST[field06] = $result01[field06]; }
					if(!$_POST[field07]) { $_POST[field07] = $result01[field07]; }
					if(!$_POST[field08]) { $_POST[field08] = $result01[field08]; }
					if(!$_POST[field09]) { $_POST[field09] = $result01[field09]; }
					if(!$_POST[field10]) { $_POST[field10] = $result01[field10]; }
					if(!$_POST[field11]) { $_POST[field11] = $result01[field11]; }
					if(!$_POST[field12]) { $_POST[field12] = $result01[field12]; }
					if(!$_POST[field13]) { $_POST[field13] = $result01[field13]; }
					if(!$_POST[field14]) { $_POST[field14] = $result01[field14]; }
					if(!$_POST[field15]) { $_POST[field15] = $result01[field15]; }
					if(!$_POST[field16]) { $_POST[field16] = $result01[field16]; }
					if(!$_POST[field17]) { $_POST[field17] = $result01[field17]; }
					if(!$_POST[field18]) { $_POST[field18] = $result01[field18]; }
					if(!$_POST[field19]) { $_POST[field19] = $result01[field19]; }
					if(!$_POST[field20]) { $_POST[field20] = $result01[field20]; }
					if(!$_POST[feature_sort1]) { $_POST[feature_sort1] = $result01[feature_sort1]; }
					if(!$_POST[feature_sort2]) { $_POST[feature_sort2] = $result01[feature_sort2]; }
					if(!$_POST[feature_sort3]) { $_POST[feature_sort3] = $result01[feature_sort3]; }
					if(!$_POST[feature_sort4]) { $_POST[feature_sort4] = $result01[feature_sort4]; }
					if(!$_POST[feature_sort5]) { $_POST[feature_sort5] = $result01[feature_sort5]; }
					if(!$_POST[feature_tracklist]) { $_POST[feature_tracklist] = $result01[feature_tracklist]; }
					if(!$_POST[name]) { $_POST[name] = ($result01[name]); }
					if(!$_POST[view]) { $_POST[view] = ($result01[view]); }

?>

<form action="" method="post" enctype="multipart/form-data">
<p><?php if($_POST[doit] && !strlen($_POST[name]) > 0) { echo "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />"; } ?>Name<font style="color: #FF0000;">*</font>
<br /><textarea style="height: 16px; width: 90%;" name="name"><?php echo stripslashes($_POST[name]); ?></textarea>
<br />
<br /><?php echo __("<b>Information</b><br />Following fields are for your own details of this media type",$myTreasuresTextdomain); ?>

<?php

	if(!$_POST[field01]) { $_POST[field01] = "Name / Titel"; }
	$selectinput1 = "<option value=\"\">".__("No view for this type",$myTreasuresTextdomain)."</option>";
	$selectinput2 = "<option value=\"\">".__("No view for this type",$myTreasuresTextdomain)."</option>";
	$selectinput3 = "<option value=\"\">".__("No view for this type",$myTreasuresTextdomain)."</option>";
	$selectinput4 = "<option value=\"\">".__("No view for this type",$myTreasuresTextdomain)."</option>";
	$selectinput5 = "<option value=\"\">".__("No view for this type",$myTreasuresTextdomain)."</option>";
	$selectinput6 = "<option value=\"\">".__("Please select fields you want to switch",$myTreasuresTextdomain)."</option>";

	for($i = 1; $i <= 20; $i++) {

		$checked1 = false;
		$checked2 = false;
		$checked3 = false;
		$checked4 = false;
		$checked5 = false;
		if($i < 10) { $field1 = __("detail",$myTreasuresTextdomain)." #0".$i; } else { $field1 = __("detail",$myTreasuresTextdomain)." #".$i; }
		if($i == 1) { $field1 .= "<font style=\"color: #FF0000;\">*</font>"; }
		if($i < 10) { $field = "field0".$i; } else { $field = "field".$i; }
		if($i > 1) { 
			if($_POST[feature_sort1] == $field) { $checked1 = "selected"; }
			if($_POST[feature_sort2] == $field) { $checked2 = "selected"; }
			if($_POST[feature_sort3] == $field) { $checked3 = "selected"; }
			if($_POST[feature_sort4] == $field) { $checked4 = "selected"; }
			if($_POST[feature_sort5] == $field) { $checked5 = "selected"; }
			$selectinput1 .= "<option value=\"".$field."\" ".$checked1.">".sprintf(__("Content of %s (Must have content!)",$myTreasuresTextdomain),$field1)."</option>";
			$selectinput2 .= "<option value=\"".$field."\" ".$checked2.">".sprintf(__("Content of %s (Must have content!)",$myTreasuresTextdomain),$field1)."</option>";
			$selectinput3 .= "<option value=\"".$field."\" ".$checked3.">".sprintf(__("Content of %s (Must have content!)",$myTreasuresTextdomain),$field1)."</option>";
			$selectinput4 .= "<option value=\"".$field."\" ".$checked4.">".sprintf(__("Content of %s (Must have content!)",$myTreasuresTextdomain),$field1)."</option>";
			$selectinput5 .= "<option value=\"".$field."\" ".$checked5.">".sprintf(__("Content of %s (Must have content!)",$myTreasuresTextdomain),$field1)."</option>";
			$selectinput6 .= "<option value=\"".$field."\">".sprintf(__("Content of %s",$myTreasuresTextdomain),$field1)."</option>";
		} 
			echo "<br /><br />".$field1."<br /><textarea style=\"height: 16px; width: 90%;\" name=\"".$field."\">".stripslashes($_POST[$field])."</textarea>";

	}

?>

<br />
<br /><b><?php echo __("Views for this media type",$myTreasuresTextdomain); ?></b>
<br /><?php echo __("You can create own \"views\" for ech media type. Just select it here",$myTreasuresTextdomain); ?>
<br />
<br /><?php echo __("View",$myTreasuresTextdomain); ?> #1
<br /><select name="feature_sort1" style="width: 380px;"><?php echo $selectinput1; ?></select>
<br />
<br /><?php echo __("View",$myTreasuresTextdomain); ?> #2
<br /><select name="feature_sort2" style="width: 380px;"><?php echo $selectinput2; ?></select>
<br />
<br /><?php echo __("View",$myTreasuresTextdomain); ?> #3
<br /><select name="feature_sort3" style="width: 380px;"><?php echo $selectinput3; ?></select>
<br />
<br /><?php echo __("View",$myTreasuresTextdomain); ?> #4
<br /><select name="feature_sort4" style="width: 380px;"><?php echo $selectinput4; ?></select>
<br />
<br /><?php echo __("View",$myTreasuresTextdomain); ?> #5
<br /><select name="feature_sort5" style="width: 380px;"><?php echo $selectinput5; ?></select>
<br />
<br /><b><?php echo __("Tracklist",$myTreasuresTextdomain); ?></b>
<br /><input type="checkbox" name="feature_tracklist" value="1" <?php if($_POST[feature_tracklist]) { echo "checked=\"checked\""; } ?>> <?php echo __("This media type has a tracklist",$myTreasuresTextdomain); ?>
<br />
<br /><b><?php echo __("Default view",$myTreasuresTextdomain); ?></b>
<br /><?php echo __("If you want to have a default view for this media type, just choose it:",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="0" <?php if($_POST[view] == '0' || $_POST[view] == '') { echo "checked=\"checked\""; } ?>> <?php echo __("Use global setttings",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="list" <?php if($_POST[view] == 'list') { echo "checked=\"checked\""; } ?>> <?php echo __("Name",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="rating" <?php if($_POST[view] == 'rating') { echo "checked=\"checked\""; } ?>> <?php echo __("Ratings",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="sort1" <?php if($_POST[view] == 'sort1') { echo "checked=\"checked\""; } ?>> <?php echo __("Media type view definition #1 (If available!)",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="sort2" <?php if($_POST[view] == 'sort2') { echo "checked=\"checked\""; } ?>> <?php echo __("Media type view definition #2 (If available!)",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="sort3" <?php if($_POST[view] == 'sort3') { echo "checked=\"checked\""; } ?>> <?php echo __("Media type view definition #3 (If available!)",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="sort4" <?php if($_POST[view] == 'sort4') { echo "checked=\"checked\""; } ?>> <?php echo __("Media type view definition #4 (If available!)",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="sort5" <?php if($_POST[view] == 'sort5') { echo "checked=\"checked\""; } ?>> <?php echo __("Media type view definition #5 (If available!)",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="covers" <?php if($_POST[view] == 'covers') { echo "checked=\"checked\""; } ?>> <?php echo __("Covers",$myTreasuresTextdomain); ?></p>
<div class="submit"><input type="submit" name="edit" value=" <?php echo __("Edit media type",$myTreasuresTextdomain); ?> "></div>
<p><b><?php echo __("Switch fields",$myTreasuresTextdomain); ?></b>
<br /><?php echo __("You can switch fields for details if you have to!",$myTreasuresTextdomain); ?>
<br />
<br /><?php echo __("Field",$myTreasuresTextdomain); ?> #1
<br /><select name="change_feature1" style="width: 380px;"><?php echo $selectinput6; ?></select>
<br />
<br /><?php echo __("Field",$myTreasuresTextdomain); ?> #2
<br /><select name="change_feature2" style="width: 380px;"><?php echo $selectinput6; ?></select></p>
<div class="submit"><input type="submit" name="changefields" value=" <?php echo __("Switch fields",$myTreasuresTextdomain); ?> "></div>
</form>

<?php

			}

?>

</div>

<?php

				}

			} elseif($_GET[action] == 'del') {

			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '$_GET[id]'");
			$result01 = mysql_fetch_array($query01);
			$all_to_delete_treasures_of_this_type = mysql_num_rows(mysql_query("SELECT `id` FROM `".$wpdb->prefix."mytreasures` WHERE `type` = '$result01[id]'"));
			
			if($result01[id]) {

?>

<div class="wrap">
<h2><?php echo __("Delete media type",$myTreasuresTextdomain); ?></h2>

<?php

				if($_POST[del] || $_POST[dontdel]) {

					if($_POST[del]) {

						mysql_query("DELETE FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '$result01[id]'");
						$message = sprintf(__("The media type <i>%s</i> was deleted successfully!",$myTreasuresTextdomain),$result01[name]);

					} else {

						$message = sprintf(__("The media type <i>%s</i> has NOT be deleted!",$myTreasuresTextdomain),$result01[name]);

					}

					echo '<div id="message" class="updated fade"><p><strong>'.$message.'</strong></p></div>';

				} else {

?>

<p><?php echo sprintf(__("Do you want to delete media type <i>%s</i>?",$myTreasuresTextdomain),$result01[name]); ?></p>
<form action="" method="post">
<?php if($all_to_delete_treasures_of_this_type) { echo sprintf(__("The're still %s media in database with this type. Please delete them first!",$myTreasuresTextdomain),$all_to_delete_treasures_of_this_type);  } else { ?>
<div class="submit"><input type="submit" name="del" value=" <?php echo __("Yes",$myTreasuresTextdomain); ?> "> <input type="submit" name="dontdel" value=" <?php echo __("No",$myTreasuresTextdomain); ?> "></div>
<?php } ?>
</form>

<?php

			}

?>

</div>

<?php

				}

			} else {

			switch($_GET[sortlist]) {
				case 'id': $order = "id"; $orderquery = "`id`"; break;
				case 'title': $order = "title"; $orderquery = "`name`"; break;
				case 'tag': $order = "tag"; $orderquery = "`short`"; break;
				default: $order = "title"; $orderquery = "`name`"; break;
			}

?>

<div class="wrap">
<h2><?php echo __("Media types",$myTreasuresTextdomain); ?></h2>
<p><?php echo __("Please click on the heading to sort the list!",$myTreasuresTextdomain); ?></p>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="left"><a href="?page=mytreasures/mytreasuresmediatype.php&sortlist=id" style="font-weight: bold; <?php if($order == 'id') { echo "font-style:italic;"; } ?>">ID</a></td></td>
		<td align="left"><a href="?page=mytreasures/mytreasuresmediatype.php&sortlist=tag" style="font-weight: bold; <?php if($order == 'tag') { echo "font-style:italic;"; } ?>">Tag</a></td>
		<td align="left"><a href="?page=mytreasures/mytreasuresmediatype.php&sortlist=title" style="font-weight: bold; <?php if($order == 'title') { echo "font-style:italic;"; } ?>">Titel / Name</a></td>
		<td align="left"><b>Optionen</b></td>
	</tr>

<?php

			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` ORDER BY ".$orderquery."");
			if(mysql_num_rows($query01)) {

				while($result01 = mysql_fetch_array($query01)) {

?>

	<tr <?php if(++$i%2 == 0) { echo "class='alternate'"; } ?>>
		<td align="left"><?php echo $result01[id]; ?></td>
		<td align="left"><?php echo $result01[short]; ?></td>
		<td align="left"><?php echo $result01[name]; ?></td>
		<td align="left">[<a href="?page=mytreasures/mytreasuresmediatype.php&action=edit&id=<?php echo $result01[id]; ?>"><?php echo __("Edit",$myTreasuresTextdomain); ?></a>] [<a href="?page=mytreasures/mytreasuresmediatype.php&action=del&id=<?php echo $result01[id]; ?>"><?php echo __("Delete",$myTreasuresTextdomain); ?></a>]</td>
	</tr>	

<?php

				}

			} else {

?>

<tr>
	<td colspan="4" align="left">Noch keine Medien Typen eingetragen!</td>
</tr>

<?php

			}

?>

</table>
<br /><br />
<h2><?php echo __("Add new media type",$myTreasuresTextdomain); ?></h2>
<form action="" method="post" enctype="multipart/form-data">
<p><?php if($_POST[doit] && !strlen($_POST[name]) > 0) { echo "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />"; } ?>Name<font style="color: #FF0000;">*</font>
<br /><textarea style="height: 16px; width: 90%;" name="name"><?php echo stripslashes($_POST[name]); ?></textarea>
<?php if($check_doubletitle && $_POST[doit]) { ?>
<br /><br /><?php echo __("<b>Important information</b><br />This name is already in database!",$myTreasuresTextdomain); ?>
<br />
<?php } ?>
<br /><br /><?php if($_POST[doit] && !preg_match("/^([a-zA-Z0-9]+)$/",$_POST[short])) { echo "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />"; } ?>Tag<font style="color: #FF0000;">*</font> <?php echo __("(Not changeable, just numbers & letters!)",$myTreasuresTextdomain); ?>
<br /><textarea style="height: 16px; width: 90%;" name="short"><?php echo stripslashes($_POST[short]); ?></textarea>
<?php if($check_doubletshort && $_POST[doit]) { ?>
<br /><br /><?php echo __("<b>Important information</b><br />This tag is already in database!",$myTreasuresTextdomain); ?>
<?php } ?>
<br />
<br /><?php echo __("<b>Information</b><br />Following fields are for your own details of this media type",$myTreasuresTextdomain); ?>

<?php

	if(!$_POST[field01]) { $_POST[field01] = "Name / Titel"; }
	$selectinput = "<option value=\"\">".__("No view for this type",$myTreasuresTextdomain)."</option>";
	for($i = 1; $i <= 20; $i++) {

		if($i < 10) { $field1 = $field1 = __("detail",$myTreasuresTextdomain)." #0".$i; } else { $field1 = __("detail",$myTreasuresTextdomain)." #".$i; }
		if($i == 1) { $field1 .= "<font style=\"color: #FF0000;\">*</font>"; }
		if($i < 10) { $field = "field0".$i; } else { $field = "field".$i; }
		if($i > 1) { $selectinput .= "<option value=\"".$field."\">".sprintf(__("Content of %s (Must have content!)",$myTreasuresTextdomain),$field1)."</option>"; } 
			echo "<br /><br />".$field1."<br /><textarea style=\"height: 16px; width: 90%;\" name=\"".$field."\">".stripslashes($_POST[$field])."</textarea>";

	}

?>

<br />
<br /><b><?php echo __("Views for this media type",$myTreasuresTextdomain); ?></b>
<br /><?php echo __("You can create own \"views\" for ech media type. Just select it here",$myTreasuresTextdomain); ?>
<br />
<br /><?php echo __("View",$myTreasuresTextdomain); ?> #1
<br /><select name="feature_sort1" style="width: 380px;"><?php echo $selectinput; ?></select>
<br />
<br /><?php echo __("View",$myTreasuresTextdomain); ?> #2
<br /><select name="feature_sort2" style="width: 380px;"><?php echo $selectinput; ?></select>
<br />
<br /><?php echo __("View",$myTreasuresTextdomain); ?> #3
<br /><select name="feature_sort3" style="width: 380px;"><?php echo $selectinput; ?></select>
<br />
<br /><?php echo __("View",$myTreasuresTextdomain); ?> #4
<br /><select name="feature_sort4" style="width: 380px;"><?php echo $selectinput; ?></select>
<br />
<br /><?php echo __("View",$myTreasuresTextdomain); ?> #5
<br /><select name="feature_sort5" style="width: 380px;"><?php echo $selectinput; ?></select>
<br />
<br /><b><?php echo __("Tracklist",$myTreasuresTextdomain); ?></b>
<br /><input type="checkbox" name="feature_tracklist" value="1" <?php if($_POST[feature_tracklist]) { echo "checked=\"checked\""; } ?>> <?php echo __("This media type has a tracklist",$myTreasuresTextdomain); ?>
<br />
<br /><b><?php echo __("Default view",$myTreasuresTextdomain); ?></b>
<br /><?php echo __("If you want to have a default view for this media type, just choose it:",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="0" <?php if($_POST[view] == '0' || $_POST[view] == '') { echo "checked=\"checked\""; } ?>> <?php echo __("Use global setttings",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="list" <?php if($_POST[view] == 'list') { echo "checked=\"checked\""; } ?>> <?php echo __("Name",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="rating" <?php if($_POST[view] == 'rating') { echo "checked=\"checked\""; } ?>> <?php echo __("Ratings",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="sort1" <?php if($_POST[view] == 'sort1') { echo "checked=\"checked\""; } ?>> <?php echo __("Media type view definition #1 (If available!)",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="sort2" <?php if($_POST[view] == 'sort2') { echo "checked=\"checked\""; } ?>> <?php echo __("Media type view definition #2 (If available!)",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="sort3" <?php if($_POST[view] == 'sort3') { echo "checked=\"checked\""; } ?>> <?php echo __("Media type view definition #3 (If available!)",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="sort4" <?php if($_POST[view] == 'sort4') { echo "checked=\"checked\""; } ?>> <?php echo __("Media type view definition #4 (If available!)",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="sort5" <?php if($_POST[view] == 'sort5') { echo "checked=\"checked\""; } ?>> <?php echo __("Media type view definition #5 (If available!)",$myTreasuresTextdomain); ?>
<br /><input type="radio" name="view" value="covers" <?php if($_POST[view] == 'covers') { echo "checked=\"checked\""; } ?>> <?php echo __("Covers",$myTreasuresTextdomain); ?></p>
<div class="submit"><input type="submit" name="doit" value=" <?php echo __("Add new media type",$myTreasuresTextdomain); ?> "></div>
</form>
</div>

<?php

			}

		}

	}

?>