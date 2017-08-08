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


if (!$admin->permissions['utilities']) {
	header("location: ./");
	exit();
}


if ($input->p['do'] == "repair") {
	$deposit = $db->fetchOne("SELECT SUM(amount) FROM deposit_history");

	if ($deposit == "") {
		$deposit = "0.00";
	}

	$cashout = $db->fetchOne("SELECT SUM(amount) FROM withdraw_history WHERE status='Completed'");

	if ($cashout == "") {
		$cashout = "0.00";
	}


	if ($input->p['bot_system'] == "yes") {
		$total_members = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members");
	}
	else {
		$total_members = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE username!='BOT'");
	}

	$datastored = array("deposit" => $deposit, "cashout" => $cashout, "members" => $total_members);
	foreach ($datastored as $upd) {
		$db->query("UPDATE statistics SET value='" . $v . "' WHERE field='" . $k . "'");
	}

	$query = $db->query("SELECT id FROM gateways");

	while ($row = $db->fetch_array($query)) {
		$wamount = $db->fetchOne("SELECT SUM(amount) FROM withdraw_history WHERE method='" . $row['id'] . "'");
		$damount = $db->fetchOne("SELECT SUM(amount) FROM deposit_history WHERE method='" . $row['id'] . "'");
		$data = array("total_withdraw" => $wamount, "total_deposit" => $damount);
		$db->update("gateways", $data, "id=" . $row['id']);
	}

	$db->delete("country");
	$q = $db->query("SELECT country FROM members WHERE username!='BOT' GROUP BY country");

	while ($r = $db->fetch_array($q)) {
		$totalusers = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE country='" . $r['country'] . "' AND username!='BOT'");
		$data = array("name" => $r['country'], "users" => $totalusers);
		$db->insert("country", $data);
	}

	serveranswer(1, "بروز رسانی انجام شد");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">تعمیر آمار</div>
<div class=\"site_content\">
<form method=\"post\" id=\"repairstatistics\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"do\" value=\"repair\" />
<div class=\"info_box\">این ابزار آمار سایت (تسویه حساب و موجودی شارژ حساب و ... ) را تعمیر میکند</div>
	<div><input type=\"checkbox\" name=\"bot_system\" value=\"yes\" />رباتها را نیز مانند اعضا به حساب آور . </div>
    <input type=\"submit\" name=\"btn\" value=\"تعمیر\" />
</form>
</div>

";
include SOURCES . "footer.php";
echo " ";
?>