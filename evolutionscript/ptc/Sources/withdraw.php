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

include SMARTYLOADER;
$q = $db->query("SELECT method FROM deposit_history WHERE user_id=" . $user_info['id'] . " GROUP BY method");

while ($r = $db->fetch_array($q)) {
	$user_gatewayid[] = $r['method'];
}

$query = $db->query("SELECT id, name, withdraw_fee, withdraw_fee_fixed FROM gateways WHERE allow_withdrawals='yes' AND status='Active'");

while ($row = $db->fetch_array($query)) {
	if ($settings['withdraw_sameprocessor'] == "yes" && is_array($user_gatewayid)) {
		if (in_array($row['id'], $user_gatewayid)) {
			$gateway[] = $row;
		}
	}

	$gateway[] = $row;
}

$smarty->assign("gateway", $gateway);
$usrgatewayarray = unserialize($user_info['gateways']);
$usrgatewayarray = (!is_array($usrgatewayarray) ? array() : $usrgatewayarray);
$n = 0;
foreach ($usrgatewayarray as $i => $k) {
	$usrgateway[$n]['id'] = $i;
	$usrgateway[$n]['account'] = $k;
	$usrinternalgtw[$i] = $k;
	$n = $n + 1;
}

$smarty->assign("usrgateway", $usrgateway);
$mymembership = $db->fetchRow("SELECT * FROM membership WHERE id=" . $user_info['type']);
$days_cashout = $mymembership['cashout_time'] * 60 * 60 * 24;
$next_cashout = $user_info['last_cashout'] + $days_cashout;

if (time() < $next_cashout) {
	$can_cashout = "no";
	$next_datec = date("jS F, Y (h:i A)", $next_cashout);
	$smarty->assign("next_datec", $next_datec);
	$smarty->assign("can_cashout", $can_cashout);
}

$smarty->assign("mymembership", $mymembership);
$smarty->assign("ugateway", $ugateway);
$cashout_steps = explode(",", $mymembership['minimum_payout']);
$last_step = count($cashout_steps) - 1;

if ($cashout_steps[$user_info['cashout_times']] != "") {
	$minimum_cashout = $cashout_steps[$user_info['cashout_times']];
}
else {
	$minimum_cashout = $cashout_steps[$last_step];
}

$smarty->assign("minimum_cashout", $minimum_cashout);

if ($input->p['a'] == "submit") {
	verifyajax();
	$gatewayid = $input->pc['gatewayid'];
	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM gateways WHERE id=" . $gatewayid . " AND allow_withdrawals='yes' AND status='Active'");

	if ($verify == 0) {
		serveranswer(0, $lang['txt']['invalidpayment']);
	}


	if ($settings['withdraw_sameprocessor'] == "yes" && is_array($user_gatewayid)) {
		if (!in_array($gatewayid, $user_gatewayid)) {
			serveranswer(0, $lang['txt']['invalidpayment']);
		}
	}

	$gateway = $db->fetchRow("SELECT * FROM gateways WHERE id=" . $gatewayid . " AND allow_withdrawals='yes' AND status='Active'");

	if ($usrinternalgtw[$gatewayid] == "") {
		$txterror = str_replace("%paymentgateway", $gateway['name'], $lang['txt']['enteryourpg']);
		serveranswer(0, $txterror);
	}


	if ($user_info['money'] < $minimum_cashout) {
		$txterror = str_replace("%money", $minimum_cashout, $lang['txt']['nocashout']);
		serveranswer(0, $txterror);
	}


	if ($user_info['clicks'] < $settings['withdraw_clicks']) {
		$txterror = str_replace("%minclicks", $settings['withdraw_clicks'], $lang['txt']['nocashout2']);
		serveranswer(0, $txterror);
	}


	if (time() < $next_cashout) {
		$txterror = str_replace("%days", $mymembership['cashout_time'], $lang['txt']['nocashout3']);
		$txterror = str_replace("%nextcashout", $next_datec, $txterror);
		serveranswer(0, $txterror);
	}

	$new_amount = $user_info['money'] - $user_info['money'] * $gateway['withdraw_fee'] / 100 - $gateway['withdraw_fee_fixed'];
	$new_amount = floor($new_amount * 100) / 100;
	$withdraw_amount = $user_info['money'];
	$withdraw_fee = $withdraw_amount - $new_amount;

	if (0 < $mymembership['max_withdraw']) {
		if ($mymembership['max_withdraw'] < $user_info['money']) {
			$new_amount = $mymembership['max_withdraw'] - $mymembership['max_withdraw'] * $gateway['withdraw_fee'] / 100 - $gateway['withdraw_fee_fixed'];
			$withdraw_amount = $mymembership['max_withdraw'];
		}

		$withdraw_fee = $withdraw_amount - $new_amount;
	}


	if ($settings['instant_payment'] != "yes" || $mymembership['instant_withdrawal'] != "yes") {
		$insert = array("user_id" => $user_info['id'], "method" => $gateway['id'], "account" => $usrinternalgtw[$gatewayid], "amount" => $new_amount, "fee" => $withdraw_fee, "date" => TIMENOW, "status" => "Pending");
		$upd = $db->insert("withdraw_history", $insert);
		$set = array("pending_withdraw" => $user_info['pending_withdraw'] + $new_amount, "money" => $user_info['money'] - $withdraw_amount, "cashout_times" => $user_info['cashout_times'] + 1, "last_cashout" => TIMENOW);
		$upd = $db->update("members", $set, "id = " . $user_info['id']);
		serveranswer(2, "$(\"#message_sent\").show();");
	}
	else {
		$paymentnote = str_replace("%sitename%", $settings['site_name'], $gateway['option5']);
		$paymentnote = str_replace("%username%", $user_info['username'], $paymentnote);
		include "modules/gateways/instant/" . $gateway['id'] . ".php";

		if ($payment_status != "ok") {
			serveranswer(0, $lang['txt']['paymentcouldntsent']);
		}
		else {
			$insert = array("user_id" => $user_info['id'], "method" => $gateway['id'], "account" => $usrinternalgtw[$gatewayid], "amount" => $new_amount, "fee" => $withdraw_fee, "date" => TIMENOW, "status" => "Completed");
			$upd = $db->insert("withdraw_history", $insert);
			$upd = $db->query("UPDATE gateways SET total_withdraw=total_withdraw+" . $new_amount . " WHERE id=" . $gateway['id']);
			$set = array("withdraw" => $user_info['withdraw'] + $new_amount, "money" => $user_info['money'] - $withdraw_amount, "cashout_times" => $user_info['cashout_times'] + 1, "last_cashout" => time());
			$upd = $db->update("members", $set, "id = " . $user_info['id']);
			$upd = $db->query("UPDATE statistics SET value=value+" . $new_amount . " WHERE field='cashout'");
			serveranswer(2, "$(\"#message_sent\").show();");
		}
	}
}

$smarty->assign("payment", $payment);
$smarty->assign("next_cashout", $next_cashout);
$smarty->assign("withdraw_class", "ui-state-default");
$smarty->assign("file_name", "withdraw.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>