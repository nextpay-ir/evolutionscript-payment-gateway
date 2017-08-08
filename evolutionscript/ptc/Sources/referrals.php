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

$ref_deletion = $db->fetchOne("SELECT referral_deletion FROM membership WHERE id=" . $user_info['type']);

if ($input->p['action'] == "delete") {
	verifyajax();
	$total_ref = count($input->p['ref']);
	$total_pay = $ref_deletion * $total_ref;

	if ($user_info['purchase_balance'] < $total_pay) {
		serveranswer(0, $lang['txt']['nofoundspb']);
	}

	foreach ($input->p['ref'] as $refid) {
		$verifyuser = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE ref1=" . $user_info['id'] . " AND id=" . $refid);

		if ($verifyuser != 0) {
			$db->query("UPDATE members SET referrals=referrals-1, myrefs1=myrefs1-1 WHERE id=" . $user_info['id']);
			$db->query("UPDATE members SET ref1=0, for_refclicks=0, for_refearned=0 WHERE id=" . $refid);
			continue;
		}
	}

	$upd = $db->query("UPDATE members SET purchase_balance=purchase_balance-" . $total_pay . " WHERE id=" . $user_info['id']);
	serveranswer(1, "location.href=\"./?view=account&page=referrals\";");
}

include SMARTYLOADER;
include INCLUDES . "class_pagination.php";
$allowed = array("username", "signup", "for_reflastclick", "for_refclicks", "for_refearned", "country", "comes_from");
$paginator = new Pagination("members", "ref1=" . $user_info['id']);
$paginator->setMaxResult($settings['max_result_page']);
$paginator->setOrders("signup", "DESC");
$paginator->setPage($input->gc['p']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=account&page=referrals&");
$q = $paginator->getQuery();

while ($list = $db->fetch_array($q)) {
	$items[] = $list;
}

$smarty->assign("paginator", $paginator);
$smarty->assign("thelist", $items);
unset($items);
$smarty->assign("ref_deletion", $ref_deletion);
$smarty->assign("file_name", "referrals.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>