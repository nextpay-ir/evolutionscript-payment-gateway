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
function NulledBy_MTimer($userid, $chart_num, $days_diff, $referrer = null) {
	global $db;

	$today_date = date("Y-m-d");
	$myclicks = array("mc1", "mc2", "mc3", "mc4", "mc5", "mc6", "mc7");
	$refclicks = array("r1", "r2", "r3", "r4", "r5", "r6", "r7");
	$rentedrefclicks = array("rr1", "rr2", "rr3", "rr4", "rr5", "rr6", "rr7");
	$autopayclicks = array("ap1", "ap2", "ap3", "ap4", "ap5", "ap6", "ap7");
	$todayclick = $db;

	if (7 <= $days_diff) {
		$next_value = 0;
		$i = 0;

		while ($i <= 6) {
			$reset_mc[$myclicks[$i]] = 0;
			$reset_mc[$refclicks[$i]] = 0;
			$reset_mc[$rentedrefclicks[$i]] = 0;
			$reset_mc[$autopayclicks[$i]] = 0;
			++$i;
		}
	}
	else {
		$next_value = $todayclick + $days_diff;
		$i = $todayclick + 1;

		while ($i <= $next_value) {
			if (6 < $i) {
				$reset_mc[$myclicks[$i - 7]] = 0;
				$reset_mc[$refclicks[$i - 7]] = 0;
				$reset_mc[$rentedrefclicks[$i - 7]] = 0;
				$reset_mc[$autopayclicks[$i - 7]] = 0;
			}
			else {
				$reset_mc[$myclicks[$i]] = 0;
				$reset_mc[$refclicks[$i]] = 0;
				$reset_mc[$rentedrefclicks[$i]] = 0;
				$reset_mc[$autopayclicks[$i]] = 0;
			}

			++$i;
		}


		if (6 < $next_value) {
			$next_value = $todayclick + $days_diff - 7;
		}
	}

	$data = array("last_cron" => $today_date, "advisto" => "", "loginads_view" => 0, "rented_autopay" => 0, "adminad" => 0, "chart_num" => $next_value);
	$data = array_merge($data, $reset_mc);
	$db->update("members", $data, "id=" . $userid);

	if ($referrer == "ref") {
		$user_info = $db->fetchRow("SELECT id, type, rented_referrals FROM members WHERE id=" . $userid);
		$cron_list = scandir(INCLUDES . "crons/");
		foreach ($cron_list as $c) {

			if (!in_array($c, array(".", ".."))) {
				if ($c != "ptc.php" && is_file(INCLUDES . "crons/" . $c)) {
					include INCLUDES . "crons/" . $c;
					continue;
				}

				continue;
			}
		}
	}

	return true;
}

function getToken($token_name) {
	$token = md5(uniqid(rand(), true));
	$token_time = TIMENOW;
	$_SESSION['token'][$token_name] = $token;
	$_SESSION['token'][$token_name] = array("token" => $token, "time" => $token_time);
	return $token;
}

function verifyToken($token_name, $token) {
	if (!isset($_SESSION['token'][$token_name])) {
		return false;
	}


	if ($_SESSION['token'][$token_name]['token'] != $token) {
		return false;
	}

	$token_age = TIMENOW - $_SESSION['token'][$token_name]['time'];

	if (600 <= $token_age) {
		return false;
	}

	return true;
}

function dateDiff($date1, $date2) {
	$datetime1 = new DateTime($date1);
	$datetime2 = new DateTime($date2);
	$days = round(($datetime2->format("U") - $datetime1->format("U")) / 86400);
	return $days;
}

function encrypt($ascii) {
	$hex = "";

	for ($i = 0;$i < strlen($ascii);$i++) {
		$byte = strtoupper(dechex(ord($ascii[$i])));
		$byte = str_repeat("0", 2 - strlen($byte)) . $byte;
		$hex .= $byte;
	}

	return $hex;
}

function decrypt($hex) {
	$ascii = "";
	$hex = str_replace(" ", "", $hex);

	for ($i = 0;$i < strlen($hex);$i = $i + 2) {
		$ascii .= chr(hexdec(substr($hex, $i, 2)));
	}

	return $ascii;
}

function verifyajax() {
	if ($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest") {
		header("location: " . $_SERVER['HTTP_REFERER']);
		exit();
	}

}

function serveranswer($status, $answer) {
	global $db;

	$stored = array("status" => $status, "msg" => $answer);
	echo json_encode($stored);
	$db->close();
	exit();
}

function validateEmail($email) {
	if (!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) {
		return false;
	}

	return true;
}

