<?php

	if(isset($_POST['donereadingchangelog'])) {

		mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `changelog` = '".$myTreasuresPluginVersion."' WHERE `id` = '1'");
		echo "<div class=\"wrap\"><h2>Changelog</h2><p>Have fun with the new Version!</p></div>" ;

	} else {

?>

<form action="" method="post">
<div class="wrap">
<h2>myTreasures</h2>
<p>myTreasures frisst viel Zeit und Entwicklung. Bitte schaut doch mal auf die "Info" Seite, ob Ihr die Entwicklung von myTreasures nicht unterst&uuml;tzen wollt!<br />Developing myTreasures is a big time killer. Please have a look on the "info" site if you want to support this plugin!</p>
<h2>Changelog</h2>
<p><b>New in 2.4.10 // Neues in 2.4.10</b>
<ul>
	<li>Added french translation - thx to Benjamin // Franz. &Uuml;bersetzung eingebaut - Danke an Benjamin</li>
	<li>Option to show lie break in list view // Option hinzugef&uuml;gt um einen Zeilenumbruch in der Listenansicht zu erm&ouml;glichen.</li>
</ul>
</p>
<div class="submit"><input type="submit" class="button-primary" name="donereadingchangelog" value=" <?php echo __("Read changelog, continue with normale use",$myTreasuresTextdomain); ?> "></div>
</form>
<p>&nbsp;</p><p>&nbsp;</p>
<p><b>New in 2.4.9 // Neues in 2.4.9</b>
<ul>
	<li>Added pagenation on administration page // Seitenfunktion auf der Verwaltungssseite eingebaut</li>
</ul>
</p>
<p><b>New in 2.4.8 // Neues in 2.4.8</b>
<ul>
	<li>Added UTF8 Converter for csv import // UTF8 Konvertierung f&uuml;r CSV Import hinzugef&uuml;gt</li>
</ul>
</p>
<p><b>New in 2.4.7 // Neues in 2.4.7</b>
<ul>
	<li>Updates for Wordpress Version 3.3.1 // Updates f&uuml;r Wordpress Version 3.3.1</li>
</ul>
</p>
<p><b>New in 2.4.6 // Neues in 2.4.6</b>
<ul>
	<li>Updates for Wordpress Version 3.3 // Updates f&uuml;r Wordpress Version 3.3</li>
</ul>
</p>
<p><b>New in 2.4.5 // Neues in 2.4.5</b>
<ul>
	<li>Change some OFDB gateway settings to match changes // OFDB Gatewaydaten umgestellt um die letzten &Auml;nderungen einzupflegen</li>
</ul>
</p>
<p><b>New in 2.4.4 // Neues in 2.4.4</b>
<ul>
	<li>Change OFDB Gateway URL // OFDB Gatway Adresse ge&auml;ndert</li>
	<li>Fixed some sql statements to match strict servers // Einige SQL Statements ge&auml;ndert um auf STRICT Servern keine Fehler zu produzieren</li>
</ul>
</p>
<p><b>New in 2.4.3 // Neues in 2.4.3</b>
<ul>
	<li>Fixed options page (not saving options) for users with new installations after 2.4  // Fehler in der Optionsseite (Nichtspeicherung von Daten) f&uuml;r neue Benutzer nach Version 2.4</li>
</ul>
</p>
<p><b>New in 2.4.2 // Neues in 2.4.2</b>
<ul>
	<li>Fixed missing image saving adding media through OFDB gateway // Fehlendes Speichern des Covers durch die OFDB Anbindung korrigiert</li>
</ul>
</p>
<p><b>New in 2.4.1 // Neues in 2.4.1</b>
<ul>
	<li>Fixed missing fields of OFDB gateway // Fehlende Felder in der OFDB Anbindung korrigiert</li>
</ul>
</p>
<p><b>New in 2.4 // Neues in 2.4</b>
<ul>
	<li>Option to deactivate ratingsystem // M&ouml;glichkeit geschaffen das Bewertungssystem abzuschalten</li>
	<li>Added new medium size cover for details page (Optional instead of small cover) // Neues Cover in mittlere Gr&ouml;&szlig;e hinzugef&uuml;gt für die Detail Ansicht (Optional statt dem normalen)</li>
	<li>Added article style list view // Es wurde eine Artikel&auml;hnliche Ansicht hinzugef&uuml;gt</li>
	<li>Added full css support for all views // F&uuml;r alle Ansichten gibt es nun vollen CSS Support</li>
	<li>Fixed minor problems with options // Kleinere Probleme mit den Optionen behoben</li>
	<li>Big code cleanup // Umfangreiche S&auml;uberung des Quellcodes</li>
</ul>
</p>
<p><b>New in 2.3 // Neues in 2.3</b>
<ul>
	<li>Expand search to all fields, not just name // Erweiterung der Suche auf alle Felder, nicht nur den Namen</li>
	<li>Added sort type (Name or ID) and order typ (asc or desc) to list view // Die Listenansicht wurde um die Wahl zwischen Sortierung nach Namen oder ID, sowie auf- oder absteigend erweitert</li>
</ul>
</p>
<p><b>New in 2.2 (.4/.5/.6) // Neues in 2.2 (.4/.5/.6)</b>
<ul>
	<li>Fixed CSV Import problem with activ field & text delimiter // CSV Import Probleme bei der Nutzung von Text- & Feldtrennzeichen behoben</li>
	<li>Extend list view with option to show just the list, no detailspage link for media // In der Listenansicht kann man den Link zur Detailseite abschalten und nur eine reine Liste anzeigen lassen</li>
	<li>Fixed two broken files for WP 3.0 // Zwei defekte Dateien an WP 3.0 angepasst</li>
	<li>Fixed problems with multiple cover upload // Problem beim Massenupload von Covern gefixt</li>
	<li>Added option to shutdown header (view list & search field) // Option zum Abschalten der Kopfzeie (Ansichtenwahl & Suchfeld) eingebaut</li>
</ul>
</p>
<p><b>New in 2.2 (.2/.3) // Neues in 2.2 (.2/.3)</b>
<ul>
	<li>2.2.2 is just a small fix // 2.2.2 ist nur kleiner Fix</li>
	<li>2.2.3 fixes the broken "Edit this entry" link in media details // 2.2.3 fixt den kaputten "Eintrag bearbeiten" Link in der Detailansicht des Eintrages</li>
</ul>
</p>
<p><b>New in 2.2 (.1) // Neues in 2.2 (.1)</b>
<ul>
	<li>Some Updates on Adminarea (Filters) // Updates f&uuml;r den Adminbereich (z.B. Filter in der &Uuml;bersicht)</li>
	<li>User with this Media Option for every media // Liste in den Medien, welche Nutzer des Blogs dieses auch haben</li>
	<li>EAN Search for OFDB // EAN Suche in der OFDB</li>
	<li>Show up to 2 media attributes (besides the name) on list view // Bis zu 2 Attributen (neben dem Namen) eines Medientypen in der Listenansicht</li>
	<li>Show/Hide media attributes on details view // Bestimmte Attribute in der Detailansicht Ein/Ausblenden</li>
	<li>New filesystem for plugin & jquery gallerysystems // Neue Datenstruktur des Plugins und neue jquery Galeriesysteme</li>
	<li>Code cleanup // Code aufger&auml;umt</li>
	<li>2.2.1 is just a small fix // 2.2.1 ist nur kleiner Fix</li>
	<li>More i just forgot ;) // Vieles kleines was ich vergessen habe ;)</li>
