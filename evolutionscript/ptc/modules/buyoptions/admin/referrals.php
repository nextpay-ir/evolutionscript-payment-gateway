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

$package = $db->fetchOne("SELECT refs FROM referral_price WHERE id=" . $order['item_id']);
$countrefs = referralsleft($order['user_id']);

if ($package <= $countrefs) {
	addboughtmembers($order['user_id'], $package);
	return 1;
}

$error_msg = "There is not enough referrals to add.";
?>