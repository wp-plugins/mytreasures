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

		$path1 = "../wp-content/mytreasuresimages/small/";
		$path2 = "../wp-content/mytreasuresimages/big/";

		if(!is_writeable($path1)) {

			echo '<div id="message" class="updated fade"><p><strong>'.__("Please create a folder \"mytreasuresimages\" with subfolder \"small\" in \"/wp-content/\" with writings rights!",$myTreasuresTextdomain).'</strong></p></div>';

		} elseif(!is_writeable($path2)) {

			echo '<div id="message" class="updated fade"><p><strong>'.__("Please create a folder \"mytreasuresimages\" with subfolder \"big\" in \"/wp-content/\" with writings rights!",$myTreasuresTextdomain).'</strong></p></div>';

		} else {
			
			if($_GET[id]) {

				$imagearray = false;
				$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '$_GET[id]'");
				$result01 = mysql_fetch_array($query01);

				if($_FILES['image']['type'] == "image/pjpeg" || $_FILES['image']['type'] == "image/jpeg") {

					$imagename = "imageupload_".time().".".myTreasuresGetImageType($_FILES['image']['name']);
					$picdata = getimagesize($_FILES['image']['tmp_name']);

					if($picdata[0] > 100) {

						if(!myTreasuresImageResize($_FILES['image']['tmp_name'],$path1.$imagename,"100","0","width")) {

							echo '<div id="message" class="updated fade"><p><strong>'.__("The system had problems to save the image. Please retry it!",$myTreasuresTextdomain).'</strong></p></div>';

						}

					} else {

						@copy($_FILES['image']['tmp_name'],$path1.$imagename);
						chmod($path1.$imagename, 0666);

					}

					@copy($_FILES['image']['tmp_name'],$path2.$imagename);
					chmod($path2.$imagename, 0666);
					mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_images` (`treasureid`, `name`, `comment`) VALUES ('$result01[id]', '$imagename', '".$_POST[title]."')");

				}

				$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_images` WHERE `treasureid` = '$_GET[id]'");
				while($result02 = mysql_fetch_array($query02)) { $imagearray[] = $result02; }
				
?>

<div class="wrap">
<h2><?php echo $result01[field01]; ?></h2>
<form action="" method="post" enctype="multipart/form-data"><p><h3><?php echo __("Add new image:",$myTreasuresTextdomain); ?></h3><b><?php echo __("Image",$myTreasuresTextdomain); ?>:</b><br /><input type="file" name="image" size="39" class="uploadform"><br /><br /><b><?php echo __("Name / subtitle for image (optional)",$myTreasuresTextdomain); ?></b><br /><textarea style="height: 16px; width: 250px;" name="title"></textarea></p><div class="submit"><input type="submit" name="doit" value=" <?php echo __("Upload new image",$myTreasuresTextdomain); ?> "></div></form></div>

<?php

				if($_POST[changepics] && $imagearray) {

					foreach($imagearray AS $image) {

						if($_POST[deletepic][$image[id]]) {

							mysql_query("DELETE FROM `".$wpdb->prefix."mytreasures_images` WHERE `id` = '$image[id]'");
							@unlink($path1.$image[name]);
							@unlink($path2.$image[name]);

						} else {

							mysql_query("UPDATE `".$wpdb->prefix."mytreasures_images` SET `orderid` = '".$_POST[orderid][$image[id]]."', `comment` = '".$_POST[comment][$image[id]]."' WHERE `id` = '$image[id]'");

						}

					}

					$imagearray = false;
					$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_images` WHERE `treasureid` = '$_GET[id]' ORDER BY `orderid`");
					while($result02 = mysql_fetch_array($query02)) { $imagearray[] = $result02; }

				}

				if($imagearray) {

?>

<br /><br />
<div class="wrap">
<h2><?php echo __("Activ images",$myTreasuresTextdomain); ?></h2>
<form action="" method="post"><p><?php foreach($imagearray AS $image) { echo "<p style=\"float: left; margin-right: 10px; text-align: center;\"><img src=\"../wp-content/mytreasuresimages/small/".$image[name]."\"><br /><textarea style=\"height: 16px; width: 100px;\" name=\"comment[".$image[id]."]\">".stripslashes($image[comment])."</textarea><br />".__("Order:",$myTreasuresTextdomain)." <input type=\"text\" style=\"height: 16px; width: 20px; text-align: center;\" name=\"orderid[".$image[id]."]\" value=\"".$image[orderid]."\"><br /><input type=\"checkbox\" name=\"deletepic[".$image[id]."]\" value=\"1\"> ".__("Delete image",$myTreasuresTextdomain)."</p> "; } ?></p><p style="clear: both;"></p><div class="submit"><input type="submit" name="changepics" value=" <?php echo __("Save changings",$myTreasuresTextdomain); ?> "></div></div></form>

<?php

				}

			} else {

			switch($_GET[sortlist]) {
				case 'id': $order = "id"; $orderquery = "`".$wpdb->prefix."mytreasures`.`id`"; break;
				case 'title': $order = "title"; $orderquery = "`".$wpdb->prefix."mytreasures`.`field01`"; break;
				case 'type': $order = "type"; $orderquery = "`".$wpdb->prefix."mytreasures_type`.`name`"; break;
				default: $order = "title"; $orderquery = "`".$wpdb->prefix."mytreasures`.`field01`"; break;
			}

?>

<div class="wrap">
<h2><?php echo __("Image overview",$myTreasuresTextdomain); ?></h2>
<p><?php echo __("Please click on the heading to sort the list!",$myTreasuresTextdomain); ?></p>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="left"><a href="?page=mytreasures/mytreasuresimages.php&sortlist=id" style="font-weight: bold; <?php if($order == 'id') { echo "font-style:italic;"; } ?>">ID</a></td></td>
		<td align="left"><a href="?page=mytreasures/mytreasuresimages.php&sortlist=title" style="font-weight: bold; <?php if($order == 'title') { echo "font-style:italic;"; } ?>">Titel / Name</a></td>
		<td align="left"><a href="?page=mytreasures/mytreasuresimages.php&sortlist=type" style="font-weight: bold; <?php if($order == 'type') { echo "font-style:italic;"; } ?>"><?php echo __("Type",$myTreasuresTextdomain); ?></a></td>
		<td align="center"><b><?php echo __("Options",$myTreasuresTextdomain); ?></b></td>
	</tr>

<?php

			$query01 = mysql_query("SELECT `".$wpdb->prefix."mytreasures`.*, `".$wpdb->prefix."mytreasures_type`.`name` FROM `".$wpdb->prefix."mytreasures` LEFT JOIN `".$wpdb->prefix."mytreasures_type` ON `".$wpdb->prefix."mytreasures`.`type` = `".$wpdb->prefix."mytreasures_type`.`id` ORDER BY ".$orderquery."");
			if(mysql_num_rows($query01)) {

				while($result01 = mysql_fetch_array($query01)) {

?>

	<tr <?php if(++$i%2 == 0) { echo "class='alternate'"; } ?>>
		<td align="left"><?php echo $result01[id]; ?></td>
		<td align="left"><?php echo $result01[field01]; ?></td>
		<td align="left"><?php echo $result01[name]; ?></td>
		<td align="center">[<a href="?page=mytreasures/mytreasuresimages.php&id=<?php echo $result01[id]; ?>"><?php echo __("Administrate images",$myTreasuresTextdomain); ?></a>]</td>
	</tr>	

<?php

				}

			} else {

?>

<tr>
	<td colspan="5" align="left"><?php echo __("No media in database!",$myTreasuresTextdomain); ?></td>
</tr>

<?php

			}

?>

</table>
</div>

<?php

			}

		}

	}

?>