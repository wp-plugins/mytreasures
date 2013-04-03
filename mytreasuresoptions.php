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

		for($i = 1; $i <= 60; $i++) {

			if($i < 10) {

				$i = "0".$i;

			}

			if(!isset($_POST['option'.$i])) {

				$_POST['option'.$i] = false;

			}

		}

		if(!isset($_POST['option10_tmp'])) {

			$_POST['option10_tmp'] = false;

		}

		if(!isset($_POST['option19_tmp'])) {

			$_POST['option19_tmp'] = false;

		}

		if($_POST['option01']) {

			$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_options` WHERE `id` = '1'");
			$result01 = mysql_fetch_array($query01);

			$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` ORDER BY `name`");
			while($result02 = mysql_fetch_array($query02)) {

				if(!$_POST['option10'][$result02['id']]) {

					$_POST['option10_tmp'] .= ";".$result02['id'].";";

				}

				if(!$_POST['option19'][$result02['id']]) {

					$_POST['option19_tmp'] .= ";".$result02['id'].";";

				}

			}

			if(!$_POST['option10']['allmedia']) {

				$_POST['option10_tmp'] .= ";allmedia;";

			}

			if(!$_POST['option19']['allmedia']) {

				$_POST['option19_tmp'] .= ";allmedia;";

			}

			$_POST['option10'] = $_POST['option10_tmp'];
			$_POST['option19'] = $_POST['option19_tmp'];
				
			if(!preg_match("/^([0-9]+)$/",$_POST['option33']) || !$_POST['option33'] || $_POST['option33'] > '100') {

				$_POST['option33'] = "25";

			}

			mysql_query("TRUNCATE TABLE `".$wpdb->prefix."mytreasures_options`");
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_options` (`id`, `version`, `changelog`, `option01`, `option02`, `option03`, `option04`, `option05`, `option06`, `option07`, `option08`, `option09`, `option10`, `option11`, `option12`, `option13`, `option14`, `option15`, `option16`, `option17`, `option18`, `option19`, `option20`, `option21`, `option22`, `option23`, `option24`, `option25`, `option26`, `option27`, `option28`, `option29`, `option30`, `option31`, `option32`, `option33`, `option34`, `option35`, `option36`, `option37`, `option38`, `option39`, `option40`, `option41`, `option42`, `option43`, `option44`, `option45`, `option46`, `option47`, `option48`, `option49`, `option50`, `option51`, `option52`, `option53`, `option54`, `option55`, `option56`, `option57`, `option58`, `option59`, `option60`) VALUES ('1', '".$result01['version']."', '".$result01['changelog']."', '".$_POST['option01']."', '".$_POST['option02']."', '".$_POST['option03']."', '".$_POST['option04']."', '".$_POST['option05']."', '".$_POST['option06']."', '".$_POST['option07']."', '".$_POST['option08']."', '".$_POST['option09']."', '".$_POST['option10']."', '".$_POST['option11']."', '".$_POST['option12']."', '".$_POST['option13']."', '".$_POST['option14']."', '".$_POST['option15']."', '".$_POST['option16']."', '".$_POST['option17']."', '".$_POST['option18']."', '".$_POST['option19']."', '".$_POST['option20']."', '".$_POST['option21']."', '".$_POST['option22']."', '".$_POST['option23']."', '".$_POST['option24']."', '".$_POST['option25']."', '".$_POST['option26']."', '".$_POST['option27']."', '".$_POST['option28']."', '".$_POST['option29']."', '".$_POST['option30']."', '".$_POST['option31']."', '".$_POST['option32']."', '".$_POST['option33']."', '".$_POST['option34']."', '".$_POST['option35']."', '".$_POST['option36']."', '".$_POST['option37']."', '".$_POST['option38']."', '".$_POST['option39']."', '".$_POST['option40']."', '".$_POST['option41']."', '".$_POST['option42']."', '".$_POST['option43']."', '".$_POST['option44']."', '".$_POST['option45']."', '".$_POST['option46']."', '".$_POST['option47']."', '".$_POST['option48']."', '".$_POST['option49']."', '".$_POST['option50']."', '".$_POST['option51']."', '".$_POST['option52']."', '".$_POST['option53']."', '".$_POST['option54']."', '".$_POST['option55']."', '".$_POST['option56']."', '".$_POST['option57']."', '".$_POST['option58']."', '".$_POST['option59']."', '".$_POST['option60']."')");
			echo '<div id="message" class="updated fade"><p><strong>'.__("Options saved successfully",$myTreasuresTextdomain).'</strong></p></div>';

		}

		$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_options` WHERE `id` = '1'");
		$result01 = mysql_fetch_array($query01);

	/*

		Liste der Optionen (Default: Großschrift)
		01 - Standard Ansicht der Liste (LIST / rating / covers / sort1 / sort2)
		02 - Markierung in der Listenansicht
		03 - Soll das Cover beim hochladen angepasst werden (yes / NO)
		04 - Wonach soll runter gerechnet werden (fixedheight / fixedwidth / fixedboth)
		05 - Höhe des Bildes (Breite wird errechnet)
		06 - Bretie des Bildes (Höhe wird errechnet)
		07 - Höhe ist fest
		08 - Breite ist fest
		09 - Umbruch für Cover
		10 - Coveransicht der Bereiche
		11 - Haben dieses Medium auch
		12 - Bewertungen in der Listenansicht anzeigen (yes / NO)
		13 - Soll der Text in der Einzelansicht um das Bild rumlaufen oder nicht (DIV / table)
		14 - Cover Großansicht (yes / NO)
		15 - Delimiter für CSV Import
		16 - Anzahl der Medien in der Listenansicht anzeigen (yes / NO)
		17 - Welches Galeriesystem (lightbox / thickbox)
		18 - Eigenes Galeriesystem
		19 - Bewertungsansicht
		20 - Amazon Verlinkung (YES / no)
		21 - Fielddelimiter für CSV Import
		22 - Bewertungen in Mischlisten
		23 - Coveransicht in Mischlisten
		24 - Welche User dürfen "User haben auch" Liste nutzen
		25 - Check ob es eine Installationsanleitung gab
		26 - Dauerhaftes Update der Rewrite Regeln (nein / JA)
		27 - Zeige bei Gruppierung die Anzahl (JA / nein)
		28 - Ansicht der Liste (LIST / glossar)
		29 - Möchte das "Verliehen an" System nutzen (nein / JA)
		30 - OverLIB Funktion (nein / JA)
		31 - Suche aktiviert (nein / JA)
		32 - Branding Text
		33 - Anzahl von Tracks (min. 1 // max 100)
		34 - Zeitstempel Letzte Aktion / Änderung (Zeitstempel für Rewrite Update)
		35 - Deaktivierung der Detailseite
		36 - Header (Sortierung & Suche) anzeigen
		37 - Sortierungsreihenfolge
		38 - Sortierungsfeld
		39 - Abschaltung des Bewertungssystems
		40 - Wonach soll runter gerechnet werden (fixedheight / fixedwidth / fixedboth)
		41 - Höhe des Bildes (Breite wird errechnet)
		42 - Breite des Bildes (Höhe wird errechnet)
		43 - Höhe ist fest
		44 - Breite ist fest
		45 - Soll das Kleine oder Mittlere Cover in der Detailansicht angezeigt werden
		46 - Anzahl der Medien pro Seite im Adminbereich
		47 - Artikelansicht mit Zeilenumbruch
		48 - FREI
		49 - FREI
		50 - FREI

	*/

?>

<div class="wrap">
<h2><?php echo __("Options",$myTreasuresTextdomain); ?></h2>
<form action="" method="post">
<input type="hidden" name="option25" value="<?php echo $result01['option25']; ?>">
<p>
<b><?php echo __("Default view",$myTreasuresTextdomain); ?></b>
<br /><input name="option01" type="radio" value="list" <?php if($result01['option01'] == '' || $result01['option01'] == 'list') { echo "checked=\"checked\""; } ?>/> <?php echo __("List",$myTreasuresTextdomain); ?>
<br /><input name="option01" type="radio" value="rating" <?php if($result01['option01'] == 'rating') { echo "checked=\"checked\""; } ?>/> <?php echo __("Ratings",$myTreasuresTextdomain); ?>
<br /><input name="option01" type="radio" value="sort1" <?php if($result01['option01'] == 'sort1') { echo "checked=\"checked\""; } ?>/> <?php echo __("Media type view definition #1 (If available!)",$myTreasuresTextdomain); ?>
<br /><input name="option01" type="radio" value="sort2" <?php if($result01['option01'] == 'sort2') { echo "checked=\"checked\""; } ?>/> <?php echo __("Media type view definition #2 (If available!)",$myTreasuresTextdomain); ?>
<br /><input name="option01" type="radio" value="sort3" <?php if($result01['option01'] == 'sort3') { echo "checked=\"checked\""; } ?>/> <?php echo __("Media type view definition #3 (If available!)",$myTreasuresTextdomain); ?>
<br /><input name="option01" type="radio" value="sort4" <?php if($result01['option01'] == 'sort4') { echo "checked=\"checked\""; } ?>/> <?php echo __("Media type view definition #4 (If available!)",$myTreasuresTextdomain); ?>
<br /><input name="option01" type="radio" value="sort5" <?php if($result01['option01'] == 'sort5') { echo "checked=\"checked\""; } ?>/> <?php echo __("Media type view definition #5 (If available!)",$myTreasuresTextdomain); ?>
<br /><input name="option01" type="radio" value="covers" <?php if($result01['option01'] == 'covers') { echo "checked=\"checked\""; } ?>/> <?php echo __("Covers",$myTreasuresTextdomain); ?>
</p>
<p>
<b><?php echo __("Image presentation script / gallerysystem",$myTreasuresTextdomain); ?></b>
<br /><?php echo __("Which system do you want to use:",$myTreasuresTextdomain); ?>
<br />&nbsp;&nbsp;&nbsp;<input name="option17" type="radio" value="" <?php if(!$result01['option17']) { echo "checked=\"checked\""; } ?>/> <?php echo __("no system, plain link to big image",$myTreasuresTextdomain); ?>
<br />&nbsp;&nbsp;&nbsp;<input name="option17" type="radio" value="prettyphoto" <?php if($result01['option17'] == 'prettyphoto')  { echo "checked=\"checked\""; } ?>/> prettyPhoto (<a href="http://www.no-margin-for-errors.com/projects/prettyPhoto-jquery-lightbox-clone/" target="_blank">Homepage</a>)
<br />&nbsp;&nbsp;&nbsp;<input name="option17" type="radio" value="thickbox" <?php if($result01['option17'] == 'thickbox')  { echo "checked=\"checked\""; } ?>/> Thickbox (<a href="http://jquery.com/demo/thickbox/" target="_blank">Homepage</a>)
<br />&nbsp;&nbsp;&nbsp;<input name="option17" type="radio" value="colorbox" <?php if($result01['option17'] == 'colorbox') { echo "checked=\"checked\""; } ?>/> Colorbox (<a href="http://colorpowered.com/colorbox/" target="_blank">Homepage</a>)
<br />&nbsp;&nbsp;&nbsp;<input name="option17" type="radio" value="fancybox" <?php if($result01['option17'] == 'fancybox') { echo "checked=\"checked\""; } ?>/> Fancybox (<a href="http://fancybox.net/" target="_blank">Homepage</a>)
<br />&nbsp;&nbsp;&nbsp;<input name="option17" type="radio" value="myown" <?php if($result01['option17'] == 'myown')  { echo "checked=\"checked\""; } ?>/> <?php echo __("my own code (will embedded into image link):",$myTreasuresTextdomain); ?> <input name="option18" type="text" value="<?php echo $result01['option18']; ?>"> 
</p>
<p>
<b><?php echo __("Cover options",$myTreasuresTextdomain); ?></b>
<br /><?php echo __("Force line break in cover view:",$myTreasuresTextdomain); ?> <select name="option09" style="width: 200px;"><option value=""><?php echo __("No line break",$myTreasuresTextdomain); ?></option><?php for($countcovers = 1; $countcovers <= 5; $countcovers++) { ?><option value="<?php echo $countcovers; ?>" <?php if($countcovers == $result01['option09']) { echo "selected"; } ?>><?php echo sprintf(__("After %s cover",$myTreasuresTextdomain),$countcovers); ?></option><?php } ?></select>
<br /><input name="option03" type="radio" value="no" <?php if($result01['option03'] == '' || $result01['option03'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Do not change cover",$myTreasuresTextdomain); ?>
<br /><input name="option03" type="radio" value="yes" <?php if($result01['option03'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Resize cover with following rules:",$myTreasuresTextdomain); ?>
<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo __("Small cover",$myTreasuresTextdomain); ?></b>
<br />&nbsp;&nbsp;&nbsp;<input name="option04" type="radio" value="fixedheight" <?php if($result01['option04'] == 'fixedheight') { echo "checked=\"checked\""; } ?>/> <?php echo __("Fixed height",$myTreasuresTextdomain); ?> <input type="text" name="option05" value="<?php echo $result01['option05']; ?>" style="width: 40px;">px, <?php echo __("calculate width.",$myTreasuresTextdomain); ?>
<br />&nbsp;&nbsp;&nbsp;<input name="option04" type="radio" value="fixedwidth" <?php if($result01['option04'] == 'fixedwidth') { echo "checked=\"checked\""; } ?>/> <?php echo __("Fixed width",$myTreasuresTextdomain); ?> <input type="text" name="option06" value="<?php echo $result01['option06']; ?>" style="width: 40px;">px, <?php echo __("calculate height.",$myTreasuresTextdomain); ?>
<br />&nbsp;&nbsp;&nbsp;<input name="option04" type="radio" value="fixedboth" <?php if($result01['option04'] == 'fixedboth') { echo "checked=\"checked\""; } ?>/> <?php echo __("Fixed height",$myTreasuresTextdomain); ?> <input type="text" name="option07" value="<?php echo $result01['option07']; ?>" style="width: 40px;">px, <?php echo __("Fixed width",$myTreasuresTextdomain); ?> <input type="text" name="option08" value="<?php echo $result01['option08']; ?>" style="width: 40px;">px.
<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo __("Medium cover",$myTreasuresTextdomain); ?></b>
<br />&nbsp;&nbsp;&nbsp;<input name="option40" type="radio" value="fixedheight" <?php if($result01['option40'] == 'fixedheight') { echo "checked=\"checked\""; } ?>/> <?php echo __("Fixed height",$myTreasuresTextdomain); ?> <input type="text" name="option41" value="<?php echo $result01['option41']; ?>" style="width: 40px;">px, <?php echo __("calculate width.",$myTreasuresTextdomain); ?>
<br />&nbsp;&nbsp;&nbsp;<input name="option40" type="radio" value="fixedwidth" <?php if($result01['option40'] == 'fixedwidth') { echo "checked=\"checked\""; } ?>/> <?php echo __("Fixed width",$myTreasuresTextdomain); ?> <input type="text" name="option42" value="<?php echo $result01['option42']; ?>" style="width: 40px;">px, <?php echo __("calculate height.",$myTreasuresTextdomain); ?>
<br />&nbsp;&nbsp;&nbsp;<input name="option40" type="radio" value="fixedboth" <?php if($result01['option40'] == 'fixedboth') { echo "checked=\"checked\""; } ?>/> <?php echo __("Fixed height",$myTreasuresTextdomain); ?> <input type="text" name="option43" value="<?php echo $result01['option43']; ?>" style="width: 40px;">px, <?php echo __("Fixed width",$myTreasuresTextdomain); ?> <input type="text" name="option44" value="<?php echo $result01['option44']; ?>" style="width: 40px;">px.
<br /><br /><input name="option14" type="radio" value="no" <?php if($result01['option14'] == '' || $result01['option14'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Don't show big cover / image",$myTreasuresTextdomain); ?>
<br /><input name="option14" type="radio" value="yes" <?php if($result01['option14'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show big cover / image",$myTreasuresTextdomain); ?>
<br /><input name="option30" type="radio" value="no" <?php if($result01['option30'] == '' || $result01['option30'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Don't show big cover / image on mouseover",$myTreasuresTextdomain); ?>
<br /><input name="option30" type="radio" value="yes" <?php if($result01['option30'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show big cover / image on mouseover",$myTreasuresTextdomain); ?>
<br /><?php echo __("Insert this branding text to the bottom right corner of the covers:",$myTreasuresTextdomain); ?>
<br /><input name="option32" type="text" value="<?php echo addslashes(htmlentities($result01['option32'])); ?>">
</p>
<p>
<b><?php echo __("Options for list view",$myTreasuresTextdomain); ?></b>
<br /><?php echo __("You can setup a sign for the list view, which will displayed infront of every entry",$myTreasuresTextdomain); ?>
<br /><input name="option02" type="text" value="<?php echo addslashes(htmlentities($result01['option02'])); ?>">
<br /><input name="option12" type="radio" value="no" <?php if($result01['option12'] == '' || $result01['option12'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Do not show ratings",$myTreasuresTextdomain); ?>
<br /><input name="option12" type="radio" value="yes" <?php if($result01['option12'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show ratings",$myTreasuresTextdomain); ?>
<br /><input name="option16" type="radio" value="no" <?php if($result01['option16'] == '' || $result01['option16'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Do not show media count",$myTreasuresTextdomain); ?>
<br /><input name="option16" type="radio" value="yes" <?php if($result01['option16'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show media count",$myTreasuresTextdomain); ?>
<br /><input name="option28" type="radio" value="list" <?php if($result01['option28'] == '' || $result01['option28'] == 'list') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show list view",$myTreasuresTextdomain); ?>
<br /><input name="option28" type="radio" value="glossar" <?php if($result01['option28'] == 'glossar') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show glossar view",$myTreasuresTextdomain); ?>
<br /><input name="option28" type="radio" value="article1" <?php if($result01['option28'] == 'article1') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show article style view #1 (Picure & Name)",$myTreasuresTextdomain); ?>
<br /><input name="option28" type="radio" value="article2" <?php if($result01['option28'] == 'article2') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show article style view #2 (Picure, Name & Description)",$myTreasuresTextdomain); ?>
<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="option47" value="yes" <?php if($result01['option47'] == 'yes') { echo "checked=\"checked\""; } ?> /> <?php echo __("Show description with line break",$myTreasuresTextdomain); ?>
<br /><input name="option35" type="radio" value="yes" <?php if($result01['option35'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Do not link to detailspage",$myTreasuresTextdomain); ?>
<br /><input name="option35" type="radio" value="no" <?php if($result01['option35'] == '' || $result01['option35'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Link to detailslink",$myTreasuresTextdomain); ?>
<br /><input name="option38" type="radio" value="name" <?php if($result01['option38'] == '' || $result01['option38'] == 'name') { echo "checked=\"checked\""; } ?>/> <?php echo __("Order by name",$myTreasuresTextdomain); ?>
<br /><input name="option38" type="radio" value="id" <?php if($result01['option38'] == 'id') { echo "checked=\"checked\""; } ?>/> <?php echo __("Order by ID",$myTreasuresTextdomain); ?>
<br /><input name="option37" type="radio" value="asc" <?php if($result01['option37'] == '' || $result01['option37'] == 'asc') { echo "checked=\"checked\""; } ?>/> <?php echo __("Ascending order",$myTreasuresTextdomain); ?>
<br /><input name="option37" type="radio" value="desc" <?php if($result01['option37'] == 'desc') { echo "checked=\"checked\""; } ?>/> <?php echo __("Descending order",$myTreasuresTextdomain); ?>
</p>
<p>
<b><?php echo __("Options for single view ([mytreasure=&#36;Number])",$myTreasuresTextdomain); ?></b>
<br /><input name="option13" type="radio" value="div" <?php if($result01['option13'] == '' || $result01['option13'] == 'div') { echo "checked=\"checked\""; } ?>/> <?php echo __("Text / tracklist float the picture",$myTreasuresTextdomain); ?>
<br /><input name="option13" type="radio" value="table" <?php if($result01['option13'] == 'table') { echo "checked=\"checked\""; } ?>/> <?php echo __("Text / tracklist are strictly seperated from the picture",$myTreasuresTextdomain); ?>
<br /><input name="option45" type="radio" value="no" <?php if($result01['option45'] == '' || $result01['option45'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show small cover on details page",$myTreasuresTextdomain); ?>
<br /><input name="option45" type="radio" value="yes" <?php if($result01['option45'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show medium cover on details page",$myTreasuresTextdomain); ?>
</p>
<p>
<b><?php echo __("Options for combination view ([mytreasurelist=&#36;type1,&#36;type2...])",$myTreasuresTextdomain); ?></b>
<br /><input name="option22" type="checkbox" value="1" <?php if($result01['option22'] == '1') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show ratings view",$myTreasuresTextdomain); ?>
<br /><input name="option23" type="checkbox" value="1" <?php if($result01['option23'] == '1') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show cover view",$myTreasuresTextdomain); ?>
</p>
<p>
<b><?php echo __("Common options",$myTreasuresTextdomain); ?></b>
<br /><input name="option15" type="text" value="<?php echo addslashes(htmlentities($result01['option15'])); ?>" style="width: 50px;"> <?php echo __("Field delimiter CSV Import",$myTreasuresTextdomain); ?>
<br /><input name="option21" type="text" value="<?php echo addslashes(htmlentities($result01['option21'])); ?>" style="width: 50px;"> <?php echo __("Text block delimiter CSV Import",$myTreasuresTextdomain); ?>
<br /><input name="option33" type="text" value="<?php echo addslashes(htmlentities($result01['option33'])); ?>" style="width: 50px;"> <?php echo __("Maximum tracks for media with tracklist (min. 1 and max. 100)",$myTreasuresTextdomain); ?>
<br /><input name="option46" type="text" value="<?php echo addslashes(htmlentities($result01['option46'])); ?>" style="width: 50px;"> <?php echo __("Mediacount per page on administration page (0 means no pagenation)",$myTreasuresTextdomain); ?>
<br /><input name="option26" type="radio" value="no" <?php if($result01['option26'] == '' || $result01['option26'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Create rewrite rules if something has changed",$myTreasuresTextdomain); ?>
<br /><input name="option26" type="radio" value="yes" <?php if($result01['option26'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Create rewrite rules with every call (if other plugins do this, you need myTreasures to do the same!)",$myTreasuresTextdomain); ?>
<br /><input name="option31" type="radio" value="no" <?php if($result01['option31'] == '' || $result01['option31'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Deactivate search",$myTreasuresTextdomain); ?>
<br /><input name="option31" type="radio" value="yes" <?php if($result01['option31'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Activate search",$myTreasuresTextdomain); ?>
<br /><input name="option39" type="radio" value="yes" <?php if($result01['option39'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Deactivate rating system",$myTreasuresTextdomain); ?>
<br /><input name="option39" type="radio" value="no" <?php if($result01['option39'] == '' || $result01['option39'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Activate rating system",$myTreasuresTextdomain); ?>
<br /><input name="option11" type="radio" value="no" <?php if($result01['option11'] == '' || $result01['option11'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Other users can't add their names to the \"User with this media\" list",$myTreasuresTextdomain); ?>
<br /><input name="option11" type="radio" value="yes" <?php if($result01['option11'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Other users can add their names to the \"User with this media\" list",$myTreasuresTextdomain); ?>
<br /><?php echo __("Needed userlevel to use the \"User with this media\" list",$myTreasuresTextdomain); ?> <select name="option24"><option value=""><?php echo __("No user allowed",$myTreasuresTextdomain); ?></option><option value="edit_files" <?php if($result01['option24'] == 'edit_files') { echo "selected=\"selected\""; } ?>><?php echo __("Administrator",$myTreasuresTextdomain); ?></option><option value="manage_links" <?php if($result01['option24'] == 'manage_links') { echo "selected=\"selected\""; } ?>><?php echo __("Editor",$myTreasuresTextdomain); ?></option><option value="upload_files" <?php if($result01['option24'] == 'upload_files') { echo "selected=\"selected\""; } ?>><?php echo __("Author",$myTreasuresTextdomain); ?></option><option value="edit_posts" <?php if($result01['option24'] == 'edit_posts') { echo "selected=\"selected\""; } ?>><?php echo __("Contributor",$myTreasuresTextdomain); ?></option><option value="read" <?php if($result01['option24'] == 'read') { echo "selected=\"selected\""; } ?>><?php echo __("Subscriber",$myTreasuresTextdomain); ?></option></select>
<br /><input name="option27" type="radio" value="no" <?php if($result01['option27'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Do not show mediacount in rating / custom view",$myTreasuresTextdomain); ?>
<br /><input name="option27" type="radio" value="yes" <?php if($result01['option27'] == '' || $result01['option27'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Show mediacount in rating / custom view",$myTreasuresTextdomain); ?>
<br /><input name="option29" type="radio" value="no" <?php if($result01['option29'] == '' || $result01['option29'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Do not use the rent to system, to show rented media",$myTreasuresTextdomain); ?>
<br /><input name="option29" type="radio" value="yes" <?php if($result01['option29'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Do use the rent to system, to show rented media",$myTreasuresTextdomain); ?>
<br /><input name="option36" type="radio" value="no" <?php if($result01['option36'] == '' || $result01['option36'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Do show header list with view options & search field",$myTreasuresTextdomain); ?>
<br /><input name="option36" type="radio" value="yes" <?php if($result01['option36'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Do not show header list with view options & search field",$myTreasuresTextdomain); ?>
<br /><input name="option20" type="radio" value="no" <?php if($result01['option20'] == '' || $result01['option20'] == 'no') { echo "checked=\"checked\""; } ?>/> <?php echo __("Link to amazon.de",$myTreasuresTextdomain); ?>
<br /><input name="option20" type="radio" value="yes" <?php if($result01['option20'] == 'yes') { echo "checked=\"checked\""; } ?>/> <?php echo __("Do not link to amazon.de",$myTreasuresTextdomain); ?>
<br />
<br /><?php echo __("<b>Developer comment</b>:<br />If you link your media with amazon, my partnerID will be transmitted and if someone actual buy something, i'll get a refund of 5%. You don't have to active amazon.de linking for using this plugin!",$myTreasuresTextdomain); ?>
<br />
<br />
</p>
<p>
<b><?php echo __("Coverview activation",$myTreasuresTextdomain); ?></b>
<br /><?php echo __("Please select which media types have a cover overview:",$myTreasuresTextdomain); ?>
<br /><input type="checkbox" name="option10[allmedia]" value="1" <?php if(!preg_match("/;allmedia;/",$result01['option10'])) { echo "checked"; } ?>> <?php echo __("All media display",$myTreasuresTextdomain); ?>

<?php

	$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` ORDER BY `name`");
	while($result02 = mysql_fetch_array($query02)) {

?>

<br /><input type="checkbox" name="option10[<?php echo $result02['id']; ?>]" value="1" <?php if(!preg_match("/;".$result02['id'].";/",$result01['option10'])) { echo "checked"; } ?>> <?php echo $result02['name']; ?>

<?php

	}

?>
</p>
<p>
<b><?php echo __("Ratingview activation",$myTreasuresTextdomain); ?></b>
<br /><?php echo __("Please select which media types have a rating overview:",$myTreasuresTextdomain); ?>
<br /><input type="checkbox" name="option19[allmedia]" value="1" <?php if(!preg_match("/;allmedia;/",$result01['option19'])) { echo "checked"; } ?>> <?php echo __("All media display",$myTreasuresTextdomain); ?>

<?php

	$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` ORDER BY `name`");
	while($result02 = mysql_fetch_array($query02)) {

?>

<br /><input type="checkbox" name="option19[<?php echo $result02['id']; ?>]" value="1" <?php if(!preg_match("/;".$result02['id'].";/",$result01['option19'])) { echo "checked"; } ?>> <?php echo $result02['name']; ?>

<?php

	}

?>
</p>
<div class="submit"><input type="submit" class="button-primary" value=" <?php echo __("Save options",$myTreasuresTextdomain); ?> "></div>
</form>
</div>

<?php

	}

?>