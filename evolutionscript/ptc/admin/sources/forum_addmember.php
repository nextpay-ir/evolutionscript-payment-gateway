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

$group = $db->fetchRow("SELECT * FROM forum_groups WHERE id=" . $input->g['gid']);

if ($input->p['doact'] == "addnewmember") {
	verifyajax();
	verifydemo();
	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE username='" . $input->pc['username'] . "'");

	if ($verify != 0) {
		$db->query("UPDATE members SET forum_role=" . $group['id'] . " WHERE username='" . $input->pc['username'] . "'");
		serveranswer(1, "Member <strong>" . $username . "</strong> was added to this group.");
	}
	else {
		serveranswer(0, "Username was not found.");
	}
}

include "header.php";
echo "<div class=\"site_title\">Add a new member to ";
echo $group['name'];
echo "</div>
<div class=\"site_content\">
    <form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"doact\" value=\"addnewmember\" />
<table width=\"100%\" class=\"widget-tbl\">
  <tr>
    	<td align=\"right\" width=\"300\">Username</td>
        <td><input type=\"text\" name=\"username\" /></td>
	</tr>
    <tr>
    	<td></td>
        <td><input type=\"submit\" value=\"Add\"  name=\"btn\" />
        <input type=\"button\" value=\"Return\"  name=\"btn\" onclick=\"location.href='./?view=forum_settings#tab-2';\" /></td>
    </tr>
</table>
    </form>
</div>


";
include "footer.php";
echo " ";
?>