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


if (!$admin->permissions['setup']) {
	header("location: ./");
	exit();
}


if (is_numeric($input->p['gateway_id'])) {
	switch ($input->p['gateway_action']) {
		case "deactivate":
			$upd = $db->query("UPDATE gateways SET status='Inactive' WHERE id=" . $input->pc['gateway_id']);
			$cache->delete("gatewaylist");
			serveranswer(4, "location.href='./?view=gateways';");
			break;

		case "update":
			$updarray = array("api_key" => $input->pc['api_key'], "allow_deposits" => $input->pc['allow_deposits'], "allow_withdrawals" => $input->pc['allow_withdrawals'], "allow_upgrade" => $input->pc['allow_upgrade'], "withdraw_fee" => $input->pc['withdraw_fee'], "withdraw_fee_fixed" => $input->pc['withdraw_fee_fixed'], "currency" => $input->pc['currency'], "option1" => $input->pc['option1'], "option2" => $input->pc['option2'], "option3" => $input->pc['option3'], "option4" => $input->pc['option4'], "option5" => $input->pc['option5'], "min_deposit" => $input->pc['min_deposit']);
			$upd = $db->update("gateways", $updarray, "id=" . $input->pc['gateway_id']);
			$cache->delete("gatewaylist");
			serveranswer(1, "بروزرسانی انجام شد");
	}
}


if ($input->p['do'] == "update_withdraw") {
	verifyajax();
	verifydemo();
	$set = array("value" => $input->pc['instant_payment']);
	$upd = $db->update("settings", $set, "field='instant_payment'");
	$set = array("value" => $input->pc['upgrade_purchasebalance']);
	$upd = $db->update("settings", $set, "field='upgrade_purchasebalance'");
	$set = array("value" => $input->pc['fail_payments']);
	$upd = $db->update("settings", $set, "field='fail_payments'");
	$cache->delete("settings");
	serveranswer(1, "بروز شد.");
}
else {
	if ($input->p['do'] == "activate") {
		verifyajax();
		verifydemo();
		$set = array("status" => "Active");
		$upd = $db->update("gateways", $set, ("id='" . $input->pc['gateway'] . "'"));
		$cache->delete("gatewaylist");
		serveranswer(4, "location.href='./?view=gateways';");
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">دروازه های پرداخت</div>
<div class=\"site_content\">
<form method=\"post\" id=\"frm\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"do\" value=\"update_withdraw\" />
<table width=\"100%\" class=\"widget-tbl\">
  <tr>
    <td  align=\"right\" width=\"200\">پرداخت فوری</td>
    <td><input type=\"checkbox\" name=\"instant_payment\" value=\"yes\" ";

if ($settings['instant_payment'] == "yes") {
	echo "checked";
}

echo " />
      برای فعال سازی تیک بزنید</td>
  </tr>
  <tr>
    <td  align=\"right\">ارتقای حساب به وسیله موجودی شارژ حساب </td>
    <td><input type=\"checkbox\" name=\"upgrade_purchasebalance\" value=\"yes\" ";

if ($settings['upgrade_purchasebalance'] == "yes") {
	echo "checked";
}

echo " />
      برای فعالسازی تیک بزنید</td>
  </tr>
  <tr>
    <td  align=\"right\">رفتن پرداخت های ناموفق به لیست منتظر</td>
    <td><input type=\"checkbox\" name=\"fail_payments\" value=\"yes\" ";

if ($settings['fail_payments'] == "yes") {
	echo "checked";
}

echo " />
      برای فعال سازی تیک بزنید . اگر بهر دلیلی پرداخت با شکست مواجه شد . درخواست به لیست منتظر میرود</td>
  </tr>
  <tr>
  	<td colspan=\"2\"  align=\"center\">
    <input type=\"submit\" name=\"create\" value=\"ذخیره\" />
  	</td>
  </tr>
</table>
</form>

";
$count = $db->fetchOne("SELECT COUNT(*) AS NUM FROM gateways  WHERE status='Inactive'");

if (0 < $count) {
	$q = $db->query("SELECT id, name FROM gateways WHERE status='Inactive'");
	echo "<div class=\"widget-title\">دروازه های پرداخت برای فعال سازی</div>
<div class=\"widget-content\">
<form method=\"post\" id=\"frm22\" onsubmit=\"return submitform(this.id);\">
	<input type=\"hidden\" name=\"do\" value=\"activate\" />
	<table width=\"100%\" class=\"widget-tbl\">
    	<tr>
        	<td align=\"right\" width=\"200\">انتخاب کنید : </td>
            <td>
	<select name=\"gateway\">
";

	while ($r = $db->fetch_array($q)) {
		echo "<option value=\"" . $r['id'] . "\">" . $r['name'] . "</option>";
	}

	echo "	</select>
    		<input type=\"submit\" name=\"btn\" value=\"فعال سازی\" /></td>
       </tr>
     </table>
</form>
</div>
";
}

$q = $db->query("SELECT * FROM gateways WHERE status='Active'");

while ($r = $db->fetch_array($q)) {
	echo "<div class=\"widget-title\">" . $r['name'] . "</div>
		<div class=\"widget-content\">";
	include MODULESPATH . "gateways/configuration/" . $r['id'] . ".php";
	echo "</div>";
}

echo "</div>


        ";
include SOURCES . "footer.php";
echo " ";
?>