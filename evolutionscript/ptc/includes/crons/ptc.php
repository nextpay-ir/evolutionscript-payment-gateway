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

$reset_ptcads = NulledBy_MTimer($user_info['id'], $user_info['chart_num'], $days_diff);

if ($user_info['ref1'] != 0) {
	$ref_info = $db->fetchRow("SELECT id, chart_num, last_cron FROM members WHERE id=" . $user_info['ref1']);

	if ($ref_info['last_cron'] == "") {
		$db->query("UPDATE members SET last_cron='" . $today_date . "', mc1=0, r1=0, rr1=0, ap1=0 WHERE id=" . $ref_info['id']);
	}
	else {
		$refdays_diff = dateDiff($ref_info['last_cron'], $today_date);

		if (1 <= $refdays_diff) {
			$resetref_ptcads = NulledBy_MTimer($ref_info['id'], $ref_info['chart_num'], $refdays_diff, "ref");
		}
	}
}


if ($user_info['rented'] != 0) {
	$ref_info = $db->fetchRow("SELECT id, chart_num, last_cron FROM members WHERE id=" . $user_info['rented']);

	if ($ref_info['last_cron'] == "") {
		$db->query("UPDATE members SET last_cron='" . $today_date . "', mc1=0, r1=0, rr1=0, ap1=0 WHERE id=" . $ref_info['id']);
		return 1;
	}

	$refdays_diff = dateDiff($ref_info['last_cron'], $today_date);

	if (1 <= $refdays_diff) {
		$resetref_ptcads = NulledBy_MTimer($ref_info['id'], $ref_info['chart_num'], $refdays_diff, "ref");
	}
}

?>