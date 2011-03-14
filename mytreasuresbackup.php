<?php

	$checksystem = myTreasuresCheckWorkspace(current_user_can('edit_plugins'),'backup');

	if($checksystem) {

		if(isset($checksystem['message'])) {

			echo $checksystem['message'];

		}

		if(isset($checksystem['include'])) {

			include($checksystem['include']);

		}

	} else {

		if($_POST[createbackup]) {

			$file = $myTreasuresPathArray[backup]."myTreasuresBackUp_".date("Ymd_His").".sql";
			$create_file = fopen($file, "x+");
			$file_line = "--\n-- myTreasures SQL Dump\n-- http://www.mytreasures.de\n--";
			$table_array = Array($wpdb->prefix."mytreasures", $wpdb->prefix."mytreasures_options", $wpdb->prefix."mytreasures_type", $wpdb->prefix."mytreasures_images", $wpdb->prefix."mytreasures_links", $wpdb->prefix."mytreasures_users");
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

		if($_POST[usebackup] && $_POST[backupfile]) {

			if(file_exists($myTreasuresPathArray[backup].$_POST[backupfile])) {

				$backup = file($myTreasuresPathArray[backup].$_POST[backupfile]);
				foreach($backup AS $line) {

					if(!preg_match("/^--/",$line) && preg_match("/;/",$line)) {
							
						$test = false;
						mysql_query($line);
						$test = mysql_error();
						if($test) {
							echo "<br /><br />Error: $test<br />".__("Command",$myTreasuresTextdomain).": $line";
						}

					}

				}

				echo '<div id="message" class="updated fade"><p>'.__("Backup was imported successfully!",$myTreasuresTextdomain).'</p></div>';

			} else {

				echo '<div id="message" class="updated fade"><p><strong>'.__("The selected backupfile didn't exists!",$myTreasuresTextdomain).'</strong></p></div>';

			}

		}

		$backupdir = opendir($myTreasuresPathArray[backup]); while(($file = readdir($backupdir)) !== false) { if(preg_match("/myTreasuresBackUp_([0-9]{8})_([0-9]{4,6}).sql/",$file)) { $oldbackups[] = $file; ++$backupfiles; } } closedir($backupdir);
		if($oldbackups) { foreach($oldbackups AS $backup) { $backupfilechoose .= "<br /><input type=\"radio\" name=\"backupfile\" value=\"".$backup."\"> ".$backup.""; } }
		if($backupfiles) { $message .= "<form action=\"\" method=\"post\"><p>".__("You can import following backups:",$myTreasuresTextdomain).$backupfilechoose."</p><div class=\"submit\"><input type=\"submit\" name=\"usebackup\" value=\" ".__("Import backup",$myTreasuresTextdomain)." \"></div></form>"; } else { $message .= "<p>".__("The're no backups for import!",$myTreasuresTextdomain)."</p>"; }
			
		echo "<div class=\"wrap\">
<h2>".__("Import backup",$myTreasuresTextdomain)."</h2>
<p>".__("To import a backup, there has to be a backup file in the \"mytreasuresbackup\" folder. The file format has to be \"myTreasuresBackUp_YYYYMMDD_HHMMSS.sql\"!",$myTreasuresTextdomain)."</p>
".$message."
<h2>".__("Create backup",$myTreasuresTextdomain)."</h2>
<form action=\"\" method=\"post\"><p>".__("Just push the button to create a backup of your running myTreasures installation. This will create a file in the \"mytreasuresbackup\" folder for a download troug ftp",$myTreasuresTextdomain)."</p><div class=\"submit\"><input type=\"submit\" name=\"createbackup\" value=\" ".__("Create backup",$myTreasuresTextdomain)." \"></div></form>
</div>";

	}
			
?>