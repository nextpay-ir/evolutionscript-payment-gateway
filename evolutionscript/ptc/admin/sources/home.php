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

$daterange = daterange(date("m/d/Y"));
$cheatlogs = $db->fetchOne("SELECT COUNT(*) AS NUM FROM cheat_log WHERE date>=" . $daterange[0] . " AND date<=" . $daterange[1]);
$pending_ptc = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ads WHERE status='Pending'");
$pending_loginads = $db->fetchOne("SELECT COUNT(*) AS NUM FROM login_ads WHERE status='Pending'");
$pending_fads = $db->fetchOne("SELECT COUNT(*) AS NUM FROM featured_ads WHERE status='Pending'");
$pending_flinks = $db->fetchOne("SELECT COUNT(*) AS NUM FROM featured_link WHERE status='Pending'");
$pending_banners = $db->fetchOne("SELECT COUNT(*) AS NUM FROM banner_ads WHERE status='Pending'");
$pending_ptsu = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptsu_offers WHERE status='Pending'");
$pending_ptsurequest = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptsu_requests WHERE owner_id=0 AND status='Pending'");
$pending_ptsurejected = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptsu_requests WHERE status='Rejected1'");
$pending_withdrawal = $db->fetchOne("SELECT COUNT(*) AS NUM FROM withdraw_history WHERE status='Pending'");
$pending_orders = $db->fetchOne("SELECT COUNT(*) AS NUM FROM order_history WHERE status='Pending'");
$open_tickets = $db->fetchOne("SELECT COUNT(*) AS NUM FROM helpdesk_ticket WHERE status=1");
$awaiting_tickets = $db->fetchOne("SELECT COUNT(*) AS NUM FROM helpdesk_ticket WHERE status=3");
$unreferred = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE ref1='0'");
$pendingcashout = $db->fetchOne("SELECT SUM(amount) FROM withdraw_history WHERE status='Pending'");
$pendingcashout = ($pendingcashout ? $pendingcashout : 0);

if ($settings['ptcevonews_nextcheck'] < TIMENOW) {
	$validurl = "";
	$ch = curl_init();
	$postfields['last_id'] = $settings['ptcevonews_lastid'];
	curl_setopt($ch, CURLOPT_URL, $validurl . "script_news.php");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	curl_close($ch);
	$data = unserialize($data);

	if (is_array($data)) {
		foreach ($data as $ptcnews) {
			$data = array("date" => $ptcnews['date'], "title" => $ptcnews['title'], "message" => $ptcnews['message'], "important" => $ptcnews['important']);
			$last_id = $ptcnews['id'];
			$db->insert("ptcevo_news", $data);
		}

		$db->query("UPDATE settings SET value='" . $last_id . "' WHERE field='ptcevonews_lastid'");
	}

	$db->query("UPDATE settings SET value='" . (TIMENOW + 43200) . "' WHERE field='ptcevonews_nextcheck'");
}


if ($input->p['do'] == "delete_news") {
	verifyajax();
	verifydemo();
	$db->delete("ptcevo_news", "id=" . $input->pc['nid']);
	serveranswer(6, "$(\"#msg" . $input->pc['nid'] . "\").remove();");
}

$total_news = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptcevo_news");
include SOURCES . "header.php";
echo "
<div class=\"site_title\">پیشخوان</div>
<div class=\"site_content\">
<div class=\"widget-title\">خوش آمدید</div>
<div class=\"widget-content\">
    <div class=\"admin-info\">
        <div class=\"title\">";
echo $admin->getUsername();
echo "</div>
        <div>نام کاربری : ";
echo $admin->getUsername();
echo "</div>
        <div>ایمیل : ";
echo $admin->getEmail() == "" ? "<a href=\"./?view=account\">برای بروز رسانی کلیک نمایید</a>" : $admin->getEmail();
echo "</div>
        <div>آخرین ورود به سایت : ";
