<?php


if (!defined("EvolutionScript")) {
	exit("Hacking attempt...");
}


if ($_SESSION['logged'] == "yes") {
	header("location: index.php");
	exit();
}

$classes = array("forgot", "activation");

if ($input->p['a'] == "submit" && !empty($input->p['class'])) {
	if (!in_array($input->p['class'], $classes)) {
		serveranswer(0, $lang['txt']['invalidrequest']);
	}

	$username = $db->real_escape_string($input->p['username']);
	$email = $input->p['email'];
	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE username='" . $username . "'");

	if ($verify == 0) {
		serveranswer(0, $lang['txt']['usremailmemberinvalid']);
	}

	$member = $db->fetchRow("SELECT id, username, fullname, email, status, verifycode FROM members WHERE username='" . $username . "'");

	if ($input->p['class'] == "forgot") {
		if ($member['email'] != $email) {
			serveranswer(0, $lang['txt']['usremailmemberinvalid']);
		}
		else {
			if ($member['status'] == "Un-verified") {
				serveranswer(0, $lang['txt']['accountinactive']);
			}
			else {
				if ($member['status'] == "Suspended") {
					serveranswer(0, $lang['txt']['accountissuspended']);
				}
			}
		}

		$newpassword = substr(md5($member['id'] . time()), 0, 7);
		$set = array("password" => md5($newpassword));
		$upd = $db->update("members", $set, "id=" . $member['id']);
		$str2find = array("%site_name%", "%site_url%", "%fullname%", "%username%", "%newpassword%");
		$str2change = array($settings['site_name'], $settings['site_url'], $member['fullname'], $member['username'], $newpassword);
		$data_mail = array("mail_id" => "forgot_password", "str2find" => $str2find, "str2change" => $str2change, "receiver" => $member['email']);
		$mail = new MailSystem($data_mail);
		$mail->send();
		serveranswer(5, $lang['txt']['newpaswordsent']);
	}
	else {
		if ($member['email'] != $email) {
			serveranswer(0, $lang['txt']['usremailmemberinvalid']);
		}
		else {
			if ($member['status'] == "Active") {
				serveranswer(0, $lang['txt']['accountisactive']);
			}
			else {
				if ($member['status'] == "Suspended") {
					serveranswer(0, $lang['txt']['accountissuspended']);
				}
				else {
					if ($settings['register_activation'] != "yes") {
						serveranswer(0, $lang['txt']['notnecessaryactlink']);
					}
				}
			}
		}

		$verifycode = substr(md5($member['id'] . time()), 0, 20);
		$set = array("verifycode" => $verifycode);
		$upd = $db->update("members", $set, "id=" . $member['id']);
		$str2find = array("%site_name%", "%site_url%", "%fullname%", "%username%", "%activation_code%");
		$str2change = array($settings['site_name'], $settings['site_url'], $member['fullname'], $member['username'], $verifycode);
		$data_mail = array("mail_id" => "registration_activation", "str2find" => $str2find, "str2change" => $str2change, "receiver" => $member['email']);
		$mail = new MailSystem($data_mail);
		$mail->send();
		serveranswer(5, $lang['txt']['resendactivationmsg']);
	}
}

include SMARTYLOADER;
$smarty->assign("login_class", "current");

if ($_REQUEST['page'] == "resend_activation") {
	$smarty->display("resend_activation.tpl");
}
else {
	$smarty->display("forgot.tpl");
}

$db->close();
exit();
?>