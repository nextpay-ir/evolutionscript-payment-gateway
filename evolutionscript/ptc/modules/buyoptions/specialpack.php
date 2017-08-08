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

$listq = $db->query("SELECT * FROM specialpacks_list WHERE specialpack=" . $product['id']);

while ($package = $db->fetch_array($listq)) {
	if ($package['type'] == "ptc_credits") {
		addptccredits($user_info['id'], $package['amount']);
	}


	if ($package['type'] == "fads_credits") {
		addfadscredits($user_info['id'], $package['amount']);
	}


	if ($package['type'] == "banerads_credits") {
		addbannercredits($user_info['id'], $package['amount']);
	}


	if ($package['type'] == "flink_credits") {
		addflinkcredits($user_info['id'], $package['amount']);
	}


	if ($package['type'] == "ptsu_credits") {
		addptsucredits($user_info['id'], $package['amount']);
	}


	if ($package['type'] == "direct_refs") {
		addboughtmembers($user_info['id'], $package['amount']);
	}


	if ($package['type'] == "rented_refs") {
		addrentreferrals($user_info['id'], $package['amount']);
	}


	if ($package['type'] == "membership") {
		if ($user_info['type'] == $package['amount']) {
			$duration = $db->fetchOne("SELECT duration FROM membership WHERE id=" . $user_info['type']);
			extendmembership($user_info['id'], $duration);
		}

		$duration = $db->fetchOne("SELECT duration FROM membership WHERE id=" . $package['amount']);
		addmembership($user_info['id'], $duration, $package['amount']);
	}
}

$upd = $db->query("UPDATE specialpacks SET buys=buys+1 WHERE id=" . $product['id']);
?>