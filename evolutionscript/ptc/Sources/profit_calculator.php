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
$membershipsql = $db->query("SELECT name, ref_click, rentedref_limit FROM membership ORDER BY ID ASC");
$membershiplist = array();

while ($row = $db->fetch_array($membershipsql)) {
	$membershiplist[] = $row;
}

$membershiplist = json_encode($membershiplist);
$membershiplist = "var membershipList = {\"membership\":" . $membershiplist . "};";
$smarty->assign("membershiplist", $membershiplist);
$click_value = $db->fetchOne("SELECT value FROM ad_value ORDER BY value DESC LIMIT 1");
$smarty->assign("click_value", $click_value);
$smarty->assign("themembership", $membership);
$smarty->assign("mymembership", $mymembership);
$smarty->assign("payment", $payment);
$smarty->assign("profitcalculator_class", "ui-state-default");
$smarty->assign("file_name", "profit_calculator.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>