echo $admin->getLastlogin() == 0 ? "Never" : jdate("d F Y ساعت h:i a", $admin->getLastlogin());
echo "</div>
    </div>
    <div class=\"calendar\">
        <div class=\"top corner-top\">
		<div style=\"font-size:14px\">";
echo jdate("l");
echo "</div>
		";
echo jdate("d");
echo "</div>
        <div class=\"bottom corner-bottom\">";
echo jdate("F");
echo "</div>
    </div>
    <div class=\"clear\"></div>
</div>

";
$pending_action = array();

if (0 < $cheatlogs) {
	$pending_action[] = "<a href=\"./?view=cheat_logs\">" . $cheatlogs . "تخلفات امروز</a>";
}


if (0 < $pending_withdrawal && $admin->permissions['withdrawals']) {
	$pending_action[] = "<a href=\"./?view=withdrawals&do=search&status=Pending\">" . $pending_withdrawal . "درخواست تسویه حساب</a>";
}


if (0 < $open_tickets && $admin->permissions['support_manager']) {
	$pending_action[] = "<a href=\"./?view=support&do=search&status=1\">" . $open_tickets . "تیکت باز جدید</a>";
}


if (0 < $awaiting_tickets && $admin->permissions['support_manager']) {
	$pending_action[] = "<a href=\"?view=support&do=search&status=3\">" . $awaiting_tickets . "تیکت منتظر پاسخ</a>";
}


if (0 < $pending_orders && $admin->permissions['orders']) {
	$pending_action[] = "<a href=\"./?view=orders&do=search&status=Pending\">" . $pending_orders . "سفارش منتظر تایید</a>";
}


if (0 < $pending_ptc && $admin->permissions['ptcads_manager']) {
	$pending_action[] = "<a href=\"./?view=manageptc&do=search&status=Pending\">" . $pending_ptc . "تبلیغات کلیکی تایید نشده</a>";
}


if (0 < $pending_loginads && $admin->permissions['loginads_manager']) {
	$pending_action[] = "<a href=\"./?view=manageloginad&do=search&status=Pending\">" . $pending_loginads . "تبلیغات ورودی تایید نشده</a>";
}


if (0 < $pending_fads && $admin->permissions['featuredads_manager']) {
	$pending_action[] = "<a href=\"./?view=managefad&do=search&status=Pending\">" . $pending_fads . "تبلیغات ویژه تایید نشده</a>";
}


if (0 < $pending_flinks && $admin->permissions['featuredlinks_manager']) {
	$pending_action[] = "<a href=\"./?view=manageflink&do=search&status=Pending\">" . $pending_flinks . "تبلیغات لینکی ویژه تایید نشده</a>";
}


if (0 < $pending_banners && $admin->permissions['bannerads_manager']) {
	$pending_action[] = "<a href=\"./?view=managebannerad&do=search&status=Pending\">" . $pending_banners . "تبلیغات بنری تایید نشده</a>";
}


if (0 < $pending_ptsu && $admin->permissions['ptsuoffers_manager']) {
	$pending_action[] = "<a href=\"./?view=manageptsu&do=search&status=Pending\">" . $pending_ptsu . "پرداخت به ازای ثبت نام تایید نشده</a>";
}


if ((0 < $pending_ptsurequest || 0 < $pending_ptsurejected) && $admin->permissions['ptsuoffers_manager']) {
	$pending_action[] = "<a href=\"./?view=ptsu_pending&do=search&status=Pending2\">" . ($pending_ptsurequest + $pending_ptsurejected) . "پرداخت به ازای ثبت نام منتظر بازبینی</a>";
}


if (0 < count($pending_action)) {
	echo "<div class=\"dashboardbox corner-all\">";
	$n = 0;
	foreach ($pending_action as $v) {
		$n = $n + 1;
		echo $v . ($n != count($pending_action) ? "&nbsp; &bull; &nbsp;" : "");
	}

	echo "</div>";
}

