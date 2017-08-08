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

define("EvolutionScript", 1);
define("ROOTPATH", dirname(__FILE__) . "/");
define("INCLUDES", ROOTPATH . "includes/");
require_once INCLUDES . "global.php";
$query = $db->query("SELECT * FROM cron_settings");

while ($result = $db->fetch_array($query)) {
	$cron[$result['field']] = $result['value'];
}


if ($cron['suspend_inactive'] == "yes") {
	$upd = $db->query("UPDATE members SET status='Suspended' WHERE status='Inactive'");
}


if ($cron['delete_ptc'] == "yes") {
	$upd = $db->delete("ads", "status='Inactive' OR status='Expired'");
}


if ($cron['delete_fads'] == "yes") {
	$upd = $db->delete("featured_ads", "status='Inactive' OR status='Expired'");
}


if ($cron['delete_flinks'] == "yes") {
	$upd = $db->delete("featured_link", "status='Inactive' OR status='Expired'");
}


if ($cron['delete_loginads'] == "yes") {
	$upd = $db->delete("login_ads", "status='Inactive' OR status='Expired'");
}


if ($cron['delete_bannerads'] == "yes") {
	$upd = $db->delete("banner_ads", "status='Inactive' OR status='Expired'");
}

$chkad = $db->query("SELECT id FROM ads WHERE click_pack=0");

while ($list = $db->fetch_array($chkad)) {
	$upd = $db->query("UPDATE ads SET status='Inactive' WHERE id=" . $list['id']);
}

$chkad = $db->query("SELECT id FROM banner_ads WHERE credits=0");

while ($list = $db->fetch_array($chkad)) {
	$upd = $db->query("UPDATE banner_ads SET status='Inactive' WHERE id=" . $list['id']);
}


if ($list = $chkad = "") {
	$upd = $db->query("UPDATE featured_ads SET status='Inactive' WHERE id=" . $list['id']);
}

$today = time();
$chkad = $db->query("SELECT id FROM featured_link WHERE expires<" . $today);

while ($list = $db->fetch_array($chkad)) {
	$upd = $db->query("UPDATE featured_link SET status='Inactive' WHERE id=" . $list['id']);
}

$db->query("SELECT id FROM login_ads WHERE expires<" . $today);
$chkad = $db->query("SELECT id FROM featured_ads WHERE credits=0");
$db->fetch_array($chkad);

while ($list = $db->fetch_array($chkad)) {
	$upd = $db->query("UPDATE login_ads SET status='Inactive' WHERE id=" . $list['id']);
}

$ptsu_autoapprovedays = $db->fetchOne("SELECT value FROM settings WHERE field='ptsu_autoapprovedays'");
$days2approve = TIMENOW - 60 * 60 * 24 * $ptsu_autoapprovedays;
$ptsuq = $db->query("SELECT id, user_id, value, ptsu_id, date FROM ptsu_requests WHERE date<=" . $days2approve . " AND status='Pending'");

while ($ptsur = $db->fetch_array($ptsuq)) {
	$upd = $db->query("UPDATE ptsu_requests SET status='Completed' WHERE id=" . $ptsur['id']);
	$upd = $db->query("UPDATE members SET money=money+" . $ptsur['value'] . " WHERE id=" . $ptsur['user_id']);
	$upd = $db->query("UPDATE ptsu_offers SET approved=approved+1, pending=pending-1 WHERE id=" . $ptsur['ptsu_id']);
	$membershiptype = $db->fetchOne("SELECT type FROM members WHERE id=" . $ptsur['user_id']);
	$membership = $db->fetchRow("SELECT point_enable, point_ptsu FROM membership WHERE id=" . $membershiptype);

	if ($membership['point_enable'] == 1) {
		addpoints($ptsur['user_id'], $membership['point_ptsu']);
	}
}

$db->query("UPDATE ptsu_offers SET pending=0 WHERE pending<0");
?>