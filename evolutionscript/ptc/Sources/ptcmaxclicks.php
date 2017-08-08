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


if (!is_numeric($input->g['aid'])) {
	header("location: ./?view=account&page=manageads&class=ads");
	$db->close();
	exit();
}

$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ads WHERE id=" . $input->gc['aid'] . " AND status!='Inactive' AND user_id=" . $user_info['id']);

if ($chk == 0) {
	header("location: ./?view=account&page=manageads&class=ads");
	$db->close();
	exit();
}


if ($input->p['do'] == "update") {
	verifyajax();
	$data = array("clicks_day" => $input->pc['clicks_day']);
	$db->update("ads", $data, "id=" . $input->gc['aid']);
	serveranswer(2, "$(\"#message_sent\").show();");
}

include SMARTYLOADER;
$ad = $db->fetchRow("SELECT * FROM ads WHERE id=" . $input->gc['aid']);
$smarty->assign("ad", $ad);
$smarty->assign("file_name", "ptcmaxclicks.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>