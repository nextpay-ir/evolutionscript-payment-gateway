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

$template = $cache->get("template");

if ($template == null) {
	$default_tpl = $db->fetchOne("SELECT COUNT(*) AS NUM FROM templates WHERE default_tpl=1 AND version='" . $software['version'] . "'");

	if ($default_tpl != 0) {
		$template = $db->fetchRow("SELECT * FROM templates WHERE default_tpl=1 AND version='" . $software['version'] . "'");
	}
	else {
		$template['name'] = "Modern Blue";
		$template['folder'] = "ModernBlue";
	}

	$cache->set("template", $template, 604800);
}

$templateFolder = $template['folder'];
$smarty = new Smarty();
$smarty->setTemplateDir(ROOTPATH . "templates/" . $templateFolder . "/");
$smarty->setCompileDir(ROOTPATH . "templates_c/" . $templateFolder . "/");
$smarty->config_dir = "configs/";
$smarty->setCacheDir(CACHE);
$smarty->assign("template_name", $templateFolder);
$smarty->assign("memberonly_support", MEMBERS_SUPPORT);
$smarty->assign("settings", $settings);

if ($settings['site_stats'] == "yes") {
	$smarty->assign("statistics", $statistics);
}


if ($settings['copyright'] != 0) {
	$smarty->assign("copyright", "<div style=\"margin-top:3px\">Powered by <a href=\"http://www.evolutionscript.com\">EvolutionScript</a> Version " . $software['version'] . "</div>");
}


if ($settings['allowchangelanguage'] == "yes") {
	$smarty->assign("language_list", $langlist);
	$smarty->assign("current_lang", $current_lang);
}

$smarty->assign("lang", $lang);

if ($_SESSION['logged']) {
	$unread_messages = $db->fetchOne("SELECT COUNT(*) AS NUM FROM messages WHERE user_to=" . $user_info['id'] . " AND user_read='no'");
	$smarty->assign("logged", $_SESSION['logged']);
	$smarty->assign("user_info", $user_info);
	$smarty->assign("unread_messages", $unread_messages);
}

$gatewaylist = $cache->get("gatewaylist");

if ($gatewaylist == null) {
	$q = $db->query("SELECT id FROM gateways WHERE status='Active'");

	while ($r = $db->fetch_array($q)) {
		$gatewaylist[] = $r['id'];
	}

	$cache->set("gatewaylist", $gatewaylist, 604800);
}

$smarty->assign("gatewaylist", $gatewaylist);
?>