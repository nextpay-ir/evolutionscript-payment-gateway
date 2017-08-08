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


if ($_SESSION['logged'] != "yes" && isset($_SERVER['HTTP_REFERER'])) {
	$referringurl = $_SERVER['HTTP_REFERER'];
	$urlParts = parse_url($referringurl);
	$referringurl = preg_replace("/^www\./", "", $urlParts['host']);
	$urlParts = parse_url($settings['site_url']);
	$main_domain = preg_replace("/^www\./", "", $urlParts['host']);

	if ($main_domain != $referringurl) {
		$_SESSION['comes_from'] = $referringurl;
	}
	else {
		$_SESSION['comes_from'] = "-";
	}


	if ($settings['topdomains'] == "yes") {
		if (!empty($referringurl) && $main_domain != $referringurl) {
			$verifydomain = $db->fetchOne("SELECT COUNT(*) AS NUM FROM topdomains WHERE domain='" . $referringurl . "'");

			if ($verifydomain == 0) {
				$data = array("domain" => $referringurl, "hits" => 1);
				$db->insert("topdomains", $data);
			}
			else {
				$upd = $db->query("UPDATE topdomains SET hits=hits+1 WHERE domain='" . $referringurl . "'");
			}
		}
	}
}


if (!empty($input->g['ref'])) {
	$_SESSION['ref'] = $input->g['ref'];
}


if (!empty($_GET['track']) && empty($_SESSION['track'])) {
	$track = $db->real_escape_string($_GET['track']);
	$res = $db->fetchOne("SELECT COUNT(*) AS NUM FROM linktracker WHERE name='" . $track . "'");

	if ($res != 0) {
		$_SESSION['track'] = $track;
		$trackid = $db->fetchOne("SELECT id FROM linktracker WHERE name='" . $track . "'");
		$checktrack = verifytrack($trackid, $_SERVER['REMOTE_ADDR']);
	}
}
else {
	if (!empty($_SESSION['track']) && empty($_GET['track'])) {
		$track = $db->real_escape_string($_SESSION['track']);
		$res = $db->fetchOne("SELECT COUNT(*) AS NUM FROM linktracker WHERE name='" . $track . "'");

		if ($res != 0) {
			$trackid = $db->fetchOne("SELECT id FROM linktracker WHERE name='" . $track . "'");
			$checktrack = verifytrack($trackid, $_SERVER['REMOTE_ADDR']);
		}
		else {
			unset($_SESSION['track']);
		}
	}
}

require SMARTYLOADER;
$smarty->display("home.tpl");
$db->close();
exit();
?>