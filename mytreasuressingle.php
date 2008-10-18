<?php

	if($_POST[amazonok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'no' WHERE `id` = '1'"); }
	if($_POST[amazonnok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'yes' WHERE `id` = '1'"); }

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

		$check_doubletitle = "0";
		$path = "../wp-content/mytreasures/";

		if(!is_writeable($path)) {

			echo '<div id="message" class="updated fade"><p><strong>'.__("Please create a folder \"mytreasures\" in \"/wp-content/\" with writings rights!",$myTreasuresTextdomain).'</strong></p></div>';

		} else {

			if($_POST[treasuretype]) {

			if(strlen($_POST[field01]) > 0) {

				$check_doubletitle = mysql_num_rows(mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `field01` = '".$_POST[field01]."' AND `type` = '$_POST[treasuretype]'"));

			}

			if(count($_POST[trackname]) > 0) {

				for($tracklist = 1; $tracklist <= 25; $tracklist++) {

					if($_POST[trackname][$tracklist]) {

						$tracks .= $_POST[trackname][$tracklist]."#L#".$_POST[tracklength][$tracklist]."#NT#";

					}

				}

			}

			if(($check_doubletitle == "0" || $check_doubletitle != "0" && $_POST[override]) && strlen($_POST[field01]) > 0 && strlen($_POST[treasuretype]) > 0) { 

				if($_FILES['image']['type'] == "image/pjpeg" || $_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/gif" || $_FILES['image']['type'] == "image/png") {

					$imagename = "ownupload_".time().".".myTreasuresGetImageType($_FILES['image']['name']);
					if($myTreasures_options[option03] == 'yes') {

						if($myTreasures_options[option04] == 'fixedheight') { $height = $myTreasures_options[option05]; $width = "0"; $resizeby = "height"; $cutimage = false; }
						if($myTreasures_options[option04] == 'fixedwidth') { $height = "0"; $width = $myTreasures_options[option06]; $resizeby = "width"; $cutimage = false; }
						if($myTreasures_options[option04] == 'fixedboth') { $height = $myTreasures_options[option07]; $width = $myTreasures_options[option08]; $resizeby = "width"; $cutimage = true; }
						if(!myTreasuresImageResize($_FILES['image']['tmp_name'],$path.$imagename,$width,$height,$resizeby,$cutimage)) {

							echo "<div id=\"message\" class=\"updated fade\"><p><strong>".__("The system had problems to save the image / cover. Please retry it!",$myTreasuresTextdomain)."</strong></p></div>";

						}

					} else {

						copy($_FILES['image']['tmp_name'],$path.$imagename);
						chmod($path.$imagename, 0666);

					}

					if($myTreasures_options[option14] == 'yes') {

						copy($_FILES['image']['tmp_name'],$path."big_".$imagename);
						chmod($path."big_".$imagename, 0666);

					}
						
				}

				mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures` (`type`, `rating`, `description`, `comment`, `tracklist`, `image`, `field01`, `field02`, `field03`, `field04`, `field05`, `field06`, `field07`, `field08`, `field09`, `field10`, `field11`, `field12`, `field13`, `field14`, `field15`, `field16`, `field17`, `field18`, `field19`, `field20`) VALUES ('$_POST[treasuretype]', '$_POST[rating]',  '".$_POST[description]."', '".$_POST[comment]."', '$tracks', '$imagename', '".$_POST[field01]."', '".$_POST[field02]."', '".$_POST[field03]."', '".$_POST[field04]."', '".$_POST[field05]."', '".$_POST[field06]."', '".$_POST[field07]."', '".$_POST[field08]."', '".$_POST[field09]."', '".$_POST[field10]."', '".$_POST[field11]."', '".$_POST[field12]."', '".$_POST[field13]."', '".$_POST[field14]."', '".$_POST[field15]."', '".$_POST[field16]."', '".$_POST[field17]."', '".$_POST[field18]."', '".$_POST[field19]."', '".$_POST[field20]."')");
				echo "<div id=\"message\" class=\"updated fade\"><p><strong>".sprintf(__("The media <i>%s</i> was created successfully!",$myTreasuresTextdomain),trim($_POST[field01]))."</strong></p></div>";

?>

<div class="wrap">
<div class="submit"><form action="" method="post" style="display: inline;"><input type="submit" name="doit2" value=" <?php echo __("Add media of a different media type",$myTreasuresTextdomain); ?> "></form> <form action="" method="post" style="display: inline;"><input type="hidden" name="treasuretype" value="<?php echo $_POST[treasuretype]; ?>"><input type="submit" name="doit2" value=" <?php echo __("Add media of the same media type",$myTreasuresTextdomain); ?> "></form></div>
</div>

<?php
				
			} else {

				$query_type = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '$_POST[treasuretype]'");
				$result_type = mysql_fetch_array($query_type);

?>

<div class="wrap">
<h2><?php echo sprintf(__("Add media of typ <i>%s</i>",$myTreasuresTextdomain),$result_type[name]); ?></h2>
<p><?php echo __("Please insert your data. All mandatory fields are marked with a <font style=\"color: #FF0000;\">*</font>",$myTreasuresTextdomain); ?></p>

<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="treasuretype" value="<?php echo $_POST[treasuretype]; ?>">
<p><?php if($_POST[doit] && !strlen($_POST[field01]) > 0) { echo "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />"; } echo $result_type["field01"]; ?><font style="color: #FF0000;">*</font>
<br /><textarea style="height: 16px; width: 90%;" name="field01"><?php echo stripslashes($_POST[field01]); ?></textarea>
<?php if($check_doubletitle && $_POST[doit]) { ?>
<br /><br /><b><?php echo __("Important information",$myTreasuresTextdomain); ?></b><br /><?php echo __("This title is already in database, continue adding?",$myTreasuresTextdomain); ?>
<br /><input type="checkbox" name="override" value="1"> <?php echo __("Yes",$myTreasuresTextdomain); ?>
<br />
<?php } ?>

<?php

	for($i = 2; $i <= 20; $i++) {

		if($i < 10) { $field = "field0".$i; } else { $field = "field".$i; }

		if($result_type[$field]) {

			echo "<br /><br />".$result_type[$field]."<br /><textarea style=\"height: 16px; width: 90%;\" name=\"".$field."\">".stripslashes($_POST[$field])."</textarea>";

		}

	}

	if($result_type[feature_tracklist]) { for($tracklist = 1; $tracklist <= 25; $tracklist++) {

?>

<br />
<br /><?php echo __("Track",$myTreasuresTextdomain); ?> #<?php echo $tracklist; ?>
<br /><textarea style="height: 16px; width: 90%;" name="trackname[<?php echo $tracklist; ?>]"><?php echo stripslashes($_POST[trackname][$tracklist]); ?></textarea>
<br /><?php echo __("Length (in minutes)",$myTreasuresTextdomain); ?>
<br /><textarea style="height: 16px; width: 90%;" name="tracklength[<?php echo $tracklist; ?>]"><?php echo stripslashes($_POST[tracklength][$tracklist]); ?></textarea>
<?php } } else { ?>
<br />
<br /><?php echo __("Description",$myTreasuresTextdomain); ?>
<br /><textarea name="description" style="height: 150px; width: 90%;"><?php echo stripslashes($_POST[description]); ?></textarea>
<?php } ?>

<br />
<br /><?php echo __("My comment",$myTreasuresTextdomain); ?>
<br /><textarea name="comment" style="height: 150px; width: 90%;"><?php echo stripslashes($_POST[comment]); ?></textarea>
<br />
<br /><?php echo __("Rating (in stars)",$myTreasuresTextdomain); ?>
<br /><?php echo __("bad",$myTreasuresTextdomain); ?> <?php for($i = 0.5; $i <= 5; $i += 0.5) { ?><input type="radio" name="rating" value="<?php echo ($i*10); ?>" <?php if($_POST[rating] == ($i*10)) { echo "checked"; } ?>><?php echo number_format($i,1,",",""); ?>&nbsp;&nbsp;<?php } ?> <?php echo __("good",$myTreasuresTextdomain); ?>
<br />
<br /><?php echo __("Image / Cover",$myTreasuresTextdomain); ?>
<br /><input type="file" name="image" size="39" class="uploadform">
<br />
<br /><?php echo __("System will take default image if no other is set",$myTreasuresTextdomain); ?> (default.jpg in wp-content/mytreasures/)</p>
<div class="submit"><input type="submit" name="doit" value=" <?php echo __("Add media",$myTreasuresTextdomain); ?> "></div>
</form>

<?php

			}

?>

</div>

<?php


			} else {

?>

<div class="wrap">
<h2><?php echo __("Choose media type",$myTreasuresTextdomain); ?></h2>
<p><?php echo __("Please choose which media type you want to add:",$myTreasuresTextdomain); ?></p>
<form action="" method="post">
<?php $query99 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` ORDER BY `name`"); while($result99 = mysql_fetch_array($query99)) { echo "<br /><input type=\"radio\" name=\"treasuretype\" value=\"".$result99[id]."\"> ".$result99[name]; } ?>
<div class="submit"><input type="submit" name="gotodata" value=" <?php echo __("Continue to details",$myTreasuresTextdomain); ?> "></div>
</form>
</div>

<?php

			}

		}

	}

?>