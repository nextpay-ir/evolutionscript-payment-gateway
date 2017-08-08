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

$max_results = $settings['max_result_page'];

if (is_numeric($_REQUEST['id'])) {
	$ptsu_id = cleanfrm($_REQUEST['id']);
	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptsu_offers WHERE id=" . $ptsu_id . " AND user_id=" . $user_info['id']);

	if ($_REQUEST['a'] == "accept") {
		verifyajax();
		$request_id = cleanfrm($_POST['rid']);
		$verify_req = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptsu_requests WHERE id='" . $request_id . "' AND ptsu_id=" . $ptsu_id);

		if ($verify_req != 0) {
			$acc2pay = $db->fetchRow("SELECT user_id, value FROM ptsu_requests WHERE id='" . $request_id . "'");
			$upd = $db->query("UPDATE ptsu_requests SET status='Completed' WHERE id=" . $request_id);
			$upd = $db->query("UPDATE members SET money=money+" . $acc2pay['value'] . " WHERE id=" . $acc2pay['user_id']);
			$upd = $db->query("UPDATE ptsu_offers SET approved=approved+1, pending=pending-1 WHERE id=" . $ptsu_id);
			$membershiptype = $db->fetchOne("SELECT type FROM members WHERE id=" . $acc2pay['user_id']);
			$membership = $db->fetchRow("SELECT point_enable, point_ptsu FROM membership WHERE id=" . $membershiptype);

			if ($membership['point_enable'] == 1) {
				addpoints($acc2pay['user_id'], $membership['point_ptsu']);
			}

			serveranswer(1, "");
		}
		else {
			serveranswer(0, $lang['txt']['invalidrequest']);
		}
	}
	else {
		if ($_REQUEST['a'] == "reject") {
			verifyajax();
			$request_id = cleanfrm($_POST['rid']);
			$message = cleanfrm($_POST['message']);
			$verify_req = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptsu_requests WHERE id='" . $request_id . "' AND ptsu_id=" . $ptsu_id);

			if ($verify_req != 0) {
				if (empty($message)) {
					serveranswer(0, $lang['txt']['fieldsempty']);
				}
				else {
					$upd = $db->query("UPDATE ptsu_requests SET status='Rejected1', advertiser_notes='" . $message . "' WHERE id=" . $request_id);
					serveranswer(1, "");
				}
			}
			else {
				serveranswer(0, $lang['txt']['invalidrequest']);
			}
		}
	}


	if ($verify != 0) {
		$countlist = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptsu_requests WHERE ptsu_id=" . $ptsu_id);
	}
	else {
		header("location: index.php?view=account&page=manageads&class=ptsu_offers");
		$db->close();
		exit();
	}
}
else {
	header("location: index.php?view=account&page=manageads&class=ptsu_offers");
	$db->close();
	exit();
}

include SMARTYLOADER;
include INCLUDES . "class_pagination.php";
$allowed = array("date", "account", "method", "amount", "status");
$paginator = new Pagination("ptsu_requests", "ptsu_id=" . $input->g['id'] . " AND (status='Pending' OR status='Rejected1')");
$paginator->setMaxResult($settings['max_result_page']);
$paginator->setOrders("date", "DESC");
$paginator->setPage($input->gc['p']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=account&page=pending_ptsu&id=" . $input->g['id'] . "&");
$q = $paginator->getQuery();

while ($list = $db->fetch_array($q)) {
	$list['ptcusername'] = $db->fetchOne("SELECT username FROM members WHERE id=" . $list['user_id']);
	$list['message'] = nl2br($list['message']);
	$items[] = $list;
}

$smarty->assign("paginator", $paginator);
$smarty->assign("thelist", $items);
unset($items);
$smarty->assign("file_name", "pending_ptsu.tpl");
$smarty->display("account.tpl");
$db->close();
?>