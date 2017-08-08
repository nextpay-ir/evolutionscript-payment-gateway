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

$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM deposit_history WHERE batch='" . $batch . "' AND method='" . $gateway['id'] . "'");

if ($chk != 0) {
	exit();
}


if ($amount < $gateway['min_deposit']) {
	exit();
}


$data = array("user_id" => $order_id, "method" => $gateway['id'], "fromacc" => $customer, "amount" => $amount, "batch" => $batch, "date" => TIMENOW);
$db->insert("deposit_history", $data);
$db->query("UPDATE members SET purchase_balance=purchase_balance+" . $amount . " WHERE id=" . $order_id);
$db->query("UPDATE gateways SET total_deposit=total_deposit+" . $amount . " WHERE id=" . $gateway['id']);
$membershiptype = $db->fetchOne("SELECT type FROM members WHERE id=" . $order_id);
$membership = $db->fetchRow("SELECT point_enable, point_deposit FROM membership WHERE id=" . $membershiptype);

if ($membership['point_enable'] == 1) {
	$pointsperdollar = floor($amount) * $membership['point_deposit'];
	addpoints($order_id, $pointsperdollar);
}

?>