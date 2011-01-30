<?php

	$checksystem = myTreasuresCheckWorkspace(current_user_can('edit_plugins'));

	if($checksystem) {

		if(isset($checksystem['message'])) {

			echo $checksystem['message'];

		}

		if(isset($checksystem['include'])) {

			include($checksystem['include']);

		}

	} else {

		if(!isset($_POST['deletemedia'])) {

			$_POST['deletemedia'] = false;

		}

		if(!isset($_POST['del'])) {

			$_POST['del'] = false;

		}

		if(!isset($_POST['dontdel'])) {

			$_POST['dontdel'] = false;

		}

		if($_GET['type'] && ($_POST['deletemedia'] || $_GET['id'])) {

			if($_GET['type'] == 'media') {

				if($_POST['del']) {

					$deletemedia = false;
					foreach($_POST['deletemedia'] AS $id => $name) {

						$query01 = mysql_query("SELECT `id`, `field01`, `image` FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '".$id."'");
						$result01 = mysql_fetch_array($query01);
						$query02 = mysql_query("SELECT `name` FROM `".$wpdb->prefix."mytreasures_images` WHERE `treasureid` = '".$result01['id']."'");
						while($result02 = mysql_fetch_array($query02)) {

							@unlink($myTreasuresPathArray['image_small'].$result02['name']);
							@unlink($myTreasuresPathArray['image_big'].$result02['name']);

						}

						mysql_query("DELETE FROM `".$wpdb->prefix."mytreasures_images` WHERE `treasureid` = '".$result01['id']."'");
						mysql_query("DELETE FROM `".$wpdb->prefix."mytreasures_links` WHERE `treasureid` = '".$result01['id']."'");
						mysql_query("DELETE FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '".$result01['id']."'");
						@unlink($myTreasuresPathArray['cover'].$result01['image']);
						@unlink($myTreasuresPathArray['cover']."big_".$result01['image']);
						$deletemedia .= "<br />- ".$result01['field01'];

					}

					$message = "<p>".sprintf(__("Following media was deleted successfully!",$myTreasuresTextdomain)).$deletemedia."<br /><br />".sprintf(__("You'll be redirected in a few seconds!",$myTreasuresTextdomain))."</p></div><meta http-equiv=\"refresh\" content=\"3; URL=?page=mytreasures/mytreasuresadmin.php\">";

				} elseif($_POST['dontdel']) {

					$deletemedia = false;
					foreach($_POST['deletemedia'] AS $id => $name) { 

						$deletemedia .= "<br />- ".$name; 

					}

					$message = "<p>".sprintf(__("Following media has <b>NOT</b> be deleted!",$myTreasuresTextdomain)).$deletemedia."<br /><br />".sprintf(__("You'll be redirected in a few seconds!",$myTreasuresTextdomain))."</p></div><meta http-equiv=\"refresh\" content=\"3; URL=?page=mytreasures/mytreasuresadmin.php\">";

				} else {

					$deletemedia = false;
					$extendquery = "WHERE `id` = '".$_GET['id']."'";
					if($_POST['deletemedia']) {
 
						$extendquery = "WHERE "; 
						foreach($_POST['deletemedia'] AS $id => $dummy) { 

							$extendquery .= "`id` = '".$id."' OR "; 

						}

						$extendquery = substr($extendquery,0,-4); 

					}

					$query01 = mysql_query("SELECT `id`, `field01` FROM `".$wpdb->prefix."mytreasures` ".$extendquery);
					while($result01 = mysql_fetch_array($query01)) {

						$deletemedia .= "<input type=\"hidden\" name=\"deletemedia[".$result01['id']."]\" value=\"".$result01['field01']."\" /><br />- ".$result01['field01'];

					}

					$message = "<form action=\"\" method=\"post\"><p>".__("Do you want to delete the following media?",$myTreasuresTextdomain).$deletemedia."</p><div class=\"submit\"><input type=\"submit\" name=\"del\" class=\"button-primary\" value=\" ".__("Yes",$myTreasuresTextdomain)." \"> <input type=\"submit\" name=\"dontdel\" value=\" ".__("No",$myTreasuresTextdomain)." \"></div></form>";

				}
			
				echo "<div class=\"wrap\"><h2>".__("Delete media",$myTreasuresTextdomain)."</h2>".$message."</div>";

			}

			if($_GET['type'] == 'mediatype') {

				$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '".$_GET['id']."'");
				$result01 = mysql_fetch_array($query01);
				$all_to_delete_treasures_of_this_type = mysql_num_rows(mysql_query("SELECT `id` FROM `".$wpdb->prefix."mytreasures` WHERE `type` = '".$result01['id']."'"));
				if($all_to_delete_treasures_of_this_type) {

					$formbutton = sprintf(__("The're still %s media in database with this type. Please delete them first!",$myTreasuresTextdomain),$all_to_delete_treasures_of_this_type);

				} else {

					$formbutton = "<form action=\"\" method=\"post\"><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"del\" value=\" ".__("Yes",$myTreasuresTextdomain)." \"> <input type=\"submit\" name=\"dontdel\" value=\" ".__("No",$myTreasuresTextdomain)." \"></div></form>";

				}

				if($result01['id']) {

					if($_POST['del']) {

						mysql_query("DELETE FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '".$result01['id']."'");
						$message = "<p>".sprintf(__("The media type <i>%s</i> was deleted successfully!",$myTreasuresTextdomain),$result01['name'])."<br /><br />".sprintf(__("You'll be redirected in a few seconds!",$myTreasuresTextdomain))."</p></div><meta http-equiv=\"refresh\" content=\"3; URL=?page=mytreasures/mytreasuresmediatype.php\">";

					} elseif($_POST['dontdel']) {

						$message = "<p>".sprintf(__("The media type <i>%s</i> has NOT be deleted!",$myTreasuresTextdomain),$result01['name'])."<br /><br />".sprintf(__("You'll be redirected in a few seconds!",$myTreasuresTextdomain))."</p></div><meta http-equiv=\"refresh\" content=\"3; URL=?page=mytreasures/mytreasuresmediatype.php\">";

					} else {

						$message = "<p>".sprintf(__("Do you want to delete media type <i>%s</i>?",$myTreasuresTextdomain),$result01['name'])."</p>".$formbutton;

					}
				
					echo "<div class=\"wrap\"><h2>".__("Delete media type",$myTreasuresTextdomain)."</h2>".$message."</div>";

				}

			}

		} else {

			echo "<div class=\"wrap\"><h2>Error</h2><div id=\"message\" class=\"updated fade\"><p>".__("You've used an incorrect link, please check it!",$myTreasuresTextdomain)."</p></div>";

		}

	}

?>