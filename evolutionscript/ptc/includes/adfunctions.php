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
function addpoints($userid, $points) {
	global $db;

	$upd = $db->query("UPDATE members SET points=points+" . $points . " WHERE id=" . $userid);
}

function addptccredits($userid, $credits) {
	global $db;

	$upd = $db->query("UPDATE members SET ad_credits=ad_credits+" . $credits . " WHERE id=" . $userid);
}

function addptsucredits($userid, $credits) {
	global $db;

	$upd = $db->query("UPDATE members SET ptsu_credits=ptsu_credits+" . $credits . " WHERE id=" . $userid);
}

function addfadscredits($userid, $credits) {
	global $db;

	$upd = $db->query("UPDATE members SET fads_credits=fads_credits+" . $credits . " WHERE id=" . $userid);
}

function addbannercredits($userid, $credits) {
	global $db;

	$upd = $db->query("UPDATE members SET banner_credits=banner_credits+" . $credits . " WHERE id=" . $userid);
}

function addflinkcredits($userid, $credits) {
	global $db;

	$upd = $db->query("UPDATE members SET flink_credits=flink_credits+" . $credits . " WHERE id=" . $userid);
}

function addloginadcredits($userid, $credits) {
	global $db;

	$upd = $db->query("UPDATE members SET loginads_credits=loginads_credits+" . $credits . " WHERE id=" . $userid);
}

function addmembership($userid, $time, $type) {
	global $db;

	$timeupgrade = 60 * 60 * 24 * $time;
	$date_upg = time() + $timeupgrade;
	$upd = $db->query("UPDATE members SET type=" . $type . ", upgrade_ends='" . $date_upg . "' WHERE id=" . $userid);
}

function extendmembership($userid, $time) {
	global $db;

	$timeupgrade = 60 * 60 * 24 * $time;
	$upd = $db->query("UPDATE members SET upgrade_ends=upgrade_ends+" . $timeupgrade . " WHERE id=" . $userid);
}

function addboughtmembers($userid, $members) {
	global $db;
	global $settings;

	$todayclick = $settings['todayclick'] + 1;

	if ($settings['buyref_days'] != 0 && $settings['buyref_filter'] == "enable") {
		$i = 1;

		while ($i <= $settings['buyref_days']) {
			$day2see = $todayclick - $i;

			if ($day2see <= 0) {
				$day2see = 7 + $day2see;
			}

			$extraq .= "mc" . $day2see . "+";
			++$i;
		}

		$extraq = "AND (" . substr($extraq, 0, -1) . ">=" . $settings['buyref_clicks'] . ")";
	}

	$res = $db->query("SELECT id FROM members WHERE ref1=0 AND (status='Active' AND id!=" . $userid . ") " . $extraq . " ORDER BY Rand() LIMIT " . $members);

	while ($list = $db->fetch_array($res)) {
		$set = array("ref1" => $userid);
		$upd = $db->update("members", $set, "id=" . $list['id']);
	}

	$upd = $db->query("UPDATE members SET referrals=referrals+" . $members . ", myrefs1=myrefs1+" . $members . " WHERE id=" . $userid);
}

function addpurchasebalance($userid, $amount) {
	global $db;

	$upd = $db->query("UPDATE members SET purchase_balance=purchase_balance+" . $amount . " WHERE id=" . $userid);
}

