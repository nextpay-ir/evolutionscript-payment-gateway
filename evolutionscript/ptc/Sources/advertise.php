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
$query = $db->query("SELECT * FROM ads_price ORDER BY credits ASC");

while ($row = $db->fetch_array($query)) {
	$ad_prices[] = $row;
}

$smarty->assign("ad_prices", $ad_prices);
unset($ad_prices);
$query = $db->query("SELECT * FROM loginads_price ORDER BY days ASC");

while ($row = $db->fetch_array($query)) {
	$loginad_prices[] = $row;
}

$smarty->assign("loginad_prices", $loginad_prices);
unset($loginad_prices);

if ($settings['ptsu_available'] == "yes") {
	$query = $db->query("SELECT * FROM ptsu_price ORDER BY credits ASC");

	while ($row = $db->fetch_array($query)) {
		$ptsu_price[] = $row;
	}

	$smarty->assign("ptsu_price", $ptsu_price);
	unset($ptsu_price);
}


if ($settings['fads_available'] == "yes") {
	$query = $db->query("SELECT * FROM fads_price ORDER BY credits ASC");

	while ($row = $db->fetch_array($query)) {
		$fads_price[] = $row;
	}

	$smarty->assign("fads_price", $fads_price);
	unset($fads_price);
}


if ($settings['bannerads_available'] == "yes") {
	$query = $db->query("SELECT * FROM banner_price ORDER BY credits ASC");

	while ($row = $db->fetch_array($query)) {
		$banner_price[] = $row;
	}

	$smarty->assign("banner_price", $banner_price);
	unset($banner_price);
}


if ($settings['flinks_available'] == "yes") {
	$query = $db->query("SELECT * FROM flinks_price ORDER BY month ASC");

	while ($row = $db->fetch_array($query)) {
		$flinks_price[] = $row;
	}

	$smarty->assign("flinks_price", $flinks_price);
	unset($flinks_price);
}


if ($settings['special_available'] == "yes") {
	$query = $db->query("SELECT * FROM specialpacks WHERE enable='yes' ORDER BY id ASC");

	while ($row = $db->fetch_array($query)) {
		$specialpacks[] = $row;
	}

	$packitems = $db->query("SELECT * FROM specialpacks_list ORDER BY specialpack ASC");

	while ($row = $db->fetch_array($packitems)) {
		if ($row['type'] == "membership") {
			$row['amount'] = $db->fetchOne("SELECT name FROM membership WHERE id=" . $row['amount']);

			if (empty($row['amount'])) {
				continue;
			}
		}

		$specialitems[] = $row;
	}

	$specialitems = json_encode($specialitems);
	$specialpackitems = "var specialitemsList = {\"specialitems\":" . $specialitems . "};";
	$smarty->assign("specialpacks", $specialpacks);
	$smarty->assign("specialpackitems", $specialpackitems);
	unset($specialpacks);
	unset($specialpackitems);
}

$smarty->assign("special_packages", $special_available);
$smarty->display("advertise.tpl");
$db->close();
exit();
?>