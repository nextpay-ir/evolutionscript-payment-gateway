<?php
/**
 * @ EvolutionScript
 * Created by NextPay.ir
 * author: Nextpay Company
 * ID: @nextpay
 * Date: 04/01/2017
 * Time: 5:45 PM
 * Website: NextPay.ir
 * Email: info@nextpay.ir
 * @copyright 2017
 * @package NextPay_Gateway
 * @version 1.0
 */
if (!defined("EvolutionScript")) {
	exit("Hacking attempt...");
}


if ($input->p['act'] == "update_group") {
	verifyajax();
	verifydemo();
	$stored = array("name" => $input->pc['name'], "canviewforum" => $input->pc['canviewforum'], "canviewtopic" => $input->pc['canviewtopic'], "canposttopic" => $input->pc['canposttopic'], "caneditownpost" => $input->pc['caneditownpost'], "caneditotherspost" => $input->pc['caneditotherspost'], "candeleteownpost" => $input->pc['candeleteownpost'], "candeleteotherspost" => $input->pc['candeleteotherspost'], "canopencloseowntopics" => $input->pc['canopencloseowntopics'], "canopenclosetopics" => $input->pc['canopenclosetopics'], "canmoveowntopics" => $input->pc['canmoveowntopics'], "canmoveotherstopic" => $input->pc['canmoveotherstopic'], "canbanmembers" => $input->pc['canbanmembers'], "cansuspendmember" => $input->pc['cansuspendmember']);
	$db->update("forum_groups", $stored, "id=" . $input->g['gid']);
	serveranswer(1, "Group was updated.");
}

$group = $db->fetchRow("SELECT * FROM forum_groups WHERE id=" . $input->g['gid']);
include SOURCES . "header.php";
echo "<div class=\"site_title\">Edit Forum Group</div>
<div class=\"site_content\">
<form method=\"post\" id=\"frmgedit\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"act\" value=\"update_group\" />
<table class=\"widget-tbl\" width=\"100%\">
  <tr>
        <td align=\"right\" width=\"300\">User Title:</td>
        <td><input type=\"text\" name=\"name\" value=\"";
echo $group['name'];
echo "\" /></td>
  </tr>
  <tr>
        <td align=\"right\">Can View Forum:</td>
        <td>
        ";
$radioname = "canviewforum";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "
        </td>
  </tr>
  <tr>
        <td align=\"right\">Can View Topic Content:</td>
        <td>
        ";
$radioname = "canviewtopic";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "        </td>
  </tr>
  <tr>
        <td align=\"right\">Can Post Topics:</td>
        <td>
        ";
$radioname = "canposttopic";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "        </td>
  </tr>
  <tr>
        <td align=\"right\">Can Edit Own Posts:</td>
        <td>
        ";
$radioname = "caneditownpost";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "        </td>
  </tr>
  <tr>
        <td align=\"right\">Can Edit Others Posts:</td>
        <td>
        ";
$radioname = "caneditotherspost";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "        </td>
  </tr>
  <tr>
        <td align=\"right\">Can Delete Own Posts:</td>
        <td>
        ";
$radioname = "candeleteownpost";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "        </td>
  </tr>
  <tr>
        <td align=\"right\">Can Delete Others Posts:</td>
        <td>
        ";
$radioname = "candeleteotherspost";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "        </td>
  </tr>
  <tr>
        <td align=\"right\">Can Open / Close Own Topics:</td>
        <td>
        ";
$radioname = "canopencloseowntopics";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "        </td>
  </tr>
  <tr>
        <td align=\"right\">Can Open / Close Others Topics:</td>
        <td>
        ";
$radioname = "canopenclosetopics";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "        </td>
  </tr>
  <tr>
        <td align=\"right\">Can Move Own Topics:</td>
        <td>
        ";
$radioname = "canmoveowntopics";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "        </td>
  </tr>
  <tr>
        <td align=\"right\">Can Move Others Topics:</td>
        <td>
        ";
$radioname = "canmoveotherstopic";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "        </td>
  </tr>
  <tr>
        <td align=\"right\">Can Ban/Unban members from forum:</td>
        <td>
        ";
$radioname = "canbanmembers";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "        </td>
  </tr>
  <tr>
        <td align=\"right\">Can Suspend members from site:</td>
        <td>
        ";
$radioname = "cansuspendmember";
$options = array("yes" => "Yes", "no" => "No");
foreach ($options as $k => $v) {

	if ($group[$radioname] == $k) {
		echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" checked />" . $v;
		continue;
	}

	echo "<input type=\"radio\" name=\"" . $radioname . "\" value=\"" . $k . "\" />" . $v;
}

echo "        </td>
  </tr>
  <tr>
  	<td></td>
    <td><input type=\"submit\" name=\"btn\" value=\"Save\" />
    <input type=\"button\" name=\"btn\" value=\"Return\" onclick=\"location.href='?view=forum_settings#tab-2';\" /></td>
  </tr>
</table>
</form>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>