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
function validate_captcha_login($field1, $field2) {
	global $lang;

	if ($field1 != $_SESSION['captcha_login'] || $field1 == "") {
		unset($_SESSION['captcha_login']);
		$stored = array("status" => 0, "msg" => $lang['txt']['invalidimageverification']);
		echo json_encode($stored);
		exit();
	}

}


if (!defined("EvolutionScript")) {
	exit("Hacking attempt...");
}

?>