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

		if(isset($_POST['rating']) && $_POST['rating']) {

			$ratedmedia = "0";
			foreach($_POST['rating'] AS $id => $rating) {

				if($rating) {

					++$ratedmedia;
					mysql_query("UPDATE `".$wpdb->prefix."mytreasures` SET `rating` = '".$rating."' WHERE `id` = '".$id."'");

				}

			}
				
			if($ratedmedia) {

				echo "<div id=\"message\" class=\"updated fade\"><p><strong>".sprintf(__("You've rated <i>%s</i> media successfully",$myTreasuresTextdomain),$ratedmedia)."</strong></p></div>";

			}

		}

		$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `rating` = '' OR `rating` = '0' ORDER BY `id` LIMIT 0,10");
			
		if(mysql_num_rows($query01)) {

?>

<div class="wrap">
<h2><?php echo __("Rate media",$myTreasuresTextdomain); ?></h2>
<p><?php echo __("Please give a rating for the following media",$myTreasuresTextdomain); ?></p>
<form action="" method="post">
<?php while($result01 = mysql_fetch_array($query01)) { ?>
<p><b>Titel / Name:</b>
<br /><?php echo stripslashes($result01['field01']); ?>
<br /><b><?php echo __("Rating (in stars)",$myTreasuresTextdomain); ?>:</b>
<br /><?php echo __("bad",$myTreasuresTextdomain); ?>&nbsp;&nbsp;<?php for($i = 0.5; $i <= 5; $i += 0.5) { ?><input type="radio" name="rating[<?php echo $result01['id']; ?>]" value="<?php echo ($i*10); ?>"><?php echo number_format($i,1,",",""); ?>&nbsp;&nbsp;<?php } echo __("good",$myTreasuresTextdomain); ?><br /></p>
<?php } ?>
<div class="submit"><input type="submit" class="button-primary" value=" <?php echo __("Save ratings",$myTreasuresTextdomain); ?> "></div>
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
			
?>