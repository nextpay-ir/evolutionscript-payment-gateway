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

include SMARTYLOADER;
$countgateway = $db->fetchOne("SELECT COUNT(*) AS NUM FROM gateways WHERE allow_upgrade='yes' AND status='Active'");

if ($countgateway != 0) {
	$query = $db->query("SELECT id, account, name, currency, option1, min_deposit FROM gateways WHERE allow_upgrade='yes' AND status='Active'");

	while ($row = $db->fetch_array($query)) {
		include MODULES . "gateways/upgrade_form/" . $row['id'] . ".php";
		$oldphrase = array("[id]", "[merchant]", "[itemname]", "[currency]", "[site_url]", "[price]", "[userid]", "[option1]");
		$newphrase = array($row['id'], $row['account'], $settings['site_name'] . " Upgrade - Member: " . $user_info['username'], $row['currency'], $settings['site_url'], $row['min_deposit'], $user_info['id'], $row['option1']);
		$row['formvar'] = str_replace($oldphrase, $newphrase, $processor_form);
		$gateways[] = $row;
	}

	$smarty->assign("gateways", $gateways);
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

$mymembership = $db->fetchRow("SELECT * FROM membership WHERE id=" . $user_info['type']);
$smarty->assign("mymembership", $mymembership);
$smarty->assign("themembership", $membership);
$smarty->assign("payment", $payment);
$smarty->assign("file_name", "upgrade.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>