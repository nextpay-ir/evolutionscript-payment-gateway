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

$countrefs = rentedreferralsleft($order['user_id']);

if ($order['item_id'] <= $countrefs) {
	$rentaction = addrentreferrals($order['user_id'], $order['item_id']);
	return 1;
}

$error_msg = "There is not enough referrals to add.";
?>