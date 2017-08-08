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

$count = $db->fetchOne("SELECT COUNT(*) AS NUM FROM membership WHERE id=" . $upgrade_id);

if ($count == 0) {
	exit();
}

$membership = $db->fetchRow("SELECT id, price, name, duration FROM membership WHERE id=" . $upgrade_id);

if ($amount < $membership['price']) {
	exit();
}

$mymembership = $db->fetchOne("SELECT type FROM members WHERE id=" . $order_id);

if ($mymembership == $upgrade_id) {
	extendmembership($order_id, $membership['duration']);
}
else {
	addmembership($order_id, $membership['duration'], $membership['id']);
}

$data = array("user_id" => $order_id, "name" => $membership['name'] . " Membership", "type" => "membership", "item_id" => $membership['id'], "price" => $membership['price'], "date" => TIMENOW, "status" => "Completed");
$db->insert("order_history", $data);
$data = array("user_id" => $order_id, "method" => $gateway['id'], "fromacc" => $customer, "amount" => $amount, "batch" => $batch, "date" => TIMENOW);
$db->insert("deposit_history", $data);
$db->query("UPDATE gateways SET total_deposit=total_deposit+" . $amount . " WHERE id=" . $gateway['id']);
$refid = $db->fetchOne("SELECT ref1 FROM members  WHERE id=" . $order_id);

if ($refid != 0) {
	$comtype = $db->fetchOne("SELECT comtype FROM buyoptions WHERE name='membership'");
	$refcom = calculatecom($refid, $amount, $comtype);
	$upd = $db->query("UPDATE members SET money=money+" . $refcom . ", refearnings=refearnings+" . $refcom . " WHERE id=" . $refid);
}

$membershiptype = $db->fetchOne("SELECT type FROM members WHERE id=" . $order_id);
$membership = $db->fetchRow("SELECT point_enable, point_deposit FROM membership WHERE id=" . $membershiptype);

if ($membership['point_enable'] == 1) {
	$pointsperdollar = floor($amount) * $membership['point_deposit'];
	addpoints($order_id, $pointsperdollar);
}

?>