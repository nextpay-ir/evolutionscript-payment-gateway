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

$time_upg = $db->fetchOne("SELECT duration FROM membership WHERE id=" . $order['item_id']);
$usertype = $db->fetchOne("SELECT type FROM members WHERE id=" . $order['user_id']);

if ($usertype != $order['item_id']) {
	addmembership($order['user_id'], $time_upg, $order['item_id']);
	return 1;
}

extendmembership($order['user_id'], $time_upg);
?>