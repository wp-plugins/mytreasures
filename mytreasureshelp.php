<?php

	$all_type_help_array = "0";
	$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` ORDER BY `name`");
	while($result01 = mysql_fetch_array($query01)) {

		++$all_type_help_array;
		$type_help_array[] = $result01;

	}

?>

<div class="wrap">
<h2><?php echo __("Help",$myTreasuresTextdomain); ?></h2>
<p><strong><?php echo __("Code list",$myTreasuresTextdomain); ?></strong><br /><?php echo __("Following codes are valid for use with myTreasures. Just use a code in your page / post to display the data",$myTreasuresTextdomain); ?></p>
<center>
<table cellpadding="0" cellspacing="0" border="0" width="80%" style="border: 1px solid #000000;">
<tr>
	<td align="left" valign="top" style="border-bottom: 2px solid #000000; padding: 5px;"><b><?php echo __("Code to use",$myTreasuresTextdomain); ?></b></td>
	<td align="left" valign="top" style="border-bottom: 2px solid #000000; padding: 5px;"><b><?php echo __("Displayed result",$myTreasuresTextdomain); ?></b></td>
</tr>
<tr>
	<td align="left" valign="top" style="border-bottom: 1px solid #000000; padding: 5px;">[mytreasure=<?php echo __("&#36;Number",$myTreasuresTextdomain); ?>]</td>
	<td align="left" valign="top" style="border-bottom: 1px solid #000000; padding: 5px;"><?php echo __("Shows the single media with the ID &#36;Number (see overview to find the ID)",$myTreasuresTextdomain); ?></td>
</tr>
<tr class='alternate'>
	<td align="left" valign="top" style="border-bottom: 1px solid #000000; padding: 5px;">[mytreasurelist=<?php echo __("&#36;type1,&#36;type2,&#36;type3",$myTreasuresTextdomain); ?>]</td>
	<td align="left" valign="top" style="border-bottom: 1px solid #000000; padding: 5px;"><?php echo __("Shows a combination of all selected media types (tags!) seperated with a comma.",$myTreasuresTextdomain); ?></td>
</tr>
<tr>
	<td align="left" valign="top" style="border-bottom: 1px solid #000000; padding: 5px;">[mytreasures]</td>
	<td align="left" valign="top" style="border-bottom: 1px solid #000000; padding: 5px;"><?php echo __("Shows all media",$myTreasuresTextdomain); ?></td>
</tr>

<?php

	for($i2 = 0; $i2 < $all_type_help_array; $i2++) {

		if(++$i%2 != 0) { $class = "class='alternate'"; } else { $class = false; }
		echo "<tr ".$class."><td align=\"left\" style=\"border-bottom: 1px solid #000000; padding: 5px;\">[my".$type_help_array[$i2]['short']."treasures]</td><td align=\"left\" style=\"border-bottom: 1px solid #000000; padding: 5px;\">".sprintf(__("Shows all media of type \"%s\"",$myTreasuresTextdomain),$type_help_array[$i2]['name'])."</td></tr>";

	}

?>

</table>
</center>
<br /><br />
<h2><?php echo __("Support",$myTreasuresTextdomain); ?></h2>
<?php

	if(!isset($_POST['mytreasuresproblemhelp'])) {

		$_POST['mytreasuresproblemhelp'] = false;

	}

	if($_POST['mytreasuresproblemhelp'] && $_POST['sendmytreasuresproblemhelp']) {

		myTreasuresSupportEMail($_POST['mytreasuresproblemhelp']);
		echo __("Your Problem has been send to support@mytreasures.de",$myTreasuresTextdomain);

	} else {

?>

<form action="" method="post">
<p><?php echo __("If you need help, just fill out this form and try to explain your problem as good as you can.",$myTreasuresTextdomain); ?></p>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td align="right" valign="top" style="padding: 5px;"><b><?php echo __("Link to blog",$myTreasuresTextdomain); ?></b></td>
	<td align="left"  valign="top"><input style="width: 350px;" type="text" value="<?php echo get_bloginfo('wpurl'); ?>" disabled /></td>
</tr>
<tr class='alternate'>
	<td align="right" valign="top" style="padding: 5px;"><b><?php echo __("Server",$myTreasuresTextdomain); ?></b></td>
	<td align="left"  valign="top"><input style="width: 350px;" type="text" value="<?php echo $_SERVER["SERVER_SOFTWARE"]; ?>" disabled /></td>
</tr>
<tr>
	<td align="right" valign="top" style="padding: 5px;"><b><?php echo __("Client",$myTreasuresTextdomain); ?></b></td>
	<td align="left"  valign="top"><input style="width: 350px;" type="text" value="<?php echo $_SERVER["HTTP_USER_AGENT"]; ?>" disabled /></td>
</tr>
<tr class='alternate'>
	<td align="right" valign="top" style="padding: 5px;"><b><?php echo __("PHP Version",$myTreasuresTextdomain); ?></b></td>
	<td align="left"  valign="top"><input style="width: 350px;" type="text" value="<?php echo phpversion(); ?>" disabled /></td>
</tr>
<tr>
	<td align="right" valign="top" style="padding: 5px;"><b><?php echo __("MySQL Version",$myTreasuresTextdomain); ?></b></td>
	<td align="left"  valign="top"><input style="width: 350px;" type="text" value="<?php echo mysql_get_client_info(); ?>" disabled /></td>
</tr>
<tr class='alternate'>
	<td align="right" valign="top" style="padding: 5px;"><b><?php echo __("Your Problem",$myTreasuresTextdomain); ?></b></td>
	<td align="left"  valign="top"><textarea style="width: 350px; height: 250px;" name="mytreasuresproblemhelp"></textarea></td>
</tr>
</table>
<div class="submit"><input type="submit" class="button-primary" name="sendmytreasuresproblemhelp" value=" <?php echo __("Send to support@mytreasures.de",$myTreasuresTextdomain); ?> "></div>
</form>

<?php

	}

?>

</div>