</ul>
</p>
<p><b>New in 2.1 // Neues in 2.1</b>
<ul>
	<li>Fixed bug with multiple myTreasure codes on the same page // Probleme bei der mehrfachen Nutzung von myTreasures auf einer einzigen Seite behoben</li>
	<li>Option for max. tracks per media instead of hardcoded 25 // Man kann nun einstellen wieviele Tracks eine CD hat, statt der festen max. 25</li>
</ul>
</p>
<p><b>New in 2.0 // Neues in 2.0</b>
<ul>
	<li>Updated all pages to WP 2.8 Theme // Alle Seiten an den WP 2.8 Theme angepasst</li>
	<li>ofdb Gateway (german movies only) // OFDB Schnittstelle (F&uuml;r deutsche Filme)</li>
	<li>ofdb dashboard widget with quick search // OFDB Dashboard Widget mit Schnellsuche</li>
	<li>code cleanup // Quellcode aufger&auml;umt</li>
</ul>
</p>
<p><b>New in 1.0.10 // Neues in 1.0.10</b>
<ul>
	<li>Erneutes Problem bei der Coveranzeige behoben // Fixed new problem with coverimages</li>
</ul>
</p>
<p><b>New in 1.0.9 // Neues in 1.0.9</b>
<ul>
	<li>Problem beim MouseOver behoben // Fixed problem on mouseover</li>
	<li>Problem bei der Coveranzeige behoben // Fixed problem with coverimages</li>
	<li>Newsletter deaktiviert // Disabled newsletter</li>
</ul>
</p>
<p><b>New in 1.0.8 // Neues in 1.0.8</b>
<ul>
	<li>L&auml;uft fehlerfrei mit WP 2.7 // Works with WP 2.7</li>
	<li>Neues "Look & Feel" // New "look & feel"</li>
</ul>
</p>
<p><b>New in 1.0.7 // Neues in 1.0.7</b>
<ul>
	<li>L&ouml;schung von mehreren Eintr&auml;gen zugleich // Added feature to delete multiple media at once</li>
	<li>Neue Art der Installation // Changed way of installation (wordpress hook)</li>
	<li>Text Branding m&ouml;glich f&uuml; Cover // Added text branding of cover images</li>
	<li>default.jpg von wp-content/mytreasures/ nach wp-content/plugins/mytreasures/images/ verschoben // Moved default.jpg from wp-content/mytreasures/ into wp-content/plugins/mytreasures/images/</li>
</ul>
</p>
<p><b>New in 1.0.6 // Neues in 1.0.6</b>
<ul>
	<li>DB Bug bei frischer Neuinstall // Database bug @ first install</li>
	<li>Funktionen umbenannt // renamed some functions</li>
</ul>
</p>
<p><b>New in 1.0.5 // Neues in 1.0.5</b>
<ul>
	<li>Suche wieder aktivierbar // Search function is back!</li>
	<li>Code aufger&auml;umt // code clean up</li>
</ul>
</p>
<p><b>New in 1.0.4 // Neues in 1.0.4</b>
<ul>
	<li>K&uuml;rzerer Hinweis auf das Plugin // Copyright is shorter</li>
	<li>Link Datenbank f&uuml;r Medien // link database for media</li>
	<li>Kleine Design Fehler behoben // fixed some small display problems</li>
</ul>
</p>
<p><b>New in 1.0.3 // Neues in 1.0.3</b>
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