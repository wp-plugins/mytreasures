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

		$check_doubletitle = "0";
		$path = "../wp-content/mytreasures/";

		if(!is_writeable($path)) {

			echo '<div id="message" class="updated fade"><p><strong>'.__("Please create a folder \"mytreasures\" in \"/wp-content/\" with writings rights!",$myTreasuresTextdomain).'</strong></p></div>';

		} else {

			$checkurlfopen = ini_get('allow_url_fopen');
			if($checkurlfopen) {

				if(strlen($_POST[field01]) > 0) {

					$check_doubletitle = mysql_num_rows(mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `field01` = '".$_POST[field01]."' AND `type` = '$_POST[treasuretype]'"));

				}

				if(($check_doubletitle == "0" || $check_doubletitle != "0" && $_POST[override]) && strlen($_POST[field01]) > 0 && strlen($_POST[treasuretype]) > 0) {

					if($_FILES['image']['type'] == "image/pjpeg" || $_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/gif" || $_FILES['image']['type'] == "image/png") {

						$imagename = "ownupload_".time().".".myTreasuresGetImageType($_FILES['image']['name']);
						if($myTreasures_options[option03] == 'yes') {

							if($myTreasures_options[option04] == 'fixedheight') { $height = $myTreasures_options[option05]; $width = "0"; $resizeby = "height"; $cutimage = false; }
							if($myTreasures_options[option04] == 'fixedwidth') { $height = "0"; $width = $myTreasures_options[option06]; $resizeby = "width"; $cutimage = false; }
							if($myTreasures_options[option04] == 'fixedboth') { $height = $myTreasures_options[option07]; $width = $myTreasures_options[option08]; $resizeby = "width"; $cutimage = true; }
							if(!myTreasuresImageResize($_FILES['image']['tmp_name'],$path.$imagename,$width,$height,$resizeby,$cutimage,$myTreasures_options[option32])) {

								echo "<div id=\"message\" class=\"updated fade\"><p><strong>".__("The system had problems to save the image / cover. Please retry it!",$myTreasuresTextdomain)."</strong></p></div>";

							}

						} else {

							myTreasuresImageResize($_FILES['image']['tmp_name'],$path.$imagename,"","","","",$myTreasures_options[option32]);
							chmod($path.$imagename, 0666);

						}

						if($myTreasures_options[option14] == 'yes') {

							myTreasuresImageResize($_FILES['image']['tmp_name'],$path."big_".$imagename,"","","","",$myTreasures_options[option32]);
							chmod($path."big_".$imagename, 0666);

						}
						
					}

					mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures` (`type`, `rating`, `description`, `comment`, `tracklist`, `image`, `field01`, `field02`, `field03`, `field04`, `field05`, `field06`, `field07`, `field08`, `field09`, `field10`, `field11`, `field12`, `field13`, `field14`, `field15`, `field16`, `field17`, `field18`, `field19`, `field20`) VALUES ('$_POST[treasuretype]', '$_POST[rating]',  '".$_POST[description]."', '".$_POST[comment]."', '$tracks', '$imagename', '".$_POST[field01]."', '".$_POST[field02]."', '".$_POST[field03]."', '".$_POST[field04]."', '".$_POST[field05]."', '".$_POST[field06]."', '".$_POST[field07]."', '".$_POST[field08]."', '".$_POST[field09]."', '".$_POST[field10]."', '".$_POST[field11]."', '".$_POST[field12]."', '".$_POST[field13]."', '".$_POST[field14]."', '".$_POST[field15]."', '".$_POST[field16]."', '".$_POST[field17]."', '".$_POST[field18]."', '".$_POST[field19]."', '".$_POST[field20]."')");
					echo "<div id=\"message\" class=\"updated fade\"><p><strong>".sprintf(__("The media <i>%s</i> was created successfully!",$myTreasuresTextdomain),trim($_POST[field01]))."</strong></p></div><div class=\"wrap\"><br /><br /><a href=\"?page=mytreasures/mytreasuresofdb.php\" class=\"button\">".__("New search for movie in ofdb",$myTreasuresTextdomain)."</a>&nbsp;&nbsp;<a href=\"?page=mytreasures/mytreasuresoverview.php\" class=\"button\">".__("Overview",$myTreasuresTextdomain)."</a></div>";

				} elseif($_POST[ofdbid] && $_POST[treasuretype]) {

					$feedback = myTreasuresXML2Array("http://xml.n4rf.net/ofdbgw/movie/".urlencode($_POST[ofdbid]));
					if($feedback) {

						if($feedback[0][0][1][name] == 'rcodedesc' && $feedback[0][0][1][value] == 'Ok') {

							foreach($feedback[0][1] AS $key1 => $value1) {

								if(strlen($feedback[0][1][$key1][name]) > 1 && $feedback[0][1][$key1][name] != 'bild' && $feedback[0][1][$key1][name] != 'bewertung' && $feedback[0][1][$key1][name] != 'soundtrack' && $feedback[0][1][$key1][name] != 'produktionsland' && $feedback[0][1][$key1][name] != 'alternativ' && $feedback[0][1][$key1][name] != 'fassungen') {
									
									if($feedback[0][1][$key1][name] == 'genre') {

										$returnarray[$feedback[0][1][$key1][name]] = htmlentities($feedback[0][1][$key1][0][value],ENT_QUOTES, "UTF-8");

									} elseif($feedback[0][1][$key1][name] == 'regie') {

										if(is_array($feedback[0][1][$key1])) { foreach($feedback[0][1][$key1] AS $tmpid => $tmpvalue) { if(is_array($feedback[0][1][$key1][$tmpid])) { if($feedback[0][1][$key1][$tmpid][1][value]) { $regie .= htmlentities($feedback[0][1][$key1][$tmpid][1][value],ENT_QUOTES, "UTF-8").", "; } } } }
										if($regie) { $returnarray[$feedback[0][1][$key1][name]] = substr($regie,0,-2); }

									} elseif($feedback[0][1][$key1][name] == 'produzent') {

										if(is_array($feedback[0][1][$key1])) { foreach($feedback[0][1][$key1] AS $tmpid => $tmpvalue) { if(is_array($feedback[0][1][$key1][$tmpid])) { if($feedback[0][1][$key1][$tmpid][1][value]) { $produzent .= htmlentities($feedback[0][1][$key1][$tmpid][1][value],ENT_QUOTES, "UTF-8").", "; } } } }
										if($produzent) { $returnarray[$feedback[0][1][$key1][name]] = substr($produzent,0,-2); }

									} elseif($feedback[0][1][$key1][name] == 'besetzung') {

										if(is_array($feedback[0][1][$key1])) { foreach($feedback[0][1][$key1] AS $tmpid => $tmpvalue) { if(is_array($feedback[0][1][$key1][$tmpid])) { if($feedback[0][1][$key1][$tmpid][1][value]) { if($feedback[0][1][$key1][$tmpid][2][value]) { $addbesetzung = " (".htmlentities($feedback[0][1][$key1][$tmpid][2][value],ENT_QUOTES, "UTF-8").")"; } else { $addbesetzung = false; } $besetzung .= htmlentities($feedback[0][1][$key1][$tmpid][1][value],ENT_QUOTES, "UTF-8").$addbesetzung.", "; } } } }
										if($besetzung) { $returnarray[$feedback[0][1][$key1][name]] = substr($besetzung,0,-2); }

									} elseif($feedback[0][1][$key1][value]) {

										$returnarray[$feedback[0][1][$key1][name]] = htmlentities($feedback[0][1][$key1][value],ENT_QUOTES,"UTF-8");

									}

								}

							}

						}

					}

					if($returnarray) {

						$query_type = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '$_POST[treasuretype]'");
						$result_type = mysql_fetch_array($query_type);
						foreach($returnarray AS $name => $value) { $select .= "<option value=\"".$value."\">".$name." | ".$value."</option>"; }
						echo "<div class=\"wrap\"><h2>".sprintf(__("Add media of typ <i>%s</i>",$myTreasuresTextdomain),$result_type[name])."</h2><p>".__("Please choose the OFDB Data you want to use. All mandatory fields are marked with a <font style=\"color: #FF0000;\">*</font>",$myTreasuresTextdomain)."</p><form action=\"\" method=\"post\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"treasuretype\" value=\"".$_POST[treasuretype]."\"><input type=\"hidden\" name=\"ofdbid\" value=\"".$_POST[ofdbid]."\"><p>";
						if($_POST[doit] && !strlen($_POST[field01]) > 0) { echo "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />"; } echo $result_type["field01"]."<font style=\"color: #FF0000;\">*</font>";
						echo "<br /><select style=\"width: 90%;\" name=\"field01\"><option value=\"\">".__("Please choose",$myTreasuresTextdomain)."</option>".$select."</select>";
						if($check_doubletitle && $_POST[doit]) { echo "<br /><br /><b>".__("Important information",$myTreasuresTextdomain)."</b><br />".__("This title is already in database, continue adding?",$myTreasuresTextdomain)."<br /><input type=\"checkbox\" name=\"override\" value=\"1\"> ".__("Yes",$myTreasuresTextdomain)."<br />"; }
						for($i = 2; $i <= 20; $i++) {

							if($i < 10) { $field = "field0".$i; } else { $field = "field".$i; }
							if($result_type[$field]) {

								echo "<br /><br />".$result_type[$field]."<br /><select style=\"width: 90%;\" name=\"".$field."\"><option value=\"\">".__("Please choose",$myTreasuresTextdomain)."</option>".$select."</select>";

							}

						}
						echo "<br /><br />".__("Description",$myTreasuresTextdomain)."<br /><select style=\"width: 90%;\" name=\"description\"><option value=\"\">".__("Please choose",$myTreasuresTextdomain)."</option>".$select."</select><br /><br />".__("My comment",$myTreasuresTextdomain)."<br /><textarea name=\"comment\" style=\"height: 150px; width: 90%;\">".stripslashes($_POST[comment])."</textarea><br /><br />".__("Rating (in stars)",$myTreasuresTextdomain)."<br />".__("bad",$myTreasuresTextdomain)."&nbsp;&nbsp;";
						for($i = 0.5; $i <= 5; $i += 0.5) { ?><input type="radio" name="rating" value="<?php echo ($i*10); ?>" <?php if($_POST[rating] == ($i*10)) { echo "checked"; } ?>><?php echo number_format($i,1,",",""); ?>&nbsp;&nbsp;<?php } echo __("good",$myTreasuresTextdomain)."<br /><br />".__("Image / Cover",$myTreasuresTextdomain)."<br /><input type=\"file\" name=\"image\" size=\"39\" class=\"uploadform\"><br /><br />".__("System will take default image if no other is set",$myTreasuresTextdomain)." (default.jpg in wp-content/mytreasures/)</p><div class=\"submit\"><input type=\"submit\" name=\"doit\" class=\"button-primary\" value=\" ".__("Add media",$myTreasuresTextdomain)." \"></div></form></div>";

					}

				} elseif($_POST[ofdbid]) {

					$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` ORDER BY `name`");
					while($result01 = mysql_fetch_array($query01)) { $treasuretype .= "<br /><input type=\"radio\" name=\"treasuretype\" value=\"".$result01[id]."\"> ".$result01[name]; }
					echo "<form action=\"\" method=\"post\"><input type=\"hidden\" name=\"ofdbid\" value=\"".$_POST[ofdbid]."\"<div class=\"wrap\"><h2>".__("Choose media type",$myTreasuresTextdomain)."</h2><p>".__("Please choose which media type you want to add:",$myTreasuresTextdomain).$treasuretype."</p><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"gotodata\" value=\" ",__("Continue to details",$myTreasuresTextdomain)." \"></div></form></div>";

				} else {

				if($_POST[search]) {

					$feedback = myTreasuresXML2Array("http://xml.n4rf.net/ofdbgw/search/".urlencode($_POST[search]));
					if($feedback) {

						if($feedback[0][0][1][name] == 'rcodedesc' && $feedback[0][0][1][value] == 'Ok') {

							foreach($feedback[0][1] AS $key1 => $value1) {

								if(is_array($feedback[0][1][$key1])) {

									++$id;
									foreach($feedback[0][1][$key1] AS $key2 => $value2) {

										if($feedback[0][1][$key1][$key2][name] == 'id') { $resultarray[$id][id] = htmlentities($feedback[0][1][$key1][$key2][value],ENT_QUOTES, "UTF-8"); }
										if($feedback[0][1][$key1][$key2][name] == 'titel') { $resultarray[$id][titel] = htmlentities($feedback[0][1][$key1][$key2][value],ENT_QUOTES, "UTF-8"); }
										if($feedback[0][1][$key1][$key2][name] == 'titel_orig') { $resultarray[$id][titel_orig] = htmlentities($feedback[0][1][$key1][$key2][value],ENT_QUOTES, "UTF-8"); }
										if($feedback[0][1][$key1][$key2][name] == 'jahr') { $resultarray[$id][jahr] = htmlentities($feedback[0][1][$key1][$key2][value],ENT_QUOTES, "UTF-8"); }

									}

								}

							}

						}

					}

					if($resultarray) {

						echo "<form action=\"\" method=\"post\"><div class=\"wrap\"><h2>".__("Searchresult",$myTreasuresTextdomain).": ".$_POST[search]."</h2><table class=\"widefat fixed\" cellspacing=\"0\"><thead><tr class=\"thead\"><th scope=\"col\"  class=\"manage-column column-cb check-column\" style=\"\"></th><th scope=\"col\"  class=\"manage-column column-username\" style=\"\">".__("Titel",$myTreasuresTextdomain)."</th><th scope=\"col\"  class=\"manage-column column-name\" style=\"\">".__("Original titel",$myTreasuresTextdomain)."</th><th scope=\"col\"  class=\"manage-column column-posts num\" style=\"\">".__("Year",$myTreasuresTextdomain)."</th></tr></thead><tfoot><tr class=\"thead\"><th scope=\"col\"  class=\"manage-column column-cb check-column\" style=\"\"></th><th scope=\"col\"  class=\"manage-column column-username\" style=\"\">".__("Titel",$myTreasuresTextdomain)."</th><th scope=\"col\"  class=\"manage-column column-name\" style=\"\">".__("Original titel",$myTreasuresTextdomain)."</th><th scope=\"col\"  class=\"manage-column column-posts num\" style=\"\">".__("Year",$myTreasuresTextdomain)."</th></tr></tfoot><tbody id=\"users\" class=\"list:user user-list\">";
						foreach($resultarray AS $id => $result01) {

							$checkfolder = (strlen($result01[id])-3);
							$file = @fopen("http://img.ofdb.de/film/".substr($result01[id],0,$checkfolder)."/".$result01[id].".jpg", "r");
							if($file) { $image = "<img src=\"http://img.ofdb.de/film/".substr($result01[id],0,$checkfolder)."/".$result01[id].".jpg\"> "; } else { $image = false; }
							echo "<tr id='user-1'"; if(++$i%2 == 0) { echo " class='alternate'"; } echo " ><th scope=\"row\" class=\"check-column\"><input type=\"radio\" name=\"ofdbid\" value=\"".$result01[id]."\" /></th><td class=\"username column-username\">".$image.$result01[titel]."<br /><br /><a href=\"http://www.ofdb.de/film/".$result01[id].",".$result01[titel]."\" target=\"_blank\">OFDB Link</a></td><td class=\"name column-name\">".$result01[titel_orig]."</td><td class=\"posts column-posts num\">".$result01[jahr]."</td></tr>";

						}
						echo "</tbody></table><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"checkfields\" value=\" ".__("Add this movie to the database",$myTreasuresTextdomain)." \"></div></form></div><br /><br />";			

					} else {

						echo "<div class=\"wrap\"><h2>".__("Search for movie in ofdb",$myTreasuresTextdomain).": ".$_POST[search]."</h2><p>".__("There is no movie with this name in ofdb",$myTreasuresTextdomain)."</p></div>";

					}

				}

					echo "<form action=\"\" method=\"post\"><div class=\"wrap\"><h2>".__("Search for movie in ofdb",$myTreasuresTextdomain)."</h2><input type=\"text\" style=\"width: 70%;\" name=\"search\" /><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" value=\"".__("Search...",$myTreasuresTextdomain)."\"></div></div></form>";

				}

			} else {

				echo '<div id="message" class="updated fade"><p><strong>'.__("In order to use the OFDB Gateway your php.ini must allow external file inclusion. (PHP Setting allow_url_fopen has to be on)",$myTreasuresTextdomain).'</strong></p></div>';
		
			}

		}

	}

?>