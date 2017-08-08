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

if ($settings['captcha_login'] == "yes") {
	if ($settings['captcha_type'] == "1") {
		$captcha_login_html .= "<div style=\"padding:5px 0px\"><a href=\"javascript:void(0);\" onclick=\"captchareload2();\"><img src=\"modules/captcha/captcha.php?r=login\" id=\"captchaimglogin\" border=\"0\" /></a></div>
";
		$captcha_login_html .= "<script>function captchareload2(){
";
		$captcha_login_html .= "$(\"#captchaimglogin\").attr('src','modules/captcha/captcha.php?r=login&x=34&y=75&z=119&?newtime=' + (new Date()).getTime());
";
		$captcha_login_html .= "}</script>";
		require_once "modules/captcha/getcaptcha_login.php";
	}
	else {
		if ($settings['captcha_type'] == "2") {
			require_once "modules/reCAPTCHA/recaptcha.php";
		}
		else {
			if ($settings['captcha_type'] == "3") {
				require_once "modules/solvemedia/solvemedia.php";
			}
		}
	}
}


if ($input->p['a'] == "submit") {
	verifyajax();

	if (empty($input->p['username']) || empty($input->p['password'])) {
		serveranswer(0, $lang['txt']['invalidlogindetails']);
	}


	if (verifyToken("login", $input->p['token']) !== true) {
		serveranswer(0, $lang['txt']['invalidtoken']);
	}

	$captcha = strtoupper($input->pc['captcha']);

	if ($settings['captcha_login'] == "yes") {
		if ($settings['captcha_type'] == "1") {
			$verify = validate_captcha_login($captcha, "");
		}
		else {
			if ($settings['captcha_type'] == "2") {
				$resp = validate_captcha($_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
			}
			else {
				if ($settings['captcha_type'] == "3") {
					$resp = validate_captcha();
				}
			}
		}
	}

	$username = $db->real_escape_string($input->pc['username']);
	$password = $input->p['password'];
	$country = ip2country($_SERVER['REMOTE_ADDR']);
	$ip_user = $_SERVER['REMOTE_ADDR'];
	$sect = explode(".", $ip_user);
	$reip = $sect[0] . "." . $sect[1] . "." . $sect[2] . "." . $sect[3];
	$reipa = $sect[0] . "." . $sect[1] . "." . $sect[2] . ".*";
	$reipb = $sect[0] . "." . $sect[1] . ".*.*";
	$reipc = $sect[0] . ".*.*.*";
	$verifyusername = $db->fetchOne("SELECT COUNT(*) AS NUM FROM blacklist WHERE type='username' AND criteria='" . $username . "'");
	$ipban1 = $db->fetchOne("SELECT COUNT(*) AS NUM FROM blacklist WHERE type='ip' AND criteria='" . $reip . "'");
	$ipban2 = $db->fetchOne("SELECT COUNT(*) AS NUM FROM blacklist WHERE type='ip' AND criteria='" . $reipa . "'");
	$ipban3 = $db->fetchOne("SELECT COUNT(*) AS NUM FROM blacklist WHERE type='ip' AND criteria='" . $reipb . "'");
	$ipban4 = $db->fetchOne("SELECT COUNT(*) AS NUM FROM blacklist WHERE type='ip' AND criteria='" . $reipc . "'");
	$countryban = $db->fetchOne("SELECT COUNT(*) AS NUM FROM blacklist WHERE type='country' AND criteria='" . $country . "'");

	if ($verifyusername != 0) {
		serveranswer(0, $lang['txt']['usernameblocked']);
	}
	else {
		if ($ipban1 != 0 || $ipban2 != 0 || $ipban3 != 0 || $ipban4 != 0) {
			serveranswer(0, $lang['txt']['ipblocked']);
		}
		else {
			if ($countryban != 0) {
				serveranswer(0, $lang['txt']['countryblocked']);
			}
		}
	}

	$verifyuser = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE username='" . $username . "'");

	if ($verifyuser == 0) {
		serveranswer(0, $lang['txt']['invalidlogindetails']);
	}

	$user_info = $db->fetchRow("SELECT id, username, password, status, country FROM members WHERE username='" . $username . "'");

	if ($user_info['password'] != md5($password)) {
		$bid = array("user_id" => $user_info['id'], "ip" => $ip_user, "status" => "Failed", "password" => $password, "date" => TIMENOW, "agent" => (!empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $_ENV['HTTP_USER_AGENT']));
		$upd = $db->insert("login_history", $bid);
		serveranswer(0, $lang['txt']['invalidlogindetails']);
	}


	if ($settings['multi_login'] == "yes") {
		$checkip = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE last_ip='" . $ip_user . "' AND id!=" . $user_info['id']);

		if ($checkip != 0) {
			$showip_error = "yes";
		}


		if ($showip_error == "yes") {
			serveranswer(0, $lang['txt']['multipleaccountsdetected']);
		}
	}


	if ($settings['multi_country'] == "yes") {
		if ($country != $user_info['country'] && $country != "-") {
			serveranswer(0, $lang['txt']['multiplecountrydetected']);
		}
	}


	if ($user_info['status'] == "Un-verified") {
		serveranswer(0, $lang['txt']['accountinactive']);
	}
	else {
		if ($user_info['status'] == "Suspended") {
			serveranswer(0, $lang['txt']['accountissuspended']);
		}
	}

	$cookie_id = strtoupper(md5(TIMENOW . $ip_user . $_SERVER['HTTP_USER_AGENT']));
	$set = array("last_ip" => $ip_user, "last_login" => TIMENOW, "status" => "Active", "cookie_id" => $cookie_id);
	$upd = $db->update("members", $set, "id=" . $user_info['id']);

	if (empty($user_info['computer_id'])) {
		include "includes/encrypt.php";
		$computerid = ascii2hex(substr(md5($user_info['username'] . $user_info['signup']), 0, 10));
		$upd = $db->query("UPDATE members SET computer_id='" . $computerid . "' WHERE id=" . $user_info['id']);
	}

	$bid = array("user_id" => $user_info['id'], "ip" => $ip_user, "date" => TIMENOW, "agent" => (!empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $_ENV['HTTP_USER_AGENT']));
	$upd = $db->insert("login_history", $bid);
	$_SESSION['user_id'] = $user_info['id'];
	$_SESSION['password'] = $user_info['password'];
	$_SESSION['cookie_id'] = $cookie_id;
	setcookie("user_id", $user_info['id'], time() + 86400);
	setcookie("password", $user_info['password'], time() + 86400);
	setcookie("cookie_id", $cookie_id, time() + 86400);

	if ($verifyforum != 0) {
		$forum = $db->fetchOne("SELECT forum FROM forums WHERE status='Active' LIMIT 1");
		$forum_action = "login";
		include "modules/forums/functions/" . $forum . ".php";
	}

	serveranswer(1, "location.href='index.php?view=login&a=y';");
	exit();
}


if ($input->g['a'] == "y") {
	$smarty->assign("loginout_process", "login");
	$smarty->display("loginoutprocess.tpl");
	$db->close();
	exit();
	return 1;
}


if ($_SESSION['logged'] == "yes") {
	header("location: index.php");
	$db->close();
	exit();
}

$smarty->assign("captcha_login", $captcha_login_html);
$smarty->assign("login_class", "current");
$smarty->display("login.tpl");
$db->close();
exit();
?>