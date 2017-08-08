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

require dirname(__FILE__) . "/global.php";
$settings = $cache->get("settings");

if ($settings == null) {
	$query = $db->query("SELECT * FROM settings");

	while ($result = $db->fetch_array($query)) {
		$settings[$result['field']] = $result['value'];
	}

	$cache->set("settings", $settings, 3600);
}

$adsreset = strtotime("midnight tomorrow");

if (in_array($settings['timezone'], $timezone)) {
	date_default_timezone_set($settings['timezone']);
}


if ($settings['maintenance'] == "yes") {
	include "modules/maintenance/maintenance.php";
	exit();
}

$members_support = $cache->get("members_support");

if ($members_support == null) {
	$members_support = $db->fetchOne("SELECT value FROM helpdesk_settings WHERE field='members_only'");
	$cache->set("members_support", $members_support, 3600);
}

define("MEMBERS_SUPPORT", $members_support);

if ($settings['site_stats'] == "yes") {
	include PLUGINS . "statistics.php";
}

include PLUGINS . "languages.php";

if (isset($_COOKIE['user_id']) && isset($_COOKIE['password']) && isset($_COOKIE['cookie_id'])) {
	if (is_numeric($_COOKIE['user_id'])) {
		$_SESSION['user_id'] = cleanfrm($_COOKIE['user_id']);
		$_SESSION['password'] = $_COOKIE['password'];
		$_SESSION['cookie_id'] = $_COOKIE['cookie_id'];
	}
	else {
		header("location: index.php?view=logout");
		$db->close();
		exit();
	}
}


if (!$_SERVER['HTTPS'] == "on" && $settings['ssl_host'] == "yes") {
	if ($_GET['view'] == "login" || $_GET['view'] == "register") {
		$porthttps = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$db->close();
		header("Location: " . $porthttps);
		exit();
	}
}


if ($_SERVER['HTTPS'] == "on" && $settings['ssl_host'] == "yes") {
	if ($_GET['view'] != "login" && $_GET['view'] != "register") {
		$porthttp = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$db->close();
		header("Location: " . $porthttp);
		exit();
	}
}


if ($_SERVER['HTTPS'] == "on" && $settings['ssl_host'] != "yes") {
	$porthttp = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	header("Location: " . $porthttp);
	exit();
}


if (isset($_SESSION['user_id']) && isset($_SESSION['password']) && isset($_SESSION['cookie_id'])) {
	$user_id = $db->real_escape_string($_SESSION['user_id']);
	$res = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE id='" . $user_id . "' and status='Active'");

	if ($res == 0) {
		logout();
		header("location: index.php");
		$db->close();
		exit();
	}
	else {
		$user_info = $db->fetchRow("SELECT * FROM members WHERE id='" . $user_id . "'");

		if ($user_info['status'] != "Active") {
			logout();
			header("location: index.php");
			$db->close();
			exit();
		}


		if ($_SESSION['password'] != $user_info['password']) {
			logout();
			header("location: index.php");
			$db->close();
			exit();
		}
		else {
			$_SESSION['logged'] = "yes";

			if ($_SESSION['cookie_id'] != $user_info['cookie_id']) {
				logout();
				$db->close();
				header("location: index.php");
				exit();
			}
		}
	}
}
else {
	unset($_SESSION['logged']);
}


if ($_SESSION['logged'] == "yes") {
	if (!isset($_SESSION['user_cron'])) {
		$todayis = time();
		$rentedq = $db->query("SELECT id FROM members WHERE rented_expires!=0 AND rented_expires<" . $todayis . " AND rented=" . $user_info['id']);

		while ($rented = $db->fetch_array($rentedq)) {
			$set = array("rented" => 0, "rented_time" => 0, "rented_expires" => 0, "rented_clicks" => 0, "rented_lastclick" => 0, "rented_earned" => 0, "rented_autopay" => 0);
			$upd = $db->update("members", $set, "id=" . $rented['id']);
			$cr = $cr + 1;
		}

		$upd = $db->query("UPDATE members SET rented_referrals=rented_referrals-" . $cr . " WHERE id=" . $user_info['id']);
		$delete_logs = $todayis - 60 * 60 * 24 * 7;
		$upd = $db->delete("login_history", "date<" . $delete_logs);

		if ($user_info['upgrade_ends'] != 0 && $user_info['type'] != 1 && $user_info['upgrade_ends'] <= TIMENOW) {
			$set = array("type" => 1, "upgrade_ends" => 0);
			$upd = $db->update("members", $set, "id=" . $user_info['id']);
		}


		if ($user_info['rented_expires'] != 0 && $user_info['rented_expires'] <= TIMENOW) {
			$upd = $db->query("UPDATE members SET rented_referrals=rented_referrals-1 WHERE id=" . $user_info['rented']);
			$set = array("rented" => 0, "rented_time" => 0, "rented_expires" => 0, "rented_clicks" => 0, "rented_lastclick" => 0, "rented_earned" => 0, "rented_autopay" => 0);
			$upd = $db->update("members", $set, "id=" . $user_info['id']);
		}

		$total_rentedrefs = $db->fetchOne("SELECT COUNT(id) AS NUM FROM members WHERE rented=" . $user_info['id']);
		$total_refs = $db->fetchOne("SELECT COUNT(id) AS NUM FROM members WHERE ref1=" . $user_info['id']);
		$data = array("referrals" => $total_refs, "rented_referrals" => $total_rentedrefs);
		$db->update("members", $data, "id=" . $user_info['id']);
		$_SESSION['user_cron'] = 1;
	}

	$today_date = date("Y-m-d");

	if ($user_info['last_cron'] == "") {
		$db->query("UPDATE members SET last_cron='" . $today_date . "', mc1=0, r1=0, rr1=0, ap1=0 WHERE id=" . $user_info['id']);
	}
	else {
		$days_diff = dateDiff($user_info['last_cron'], $today_date);

		if (1 <= $days_diff) {
			include INCLUDES . "crons/ptc.php";
			$cron_list = scandir(INCLUDES . "crons/");
			foreach ($cron_list as $c) {

				if (!in_array($c, array(".", ".."))) {
					if ($c != "ptc.php" && is_file(INCLUDES . "crons/" . $c)) {
						include INCLUDES . "crons/" . $c;
						continue;
					}

					continue;
				}
			}

			header("location: /?view=account");
			exit();
		}
	}
}


if (!defined("DISABLE_TEMPLATE")) {
	require SMARTYLOADER;
}

?>