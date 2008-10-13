<?php

	if($_POST[amazonok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'no' WHERE `id` = '1'"); myTreasuresAmazonemail("no"); $myTreasures_options[option20] = "no"; }
	if($_POST[amazonnok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'yes' WHERE `id` = '1'"); myTreasuresAmazonemail("yes"); $myTreasures_options[option20] = "yes"; }

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

		$path 			= "../wp-content/mytreasures/";
		$coverpath 	= "../wp-content/mytreasures/coverupload/";

		if(!is_writeable($path)) {

			echo '<div id="message" class="updated fade"><p><strong>'.__("Please create a folder \"mytreasures\" in \"/wp-content/\" with writings rights!",$myTreasuresTextdomain).'</strong></p></div>';

		} else {

			if($_POST[multipleimageupload]) {

				$uploadedimagescount = "0";
				foreach($_POST[multipleimageupload] AS $uploadedimage => $id) {

					if($id) {

						$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '$id'");
						$result01 = mysql_fetch_array($query01);
					
						@unlink($path.$result01[image]);
						$imagename = "ownupload_".time().".".myTreasuresGetImageType($uploadedimage);
						while(file_exists($path.$imagename)) {
							$imagename = "ownupload_".time().".".myTreasuresGetImageType($uploadedimage);
						}
						mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET `image` = '$imagename' WHERE `id` = '$result01[id]'");
						if($myTreasures_options[option03] == 'yes') {

							if($myTreasures_options[option04] == 'fixedheight') { $height = $myTreasures_options[option05]; $width = "0"; $resizeby = "height"; $cutimage = false; }
							if($myTreasures_options[option04] == 'fixedwidth') { $height = "0"; $width = $myTreasures_options[option06]; $resizeby = "width"; $cutimage = false; }
							if($myTreasures_options[option04] == 'fixedboth') { $height = $myTreasures_options[option07]; $width = $myTreasures_options[option08]; $resizeby = "width"; $cutimage = true; }
							myTreasuresImageResize($coverpath.$uploadedimage,$path.$imagename,$width,$height,$resizeby,$cutimage);

						} else {

							copy($coverpath.$uploadedimage,$path.$imagename);
							chmod($path.$imagename, 0666);

						}

						if($myTreasures_options[option14] == 'yes') {

							copy($coverpath.$uploadedimage,$path."big_".$imagename);
							chmod($path."big_".$imagename, 0666);

						}

						++$uploadedimagescount;
						@unlink($coverpath.$uploadedimage);

					}

				}
				$message = sprintf(__("You've updated %s cover!",$myTreasuresTextdomain),$uploadedimagescount);

			}

			if($_FILES['csvfile'] && $_POST[treasuretype]) {

				if(preg_match("/.csv$/",strtolower($_FILES['csvfile']['name']))) {

					$csv_content = file($_FILES['csvfile']['tmp_name']);
					if($myTreasures_options[option21]) {

						$delimiter = $myTreasures_options[option21].$myTreasures_options[option15];
						$cutfromstart = 1;

					} else {

						$delimiter = $myTreasures_options[option15];
						$cutfromstart = 0;

					}

					if(count($csv_content) > 0) {

						$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '$_POST[treasuretype]'");
						$result01 = mysql_fetch_array($query01);

						$dbarray[] = "description";
						for($i = 1; $i <= 20; $i++) {

							if($i < 10) { $i = "0".$i; }
							if($result01["field".$i]) { $dbarray[] = "field".$i; }

						}

						$maxfields = count($dbarray);
						$myTreasuresupdate = 0;
						$myTreasuresadd = 0;

						foreach($csv_content AS $value) {

							$extend_query = false;
							$insert_fields = false;
							$insert_values = false;
							$csvarray = explode($delimiter,str_replace("\r\n","",$value));

							for($i = 0; $i < $maxfields; $i++) {

								$extend_query .= "`".$dbarray[$i]."` = '".str_replace("\"\"","\"",addslashes(utf8_encode(substr($csvarray[$i],$cutfromstart))))."', ";
								$insert_fields .= "`".$dbarray[$i]."`, ";
								$insert_values .= "'".str_replace("\"\"","\"",addslashes(utf8_encode(substr($csvarray[$i],$cutfromstart))))."', ";

							}

							if(mysql_num_rows(mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `type` = '$_POST[treasuretype]' AND `field01` = '".addslashes(utf8_encode(substr($csvarray[1],$cutfromstart)))."'"))) {

								mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET ".substr($extend_query, 0, -2)." WHERE `type` = '$_POST[treasuretype]' AND `field01` = '".addslashes(utf8_encode(substr($csvarray[1],$cutfromstart)))."'");
								++$myTreasuresupdate;

							} else {

								mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures` (".$insert_fields."`type`) VALUES (".$insert_values."'".$_POST[treasuretype]."')");
								++$myTreasuresadd;

 							}

						}
						
						if($myTreasuresadd > 0 && $myTreasuresupdate > 0) {

							$message = sprintf(__("You've added %s new media and updated %s media!",$myTreasuresTextdomain),$myTreasuresadd,$myTreasuresupdate);

						} elseif($myTreasuresadd > 0 && $myTreasuresupdate < 1) {

							$message = sprintf(__("You've added %s new media!",$myTreasuresTextdomain),$myTreasuresadd);

						} elseif($myTreasuresupdate > 0 && $myTreasuresadd < 1) {

							$message = sprintf(__("You've updated %s media!",$myTreasuresTextdomain),$myTreasuresadd,$myTreasuresupdate);

						}

					} else {

						$message = __("The file doesn't provide any content",$myTreasuresTextdomain);

					}

				} else {

					$message = __("Please use a csv file with the right format!",$myTreasuresTextdomain);

				}
			
			}

			if($message) {

				echo '<div id="message" class="updated fade"><p><strong>'. $message .'</strong></p></div>';

			}

?>

<div class="wrap">
<h2>myTreasures</h2>
<form method="post" action="" ENCTYPE="multipart/form-data">
<p><h3>CSV Upload</h3><?php echo __("You can update your myTreasures database with a csv upload / import. Just use the given csv fields to create your csv file. Keep in mind, that the system just checks title / name to find entries for an update.",$myTreasuresTextdomain); ?>
<br /><br /><?php echo __("Your local csv file:",$myTreasuresTextdomain); ?> (<u><?php echo __("field delimiter:",$myTreasuresTextdomain); ?></u> <b><?php echo $myTreasures_options[option15]; ?></b> <?php if($myTreasures_options[option21]) { echo "<u>".__("text block delimiter",$myTreasuresTextdomain)."</u> <b>".$myTreasures_options[option21]."</b>"; } ?>)<br /><input type="file" name="csvfile" size="35" class="uploadform">
<br /><br /><b><?php echo __("Media type of csv file",$myTreasuresTextdomain); ?></b>
<?php $query99 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` ORDER BY `name`"); while($result99 = mysql_fetch_array($query99)) { $csv = false; for($i2 = 1; $i2 <= 20; $i2++) { if($i2 < 10) { $i2 = "0".$i2; } if($result99["field".$i2]) { if($myTreasures_options[option21]) { $description = $myTreasures_options[option21].__("Description",$myTreasuresTextdomain).$myTreasures_options[option21]; $csv .= $myTreasures_options[option15].$myTreasures_options[option21].$result99["field".$i2].$myTreasures_options[option21]; } else{ $description = __("Description",$myTreasuresTextdomain); $csv .= $myTreasures_options[option15].$result99["field".$i2]; } } } echo "<br /><input type=\"radio\" name=\"treasuretype\" value=\"".$result99[id]."\"> ".$result99[name]." (".__("csv format:",$myTreasuresTextdomain)." <i>".$description.$csv."</i>)"; } ?>
<div class="submit"><input type="submit" value=" <?php echo __("Upload csv file",$myTreasuresTextdomain); ?> "></form></div>
</p>
<br />
<p><h3>Cover Upload</h3>

<?php

	if(is_writeable($coverpath)) {

		echo __("Just upload your covers to \"wp-content/mytreasures/coverupload/\" and you can manager multiple covers in this area!",$myTreasuresTextdomain); 
			
			$coverarray = false;
			if($directoryhandler = opendir($coverpath)) { while (($file = readdir($directoryhandler)) !== false) { $filetype = myTreasuresGetImageType($file); if($filetype == 'jpeg' || $filetype == 'jpg' || $filetype == 'gif' || $filetype == 'png') { $coverarray[] = $file; } } } closedir($directoryhandler);
		if($coverarray) {

			$selectoptions = "<option value=\"0\">".__("Please choose:",$myTreasuresTextdomain)."</option>";
			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `image` = '' ORDER BY `field01`");
			while($result01 = mysql_fetch_array($query01)) { $selectoptions .= "<option value=\"".$result01[id]."\">".$result01[field01]."</option>"; }
			$selectoptions .= "<option value=\"0\">".__("Following media already have cover:",$myTreasuresTextdomain)."</option>";
			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `image` != '' ORDER BY `field01`");
			while($result01 = mysql_fetch_array($query01)) { $selectoptions .= "<option value=\"".$result01[id]."\">".$result01[field01]."</option>"; }
			echo "<form method=\"post\" action=\"\">";
			foreach($coverarray AS $image) { echo "<br /><br /><img src=\"../wp-content/mytreasures/coverupload/".$image."\"><br /><select name=\"multipleimageupload[".$image."]\" style=\"width: 200px;\">".$selectoptions."</select>"; }
			echo "<br /><br /><div class=\"submit\"><input type=\"submit\" value=\" ".__("Update media",$myTreasuresTextdomain)." \"></form></div></form>";

		}

		} else { 
			echo __("You can manage multiple covers, if you create a folder \"coverupload\" in \"wp-content/mytreasures/\" with writings rights!",$myTreasuresTextdomain);
		}

?>

</div>

<?php

		}

	}

?>