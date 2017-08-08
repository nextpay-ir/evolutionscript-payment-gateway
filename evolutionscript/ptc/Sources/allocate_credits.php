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
$pages = array("ads", "login_ads", "banner_ads", "featured_ads", "featured_link", "ptsu_offers");
$pages_title = array($lang['txt']['ptcads'], $lang['txt']['loginads'], $lang['txt']['bannerad'], $lang['txt']['featuredad'], $lang['txt']['featuredlink'], $lang['txt']['ptsu']);

if (!empty($_REQUEST['class'])) {
	if (!in_array($_REQUEST['class'], $pages)) {
		header("location: index.php?view=account&page=manageads");
		exit();
	}

	$adid = cleanfrm($db->real_escape_string($_REQUEST['aid']));

	if (!is_numeric($adid)) {
		header("location: index.php?view=account&page=manageads");
		exit();
	}

	$verifyid = $db->fetchOne("SELECT COUNT(*) AS NUM FROM " . $_REQUEST['class'] . " WHERE id=" . $adid . " AND user_id=" . $user_info['id']);

	if ($verifyid == 0) {
		header("location: index.php?view=account&page=manageads");
		exit();
	}

	$ads = $db->fetchRow("SELECT * FROM " . $_REQUEST['class'] . " WHERE id=" . $adid);
	$count = 0;

	while ($count < count($pages)) {
		if ($pages[$count] == $_REQUEST['class']) {
			$key = $count;
		}

		++$count;
	}


	if ($_REQUEST['class'] == "ads") {
		$listcountry = $db->query("SELECT * FROM country ORDER BY name ASC");

		while ($list = $db->fetch_array($listcountry)) {
			$country[] = $list;
		}

		$smarty->assign("countrylist", $country);
		$clickvalue = $db->fetchOne("SELECT credits FROM ad_value WHERE value='" . $ads['value'] . "'");
		$smarty->assign("advalue", $ads['value']);
		$smarty->assign("creditcost", $clickvalue);
	}
	else {
		if ($_REQUEST['class'] == "ptsu_offers") {
			$listcountry = $db->query("SELECT * FROM country ORDER BY name ASC");

			while ($list = $db->fetch_array($listcountry)) {
				$country[] = $list;
			}

			$smarty->assign("countrylist", $country);
			$clickvalue = $db->fetchOne("SELECT credits FROM ptsu_value WHERE value='" . $ads['value'] . "'");
			$smarty->assign("advalue", $ads['value']);
			$smarty->assign("creditcost", $clickvalue);
		}
	}

	$smarty->assign("referrer", $_SERVER['HTTP_REFERER']);
	$smarty->assign("page_title", $pages_title[$key]);
	$smarty->assign("page_id", $pages[$key]);
	$smarty->assign("aditem", $ads);
	$smarty->assign("manageads_class", "ui-state-default");
	$smarty->assign("file_name", "allocate.tpl");
	$smarty->display("account.tpl");
	$db->close();
	exit();
	return 1;
}

header("location: index.php?view=account&page=manageads");
$db->close();
exit();
?>