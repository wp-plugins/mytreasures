<?php

	get_currentuserinfo();
	if($_POST[amazonok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'no' WHERE `id` = '1'"); myTreasuresAmazonemail("no"); $myTreasures_options[option20] = "no"; }
	if($_POST[amazonnok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'yes' WHERE `id` = '1'"); myTreasuresAmazonemail("yes"); $myTreasures_options[option20] = "yes"; }

	if ($user_level < 6) {

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

			if(strlen($_POST[field01]) > 0 && $_POST[id] && $_POST[rating]) { 

				mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET `rating` = '".($_POST[rating]*10)."' WHERE `id` = '$_POST[id]'");
				echo "<div id=\"message\" class=\"updated fade\"><p><strong>".sprintf(__("You've rated <i>%s</i> successfully",$myTreasuresTextdomain),stripslashes($_POST[field01]))."</strong></p></div>";

			}

			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `rating` = '' OR `rating` = '0' ORDER BY `id` LIMIT 0,1");
			$result01 = mysql_fetch_array($query01);
			
			if($result01[id]) {

?>

<div class="wrap">
<h2><?php echo __("Rate media",$myTreasuresTextdomain); ?></h2>
<p><?php echo __("Please give a rating for the following media",$myTreasuresTextdomain); ?></p>

<form action="" method="post"><input type="hidden" name="field01" value="<?php echo stripslashes($result01[field01]); ?>"><input type="hidden" name="id" value="<?php echo $result01[id]; ?>">
<p><b>Titel / Name:</b>
<br /><?php echo stripslashes($result01[field01]); ?>
<br />
<br /><b><?php echo __("Rating (in stars)",$myTreasuresTextdomain); ?></b>
<br /><?php echo __("bad",$myTreasuresTextdomain); ?> <?php for($i = 0.5; $i <= 5; $i += 0.5) { ?><input type="radio" name="rating" value="<?php echo ($i*10); ?>"><?php echo number_format($i,1,",",""); ?>&nbsp;&nbsp;<?php } ?> <?php echo __("good",$myTreasuresTextdomain); ?></p>
<div class="submit"><input type="submit" value=" <?php echo __("Save rating and continue with the next media",$myTreasuresTextdomain); ?> "></div>
</form>
</div>

<?php

			} else {

?>

<div class="wrap">
<h2><?php echo __("Rate media",$myTreasuresTextdomain); ?></h2>
<p><?php echo __("You've rated all media in database!",$myTreasuresTextdomain); ?></p>
</div>

<?php

			}

		}

	}
			
?>