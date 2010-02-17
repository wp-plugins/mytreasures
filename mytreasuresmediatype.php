<?php

	if($_POST[amazonok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'no' WHERE `id` = '1'"); $myTreasures_options[option20] = "no"; } 
	if($_POST[amazonnok]) { mysql_query("UPDATE `".$wpdb->prefix."mytreasures_options` SET `option20` = 'yes' WHERE `id` = '1'"); $myTreasures_options[option20] = "yes"; } 
	$checksystem = myTreasuresCheckWorkspace(current_user_can('edit_plugins'));

	if($checksystem) {

		if($checksystem['message']) { echo $checksystem['message']; }
		if($checksystem['include']) { include($checksystem['include']); }

	} else {

		if(strlen($_POST[short]) > 0 && preg_match("/^([a-zA-Z0-9]+)$/",$_POST[short])) {

			$check_doubleshort = mysql_num_rows(mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` WHERE `short` = '".strtolower($_POST[short])."'"));

		}

		if(!$check_doubleshort && $_POST[name] && $_POST[field01] && preg_match("/^([a-zA-Z0-9]+)$/",$_POST[short])) {

			if($_POST[feature_sort1]) { $feature_sort1 = $_POST[feature_sort1]; }
			if($_POST[feature_sort2]) { $feature_sort2 = $_POST[feature_sort2]; }
			if($_POST[feature_sort3]) { $feature_sort3 = $_POST[feature_sort3]; }
			if($_POST[feature_sort4]) { $feature_sort4 = $_POST[feature_sort4]; }
			if($_POST[feature_sort5]) { $feature_sort5 = $_POST[feature_sort5]; }
			if($_POST[feature_tracklist] != '1') { $_POST[feature_tracklist] = "0"; }
			for($i = 2; $i <= 20; $i++) {

				if($i < 10) { $field = "field0".$i; } else { $field = "field".$i; }
				if($_POST["public_".$field] != '1') { $_POST["public_".$field] = "0"; }

			}
			mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_type` (`name`, `short`, `view`, `field01`, `field02`, `field03`, `field04`, `field05`, `field06`, `field07`, `field08`, `field09`, `field10`, `field11`, `field12`, `field13`, `field14`, `field15`, `field16`, `field17`, `field18`, `field19`, `field20`, `public_field01`, `public_field02`, `public_field03`, `public_field04`, `public_field05`, `public_field06`, `public_field07`, `public_field08`, `public_field09`, `public_field10`, `public_field11`, `public_field12`, `public_field13`, `public_field14`, `public_field15`, `public_field16`, `public_field17`, `public_field18`, `public_field19`, `public_field20`, `listview_field01`, `listview_field02`, `listview_field03`, `feature_sort1`, `feature_sort2`, `feature_sort3`, `feature_sort4`, `feature_sort5`, `feature_tracklist`) VALUES ('".$_POST[name]."', '".strtolower($_POST[short])."', '".$_POST[view]."', '".$_POST[field01]."', '".$_POST[field02]."', '".$_POST[field03]."', '".$_POST[field04]."', '".$_POST[field05]."', '".$_POST[field06]."', '".$_POST[field07]."', '".$_POST[field08]."', '".$_POST[field09]."', '".$_POST[field10]."', '".$_POST[field11]."', '".$_POST[field12]."', '".$_POST[field13]."', '".$_POST[field14]."', '".$_POST[field15]."', '".$_POST[field16]."', '".$_POST[field17]."', '".$_POST[field18]."', '".$_POST[field19]."', '".$_POST[field20]."', '1', '".$_POST[public_field02]."', '".$_POST[public_field03]."', '".$_POST[public_field04]."', '".$_POST[public_field05]."', '".$_POST[public_field06]."', '".$_POST[public_field07]."', '".$_POST[public_field08]."', '".$_POST[public_field09]."', '".$_POST[public_field10]."', '".$_POST[public_field11]."', '".$_POST[public_field12]."', '".$_POST[public_field13]."', '".$_POST[public_field14]."', '".$_POST[public_field15]."', '".$_POST[public_field16]."', '".$_POST[public_field17]."', '".$_POST[public_field18]."', '".$_POST[public_field19]."', '".$_POST[public_field20]."', '1', '".$_POST[listview_field02]."', '".$_POST[listview_field03]."', '$feature_sort1', '$feature_sort2', '$feature_sort3', '$feature_sort4', '$feature_sort5', '$_POST[feature_tracklist]')");
			$message .= '<div id="message" class="updated fade"><p><strong>'.sprintf(__("Media type <i>%s</i> was created successfully!",$myTreasuresTextdomain),$_POST[name]).'</strong></p></div>';
			$_POST = false;

		}

		switch($_GET[sortlist]) {
			case 'id': $order = "id"; $orderquery = "`id`"; break;
			case 'title': $order = "title"; $orderquery = "`name`"; break;
			case 'tag': $order = "tag"; $orderquery = "`short`"; break;
			default: $order = "title"; $orderquery = "`name`"; break;
		}

		$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_type` ORDER BY ".$orderquery."");
		if(mysql_num_rows($query01)) {

			if($order == 'title') { $italic1 = "font-style:italic;"; }
			if($order == 'tag') { $italic2 = "font-style:italic;"; }
			if($order == 'id') { $italic3 = "font-style:italic;"; }
			$message .= "<p>".__("Please click on the heading to sort the list!",$myTreasuresTextdomain)."</p><table class=\"widefat fixed\" cellspacing=\"0\"><thead><tr class=\"thead\"><th scope=\"col\" class=\"manage-column column-username\"><a href=\"?page=mytreasures/mytreasuresmediatype.php&sortlist=title\" style=\"font-weight: bold; ".$italic1."\">Titel / Name</a></th><th scope=\"col\" class=\"manage-column column-name\"><a href=\"?page=mytreasures/mytreasuresmediatype.php&sortlist=tag\" style=\"font-weight: bold; ".$italic2."\">Tag</a></th><th scope=\"col\" class=\"manage-column column-posts num\"><a href=\"?page=mytreasures/mytreasuresmediatype.php&sortlist=id\" style=\"font-weight: bold; ".$italic3."\">ID</a></th></tr></thead><tfoot><tr class=\"thead\"><th scope=\"col\" class=\"manage-column column-username\"><a href=\"?page=mytreasures/mytreasuresmediatype.php&sortlist=title\" style=\"font-weight: bold; ".$italic1."\">Titel / Name</a></th><th scope=\"col\" class=\"manage-column column-name\"><a href=\"?page=mytreasures/mytreasuresmediatype.php&sortlist=tag\" style=\"font-weight: bold; ".$italic2."\">Tag</a></th><th scope=\"col\" class=\"manage-column column-posts num\"><a href=\"?page=mytreasures/mytreasuresmediatype.php&sortlist=id\" style=\"font-weight: bold; ".$italic3."\">ID</a></th></tr></tfoot><tbody id=\"users\" class=\"list:user user-list\">";
			while($result01 = mysql_fetch_array($query01)) {

				if(++$i%2 == 0) { $class = "class=\"alternate\""; } else { $class = false; }
				$message .= "<tr id=\"user-1\" ".$class."><td class=\"username column-username\">".$result01[name]."<br /><div class=\"row-actions\"><span class=\"edit\"><a href=\"?page=mytreasures/mytreasuresedit.php&type=mediatype&id=".$result01[id]."\">".__("Edit",$myTreasuresTextdomain)."</a> | <a href=\"?page=mytreasures/mytreasuresdelete.php&type=mediatype&id=".$result01[id]."\">".__("Delete",$myTreasuresTextdomain)."</a></span></div></td><td class=\"name column-name\">".$result01[short]."</td><td class=\"posts column-posts num\">".$result01[id]."</td></tr>";

			}

			$message .= "</tbody></table>";

		} else {

			$message .= "<p>".__("No media in database!",$myTreasuresTextdomain)."</p>";

		}

		if(!$_POST[field01]) { $_POST[field01] = "Name / Titel"; }
		if($_POST[feature_tracklist]) { $checkedarray[feature_tracklist] = "checked=\"checked\""; } else { $checkedarray[feature_tracklist] = ""; }
		if($_POST[view] == '0' || $_POST[view] == '') { $checkedarray[view] = "checked=\"checked\""; } else { $checkedarray[view] = ""; }
		if($_POST[view] == 'list') { $checkedarray['list'] = "checked=\"checked\""; } else { $checkedarray['list'] = ""; }
		if($_POST[view] == 'rating') { $checkedarray[rating] = "checked=\"checked\""; } else { $checkedarray[rating] = ""; }
		if($_POST[view] == 'sort1') { $checkedarray[sort1] = "checked=\"checked\""; } else { $checkedarray[sort1] = ""; }
		if($_POST[view] == 'sort2') { $checkedarray[sort2] = "checked=\"checked\""; } else { $checkedarray[sort2] = ""; }
		if($_POST[view] == 'sort3') { $checkedarray[sort3] = "checked=\"checked\""; } else { $checkedarray[sort3] = ""; }
		if($_POST[view] == 'sort4') { $checkedarray[sort4] = "checked=\"checked\""; } else { $checkedarray[sort4] = ""; }
		if($_POST[view] == 'sort5') { $checkedarray[sort5] = "checked=\"checked\""; } else { $checkedarray[sort5] = ""; }
		if($_POST[view] == 'covers') { $checkedarray[covers] = "checked=\"checked\""; } else { $checkedarray[covers] = ""; }
		if($_POST[doit] && !$_POST[name]) { $error11 = "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />"; }
		if($_POST[doit] && !preg_match("/^([a-zA-Z0-9]+)$/",$_POST[short])) { $error21 = "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />"; }
		if($check_doubleshort && $_POST[doit]) { $error21 = "<img src=\"../wp-content/plugins/mytreasures/images/missing.gif\" />"; $error22 = "<br /><br />".__("<b>Important information</b><br />This tag is already in database!",$myTreasuresTextdomain)."<br /><br />"; }
		$selectinput = "<option value=\"\">".__("No view for this type",$myTreasuresTextdomain)."</option>";
		for($i = 1; $i <= 20; $i++) {

			if($i == 1) { $field1 .= "<font style=\"color: #FF0000;\">*</font>"; $disabledpublicfield = "disabled=\"disabled\""; } else { $disabledpublicfield = false; }
			if($i < 10) { $field1 = $field1 = __("detail",$myTreasuresTextdomain)." #0".$i; $field = "field0".$i; } else { $field1 = __("detail",$myTreasuresTextdomain)." #".$i; $field = "field".$i; }
			if($i > 1) { $selectinput .= "<option value=\"".$field."\">".sprintf(__("Content of %s (Must have content!)",$myTreasuresTextdomain),$field1)."</option>"; }
			if($i == 1 || !$_POST[doit] || $_POST["public_".$field]) { $checked1 = "checked=\"checked\""; } else { $checked1 = false; }
			if($i == 1 || $_POST["listview_".$field]) { $checked2 = "checked=\"checked\""; } else { $checked2 = false; }
			if($i < 4) { $showlistview = "<br /><input type=\"checkbox\" name=\"listview_".$field."\" value=\"1\" ".$checked2." ".$disabledpublicfield."> ".__("show on list view",$myTreasuresTextdomain);  } else { $showlistview = false; }
			$mediafiles .= "<br /><br />".$field1."<br /><input type=\"checkbox\" name=\"public_".$field."\" value=\"1\" ".$checked1." ".$disabledpublicfield."> ".__("show on details view",$myTreasuresTextdomain).$showlistview."<br /><textarea style=\"height: 45px; width: 90%;\" name=\"".$field."\">".stripslashes($_POST[$field])."</textarea>";

		}

		$message .= "<br /><br /><h2>".__("Add new media type",$myTreasuresTextdomain)."</h2><form action=\"\" method=\"post\" enctype=\"multipart/form-data\"><p>".$error11."Name<font style=\"color: #FF0000;\">*</font><br /><textarea style=\"height: 45px; width: 90%;\" name=\"name\">".stripslashes($_POST[name])."</textarea><br /><br />".$error21."Tag<font style=\"color: #FF0000;\">*</font> ".__("(Not changeable, just numbers & letters!)",$myTreasuresTextdomain)."<br /><textarea style=\"height:45px; width: 90%;\" name=\"short\">".stripslashes($_POST[short])."</textarea>".$error22."<br /><br />".__("<b>Information</b><br />Following fields are for your own details of this media type",$myTreasuresTextdomain).$mediafiles."<br /><br /><b>".__("Views for this media type",$myTreasuresTextdomain)."</b><br />".__("You can create own \"views\" for ech media type. Just select it here",$myTreasuresTextdomain)."<br /><br />".__("View",$myTreasuresTextdomain)." #1<br /><select name=\"feature_sort1\" style=\"width: 380px;\">".$selectinput."</select><br /><br />".__("View",$myTreasuresTextdomain)." #2<br /><select name=\"feature_sort2\" style=\"width: 380px;\">".$selectinput."</select><br /><br />".__("View",$myTreasuresTextdomain)." #3<br /><select name=\"feature_sort3\" style=\"width: 380px;\">".$selectinput."</select><br /><br />".__("View",$myTreasuresTextdomain)." #4<br /><select name=\"feature_sort4\" style=\"width: 380px;\">".$selectinput."</select><br /><br />".__("View",$myTreasuresTextdomain)." #5<br /><select name=\"feature_sort5\" style=\"width: 380px;\">".$selectinput."</select><br /><br /><b>".__("Tracklist",$myTreasuresTextdomain)."</b><br /><input type=\"checkbox\" name=\"feature_tracklist\" value=\"1\" ".$checkedarray['feature_tracklist']."> ".__("This media type has a tracklist",$myTreasuresTextdomain)."<br /><br /><b>".__("Default view",$myTreasuresTextdomain)."</b><br />".__("If you want to have a default view for this media type, just choose it:",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"0\" ".$checkedarray['view']."> ".__("Use global setttings",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"list\" ".$checkedarray['list']."> ".__("Name",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"rating\" ".$checkedarray['rating']."> ".__("Ratings",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"sort1\" ".$checkedarray['sort1']."> ".__("Media type view definition #1 (If available!)",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"sort2\" ".$checkedarray['sort2']."> ".__("Media type view definition #2 (If available!)",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"sort3\" ".$checkedarray['sort3']."> ".__("Media type view definition #3 (If available!)",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"sort4\" ".$checkedarray['sort4']."> ".__("Media type view definition #4 (If available!)",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"sort5\" ".$checkedarray['sort5']."> ".__("Media type view definition #5 (If available!)",$myTreasuresTextdomain)."<br /><input type=\"radio\" name=\"view\" value=\"covers\" ".$checkedarray['covers']."> ".__("Covers",$myTreasuresTextdomain)."</p><div class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"doit\" value=\" ".__("Add new media type",$myTreasuresTextdomain)." \"></div></form></div>";
		echo "<div class=\"wrap\"><h2>".__("Media types",$myTreasuresTextdomain)."</h2>".$message."</div>";

	}

?>