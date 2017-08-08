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

define("EvolutionScript", 1);
define("ROOTPATH", dirname(__FILE__) . "/");
define("INCLUDES", ROOTPATH . "includes/");
require_once INCLUDES . "global.php";
$query = $db->query("SELECT * FROM cron_settings");

while ($result = $db->fetch_array($query)) {
	$cron[$result['field']] = $result['value'];
}


if ($cron['delete_inactive'] == "yes") {
	$query = $db->query("SELECT id, country, ref1 FROM members WHERE status='Inactive'");

	while ($member = $db->fetch_array($query)) {
		$db->delete("ads", "user_id=" . $member['id']);
		$db->delete("banner_ads", "user_id=" . $member['id']);
		$db->delete("featured_ads", "user_id=" . $member['id']);
		$db->delete("featured_link", "user_id=" . $member['id']);
		$db->delete("login_history", "user_id=" . $member['id']);
		$db->delete("order_history", "user_id=" . $member['id']);
		$db->delete("withdraw_history", "user_id=" . $member['id']);
		$db->query("UPDATE country SET users=users-1 WHERE name='" . $member['country'] . "'");

		if ($member['ref1'] != 0 || !empty($member['ref1'])) {
			$db->query("UPDATE members SET referrals=referrals-1, myrefs1=myrefs1-1 WHERE id=" . $member['ref1']);
		}

		$db->query("UPDATE members SET ref1=0 WHERE ref1=" . $member['id']);
		$db->query("UPDATE members SET rented=0, rented_time=0, rented_expires=0, rented_clicks=0 WHERE rented=" . $member['id']);
		$db->delete("members", "id=" . $member['id']);
	}
}

$set = array("type" => 1, "upgrade_ends" => 0);
$todayis = time();
$upd = $db->update("members", $set, "upgrade_ends!=0 AND upgrade_ends<" . $todayis);
$todayis = time();
$rentedq = $db->query("SELECT rented FROM members WHERE rented_expires!=0 AND rented_expires<" . $todayis);

while ($rented = $db->fetch_array($rentedq)) {
	$upd = $db->query("UPDATE members SET rented_referrals=rented_referrals-1 WHERE id=" . $rented['rented']);
}

$set = array("rented" => 0, "rented_time" => 0, "rented_expires" => 0, "rented_clicks" => 0, "rented_lastclick" => 0, "rented_earned" => 0, "rented_autopay" => 0);
$upd = $db->update("members", $set, "rented_expires!=0 AND rented_expires<" . $todayis);
$inactive_days = $db->fetchOne("SELECT value FROM settings WHERE field='inactive_days'");
$inactivity_days = time() - 60 * 60 * 24 * $inactive_days;
$query = $db->query("SELECT id, signup, last_login  FROM members WHERE last_login<" . $inactivity_days . " AND status='Active'");

while ($list = $db->fetch_array($query)) {
	if ($list['last_login'] != 0) {
		$upd = $db->query("UPDATE members SET status='Inactive' WHERE id=" . $list['id']);
	}


	if ($list['signup'] < $inactivity_days) {
		$upd = $db->query("UPDATE members SET status='Inactive' WHERE id=" . $list['id']);
	}
}

?>