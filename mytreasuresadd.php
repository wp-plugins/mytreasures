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

		if(isset($_POST['treasuretype'])) {

			for($i = 1; $i <= 20; $i++) {

				if($i < 10) {

					$i = "0".$i;

				}

				if(!isset($_POST['field'.$i])) {

					$_POST['field'.$i] = false;

				}

			}

			if(!isset($_POST['description'])) {

				$_POST['description'] = false;

			}

			if(!isset($_POST['comment'])) {

				$_POST['comment'] = false;

			}

			if(!isset($_POST['rating'])) {

				$_POST['rating'] = false;

			}

			$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '$_POST[treasuretype]'");
			$result02 = mysql_fetch_array($query02);

			$tracks = false;
			if(isset($_POST['trackname'])) {

				for($tracklist = 1; $tracklist <= $myTreasures_options['option33']; $tracklist++) {

					if($_POST['trackname'][$tracklist]) {

						$tracks .= $_POST['trackname'][$tracklist]."#L#".$_POST['tracklength'][$tracklist]."#NT#";

					}

				}

			}

			if($_POST['field01'] && (!$result02['feature_tracklist'] || ($result02['feature_tracklist'] && $tracks))) {

				$imagename = false;
				if($_FILES['image']['type'] == "image/pjpeg" || $_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/gif" || $_FILES['image']['type'] == "image/png") {

					$checknewimage = myTreasuresSaveMediaCover($_FILES['image'],$imagename);
					if(!$checknewimage) {

						$message .= "<div id=\"message\" class=\"updated fade\"><p><strong>".__("The system had problems to save the image / cover. Please retry it!",$myTreasuresTextdomain)."</strong></p></div>";

					} else {

						$imagename = $checknewimage;

					}

				}

				mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures` (`type`, `rating`, `description`, `comment`, `tracklist`, `image`, `field01`, `field02`, `field03`, `field04`, `field05`, `field06`, `field07`, `field08`, `field09`, `field10`, `field11`, `field12`, `field13`, `field14`, `field15`, `field16`, `field17`, `field18`, `field19`, `field20`) VALUES ('".$_POST['treasuretype']."', '".$_POST['rating']."',  '".$_POST['description']."', '".$_POST['comment']."', '$tracks', '$imagename', '".$_POST['field01']."', '".$_POST['field02']."', '".$_POST['field03']."', '".$_POST['field04']."', '".$_POST['field05']."', '".$_POST['field06']."', '".$_POST['field07']."', '".$_POST['field08']."', '".$_POST['field09']."', '".$_POST['field10']."', '".$_POST['field11']."', '".$_POST['field12']."', '".$_POST['field13']."', '".$_POST['field14']."', '".$_POST['field15']."', '".$_POST['field16']."', '".$_POST['field17']."', '".$_POST['field18']."', '".$_POST['field19']."', '".$_POST['field20']."')");
				$message = "<p>".sprintf(__("The media <i>%s</i> was created successfully!",$myTreasuresTextdomain),trim($_POST['field01']))."</p><div class=\"submit\"><form action=\"\" method=\"post\" style=\"display: inline;\"><input type=\"submit\" class=\"button-primary\" value=\" ".__("Add media of a different media type",$myTreasuresTextdomain)." \"></form> <form action=\"\" method=\"post\" style=\"display: inline;\"><input type=\"hidden\" name=\"treasuretype\" value=\"".$_POST['treasuretype']."\"><input type=\"submit\" value=\" ".__("Add media of the same media type",$myTreasuresTextdomain)."\"></form></div>";

			} else {

				if(isset($_POST['doit']) && !$_POST['field01']) { $result02['field01'] = "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />".$result02['field01']; }
				$message = "<p>".__("Please insert your data. All mandatory fields are marked with a <font style=\"color: #FF0000;\">*</font>",$myTreasuresTextdomain)."</p><form action=\"\" method=\"post\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"treasuretype\" value=\"".$_POST['treasuretype']."\"><p>".$result02['field01']."<font style=\"color: #FF0000;\">*</font><br /><textarea style=\"height: 45px; width: 90%;\" name=\"field01\">".stripslashes($_POST['field01'])."</textarea>";
				for($i = 2; $i <= 20; $i++) {

					if($i < 10) {

						$field = "field0".$i;

					} else {

						$field = "field".$i;

					}

					if($result02[$field]) {

						$message .= "<br /><br />".$result02[$field]."<br /><textarea style=\"height: 45px; width: 90%;\" name=\"".$field."\">".stripslashes($_POST[$field])."</textarea>";

					}

				}

				if($result02['feature_tracklist']) {

					for($tracklist = 1; $tracklist <= $myTreasures_options['option33']; $tracklist++) {

						$message .= "<br /><br />".__("Track",$myTreasuresTextdomain)." #".$tracklist."<br /><textarea style=\"height: 45px; width: 90%;\" name=\"trackname[".$tracklist."]\">".stripslashes($_POST['trackname'][$tracklist])."</textarea><br />".__("Length (in minutes)",$myTreasuresTextdomain)."<br /><textarea style=\"height: 45px; width: 90%;\" name=\"tracklength[".$tracklist."]\">".stripslashes($_POST['tracklength'][$tracklist])."</textarea>";

					} 

				} else { 

					$message .= "<br /><br />".__("Description",$myTreasuresTextdomain)."<br /><textarea name=\"description\" style=\"height: 150px; width: 90%;\">".stripslashes($_POST['description'])."</textarea>";

				}

				$message .= "<br /><br />".__("My comment",$myTreasuresTextdomain)."<br /><textarea name=\"comment\" style=\"height: 150px; width: 90%;\">".stripslashes($_POST['comment'])."</textarea><br />";
				if($myTreasures_options['option29'] == 'yes') {
						
					$message .= "<br />".__("Rent to",$myTreasuresTextdomain)."<br /><textarea style=\"height: 45px; width: 90%;\" name=\"rentto\">".stripslashes($_POST['rentto'])."</textarea><br />";

				}

				if($myTreasures_options['option39'] != 'yes') {

					if(!$_POST['rating']) {

						$checked1 = "checked=\"checked\"";

					} else {

						$checked1 = false;

					}

					$stars = false;
					for($i = 0.5; $i <= 5; $i += 0.5) {

						if($_POST['rating'] == ($i*10)) {

							$checked = "checked=\"checked\"";

						} else {

							$checked = false;

						}

						$stars .= "<input type=\"radio\" name=\"rating\" value=\"".($i*10)."\" ".$checked." />".number_format($i,1,",","")."&nbsp;&nbsp;";

					}

					$message .= "<br />".__("Rating (in stars)",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"rating\" value=\"0\" ".$checked1." /> ".__("no rating",$myTreasuresTextdomain)."&nbsp;&nbsp;|&nbsp;&nbsp;".__("bad",$myTreasuresTextdomain)."&nbsp;&nbsp;".$stars.__("good",$myTreasuresTextdomain)."<br />";

				}

				$message .= "<br />".__("Image / Cover",$myTreasuresTextdomain)."<br /><input type=\"file\" name=\"image\" size=\"39\" class=\"uploadform\"><br /><br />".__("System will take default image if no other is set",$myTreasuresTextdomain)." (default.jpg in wp-content/mytreasures/)</p><div class=\"submit\"><input type=\"submit\" name=\"doit\" class=\"button-primary\" name=\"doit\" value=\" ".__("Add media",$myTreasuresTextdomain)." \"></div></form>";

			}

		} else {

			$selectmediatype = false;
			if(is_array($myTreasuresMediaTypeArray)) {

				foreach($myTreasuresMediaTypeArray AS $id => $name) {

					$selectmediatype .= "<br /><input type=\"radio\" name=\"treasuretype\" value=\"".$id."\"> ".$name;

				}

			}

			$message = "<form action=\"\" method=\"post\"><p>".__("Please choose which media type you want to add:",$myTreasuresTextdomain).$selectmediatype."</p><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" value=\" ".__("Continue to details",$myTreasuresTextdomain)." \"></div></form>";

		}

		echo "<div class=\"wrap\"><h2>".__("Add media",$myTreasuresTextdomain)."</h2>".$message."</div>";

	}

?>