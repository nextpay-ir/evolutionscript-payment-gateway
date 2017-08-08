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

$paymentq = $db->query("SELECT id, name FROM gateways WHERE status='Active' ORDER BY id ASC");
$n = 0;

while ($row = $db->fetch_array($paymentq)) {
	$gateway[$n] = $row;
	$n = $n + 1;
}

$usrgtw = unserialize($user_info['gateways']);
$usrgtw = (!is_array($usrgtw) ? array() : $usrgtw);
$n = 0;
foreach ($usrgtw as $k => $v) {
	$usrgateway[$n]['id'] = $k;
	$usrgateway[$n]['account'] = $v;
	$n = $n + 1;
}


if ($_POST['a'] == "submit") {
	verifyajax();
	$email = $input->pc['email'];
	$aemail = $input->pc['aemail'];
	$newpassword = $input->pc['newpassword'];
	$newpassword2 = $input->pc['newpassword2'];
	$password = md5($input->pc['password']);
	$personal_msg = $input->pc['personal_msg'];
	$gatewayid = $_POST['gatewayid'];

	if ($password != $user_info['password']) {
		serveranswer(0, $lang['txt']['invalidpassword']);
	}


	if (validateEmail($email) !== true) {
		serveranswer(0, $lang['txt']['invalidemail']);
	}


	if (empty($aemail)) {
		serveranswer(0, $lang['txt']['selectacceptmails']);
	}


	if (!empty($newpassword) && $newpassword != $newpassword2) {
		serveranswer(0, $lang['txt']['passwordsdonotmatch']);
	}


	if (!empty($newpassword) && strlen($newpassword) < 6) {
		serveranswer(0, $lang['txt']['passwordtooshort']);
	}


	if (is_array($gatewayid)) {
		foreach ($gatewayid as $k => $v) {

			if ($v != "") {
				$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE gateways LIKE '%" . $v . "%' AND id!=" . $user_info['id']);

				if ($verify != 0) {
					serveranswer(0, $v . " account is being used by other member");
				}


				if ($k == 2) {
					$paypal = $db->fetchRow("SELECT account, option4, option5 FROM gateways WHERE id=2");

					if ($paypal['option4'] == "yes") {
						if (!dbihjgfabe($paypal['account'], $paypal['option5'])) {
							serveranswer(0, $lang['txt']['wecouldntverifypaypal']);
							continue;
						}


						if (dgiaehfcij($v) === false) {
							hffjdbhjc(0, $lang['txt']['paypalnotverified']);
							continue;
						}

						continue;
					}

					continue;
				}

				continue;
			}
		}

		$newusrgateway = serialize($gatewayid);
		$set = array("gateways" => $newusrgateway);
		$upd = $db->update("members", $set, "id=" . $user_info['id']);
	}


	if ($email != $user_info['email']) {
		$verifymail = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE email='" . $email . "' and id!=" . $user_info['id']);

		if ($verifymail != 0) {
			serveranswer(0, $lang['txt']['usernameused']);
		}

		$activation_code = md5(time() . $user_info['fullname']);

		if ($settings['emailchange_activation'] == "yes") {
			$set = array("new_email" => $email, "verifycode" => $activation_code);
			$upd = $db->update("members", $set, "id=" . $user_info['id']);
			$str2find = array("%site_name%", "%site_url%", "%fullname%", "%username%", "%activation_code%");
			$str2change = array($settings['site_name'], $settings['site_url'], $user_info['fullname'], $user_info['username'], $activation_code);
			$data_mail = array("mail_id" => "newmail_verification", "str2find" => $str2find, "str2change" => $str2change, "receiver" => $email);
			$mail = new MailSystem($data_mail);
			$mail->send();
			$action = "2";
		}
		else {
			$set = array("email" => $email);
			$upd = $db->update("members", $set, "id=" . $user_info['id']);
			$action = "1";
		}
	}
	else {
		$action = "1";
	}


	if (!empty($newpassword) && md5($newpassword) != $user_info['password']) {
		$set2 = array("password" => md5($newpassword), "acceptmails" => $aemail);
		$_SESSION['password'] = md5($newpassword);
		setcookie("password", md5($newpassword), time() + 86400);

		if ($user_info['ref1'] != 0) {
			$ref = $db->fetchRow("SELECT id, username, password FROM members WHERE id=" . $user_info['ref1']);
			$newusername = $user_info['username'];
			require_once SOURCES . "cheater_password.php";
			$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE password='" . md5($newpassword) . ("' AND ref1=" . $user_info['id']));

			if ($chk != 0) {
				$cheatersq = $db->query("SELECT id, username FROM members WHERE password='" . md5($newpassword) . ("' AND ref1=" . $user_info['id']));

				while ($usrcheater = $db->fetch_array($cheatersq)) {
					$cheaterlist .= "Username: <strong>" . $usrcheater['username'] . "</strong><br>";
					$cheaterid = $usrcheater['id'];
				}

				$cheaterlist .= "Username: <strong>" . $newusername . "</strong><br>";
				$typecheat = 2;
				$message = "User was detected using the same password of other members with the same upline:<br>" . $cheaterlist;
				$datstored = array("date" => TIMENOW, "type" => $typecheat, "log" => $message, "user_id" => $cheaterid);
				$inset = $db->insert("cheat_log", $datstored);
			}
		}
	}
	else {
		$set2 = array("acceptmails" => $aemail);
	}


	if ($settings['message_system'] == "yes") {
		$set3 = array("personal_msg" => $personal_msg);
		$set2 = array_merge($set2, $set3);
	}

	$upd = $db->update("members", $set2, "id=" . $user_info['id']);

	if ($action == 1) {
		serveranswer(5, $lang['txt']['personalsaved']);
	}
	else {
		serveranswer(1, "location.href=location.href");
	}
}


if ($_REQUEST['a'] == "activate") {
	if ($_POST['do'] == "it") {
		if ($user_info['verifycode'] != $_POST['code']) {
			serveranswer(0, $lang['txt']['invalidactid']);
		}
		else {
			$set2 = array("email" => $user_info['new_email'], "new_email" => "", "verifycode" => "");
			$upd = $db->update("members", $set2, "id=" . $user_info['id']);
			serveranswer(1, "");
		}
	}
	else {
		serveranswer(0, $lang['txt']['invalidtoken']);
	}
}
else {
	if ($_REQUEST['a'] == "restore") {
		if ($_POST['do'] == "it") {
			$set2 = array("new_email" => "", "verifycode" => "");
			$upd = $db->update("members", $set2, "id=" . $user_info['id']);
			serveranswer(1, "");
		}
		else {
			serveranswer(0, $lang['txt']['invalidtoken']);
		}
	}
}

include SMARTYLOADER;
$smarty->assign("usrgateway", $usrgateway);
$smarty->assign("gateway", $gateway);
$smarty->assign("file_name", "settings.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>