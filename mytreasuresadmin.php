<?php

	if($_POST[amazonok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'no' WHERE `id` = '1'"); myTreasuresAmazonemail("no"); $myTreasures_options[option20] = "no"; }
	if($_POST[amazonnok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'yes' WHERE `id` = '1'"); myTreasuresAmazonemail("yes"); $myTreasures_options[option20] = "yes"; }

	if(!current_user_can('level_10')) {

		echo '<div id="message" class="updated fade"><p>'.__("<strong>Note</strong><br />You need administrator rights to use myTreasures!",$myTreasuresTextdomain).'</p></div>';
		return;

	} elseif($myTreasures_options[option25] != 'doneit') {

		include("mytreasuresinstall.php");

	} elseif($myTreasures_options[option20] != 'no' && $myTreasures_options[option20] != 'yes') {

		echo "<div class=\"wrap\"><h2>myTreasures</h2><p>".__("Dear user,<br /><br />the development of myTreasures takes up a lot of time and I offer it to you free of charge. But of course the webserver and the traffic have to paid for. If you allow this installation to post an Amazon Partner link (just a plain text link saying \"Amazon.de\" that will only be displayed in the Detail view) it would be a reward for my work. If anyone buys anything using that link I get credited 5%.<br /><br />There are no costs for you! If you'd like to contribute in another way, please have a look at the Info page.<br /><br />Would you like to activate the Amazon link and support the development of myTreasures?",$myTreasuresTextdomain)."</p><div class=\"submit\"><form action=\"\" method=\"post\" style=\"display: inline;\"><input type=\"submit\" name=\"amazonok\" value=\" ".__("Yes, please activate",$myTreasuresTextdomain)." \">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"amazonnok\" value=\" ".__("No thanks, I don't want the Amazon link",$myTreasuresTextdomain)." \"></form></div></div>";

	} else {

		$path = "../wp-content/mytreasures/";

		if(!is_writeable($path)) {

			echo '<div id="message" class="updated fade"><p><strong>'.__("Please create a folder \"mytreasures\" in \"/wp-content/\" with writings rights!",$myTreasuresTextdomain).'</strong></p></div>';

		} else {

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
<br /><br /><?php echo __("Your local csv file:",$myTreasuresTextdomain); ?> (<u><?php echo __("field delimiter:",$myTreasuresTextdomain); ?></u> <b><?php echo $myTreasures_options[option15]; ?></b> <?php if($myTreasures_options[option21]) { echo "<u>".__("text block delimiter",$myTreasuresTextdomain)."</u> <b>".$myTreasures_options[option21]."</b>"; } ?>)<br /><input type="file" name="csvfile" size="35" class="uploadform"></p>
<br /><br /><b><?php echo __("Media type of csv file",$myTreasuresTextdomain); ?></b>
<?php $query99 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` ORDER BY `name`"); while($result99 = mysql_fetch_array($query99)) { $csv = false; for($i2 = 1; $i2 <= 20; $i2++) { if($i2 < 10) { $i2 = "0".$i2; } if($result99["field".$i2]) { if($myTreasures_options[option21]) { $description = $myTreasures_options[option21].__("Description",$myTreasuresTextdomain).$myTreasures_options[option21]; $csv .= $myTreasures_options[option15].$myTreasures_options[option21].$result99["field".$i2].$myTreasures_options[option21]; } else{ $description = __("Description",$myTreasuresTextdomain); $csv .= $myTreasures_options[option15].$result99["field".$i2]; } } } echo "<br /><input type=\"radio\" name=\"treasuretype\" value=\"".$result99[id]."\"> ".$result99[name]." (".__("csv format:",$myTreasuresTextdomain)." <i>".$description.$csv."</i>)"; } ?>
<div class="submit"><input type="submit" value=" <?php echo __("Upload csv file",$myTreasuresTextdomain); ?> "></form></div>
</div>

<?php

		}

	}

?>