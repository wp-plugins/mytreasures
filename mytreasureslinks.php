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

		echo "<div class=\"wrap\"><h2>myTreasures</h2><p>".__("Dear user,<br /><br />the development of myTreasures takes up a lot of time and I offer it to you free of charge. But of course the webserver and the traffic have to paid for. If you allow this installation to post an Amazon Partner link (just a plain text link saying \"Amazon.de\" that will only be displayed in the Detail view) it would be a reward for my work. If anyone buys anything using that link I get credited 5%.<br /><br />There are no costs for you! If you'd like to contribute in another way, please have a look at the Info page.<br /><br />Would you like to activate the Amazon link and support the development of myTreasures?",$myTreasuresTextdomain)."</p><div class=\"submit\"><form action=\"\" method=\"post\" style=\"display: inline;\"><input type=\"submit\" name=\"amazonok\" value=\" ".__("Yes, please activate",$myTreasuresTextdomain)." \">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"amazonnok\" value=\" ".__("No thanks, I don't want the Amazon link",$myTreasuresTextdomain)." \"></form></div></div>";

	} else {

		if($_GET[id]) {

			$linkarray = false;
			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '$_GET[id]'");
			$result01 = mysql_fetch_array($query01);
			$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_links` WHERE `treasureid` = '$_GET[id]'");
			while($result02 = mysql_fetch_array($query02)) { $linkarray[] = $result02; }

			if($_POST[target] && $_POST[name]) {

				mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_links` (`treasureid`, `name`, `link`) VALUES ('$result01[id]', '".$_POST[name]."', '".$_POST[target]."')");

			}
			
			if($_POST[changelinks] && $linkarray) {

				foreach($linkarray AS $value) {

					if($_POST[deletelink][$value[id]]) {

						mysql_query("DELETE FROM `".$wpdb->prefix."mytreasures_links` WHERE `id` = '$value[id]'");

					}

				}

				$linkarray = false;

			}

			$linkarray = false;
			$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_links` WHERE `treasureid` = '$_GET[id]'");	
			while($result02 = mysql_fetch_array($query02)) { $linkarray[] = $result02; }
			
?>

<div class="wrap">
<h2><?php echo $result01[field01]; ?></h2>
<form action="" method="post"><p><h3><?php echo __("Add new link:",$myTreasuresTextdomain); ?></h3><b><?php echo __("Link target",$myTreasuresTextdomain); ?>:</b><br /><input type="text" style="height: 16px; width: 250px;" name="target" value="http://"><br /><br /><b><?php echo __("Link name",$myTreasuresTextdomain); ?></b><br /><input type="text" style="height: 16px; width: 250px;" name="name"></p><div class="submit"><input type="submit" name="doit" value=" <?php echo __("Create new link",$myTreasuresTextdomain); ?> "></div></form></div>

<?php

			if($linkarray) {

?>

<br /><br />
<div class="wrap">
<h2><?php echo __("Activ links",$myTreasuresTextdomain); ?></h2>
<form action="" method="post"><p><?php foreach($linkarray AS $value) { echo "<br /><input type=\"checkbox\" name=\"deletelink[".$value[id]."]\" value=\"1\"> <b>".$value[name]."</b> (".$value[link].")"; } ?></p>
<div class="submit">
<input type="submit" name="changelinks" value=" <?php echo __("Delete marked links",$myTreasuresTextdomain); ?> ">
</div>
</div>
</form>

<?php

			}

		}

	}

?>