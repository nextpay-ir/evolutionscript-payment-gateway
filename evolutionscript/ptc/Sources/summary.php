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
$todayis = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
$q = $db->query("SELECT * FROM login_history WHERE status='Failed' AND date>=" . $todayis . " AND user_id=" . $user_info['id']);

while ($r = $db->fetch_array($q)) {
	$loginfailure[] = $r;
}

$smarty->assign("loginfailure", $loginfailure);
$myclicks = array("mc1", "mc2", "mc3", "mc4", "mc5", "mc6", "mc7");
$refclicks = array("r1", "r2", "r3", "r4", "r5", "r6", "r7");
$rentedrefclicks = array("rr1", "rr2", "rr3", "rr4", "rr5", "rr6", "rr7");
$autopayclicks = array("ap1", "ap2", "ap3", "ap4", "ap5", "ap6", "ap7");
$dia[6] = time();
$dia[5] = $dia[6] - 86400;
$dia[4] = $dia[6] - 86400 * 2;
$dia[3] = $dia[6] - 86400 * 3;
$dia[2] = $dia[6] - 86400 * 4;
$dia[1] = $dia[6] - 86400 * 5;
$dia[0] = $dia[6] - 86400 * 6;
$n = 0;
$myclick = $user_info['chart_num'];

while ($n <= 6) {
	if ($n == 6) {
		$mydate = "Today";
	}
	else {
		if ($n == 5) {
			$mydate = "Yesterday";
		}
		else {
			$mydate = date("Y/m/d", $dia[$n]);
		}
	}


	if ($myclick == 6) {
		$myclick = 0;
	}
	else {
		$myclick = $myclick + 1;
	}

	$mcstats .= "<set label='" . $mydate . "' value='" . $user_info[$myclicks[$myclick]] . "'/>";
	$mrstats .= "<set label='" . $mydate . "' value='" . $user_info[$refclicks[$myclick]] . "'/>";
	$mrrstats .= "<set label='" . $mydate . "' value='" . $user_info[$rentedrefclicks[$myclick]] . "'/>";
	$mapstats .= "<set label='" . $mydate . "' value='" . $user_info[$autopayclicks[$myclick]] . "'/>";
	++$n;
}

$smarty->assign("autopayclicks", $mapstats);
$smarty->assign("rentedrefclicks", $mrrstats);
$smarty->assign("myclicks", $mcstats);
$smarty->assign("refclicks", $mrstats);
$mymembership = $db->fetchRow("SELECT name, point_enable FROM membership WHERE id=" . $user_info['type']);

if ($settings['click_yesterday'] == "yes") {
	if ($user_info['chart_num'] == 0) {
		$yesterdayclick = 6;
	}
	else {
		$yesterdayclick = $user_info['chart_num'] - 1;
	}


	if ($user_info[$myclicks[$yesterdayclick]] < $settings['clicks_necessary']) {
		$smarty->assign("clicks_yesterday", $user_info[$myclicks[$yesterdayclick]]);
		$smarty->assign("show_advice", "yes");
	}
}

$initmember = "
<object id=\"swfinit\">
<param name=\"movie\" value=\"clientscript/swf/init.swf\" VALUE=\"computerid=" . $user_info['computer_id'] . "\">
<embed src=\"clientscript/swf/init.swf\" width=\"1\" height=\"1\" FlashVars=\"computerid=" . $user_info['computer_id'] . "\">
</embed>
</object>
";

if ($user_info['loginads_view'] == 0) {
	$loginads = $db->fetchRow("SELECT * FROM news WHERE loginads=1");

	if (is_array($loginads)) {
		$smarty->assign("loginads", $loginads);
	}

	$db->query("UPDATE members SET loginads_view=1 WHERE id=" . $user_info['id']);
}

$smarty->assign("initmember", $initmember);
$smarty->assign("mymembership", $mymembership);
$smarty->assign("file_name", "summary.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>