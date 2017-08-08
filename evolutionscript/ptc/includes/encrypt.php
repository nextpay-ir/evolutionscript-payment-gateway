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
function ascii2hex($ascii) {
	$hex = "";

	for ($i = 0;$i < strlen($ascii);$i++) {
		$byte = strtoupper(dechex(ord($ascii[$i])));
		$byte = str_repeat("0", 2 - strlen($byte)) . $byte;
		$hex .= $byte;
	}

	return $hex;
}

function hex2ascii($hex) {
	$ascii = "";
	$hex = str_replace(" ", "", $hex);

	for ($i = 0;$i < strlen($hex);$i = $i + 2) {
		$ascii .= chr(hexdec(substr($hex, $i, 2)));
	}

	return $ascii;
}


if (!defined("EvolutionScript")) {
	exit("Hacking attempt...");
}

?>