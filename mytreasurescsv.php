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

		if(isset($_POST['multipleimageupload'])) {

			$uploadedimagescount = "0";
			foreach($_POST['multipleimageupload'] AS $uploadedimage => $id) {

				if($id) {

					$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '$id'");
					$result01 = mysql_fetch_array($query01);
					
					@unlink($myTreasuresPathArray['cover'].$result01['image']);
					$imagename = "ownupload_".time().".".myTreasuresGetImageType($uploadedimage);
					while(file_exists($myTreasuresPathArray['cover'].$imagename)) {
						$imagename = "ownupload_".time().".".myTreasuresGetImageType($uploadedimage);
					}
					mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET `image` = '$imagename' WHERE `id` = '".$result01['id']."'");
					if($myTreasures_options['option03'] == 'yes') {

						if($myTreasures_options['option04'] == 'fixedheight') { $height = $myTreasures_options['option05']; $width = "0"; $resizeby = "height"; $cutimage = false; }
						if($myTreasures_options['option04'] == 'fixedwidth') { $height = "0"; $width = $myTreasures_options['option06']; $resizeby = "width"; $cutimage = false; }
						if($myTreasures_options['option04'] == 'fixedboth') { $height = $myTreasures_options['option07']; $width = $myTreasures_options['option08']; $resizeby = "width"; $cutimage = true; }
						myTreasuresImageResize($myTreasuresPathArray['coverupload'].$uploadedimage,$myTreasuresPathArray['cover'].$imagename,$width,$height,$resizeby,$cutimage,$myTreasures_options['option32']);

					} else {

						myTreasuresImageResize($myTreasuresPathArray['coverupload'].$uploadedimage,$myTreasuresPathArray['cover'].$imagename,"","","","",$myTreasures_options['option32']);
						chmod($myTreasuresPathArray['cover'].$imagename, 0666);

					}

					if($myTreasures_options[option14] == 'yes') {

						myTreasuresImageResize($myTreasuresPathArray['coverupload'].$uploadedimage,$myTreasuresPathArray['cover']."big_".$imagename,"","","","",$myTreasures_options['option32']);
						chmod($myTreasuresPathArray['cover']."big_".$imagename, 0666);

					}

					++$uploadedimagescount;
					@unlink($myTreasuresPathArray['coverupload'].$uploadedimage);

				}

			}
			$message = sprintf(__("You've updated %s cover!",$myTreasuresTextdomain),$uploadedimagescount);

		}

		if(isset($_FILES['csvfile']['tmp_name']) && $_POST['treasuretype']) {

			if(preg_match("/.csv$/",strtolower($_FILES['csvfile']['name']))) {

				$csv_content = file($_FILES['csvfile']['tmp_name']);
				if($myTreasures_options['option21']) {

					$delimiter = $myTreasures_options['option21'].$myTreasures_options['option15'];
					$cutfromstart = 1;

				} else {

					$delimiter = $myTreasures_options['option15'];
					$cutfromstart = 0;

				}

				if(count($csv_content) > 0) {

					$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '".$_POST['treasuretype']."'");
					$result01 = mysql_fetch_array($query01);

					$dbarray[] = "description";
					for($i = 1; $i <= 20; $i++) {

						if($i < 10) {

							$i = "0".$i;

						}

						$dbarray[] = "field".$i;

					}

					$maxfields = count($dbarray);
					$myTreasuresupdate = 0;
					$myTreasuresadd = 0;

					foreach($csv_content AS $value) {

						$extend_query = false;
						$insert_fields = false;
						$insert_values = false;
						$value = str_replace("\n","",str_replace("\r","",$value));

						if(isset($_POST['convertfile']) && $_POST['convertfile']) {

							$value = utf8_encode($value);

						}

						if($cutfromstart) {

							$value = substr($value,0,-1);

						}

						$csvarray = explode($delimiter,$value);

						for($i = 0; $i < $maxfields; $i++) {

							$extend_query .= "`".$dbarray[$i]."` = '".str_replace("\"\"","\"",addslashes((substr($csvarray[$i],$cutfromstart))))."', ";
							$insert_fields .= "`".$dbarray[$i]."`, ";
							$insert_values .= "'".str_replace("\"\"","\"",addslashes((substr($csvarray[$i],$cutfromstart))))."', ";

						}

						if(mysql_num_rows(mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `type` = '".$_POST['treasuretype']."' AND `field01` = '".addslashes((substr($csvarray[1],$cutfromstart)))."'"))) {

							mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET ".substr($extend_query, 0, -2)." WHERE `type` = '".$_POST['treasuretype']."' AND `field01` = '".addslashes((substr($csvarray[1],$cutfromstart)))."'");
							++$myTreasuresupdate;

						} else {

							mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures` (".$insert_fields."`type`, `comment`, `image`, `rentto`, `tracklist`, `rating`) VALUES (".$insert_values."'".$_POST['treasuretype']."', '', '', '', '', '0')");
							++$myTreasuresadd;

						}

					}
						
					if($myTreasuresadd > 0 && $myTreasuresupdate > 0) {

						$message = sprintf(__("You've added %s new media and updated %s media!",$myTreasuresTextdomain),$myTreasuresadd,$myTreasuresupdate);

					} elseif($myTreasuresadd > 0 && $myTreasuresupdate < 1) {

						$message = sprintf(__("You've added %s new media!",$myTreasuresTextdomain),$myTreasuresadd);

					} elseif($myTreasuresupdate > 0 && $myTreasuresadd < 1) {

						$message = sprintf(__("You've updated %s media!",$myTreasuresTextdomain),$myTreasuresupdate);

					}

				} else {

					$message = __("The file doesn't provide any content",$myTreasuresTextdomain);

				}

			} else {

				$message = __("Please use a csv file with the right format!",$myTreasuresTextdomain);

			}
			
		}

		if(isset($message)) {

			echo '<div id="message" class="updated fade"><p><strong>'. $message .'</strong></p></div>';

		}

?>

<div class="wrap">
<h2>myTreasures</h2>
<form method="post" action="" ENCTYPE="multipart/form-data">
<p><h3>CSV Upload</h3><?php echo __("You can update your myTreasures database with a csv upload / import. Just use the given csv fields to create your csv file. Keep in mind, that the system just checks title / name to find entries for an update.",$myTreasuresTextdomain); ?>
<br /><br /><?php echo __("Your local csv file:",$myTreasuresTextdomain); ?> (<u><?php echo __("field delimiter:",$myTreasuresTextdomain); ?></u> <b><?php echo $myTreasures_options['option15']; ?></b> <?php if($myTreasures_options['option21']) { echo "<u>".__("text block delimiter",$myTreasuresTextdomain)."</u> <b>".$myTreasures_options['option21']."</b>"; } ?>)<br /><input type="file" name="csvfile" size="35" class="uploadform">
<br /><input type="checkbox" name="convertfile" name="1" /> <?php echo __("Convert file to UTF8",$myTreasuresTextdomain); ?>
<br /><br /><b><?php echo __("Media type of csv file",$myTreasuresTextdomain); ?></b>
<?php $query99 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` ORDER BY `name`"); while($result99 = mysql_fetch_array($query99)) { $csv = false; for($i2 = 1; $i2 <= 20; $i2++) { if($i2 < 10) { $i2 = "0".$i2; } if($result99["field".$i2]) { if($myTreasures_options['option21']) { $description = $myTreasures_options['option21'].__("Description",$myTreasuresTextdomain).$myTreasures_options['option21']; $csv .= $myTreasures_options['option15'].$myTreasures_options['option21'].$result99["field".$i2].$myTreasures_options['option21']; } else{ $description = __("Description",$myTreasuresTextdomain); $csv .= $myTreasures_options['option15'].$result99["field".$i2]; } } } echo "<br /><input type=\"radio\" name=\"treasuretype\" value=\"".$result99['id']."\"> ".$result99['name']." (".__("csv format:",$myTreasuresTextdomain)." <i>".$description.$csv."</i>)"; } ?>
<div class="submit"><input type="submit" class="button-primary" value=" <?php echo __("Upload csv file",$myTreasuresTextdomain); ?> "></form></div>
</p>
<br />
<p><h3>Cover Upload</h3>

<?php

		if(is_writeable($myTreasuresPathArray['coverupload'])) {

			echo __("Just upload your covers to \"wp-content/mytreasures/coverupload/\" and you can manager multiple covers in this area!",$myTreasuresTextdomain); 
			
			$coverarray = false;
			if($directoryhandler = opendir($myTreasuresPathArray['coverupload'])) { while (($file = readdir($directoryhandler)) !== false) { $filetype = strtolower(myTreasuresGetImageType($file)); if($filetype == 'jpeg' || $filetype == 'jpg' || $filetype == 'gif' || $filetype == 'png') { $coverarray[] = $file; } } } closedir($directoryhandler);
			if($coverarray) {

				$checkcover = "0";
				$selectoptions = "<option value=\"0\">".__("Please choose:",$myTreasuresTextdomain)."</option>";
				$query01 = mysql_query("SELECT `media`.`id`, `media`.`field01`, `mediatype`.`name` FROM `".$wpdb->prefix."mytreasures` AS `media` LEFT JOIN `".$wpdb->prefix."mytreasures_type` AS `mediatype` ON `mediatype`.`id` = `media`.`type` WHERE `media`.`image` = '' ORDER BY `media`.`field01`");
				while($result01 = mysql_fetch_array($query01)) { $selectoptions .= "<option value=\"".$result01['id']."\">".$result01['field01']."</option>"; $checkcover++; }

				if($checkcover) {

					$selectoptions .= "<option value=\"0\">".__("Following media already have cover:",$myTreasuresTextdomain)."</option>";
					$query01 = mysql_query("SELECT `media`.`id`, `media`.`field01`, `mediatype`.`name` FROM `".$wpdb->prefix."mytreasures` AS `media` LEFT JOIN `".$wpdb->prefix."mytreasures_type` AS `mediatype` ON `mediatype`.`id` = `media`.`type` WHERE `media`.`image` != '' ORDER BY `media`.`field01`");
					while($result01 = mysql_fetch_array($query01)) { $selectoptions .= "<option value=\"".$result01['id']."\">".$result01['field01']."</option>"; }

				}

				echo "<form method=\"post\" action=\"\">";
				foreach($coverarray AS $image) { echo "<br /><br /><img src=\"../wp-content/mytreasures/coverupload/".$image."\"><br /><select name=\"multipleimageupload[".$image."]\" style=\"width: 80%;\">".$selectoptions."</select>"; }
				echo "<br /><br /><div class=\"submit\"><input type=\"submit\"  class=\"button-primary\" value=\" ".__("Update media",$myTreasuresTextdomain)." \"></form></div></form>";

			}

		} else { 

			echo __("You can manage multiple covers, if you create a folder \"coverupload\" in \"wp-content/mytreasures/\" with writings rights!",$myTreasuresTextdomain);

		}

?>

</div>

<?php

	}

?>