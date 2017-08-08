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


if ($settings['register_activation'] != "yes") {
	header("location: index.php");
	$db->close();
	exit();
}


if ($_SESSION['logged'] == "yes") {
	header("location: index.php");
	$db->close();
	exit();
}


if ($input->p['a'] == "submit") {
	verifyajax();
	$username = $db->real_escape_string(cleanfrm($input->p['username']));
	$code = $db->real_escape_string(cleanfrm($input->p['code']));
	$inputs = array("username" => $input->p['username'], "code" => $input->p['code']);

	if (!$username || !$code) {
		serveranswer(0, $lang['txt']['fieldsempty']);
	}

	$res = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE username='" . $username . "' and verifycode='" . $code . "'");

	if ($res == 0) {
		serveranswer(0, $lang['txt']['usernameoractivationinvalid']);
	}

	$user_info = $db->fetchRow("SELECT id, username, fullname, email FROM members WHERE username='" . $username . "'");
	$set = array("status" => "Active", "verifycode" => "");
	$upd = $db->update("members", $set, "id=" . $user_info['id']);
	$str2find = array("%site_name%", "%site_url%", "%fullname%", "%username%");
	$str2change = array($settings['site_name'], $settings['site_url'], $user_info['fullname'], $user_info['username']);
	$data_mail = array("mail_id" => "registration_complete", "str2find" => $str2find, "str2change" => $str2change, "receiver" => $user_info['email']);
	$mail = new MailSystem($data_mail);
	$mail->send();
	serveranswer(2, "$('#message_sent').show();");
}

require SMARTYLOADER;
$smarty->display("activation.tpl");
$db->close();
exit();
?>