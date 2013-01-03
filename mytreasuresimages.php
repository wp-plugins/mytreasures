<?php

	$checksystem = myTreasuresCheckWorkspace(current_user_can('edit_plugins'),'images');

	if($checksystem) {

		if(isset($checksystem['message'])) {

			echo $checksystem['message'];

		}

		if(isset($checksystem['include'])) {

			include($checksystem['include']);

		}

	} else {

		$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '".$_GET['id']."'");
		$result01 = mysql_fetch_array($query01);

		if($result01['id']) {

			if(!isset($_FILES['image'])) {

				$_FILES['image'] = false;

			}

			if(!isset($_POST['saveorder'])) {

				$_POST['saveorder'] = false;

			}

			if(!isset($_POST['deletemarked'])) {

				$_POST['deletemarked'] = false;

			}

			if(!isset($_POST['del'])) {

				$_POST['del'] = false;

			}

			if(!isset($_POST['dontdel'])) {

				$_POST['dontdel'] = false;

			}

			if($_FILES['image']['type'] == "image/pjpeg" || $_FILES['image']['type'] == "image/jpeg") {

				$imagename = "imageupload_".time().".".myTreasuresGetImageType($_FILES['image']['name']);
				$picdata = getimagesize($_FILES['image']['tmp_name']);

				if($picdata[0] > 100) {

					if(!myTreasuresImageResize($_FILES['image']['tmp_name'],$myTreasuresPathArray['image_small'].$imagename,"100","0","width")) {

						echo '<div id="message" class="updated fade"><p><strong>'.__("The system had problems to save the image. Please retry it!",$myTreasuresTextdomain).'</strong></p></div>';

					}

				} else {

					@copy($_FILES['image']['tmp_name'],$myTreasuresPathArray['image_small'].$imagename);
					chmod($myTreasuresPathArray['image_small'].$imagename, 0666);

				}

				@copy($_FILES['image']['tmp_name'],$myTreasuresPathArray['image_big'].$imagename);
				chmod($myTreasuresPathArray['image_big'].$imagename, 0666);
				mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_images` (`treasureid`, `name`, `comment`, `orderid`) VALUES ('".$result01['id']."', '".$imagename."', '".$_POST['title']."', '999999')");
				myTreasuresCheckOrder($wpdb->prefix."mytreasures_images","WHERE `treasureid` = '".$result01['id']."'");

			}

			if($_POST['saveorder'] && $_POST['orderid']) {

				foreach($_POST['orderid'] AS $id => $orderid) {

					mysql_query("UPDATE `".$wpdb->prefix."mytreasures_images` SET `orderid` = '".$orderid."', `comment` = '".$_POST['comment'][$id]."' WHERE `id` = '".$id."'");

				}

				myTreasuresCheckOrder($wpdb->prefix."mytreasures_images","WHERE `treasureid` = '".$result01['id']."'");

			}

			if($_POST['deletemarked'] && $_POST['deletemedia']) {

				if($_POST['del'] || $_POST['dontdel']) {

					if($_POST['del']) {

						$deleteditems = 0;
						foreach($_POST['deletemedia'] AS $id => $name) {

							$deleteditems++;
							$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_images` WHERE `id` = '".$id."'");
							$result02 = mysql_fetch_array($query02);
							mysql_query("DELETE FROM `".$wpdb->prefix."mytreasures_images` WHERE `id` = '".$result02['id']."'");
							@unlink($myTreasuresPathArray['image_small'].$result02['name']);
							@unlink($myTreasuresPathArray['image_big'].$result02['name']);

						}

						myTreasuresCheckOrder($wpdb->prefix."mytreasures_images","WHERE `treasureid` = '".$result01['id']."'");
						echo '<div id="message" class="updated fade"><p><strong>'.sprintf(__("%s image(s) deleted successfully!",$myTreasuresTextdomain),$deleteditems).'</strong></p></div>';

					}

				} else {

					$hiddenfields = false;
					foreach($_POST['deletemedia'] AS $id => $name) {

						$hiddenfields .= "<input type=\"hidden\" name=\"deletemedia[".$id."]\" value=\"".$name."\" /><img src=\"../wp-content/mytreasuresimages/small/".$name."\"> ";

					}

					echo "<div class=\"wrap\"><h2>".__("Delete media",$myTreasuresTextdomain)." (".$result01['field01'].")</h2><form action=\"\" method=\"post\"><input type=\"hidden\" name=\"deletemarked\" value=\"1\" /><p>".__("Do you want to delete the following images?",$myTreasuresTextdomain)."<br />".$hiddenfields."</p><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"del\" value=\" ".__("Yes",$myTreasuresTextdomain)." \"> <input type=\"submit\" name=\"dontdel\" value=\" ".__("No",$myTreasuresTextdomain)." \"></div></form></div><br /><br />";

				}

			}

			echo "<script type=\"text/javascript\">function markallmedia() { for(var i = 0; i < document.myform.elements.length; i++) { if(document.myform.elements[i].type == 'checkbox'){ document.myform.elements[i].checked = !(document.myform.elements[i].checked); } } document.myform.elements[0].checked = !(document.myform.elements[0].checked); }</script><div class=\"wrap\"><h2>".__("Activ images",$myTreasuresTextdomain)." (".$result01['field01'].")</h2><form action=\"\" method=\"post\" enctype=\"multipart/form-data\"><p><h3>".__("Add new image:",$myTreasuresTextdomain)."</h3><b>".__("Image",$myTreasuresTextdomain).":</b><br /><input type=\"file\" name=\"image\" size=\"39\" class=\"uploadform\"><br /><br /><b>".__("Name / subtitle for image (optional)",$myTreasuresTextdomain)."</b><br /><input type=\"text\" name=\"title\" style=\"width: 75%;\"></p><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"doit\" value=\" ".__("Upload new image",$myTreasuresTextdomain)." \"></div></form>";
			$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_images` WHERE `treasureid` = '".$result01['id']."' ORDER BY `orderid`");
			if(mysql_num_rows($query02)) {

				echo "<form name=\"myform\" action=\"\" method=\"post\"><table class=\"widefat fixed\" cellspacing=\"0\"><thead><tr class=\"thead\"><th scope=\"col\" class=\"manage-column column-cb check-column\" style=\"\"><input type=\"checkbox\" /></th><th scope=\"col\" class=\"manage-column column-name\" style=\"\">&nbsp;</th></tr></thead><tfoot><tr class=\"thead\"><th scope=\"col\" class=\"manage-column column-cb check-column\" style=\"\"><input type=\"checkbox\" /></th><th scope=\"col\" class=\"manage-column column-name\" style=\"\">&nbsp;</th></tr></tfoot><tbody id=\"users\" class=\"list:user user-list\">";
				while($result02 = mysql_fetch_array($query02)) {

					if(++$i%2 == 0) {

						$class = "class='alternate'";

					} else {

						$class = false;

					}

					echo "<tr id='user-1' ".$class."><th scope='row' class='check-column'><input type='checkbox' name='deletemedia[".$result02['id']."]' id='user_1' value='".$result02['name']."' /></th><td class=\"username column-username\"><img src=\"../wp-content/mytreasuresimages/small/".$result02['name']."\"> <input type=\"text\" name=\"orderid[".$result02['id']."]\" value=\"".$result02['orderid']."\" style=\"width: 30px; text-align: center;\"><br /><input type=\"text\" name=\"comment[".$result02['id']."]\" value=\"".$result02['comment']."\" style=\"width: 75%;\"></td></tr>";

				}

				echo "</tbody></table><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"deletemarked\" value=\" ".__("Delete marked images",$myTreasuresTextdomain)." \"> <input type=\"submit\" name=\"saveorder\" value=\" ".__("Save new order & comments",$myTreasuresTextdomain)." \"></div></form>";

			}

			echo "</div>";

		} else {

				include("mytreasuresadmin.php");

		}

	}

?>