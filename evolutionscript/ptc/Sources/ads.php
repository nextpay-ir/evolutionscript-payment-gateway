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

if ($_SESSION['logged'] == "yes") {
	$add_sql = "membership LIKE '%," . $user_info['type'] . ",%' and";
	$count_ads = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ads WHERE status='Active' and click_pack!='0' and (clicks_today<clicks_day OR clicks_day=0) and " . $add_sql . " (country LIKE \"%" . $user_info['country'] . "%\")");
	$advisited = explode(", ", $user_info['advisto']);
	$verifyAdminAd = $db->fetchOne("SELECT COUNT(*) AS NUM FROM admin_advertisement WHERE ad_expires>=" . TIMENOW);

	if ($verifyAdminAd != 0) {
		$adminAd = $db->fetchRow("SELECT * FROM admin_advertisement");
		$smarty->assign("adminAdvertisement", $adminAd);
	}

	$smarty->assign("advisited", $advisited);
	unset($advisited);
}
else {
	$count_ads = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ads WHERE status='Active' and click_pack!='0'");
	$advisited = array();
	$smarty->assign("advisited", $advisited);
	unset($advisited);
}

$adcatq = $db->query("SELECT * FROM ad_value ORDER BY value DESC");
$n = 0;

while ($row = $db->fetch_array($adcatq)) {
	$adcategory[$n]['id'] = $row['id'];
	$adcategory[$n]['name'] = $row['catname'];
	$adcategory[$n]['value'] = $row['value'];
	$adcategory[$n]['hide_descr'] = $row['hide_descr'];
	$n = $n + 1;
}

$smarty->assign("adcategory", $adcategory);
unset($adcategory);

if ($count_ads != 0) {
	if ($_SESSION['logged'] == "yes") {
		$res = $db->query("SELECT * FROM ads WHERE status='Active' and click_pack!='0' and (clicks_today<clicks_day OR clicks_day=0)  and " . $add_sql . " (country LIKE \"%" . $user_info['country'] . "%\" OR country='all') ORDER BY value DESC");
		$memval = $db->fetchOne("SELECT click FROM membership WHERE id=" . $user_info['type']);
	}
	else {
		$res = $db->query("SELECT * FROM ads WHERE status='Active' and click_pack!='0' ORDER BY value DESC");
	}

	$ad_check = 0;

	while ($list = $db->fetch_array($res)) {
		if ($_SESSION['logged'] == "yes") {
			$list['value'] = $list['value'] * $memval / 100;
		}


		if ($ad_check == 0 && $_SESSION['logged'] == "yes") {
			$list2 = array();
			$_SESSION['adSync'] = md5(TIMENOW);
			$list2['token'] = $_SESSION['adSync'];
			$ad_check = 1;
		}

		$ad[] = $list;
	}

	$ad[] = $list2;
	$smarty->assign("advertisement", $ad);
	unset($ad);
}


if ($settings['click_yesterday'] == "yes") {
	$myclicks = array("mc1", "mc2", "mc3", "mc4", "mc5", "mc6", "mc7");

	if ($user_info[$myclicks[$user_info['chart_num']]] < $settings['clicks_necessary']) {
		$smarty->assign("clicks_today", $user_info[$myclicks[$user_info['chart_num']]]);
		$smarty->assign("clicks_necessary", $settings['clicks_necessary'] - $user_info[$myclicks[$user_info['chart_num']]]);
		$smarty->assign("show_advice", "yes");
	}
}

$smarty->assign("adsreset", $adsreset);
$smarty->assign("myads", $count_ads);
$smarty->display("ads.tpl");
$db->close();
exit();
?>