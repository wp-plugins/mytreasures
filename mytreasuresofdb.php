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

		$checkurlfopen = ini_get('allow_url_fopen');
		if($checkurlfopen) {

			if(!isset($_POST['field01'])) {

				$_POST['field01'] = false;

			}

			if(!isset($_POST['search'])) {

				$_POST['search'] = false;

			}

			if(!isset($_POST['ofdbid'])) {

				$_POST['ofdbid'] = false;

			}

			if(!isset($_POST['treasuretype'])) {

				$_POST['treasuretype'] = false;

			}

			if(!isset($_POST['doit'])) {

				$_POST['doit'] = false;

			}

			if(!isset($_POST['descriptiona'])) {

				$_POST['descriptiona'] = false;

			}

			if(!isset($_POST['comment'])) {


				$_POST['comment'] = false;

			}

			if(!isset($_POST['rating'])) {

				$_POST['rating'] = "0";

			}

			for($i = 1; $i <= 20; $i++) {		

				if($i < 10) {

					$i = "0".$i;

				}

				if(!isset($_POST['field'.$i.'a'])) {

					$_POST['field'.$i.'a'] = false;

				}

				if(!isset($_POST['field'.$i])) {

					$_POST['field'.$i] = false;

				}

			}

			if($_POST['field01'] && $_POST['treasuretype']) {

				for($i = 1; $i <= 20; $i++) {

					if($i < 10) {

						$field = "field0".$i;

					} else {

						$field = "field".$i;

					}

					if($_POST[$field."a"] && !$_POST[$field]) {

						$_POST[$field] = $_POST[$field."a"];

					}

				}

				if($_POST['descriptiona'] && !$_POST['description']) {

					$_POST['description'] = $_POST['descriptiona'];

				}

				$imagename = false;
				if($_FILES['image']['type'] == "image/pjpeg" || $_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/gif" || $_FILES['image']['type'] == "image/png") {

					$checknewimage = myTreasuresSaveMediaCover($_FILES['image'],$imagename);
					if($checknewimage) {

						$imagename = $checknewimage;

					}

				}

				mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures` (`type`, `rating`, `description`, `tracklist`, `image`, `rentto`,`comment`, `field01`, `field02`, `field03`, `field04`, `field05`, `field06`, `field07`, `field08`, `field09`, `field10`, `field11`, `field12`, `field13`, `field14`, `field15`, `field16`, `field17`, `field18`, `field19`, `field20`) VALUES ('".$_POST['treasuretype']."', '".$_POST['rating']."',  '".$_POST['description']."', '', '".$imagename."', '', '".$_POST['comment']."', '".$_POST['field01']."', '".$_POST['field02']."', '".$_POST['field03']."', '".$_POST['field04']."', '".$_POST['field05']."', '".$_POST['field06']."', '".$_POST['field07']."', '".$_POST['field08']."', '".$_POST['field09']."', '".$_POST['field10']."', '".$_POST['field11']."', '".$_POST['field12']."', '".$_POST['field13']."', '".$_POST['field14']."', '".$_POST['field15']."', '".$_POST['field16']."', '".$_POST['field17']."', '".$_POST['field18']."', '".$_POST['field19']."', '".$_POST['field20']."')");
				$message = "<div id=\"message\" class=\"updated fade\"><p><strong>".sprintf(__("The media <i>%s</i> was created successfully!",$myTreasuresTextdomain),trim($_POST['field01']))."</strong></p></div><p><a href=\"?page=mytreasures/mytreasuresofdb.php\" class=\"button\">".__("New search for movie in ofdb",$myTreasuresTextdomain)."</a>&nbsp;&nbsp;<a href=\"?page=mytreasures/mytreasuresadmin.php\" class=\"button\">".__("Overview",$myTreasuresTextdomain)."</a></p>";

			} elseif($_POST['ofdbid'] && $_POST['treasuretype']) {

				$returnarray = myTreasuresXML2Array2Return(myTreasuresXML2Array("http://ofdbgw.org/movie/".urlencode($_POST['ofdbid'])));
				if($returnarray) {

					$select = false;
					$query_type = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '".$_POST['treasuretype']."'");
					$result_type = mysql_fetch_array($query_type);
					foreach($returnarray AS $name => $value) { $select .= "<option value=\"".$value."\">".$name." | ".$value."</option>"; }
					if($_POST['doit'] && !$_POST['field01']) { $result_type['field01'] = "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />".$result_type['field01']; }
					$message = "<p><b>".sprintf(__("Add media of typ <i>%s</i>",$myTreasuresTextdomain),$result_type['name'])."</b><br />".__("Please choose the OFDB Data you want to use. All mandatory fields are marked with a <font style=\"color: #FF0000;\">*</font>",$myTreasuresTextdomain)."</p><form action=\"\" method=\"post\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"treasuretype\" value=\"".$_POST['treasuretype']."\"><input type=\"hidden\" name=\"ofdbid\" value=\"".$_POST['ofdbid']."\"><p>".$result_type["field01"]."<font style=\"color: #FF0000;\">*</font>";
					$message .= "<br /><select style=\"width: 90%;\" name=\"field01\"><option value=\"\">".__("Please choose or fill in your own value",$myTreasuresTextdomain)."</option>".$select."</select><br /><textarea style=\"height: 45px; width: 90%;\" name=\"field01a\">".stripslashes($_POST['field01a'])."</textarea>";
					for($i = 2; $i <= 20; $i++) {

						if($i < 10) { $field = "field0".$i; } else { $field = "field".$i; }
						if($result_type[$field]) {

							$message .= "<br /><br />".$result_type[$field]."<br /><select style=\"width: 90%;\" name=\"".$field."\"><option value=\"\">".__("Please choose or fill in your own value",$myTreasuresTextdomain)."</option>".$select."</select><br /><textarea style=\"height: 45px; width: 90%;\" name=\"".$field."a\">".stripslashes($_POST[$field."a"])."</textarea>";

						}

					}

					$message .= "<br /><br />".__("Description",$myTreasuresTextdomain)."<br /><select style=\"width: 90%;\" name=\"description\"><option value=\"\">".__("Please choose",$myTreasuresTextdomain)."</option>".$select."</select><br /><textarea style=\"height: 150px; width: 90%;\" name=\"descriptiona\">".stripslashes($_POST['descriptiona'])."</textarea><br /><br />".__("My comment",$myTreasuresTextdomain)."<br /><textarea name=\"comment\" style=\"height: 150px; width: 90%;\">".stripslashes($_POST['comment'])."</textarea><br />";
					$stars = false;
					for($i = 0.5; $i <= 5; $i += 0.5) {

						if($_POST['rating'] == ($i*10)) {

							$checked = "checked=\"checked\"";

						} else {

							$checked = false;

						}

						$stars .= "<input type=\"radio\" name=\"rating\" value=\"".($i*10)."\" ".$checked." />".number_format($i,1,",","")."&nbsp;&nbsp;";

					}

					$message .= "<br />".__("Rating (in stars)",$myTreasuresTextdomain)."<br />".__("bad",$myTreasuresTextdomain)."&nbsp;&nbsp;".$stars.__("good",$myTreasuresTextdomain)."<br /><br />".__("Image / Cover",$myTreasuresTextdomain)."<br /><input type=\"file\" name=\"image\" size=\"39\" class=\"uploadform\"><br /><br />".__("System will take default image if no other is set",$myTreasuresTextdomain)." (default.jpg in wp-content/mytreasures/)</p><div class=\"submit\"><input type=\"submit\" name=\"doit\" class=\"button-primary\" value=\" ".__("Add media",$myTreasuresTextdomain)." \"></div></form>";

				}

			} elseif(preg_match("/^([0-9]{8,13})$/",$_POST['search']) || $_POST['ofdbid']) {

				if(preg_match("/^([0-9]{8,13})$/",$_POST['search'])) {

					$_POST['ofdbid'] = myTreasuresEAN2Moviename($_POST['search']);

				}

				$treasuretype = false;
				$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` ORDER BY `name`");
				while($result01 = mysql_fetch_array($query01)) { $treasuretype .= "<br /><input type=\"radio\" name=\"treasuretype\" value=\"".$result01['id']."\"> ".$result01['name']; }
				$message = "<form action=\"\" method=\"post\"><input type=\"hidden\" name=\"ofdbid\" value=\"".$_POST['ofdbid']."\"><p><b>".__("Choose media type",$myTreasuresTextdomain)."</b><br />".__("Please choose which media type you want to add:",$myTreasuresTextdomain).$treasuretype."</p><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"gotodata\" value=\" ".__("Continue to details",$myTreasuresTextdomain)." \"></div></form>";

			} else {

				if($_POST['search']) {

					$id = "0";
					$feedback = myTreasuresXML2Array("http://ofdbgw.org/search/".urlencode($_POST['search']));
					if($feedback) {

						if($feedback[0][0][1]['name'] == 'rcodedesc' && $feedback[0][0][1]['value'] == 'Ok') {

							foreach($feedback[0][1] AS $key1 => $value1) {

								if(is_array($feedback[0][1][$key1])) {

									++$id;
									foreach($feedback[0][1][$key1] AS $key2 => $value2) {

										if($feedback[0][1][$key1][$key2]['name'] == 'id') { $resultarray[$id]['id'] = htmlentities($feedback[0][1][$key1][$key2]['value'],ENT_QUOTES, "UTF-8"); }
										if($feedback[0][1][$key1][$key2]['name'] == 'titel') { $resultarray[$id]['titel'] = htmlentities($feedback[0][1][$key1][$key2]['value'],ENT_QUOTES, "UTF-8"); }
										if($feedback[0][1][$key1][$key2]['name'] == 'titel_orig') { $resultarray[$id]['titel_orig'] = htmlentities($feedback[0][1][$key1][$key2]['value'],ENT_QUOTES, "UTF-8"); }
										if($feedback[0][1][$key1][$key2]['name'] == 'jahr') { $resultarray[$id]['jahr'] = htmlentities($feedback[0][1][$key1][$key2]['value'],ENT_QUOTES, "UTF-8"); }

									}

								}

							}

						}

					}

					if($resultarray) {

						$message = "<form action=\"\" method=\"post\"><p><b>".__("Searchresult",$myTreasuresTextdomain).": ".$_POST['search']."</b></p><table class=\"widefat fixed\" cellspacing=\"0\"><thead><tr class=\"thead\"><th scope=\"col\"  class=\"manage-column column-cb check-column\" style=\"\"></th><th scope=\"col\"  class=\"manage-column column-username\" style=\"\">".__("Titel",$myTreasuresTextdomain)."</th><th scope=\"col\"  class=\"manage-column column-name\" style=\"\">".__("Original titel",$myTreasuresTextdomain)."</th><th scope=\"col\"  class=\"manage-column column-posts num\" style=\"\">".__("Year",$myTreasuresTextdomain)."</th></tr></thead><tfoot><tr class=\"thead\"><th scope=\"col\"  class=\"manage-column column-cb check-column\" style=\"\"></th><th scope=\"col\"  class=\"manage-column column-username\" style=\"\">".__("Titel",$myTreasuresTextdomain)."</th><th scope=\"col\"  class=\"manage-column column-name\" style=\"\">".__("Original titel",$myTreasuresTextdomain)."</th><th scope=\"col\"  class=\"manage-column column-posts num\" style=\"\">".__("Year",$myTreasuresTextdomain)."</th></tr></tfoot><tbody id=\"users\" class=\"list:user user-list\">";
						foreach($resultarray AS $id => $result01) {

							$checkfolder = (strlen($result01['id'])-3);
							$file = @fopen("http://img.ofdb.de/film/".substr($result01['id'],0,$checkfolder)."/".$result01['id'].".jpg", "r");
							if($file) { $image = "<img src=\"http://img.ofdb.de/film/".substr($result01['id'],0,$checkfolder)."/".$result01['id'].".jpg\"> "; } else { $image = false; }
							if(++$i%2 == 0) { $class = "class='alternate'"; } else { $class = false; }
							$message .= "<tr id='user-1' ".$class."><th scope=\"row\" class=\"check-column\"><input type=\"radio\" name=\"ofdbid\" value=\"".$result01['id']."\" /></th><td class=\"username column-username\">".$image.$result01['titel']."<br /><br /><a href=\"http://www.ofdb.de/film/".$result01['id'].",".$result01['titel']."\" target=\"_blank\">OFDB Link</a></td><td class=\"name column-name\">".$result01['titel_orig']."</td><td class=\"posts column-posts num\">".$result01['jahr']."</td></tr>";

						}

						$message .= "</tbody></table><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"checkfields\" value=\" ".__("Add this movie to the database",$myTreasuresTextdomain)." \"></div></form>";			

					} else {

						$message = "<p>".__("There is no movie with this name in ofdb",$myTreasuresTextdomain)."</p>";

					}

				} else {

					$message = "<form action=\"\" method=\"post\"><input type=\"text\" style=\"width: 100%;\" name=\"search\" /><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" value=\"".__("Search...",$myTreasuresTextdomain)."\"></div></form>";

				}

			}

		} else {

			echo '<div id="message" class="updated fade"><p><strong>'.__("In order to use the OFDB Gateway your php.ini must allow external file inclusion. (PHP Setting allow_url_fopen has to be on)",$myTreasuresTextdomain).'</strong></p></div>';
		
		}

		echo "<div class=\"wrap\"><h2>".__("Search for movie in ofdb",$myTreasuresTextdomain)."</h2>".$message."</div>";

	}

?>