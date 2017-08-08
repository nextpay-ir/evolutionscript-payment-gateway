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

if ($settings['loginads_available'] != "yes") {
	unset($pages[1]);
	unset($pages_title[1]);
}


if ($settings['bannerads_available'] != "yes") {
	unset($pages[2]);
	unset($pages_title[2]);
}


if ($settings['fads_available'] != "yes") {
	unset($pages[3]);
	unset($pages_title[3]);
}


if ($settings['flinks_available'] != "yes") {
	unset($pages[4]);
	unset($pages_title[4]);
}


if ($settings['ptsu_available'] != "yes") {
	unset($pages[5]);
	unset($pages_title[5]);
}


if (!empty($input->g['class'])) {
	if (!in_array($input->g['class'], $pages)) {
		header("location: index.php?view=account&page=manageads");
		exit();
	}

	$count = 0;
	foreach ($pages as $k => $v) {

		if ($pages[$k] == $_REQUEST['class']) {
			$key = $k;
			break;
		}
	}

	$class_page = $input->gc['class'];
	$smarty->assign("page_title", $pages_title[$key]);
	$smarty->assign("page_id", $pages[$key]);
}
else {
	$class_page = "ads";
	$smarty->assign("page_title", "Paid To Click Ads");
	$smarty->assign("page_id", "ads");
}

include INCLUDES . "class_pagination.php";
switch ($input->g['class']) {
	case "ads":
		$allowed = array("title", "click_pack", "clicks", "outside_clicks", "clicks_today");
		break;

	case "ptsu_offers":
		$allowed = array("title", "credits", "approved", "pending");
		break;

	default :
		$allowed = array("title", "credits", "views", "clicks", "expires");
		break;
}

$paginator = new Pagination($class_page, "user_id=" . $user_info['id']);
$paginator->setMaxResult($settings['max_result_page']);
$paginator->setOrders("id", "DESC");
$paginator->setPage($input->gc['p']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=account&page=manageads&class=" . $class_page . "&");
$q = $paginator->getQuery();

while ($list = $db->fetch_array($q)) {
	$items[] = $list;
}

$smarty->assign("paginator", $paginator);
$smarty->assign("thelist", $items);
unset($items);
$smarty->assign("pagesid", $pages);
$smarty->assign("pages", $pages_title);
$smarty->assign("adlist", $adlist);
$smarty->assign("file_name", "manageads.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>