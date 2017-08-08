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


if ($input->p['action'] == "buy") {
	$type = $db->real_escape_string($input->p['buy']);
	$item = $db->real_escape_string($input->p['item']);

	if (!is_numeric($item)) {
		serveranswer(0, $lang['txt']['invaliditem']);
	}

	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM buyoptions WHERE name='" . $type . "'");

	if ($verify == 0) {
		serveranswer(0, $lang['txt']['invaliditem']);
	}

	$buyoptions = $db->fetchRow("SELECT * FROM buyoptions WHERE name='" . $type . "'");

	if ($buyoptions['enable'] != "yes") {
		serveranswer(0, $lang['txt']['invalidrequest']);
	}


	if ($buyoptions['hook_verify'] == "yes") {
		include "modules/buyoptions/" . $buyoptions['name'] . "_hook.php";
	}
	else {
		$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM " . $buyoptions['tblname'] . " WHERE id='" . $item . "'");

		if ($verify == 0) {
			serveranswer(0, $lang['txt']['invaliditem']);
		}

		$product = $db->fetchRow("SELECT * FROM " . $buyoptions['tblname'] . " WHERE id=" . $item);

		if ($user_info['purchase_balance'] < $product['price']) {
			serveranswer(0, $lang['txt']['enoughfundspb']);
		}

		$descr = str_replace("%descr", $product[$buyoptions['fieldassign']], $buyoptions['descr']);
	}

	$refcom = calculatecom($user_info['ref1'], $product['price'], $buyoptions['comtype']);

	if ($buyoptions['autoassign'] == "yes") {
		$status = "Completed";

		if ($user_info['ref1'] != 0) {
			$upd = $db->query("UPDATE members SET money=money+" . $refcom . ", refearnings=refearnings+" . $refcom . " WHERE id=" . $user_info['ref1']);
		}

		include "modules/buyoptions/" . $buyoptions['name'] . ".php";
	}
	else {
		$status = "Pending";
	}

	$insert = array("user_id" => $user_info['id'], "name" => $descr, "type" => $buyoptions['name'], "item_id" => $product['id'], "price" => $product['price'], "date" => time(), "status" => $status, "ref" => $user_info['ref1'], "ref_comission" => $refcom);
	$insertdb = $db->insert("order_history", $insert);
	$orderid = $db->lastInsertId();
	$upd = $db->query("UPDATE members SET purchase_balance=purchase_balance-" . $product['price'] . " WHERE id=" . $user_info['id']);
	serveranswer(1, $orderid);
}

$db->close();
exit();
?>