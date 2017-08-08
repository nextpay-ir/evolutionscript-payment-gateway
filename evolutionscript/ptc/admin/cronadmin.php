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

$upd = $db->query("UPDATE cron_settings SET value='" . $the_day . "' WHERE field='last_cron'");
$db->query("UPDATE statistics SET value='0' WHERE field='members_today'");
$db->delete("ip_ptc");
$set = array("type" => 1, "upgrade_ends" => 0);
$todayis = time();
$upd = $db->update("members", $set, "upgrade_ends!=0 AND upgrade_ends<" . $todayis);
$upd = $db->query("UPDATE ads SET clicks_today=0");
$myclicks = array("mc1", "mc2", "mc3", "mc4", "mc5", "mc6", "mc7");
$refclicks = array("r1", "r2", "r3", "r4", "r5", "r6", "r7");
$rentedrefclicks = array("rr1", "rr2", "rr3", "rr4", "rr5", "rr6", "rr7");
$autopayclicks = array("ap1", "ap2", "ap3", "ap4", "ap5", "ap6", "ap7");
?>