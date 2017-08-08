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

$membership = $db->fetchRow("SELECT point_enable, point_upgrade, point_upgraderate FROM membership WHERE id=" . $user_info['type']);

if ($membership['point_enable'] != 1 || $membership['point_upgrade'] != 1) {
	serveranswer(0, $lang['txt']['invalidpayment']);
}

$product = $db->fetchRow("SELECT * FROM " . $buyoptions['tblname'] . " WHERE id=" . $item);
$points_price = $product['price'] * $membership['point_upgraderate'];

if ($user_info['points'] < $points_price) {
	serveranswer(0, $lang['txt']['noenoughpoints']);
}

?>