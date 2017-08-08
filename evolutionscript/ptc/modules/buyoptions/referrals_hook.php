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

$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM referral_price WHERE id='" . $item . "'");

if ($verify == 0) {
	serveranswer(0, $lang['txt']['invaliditem']);
}

$product = $db->fetchRow("SELECT * FROM referral_price WHERE id=" . $item);

if ($user_info['purchase_balance'] < $product['price']) {
	serveranswer(0, $lang['txt']['enoughfundspb']);
}

$total_refs = referralsleft($user_info['id']);

if ($total_refs < $product['refs']) {
	serveranswer(0, $lang['txt']['norefsenought']);
}

$descr = str_replace("%descr", $product['refs'], $buyoptions['descr']);
?>