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

		if($_GET['type'] && $_GET['id']) {

			if($_GET['type'] == 'media') {

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

				if(!isset($_POST['doit'])) {

					$_POST['doit'] = false;

				}

				if(!isset($_POST['deletecover'])) {

					$_POST['deletecover'] = false;

				}

				if(!isset($_POST['rentto'])) {

					$_POST['rentto'] = false;

				}

				$message = false;
				$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '".$_GET['id']."'");
				$result01 = mysql_fetch_array($query01);

				$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '".$result01['type']."'");
				$result02 = mysql_fetch_array($query02);
			
				if($result01['id']) {

					$tracks = false;
					if($result02['feature_tracklist'] && $_POST['trackname']) {

						for($tracklist = 1; $tracklist <= $myTreasures_options['option33']; $tracklist++) {

							if($_POST['trackname'][$tracklist]) {

								$tracks .= $_POST['trackname'][$tracklist]."#L#".$_POST['tracklength'][$tracklist]."#NT#";

							}

						}

					}
			
					if($_POST['field01'] && (!$result02['feature_tracklist'] || ($result02['feature_tracklist'] && $tracks))) {

						$imagename = $result01['image'];
						if($_FILES['image']['type'] == "image/pjpeg" || $_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/gif" || $_FILES['image']['type'] == "image/png") {

							$checknewimage = myTreasuresSaveMediaCover($_FILES['image'],$imagename);
							if(!$checknewimage) {

								$message .= "<div id=\"message\" class=\"updated fade\"><p><strong>".__("The system had problems to save the image / cover. Please retry it!",$myTreasuresTextdomain)."</strong></p></div>";

							} else {

								$imagename = $checknewimage;

							}

						}

						if($_POST['deletecover']) {
						
							@unlink($myTreasuresPathArray['cover'].$result01['image']);
							@unlink($myTreasuresPathArray['cover']."big_".$result01['image']);
							$imagename = false;

						}

						mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET `rentto` = '".$_POST['rentto']."', `type` = '".$_POST['type']."', `image` = '".$imagename."', `rating` = '".$_POST['rating']."', `description` = '".$_POST['description']."', `comment` = '".$_POST['comment']."', `tracklist` = '".$tracks."', `field01` = '".$_POST['field01']."', `field02` = '".$_POST['field02']."', `field03` = '".$_POST['field03']."', `field04` = '".$_POST['field04']."', `field05` = '".$_POST['field05']."', `field06` = '".$_POST['field06']."', `field07` = '".$_POST['field07']."', `field08` = '".$_POST['field08']."', `field09` = '".$_POST['field09']."', `field10` = '".$_POST['field10']."', `field11` = '".$_POST['field11']."', `field12` = '".$_POST['field12']."', `field13` = '".$_POST['field13']."', `field14` = '".$_POST['field14']."', `field15` = '".$_POST['field15']."', `field16` = '".$_POST['field16']."', `field17` = '".$_POST['field17']."', `field18` = '".$_POST['field18']."', `field19` = '".$_POST['field19']."', `field20` = '".$_POST['field20']."' WHERE `id` = '".$result01['id']."'");
						$message .= "<p>".sprintf(__("The media <i>%s</i> was edited successfully!",$myTreasuresTextdomain),$_POST['field01'])."<br /><br />".sprintf(__("You'll be redirected in a few seconds!",$myTreasuresTextdomain))."</p></div><meta http-equiv=\"refresh\" content=\"3; URL=?page=mytreasures/mytreasuresadmin.php\">";

					} else {

						if(!$_POST['doit']) {

							$_POST = $result01;
							if($result02['feature_tracklist']) {

								$all_tracks = explode("#NT#",$result01['tracklist']);
								foreach($all_tracks AS $track) {

									list($_POST['trackname'][(++$counttracks)],$_POST['tracklength'][$counttracks]) = explode("#L#",$track);

								}

							}

						} else {

							if(!$_POST['field01']) {

								$result02['field01'] = "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />".$result02['field01'];

							}

						}

						$message = "<p>".__("Please insert your data. All mandatory fields are marked with a <font style=\"color: #FF0000;\">*</font>",$myTreasuresTextdomain)."</p><form action=\"\" method=\"post\" enctype=\"multipart/form-data\"><p>".$result02['field01']."<font style=\"color: #FF0000;\">*</font><br /><textarea style=\"height: 45px; width: 90%;\" name=\"field01\">".stripslashes($_POST['field01'])."</textarea>";
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

							$stars = false;
							if(!$_POST['rating']) {

								$checked1 = "checked=\"checked\"";

							} else {

								$checked1 = false;

							}

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

						if($result01['image']) {

							$message .= "<br />".__("Current Image / Cover",$myTreasuresTextdomain)." (".$result01['image']."):<br /><img src=\"../wp-content/mytreasures/".$result01['image']."\" /><br /><input type=\"checkbox\" name=\"deletecover\" value=\"1\"> ".__("Delete current Image / Cover",$myTreasuresTextdomain)."<br />";

						}

						$message .= "<br />".__("Image / Cover",$myTreasuresTextdomain)."<br /><input type=\"file\" name=\"image\" size=\"39\" class=\"uploadform\"><br /><br />".__("Choose media type",$myTreasuresTextdomain)."<br />".__("Please just change the media type if both types have similar / identical values for each field!",$myTreasuresTextdomain)."<br /><select name=\"type\" style=\"width: 200px;\">";
						foreach($myTreasuresMediaTypeArray AS $id => $name) {

							if($_POST['rating'] == ($i*10)) {

								$selected = "selected=\"selected\"";

							} else {

								$selected = false;

							}

							$message .= "<option value=\"".$id."\" ".$selected.">".$name."</option>";

						}
					
						$message .= "</select></p><div class=\"submit\"><input type=\"submit\" name=\"doit\" class=\"button-primary\" value=\" ".__("Edit media",$myTreasuresTextdomain)." \"></div></form>";

					}

					echo "<div class=\"wrap\"><h2>".__("Edit media",$myTreasuresTextdomain)."</h2>".$message."</div>";

				} else {

					echo "<div class=\"wrap\"><h2>Error</h2><div id=\"message\" class=\"updated fade\"><p>".__("You've used an incorrect link, please check it!",$myTreasuresTextdomain)."</p></div>";

				}

			}

			if($_GET['type'] == 'mediatype') {

				if(!isset($_POST['changefields'])) {

					$_POST['changefields'] = false;

				}

				if(!isset($_POST['name'])) {

					$_POST['name'] = false;

				}

				if(!isset($_POST['doit'])) {

					$_POST['doit'] = false;

				}

				if(!isset($_POST['feature_tracklist'])) {

					$_POST['feature_tracklist'] = false;

				}

				$message = false;
				$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '".$_GET['id']."'");
				$result01 = mysql_fetch_array($query01);
			
				if($result01['id']) {

					if($_POST['changefields'] && $_POST['change_feature1'] && $_POST['change_feature2']) { 

						$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `type` = '".$result01['id']."'");
						while($result02 = mysql_fetch_array($query02)) { 

							mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET `".$_POST['change_feature1']."` = '".$result02[$_POST['change_feature2']]."', `".$_POST['change_feature2']."` = '".$result02[$_POST['change_feature1']]."' WHERE `id` = '".$result02['id']."'"); 

						}

						mysql_query("UPDATE `".$wpdb->prefix."mytreasures_type` SET `".$_POST['change_feature1']."` = '".$result01[$_POST['change_feature2']]."', `".$_POST['change_feature2']."` = '".$result01[$_POST['change_feature1']]."' WHERE `id` = '".$result01['id']."'");
						$message .= '<div id="message" class="updated fade"><p><strong>'.sprintf(__("You've switched <i>%s</i> and <i>%s</i> successfully!",$myTreasuresTextdomain),$result01[$_POST['change_feature1']],$result01[$_POST['change_feature2']]).'</strong></p></div>';
						$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '".$_GET['id']."'");
						$result01 = mysql_fetch_array($query01);

					}
				
					if($_POST['name'] && $_POST['field01'] && $_POST['doit']) {

						if(isset($_POST['feature_sort1'])) {

							$feature_sort1 = $_POST['feature_sort1'];

						} else {

							$feature_sort1 = false;

						}

						if(isset($_POST['feature_sort2'])) {

							$feature_sort2 = $_POST['feature_sort2'];

						} else {

							$feature_sort1 = false;

						}

						if(isset($_POST['feature_sort3'])) {

							$feature_sort3 = $_POST['feature_sort3'];

						} else {

							$feature_sort1 = false;

						}

						if(isset($_POST['feature_sort4'])) {

							$feature_sort4 = $_POST['feature_sort4'];

						} else {

							$feature_sort1 = false;

						}

						if(isset($_POST['feature_sort5'])) {

							$feature_sort5 = $_POST['feature_sort5'];

						} else {

							$feature_sort1 = false;

						}

						if(!isset($_POST['feature_tracklist'])) {

							$_POST['feature_tracklist'] = "0";

						}

						for($i = 2; $i <= 20; $i++) {

							if($i < 10) {

								$field = "field0".$i;

							} else {

								$field = "field".$i;

							}

							if($_POST["public_".$field] != '1') {

								$_POST["public_".$field] = "0";

							}

							if($i < 4) {

								if(!isset($_POST["listview_".$field])) {

									$_POST["listview_".$field] = "0";

								}

							}

						}

						mysql_query("UPDATE `".$wpdb->prefix."mytreasures_type` SET `view` = '".$_POST['view']."', `feature_tracklist` = '".$_POST['feature_tracklist']."', `feature_sort1` = '".$feature_sort1."', `feature_sort2` = '".$feature_sort2."', `feature_sort3` = '".$feature_sort3."', `feature_sort4` = '".$feature_sort4."', `feature_sort5` = '".$feature_sort5."', `name` = '".$_POST['name']."', `field01` = '".$_POST['field01']."', `field02` = '".$_POST['field02']."', `field03` = '".$_POST['field03']."', `field04` = '".$_POST['field04']."', `field05` = '".$_POST['field05']."', `field06` = '".$_POST['field06']."', `field07` = '".$_POST['field07']."', `field08` = '".$_POST['field08']."', `field09` = '".$_POST['field09']."', `field10` = '".$_POST['field10']."', `field11` = '".$_POST['field11']."', `field12` = '".$_POST['field12']."', `field13` = '".$_POST['field13']."', `field14` = '".$_POST['field14']."', `field15` = '".$_POST['field15']."', `field16` = '".$_POST['field16']."', `field17` = '".$_POST['field17']."', `field18` = '".$_POST['field18']."', `field19` = '".$_POST['field19']."', `field20` = '".$_POST['field20']."', `public_field01` = '1', `public_field02` = '".$_POST['public_field02']."', `public_field03` = '".$_POST['public_field03']."', `public_field04` = '".$_POST['public_field04']."', `public_field05` = '".$_POST['public_field05']."', `public_field06` = '".$_POST['public_field06']."', `public_field07` = '".$_POST['public_field07']."', `public_field08` = '".$_POST['public_field08']."', `public_field09` = '".$_POST['public_field09']."', `public_field10` = '".$_POST['public_field10']."', `public_field11` = '".$_POST['public_field11']."', `public_field12` = '".$_POST['public_field12']."', `public_field13` = '".$_POST['public_field13']."', `public_field14` = '".$_POST['public_field14']."', `public_field15` = '".$_POST['public_field15']."', `public_field16` = '".$_POST['public_field16']."', `public_field17` = '".$_POST['public_field17']."', `public_field18` = '".$_POST['public_field18']."', `public_field19` = '".$_POST['public_field19']."', `public_field20` = '".$_POST['public_field20']."', `listview_field02` = '".$_POST['listview_field02']."', `listview_field03` = '".$_POST['listview_field03']."' WHERE `id` = '".$result01['id']."'");
						$message .= "<p>".sprintf(__("The media type <i>%s</i> was edited successfully!",$myTreasuresTextdomain),$_POST['name'])."<br /><br />".sprintf(__("You'll be redirected in a few seconds!",$myTreasuresTextdomain))."</p></div><meta http-equiv=\"refresh\" content=\"3; URL=?page=mytreasures/mytreasuresmediatype.php\">";

					} else {

						if(!$_POST['doit']) {

							$_POST = $result01;
							$_POST['doit'] = false;

						}

						if(!$_POST['field01']) {

							$_POST['field01'] = "Name / Titel";

						}

						if($_POST['feature_tracklist']) {

							$checkedarray['feature_tracklist'] = "checked=\"checked\"";

						} else {

							$checkedarray['feature_tracklist'] = "";

						}

						if(!$_POST['view']) {

							$checkedarray['view'] = "checked=\"checked\"";

						} else {

							$checkedarray['view'] = "";

						}

						if($_POST['view'] == 'list') {

							$checkedarray['list'] = "checked=\"checked\"";

						} else {

							$checkedarray['list'] = "";

						}

						if($_POST['view'] == 'rating') {

							$checkedarray['rating'] = "checked=\"checked\"";

						} else {

							$checkedarray['rating'] = "";

						}

						if($_POST['view'] == 'sort1') {

							$checkedarray['sort1'] = "checked=\"checked\"";

						} else {

							$checkedarray['sort1'] = "";

						}

						if($_POST['view'] == 'sort2') {

							$checkedarray['sort2'] = "checked=\"checked\"";

						} else {

							$checkedarray['sort2'] = "";

						}

						if($_POST['view'] == 'sort3') {

							$checkedarray['sort3'] = "checked=\"checked\"";

						} else {

							$checkedarray['sort3'] = "";

						}

						if($_POST['view'] == 'sort4') {

							$checkedarray['sort4'] = "checked=\"checked\"";

						} else {

							$checkedarray['sort4'] = "";

						}

						if($_POST['view'] == 'sort5') {

							$checkedarray['sort5'] = "checked=\"checked\"";

						} else {

							$checkedarray['sort5'] = "";

						}

						if($_POST['view'] == 'covers') {

							$checkedarray['covers'] = "checked=\"checked\"";

						} else {

							$checkedarray['covers'] = "";

						}

						if($_POST['doit'] && !$_POST['name']) {

							$error11 = "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />";

						} else {

							$error11 = false;

						}

						$selectinput1 = "<option value=\"\">".__("No view for this type",$myTreasuresTextdomain)."</option>";
						$selectinput2 = "<option value=\"\">".__("Please select fields you want to switch",$myTreasuresTextdomain)."</option>";

						$field1 = false;
						$mediafiles = false;
						for($i = 1; $i <= 20; $i++) {

							if($i == 1) { $field1 .= "<font style=\"color: #FF0000;\">*</font>"; $disabledpublicfield = "disabled=\"disabled\""; } else { $disabledpublicfield = false; }
							if($i < 10) { $field1 = __("detail",$myTreasuresTextdomain)." #0".$i; $field = "field0".$i; } else { $field1 = __("detail",$myTreasuresTextdomain)." #".$i; $field = "field".$i; }
							if($i > 1) { 
								$selectinput1 .= "<option value=\"".$field."\">".sprintf(__("Content of %s (Must have content!)",$myTreasuresTextdomain),$field1)."</option>";
								$selectinput2 .= "<option value=\"".$field."\">".sprintf(__("Content of %s",$myTreasuresTextdomain),$field1)."</option>";
							}

							if(!isset($_POST['listview_'.$field])) {

								$_POST['listview_'.$field] = false;

							}
							if($i == 1 || $_POST['listview_'.$field]) { $checked2 = "checked=\"checked\""; } else { $checked2 = false; }
							if($i == 1 || $_POST['public_'.$field]) { $checked1 = "checked=\"checked\""; } else { $checked1 = false; }
							if($i < 4) { $showlistview = "<br /><input type=\"checkbox\" name=\"listview_".$field."\" value=\"1\" ".$checked2." ".$disabledpublicfield."> ".__("show on list view",$myTreasuresTextdomain);  } else { $showlistview = false; }
							$mediafiles .= "<br /><br />".$field1."<br /><input type=\"checkbox\" name=\"public_".$field."\" value=\"1\" ".$checked1." ".$disabledpublicfield."> ".__("show on details view",$myTreasuresTextdomain).$showlistview."<br /><textarea style=\"height: 45px; width: 90%;\" name=\"".$field."\">".stripslashes($_POST[$field])."</textarea>";

						}

						$message .= "<form action=\"\" method=\"post\" enctype=\"multipart/form-data\"><p>".$error11."Name<font style=\"color: #FF0000;\">*</font><br /><textarea style=\"height: 45px; width: 90%;\" name=\"name\">".stripslashes($_POST['name'])."</textarea><br /><br />".__("<b>Information</b><br />Following fields are for your own details of this media type",$myTreasuresTextdomain).$mediafiles."<br /><br /><b>".__("Views for this media type",$myTreasuresTextdomain)."</b><br />".__("You can create own \"views\" for ech media type. Just select it here",$myTreasuresTextdomain)."<br /><br />".__("View",$myTreasuresTextdomain)." #1<br /><select name=\"feature_sort1\" style=\"width: 380px;\">".str_replace("value=\"".$_POST['feature_sort1']."\"","value=\"".$_POST['feature_sort1']."\" selected=\"selected\"",$selectinput1)."</select><br /><br />".__("View",$myTreasuresTextdomain)." #2<br /><select name=\"feature_sort2\" style=\"width: 380px;\">".str_replace("value=\"".$_POST['feature_sort2']."\"","value=\"".$_POST['feature_sort2']."\" selected=\"selected\"",$selectinput1)."</select><br /><br />".__("View",$myTreasuresTextdomain)." #3<br /><select name=\"feature_sort3\" style=\"width: 380px;\">".str_replace("value=\"".$_POST['feature_sort3']."\"","value=\"".$_POST['feature_sort3']."\" selected=\"selected\"",$selectinput1)."</select><br /><br />".__("View",$myTreasuresTextdomain)." #4<br /><select name=\"feature_sort4\" style=\"width: 380px;\">".str_replace("value=\"".$_POST['feature_sort4']."\"","value=\"".$_POST['feature_sort4']."\" selected=\"selected\"",$selectinput1)."</select><br /><br />".__("View",$myTreasuresTextdomain)." #5<br /><select name=\"feature_sort5\" style=\"width: 380px;\">".str_replace("value=\"".$_POST['feature_sort5']."\"","value=\"".$_POST['feature_sort5']."\" selected=\"selected\"",$selectinput1)."</select>";
						$message .= "<br /><br /><b>".__("Tracklist",$myTreasuresTextdomain)."</b><br /><input type=\"checkbox\" name=\"feature_tracklist\" value=\"1\" ".$checkedarray['feature_tracklist']."> ".__("This media type has a tracklist",$myTreasuresTextdomain)."<br /><br /><b>".__("Default view",$myTreasuresTextdomain)."</b><br />".__("If you want to have a default view for this media type, just choose it:",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"0\" ".$checkedarray['view']."> ".__("Use global setttings",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"list\" ".$checkedarray['list']."> ".__("Name",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"rating\" ".$checkedarray['rating']."> ".__("Ratings",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"sort1\" ".$checkedarray['sort1']."> ".__("Media type view definition #1 (If available!)",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"sort2\" ".$checkedarray['sort2']."> ".__("Media type view definition #2 (If available!)",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"sort3\" ".$checkedarray['sort3']."> ".__("Media type view definition #3 (If available!)",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"sort4\" ".$checkedarray['sort4']."> ".__("Media type view definition #4 (If available!)",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"sort5\" ".$checkedarray['sort5']."> ".__("Media type view definition #5 (If available!)",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"covers\" ".$checkedarray['covers']."> ".__("Covers",$myTreasuresTextdomain)."</p><div class=\"submit\"><input type=\"submit\" name=\"doit\" class=\"button-primary\" value=\" ".__("Edit media type",$myTreasuresTextdomain)." \"></div><p><b>".__("Switch fields",$myTreasuresTextdomain)."</b><br />".__("You can switch fields for details if you have to!",$myTreasuresTextdomain)."<br /><br />".__("Field",$myTreasuresTextdomain)." #1<br /><select name=\"change_feature1\" style=\"width: 380px;\">".$selectinput2."</select><br /><br />".__("versus",$myTreasuresTextdomain)."<br /><br />".__("Field",$myTreasuresTextdomain)." #2<br /><select name=\"change_feature2\" style=\"width: 380px;\">".$selectinput2."</select></p><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"changefields\" value=\" ".__("Switch fields",$myTreasuresTextdomain)." \"></div></form>";

					}

					echo "<div class=\"wrap\"><h2>".__("Edit media type",$myTreasuresTextdomain)."</h2>".$message."</div>";

				}

			}

		} else {

			echo "<div class=\"wrap\"><h2>Error</h2><div id=\"message\" class=\"updated fade\"><p>".__("You've used an incorrect link, please check it!",$myTreasuresTextdomain)."</p></div>";

		}

	}

?>