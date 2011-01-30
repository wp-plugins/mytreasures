<?php

	if($myTreasures_options['option25'] != 'doneit') {

		if(!isset($_POST['doneinstall'])) {

			$_POST['doneinstall'] = false;

		}

		if(!isset($_POST['setupmoreinfos'])) {

			$_POST['setupmoreinfos'] = false;

		}

		if(!isset($_POST['setupmediatype'])) {

			$_POST['setupmediatype'] = false;

		}

		if($_POST['doneinstall']) {

			mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option25` = 'doneit', `changelog` = '".$myTreasuresPluginVersion."' WHERE `id` = '1'");

?>

<div class="wrap">
<h2><?php echo __("Skipped installation",$myTreasuresTextdomain); ?></h2>
<p><?php echo __("You've skipped installation, you now can use myTreasures without any restrictions.",$myTreasuresTextdomain); ?></p>
</div>


<?php

		} else {

			if($_POST['setupmoreinfos']) { 

				mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option25` = 'doneit', `changelog` = '".$myTreasuresPluginVersion."' WHERE `id` = '1'");

?>

<div class="wrap">
<h2><?php echo __("myTreasures installation - Part 3/3",$myTreasuresTextdomain); ?></h2>
<p><?php echo __("Now that you have successfully created a media type you can start to list media of that type. Since the system is easy to understand I have forgone any explanations. The following explanations will help you though to understand on how to display the lists of your media in your blog.",$myTreasuresTextdomain); ?></p>
<p><?php echo __("<b>OFDB Gateway & Dashboard Widget</b><br />With myTreasures you can search the ofdb for movie information (german only) and use the dashboard widget for quick search!",$myTreasuresTextdomain); ?></p>
<p><?php echo __("<b>List of all media of a given type</b><br />To display all media of a given type insert [my'media tag'treasures] on your page. Replace 'media tag' with the name of the tag you have created.",$myTreasuresTextdomain); ?></p>
<p><?php echo __("<b>List for a single medium</b><br />To display only one medium insert [mytreasure='media ID'] on your page. Replace 'media ID' with the ID number of your selected medium.",$myTreasuresTextdomain); ?></p>
<p><?php echo __("<b>Lists for different types of media</b><br />To display a list with media of two or more types insert [mytreasurelist='media tag1','media tag2'] on your page. Replace 'media tag' with the tags of the media you want to display.",$myTreasuresTextdomain); ?></p>
<p><?php echo __("<b>List for all media</b><br />To display a full list of all media insert [mytreasures] on your page.",$myTreasuresTextdomain); ?></p>
<p><?php echo __("<i>Please consider</i><br />Do not use ' ' when inserting the tags. If your tag is \"dvd\" for example write [mydvdtreasures]!",$myTreasuresTextdomain); ?></p>
<p><?php echo __("And that's it! More options are available on the options page.",$myTreasuresTextdomain); ?></p>
</div>

<?php

			} elseif($_POST['setupmediatype']) {

				mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` (`short`, `name`, `field01`, `field02`, `field03`, `field04`) VALUES ('dummy', 'Dummy', 'Title / Name', 'Anzahl DVDs / Number of discs', 'Jahr / Year', 'Farbe / Color')");

?>

<div class="wrap">
<h2><?php echo __("myTreasures installation - Part 2/3",$myTreasuresTextdomain); ?></h2>
<p><?php echo __("myTreasures can manage and display different \"media types\". Right now a \"dummy\" has been created to show you the system.",$myTreasuresTextdomain); ?>
<p><?php echo __("Any media type can hold up to 20 details to display! The first one has to be the name, but the rest is free for use!",$myTreasuresTextdomain); ?></p>
<div class="submit"><form action="" method="post" style="display: inline;"><input type="submit" class="button-primary" name="setupmoreinfos" value=" <?php echo __("continue to step 3",$myTreasuresTextdomain); ?> "></form> <form action="" method="post" style="display: inline;"><input type="submit" name="doneinstall" value=" <?php echo __("skip installation",$myTreasuresTextdomain); ?> "></form></div>
</div>

<?php

			} else {

?>

<div class="wrap">
<h2><?php echo __("myTreasures installation - Part 1/3",$myTreasuresTextdomain); ?></h2>
<p><?php echo __("Thank you for choosing my plugin. The next steps will explain the usage of myTreasures. You can skip this installation if you like, but please only do so if you are already familiar with myTreasures.",$myTreasuresTextdomain); ?></p>
<p><?php echo __("<b>Generic Part</b><br />myTreasures was written to organize any kind of lists or collections by inputting media types. Therefor, in the first step, you have to create a media type.",$myTreasuresTextdomain); ?></p>
<div class="submit"><form action="" method="post" style="display: inline;"><input type="submit" class="button-primary" name="setupmediatype" value=" <?php echo __("continue to step 2",$myTreasuresTextdomain); ?> "></form> <form action="" method="post" style="display: inline;"><input type="submit" name="doneinstall" value=" <?php echo __("skip installation",$myTreasuresTextdomain); ?> "></form></div>
</div>

<?php

			}

		}
	
	}

?>