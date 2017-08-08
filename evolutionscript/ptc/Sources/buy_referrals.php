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

if ($settings['buy_referrals'] != "yes") {
	header("location: index.php?view=account");
	exit();
}

$referralsleft = referralsleft($user_info['id']);
$limitref = $db->fetchOne("SELECT directref_limit FROM membership WHERE id=" . $user_info['type']);

if ($limitref == -1) {
	$addsql = "WHERE refs<=" . $referralsleft;
}
else {
	$myreferralsleft = $limitref - $user_info['referrals'];

	if ($myreferralsleft < $referralsleft) {
		$addsql = "WHERE refs<=" . $myreferralsleft;
	}
	else {
		$addsql = "WHERE refs<=" . $referralsleft;
	}
}

$countref = $db->fetchOne("SELECT COUNT(*) AS NUM FROM referral_price " . $addsql . " ORDER BY refs ASC");
$smarty->assign("countref", $countref);

if (0 < $countref) {
	$res = $db->query("SELECT * FROM referral_price " . $addsql . " ORDER BY refs ASC");

	while ($list = $db->fetch_array($res)) {
		$buyrefs[] = $list;
	}

	$smarty->assign("buy_refs", $buyrefs);
}

$smarty->assign("file_name", "buy_referrals.tpl");
$smarty->display("account.tpl");
?>