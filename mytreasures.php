<?php

/*
Plugin Name: myTreasures
Plugin URI: http://www.mytreasures.de
Description: Show your treasures (DVDs, Games, Cars & many more) in Wordpress
Version: 1.0.6
Author: Marcus Jaentsch
Author URI: http://www.crazyiven.de/

			**************************************************************************
			* Code starts here - NO TOUCHING! (If you don't know what you're doing!) *
			**************************************************************************
*/
	
	$myTreasutesRewriteDebug	= false;
	$myTreasuresDBVersion 		= "030";
	$myTreasuresPluginVersion = "1.0.6";
	$myTreasuresCopyRight			= "<p style=\"font-size: 10px;\"><a href=\"http://www.mytreasures.de/\" target=\"_blank\">myTreasures Plugin (v".$myTreasuresPluginVersion.")</a> by <a href=\"http://www.crazyiven.de\" target=\"_blank\">Marcus J&auml;ntsch</a></p>";
	$myTreasuresTextdomain		= "myTreasures";
	register_activation_hook( __FILE__, 'myTreasuresInstall');

	$myTreasures_query = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_options` WHERE `id` = '1'");
	$myTreasures_options = mysql_fetch_array($myTreasures_query);

	if(!$myTreasures_options[id]) { 

		mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_options` (`id`, `version`, `option01`, `option15`, `option21`) VALUES ('1', '".$myTreasuresDBVersion."', 'list', ';', '\"')");
		$myTreasures_query = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_options` WHERE `id` = '1'");
		$myTreasures_options = mysql_fetch_array($myTreasures_query);
		
	}

	if($myTreasuresDBVersion != $myTreasures_options[version]) {

		myTreasuresUpdate($myTreasures_options[version]);

	}

	$myTreasures_query = mysql_query("SELECT `id`, `short`, `name` FROM `".$wpdb->prefix."mytreasures_type` ORDER BY `name`");
	while($result = mysql_fetch_array($myTreasures_query)) { 

		$myTreasuresMediaTypeArray[$result[id]] = $result[name];
		$myTreasures_tags .= $result[short]."|";
	
	}

	$myTreasures_tags 									= substr($myTreasures_tags,0,-1);
	$myTreasuresCodeSearch[medialist]		= "/\[mytreasurelist=([,(".str_replace("-","\-",$myTreasures_tags).")]+)\]/";
	$myTreasuresCodeSearch[standalone]	= "/\[mytreasure=([0-9]+)\]/";
	$myTreasuresCodeSearch[singlemedia]	= "/\[my((".str_replace("-","\-",$myTreasures_tags).")?)treasures\]/";
	$myTreasuresSortTypes 							= "/(list|rating|covers|sort1|sort2|sort3|sort4|sort5)/";

	function myTreasuresInstall() {

		global $wpdb;
		mysql_query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."mytreasures` (`id` int(10) unsigned NOT NULL auto_increment, `type` int(5) unsigned NOT NULL default '0', `rating` int(5) unsigned NOT NULL default '0', `description` longtext NOT NULL, `comment` longtext NOT NULL, `tracklist` longtext NOT NULL, `image` varchar(255) NOT NULL default '', `rentto` varchar(255) NOT NULL default '', `field01` longtext NOT NULL, `field02` longtext NOT NULL, `field03` longtext NOT NULL, `field04` longtext NOT NULL, `field05` longtext NOT NULL, `field06` longtext NOT NULL, `field07` longtext NOT NULL, `field08` longtext NOT NULL, `field09` longtext NOT NULL, `field10` longtext NOT NULL, `field11` longtext NOT NULL, `field12` longtext NOT NULL, `field13` longtext NOT NULL, `field14` longtext NOT NULL, `field15` longtext NOT NULL, `field16` longtext NOT NULL, `field17` longtext NOT NULL, `field18` longtext NOT NULL, `field19` longtext NOT NULL, `field20` longtext NOT NULL, PRIMARY KEY  (`id`)) TYPE=MyISAM;");
		mysql_query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."mytreasures_options` (`id` char(1) NOT NULL default '', `version` varchar(10) NOT NULL default '', `changelog` varchar(20) NOT NULL default '', `option01` longtext NOT NULL, `option02` longtext NOT NULL, `option03` longtext NOT NULL, `option04` longtext NOT NULL, `option05` longtext NOT NULL, `option06` longtext NOT NULL, `option07` longtext NOT NULL, `option08` longtext NOT NULL, `option09` longtext NOT NULL, `option10` longtext NOT NULL, `option11` longtext NOT NULL, `option12` longtext NOT NULL, `option13` longtext NOT NULL, `option14` longtext NOT NULL, `option15` longtext NOT NULL, `option16` longtext NOT NULL, `option17` longtext NOT NULL, `option18` longtext NOT NULL, `option19` longtext NOT NULL, `option20` longtext NOT NULL, `option21` longtext NOT NULL, `option22` longtext NOT NULL, `option23` longtext NOT NULL, `option24` longtext NOT NULL, `option25` longtext NOT NULL, `option26` longtext NOT NULL, `option27` longtext NOT NULL, `option28` longtext NOT NULL, `option29` longtext NOT NULL, `option30` longtext NOT NULL, `option31` longtext NOT NULL, `option32` longtext NOT NULL, `option33` longtext NOT NULL, `option34` longtext NOT NULL, `option35` longtext NOT NULL, `option36` longtext NOT NULL, `option37` longtext NOT NULL, `option38` longtext NOT NULL, `option39` longtext NOT NULL, `option40` longtext NOT NULL, PRIMARY KEY  (`id`)) TYPE=MyISAM;");
		mysql_query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."mytreasures_type` ( `id` int(10) unsigned NOT NULL auto_increment, `short` varchar(10) NOT NULL default '', `view` varchar(255) NOT NULL default '', `name` varchar(255) NOT NULL default '', `feature_tracklist` enum('0','1') NOT NULL default '0', `feature_sort1` varchar(10) NOT NULL default '', `feature_sort2` varchar(10) NOT NULL default '', `feature_sort3` varchar(10) NOT NULL default '', `feature_sort4` varchar(10) NOT NULL default '', `feature_sort5` varchar(10) NOT NULL default '', `field01` varchar(255) NOT NULL default '', `field02` varchar(255) NOT NULL default '', `field03` varchar(255) NOT NULL default '', `field04` varchar(255) NOT NULL default '', `field05` varchar(255) NOT NULL default '', `field06` varchar(255) NOT NULL default '', `field07` varchar(255) NOT NULL default '', `field08` varchar(255) NOT NULL default '', `field09` varchar(255) NOT NULL default '', `field10` varchar(255) NOT NULL default '', `field11` varchar(255) NOT NULL default '', `field12` varchar(255) NOT NULL default '', `field13` varchar(255) NOT NULL default '', `field14` varchar(255) NOT NULL default '', `field15` varchar(255) NOT NULL default '', `field16` varchar(255) NOT NULL default '', `field17` varchar(255) NOT NULL default '', `field18` varchar(255) NOT NULL default '', `field19` varchar(255) NOT NULL default '', `field20` varchar(255) NOT NULL default '', PRIMARY KEY  (`id`)) TYPE=MyISAM;");
		mysql_query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."mytreasures_images` ( `id` int(10) unsigned NOT NULL auto_increment, `orderid` int(5) unsigned NOT NULL default '0', `treasureid` int(10) unsigned NOT NULL default '0', `name` varchar(255) NOT NULL default '', `comment` mediumtext NOT NULL, PRIMARY KEY  (`id`)) TYPE=MyISAM;");
		mysql_query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."mytreasures_links` (`id` int( 10 ) unsigned NOT NULL auto_increment, `treasureid` int( 10 ) unsigned NOT NULL, `link` varchar( 255 ) NOT NULL, `name` varchar( 255 ) NOT NULL, PRIMARY KEY ( `id` ))");

		if(!mysql_num_rows(mysql_query("SELECT `id` FROM `".$wpdb->prefix."mytreasures_options`"))) {

			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_options` (`id`, `version`, `option01`, `option15`, `option21`) VALUES ('1', '".$myTreasuresDBVersion."', 'list', ';', '\"')");

		}

	}

	function myTreasuresUpdate($myTreasuresVersionRightNow) {

		global $myTreasuresDBVersion, $wpdb;
		if($myTreasuresVersionRightNow == '' && $myTreasuresDBVersion >= '005') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures` ADD `type` TINYINT( 5 ) UNSIGNED DEFAULT '1' NOT NULL AFTER `id` ;");
			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_options` ADD `version` VARCHAR( 10 ) NOT NULL AFTER `id` ;");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '005' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "005";

		}

		if($myTreasuresVersionRightNow == '005' && $myTreasuresDBVersion >= '006') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures` ADD `fsk` VARCHAR( 255 ) NOT NULL AFTER `genre`, ADD `publisher` VARCHAR( 255 ) NOT NULL AFTER `fsk` ;");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '006' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "006";

		}
	
		if($myTreasuresVersionRightNow == '006' && $myTreasuresDBVersion >= '007') {

			mysql_query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."mytreasures_type` (`id` int(10) unsigned NOT NULL auto_increment, `short` varchar(10) NOT NULL default '', `name` varchar(255) NOT NULL default '', `csv` mediumtext NOT NULL, PRIMARY KEY  (`id`)) TYPE=MyISAM;");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (1, 'dvd', 'DVD', 'Titel;Regisseur;Produzent;Genre;Jahr;L&auml;nge in Min;Darsteller;Beschreibung;Bildname');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (2, 'xbox', 'XBox Spiel', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (3, 'xbox360', 'XBox 360 Spiel', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (4, 'ps', 'PlayStation 1 Spiel', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (5, 'ps2', 'PlayStation 2 Spiel', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (6, 'ps3', 'PlayStation 3 Spiel', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (7, 'wii', 'Wii Spiel', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (8, 'psp', 'PlayStation Portable Spiel', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (9, 'dc', 'Dreamcast Spiel', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (10, 'pc', 'PC Spiel', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '007' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "007";

		}

		if($myTreasuresVersionRightNow == '007' && $myTreasuresDBVersion >= '008') {

			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (11, 'nes', 'NES', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (12, 'snes', 'Super NES', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (13, 'n64', 'Nintendo 64', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (14, 'ds', 'Nintendo DS', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (15, 'gc', 'Game Cube', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (16, 'gb', 'Game Boy', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '008' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "008";

		}

		if($myTreasuresVersionRightNow == '008' && $myTreasuresDBVersion >= '009') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures` ADD `rating` VARCHAR( 2 ) NOT NULL AFTER `type`;");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '009' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "009";

		}

		if($myTreasuresVersionRightNow == '009' && $myTreasuresDBVersion >= '010') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures` ADD `comment` LONGTEXT NOT NULL AFTER `description` ;");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '010' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "010";

		}

		if($myTreasuresVersionRightNow == '010' && $myTreasuresDBVersion >= '011') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures` ADD `tracklist` LONGTEXT NOT NULL AFTER `comment` ;");
			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures` ADD `actor` VARCHAR( 255 ) NOT NULL AFTER `title` ;");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (17, 'mcd', 'Musik CD', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '011' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "011";

		}

		if($myTreasuresVersionRightNow == '011' && $myTreasuresDBVersion >= '011') {

			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` VALUES (18, 'misc', 'Verschiedenes / Eigene Liste', 'Folgt noch - Felder nicht sicher!');");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '012' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "012";

		}

		if($myTreasuresVersionRightNow == '012' && $myTreasuresDBVersion >= '012') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_type` ADD `field01` VARCHAR( 255 ) NOT NULL, ADD `field02` VARCHAR( 255 ) NOT NULL, ADD `field03` VARCHAR( 255 ) NOT NULL, ADD `field04` VARCHAR( 255 ) NOT NULL, ADD `field05` VARCHAR( 255 ) NOT NULL, ADD `field06` VARCHAR( 255 ) NOT NULL, ADD `field07` VARCHAR( 255 ) NOT NULL, ADD `field08` VARCHAR( 255 ) NOT NULL, ADD `field09` VARCHAR( 255 ) NOT NULL, ADD `field10` VARCHAR( 255 ) NOT NULL, ADD `field11` VARCHAR( 255 ) NOT NULL, ADD `field12` VARCHAR( 255 ) NOT NULL, ADD `field13` VARCHAR( 255 ) NOT NULL, ADD `field14` VARCHAR( 255 ) NOT NULL, ADD `field15` VARCHAR( 255 ) NOT NULL, ADD `field16` VARCHAR( 255 ) NOT NULL, ADD `field17` VARCHAR( 255 ) NOT NULL, ADD `field18` VARCHAR( 255 ) NOT NULL, ADD `field19` VARCHAR( 255 ) NOT NULL, ADD `field20` VARCHAR( 255 ) NOT NULL");
			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_type` DROP `csv`");
			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_type` ADD `feature_tracklist` ENUM( '0', '1' ) DEFAULT '0' NOT NULL AFTER `name`");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_type` SET `field01` = 'Titel / Name'");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_type` SET `field02` = 'Schauspieler', `field03` = 'Regisseur', `field04` = 'Produzent', `field05` = 'Genre', `field06` = 'FSK / Jugendfreigabe', `field07` = 'Jahr', `field08` = 'L&auml;nge in Minuten' WHERE `id` = '1'");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_type` SET `field02` = 'Genre', `field03` = 'Jahr', `field04` = 'FSK / Jugendfreigabe', `field05` = 'Publisher' WHERE `id` < '17' AND `id` > '1'");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_type` SET `field02` = 'Genre', `field03` = 'Jahr', `feature_tracklist` = '1' WHERE `id` = '17'");
			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures` ADD `field01` LONGTEXT NOT NULL AFTER `image`, ADD `field02` LONGTEXT NOT NULL AFTER `field01`, ADD `field03` LONGTEXT NOT NULL AFTER `field02`, ADD `field04` LONGTEXT NOT NULL AFTER `field03`, ADD `field05` LONGTEXT NOT NULL AFTER `field04`, ADD `field06` LONGTEXT NOT NULL AFTER `field05`, ADD `field07` LONGTEXT NOT NULL AFTER `field06`, ADD `field08` LONGTEXT NOT NULL AFTER `field07`, ADD `field09` LONGTEXT NOT NULL AFTER `field08`, ADD `field10` LONGTEXT NOT NULL AFTER `field09`, ADD `field11` LONGTEXT NOT NULL AFTER `field10`, ADD `field12` LONGTEXT NOT NULL AFTER `field11`, ADD `field13` LONGTEXT NOT NULL AFTER `field12`, ADD `field14` LONGTEXT NOT NULL AFTER `field13`, ADD `field15` LONGTEXT NOT NULL AFTER `field14`, ADD `field16` LONGTEXT NOT NULL AFTER `field15`, ADD `field17` LONGTEXT NOT NULL AFTER `field16`, ADD `field18` LONGTEXT NOT NULL AFTER `field17`, ADD `field19` LONGTEXT NOT NULL AFTER `field18`, ADD `field20` LONGTEXT NOT NULL AFTER `field19`");
			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` ORDER BY `id`");
			while($result01 = mysql_fetch_array($query01)) {

				if($result01[type] == '1') {

					mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET `field01` = '".addslashes($result01[title])."', `field02` = '".addslashes($result01[cast])."', `field03` = '".addslashes($result01[director])."', `field04` = '".addslashes($result01[producer])."', `field05` = '".addslashes($result01[genre])."', `field06` = '".addslashes($result01[fsk])."', `field07` = '".addslashes($result01[year])."', `field08` = '".addslashes($result01[lenght])."' WHERE `id` = '$result01[id]'");

				}

				if($result01[type] > '1' && $result01[type] < '17') {

					mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET `field01` = '".addslashes($result01[title])."', `field02` = '".addslashes($result01[genre])."', `field03` = '".addslashes($result01[year])."', `field04` = '".addslashes($result01[fsk])."', `field05` = '".addslashes($result01[publisher])."' WHERE `id` = '$result01[id]'");

				}

				if($result01[type] == '17') {

					mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET `field01` = '".addslashes(trim($result01[actor]." - ".$result01[title]))."', `field02` = '".addslashes($result01[genre])."', `field03` = '".addslashes($result01[year])."', `field04` = '".addslashes($result01[fsk])."' WHERE `id` = '$result01[id]'");

				}

				if($result01[type] == '18') {

					mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET `field01` = '".addslashes($result01[title])."' WHERE `id` = '$result01[id]'");

				}
	
			}
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '013', `option09` = '', `option15` = '', `option17` = '', `option18` = '' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "013";

		}

		if($myTreasuresVersionRightNow == '013' && $myTreasuresDBVersion >= '013') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_type` ADD `feature_sort1` VARCHAR( 10 ) NOT NULL AFTER `feature_tracklist`, ADD `feature_sort2` VARCHAR( 10 ) NOT NULL AFTER `feature_sort1`, ADD `feature_sort3` VARCHAR( 10 ) NOT NULL AFTER `feature_sort2`, ADD `feature_sort4` VARCHAR( 10 ) NOT NULL AFTER `feature_sort3`, ADD `feature_sort5` VARCHAR( 10 ) NOT NULL AFTER `feature_sort4`");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '014', `option09` = '0' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "014";

		}

		if($myTreasuresVersionRightNow == '014' && $myTreasuresDBVersion >= '014') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures` DROP `title`, DROP `actor`, DROP `director`, DROP `producer`, DROP `genre`, DROP `fsk`, DROP `publisher`, DROP `year`, DROP `length`, DROP `cast`");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '015', `option20` = '0' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "015";

		}

		if($myTreasuresVersionRightNow == '015' && $myTreasuresDBVersion >= '015') {

			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '016', `option20` = '0' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "016";

		}

		if($myTreasuresVersionRightNow == '016' && $myTreasuresDBVersion >= '017') {

			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '017', `option15` = ';' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "017";

		}

		if($myTreasuresVersionRightNow == '017' && $myTreasuresDBVersion >= '018') {

			mysql_query("CREATE TABLE `".$wpdb->prefix."mytreasures_images` (`id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT, `treasureid` INT( 10 ) UNSIGNED NOT NULL, `name` VARCHAR( 255 ) NOT NULL, `comment` MEDIUMTEXT NOT NULL, PRIMARY KEY ( `id` ));");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '018', `option17` = '' , `option18` = ''  WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "018";

		}

		if($myTreasuresVersionRightNow == '018' && $myTreasuresDBVersion >= '019') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_type` ADD `view` VARCHAR( 255 ) NOT NULL AFTER `short`");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '019'  WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "019";

		}

		if($myTreasuresVersionRightNow == '019' && $myTreasuresDBVersion >= '020') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_images` ADD `orderid` INT( 5 ) UNSIGNED NOT NULL AFTER `id` ;");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '020'  WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "020";

		}	

		if($myTreasuresVersionRightNow == '020' && $myTreasuresDBVersion >= '021') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_options` ADD `option21` VARCHAR( 255 ) NOT NULL AFTER `option20`, ADD `option22` VARCHAR( 255 ) NOT NULL AFTER `option21`, ADD `option23` VARCHAR( 255 ) NOT NULL AFTER `option22`, ADD `option24` VARCHAR( 255 ) NOT NULL AFTER `option23`, ADD `option25` VARCHAR( 255 ) NOT NULL AFTER `option24`;");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '021', `option21` = '\"'  WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "021";

		}

		if($myTreasuresVersionRightNow == '021' && $myTreasuresDBVersion >= '022') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_options` CHANGE `option01` `option01` LONGTEXT NOT NULL, CHANGE `option02` `option02` LONGTEXT NOT NULL, CHANGE `option03` `option03` LONGTEXT NOT NULL, CHANGE `option04` `option04` LONGTEXT NOT NULL, CHANGE `option05` `option05` LONGTEXT NOT NULL, CHANGE `option06` `option06` LONGTEXT NOT NULL, CHANGE `option07` `option07` LONGTEXT NOT NULL, CHANGE `option08` `option08` LONGTEXT NOT NULL, CHANGE `option09` `option09` LONGTEXT NOT NULL, CHANGE `option10` `option10` LONGTEXT NOT NULL, CHANGE `option11` `option11` LONGTEXT NOT NULL, CHANGE `option12` `option12` LONGTEXT NOT NULL, CHANGE `option13` `option13` LONGTEXT NOT NULL, CHANGE `option14` `option14` LONGTEXT NOT NULL, CHANGE `option15` `option15` LONGTEXT NOT NULL, CHANGE `option16` `option16` LONGTEXT NOT NULL, CHANGE `option17` `option17` LONGTEXT NOT NULL, CHANGE `option18` `option18` LONGTEXT NOT NULL, CHANGE `option19` `option19` LONGTEXT NOT NULL, CHANGE `option20` `option20` LONGTEXT NOT NULL, CHANGE `option21` `option21` LONGTEXT NOT NULL, CHANGE `option22` `option22` LONGTEXT NOT NULL, CHANGE `option23` `option23` LONGTEXT NOT NULL, CHANGE `option24` `option24` LONGTEXT NOT NULL, CHANGE `option25` `option25` LONGTEXT NOT NULL");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '022' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "022";

		}

		if($myTreasuresVersionRightNow == '022' && $myTreasuresDBVersion >= '023') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_options` ADD `option26` LONGTEXT NOT NULL AFTER `option25`, ADD `option27` LONGTEXT NOT NULL AFTER `option26`, ADD `option28` LONGTEXT NOT NULL AFTER `option27`, ADD `option29` LONGTEXT NOT NULL AFTER `option28`, ADD `option30` LONGTEXT NOT NULL AFTER `option29`");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '023' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "023";

		}

		if($myTreasuresVersionRightNow == '023' && $myTreasuresDBVersion >= '024') {

			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option14` = '', `version` = '0243' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "024";

		}

		if(($myTreasuresVersionRightNow == '024' || $myTreasuresVersionRightNow == '0243') && $myTreasuresDBVersion >= '025') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures` CHANGE `type` `type` INT( 5 ) UNSIGNED NOT NULL DEFAULT '0', CHANGE `rating` `rating` INT( 5 ) UNSIGNED NOT NULL DEFAULT '0'");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '025' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "025";

		}

		if($myTreasuresVersionRightNow == '025' && $myTreasuresDBVersion >= '026') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures` ADD `rentto` VARCHAR( 255 ) NOT NULL AFTER `image`");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '026' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "026";

		}

		if($myTreasuresVersionRightNow == '026' && $myTreasuresDBVersion >= '027') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_options` ADD `option31` LONGTEXT NOT NULL AFTER `option30`, ADD `option32` LONGTEXT NOT NULL AFTER `option31`, ADD `option33` LONGTEXT NOT NULL AFTER `option32`, ADD `option34` LONGTEXT NOT NULL AFTER `option33`, ADD `option35` LONGTEXT NOT NULL AFTER `option34`, ADD `option36` LONGTEXT NOT NULL AFTER `option35`, ADD `option37` LONGTEXT NOT NULL AFTER `option36`, ADD `option38` LONGTEXT NOT NULL AFTER `option37`, ADD `option39` LONGTEXT NOT NULL AFTER `option38`, ADD `option40` LONGTEXT NOT NULL AFTER `option39`");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '027' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "027";

		}

		if($myTreasuresVersionRightNow == '027' && $myTreasuresDBVersion >= '028') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_options` ADD `option31` LONGTEXT NOT NULL AFTER `option30`, ADD `option32` LONGTEXT NOT NULL AFTER `option31`, ADD `option33` LONGTEXT NOT NULL AFTER `option32`, ADD `option34` LONGTEXT NOT NULL AFTER `option33`, ADD `option35` LONGTEXT NOT NULL AFTER `option34`, ADD `option36` LONGTEXT NOT NULL AFTER `option35`, ADD `option37` LONGTEXT NOT NULL AFTER `option36`, ADD `option38` LONGTEXT NOT NULL AFTER `option37`, ADD `option39` LONGTEXT NOT NULL AFTER `option38`, ADD `option40` LONGTEXT NOT NULL AFTER `option39`");
			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_options` ADD `changelog`  VARCHAR( 1 ) NOT NULL AFTER `version`");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '028' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "028";

		}

		if($myTreasuresVersionRightNow == '028' && $myTreasuresDBVersion >= '029') {

			mysql_query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."mytreasures_links` (`id` int( 10 ) unsigned NOT NULL auto_increment, `treasureid` int( 10 ) unsigned NOT NULL, `link` varchar( 255 ) NOT NULL, `name` varchar( 255 ) NOT NULL, PRIMARY KEY ( `id` ))");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '029', `changelog` = '0' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "029";

		}

		if($myTreasuresVersionRightNow == '029' && $myTreasuresDBVersion >= '030') {

			mysql_query("ALTER TABLE `".$wpdb->prefix."mytreasures_options` CHANGE `changelog` `changelog` CHAR( 20 ) NOT NULL");
			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `version` = '030' WHERE `id` = '1'");
			$myTreasuresVersionRightNow = "029";

		}

	}

	function myTreasures($content) {

		global $myTreasuresCodeSearch, $wp_query;

		if(preg_match($myTreasuresCodeSearch[singlemedia],$content,$return)) {

				return preg_replace($myTreasuresCodeSearch[singlemedia],showmyTreasuresContainer($wp_query->query_vars['mytreasureid'],$wp_query->query_vars['mytreasuresort'],$wp_query->query_vars['mytreasureglossar'],$return[1]),$content);

		} elseif(preg_match($myTreasuresCodeSearch[medialist],$content,$return)) {

				return preg_replace($myTreasuresCodeSearch[medialist],showmyTreasuresContainer($wp_query->query_vars['mytreasureid'],$wp_query->query_vars['mytreasuresort'],$wp_query->query_vars['mytreasureglossar'],$return[1]),$content);

		} elseif(preg_match($myTreasuresCodeSearch[standalone],$content,$return)) {

				return preg_replace($myTreasuresCodeSearch[standalone],showmyTreasuresContainer($return[1],$wp_query->query_vars['mytreasuresort'],"single"),$content);

		} else {

			return $content;

		}

	}

	function myTresuresBuildLink($value, $type, $name = false) {

		global $wp_rewrite;
		$myTreasuresLinkBasepath = get_permalink();

		if(substr($myTreasuresLinkBasepath, -1) != '/') {

			$myTreasuresLinkBasepath .= "/";

		}

		if($wp_rewrite->using_permalinks() && get_post_type() == 'page') {

			if($name && $value) {

				return clean_url($myTreasuresLinkBasepath.$value."/".$name."/");

			} elseif($value != '') {

				return $myTreasuresLinkBasepath.$value."/";

			} else {

				return $myTreasuresLinkBasepath;

			}

		} elseif($wp_rewrite->using_permalinks() && get_post_type() != 'page') {

			$myTreasuresLinkBasepath = get_permalink();
			if($type == "mytreasuresort") {

				return $myTreasuresLinkBasepath."?mytreasuresort=".$value;

			} elseif($type == "mytreasureid") {

				return $myTreasuresLinkBasepath."?mytreasureid=".$value;

			} elseif($type == "glossarview") {

				return $myTreasuresLinkBasepath."?mytreasuresort=list&mytreasureglossar=".strtolower($value);

			}

		} else {

			$myTreasuresLinkBasepath = get_permalink();
			if($type == "mytreasuresort") {

				return $myTreasuresLinkBasepath."&mytreasuresort=".$value;

			} elseif($type == "mytreasureid") {

				return $myTreasuresLinkBasepath."&mytreasureid=".$value;

			} elseif($type == "glossarview") {

				return $myTreasuresLinkBasepath."&mytreasuresort=list&mytreasureglossar=".strtolower($value);

			}

		}

	}

	function showmyTreasuresContainer($myTreasuredID = false, $myTreasuredSort = false, $myTreasureGlossar = false, $myTreasureType = false) {

		global $myTreasuresSortTypes, $myTreasures_options, $myTreasuresCopyRight;

		if(!preg_match($myTreasuresSortTypes,$myTreasuredSort) && !$myTreasuredID) {

			$myTreasuredSort = myTreasuresDefaultview($myTreasureType);

		}
		
		if(get_post_type() == 'page') {

			$myTreasuresShowHeader = true;

		} else {

			$myTreasuresShowHeader = false;

		}		

		/* Header generieren */
	
			$returncode = "";	
			if($myTreasuresShowHeader) {

				$returncode .= showmyTreasuresHeader($myTreasuredID,$myTreasureType, $myTreasuredSort);

			}

		/* Content generieren */
		
			$returncode .= showmyTreasuresContent($myTreasuredID, $myTreasuredSort, $myTreasureType, $myTreasureGlossar);

		/* Footer generieren */

			$returncode .= $myTreasuresCopyRight;

		return $returncode;

	}

	function showmyTreasuresHeader($myTreasuredID = false, $myTreasureType = false, $myTreasuredSort = false) {

		global $myTreasures_options, $myTreasuresTextdomain, $wpdb;

		$header = "<p><b>".__("Choose view",$myTreasuresTextdomain).":</b><br /><a href=\"".myTresuresBuildLink("list","mytreasuresort")."\">".__("Name",$myTreasuresTextdomain)."</a>";

		if(preg_match("/,/",$myTreasureType)) {

			$tmp_mediatype = explode(",",$myTreasureType);
			foreach($tmp_mediatype AS $value) {

				if($myTreasures_options[option22]) { $cover 	= " - <a href=\"".myTresuresBuildLink("covers","mytreasuresort")."\">".__("Covers",$myTreasuresTextdomain)."</a>"; }
				if($myTreasures_options[option23]) { $rating 	= " - <a href=\"".myTresuresBuildLink("rating","mytreasuresort")."\">".__("Ratings",$myTreasuresTextdomain)."</a>"; }

			}

		} else {

			if($myTreasuredID) {

				$query01 = mysql_query("SELECT `type` FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '$myTreasuredID'");
				$result01 = mysql_fetch_array($query01);

				$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '$result01[type]'");
				
			} else {

				$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `short` = '$myTreasureType'");

			}
		
			$result02 = mysql_fetch_array($query02);

			if((!preg_match("/;".$result02[id].";/",$myTreasures_options[option10]) && $result02[id]) || (!preg_match("/;allmedia;/",$myTreasures_options[option10]) && !$result02[id])) { $cover = " - <a href=\"".myTresuresBuildLink("covers","mytreasuresort")."\">".__("Covers",$myTreasuresTextdomain)."</a>"; }
			if((!preg_match("/;".$result02[id].";/",$myTreasures_options[option19]) && $result02[id]) || (!preg_match("/;allmedia;/",$myTreasures_options[option19]) && !$result02[id])) { $rating = " - <a href=\"".myTresuresBuildLink("rating","mytreasuresort")."\">".__("Ratings",$myTreasuresTextdomain)."</a>"; }
			if($result02[feature_sort1] && $result02[$result02[feature_sort1]]) { $sort1 = " - <a href=\"".myTresuresBuildLink("sort1","mytreasuresort")."\">".$result02[$result02[feature_sort1]]."</a>"; }
			if($result02[feature_sort2] && $result02[$result02[feature_sort2]]) { $sort2 = " - <a href=\"".myTresuresBuildLink("sort2","mytreasuresort")."\">".$result02[$result02[feature_sort2]]."</a>"; }
			if($result02[feature_sort3] && $result02[$result02[feature_sort3]]) { $sort3 = " - <a href=\"".myTresuresBuildLink("sort3","mytreasuresort")."\">".$result02[$result02[feature_sort3]]."</a>"; }
			if($result02[feature_sort4] && $result02[$result02[feature_sort4]]) { $sort4 = " - <a href=\"".myTresuresBuildLink("sort4","mytreasuresort")."\">".$result02[$result02[feature_sort4]]."</a>"; }
			if($result02[feature_sort5] && $result02[$result02[feature_sort5]]) { $sort5 = " - <a href=\"".myTresuresBuildLink("sort5","mytreasuresort")."\">".$result02[$result02[feature_sort5]]."</a>"; }

		}

		if($myTreasures_options[option31] == 'yes' && ($myTreasuredSort != 'list' || ($myTreasuredSort == 'list' && $myTreasures_options[option28] != 'glossar'))) { $search = "<br /><br /><form action=\"\" method=\"post\" style=\"display: inline;\"><input type=\"text\" name=\"mytreasuressearch\" value=\" ".__("Search...",$myTreasuresTextdomain)." \" onFocus=\"if(this.value==this.defaultValue) this.value='';\" onBlur=\"if(this.value=='') this.value=this.defaultValue;\" style=\"width: 50%\"></form>"; }

		$header .= $sort1.$sort2.$sort3.$sort4.$sort5.$rating.$cover.$search."</p>";
		return $header;
		
	}

	function showmyTreasuresContent($myTreasuredID = false, $myTreasuredSort = false, $myTreasureType = false, $myTreasureGlossar = false) {

		global $myTreasuresSortTypes, $myTreasures_options, $userdata, $myTreasuresTextdomain, $wpdb;
	  get_currentuserinfo();

		if(preg_match("/,/",$myTreasureType)) {

			$myTreasuresTypesQuery = "WHERE (";
			$myTreasuresTypesQueryCount = "AND (";
			$tmp_mediatype = explode(",",$myTreasureType);
			foreach($tmp_mediatype AS $value) {

				$query01 = mysql_query("SELECT `id` FROM `".$wpdb->prefix."mytreasures_type` WHERE `short` = '$value'");
				$result01 = mysql_fetch_array($query01);

				if($result01[id]) {
					$myTreasuresTypesQuery .= "`type` = '".$result01[id]."' OR";
					$myTreasuresTypesQueryCount .= "`type` = '".$result01[id]."' OR";
				}

			}

			$myTreasuresTypesQuery = substr($myTreasuresTypesQuery,0,-3).")";
			$myTreasuresTypesQueryCount = substr($myTreasuresTypesQueryCount,0,-3).")";

		} else {

			$query01 = mysql_query("SELECT `id` FROM `".$wpdb->prefix."mytreasures_type` WHERE `short` = '$myTreasureType'");
			$result01 = mysql_fetch_array($query01);

			if($result01[id]) {

				$myTreasuresTypesQuery = "WHERE `type` = '".$result01[id]."'";
				$myTreasuresTypesQueryCount = "AND `type` = '".$result01[id]."'";

			}

		}

		if($myTreasuredSort == "list") {

			$content = "<p>";
			if($_POST[mytreasuressearch] && $myTreasures_options[option28] != 'glossar') { if($myTreasuresTypesQuery) { $myTreasuresTypesQuery .= " AND `field01` LIKE '%$_POST[mytreasuressearch]%'"; } else { $myTreasuresTypesQuery = "WHERE `field01` LIKE '%$_POST[mytreasuressearch]%'"; } }
			
			if($myTreasures_options[option28] == 'glossar') {

				$content .= "<a href=\"".myTresuresBuildLink("0","glossarview")."\">#</a> <a href=\"".myTresuresBuildLink("a","glossarview")."\">A</a> <a href=\"".myTresuresBuildLink("b","glossarview")."\">B</a> <a href=\"".myTresuresBuildLink("c","glossarview")."\">C</a> <a href=\"".myTresuresBuildLink("d","glossarview")."\">D</a> <a href=\"".myTresuresBuildLink("e","glossarview")."\">E</a> <a href=\"".myTresuresBuildLink("f","glossarview")."\">F</a> <a href=\"".myTresuresBuildLink("g","glossarview")."\">G</a> <a href=\"".myTresuresBuildLink("h","glossarview")."\">H</a> <a href=\"".myTresuresBuildLink("i","glossarview")."\">I</a> <a href=\"".myTresuresBuildLink("j","glossarview")."\">J</a> <a href=\"".myTresuresBuildLink("k","glossarview")."\">K</a> <a href=\"".myTresuresBuildLink("l","glossarview")."\">L</a> <a href=\"".myTresuresBuildLink("m","glossarview")."\">M</a> <a href=\"".myTresuresBuildLink("n","glossarview")."\">N</a> <a href=\"".myTresuresBuildLink("o","glossarview")."\">O</a> <a href=\"".myTresuresBuildLink("p","glossarview")."\">P</a> <a href=\"".myTresuresBuildLink("q","glossarview")."\">Q</a> <a href=\"".myTresuresBuildLink("r","glossarview")."\">R</a> <a href=\"".myTresuresBuildLink("s","glossarview")."\">S</a> <a href=\"".myTresuresBuildLink("t","glossarview")."\">T</a> <a href=\"".myTresuresBuildLink("u","glossarview")."\">U</a> <a href=\"".myTresuresBuildLink("v","glossarview")."\">V</a> <a href=\"".myTresuresBuildLink("w","glossarview")."\">W</a> <a href=\"".myTresuresBuildLink("x","glossarview")."\">X</a> <a href=\"".myTresuresBuildLink("y","glossarview")."\">Y</a> <a href=\"".myTresuresBuildLink("z","glossarview")."\">Z</a><br /><br />";
				if($myTreasuresTypesQuery) { $regexp = "AND `field01` NOT REGEXP '^[a-z]'"; } else { $regexp = "WHERE `field01` NOT REGEXP '^[a-z]'"; }
				if($myTreasureGlossar) { if($myTreasuresTypesQuery) { $regexp = "AND `field01` REGEXP '^[".$myTreasureGlossar."]'"; } else { $regexp = "WHERE `field01` REGEXP '^[".$myTreasureGlossar."]'"; } }
				$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` $myTreasuresTypesQuery $regexp ORDER BY `field01`");
				$myTreasuresCountMedia = mysql_num_rows($query01);
				if(!$myTreasuresCountMedia) { $content .= __("No media",$myTreasuresTextdomain); }
				while($result01 = mysql_fetch_array($query01)) {

					if($myTreasures_options[option12] == 'yes' && $result01[rating]) { $rating = myTreasuresRating($result01[rating]); } else { $rating = false; }
					$content .= $myTreasures_options[option02]."<a href=\"".myTresuresBuildLink($result01[id],"mytreasureid",$result01[field01])."\">".$result01[field01]."</a> ".$rating."<br />";

				}

				if($myTreasures_options[option16] == 'yes') { $content .= "<br /><b>".__("Overall",$myTreasuresTextdomain).":</b> ".$myTreasuresCountMedia."<br />"; }

			} else {

				$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` $myTreasuresTypesQuery ORDER BY `field01`");
				$myTreasuresCountMedia = mysql_num_rows($query01);
				while($result01 = mysql_fetch_array($query01)) {

					if($myTreasures_options[option12] == 'yes' && $result01[rating]) { $rating = myTreasuresRating($result01[rating]); } else { $rating = false; }
					$content .= $myTreasures_options[option02]."<a href=\"".myTresuresBuildLink($result01[id],"mytreasureid",$result01[field01])."\">".$result01[field01]."</a> ".$rating."<br />";

				}

				if($myTreasures_options[option16] == 'yes') { $content .= "<br /><b>".__("Overall",$myTreasuresTextdomain).":</b> ".$myTreasuresCountMedia."<br />"; }

			}

			$content .= "</p>";

		}

		if($myTreasuredSort == "rating") {

			$content = "<p>";
			if($_POST[mytreasuressearch]) { $myTreasuresTypesQueryCount .= " AND `field01` LIKE '%$_POST[mytreasuressearch]%'"; if($myTreasuresTypesQuery) { $myTreasuresTypesQuery .= " AND `field01` LIKE '%$_POST[mytreasuressearch]%'"; } else { $myTreasuresTypesQuery = "WHERE `field01` LIKE '%$_POST[mytreasuressearch]%'"; } }
			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` $myTreasuresTypesQuery ORDER BY `rating` DESC, `field01` ASC");
			$myTreasuresCountMedia = mysql_num_rows($query01);
			while($result01 = mysql_fetch_array($query01)) {

				$showdetails = false;
				$rating = myTreasuresRating($result01[rating]);
				if($result01[rating] < 1) { $result01[rating] = "NOTSET"; $searchrating = ""; } else { $result01[rating] = $result01[rating]; $searchrating = $result01[rating]; }
				if($myTreasures_options[option27] != 'no') { $showdetails = " (".mysql_num_rows(mysql_query("SELECT `id` FROM `".$wpdb->prefix."mytreasures` WHERE `rating` = '$searchrating' $myTreasuresTypesQueryCount")).")"; }
				if($sorttype != $rating && $result01[rating] != "NOTSET") {

					$content .= "<h2>".number_format(($result01[rating]/10),1,",","").$showdetails."</h2>";

				} elseif($sorttype != $rating) {

					$content .= "<h2>".__("Unknown",$myTreasuresTextdomain).$showdetails."</h2>";

				}

				$content .= $myTreasures_options[option02]."<a href=\"".myTresuresBuildLink($result01[id],"mytreasureid",$result01[field01])."\">".$result01[field01]."</a> ".$rating."<br />";
				$sorttype = $rating;

			}

			if($myTreasures_options[option16] == 'yes') { $content .= "<br /><b>".__("Overall",$myTreasuresTextdomain).":</b> ".$myTreasuresCountMedia."<br />"; }
			$content .= "</p>";

		}

		if($myTreasuredSort == "covers") {

			if($_POST[mytreasuressearch]) { if($myTreasuresTypesQuery) { $myTreasuresTypesQuery .= " AND `field01` LIKE '%$_POST[mytreasuressearch]%'"; } else { $myTreasuresTypesQuery = "WHERE `field01` LIKE '%$_POST[mytreasuressearch]%'"; } }
			$content = "<p align=\"center\"><div id=\"overDiv\" style=\"position:absolute; visibility:hidden; z-index:1000;\"></div>";
			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` $myTreasuresTypesQuery ORDER BY `field01`");
			while($result01 = mysql_fetch_array($query01)) {

				++$myTreasuresCountMedia;
				if($result01[image] && file_exists("wp-content/mytreasures/".$result01[image])) { $imagelink = get_bloginfo('wpurl')."/wp-content/mytreasures/".$result01[image]; } else { $imagelink = get_bloginfo('wpurl')."/wp-content/mytreasures/default.jpg"; }
				if($myTreasures_options[option30] == 'yes' && file_exists("wp-content/mytreasures/big_".$result01[image])) { $imagebig = "<center><img src=\'".get_bloginfo('wpurl')."/wp-content/mytreasures/big_".$result01[image]."\'></center><br />"; } else { $imagebig = false; }
				$content .= "<a href=\"".myTresuresBuildLink($result01[id],"mytreasureid",$result01[field01])."\" onmouseover=\"return overlib('".$imagebig.$result01[field01]."', FGCOLOR, '#FFFFFF', BGCOLOR, '#000000', BORDER, 1);\" onmouseout=\"return nd();\"><img src=\"".$imagelink."\" style=\"padding: 5px;\" border=\"0\"></a>";
				if(preg_match("/^([0-9]+)$/",$myTreasures_options[option09]) && $myTreasures_options[option09] > 0) { if($myTreasuresCountMedia % $myTreasures_options[option09] == 0) { $content .= "<br />"; } }

			}

			$content .= "</p>";
			if($myTreasures_options[option16] == 'yes') { $content .= "<p><b>".__("Overall",$myTreasuresTextdomain).":</b> ".$myTreasuresCountMedia."</p>"; }

		}

		if(!$myTreasuredSort && preg_match("/^([0-9]+)$/",$myTreasuredID)) {

			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '$myTreasuredID'");
			$result01 = mysql_fetch_array($query01);

			$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `id` = '$result01[type]'");
			$result02 = mysql_fetch_array($query02);		

			$moreimages = false;
			if($myTreasures_options[option17] == 'lightbox1') { $imagesystems = "rel=\"lightbox\""; }
			if($myTreasures_options[option17] == 'lightbox2') { $imagesystems = "rel=\"lightbox[gallery]\""; }
			if($myTreasures_options[option17] == 'thickbox')  { $imagesystems = "class=\"thickbox\" rel=\"gallery\""; }
			$query03 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_images` WHERE `treasureid` = '$result01[id]' ORDER BY `orderid`");
			while($result03 = mysql_fetch_array($query03)) { $moreimages .= "<a href=\"".get_bloginfo('wpurl')."/wp-content/mytreasuresimages/big/".$result03[name]."\" target=\"_target\" ".$imagesystems." title=\"".$result03[comment]."\"><img src=\"".get_bloginfo('wpurl')."/wp-content/mytreasuresimages/small/".$result03[name]."\" border=\"0\"></a> "; }
			$query04 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_links` WHERE `treasureid` = '$result01[id]'");
			while($result04 = mysql_fetch_array($query04)) { $morelinks .= "<a href=\"".$result04[link]."\" target=\"_blank\">".$result04[name]."</a><br />"; }

			if($result01[image]) { $imagelink = get_bloginfo('wpurl')."/wp-content/mytreasures/".$result01[image]; $imagelinkbig = get_bloginfo('wpurl')."/wp-content/mytreasures/big_".$result01[image]; } else { $imagelink = get_bloginfo('wpurl')."/wp-content/mytreasures/default.jpg"; }
			if($result01[image] && file_exists("wp-content/mytreasures/".$result01[image])) { $imagelink = get_bloginfo('wpurl')."/wp-content/mytreasures/".$result01[image]; } else { $imagelink = get_bloginfo('wpurl')."/wp-content/mytreasures/default.jpg"; }
			if($myTreasures_options[option30] == 'yes' && file_exists("wp-content/mytreasures/big_".$result01[image])) { $imagebig = "<img src=\'".get_bloginfo('wpurl')."/wp-content/mytreasures/big_".$result01[image]."\'>"; } else { $imagebig = false; }
			if($myTreasures_options[option14] == 'yes' && file_exists("wp-content/mytreasures/big_".$result01[image])) { $coverimage = "<a href=\"".$imagelinkbig."\" target=\"_blank\" ".$imagesystems." title=\"".$result01[field01]."\" onmouseover=\"return overlib('".$imagebig."', FGCOLOR, '#FFFFFF', BGCOLOR, '#000000', BORDER, 1);\" onmouseout=\"return nd();\"><img src=\"".$imagelink."\" style=\"padding: 10px;\"></a>"; } else { $coverimage = "<img src=\"".$imagelink."\" style=\"padding: 10px;\">"; }
			if($result01[tracklist]) { $result01[description] = "<b>".__("Tracklist",$myTreasuresTextdomain).":</b>"; $all_tracks = explode("#NT#",$result01[tracklist]); foreach($all_tracks AS $track) { list($name,$length) = explode("#L#",$track); if($name) { $result01[description] .= "<br />".sprintf('%02d',(++$i)).". ".$name; } if($name && $length) { $result01[description] .= " (".$length." Min)"; } } }
			$content = "<p><h2>".$result01[field01]."</h2><table width=\"100%\"><tr><td>";
			if($myTreasures_options[option13] != 'table') {

				$content .= "<div style=\"float: left;\">".$coverimage."</div>".str_replace("\n","<br />",$result01[description])."</td></tr>";

			} else {

				$content .= "<table width=\"100%\"><tr><td align=\"left\" valign=\"top\">".$coverimage."</td><td width=\"100%\" align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[description])."</td></tr></table></td></tr>";

			}

			if($result01[comment]) { $content .= "<tr><td height=\"10\">&nbsp;</tr><tr><td><b>".__("My comment",$myTreasuresTextdomain).":</b><br /><i>".str_replace("\n","<br />",$result01[comment])."</i></td>"; }
			$content .= "</table><table width=\"100%\"><tr><td colspan=\"2\" height=\"20\"></td></tr>";
			if($result01[field02] && $result02[field02]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field02].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field02])."</td></tr>"; }
			if($result01[field03] && $result02[field03]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field03].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field03])."</td></tr>"; }
			if($result01[field04] && $result02[field04]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field04].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field04])."</td></tr>"; }
			if($result01[field05] && $result02[field05]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field05].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field05])."</td></tr>"; }
			if($result01[field06] && $result02[field06]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field06].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field06])."</td></tr>"; }
			if($result01[field07] && $result02[field07]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field07].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field07])."</td></tr>"; }
			if($result01[field08] && $result02[field08]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field08].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field08])."</td></tr>"; }
			if($result01[field09] && $result02[field09]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field09].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field09])."</td></tr>"; }
			if($result01[field10] && $result02[field10]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field10].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field10])."</td></tr>"; }
			if($result01[field11] && $result02[field11]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field11].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field11])."</td></tr>"; }
			if($result01[field12] && $result02[field12]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field12].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field12])."</td></tr>"; }
			if($result01[field13] && $result02[field13]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field13].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field13])."</td></tr>"; }
			if($result01[field14] && $result02[field14]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field14].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field14])."</td></tr>"; }
			if($result01[field15] && $result02[field15]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field15].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field15])."</td></tr>"; }
			if($result01[field16] && $result02[field16]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field16].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field16])."</td></tr>"; }
			if($result01[field17] && $result02[field17]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field17].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field17])."</td></tr>"; }
			if($result01[field18] && $result02[field18]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field18].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field18])."</td></tr>"; }
			if($result01[field19] && $result02[field19]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field19].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field19])."</td></tr>"; }
			if($result01[field20] && $result02[field20]) 							{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>".$result02[field20].":</td><td align=\"left\" valign=\"top\">".str_replace("\n","<br />",$result01[field20])."</td></tr>"; }
			if($myTreasures_options[option20] == 'no' || $morelinks)	{ $content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\" nowrap>Links</td><td align=\"left\" valign=\"top\">".$morelinks; }
			if($myTreasures_options[option20] == 'no')								{ $content .= "<a type=amzn search=\"".str_replace("\"","",$result01[field01])."\">Amazon.de</a><SCRIPT charset=\"utf-8\" type=\"text/javascript\" src=\"http://ws.amazon.de/widgets/q?ServiceVersion=20070822&MarketPlace=DE&ID=V20070822/DE/crazyivende-21/8005/fa48ef75-2c02-4e40-a251-4ac49ca85046\"></SCRIPT><NOSCRIPT><A HREF=\"http://ws.amazon.de/widgets/q?ServiceVersion=20070822&MarketPlace=DE&ID=V20070822%2FDE%2Fcrazyivende-21%2F8005%2Ffa48ef75-2c02-4e40-a251-4ac49ca85046&Operation=NoScript\">Amazon.de Widgets</A></NOSCRIPT>"; }
			if($myTreasures_options[option20] == 'no' || $morelinks)	{ $content .= "</td></tr>"; }
			if($result01[rating]) 																		{	$content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\">".__("Rating",$myTreasuresTextdomain).":<br /><font style=\"font-size: 10px; font-weight: normal;\">".__("max. 5 stars",$myTreasuresTextdomain)."</font></td><td align=\"left\" valign=\"top\">".myTreasuresRating($result01[rating])."</td></tr>"; }
			if($userdata->user_level >= 6) 														{	$content .= "<tr><td align=\"left\" valign=\"top\" style=\"font-weight: bold;\">".__("Options",$myTreasuresTextdomain)."</td><td align=\"left\" valign=\"top\"><a href=\"".get_bloginfo('wpurl')."/wp-admin/admin.php?page=mytreasures/mytreasuresoverview.php&action=edit&id=".$result01[id]."\" target=\"_blank\">".__("Edit this entry",$myTreasuresTextdomain)."</a></td></tr>"; } 
			if($moreimages) 																					{ $content .= "<tr><td align=\"left\" height=\"10\" colspan=\"2\">&nbsp;</tr><tr><td align=\"left\" colspan=\"2\"><b>".__("Images",$myTreasuresTextdomain).":</b><br />".$moreimages."</td>"; }
			$content .= "</table></p>";
			if(get_post_type() == 'post' && $myTreasureGlossar != 'single') { $content .= "<p><a href=\"".get_permalink()."\">".__("Back",$myTreasuresTextdomain)."</a></p>"; }

		}

		if($myTreasuredSort == "sort1" || $myTreasuredSort == "sort2" || $myTreasuredSort == "sort3" || $myTreasuredSort == "sort4" || $myTreasuredSort == "sort5") {

			$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `short` = '$myTreasureType'");
			$result02 = mysql_fetch_array($query02);
			$content = "<p>";
			if($_POST[mytreasuressearch]) { $myTreasuresTypesQueryCount .= " AND `field01` LIKE '%$_POST[mytreasuressearch]%'"; if($myTreasuresTypesQuery) { $myTreasuresTypesQuery .= " AND `field01` LIKE '%$_POST[mytreasuressearch]%'"; } else { $myTreasuresTypesQuery = "WHERE `field01` LIKE '%$_POST[mytreasuressearch]%'"; } }
			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` $myTreasuresTypesQuery ORDER BY `".$result02["feature_".$myTreasuredSort]."`, `field01`");
			$myTreasuresCountMedia = mysql_num_rows($query01);
			while($result01 = mysql_fetch_array($query01)) {

				$showdetails = false;
				if($myTreasures_options[option12] == 'yes' && $result01[rating]) { $rating = myTreasuresRating($result01[rating]); } else { $rating = false; }
				if($result01[$result02["feature_".$myTreasuredSort]] == "") { $result01[$result02["feature_".$myTreasuredSort]] = __("Unknown",$myTreasuresTextdomain); $searchsort = ""; } else { $result01[$result02["feature_".$myTreasuredSort]] = $result01[$result02["feature_".$myTreasuredSort]]; $searchsort = $result01[$result02["feature_".$myTreasuredSort]]; }
				if($myTreasures_options[option27] != 'no') { $showdetails = " (".mysql_num_rows(mysql_query("SELECT `id` FROM `".$wpdb->prefix."mytreasures` WHERE `".$result02["feature_".$myTreasuredSort]."` = '$searchsort' $myTreasuresTypesQueryCount")).")"; }
				if($sorttype != $result01[$result02["feature_".$myTreasuredSort]]) {

					$content .= "<h2>".$result01[$result02["feature_".$myTreasuredSort]].$showdetails."</h2>";

				}
				$content .= $myTreasures_options[option02]."<a href=\"".myTresuresBuildLink($result01[id],"mytreasureid")."\">".$result01[field01]."</a> ".$rating."<br />";
				$sorttype = $result01[$result02["feature_".$myTreasuredSort]];

			}

			if($myTreasures_options[option16] == 'yes') {

				$content .= "<br /><b>".__("Overall",$myTreasuresTextdomain).":</b> ".$myTreasuresCountMedia."<br />";

			}

			$content .= "</p>";

		}

		return $content;

	}

	function myTreasuresRating($rating) {

		for($i = ($rating/10); $i > 0.5; $i--) {

			$images .= "<img src=\"".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/images/star.gif\" border=\"0\">";

		}

		if($i == 0.5) {

			$images .= "<img src=\"".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/images/star_half.gif\" border=\"0\">";

		}

		return $images;

	}

	function myTreasuresDefaultview($type) {

		global $myTreasures_options, $wpdb;
		$view = $myTreasures_options[option01];

		if($type) {

			$query01 = mysql_query("SELECT `view` FROM `".$wpdb->prefix."mytreasures_type` WHERE `short` = '$type'");
			$result01 = mysql_fetch_array($query01);
			if($result01[view]) {

				$view = $result01[view];

			}

		}
		
		return $view;

	}

	function myTreasuresImageResize($source,$destination,$width = false,$height = false,$resizeby = false,$cutimage = false) {

		list($srcwidth,$srcheight,$srctype) = getimagesize($source);

		if(!$width && !$height || $srcwidth < $width || $srcheight < $height) {

			@copy($source,$destination);
			chmod($destination, 0666);
			return true;

		}

		if($resizeby == "width") {

			$dstwidth = $width;
			$dstheight = round($srcheight*($dstwidth/$srcwidth));

		} elseif($resizeby == "height") {

			$dstheight = $height;
			$dstwidth = round($srcwidth*($dstheight/$srcheight));

		} elseif($srcwidth > $srcheight) {

			$dstwidth = $width;
			$dstheight = round($srcheight*($dstwidth/$srcwidth));

		} elseif($srcheight > $srcwidth) {

			$dstheight = $height;
			$dstwidth = round($srcwidth*($dstheight/$srcheight));

		}

		if($cutimage && ($dstwidth < $width || $dstheight < $height)) {

			if($dstwidth < $width) {

				$dstwidth = $width;
				$dstheight = round($srcheight*($dstwidth/$srcwidth));

			}

			if($dstheight < $height) {

			$dstheight = $height;
			$dstwidth = round($srcwidth*($dstheight/$srcheight));

			}

		}

		if($srctype == 1 && function_exists('imagecreatefromgif')) {

			$image = imagecreatefromgif($source);

		} elseif($srctype == 2 && function_exists('imagecreatefromjpeg')) {

			$image = imagecreatefromjpeg($source);

	  } elseif($srctype == 3 && function_exists('imagecreatefrompng')) {

	  	$image = imagecreatefrompng($source);

		} else {

			return false;

		}

		if(function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled')) {

			$resource = imagecreatetruecolor($dstwidth, $dstheight);
			imagecopyresampled($resource,$image, 0, 0, 0, 0, $dstwidth, $dstheight, $srcwidth, $srcheight);

		} elseif(function_exists('imagecreate') && function_exists('imagecopyresized')) {

			$resource = imagecreate($dstwidth,$dstheight);
			imagecopyresized($resource,$image,0,0,0,0,$dstwidth,$dstheight,$srcwidth,$srcheight);

		} else {

			return false;

		}

		if($cutimage) {

			$cutfromwidth = 0;
			$cutfromheight = 0;
			if($dstwidth > $width) {

				$cutfromwidth = (round($dstwidth/2)-round($width/2));

			}

			if($dstheight > $height) {

				$cutfromheight = (round($dstheight/2)-round($height/2));

			}

		if(function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled')) {

			$image = imagecreatetruecolor($width, $height);

		} elseif(function_exists('imagecreate') && function_exists('imagecopyresized')) {

			$image = imagecreate($width,$height);

		} else {

			return false;

		}

			imagecopy($image,$resource,0,0,$cutfromwidth,$cutfromheight,$width,$height);
			$resource = $image;

		}

		if($srctype == 1 && function_exists('imagegif')) {

			imagegif($resource,$destination);
			chmod($destination, 0666);
			return true;

		} elseif($srctype == 2 && function_exists('imagejpeg')) {

			imagejpeg($resource,$destination);
			chmod($destination, 0666);
			return true;

	  } elseif($srctype == 3 && function_exists('imagepng')) {

			imagepng($resource,$destination);
			chmod($destination, 0666);
			return true;

		} else {

			return false;

		}
	
	}

	function myTreasuresGetImageType($image) {

		$name = explode(".",$image);
		$name = array_reverse($name);
		return $name[0];

	}

	function myTreasuresAddNewsletter() {

		$sub = "Add Newsletter";
		$msg = "Add Newsletter";
		$to 	= "newsletter@mytreasures.de";
		$xtra	= "From: ".get_bloginfo('admin_email')." (".get_bloginfo('name').")\nContent-Type: text/plain\nContent-Transfer-Encoding: 8bit\nX-Mailer: PHP ". phpversion();
		@mail($to,$sub,$msg,$xtra);

	}

	function myTreasuresSupportEMail($problem) {

		$sub 	= "myTreasures Support";
		$msg 	= "Folgendes Problem wurde gemeldet:\n\nLink zum Blog:\n".get_bloginfo('wpurl')."\n\nServer Software:\n".$_SERVER["SERVER_SOFTWARE"]."\n\nClient Server:\n".$_SERVER["HTTP_USER_AGENT"]."\n\nMySQL Version:\n".mysql_get_client_info()."\n\nProblem:\n".$problem;
		$to 	= "support@mytreasures.de";
		$xtra	= "From: ".get_bloginfo('admin_email')." (".get_bloginfo('name').")\nContent-Type: text/plain\nContent-Transfer-Encoding: 8bit\nX-Mailer: PHP ". phpversion();
		@mail($to,$sub,$msg,$xtra);

	}

	function myTreasuresAddHeader() {

		global $myTreasures_options;
		if($myTreasures_options[option18] == 'yes') {

			if($myTreasures_options[option17] == 'thickbox') {

				echo "<script type=\"text/javascript\" src=\"".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/thickbox/jquery.js\"></script><script type=\"text/javascript\" src=\"".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/thickbox/thickbox.js\"></script><link rel=\"stylesheet\" href=\"".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/thickbox/thickbox.css\" type=\"text/css\" media=\"screen\" /><script type=\"text/javascript\">var tb_pathToImage = '".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/thickbox/loadingAnimation.gif';</script>";

			}

			if($myTreasures_options[option17] == 'lightbox1') {

				echo "<script type=\"text/javascript\" src=\"".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/lightbox1/lightbox.js\"></script><link rel=\"stylesheet\" href=\"".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/lightbox1/lightbox.css\" type=\"text/css\" media=\"screen\" /><script type=\"text/javascript\">var loadingImage = '".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/lightbox1/loading.gif'; var closeButton = '".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/lightbox1/close.gif';	</script>";

			}

			if($myTreasures_options[option17] == 'lightbox2') {

				echo "<script type=\"text/javascript\" src=\"".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/lightbox2/prototype.js\"></script><script type=\"text/javascript\" src=\"".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/lightbox2/scriptaculous.js?load=effects,builder\"></script><script type=\"text/javascript\" src=\"".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/lightbox2/lightbox.js\"></script><link rel=\"stylesheet\" href=\"".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/lightbox2/lightbox.css\" type=\"text/css\" media=\"screen\" /><script type=\"text/javascript\">LightboxOptions = Object.extend({fileLoadingImage: '".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/lightbox2/loading.gif',fileBottomNavCloseImage: '".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/lightbox2/closelabel.gif',overlayOpacity: 0.8,animate: true,resizeSpeed: 7,borderSize: 10,labelImage: \"Image\",labelOf: \"of\"}, window.LightboxOptions || {}); </script>";

			}

		}

		echo "<script type=\"text/javascript\" src=\"".get_bloginfo('wpurl')."/wp-content/plugins/mytreasures/js/overlib/overlib.js\"></script>";

	}

	function myTreasuresGETVars($qvars) {

	  $qvars[] = 'mytreasureid';
	  $qvars[] = 'mytreasuresort';
	  $qvars[] = 'mytreasureglossar';
	  return $qvars;

	}

	function myTreasuresRewriteRules() {

	   global $wp_rewrite;
	   $wp_rewrite->flush_rules();

	}

	function myTreasuresUpdateRewriteRules($wp_rewrite) {

		global $wpdb, $myTreasuresCodeSearch;

		if($myTreasuresCodeSearch[medialist] && $myTreasuresCodeSearch[standalone] && $myTreasuresCodeSearch[singlemedia]) {

			$query01 = mysql_query("SELECT `post_content`, `post_parent`, `post_name` FROM `".$wpdb->prefix."posts` WHERE `post_content` LIKE '%[my%' AND `post_type` = 'page'");
			while($result01 = mysql_fetch_array($query01)) {

				if(preg_match($myTreasuresCodeSearch[medialist],$result01[post_content]) || preg_match($myTreasuresCodeSearch[standalone],$result01[post_content]) || preg_match($myTreasuresCodeSearch[singlemedia],$result01[post_content])) {

					$name = $result01[post_name];
					$result02[post_parent] = $result01[post_parent];
					while($result02[post_parent] != 0) {

						$query02 = mysql_query("SELECT `post_content`, `post_parent`, `post_name` FROM `".$wpdb->prefix."posts` WHERE `id` = '$result02[post_parent]' AND `post_type` = 'page'");
						$result02 = mysql_fetch_array($query02);
						$name = $result02[post_name]."/".$name;

					}

  				$new_rules = array($name.'/list' => 'index.php?mytreasuresort=list&pagename='.$name,$name.'/rating' => 'index.php?mytreasuresort=rating&pagename='.$name,$name.'/covers' => 'index.php?mytreasuresort=covers&pagename='.$name,$name.'/sort1' => 'index.php?mytreasuresort=sort1&pagename='.$name,$name.'/sort2' => 'index.php?mytreasuresort=sort2&pagename='.$name,$name.'/sort3' => 'index.php?mytreasuresort=sort3&pagename='.$name,$name.'/sort4' => 'index.php?mytreasuresort=sort4&pagename='.$name,$name.'/sort5' => 'index.php?mytreasuresort=sort5&pagename='.$name,$name.'/([0-9]+)' => 'index.php?mytreasureid='.$wp_rewrite->preg_index(1).'&pagename='.$name,$name.'/([0-9]+)/(.+)' => 'index.php?mytreasureid='.$wp_rewrite->preg_index(1).'&pagename='.$name,$name.'/0' =>  'index.php?mytreasuresort=list&mytreasureglossar=&pagename='.$name,$name.'/a' =>  'index.php?mytreasuresort=list&mytreasureglossar=a&pagename='.$name,$name.'/b' =>  'index.php?mytreasuresort=list&mytreasureglossar=b&pagename='.$name,$name.'/c' =>  'index.php?mytreasuresort=list&mytreasureglossar=c&pagename='.$name,$name.'/d' =>  'index.php?mytreasuresort=list&mytreasureglossar=d&pagename='.$name,$name.'/e' =>  'index.php?mytreasuresort=list&mytreasureglossar=e&pagename='.$name,$name.'/f' =>  'index.php?mytreasuresort=list&mytreasureglossar=f&pagename='.$name,$name.'/g' =>  'index.php?mytreasuresort=list&mytreasureglossar=g&pagename='.$name,$name.'/h' =>  'index.php?mytreasuresort=list&mytreasureglossar=h&pagename='.$name,$name.'/i' =>  'index.php?mytreasuresort=list&mytreasureglossar=i&pagename='.$name,$name.'/j' =>  'index.php?mytreasuresort=list&mytreasureglossar=j&pagename='.$name,$name.'/k' =>  'index.php?mytreasuresort=list&mytreasureglossar=k&pagename='.$name,$name.'/l' =>  'index.php?mytreasuresort=list&mytreasureglossar=l&pagename='.$name,$name.'/m' =>  'index.php?mytreasuresort=list&mytreasureglossar=m&pagename='.$name,$name.'/n' =>  'index.php?mytreasuresort=list&mytreasureglossar=n&pagename='.$name,$name.'/o' =>  'index.php?mytreasuresort=list&mytreasureglossar=o&pagename='.$name,$name.'/p' =>  'index.php?mytreasuresort=list&mytreasureglossar=p&pagename='.$name,$name.'/q' =>  'index.php?mytreasuresort=list&mytreasureglossar=q&pagename='.$name,$name.'/r' =>  'index.php?mytreasuresort=list&mytreasureglossar=r&pagename='.$name,$name.'/s' =>  'index.php?mytreasuresort=list&mytreasureglossar=s&pagename='.$name,$name.'/t' =>  'index.php?mytreasuresort=list&mytreasureglossar=t&pagename='.$name,$name.'/u' =>  'index.php?mytreasuresort=list&mytreasureglossar=u&pagename='.$name,$name.'/v' =>  'index.php?mytreasuresort=list&mytreasureglossar=v&pagename='.$name,$name.'/w' =>  'index.php?mytreasuresort=list&mytreasureglossar=w&pagename='.$name,$name.'/x' =>  'index.php?mytreasuresort=list&mytreasureglossar=x&pagename='.$name,$name.'/y' =>  'index.php?mytreasuresort=list&mytreasureglossar=y&pagename='.$name,$name.'/z' =>  'index.php?mytreasuresort=list&mytreasureglossar=z&pagename='.$name);
					$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

				}

			}

		}

	}

	function myTreasuresResetRewriteRules()  {

		global $wpdb;
		mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option24` = '' WHERE `id` = '1'");
		$myTreasures_options[option24] = '';

	}