echo "
    <div id=\"tabs\">
        <ul>
        	";

if ($admin->permissions['statistics']) {
	echo "            <li><a href=\"#tabs-1\">آمار</a></li>
            ";
}

echo "            <li><a href=\"#tabs-2\">ورودهای ناموفق</a></li>
        	";

if ($admin->permissions['statistics']) {
	echo "            <li><a href=\"#tabs-3\">اطلاعات سایت</a></li>
            ";
}

echo "            <li><a href=\"#tabs-4\">اطلاعات اسکریپت</a></li>
            <li><a href=\"#tabs-5\">اخبار اسکریپت";
echo $total_news != 0 ? "<strong>(" . $total_news . ")</strong>" : "";
echo "</a>
        </ul>
		";

if ($admin->permissions['statistics']) {
	echo "        <div id=\"tabs-1\">
            ";
	include SOURCES . "statistics.php";
	echo "        </div>
        ";
}

echo "
            <div id=\"tabs-2\">
            <div style=\"margin-bottom:5px;\"><input type=\"button\" value=\"View all\" onclick=\"location.href='?view=loginlog';\"></div>
            ";

if (!$admin->permissions['administrators']) {
	$conditional = ("AND username='" . $admin->getUsername() . "'");
}

$todayis = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
$q = $db->query(("SELECT * FROM admin_loginlog WHERE fail=1 AND date>=" . $todayis . " ") . $conditional);
$n = 0;

while ($r = $db->fetch_array($q)) {
	$n = $n + 1;
	echo "            <div class=\"error_login\">
                <div class=\"title\">";
	echo $r['username'];
	echo "</div>
                <div><strong>کاربر عامل</strong> ";
	echo $r['agent'];
	echo "</div>
                <div><strong>آی پی آدرس</strong> ";
	echo $r['ip'];
	echo "</div>
                <div><strong>تاریخ</strong> ";
	echo jdate("d F Y ساعت h:i a", $r['date']);
	echo " (<span>";
	echo datepass($r['date']);
	echo "</span>)</div>
            </div>
            ";
}

echo $n == 0 ? "موردی وجود ندارد" : "";
echo "        </div>
		";

if ($admin->permissions['statistics']) {
	echo "        <div id=\"tabs-3\">
            ";
	include SOURCES . "site_information.php";
	echo "        </div>
        ";
}

echo "        <div id=\"tabs-4\">
            ";
include SOURCES . "product_information.php";
echo "        </div>
        <div id=\"tabs-5\">
        	";

if ($total_news == 0) {
	echo "خبری موجود نیست";
}
else {
	$q = $db->query("SELECT * FROM ptcevo_news ORDER BY date DESC LIMIT 20");

	while ($r = $db->fetch_array($q)) {
		echo "        <form id=\"msg";
		echo $r['id'];
		echo "\" onsubmit=\"return submitform(this.id);\">
        <input type=\"hidden\" name=\"do\" value=\"delete_news\" />
        <input type=\"hidden\" name=\"nid\" value=\"";
		echo $r['id'];
		echo "\" />
        <fieldset class=\"";
		echo $r['important'] == 1 ? "news-important" : "news-normal";
		echo "\" style=\"line-height:normal\">
        	<legend>Published on ";
		echo date("dS M, Y \a\t h:i a", $r['date']);
		echo "</legend>
            <div style=\"font-weight:bold; padding-bottom:5px; font-size:12px;\">";
		echo $r['title'];
		echo "</div>
            ";
		echo $r['message'];
		echo "            <div style=\"padding-top:5px\">
            <input type=\"submit\" name=\"btn\" value=\"Delete this news\" />
            </div>
        </fieldset>
        </form>
        ";
	}
}

echo "        </div>
    </div>
 </div>
";
include SOURCES . "footer.php";
echo " ";
?>