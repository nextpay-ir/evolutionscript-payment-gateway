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


if ($user_info['money'] < $item) {
	serveranswer(0, $lang['txt']['noenoughfundsab']);
}


if ($item < $settings['amount_transfer']) {
	serveranswer(0, "The minimum amount to transfer is \$" . $settings['amount_transfer']);
}

$product['price'] = $item;
$product['id'] = $item;
$descr = str_replace("%descr", $item, $buyoptions['descr']);
?>