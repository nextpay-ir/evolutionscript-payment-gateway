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

$countgateway = $db->fetchOne("SELECT COUNT(*) AS NUM FROM gateways WHERE allow_deposits='yes' AND status='Active'");

if ($countgateway != 0) {
	$query = $db->query("SELECT id, api_key, name, currency, option1, min_deposit FROM gateways WHERE allow_deposits='yes' AND status='Active'");

	while ($row = $db->fetch_array($query)) {
		include MODULES . "gateways/deposit_form/" . $row['id'] . ".php";
		$oldphrase = array("[id]", "[merchant]", "[itemname]", "[currency]", "[site_url]", "[price]", "[userid]", "[option1]");
		$newphrase = array($row['id'], $row['api_key'], $settings['site_name'] . " add funds - Member:" . $user_info['username'], $row['currency'], $settings['site_url'], $row['min_deposit'], $user_info['id'], $row['option1']);
		$row['formvar'] = str_replace($oldphrase, $newphrase, $processor_form);
		$gateways[] = $row;
	}
}


require SMARTYLOADER;
$smarty->assign("countgateway", $countgateway);
$smarty->assign("gateways", $gateways);
$smarty->assign("file_name", "addfunds.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>