function addrentreferrals($userid, $members) {
	global $db;
	global $settings;

	$todayclick = $settings['todayclick'] + 1;

	if ($settings['rentref_days'] != 0 && $settings['rentref_filter'] == "enable") {
		$i = 1;

		while ($i <= $settings['rentref_days']) {
			$day2see = $todayclick - $i;

			if ($day2see <= 0) {
				$day2see = 7 + $day2see;
			}

			$extraq .= "mc" . $day2see . "+";
			++$i;
		}

		$extraq = "AND (" . substr($extraq, 0, -1) . ">=" . $settings['rentref_clicks'] . ")";
	}


	if ($settings['rentype'] == 2) {
		$addrentedsql = " AND status='Active'";
	}
	else {
		if ($settings['rentype'] == 3) {
			$addrentedsql = " AND ref1=0";
		}
		else {
			if ($settings['rentype'] == 4) {
				$addrentedsql = " AND status='Active' AND ref1=0";
			}
		}
	}

	$countrefs = $db->fetchOne(("SELECT COUNT(*) AS NUM FROM members WHERE rented=0 " . $extraq . " ") . $addrentedsql . " AND id!=" . $userid);

	if ($countrefs < $members) {
		return "no";
	}

	$res = $db->query(("SELECT id FROM members WHERE rented=0 " . $extraq . " ") . $addrentedsql . " AND id!=" . $userid . " ORDER BY Rand() LIMIT " . $members);
	$onemonth = time() + 2592000;

	while ($list = $db->fetch_array($res)) {
		$set = array("rented" => $userid, "rented_time" => time(), "rented_expires" => $onemonth);
		$upd = $db->update("members", $set, "id=" . $list['id']);
	}

	$db->query("UPDATE members SET rented_referrals=rented_referrals+" . $members . " WHERE id=" . $userid);
	return "yes";
}

function rentedreferralsleft($userid = null) {
	global $settings;
	global $db;

	$todayclick = $settings['todayclick'] + 1;

	if ($settings['rentref_days'] != 0 && $settings['rentref_filter'] == "enable") {
		$i = 1;

		while ($i <= $settings['rentref_days']) {
			$day2see = $todayclick - $i;

			if ($day2see <= 0) {
				$day2see = 7 + $day2see;
			}

			$extraq .= "mc" . $day2see . "+";
			++$i;
		}

		$extraq = "AND (" . substr($extraq, 0, -1) . ">=" . $settings['rentref_clicks'] . ")";
	}


	if ($settings['rentype'] == 2) {
		$addrentedsql = " AND status='Active'";
	}
	else {
		if ($settings['rentype'] == 3) {
			$addrentedsql = " AND ref1=0";
		}
		else {
			if ($settings['rentype'] == 4) {
				$addrentedsql = " AND status='Active' AND ref1=0";
			}
		}
	}


	if ($userid != null) {
		$refs_available = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE rented=0 " . $extraq . " " . $addrentedsql . " AND id!=" . $userid);
	}
	else {
		$refs_available = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE rented=0 " . $extraq . " " . $addrentedsql);
	}

	return $refs_available;
}

function referralsleft($userid = null) {
	global $db;
	global $settings;

	$todayclick = $settings['todayclick'] + 1;

	if ($settings['buyref_days'] != 0 && $settings['buyref_filter'] == "enable") {
		$i = 1;

		while ($i <= $settings['buyref_days']) {
			$day2see = $todayclick - $i;

			if ($day2see <= 0) {
				$day2see = 7 + $day2see;
			}

			$extraq .= "mc" . $day2see . "+";
			++$i;
		}

		$extraq = "AND (" . substr($extraq, 0, -1) . ">=" . $settings['buyref_clicks'] . ")";
	}


	if ($userid != null) {
		$refs_available = $db->fetchOne(("SELECT COUNT(*) AS NUM FROM members WHERE ref1='0' AND status='Active' AND id!=" . $userid . " ") . $extraq);
	}
	else {
		$refs_available = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE ref1='0' AND status='Active' " . $extraq);
	}

	return $refs_available;
}

function recycle($userid, $refid) {
	global $db;

	$rented_expires = $db->fetchOne("SELECT rented_expires FROM members WHERE id=" . $refid);
	$newref = $db->fetchOne("SELECT id FROM members WHERE rented=0 AND id!='" . $userid . "' ORDER BY Rand() LIMIT 1");
	$data = array("rented" => $userid, "rented_time" => time(), "rented_expires" => $rented_expires);
	$upd = $db->update("members", $data, "id=" . $newref);
	$data = array("rented" => 0, "rented_time" => 0, "rented_expires" => 0, "rented_clicks" => 0, "rented_lastclick" => 0, "rented_earned" => 0, "rented_autopay" => 0);
	$upd = $db->update("members", $data, "id=" . $refid);
}


if (!defined("EvolutionScript")) {
	exit("Hacking attempt...");
}

?>