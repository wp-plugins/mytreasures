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

		echo "<div class=\"wrap\"><h2>myTreasures</h2><p>".__("Dear user,<br /><br />the development of myTreasures takes up a lot of time and I offer it to you free of charge. But of course the webserver and the traffic have to paid for. If you allow this installation to post an Amazon Partner link (just a plain text link saying \"Amazon.de\" that will only be displayed in the Detail view) it would be a reward for my work. If anyone buys anything using that link I get credited 5%.<br /><br />There are no costs for you! If you'd like to contribute in another way, please have a look at the Info page.<br /><br />Would you like to activate the Amazon link and support the development of myTreasures?",$myTreasuresTextdomain)."</p><div class=\"submit\"><form action=\"\" method=\"post\" style=\"display: inline;\"><input type=\"submit\" class=\"button-primary\" name=\"amazonok\" value=\" ".__("Yes, please activate",$myTreasuresTextdomain)." \">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"amazonnok\" value=\" ".__("No thanks, I don't want the Amazon link",$myTreasuresTextdomain)." \"></form></div></div>";

	} else {

		$path1 = "../wp-content/mytreasuresimages/small/";
		$path2 = "../wp-content/mytreasuresimages/big/";

		if(!is_writeable($path1)) {

			echo '<div id="message" class="updated fade"><p><strong>'.__("Please create a folder \"mytreasuresimages\" with subfolder \"small\" in \"/wp-content/\" with writings rights!",$myTreasuresTextdomain).'</strong></p></div>';

		} elseif(!is_writeable($path2)) {

			echo '<div id="message" class="updated fade"><p><strong>'.__("Please create a folder \"mytreasuresimages\" with subfolder \"big\" in \"/wp-content/\" with writings rights!",$myTreasuresTextdomain).'</strong></p></div>';

		} else {
			
			if($_GET[id]) {

				$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '$_GET[id]'");
				$result01 = mysql_fetch_array($query01);

				if($_POST[deletemarked] && $_POST[deletemedia]) {

					if($_POST[del] || $_POST[dontdel]) {

						if($_POST[del]) {

							$deleteditems = 0;
							foreach($_POST[deletemedia] AS $id => $name) {

								$deleteditems++;
								$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_images` WHERE `id` = '$id'");
								$result02 = mysql_fetch_array($query02);
								mysql_query("DELETE FROM `".$wpdb->prefix."mytreasures_images` WHERE `id` = '$result02[id]'");
								@unlink($path1.$result02[name]);
								@unlink($path2.$result02[name]);

							}

							myTreasuresCheckOrder($wpdb->prefix."mytreasures_images","WHERE `treasureid` = '$result01[id]'");
							echo '<div id="message" class="updated fade"><p><strong>'.sprintf(__("%s image(s) deleted successfully!",$myTreasuresTextdomain),$deleteditems).'</strong></p></div>';

						}

					} else {

?>

<div class="wrap"><h2><?php echo __("Delete media",$myTreasuresTextdomain); ?> (<?php echo $result01[field01]; ?>)</h2>
<form action="" method="post">
<input type="hidden" name="deletemarked" value="1" />
<p><?php echo __("Do you want to delete the following images?",$myTreasuresTextdomain)."<br />"; foreach($_POST[deletemedia] AS $id => $name) { echo "<input type=\"hidden\" name=\"deletemedia[".$id."]\" value=\"".$name."\" /><img src=\"../wp-content/mytreasuresimages/small/".$name."\"> "; } ?></p>
<div class="submit"><input type="submit" class="button-primary" name="del" value=" <?php echo __("Yes",$myTreasuresTextdomain); ?> "> <input type="submit" name="dontdel" value=" <?php echo __("No",$myTreasuresTextdomain); ?> "></div>
</form>
</div>
<br /><br />

<?php

					}

				}

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
					mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_images` (`treasureid`, `name`, `comment`, `orderid`) VALUES ('$result01[id]', '$imagename', '".$_POST[title]."', '999999')");
					myTreasuresCheckOrder($wpdb->prefix."mytreasures_images","WHERE `treasureid` = '$result01[id]'");

				}
				
				if($_POST[saveorder] && $_POST[orderid]) {

					foreach($_POST[orderid] AS $id => $orderid) {

						mysql_query("UPDATE `".$wpdb->prefix."mytreasures_images` SET `orderid` = '$orderid', `comment` = '".$_POST[comment][$id]."' WHERE `id` = '$id'");

					}

					myTreasuresCheckOrder($wpdb->prefix."mytreasures_images","WHERE `treasureid` = '$result01[id]'");

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
<h2><?php echo __("Activ images",$myTreasuresTextdomain); ?> (<?php echo $result01[field01]; ?>)</h2>
<form action="" method="post" enctype="multipart/form-data"><p><h3><?php echo __("Add new image:",$myTreasuresTextdomain); ?></h3><b><?php echo __("Image",$myTreasuresTextdomain); ?>:</b><br /><input type="file" name="image" size="39" class="uploadform"><br /><br /><b><?php echo __("Name / subtitle for image (optional)",$myTreasuresTextdomain); ?></b><br /><input type="text" name="title" style="width: 75%;"></p><div class="submit"><input type="submit" class="button-primary" name="doit" value=" <?php echo __("Upload new image",$myTreasuresTextdomain); ?> "></div></form>

<?php

			$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_images` WHERE `treasureid` = '$result01[id]' ORDER BY `orderid`");
			if(mysql_num_rows($query02)) {

?>

<form name="myform" action="" method="post">
<table class="widefat fixed" cellspacing="0">
<thead>
<tr class="thead">
	<th scope="col"  class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
	<th scope="col"  class="manage-column column-name" style="">&nbsp;</th>
</tr>
</thead>

<tfoot>
<tr class="thead">
	<th scope="col"  class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
	<th scope="col"  class="manage-column column-name" style="">&nbsp;</th>
</tr>
</tfoot>

<tbody id="users" class="list:user user-list">

<?php

		while($result02 = mysql_fetch_array($query02)) {

?>

<tr id='user-1' <?php if(++$i%2 == 0) { echo "class='alternate'"; } ?>>
	<th scope='row' class='check-column'><input type='checkbox' name='deletemedia[<?php echo $result02[id]; ?>]' id='user_1' value='<?php echo $result02[name]; ?>' /></th>
	<td class="username column-username"><img src="../wp-content/mytreasuresimages/small/<?php echo $result02[name]; ?>"> <input type="text" name="orderid[<?php echo $result02[id]; ?>]" value="<?php echo $result02[orderid]; ?>" style="width: 30px; text-align: center;"><br /><input type="text" name="comment[<?php echo $result02[id]; ?>]" value="<?php echo $result02[comment]; ?>" style="width: 75%;"></td>
</tr>


<?php

				}

?>

</tbody>
</table>
<div class="submit"><input type="submit" class="button-primary" name="deletemarked" value=" <?php echo __("Delete marked images",$myTreasuresTextdomain); ?> "> <input type="submit" name="saveorder" value=" <?php echo __("Save new order & comments",$myTreasuresTextdomain); ?> "></div>
</form>

<?php

			}

?>

</div>

<?php

			} else {

				include("mytreasuresoverview.php");

			}

		}

	}

?>