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

$mymembership = $db->fetchRow("SELECT * FROM membership WHERE id=" . $user_info['type']);

if ($mymembership['point_enable'] != 1) {
	header("location: ./?view=account");
	$db->close();
	exit();
}


if ($mymembership['point_upgrade'] != 1 && $mymembership['point_purchasebalance'] != 1) {
	header("location: ./?view=account");
	$db->close();
	exit();
}


if ($input->p['do'] == "convertpoints" && $mymembership['point_purchasebalance'] == 1) {
	verifyajax();

	if (!is_numeric($input->p['points']) || $input->p['points'] <= 0) {
		serveranswer(0, $lang['txt']['invalidrequest']);
	}


	if ($user_info['points'] < $input->p['points']) {
		serveranswer(0, $lang['txt']['noenoughpoints']);
	}

	$uspoints = $db->real_escape_string($input->p['points']);
	$conversion = $input->p['points'] / $mymembership['point_cashrate'];

	if ($conversion < 0.01) {
		$minconversion = 0.01 * $mymembership['point_cashrate'];
		serveranswer(0, "Minimum conversion is " . $minconversion);
	}

	$upd = $db->query("UPDATE members SET points=points-" . $uspoints . ", purchase_balance=purchase_balance+" . $conversion . " WHERE id=" . $user_info['id']);
	serveranswer(5, $lang['txt']['orderdone']);
}

$res = $db->query("SELECT * FROM membership WHERE active='yes' or id=" . $user_info['type'] . " ORDER BY price ASC");

while ($list = $db->fetch_array($res)) {
	$number = "";
	$mincashoutarray = explode(",", $list['minimum_payout']);
	foreach ($mincashoutarray as $k => $v) {
		$number .= "\$" . number_format($v, 2, ".", ",") . ", ";
	}

	$totalchars = count($number) - 1;
	$number = substr($number, $totalchars, -2);
	$list['minimum_payout'] = $number;
	$membership[] = $list;
}

require SMARTYLOADER;
$smarty->assign("mymembership", $mymembership);
$smarty->assign("themembership", $membership);
$smarty->assign("file_name", "convert_points.tpl");
$smarty->display("account.tpl");
?>