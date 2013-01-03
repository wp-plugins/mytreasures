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

		if(!isset($_GET['sortlist'])) {

			$_GET['sortlist'] = "default";

		}

		if(!isset($_POST['changefilter'])) {

			$_POST['changefilter'] = false;

		}

		if(!isset($_GET['showmedia'])) {

			$_GET['showmedia'] = false;

		}

		if(!isset($_POST['showmedia'])) {

			$_POST['showmedia'] = false;

		}

		switch($_GET['sortlist']) {

			case 'id':
				$order = "id";
				$orderquery = "`".$wpdb->prefix."mytreasures`.`id`";
				break;

			case 'title':
				$order = "title";
				$orderquery = "`".$wpdb->prefix."mytreasures`.`field01`";
				break;

			case 'type':
				$order = "type";
				$orderquery = "`".$wpdb->prefix."mytreasures_type`.`name`, `".$wpdb->prefix."mytreasures`.`field01`";
				break;

			default:
				$order = "title";
				$orderquery = "`".$wpdb->prefix."mytreasures`.`field01`";
				break;

		}

		if($_POST['changefilter']) {

			$_GET['showmedia'] = $_POST['showmedia'];

		}

		if($_GET['showmedia']) {

			$extendquery = "WHERE `".$wpdb->prefix."mytreasures_type`.`id` = '".$_GET['showmedia']."'";

		} else {

			$extendquery = false;

		} 

		if($_POST['showmedia']) {

			$extendlink = "&showmedia=".$_POST['showmedia'];

		} elseif($_GET['showmedia']) {

			$extendlink = "&showmedia=".$_GET['showmedia'];

		} else {

			$extendlink = false;

		}

		$italic1 = false;
		$italic2 = false;
		$italic3 = false;

		if($order == 'title') {

			$italic1 = "font-style: italic;";

		}

		if($order == 'type') {

			$italic2 = "font-style: italic;";

		}

		if($order == 'id') {

			$italic3 = "font-style: italic;";

		}

		$selectfilter = false;
		if(is_array($myTreasuresMediaTypeArray)) {

			foreach($myTreasuresMediaTypeArray AS $id => $name) {

				if($_POST['showmedia'] == $id || $_GET['showmedia'] == $id) {

					$selected = "selected=\"selected\"";

				} else {

					$selected = false;

				}

				$selectfilter .= "<option value=\"".$id."\" ".$selected.">".$name."</option>";
			}

		}

		if($myTreasures_options['option46']) {

			$pagenation = "<br /><br />".__("Page").":";
			$query01 = mysql_query("SELECT `".$wpdb->prefix."mytreasures`.*, `".$wpdb->prefix."mytreasures_type`.`name` FROM `".$wpdb->prefix."mytreasures` LEFT JOIN `".$wpdb->prefix."mytreasures_type` ON `".$wpdb->prefix."mytreasures`.`type` = `".$wpdb->prefix."mytreasures_type`.`id` ".$extendquery." ORDER BY ".$orderquery."");
			$allmedia = mysql_num_rows($query01);

			if(!$_GET['pagenation'] || $_GET['pagenation'] > ceil($allmedia/$myTreasures_options['option46']) || $_GET['pagenation'] < 0) {

				$_GET['pagenation'] = "1";

			}

			for($i = 1; $i <= ceil($allmedia/$myTreasures_options['option46']); $i++) {

				if($i == $_GET['pagenation']) {

					$style = " style=\"font-weight: bold;\"";

				} else {

					$style = false;

				}

				$pagenation .= " <a href=\"?page=mytreasures/mytreasuresadmin.php&pagenation=".$i."\"".$style.">".$i."</a>";

			}

			$query01 = mysql_query("SELECT `".$wpdb->prefix."mytreasures`.*, `".$wpdb->prefix."mytreasures_type`.`name` FROM `".$wpdb->prefix."mytreasures` LEFT JOIN `".$wpdb->prefix."mytreasures_type` ON `".$wpdb->prefix."mytreasures`.`type` = `".$wpdb->prefix."mytreasures_type`.`id` ".$extendquery." ORDER BY ".$orderquery." LIMIT ".(($_GET['pagenation']-1)*$myTreasures_options['option46']).", ".$myTreasures_options['option46']."");

		} else {

			$pagenation = false;
			$query01 = mysql_query("SELECT `".$wpdb->prefix."mytreasures`.*, `".$wpdb->prefix."mytreasures_type`.`name` FROM `".$wpdb->prefix."mytreasures` LEFT JOIN `".$wpdb->prefix."mytreasures_type` ON `".$wpdb->prefix."mytreasures`.`type` = `".$wpdb->prefix."mytreasures_type`.`id` ".$extendquery." ORDER BY ".$orderquery."");

		}

		if(mysql_num_rows($query01)) {

			$i = "0";
			$message = "<p style=\"float: left\">".__("Please click on the heading to sort the list!",$myTreasuresTextdomain).$pagenation."</p><form action=\"\" method=\"post\" style=\"display: inline;\"><p style=\"float: right\"><select name=\"showmedia\"><option value=\"\">".__("show all media",$myTreasuresTextdomain)."</option>".$selectfilter."</select> <input type=\"submit\" name=\"changefilter\" value=\"Filter\"></p></form><form name=\"myform\" action=\"?page=mytreasures/mytreasuresdelete.php&type=media\" method=\"post\" style=\"clear: both;\"><table class=\"widefat fixed\" cellspacing=\"0\"><thead><tr class=\"thead\"><th scope=\"col\" class=\"manage-column column-cb check-column\" style=\"\"><input type=\"checkbox\" /></th><th scope=\"col\" class=\"manage-column column-username\" style=\"\"><a href=\"?page=mytreasures/mytreasuresadmin.php&sortlist=title".$extendlink."\" style=\"font-weight: bold; ".$italic1."\">Titel</a></th><th scope=\"col\" class=\"manage-column column-name\" style=\"\"><a href=\"?page=mytreasures/mytreasuresadmin.php&sortlist=type".$extendlink."\" style=\"font-weight: bold; ".$italic2."\">".__("Type",$myTreasuresTextdomain)."</a></th><th scope=\"col\" class=\"manage-column column-posts num\" style=\"\"><a href=\"?page=mytreasures/mytreasuresadmin.php&sortlist=id".$extendlink."\" style=\"font-weight: bold; ".$italic3."\">ID</a></th></tr></thead><tfoot><tr class=\"thead\"><th scope=\"col\" class=\"manage-column column-cb check-column\" style=\"\"><input type=\"checkbox\" /></th><th scope=\"col\" class=\"manage-column column-username\" style=\"\"><a href=\"?page=mytreasures/mytreasuresadmin.php&sortlist=title".$extendlink."\" style=\"font-weight: bold; ".$italic1."\">Titel</a></th><th scope=\"col\" class=\"manage-column column-name\" style=\"\"><a href=\"?page=mytreasures/mytreasuresadmin.php&sortlist=type".$extendlink."\" style=\"font-weight: bold; ".$italic2."\">".__("Type",$myTreasuresTextdomain)."</a></th><th scope=\"col\" class=\"manage-column column-posts num\" style=\"\"><a href=\"?page=mytreasures/mytreasuresadmin.php&sortlist=id".$extendlink."\" style=\"font-weight: bold; ".$italic3."\">ID</a></th></tr></tfoot><tbody id=\"users\" class=\"list:user user-list\">";
			while($result01 = mysql_fetch_array($query01)) {

				if(++$i%2 == 0) {

					$class = "class='alternate'";

				} else {

					$class = false;

				}

				if($result01['rentto'] && $myTreasures_options['option29'] == 'yes') {

					$italic = "style=\"font-style: italic;\"";

				} else {

					$italic = false;

				}

				if($result01['rentto'] && $myTreasures_options['option29'] == 'yes') {

					$result01['field01'] = "<b>".__("Rent to",$myTreasuresTextdomain).":</b> ".$result01['rentto']." - ".$result01['field01'];

				}

				if(!$result01['image']) {

					$result01['field01'] .= " (<b>".__("No Image / Cover!",$myTreasuresTextdomain)."</b>)";

				}

				$message .= "<tr id=\"user-1\" ".$class."><th scope=\"row\" class=\"check-column\"><input type=\"checkbox\" name=\"deletemedia[".$result01['id']."]\" id=\"user_1\" value=\"".$result01['field01']."\" /></th><td class=\"username column-username\" ".$italic.">".$result01['field01']."<br /><div class=\"row-actions\"><span class=\"edit\"><a href=\"?page=mytreasures/mytreasuresedit.php&type=media&id=".$result01['id']."\">".__("Edit",$myTreasuresTextdomain)."</a> | <a href=\"?page=mytreasures/mytreasuresdelete.php&type=media&id=".$result01['id']."\">".__("Delete",$myTreasuresTextdomain)."</a> | <a href=\"?page=mytreasures/mytreasuresimages.php&id=".$result01['id']."\">".__("Administrate images",$myTreasuresTextdomain)."</a> | <a href=\"?page=mytreasures/mytreasureslinks.php&id=".$result01['id']."\">".__("Administrate links",$myTreasuresTextdomain)."</a></span></div></td><td class=\"name column-name\">".$result01['name']."</td><td class=\"posts column-posts num\">".$result01['id']."</td></tr>";

			}

			$message .= "</tbody></table><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"deletemarked\" value=\" ".__("Delete marked media",$myTreasuresTextdomain)." \"></div></form>";

		} else {

			$message = "<p>".__("No media in database!",$myTreasuresTextdomain)."</p>";

		}

		echo "<script type=\"text/javascript\">function markallmedia() { for(var i = 0; i < document.myform.elements.length; i++) { if(document.myform.elements[i].type == 'checkbox'){ document.myform.elements[i].checked = !(document.myform.elements[i].checked); } } document.myform.elements[0].checked = !(document.myform.elements[0].checked); }</script><div class=\"wrap\"><h2>".__("Overview",$myTreasuresTextdomain)."</h2>".$message."</div>";

	}

?>