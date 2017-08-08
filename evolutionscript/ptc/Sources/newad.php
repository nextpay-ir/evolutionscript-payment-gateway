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
$pages = array("ads", "login_ads", "banner_ads", "featured_ads", "featured_link", "ptsu_offers");
$pages_title = array($lang['txt']['ptcads'], $lang['txt']['loginads'], $lang['txt']['bannerad'], $lang['txt']['featuredad'], $lang['txt']['featuredlink'], $lang['txt']['ptsu']);

if (!empty($_REQUEST['class'])) {
	if (!in_array($_REQUEST['class'], $pages)) {
		header("location: index.php?view=account&page=manageads");
		exit();
	}

	$ads = $db->query("SELECT * FROM " . $_REQUEST['class'] . " WHERE user_id=" . $user_info['id']);

	while ($list = $db->fetch_array($ads)) {
		$adlist[] = $list;
	}

	$count = 0;

	while ($count < count($pages)) {
		if ($pages[$count] == $_REQUEST['class']) {
			$key = $count;
		}

		++$count;
	}


	if ($_REQUEST['class'] == "ads") {
		$listcountry = $db->query("SELECT country FROM ip2nationCountries ORDER BY country ASC");

		while ($list = $db->fetch_array($listcountry)) {
			$country[] = $list;
		}

		$smarty->assign("countrylist", $country);
		$listvalue = $db->query("SELECT * FROM ad_value ORDER BY value ASC");

		while ($list = $db->fetch_array($listvalue)) {
			$values[] = $list;
		}

		$smarty->assign("listvalue", $values);
	}
	else {
		if ($_REQUEST['class'] == "ptsu_offers") {
			$listcountry = $db->query("SELECT country FROM ip2nationCountries ORDER BY country ASC");

			while ($list = $db->fetch_array($listcountry)) {
				$country[] = $list;
			}

			$smarty->assign("countrylist", $country);
			$listvalue = $db->query("SELECT * FROM ptsu_value ORDER BY value ASC");

			while ($list = $db->fetch_array($listvalue)) {
				$values[] = $list;
			}

			$smarty->assign("listvalue", $values);
		}
	}

	$smarty->assign("referrer", $_SERVER['HTTP_REFERER']);
	$smarty->assign("page_title", $pages_title[$key]);
	$smarty->assign("page_id", $pages[$key]);
	$smarty->assign("pagesid", $pages);
	$smarty->assign("pages", $pages_title);
	$smarty->assign("adlist", $adlist);
	$smarty->assign("file_name", "newad.tpl");
	$smarty->display("account.tpl");
	$db->close();
	exit();
	return 1;
}

header("location: index.php?view=account&page=manageads");
$db->close();
exit();
?>