function calculatecom($userid, $price, $type = null) {
	global $db;

	if ($userid == 0) {
		return 0;
	}

	$ref = $db->fetchRow("SELECT type FROM members WHERE id=" . $userid);

	if ($type == "upgrade") {
		$ref_com = $db->fetchOne("SELECT ref_upgrade FROM membership WHERE id=" . $ref['type']);
	}
	else {
		if ($type == "purchase") {
			$ref_com = $db->fetchOne("SELECT ref_purchase FROM membership WHERE id=" . $ref['type']);
			$ref_com = $price * $ref_com / 100;
		}
		else {
			$ref_com = 0;
		}
	}

	return $ref_com;
}

function jsescape($os) {
	$ns = "";
	$t = "";
	$chr = "";
	$cc = "";
	$tn = "";
	$i = 0;

	while ($i < 256) {
		$tn = dechex($i);
		$tn = strval($tn);

		if (strlen($tn) < 2) {
			$tn = "0" . $tn;
		}

		$cc .= $os;
		$chr .= urldecode("%" . $tn);
		++$i;
	}

	$cc = strtoupper($cc);
	$q = 0;

	while ($q < strlen($os)) {
		$t = substr($os, $q, 1);
		$i = 0;

		while ($i < strlen($chr)) {
			if ($t == substr($chr, $i, 1)) {
				$t = str_replace($t, substr($chr, $i, 1), "%" . substr($cc, $i * 2, 2));
				$i = strlen($chr);
			}

			++$i;
		}

		$ns .= $tn;
		++$q;
	}

	return $ns;
}

function vdata($data) {
	$lk = $lkrs;
	$lk = str_replace("\n", "", $lk);
	$lc = substr($lk, 0, strlen($lk) - 64);
	$pk = substr($lk, strlen($lk) - 32);
	$pk = strrev($pk);
	$msh = substr($lk, strlen($lk) - 64, 32);

	if ($msh == md5($lc . $pk)) {
		$lc = strrev($lc);
		$msh = substr($lc, 0, 32);
		$lc = substr($lc, 32);
		$lc = base64_decode($lc);
		$lkrs = unserialize($lc);
		return $lkrs;
	}

}

function jsanswer($id, $status, $answer, $fn = null) {
	echo "<script language=\"javascript\" type=\"text/javascript\">window.top.window.stopform('" . $id . "', " . $status . ", '" . $answer . "', '" . $fn . "')</script>";
	exit();
}

function cleanfrm($var) {
	$res = htmlspecialchars(trim($var));
	return $res;
}

function logout() {
	setcookie("user_id", "", time() - 86400);
	setcookie("password", "", time() - 86400);
	setcookie("cookie_id", "", time() - 86400);
	$_SESSION = array();
	session_destroy();
}

function getnumbers() {
	unset($_SESSION['vnumbers']);
	unset($_SESSION['the_number']);
	$number = array();
	$numeros = 10;
	$count = count($number);

	while ($count < $numeros) {
		$random = rand(0, 9);

		if (in_array($random, $number)) {
			continue;
		}

		$number[] = $random;
		++$count;
	}

	$_SESSION['vnumbers'] = $number;
	$_SESSION['the_number'] = rand(0, 5);
}

function ip2country($ipvisitor) {
	global $db;

	$query = "SELECT c.country FROM ip2nationCountries c, ip2nation i WHERE i.ip < INET_ATON('" . $ipvisitor . "') AND c.code = i.country ORDER BY i.ip DESC LIMIT 0,1";
	$countryName = $db->fetchRow($query);
	return $countryName['country'];
}

function verifytrack($trackid, $ip) {
	global $db;

	$countrack = $db->fetchOne("SELECT COUNT(*) AS NUM FROM linktracker_log WHERE ip='" . $ip . "'");

	if ($countrack == 0) {
		$data = array("track_id" => $trackid, "ip" => $ip);
		$db->insert("linktracker_log", $data);
		$upd = $db->query("UPDATE linktracker SET uniquehits=uniquehits+1, hits=hits+1 WHERE id=" . $trackid);
		return null;
	}

	$upd = $db->query("UPDATE linktracker SET hits=hits+1 WHERE id=" . $trackid);
}

function delete_dir($path) {
	$files = glob($path . "/*");
	foreach ($files as $file) {

		if (is_dir($file) && !is_link($file)) {
			delete_dir($file);
			continue;
		}

		unlink($p);
	}

	rmdir($path);
}

