<?php

	if($_POST[donereadingchangelog]) {

		mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `changelog` = '1' WHERE `id` = '1'");
		echo "<div class=\"wrap\"><h2>Changelog</h2><p>Have fun with the new Version!</p></div>" ;

	} else {

?>

<form action="" method="post">
<div class="wrap">
<h2>Changelog</h2>
<p><b>Neues in 1.0.4 // New in 1.0.4</b>
<ul>
	<li>K&uuml;rzerer Hinweis auf das Plugin // Copyright is shorter</li>
	<li>Link Datenbank f&uuml;r Medien // link database for media</li>
	<li>Kleine Design Fehler behoben // fixed some small display problems</li>
</ul>
</p>
<div class="submit"><input type="submit" name="donereadingchangelog" value=" <?php echo __("Read changelog, continue with normale use",$myTreasuresTextdomain); ?> "></div>
</div>
</form>
<br /><br />
<p><b>Neues in 1.0.3 // New in 1.0.3</b>
<ul>
	<li>Ab jetzt gibts diesen Changelog // Using this Changelog</li>
	<li>Massenupload von Covern // Massupload of cover images</li>
	<li>10 statt 1 Medien bewerten (Bewertungslink aus der Navigation)// Rating 10 instead of 1 media (ratings link in navi)</li>
	<li>Grosses Cover wenn aktiviert mit "MouseOver" Anzeige mit Overlib // If you save the big cover image, the system shows it with overlib on a mouseover event</li>
	<li>Zur&uuml;ck Button wenn in einem normalen Beitrag die Liste angezeigt wird // Back button if you show a complete list in a single post</li>
	<li>Markierung von verliehenen Medien (Optional und nur in der Gesamt &Uuml;bersicht im Admin Bereich) // Mark rented media (optional and just in admin area overview)</li>
	<li>M&ouml;glichkeit ein Medium in einen anderen Typen zu verschieben // It's now possible to change a media type</li>
	<li>Glossaransicht als Option f&uuml;r die Liste // Glossar view as an option for list view</li>
	<li>Option ob die Zahl der Medien innerhalb der eigenen Sortierung gezeigt werden soll (z.B. Genre)// Option if you want to show the media count of an own sort (e.g. Genre)</li>
</ul>
</p>

<?php

	}

?>