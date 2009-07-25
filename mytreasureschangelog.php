<?php

	if($_POST[donereadingchangelog]) {

		mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `changelog` = '".$myTreasuresPluginVersion."' WHERE `id` = '1'");
		echo "<div class=\"wrap\"><h2>Changelog</h2><p>Have fun with the new Version!</p></div>" ;

	} else {

?>

<form action="" method="post">
<div class="wrap">
<h2>myTreasures</h2>
<p>myTreasures frisst viel Zeit und Entwicklung. Bitte schaut doch mal auf die "Info" Seite, ob Ihr die Entwicklung von myTreasures nicht unterst&uuml;tzen wollt!<br />Developing myTreasures is a big time killer. Please have a look on the "info" site if you want to support this plugin!</p>
<h2>Changelog</h2>
<p><b>Neues in 2.0 // New in 2.0</b>
<ul>
	<li>Updated all pages to WP 2.8 Theme // Alle Seiten an den WP 2.8 Theme angepasst</li>
	<li>ofdb Gateway (german movies only) // OFDB Schnittstelle (F&uuml;r deutsche Filme)</li>
	<li>ofdb dashboard widget with quick search // OFDB Dashboard Widget mit Schnellsuche</li>
	<li>code cleanup // Quellcode aufger&auml;umt</li>
</ul>
</p>
<div class="submit"><input type="submit" class="button-primary" name="donereadingchangelog" value=" <?php echo __("Read changelog, continue with normale use",$myTreasuresTextdomain); ?> "></div>
</form>
<br /><br />
<p><b>Neues in 1.0.10 // New in 1.0.10</b>
<ul>
	<li>Erneutes Problem bei der Coveranzeige behoben // Fixed new problem with coverimages</li>
</ul>
</p>
<p><b>Neues in 1.0.9 // New in 1.0.9</b>
<ul>
	<li>Problem beim MouseOver behoben // Fixed problem on mouseover</li>
	<li>Problem bei der Coveranzeige behoben // Fixed problem with coverimages</li>
	<li>Newsletter deaktiviert // Disabled newsletter</li>
</ul>
</p>
<p><b>Neues in 1.0.8 // New in 1.0.8</b>
<ul>
	<li>L&auml;uft fehlerfrei mit WP 2.7 // Works with WP 2.7</li>
	<li>Neues "Look & Feel" // New "look & feel"</li>
</ul>
</p>
<p><b>Neues in 1.0.7 // New in 1.0.7</b>
<ul>
	<li>L&ouml;schung von mehreren Eintr&auml;gen zugleich // Added feature to delete multiple media at once</li>
	<li>Neue Art der Installation // Changed way of installation (wordpress hook)</li>
	<li>Text Branding m&ouml;glich f&uuml; Cover // Added text branding of cover images</li>
	<li>default.jpg von wp-content/mytreasures/ nach wp-content/plugins/mytreasures/images/ verschoben // Moved default.jpg from wp-content/mytreasures/ into wp-content/plugins/mytreasures/images/</li>
</ul>
</p>
<p><b>Neues in 1.0.6 // New in 1.0.6</b>
<ul>
	<li>DB Bug bei frischer Neuinstall // Database bug @ first install</li>
	<li>Funktionen umbenannt // renamed some functions</li>
</ul>
</p>
<p><b>Neues in 1.0.5 // New in 1.0.5</b>
<ul>
	<li>Suche wieder aktivierbar // Search function is back!</li>
	<li>Code aufger&auml;umt // code clean up</li>
</ul>
</p>
<p><b>Neues in 1.0.4 // New in 1.0.4</b>
<ul>
	<li>K&uuml;rzerer Hinweis auf das Plugin // Copyright is shorter</li>
	<li>Link Datenbank f&uuml;r Medien // link database for media</li>
	<li>Kleine Design Fehler behoben // fixed some small display problems</li>
</ul>
</p>
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
</div>

<?php

	}

?>