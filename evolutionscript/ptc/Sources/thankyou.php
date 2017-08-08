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

require SMARTYLOADER;

if ($input->g['type'] == "funds") {
	$smarty->assign("file_name", "thankyou.tpl");
	$smarty->display("account.tpl");
	$db->close();
	exit();
}
else {
	if ($input->g['type'] == "upgrade") {
		$smarty->assign("file_name", "thankyou.tpl");
		$smarty->display("account.tpl");
		$db->close();
		exit();
	}
}


if (is_numeric($input->g['order'])) {
	$order_id = $db->real_escape_string($input->g['order']);
	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM order_history WHERE id='" . $order_id . "' AND user_id=" . $user_info['id']);

	if ($verify != 0) {
		$order = $db->fetchRow("SELECT * FROM order_history WHERE id='" . $order_id . "'");
		$smarty->assign("order", $order);
	}
	else {
		header("location: index.php?view=account");
		$db->close();
		exit();
	}
}
else {
	header("location: index.php?view=account");
	$db->close();
	exit();
}

$smarty->assign("file_name", "thankyou.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>