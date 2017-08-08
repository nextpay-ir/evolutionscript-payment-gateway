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

include INCLUDES_ADMIN . "class_admin.php";
include INCLUDES_ADMIN . "class_pagination.php";
$admin = null;

if ($input->c['c_uid'] && $input->c['c_pwd'] && $input->c['c_tkn']) {
	$admin = new Admin($input->cc['c_uid'], $input->cc['c_pwd']);

	if ($admin->verify() !== true) {
		$admin->deleteSession();
	}
	else {
		if ($admin->getStatus() != "enable") {
			$admin->deleteSession();
		}
		else {
			if ($admin->checkToken($input->cc['c_tkn']) === true) {
				define("ADMINLOGGED", true);
			}
		}
	}
}


if (!defined("ADMINLOGGED")) {
	define("ADMINLOGGED", false);
}

?>