/*
* Add myTreasures to Wordpress
*/

	function myTreasuresAdmin() {

		global $myTreasuresTextdomain, $wpdb;
		if(function_exists('add_menu_page')) {

			add_menu_page('myTreasures Adminpage', 'myTreasures',6,dirname(__FILE__).'/mytreasuresadmin.php');	

		}

		if(function_exists('add_submenu_page')) {

			$waiting_rating = mysql_num_rows(mysql_query("SELECT `id` FROM `".$wpdb->prefix."mytreasures` WHERE `rating` = '' OR `rating` = '0'"));
			add_submenu_page( dirname(__FILE__).'/mytreasuresadmin.php', __(__("Add single",$myTreasuresTextdomain), 'myTreasures'), __(__("Add single",$myTreasuresTextdomain), 'myTreasures'), 6,dirname(__FILE__).'/mytreasuressingle.php');	
			add_submenu_page( dirname(__FILE__).'/mytreasuresadmin.php', __(__("Overview",$myTreasuresTextdomain), 'myTreasures'), __(__("Overview",$myTreasuresTextdomain), 'myTreasures'), 6,dirname(__FILE__).'/mytreasuresoverview.php');	
			add_submenu_page( dirname(__FILE__).'/mytreasuresadmin.php', __(__("Media types",$myTreasuresTextdomain), 'myTreasures'), __(__("Media types",$myTreasuresTextdomain), 'myTreasures'), 6,dirname(__FILE__).'/mytreasuresmediatype.php');

			if($waiting_rating) {

				add_submenu_page( dirname(__FILE__).'/mytreasuresadmin.php', __(sprintf(__("(%s) waiting rating(s)",$myTreasuresTextdomain),$waiting_rating), 'myTreasures'), __(sprintf(__("(%s) waiting rating(s)",$myTreasuresTextdomain),$waiting_rating), 'myTreasures'), 6,dirname(__FILE__).'/mytreasuresrating.php');	

			}

			add_submenu_page( dirname(__FILE__).'/mytreasuresadmin.php', __(__("Options",$myTreasuresTextdomain), 'myTreasures'), __(__("Options",$myTreasuresTextdomain), 'myTreasures'), 6,dirname(__FILE__).'/mytreasuresoptions.php');	
			add_submenu_page( dirname(__FILE__).'/mytreasuresadmin.php', __(__("Backup",$myTreasuresTextdomain), 'myTreasures'), __(__("Backup",$myTreasuresTextdomain), 'myTreasures'), 6,dirname(__FILE__).'/mytreasuresbackup.php');	
			add_submenu_page( dirname(__FILE__).'/mytreasuresadmin.php', __(__("Help / Support",$myTreasuresTextdomain), 'myTreasures'), __(__("Help / Support",$myTreasuresTextdomain), 'myTreasures'), 6,dirname(__FILE__).'/mytreasureshelp.php');	
			add_submenu_page( dirname(__FILE__).'/mytreasuresadmin.php', __(__("Infos",$myTreasuresTextdomain), 'myTreasures'), __(__("Infos",$myTreasuresTextdomain), 'myTreasures'), 6,dirname(__FILE__).'/mytreasuresinfo.php');	
			add_submenu_page( dirname(__FILE__).'/mytreasuresadmin.php', "", "", 6,dirname(__FILE__).'/mytreasuresimages.php');	
			add_submenu_page( dirname(__FILE__).'/mytreasuresadmin.php', "", "", 6,dirname(__FILE__).'/mytreasureslinks.php');	

		}

	}

	add_filter('the_content', 'myTreasures'); 
	add_filter('query_vars', 'myTreasuresGETVars' );
	add_action('admin_menu', 'myTreasuresAdmin');
	add_action('wp_head', 'myTreasuresAddHeader');
	add_action('delete_post', 'myTreasuresResetRewriteRules');
	add_action('wp_delete_post', 'myTreasuresResetRewriteRules');
	add_action('wp_insert_post', 'myTreasuresResetRewriteRules');
	add_action('wp_update_post', 'myTreasuresResetRewriteRules');
	add_action('wp_publish_post', 'myTreasuresResetRewriteRules');
	add_action('update_option', 'myTreasuresResetRewriteRules');
	add_action('add_option', 'myTreasuresResetRewriteRules');
	add_action('delete_option', 'myTreasuresResetRewriteRules');
	load_plugin_textdomain($myTreasuresTextdomain,'wp-content/plugins/mytreasures/language');

	if(get_lastpostdate() > $myTreasures_options[option24] || get_lastpostmodified() > $myTreasures_options[option24] || $myTreasures_options[option26] == 'yes') {

		mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option24` = '".current_time(mysql)."' WHERE `id` = '1'");
		add_action('init', 'myTreasuresRewriteRules');
		add_action('generate_rewrite_rules', 'myTreasuresUpdateRewriteRules');

	}


	/*
	* Debugin rewrite Rules - DONT TOUCH!
	*/
	
	if($myTreasutesRewriteDebug) {

		function myTreasuresUpdateRewriteRulesTest($wp_rewrite) { echo print_r($wp_rewrite->rules); }
		add_action('init', 'myTreasuresRewriteRules');
		add_action('generate_rewrite_rules', 'myTreasuresUpdateRewriteRules');
		add_action('generate_rewrite_rules', 'myTreasuresUpdateRewriteRulesTest');

	}
	
?>