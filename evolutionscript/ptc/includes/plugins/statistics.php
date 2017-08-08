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

$statistics = $cache->get("statistics");

if ($statistics == null) {
	$q = $db->query("SELECT * FROM statistics");

	while ($r = $db->fetch_array($q)) {
		$statistics[$r['field']] = $r['value'];
	}

	$lastcheck = $statistics['last_check'];
	$statistics['cashout'] = number_format($statistics['cashout'], 2, ".", ",");

	if ($settings['usersonline'] == 1) {
		$checkonline = TIMENOW - $lastcheck;

		if (1800 < $checkonline) {
			$deletetime = TIMENOW - 1800;
			$db->delete("users_online", "date<=" . $deletetime);
			$db->query("UPDATE statistics SET value='" . TIMENOW . "' WHERE field='last_check'");
		}

		$totalmembers = $db->fetchOne("SELECT COUNT(*) AS NUM FROM users_online");
		$statistics['usersonline'] = $totalmembers;
	}

	$cache->set("statistics", $statistics, 300);
}


if ($settings['usersonline'] == 1) {
	$checkmyip = $db->fetchOne("SELECT COUNT(*) AS NUM FROM users_online WHERE ip='" . $_SERVER['REMOTE_ADDR'] . "'");

	if ($checkmyip == 0) {
		$datastored = array("ip" => $_SERVER['REMOTE_ADDR'], "date" => TIMENOW);
		$db->insert("users_online", $datastored);
	}
}

?>