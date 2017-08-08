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
function __autoload($class_name) {
	$class_name = strtolower($class_name);
	$file = INCLUDES_ADMIN . "class_" . $class_name . ".php";

	if (file_exists($file)) {
		require_once $file;
	}

}

function datepass($date) {
	$timeleft = TIMENOW - $date;
	$days = floor($timeleft / 86400);
	$hours = floor($timeleft / 3600) - $days * 24;
	$minutes = floor($timeleft / 60) - $days * 24 * 60 - $hours * 60;
	$seconds = $timeleft - $days * 24 * 60 * 60 - $hours * 60 * 60 - $minutes * 60;
	$lastdate = ($days ? $days . "d " : "") . $hours . "h " . $minutes . "m " . $seconds . "s";
	return $lastdate;
}

function verifydemo() {
	global $settings;

	if ($settings['demo'] == "yes") {
		serveranswer(0, "This is not possible in this demo version");
	}

}

function daterange($date) {
	$date1e = explode("/", $date);
	$daterange = array();
	$daterange[0] = mktime(0, 0, 0, $date1e[0], $date1e[1], $date1e[2]);
	$daterange[1] = mktime(23, 59, 59, $date1e[0], $date1e[1], $date1e[2]);
	return $daterange;
}

function deletemember($id) {
	global $db;

	$db->delete("ads", "user_id=" . $id);
	$db->delete("banner_ads", "user_id=" . $id);
	$db->delete("featured_ads", "user_id=" . $id);
	$db->delete("featured_link", "user_id=" . $id);
	$db->delete("login_history", "user_id=" . $id);
	$db->delete("order_history", "user_id=" . $id);
	$db->delete("pre_order", "user_id=" . $id);
	$db->delete("withdraw_history", "user_id=" . $id);
	$db->query("UPDATE members SET ref1=0, for_refclicks=0, for_refearned=0 WHERE ref1=" . $id);
	$db->query("UPDATE members SET rented=0, rented_time=0, rented_expires=0, rented_clicks=0, rented_lastclick=0, rented_earned=0, rented_autopay=0 WHERE rented=" . $id);
	$db->delete("members", "id=" . $id);
	$db->query("UPDATE statistics SET value=value-1 WHERE field='members'");
}

function unhookrefs($id) {
	global $db;

	$db->query("UPDATE members SET ref1=0, for_refclicks=0, for_refearned=0 WHERE ref1=" . $id);
	$db->query("UPDATE members SET referrals=0 WHERE id=" . $id);
}

function unhookrented($id) {
	global $db;

	$db->query("UPDATE members SET rented=0, rented_time=0, rented_expires=0, rented_clicks=0, rented_lastclick=0, rented_earned=0, rented_autopay=0 WHERE rented=" . $id);
	$db->query("UPDATE members SET rented_referrals=0 WHERE id=" . $id);
}

function ascii2hex($ascii) {
	$hex = "";

	for ($i = 0;$i < strlen($ascii);$i++) {
		$byte = strtoupper(dechex(ord($ascii[$i])));
		$byte = str_repeat("0", 2 - strlen($byte)) . $byte;
		$hex .= $byte;
	}

	return $hex;
}

function hex2ascii($hex) {
	$ascii = "";
	$hex = str_replace(" ", "", $hex);
	$i = 0;

	for ($i = 0;$i < strlen($hex);$i = $i + 2) {
		$ascii .= chr(hexdec(substr($hex, $i, 2)));
	}

	return $ascii;
}

function sesion_id($sesid = null) {
	if ($sesid) {
		$_SESSION[$sesid] = rand(100, 10000);
		return $_SESSION[$sesid];
	}

	$_SESSION['sesion_id'] = rand(100, 10000);
	return $_SESSION['sesion_id'];
}

function getuserid($username) {
	global $db;

	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE username='" . $username . "'");

	if ($verify == 0) {
		return 0;
	}

	$userid = $db->fetchOne("SELECT id FROM members WHERE username='" . $username . "'");
	return $userid;
}

function getusername($id) {
	global $db;

	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE id='" . $id . "'");

	if ($verify == 0) {
		return 0;
	}

	$username = $db->fetchOne("SELECT username FROM members WHERE id='" . $id . "'");
	return $username;
}

function getuserdetails($id) {
	global $db;

	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE id='" . $id . "'");

	if ($verify == 0) {
		return 0;
	}

	$details = $db->fetchRow("SELECT * FROM members WHERE id='" . $id . "'");
	return $details;
}

function getmembershipname($id) {
	global $db;

	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM membership WHERE id='" . $id . "'");

	if ($verify == 0) {
		return 0;
	}

	$details = $db->fetchOne("SELECT name FROM membership WHERE id='" . $id . "'");
	return $details;
}

?>