function showrotatingbanners() {
	global $db;
	global $settings;

	if ($settings['bannerads_available'] == "yes") {
		$res = $db->fetchOne("SELECT COUNT(*) AS NUM FROM banner_ads WHERE status='Active' AND credits>0");

		if ($res != 0) {
			$banner = $db->fetchRow("SELECT id, img, credits, views from banner_ads WHERE status='Active' AND credits>0 ORDER BY RAND() LIMIT 0,1");
			$set = array("credits" => $banner['credits'] - 1, "views" => $banner['views'] + 1);
			$upd = $db->update("banner_ads", $set, "id = " . $banner['id']);
			echo "<a href=\"bannerclick.php?id=" . $banner['id'] . "\" target=\"_blank\"><img src=\"" . $banner['img'] . "\" border=\"0\" width=\"468\" height=\"60\"></a>";
			return null;
		}

		echo "<a href=\"index.php?view=advertise\"><img src=\"images/ad_468x60.jpg\" border=\"0\"></a>";
	}

}

function getfeaturedad() {
	global $db;
	global $settings;
	global $lang;

	if ($settings['fads_available'] == "yes") {
		$res = $db->fetchOne("SELECT COUNT(*) AS NUM FROM featured_ads WHERE status='Active' AND credits>0");

		if ($res != 0) {
			$query = $db->query("SELECT id, title, url, ad, credits, views from featured_ads WHERE status='Active' AND credits>0 ORDER BY RAND() LIMIT 0," . $settings['show_fads']);

			while ($fads = $db->fetch_array($query)) {
				$fads['title'] = stripslashes($fads['title']);
				$fads['ad'] = stripslashes($fads['ad']);
				$fads['url'] = (strlen($fads['url']) < 40 ? $fads['url'] : substr($fads['url'], 0, 40) . "...");
				$set = array("credits" => $fads['credits'] - 1, "views" => $fads['views'] + 1);
				$upd = $db->update("featured_ads", $set, "id = " . $fads['id']);
				echo "<li><a href=\"fadclick.php?id=" . $fads['id'] . "\" target=\"_blank\" title=\"Click to visit sponsored site\">
							<strong>" . $fads['title'] . "</strong></a><br />" . $fads['ad'] . "<br><span>" . $fads['url'] . "</span></li>";
			}
		}
		else {
			echo "<li><a href=\"index.php?view=advertise\" class=\"tooltip\" title=\"" . $lang['txt']['youradhere'] . "\">" . $lang['txt']['advertisehere'] . "</a></li>";
		}
	}

}

function getfeaturedlink() {
	global $db;
	global $settings;
	global $lang;

	$today = time();
	$res = $db->fetchOne("SELECT COUNT(*) AS NUM FROM featured_link WHERE status='Active' AND expires>" . $today);

	if ($res != 0) {
		$query = $db->query("SELECT id, title, expires, views, url from featured_link WHERE status='Active' AND expires>" . $today . " ORDER BY RAND() LIMIT 0," . $settings['show_flinks']);

		while ($flink = $db->fetch_array($query)) {
			$flink['title'] = stripslashes($flink['title']);
			$flink['url'] = (strlen($flink['url']) < 40 ? $flink['url'] : substr($flink['url'], 0, 40) . "...");
			$set = array("views" => $flink['views'] + 1);
			$upd = $db->update("featured_link", $set, "id = " . $flink['id']);
			echo "<li><a href=\"flinkclick.php?id=" . $flink['id'] . "\" target=\"_blank\" title=\"Click to visit sponsored site \" class=\"tooltip\">
                        " . $flink['title'] . "</a><br><span>" . $flink['url'] . "</span></li>";
		}
	}
	else {
		echo "<li><a href=\"index.php?view=advertise\" class=\"tooltip\" title=\"" . $lang['txt']['youradhere'] . "\">" . $lang['txt']['advertisehere'] . "</a></li>";
	}

}

function showloginads() {
	global $db;
	global $settings;

	if ($settings['loginads_available'] == "yes") {
		$today = time();
		$res = $db->fetchOne("SELECT COUNT(*) AS NUM FROM login_ads WHERE status='Active' AND expires>=" . $today);

		if ($res != 0) {
			$banner = "";
			$set = array("views" => $banner['views'] + 1);
			$db->update("login_ads", $set, "id = " . $banner['id']);
			$upd = $db->fetchRow("SELECT id, image, views FROM login_ads WHERE status='Active' AND expires>=" . $today . " ORDER BY RAND() LIMIT 0,1");
			echo "<a href=\"loginadclick.php?id=" . $banner['id'] . "\" target=\"_blank\"><img src=\"" . $banner['image'] . "\" border=\"0\" width=\"468\" height=\"60\"></a>";
			return null;
		}

		echo "<a href=\"index.php?view=advertise\"><img src=\"images/ad_468x60.jpg\" border=\"0\"></a>";
	}

}


if (!defined("EvolutionScript")) {
	exit("Hacking attempt...");
}

?>