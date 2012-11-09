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

		$query01 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures` WHERE `id` = '".$_GET['id']."'");
		$result01 = mysql_fetch_array($query01);

		if($result01['id']) {

			if(!isset($_POST['deletemarked'])) {

				$_POST['deletemarked'] = false;

			}

			if(!isset($_POST['target'])) {

				$_POST['target'] = false;

			}

			if(!isset($_POST['saveorder'])) {

				$_POST['saveorder'] = false;

			}

			if(!isset($_POST['del'])) {

				$_POST['del'] = false;

			}

			if(!isset($_POST['dontdel'])) {

				$_POST['dontdel'] = false;

			}

			if($_POST['deletemarked'] && $_POST['deletemedia']) {

				if($_POST['del'] || $_POST['dontdel']) {

					if($_POST['del']) {

						$deleteditems = 0;
						foreach($_POST['deletemedia'] AS $id => $name) {

							$deleteditems++;
							$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_links` WHERE `id` = '".$id."'");
							$result02 = mysql_fetch_array($query02);
							mysql_query("DELETE FROM `".$wpdb->prefix."mytreasures_links` WHERE `id` = '".$result02['id']."'");

						}

						echo '<div id="message" class="updated fade"><p><strong>'.sprintf(__("%s link(s) deleted successfully!",$myTreasuresTextdomain),$deleteditems).'</strong></p></div>';

					}

				} else {

?>

<div class="wrap"><h2><?php echo __("Delete media",$myTreasuresTextdomain); ?> (<?php echo $result01['field01']; ?>)</h2>
<form action="" method="post">
<input type="hidden" name="deletemarked" value="1" />
<p><?php echo __("Do you want to delete the following links?",$myTreasuresTextdomain)."<br />"; foreach($_POST['deletemedia'] AS $id => $name) { echo "<input type=\"hidden\" name=\"deletemedia[".$id."]\" value=\"".$name."\" /><br />- ".$name; } ?></p>
<div class="submit"><input type="submit" class="button-primary" name="del" value=" <?php echo __("Yes",$myTreasuresTextdomain); ?> "> <input type="submit" name="dontdel" value=" <?php echo __("No",$myTreasuresTextdomain); ?> "></div>
</form>
</div>
<br /><br />

<?php

				}

			}

			$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_links` WHERE `treasureid` = '".$_GET['id']."'");
			while($result02 = mysql_fetch_array($query02)) { $linkarray[] = $result02; }

			if($_POST['target'] && $_POST['name']) {

				mysql_query("INSERT INTO `".$wpdb->prefix."mytreasures_links` (`treasureid`, `name`, `link`) VALUES ('".$result01['id']."', '".$_POST['name']."', '".$_POST['target']."')");

			}
			
			if($_POST['saveorder'] && $_POST['name']) {

				foreach($_POST['name'] AS $id => $dummy) {

					mysql_query("UPDATE `".$wpdb->prefix."mytreasures_links` SET `link` = '".$_POST['link'][$id]."', `name` = '".$_POST['name'][$id]."' WHERE `id` = '$id'");

				}

			}

?>

<script type="text/javascript">
<!-- 
function markallmedia() {

	for(var i = 0; i < document.myform.elements.length; i++) {
    if(document.myform.elements[i].type == 'checkbox'){
      document.myform.elements[i].checked = !(document.myform.elements[i].checked);
    }
  }
	document.myform.elements[0].checked = !(document.myform.elements[0].checked);

}
//-->
</script>
<div class="wrap">
<h2><?php echo __("Activ links",$myTreasuresTextdomain); ?> (<?php echo $result01['field01']; ?>)</h2>
<form action="" method="post"><p><h3><?php echo __("Add new link:",$myTreasuresTextdomain); ?></h3><b><?php echo __("Link target",$myTreasuresTextdomain); ?>:</b><br /><input type="text" style="width: 75%;" name="target" value="http://"><br /><br /><b><?php echo __("Link name",$myTreasuresTextdomain); ?>:</b><br /><input type="text" style="width: 75%;" name="name"></p><div class="submit"><input type="submit" name="doit" class="button-primary" value=" <?php echo __("Create new link",$myTreasuresTextdomain); ?> "></div></form></div>

<?php

			$query02 = mysql_query("SELECT * FROM `".$wpdb->prefix."mytreasures_links` WHERE `treasureid` = '".$result01['id']."' ORDER BY `name`");
			if(mysql_num_rows($query02)) {

?>

<form name="myform" action="" method="post">
<table class="widefat fixed" cellspacing="0">
<thead>
<tr class="thead">
	<th scope="col"  class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
	<th scope="col"  class="manage-column column-name" style=""><?php echo __("Link target",$myTreasuresTextdomain); ?></th>
	<th scope="col"  class="manage-column column-name" style=""><?php echo __("Link name",$myTreasuresTextdomain); ?></th>
</tr>
</thead>

<tfoot>
<tr class="thead">
	<th scope="col"  class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
	<th scope="col"  class="manage-column column-name" style=""><?php echo __("Link target",$myTreasuresTextdomain); ?></th>
	<th scope="col"  class="manage-column column-name" style=""><?php echo __("Link name",$myTreasuresTextdomain); ?></th>
</tr>
</tfoot>

<tbody id="users" class="list:user user-list">

<?php

		while($result02 = mysql_fetch_array($query02)) {

?>

<tr id='user-1' <?php if(++$i%2 == 0) { echo "class='alternate'"; } ?>>
	<th scope='row' class='check-column'><input type='checkbox' name='deletemedia[<?php echo $result02['id']; ?>]' id='user_1' value='<?php echo $result02['name']; ?>' /></th>
	<td class="username column-username"><input type="text" name="link[<?php echo $result02['id']; ?>]" value="<?php echo $result02['link']; ?>" style="width: 100%;"></td>
	<td class="username column-username"><input type="text" name="name[<?php echo $result02['id']; ?>]" value="<?php echo $result02['name']; ?>" style="width: 100%;"></td>
</tr>


<?php

				}

?>

</tbody>
</table>
<div class="submit"><input type="submit" class="button-primary" name="deletemarked" value=" <?php echo __("Delete marked links",$myTreasuresTextdomain); ?> "> <input type="submit" name="saveorder" value=" <?php echo __("Save new target & name",$myTreasuresTextdomain); ?> "></div>
</form>

<?php

			}
		
		}

	}

?>