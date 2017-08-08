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
$bannersql = $db->query("SELECT * FROM site_banners ORDER BY ID ASC");
$banners = array();

while ($row = $db->fetch_array($bannersql)) {
	$row['url'] = str_replace("%username%", encrypt($user_info['username']), $row['url']);
	$banners[] = $row;
}

$smarty->assign("total_banners", count($banners));
$smarty->assign("banner", $banners);
$smarty->assign("file_name", "banners.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>