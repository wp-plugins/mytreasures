<?php

	if($_POST[amazonok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'no' WHERE `id` = '1'"); $myTreasures_options[option20] = "no"; } 
	if($_POST[amazonnok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'yes' WHERE `id` = '1'"); $myTreasures_options[option20] = "yes"; } 

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

			if($_GET[action] == 'edit') {

			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '$_GET[id]'");
			$result01 = mysql_fetch_array($query01);

			$query_type = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '$result01[type]'");
			$result_type = mysql_fetch_array($query_type);
			
			if($result01[id]) {

?>

<div class="wrap">
<h2><?php echo __("Edit media",$myTreasuresTextdomain); ?></h2>
<p><?php echo __("Please insert your data. All mandatory fields are marked with a <font style=\"color: #FF0000;\">*</font>",$myTreasuresTextdomain); ?></p>

<?php

				if($result_type[feature_tracklist] && count($_POST[trackname]) > 0) {

					for($tracklist = 1; $tracklist <= 25; $tracklist++) {

						if($_POST[trackname][$tracklist]) {

							$tracks .= $_POST[trackname][$tracklist]."#L#".$_POST[tracklength][$tracklist]."#NT#";

						}

					}

				}
			
				if(strlen($_POST[field01]) > 0 && (!$result_type[feature_tracklist] || ($result_type[feature_tracklist] && strlen($tracks) > 0))) { 

					$imagename = $result01[image];
					if($_FILES['image']['type'] == "image/pjpeg" || $_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/gif" || $_FILES['image']['type'] == "image/png") {

						$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_options` WHERE `id` = '1'");
						$result02 = mysql_fetch_array($query02);
						@unlink($path.$result01[image]);
						@unlink($path."big_".$result01[image]);
						$imagename = "ownupload_".time().".".myTreasuresGetImageType($_FILES['image']['name']);

						if($result02[option03] == 'yes') {

							if($result02[option04] == 'fixedheight') { $height = $result02[option05]; $width = "0"; $resizeby = "height"; $cutimage = false; }
							if($result02[option04] == 'fixedwidth') { $height = "0"; $width = $result02[option06]; $resizeby = "width"; $cutimage = false; }
							if($result02[option04] == 'fixedboth') { $height = $result02[option07]; $width = $result02[option08]; $resizeby = "width"; $cutimage = true; }
							if(!myTreasuresImageResize($_FILES['image']['tmp_name'],$path.$imagename,$width,$height,$resizeby,$cutimage,$result02[option32])) {

								echo "<div id=\"message\" class=\"updated fade\"><p><strong>".__("The system had problems to save the image / cover. Please retry it!",$myTreasuresTextdomain)."</strong></p></div>";

							}

						} else {

							myTreasuresImageResize($_FILES['image']['tmp_name'],$path.$imagename,"","","","",$myTreasures_options[option32]);
							chmod($path.$imagename, 0666);

						}

						if($result02[option14] == 'yes') {

							myTreasuresImageResize($_FILES['image']['tmp_name'],$path."big_".$imagename,"","","","",$myTreasures_options[option32]);
							chmod($path."big_".$imagename, 0666);

						}

					}

					if($_POST[deletecover]) {
						
						@unlink($path.$result01[image]);
						@unlink($path."big_".$result01[image]);
						$imagename = "";

					}

					mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET `rentto` = '$_POST[rentto]', `type` = '$_POST[type]', `image` = '$imagename', `rating` = '$_POST[rating]', `description` = '".$_POST[description]."', `comment` = '".$_POST[comment]."', `tracklist` = '$tracks', `field01` = '".$_POST[field01]."', `field02` = '".$_POST[field02]."', `field03` = '".$_POST[field03]."', `field04` = '".$_POST[field04]."', `field05` = '".$_POST[field05]."', `field06` = '".$_POST[field06]."', `field07` = '".$_POST[field07]."', `field08` = '".$_POST[field08]."', `field09` = '".$_POST[field09]."', `field10` = '".$_POST[field10]."', `field11` = '".$_POST[field11]."', `field12` = '".$_POST[field12]."', `field13` = '".$_POST[field13]."', `field14` = '".$_POST[field14]."', `field15` = '".$_POST[field15]."', `field16` = '".$_POST[field16]."', `field17` = '".$_POST[field17]."', `field18` = '".$_POST[field18]."', `field19` = '".$_POST[field19]."', `field20` = '".$_POST[field20]."' WHERE `id` = '$result01[id]'");
					echo "<div id=\"message\" class=\"updated fade\"><p><strong>".sprintf(__("The media <i>%s</i> was edited successfully!",$myTreasuresTextdomain),$_POST[field01])."</strong></p></div>";

				} else {

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
					if(!$_POST[description]) { $_POST[description] = $result01[description]; }
					if(!$_POST[comment]) { $_POST[comment] = $result01[comment]; }
					if(!$_POST[rentto]) { $_POST[rentto] = $result01[rentto]; }
					if(!$_POST[rating]) { $_POST[rating] = ($result01[rating]); }
					if($result_type[feature_tracklist]) { $all_tracks = explode("#NT#",$result01[tracklist]); foreach($all_tracks AS $track) { list($_POST[trackname][(++$counttracks)],$_POST[tracklength][$counttracks]) = explode("#L#",$track); } }

?>

<form action="" method="post" enctype="multipart/form-data">
<p><?php if($_POST[doit] && !strlen($_POST[field01]) > 0) { echo "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />"; } echo $result_type["field01"]; ?><font style="color: #FF0000;">*</font>
<br /><textarea style="height: 30px; width: 90%;" name="field01"><?php echo stripslashes($_POST[field01]); ?></textarea>
<?php 

	for($i = 2; $i <= 20; $i++) {

		if($i < 10) { $field = "field0".$i; } else { $field = "field".$i; }

		if($result_type[$field]) {

			echo "<br /><br />".$result_type[$field]."<br /><textarea style=\"height: 30px; width: 90%;\" name=\"".$field."\">".stripslashes($_POST[$field])."</textarea>";

		}

	}

	if($result_type[feature_tracklist]) { for($tracklist = 1; $tracklist <= 25; $tracklist++) {

?>

<br />
<br /><?php echo __("Track",$myTreasuresTextdomain); ?> #<?php echo $tracklist; ?>
<br /><textarea style="height: 30px; width: 90%;"name="trackname[<?php echo $tracklist; ?>]"><?php echo stripslashes($_POST[trackname][$tracklist]); ?></textarea>
<br /><?php echo __("Length (in minutes)",$myTreasuresTextdomain); ?>
<br /><textarea style="height: 30px; width: 90%;"name="tracklength[<?php echo $tracklist; ?>]"><?php echo stripslashes($_POST[tracklength][$tracklist]); ?></textarea>
<?php } } else { ?>
<br />
<br /><?php echo __("Description",$myTreasuresTextdomain); ?>
<br /><textarea name="description" style="height: 150px; width: 90%;"><?php echo stripslashes($_POST[description]); ?></textarea>
<?php } ?>

<br />
<br /><?php echo __("My comment",$myTreasuresTextdomain); ?>
<br /><textarea name="comment" style="height: 150px; width: 90%;"><?php echo stripslashes($_POST[comment]); ?></textarea>
<br />

<?php if($myTreasures_options[option29] == 'yes') { ?>
<br /><?php echo __("Rent to",$myTreasuresTextdomain); ?>
<br /><textarea style="height: 30px; width: 90%;" name="rentto"><?php echo stripslashes($_POST[rentto]); ?></textarea>
<br />

<?php } ?>

<br /><?php echo __("Rating (in stars)",$myTreasuresTextdomain); ?>
<br /><?php echo __("bad",$myTreasuresTextdomain); ?>&nbsp;&nbsp;<?php for($i = 0.5; $i <= 5; $i += 0.5) { ?><input type="radio" name="rating" value="<?php echo ($i*10); ?>" <?php if($_POST[rating] == ($i*10)) { echo "checked"; } ?>><?php echo number_format($i,1,",",""); ?>&nbsp;&nbsp;<?php } echo __("good",$myTreasuresTextdomain); ?>
<br />

<?php if(strlen($result01[image]) > 3) { ?>

<br /><?php echo __("Current Image / Cover",$myTreasuresTextdomain); ?> (<?php echo $result01[image]; ?>):
<br /><img src="../wp-content/mytreasures/<?php echo $result01[image]; ?>" />
<br /><input type="checkbox" name="deletecover" value="1"> <?php echo __("Delete current Image / Cover",$myTreasuresTextdomain); ?>
<br />

<?php } ?>

<br /><?php echo __("Image / Cover",$myTreasuresTextdomain); ?>
<br /><input type="file" name="image" size="39" class="uploadform">
<br />
<br /><?php echo __("Choose media type",$myTreasuresTextdomain); ?>
<br /><?php echo __("Please just change the media type if both types have similar / identical values for each field!",$myTreasuresTextdomain); ?>
<br /><select name="type" style="width: 200px;"><?php foreach($myTreasuresMediaTypeArray AS $id => $name) { ?><option value="<?php echo $id; ?>" <?php if($result01[type] == $id) { echo "selected"; } ?>><?php echo $name; ?></option><?php } ?></select></p>
<div class="submit"><input type="submit" value=" <?php echo __("Edit media",$myTreasuresTextdomain); ?> "></div>
</form>

<?php

			}

?>

</div>
<br /><br />

<?php

				}

			}
			
			if($_GET[action] == 'del') {

			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '$_GET[id]'");
			$result01 = mysql_fetch_array($query01);
			
			if($result01[id]) {

?>

<div class="wrap">
<h2><?php echo __("Delete media",$myTreasuresTextdomain); ?></h2>

<?php

				if($_POST[del] || $_POST[dontdel]) {

					if($_POST[del]) {

						mysql_query("DELETE FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '$result01[id]'");
						@unlink($path.$result01[image]);
						@unlink($path."big_".$result01[image]);
						$message = sprintf(__("The media <i>%s</i> was deleted successfully!",$myTreasuresTextdomain),$result01[field01]);

					} else {

						$message = sprintf(__("The media <i>%s</i> has NOT be deleted!",$myTreasuresTextdomain),$result01[field01]);

					}

					echo '<div id="message" class="updated fade"><p><strong>'.$message.'</strong></p></div>';

				} else {

?>

<p><?php echo sprintf(__("Do you want to delete <i>%s</i>?",$myTreasuresTextdomain),$result01[field01]); ?></p>
<form action="" method="post">
<div class="submit"><input type="submit" name="del" value=" <?php echo __("Yes",$myTreasuresTextdomain); ?> "> <input type="submit" name="dontdel" value=" <?php echo __("No",$myTreasuresTextdomain); ?> "></div>
</form>

<?php

			}

?>

</div>
<br /><br />

<?php

				}

			}

			if($_POST[deletemarked] && $_POST[deletemedia]) {

?>

<div class="wrap">
<h2><?php echo __("Delete media",$myTreasuresTextdomain); ?></h2>

<?php

				if($_POST[del] || $_POST[dontdel]) {

					if($_POST[del]) {

						$deleteditems = false;
						foreach($_POST[deletemedia] AS $id => $name) {

							$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '$id'");
							$result01 = mysql_fetch_array($query01);
							mysql_query("DELETE FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '$result01[id]'");
							@unlink($path.$result01[image]);
							@unlink($path."big_".$result01[image]);
							$deleteditems .= "<br />- ".$result01[field01];

						}
						$deleteditems .= "<br />";
						$message = sprintf(__("The media <i>%s</i> was deleted successfully!",$myTreasuresTextdomain),$deleteditems);

					} else {

						$deleteditems = false;
						foreach($_POST[deletemedia] AS $id => $name) {

							$deleteditems .= "<br />- ".$name;

						}
						$deleteditems .= "<br />";
						$message = sprintf(__("The media <i>%s</i> has NOT be deleted!",$myTreasuresTextdomain),$deleteditems);

					}

					echo '<div id="message" class="updated fade"><p><strong>'.$message.'</strong></p></div>';

				} else {

?>

<form action="" method="post">
<input type="hidden" name="deletemarked" value="1" />
<p><?php echo __("Do you want to delete the following media?",$myTreasuresTextdomain); foreach($_POST[deletemedia] AS $id => $name) { echo "<input type=\"hidden\" name=\"deletemedia[".$id."]\" value=\"".$name."\" /><br />- ".$name; } ?></p>
<div class="submit"><input type="submit" name="del" value=" <?php echo __("Yes",$myTreasuresTextdomain); ?> "> <input type="submit" name="dontdel" value=" <?php echo __("No",$myTreasuresTextdomain); ?> "></div>
</form>

<?php

			}

?>

</div>
<br /><br />

<?php

			}

			switch($_GET[sortlist]) {
				case 'id': $order = "id"; $orderquery = "`".$wpdb->prefix."mytreasures`.`id`"; break;
				case 'title': $order = "title"; $orderquery = "`".$wpdb->prefix."mytreasures`.`field01`"; break;
				case 'type': $order = "type"; $orderquery = "`".$wpdb->prefix."mytreasures_type`.`name`, `".$wpdb->prefix."mytreasures`.`field01`"; break;
				default: $order = "title"; $orderquery = "`".$wpdb->prefix."mytreasures`.`field01`"; break;
			}

?>

<script type="text/javascript">
<!-- 
function markallmedia() {

	for(var i = 0; i < document.myform.elements.length; i++) {
    if(document.myform.elements[i].type == 'checkbox'){
      document.myform.elements[i].checked = !(document.myform.elements[i].checked);
    }
  }
	document.myform.elements[0].checked = !(document.myform.elements[0].checked);

}
//-->
</script>  

<div class="wrap">
<h2><?php echo __("Overview",$myTreasuresTextdomain); ?></h2>

<?php

			$query01 = mysql_query("SELECT `".$wpdb->prefix."mytreasures`.*, `".$wpdb->prefix."mytreasures_type`.`name` FROM `".$wpdb->prefix."mytreasures` LEFT JOIN `".$wpdb->prefix."mytreasures_type` ON `".$wpdb->prefix."mytreasures`.`type` = `".$wpdb->prefix."mytreasures_type`.`id` ORDER BY ".$orderquery."");
			if(mysql_num_rows($query01)) {

?>

<p><?php echo __("Please click on the heading to sort the list!",$myTreasuresTextdomain); ?></p>
<form name="myform" action="" method="post">
<table class="widefat fixed" cellspacing="0">
<thead>
<tr class="thead">
	<th scope="col"  class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
	<th scope="col"  class="manage-column column-username" style=""><a href="?page=mytreasures/mytreasuresoverview.php&sortlist=title" style="font-weight: bold; <?php if($order == 'title') { echo "font-style:italic;"; } ?>">Titel</a></th>
	<th scope="col"  class="manage-column column-name" style=""><a href="?page=mytreasures/mytreasuresoverview.php&sortlist=type" style="font-weight: bold; <?php if($order == 'type') { echo "font-style:italic;"; } ?>"><?php echo __("Type",$myTreasuresTextdomain); ?></a></th>
	<th scope="col"  class="manage-column column-posts num" style=""><a href="?page=mytreasures/mytreasuresoverview.php&sortlist=id" style="font-weight: bold; <?php if($order == 'id') { echo "font-style:italic;"; } ?>">ID</a></th>
</tr>
</thead>

<tfoot>
<tr class="thead">
	<th scope="col"  class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
	<th scope="col"  class="manage-column column-username" style=""><a href="?page=mytreasures/mytreasuresoverview.php&sortlist=title" style="font-weight: bold; <?php if($order == 'title') { echo "font-style:italic;"; } ?>">Titel</a></th>
	<th scope="col"  class="manage-column column-name" style=""><a href="?page=mytreasures/mytreasuresoverview.php&sortlist=type" style="font-weight: bold; <?php if($order == 'type') { echo "font-style:italic;"; } ?>"><?php echo __("Type",$myTreasuresTextdomain); ?></a></th>
	<th scope="col"  class="manage-column column-posts num" style=""><a href="?page=mytreasures/mytreasuresoverview.php&sortlist=id" style="font-weight: bold; <?php if($order == 'id') { echo "font-style:italic;"; } ?>">ID</a></th>
</tr>
</tfoot>

<tbody id="users" class="list:user user-list">

<?php

		while($result01 = mysql_fetch_array($query01)) {

?>

<tr id='user-1' <?php if(++$i%2 == 0) { echo "class='alternate'"; } ?>>
	<th scope='row' class='check-column'><input type='checkbox' name='deletemedia[<?php echo $result01[id]; ?>]' id='user_1' value='<?php echo $result01[field01]; ?>' /></th>
	<td class="username column-username"<?php if($result01[rentto] && $myTreasures_options[option29] == 'yes') { echo "style=\"font-style: italic;\""; } ?>><?php if($result01[rentto] && $myTreasures_options[option29] == 'yes') { echo "<b>".__("Rent to",$myTreasuresTextdomain).":</b> ".$result01[rentto]." - "; }; echo $result01[field01]; if(strlen($result01[image]) < 3) { echo " (<b>".__("No Image / Cover!",$myTreasuresTextdomain)."</b>)"; } ?><br /><div class="row-actions"><span class='edit'><a href="?page=mytreasures/mytreasuresoverview.php&action=edit&id=<?php echo $result01[id]; ?>"><?php echo __("Edit",$myTreasuresTextdomain); ?></a> | <a href="?page=mytreasures/mytreasuresoverview.php&action=del&id=<?php echo $result01[id]; ?>"><?php echo __("Delete",$myTreasuresTextdomain); ?></a> | <a href="?page=mytreasures/mytreasuresimages.php&id=<?php echo $result01[id]; ?>"><?php echo __("Administrate images",$myTreasuresTextdomain); ?></a> | <a href="?page=mytreasures/mytreasureslinks.php&id=<?php echo $result01[id]; ?>"><?php echo __("Administrate links",$myTreasuresTextdomain); ?></a></span></div></td>
	<td class="name column-name"><?php echo $result01[name]; ?></td>
	<td class="posts column-posts num"><?php echo $result01[id]; ?></td>
</tr>


<?php

				}

?>

</tbody>
</table>
<div class="submit"><input type="submit" name="deletemarked" value=" <?php echo __("Delete marked media",$myTreasuresTextdomain); ?> "></div>
</form>

<?php

			} else {

?>

<p><?php echo __("No media in database!",$myTreasuresTextdomain); ?></p>

<?php

			}

?>

</div>

<?php

		}

	}

?>