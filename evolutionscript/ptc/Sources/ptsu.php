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


if ($settings['ptsu_available'] != "yes") {
	header("location: index.php?view=account");
	$db->close();
	exit();
}

include SMARTYLOADER;

if ($settings['ptsu_exclusion'] <= $user_info['ptsu_denied'] && 0 < $settings['ptsu_exclusion']) {
	$smarty->assign("file_name", "ptsu.tpl");
	$smarty->display("account.tpl");
	$db->close();
	exit();
}

$add_sql = "(membership LIKE '%," . $user_info['type'] . ",%') AND";

if (is_numeric($input->g['id'])) {
	$ptsu_id = $input->gc['id'];
	$count_ads = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptsu_offers WHERE status='Active' and credits!='0' and id=" . $ptsu_id . " and " . $add_sql . " (country LIKE '%" . $user_info['country'] . "%')");
	$adviewed = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptsu_requests WHERE ptsu_id=" . $ptsu_id . " and user_id=" . $user_info['id']);

	if ($input->p['action'] == "submit") {
		verifyajax();

		if ($count_ads == 0) {
			serveranswer(0, $lang['txt']['adnotfound']);
		}

		$ptsu_details = $db->fetchRow("SELECT * FROM ptsu_offers WHERE id=" . $ptsu_id);

		if ($adviewed != 0) {
			serveranswer(0, $lang['txt']['adnotfound']);
		}


		if (empty($input->p['username']) || empty($input->p['message'])) {
			serveranswer(0, $lang['txt']['fieldsempty']);
		}

		$db->query("UPDATE ptsu_offers SET credits=credits-1, pending=pending+1 WHERE id=" . $ptsu_id);
		$datastore = array("ptsu_id" => $ptsu_id, "owner_id" => $ptsu_details['user_id'], "user_id" => $user_info['id'], "username" => $input->pc['username'], "message" => $input->pc['message'], "title" => $ptsu_details['title'], "value" => $ptsu_details['value'], "url" => $ptsu_details['url'], "date" => TIMENOW);
		$insert = $db->insert("ptsu_requests", $datastore);
		serveranswer(2, "$(\"#message_sent\").show();");
	}


	if ($count_ads != 0) {
		$ptsu_details = $db->fetchRow("SELECT * FROM ptsu_offers WHERE id=" . $ptsu_id);
		$ptsu_details['web'] = str_replace("http://", "", $ptsu_details['url']);
		$ptsu_details['web'] = str_replace("https://", "", $ptsu_details['web']);
		$ptsu_details['web'] = explode("/", $ptsu_details['web']);
		$ptsu_details['web'] = $ptsu_details['web'][0];
		$advisited = explode(", ", $ptsu_details['members_done']);
		$smarty->assign("ptsu_details", $ptsu_details);
		$smarty->assign("advisited", $advisited);
	}


	if ($count_ads == 0 || $adviewed != 0) {
		$myads = 0;
	}
	else {
		$myads = 1;
	}

	$smarty->assign("myads", $myads);
	$smarty->assign("file_name", "ptsu_details.tpl");
	$smarty->display("account.tpl");
	$db->close();
	exit();
	return 1;
}

$count_ads = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptsu_offers WHERE status='Active' and credits!='0' and " . $add_sql . " (country LIKE '%" . $user_info['country'] . "%')");

if ($count_ads != 0) {
	$res = $db->query("SELECT * FROM ptsu_offers WHERE user_id!=" . $user_info['id'] . " and status='Active' and credits!='0' and " . $add_sql . " (country LIKE '%" . $user_info['country'] . "%') ORDER BY value DESC");

	while ($list = $db->fetch_array($res)) {
		$adviewed = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptsu_requests WHERE ptsu_id=" . $list['id'] . " and user_id=" . $user_info['id']);

		if ($adviewed == 0) {
			$list['web'] = str_replace("http://", "", $list['url']);
			$list['web'] = str_replace("https://", "", $list['web']);
			$list['web'] = explode("/", $list['web']);
			$list['web'] = $list['web'][0];
			$ad[] = $list;
		}
	}

	$smarty->assign("advertisement", $ad);
}

$smarty->assign("myads", $count_ads);
$smarty->assign("file_name", "ptsu.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>