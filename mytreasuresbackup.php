<?php

	if($_POST[amazonok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'no' WHERE `id` = '1'");
	if($_POST[amazonnok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'yes' WHERE `id` = '1'");

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

		$path1 = "../wp-content/mytreasures/";
		$path2 = "../wp-content/mytreasuresbackup/";

		if(!is_writeable($path1) || !is_dir($path1)) {

			echo '<div id="message" class="updated fade"><p><strong>'.__("Please create a folder \"mytreasures\" in \"/wp-content/\" with writings rights!",$myTreasuresTextdomain).'</strong></p></div>';

		} elseif(!is_writeable($path2) || !is_dir($path2)) {

			echo '<div id="message" class="updated fade"><p><strong>'.__("Please create a folder \"mytreasuresbackup\" in \"/wp-content/\" with writings rights!",$myTreasuresTextdomain).'</strong></p></div>';

		} else {

			if($_POST[createbackup]) {

				$file = $path2."myTreasuresBackUp_".date("Ymd_Hi").".sql";
				if(file_exists($file)) {

					echo '<div id="message" class="updated fade"><p><strong>Diese BackUp Datei gibt es schon!</strong></p></div>';

				} else {

				$create_file = fopen($file, "x+");
				$file_line = "--\n-- myTreasures SQL Dump\n-- http://www.mytreasures.de\n--";
				$table_array = Array("wp_mytreasures", "wp_mytreasures_options", "wp_mytreasures_type");
				foreach($table_array AS $table) {

					$file_line .= "\n\nDROP TABLE IF EXISTS `".$table."`;\nCREATE TABLE IF NOT EXISTS `".$table."` (";
					$field_array = false;
					$query01 = mysql_query("SHOW COLUMNS FROM `".$table."`");
					while($result01 = mysql_fetch_array($query01)) {

						$field_array[] = $result01[0];
						if(!$result01[2]) { $null = "NOT NULL"; } else { $null = ""; }
						if($result01[3]) { $primary_key = $result01[0]; } 
						$file_line .= "`".$result01[0]."` ".$result01[1]." ".$null." ".$result01[5].", ";

					}

					$file_line .= "PRIMARY KEY  (`".$primary_key."`)) TYPE=MyISAM;\n";
					$query02 = mysql_query("SELECT * FROM `".$table."` ORDER BY `id`");
					while($result02 = mysql_fetch_array($query02)) {

						$insert_field = false;
						$insert_value = false;
						foreach($field_array AS $field) {
							$insert_field .= "`".$field."`, ";
							$insert_value .= "'".str_replace("\r\n","\\r\\n",addslashes($result02[$field]))."', ";
						}

						$file_line .= "INSERT INTO `".$table."` (".substr($insert_field,0,-2).") VALUES (".substr($insert_value,0,-2).");\n";

					}

				}

				fwrite(fopen($file, 'a'),$file_line);
				chmod($file, 0666);

				}

			}

			if($_POST[usebackup] && $_POST[backupfile]) {

				if(file_exists($path2.$_POST[backupfile])) {

					$backup = file($path2.$_POST[backupfile]);
					foreach($backup AS $line) {

						if(!preg_match("/^--/",$line) && preg_match("/;/",$line)) {
							
							$test = false;
							mysql_query($line);
							$test = mysql_error();
							if($test) {
								echo "<br /><br />Error: $test<br />Befehl: $line";
							}

						}

					}

					echo '<div id="message" class="updated fade"><p>Das BackUp wurde erfolgreich eingespielt!</p></div>';

				} else {

					echo '<div id="message" class="updated fade"><p><strong>Die BackUp die ausgew&auml;hlt wurde ist nicht auf dem Server vorhanden!</strong></p></div>';

				}

			}

 			$backupdir = opendir($path2); while(($file = readdir($backupdir)) !== false) { if(preg_match("/myTreasuresBackUp_([0-9]{8})_([0-9]{4}).sql/",$file)) { $oldbackups[] = $file; ++$backupfiles; } } closedir($backupdir);

?>

<div class="wrap">
<h2><?php echo __("Import backup",$myTreasuresTextdomain); ?></h2>
<p><?php echo __("To import a backup, there has to be a backup file in the \"mytreasuresbackup\" folder. The file format has to be \"myTreasuresBackUp_YYYYMMDD_HHMM.sql\"!",$myTreasuresTextdomain); ?></p>
<?php if($backupfiles) { ?><form action="" method="post"><p><?php echo __("You can import following backups:",$myTreasuresTextdomain); foreach($oldbackups AS $backup) { echo "<br /><input type=\"radio\" name=\"backupfile\" value=\"".$backup."\"> ".$backup.""; } ?></p><div class="submit"><input type="submit" name="usebackup" value=" <?php echo __("Import backup",$myTreasuresTextdomain); ?> "></div></form><?php } else { ?><p><?php echo __("The're no backups for import!",$myTreasuresTextdomain); ?></p><?php } ?>
<br /><br />
<h2><?php echo __("Create backup",$myTreasuresTextdomain); ?></h2>
<form action="" method="post"><p><?php echo __("Just push the button to create a backup of your running myTreasures installation. This will create a file in the \"mytreasuresbackup\" folder for a download troug ftp",$myTreasuresTextdomain); ?></p><div class="submit"><input type="submit" name="createbackup" value=" <?php echo __("Create backup",$myTreasuresTextdomain); ?> "></div></form>
</div>

<?php

		}

	}
			
?>