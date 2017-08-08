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
define("EvolutionScript", 1);
define("ADMINPATH", dirname(__FILE__) . "/");
require_once ADMINPATH . "library.php";
$lang['invalid_login_details'] = "Invalid login details";
$lang['account_is_disabled'] = "Account is disabled";

if (ADMINLOGGED !== true) {
	include SOURCES . "login.php";
	$db->close();
	exit();
}
else {
	$notes = $user_info['notes'];

	if ($input->p['do'] == "savenotes") {
		$upd = array("notes" => $input->pc['mynotes']);
		$update = $db->update("admin", $upd, ("id='" . $admin->getId() . "'"));
		serveranswer(1, "");
	}


	if ($admin->getLastIP() != $admin->getIp() && $admin->getProtection() == 1) {
		if ($admin->getCheckcode() == "" || ($input->p['resend_code'] && !$_SESSION['resend_code'])) {
			$alpha_numeric = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
			$new_code = substr(str_shuffle($alpha_numeric), 0, 20);
			$code_enc = md5($new_code);
			$data = array("check_code" => $code_enc);
			$db->update("admin", $data, "id=" . $admin->getId());
			$country = ip2country($admin->getIp());
			$message = "Dear " . $admin->getUsername() . ",

";
			$message .= "Our system detected that you are accessing your account from a different location (IP: " . $admin->getIp() . ", Country: " . $country . "). We sent a verification code to your email to make sure you are the owner of this account.

If you did NOT try to login to your account then we would strongly recommend you change your login information.

Verification Code: " . $new_code . "

";
			$message .= "All the best,
";
			$message .= $settings['site_name'] . " Team.";
			$subject = $settings['site_name'] . " Verification Code";
			$mail = new mail();
			$mail->setFrom();
			$mail->addTo($admin->getEmail(), $admin->getUsername());
			$mail->setSubject($subject);
			$mail->setBodyText($message);
			$mail->send();

			if ($input->p['resend_code']) {
				$_SESSION['resend_code'] = 1;
			}
		}

		include SOURCES . "verification.php";
		$db->close();
		exit();
	}

	$pages = array("loginlog" => "loginlog.php", "account" => "account.php", "logout" => "logout.php", "members" => "members.php", "addmember" => "new_member.php", "massmail" => "mass_mail.php", "massmessage" => "massmessage.php", "ptcads_settings" => "ptc_settings.php", "ptsu_settings" => "ptsu_settings.php", "fads_settings" => "fads_settings.php", "flinks_settings" => "flinks_settings.php", "bannerads_settings" => "bannerads_settings.php", "loginads_settings" => "loginads_settings.php", "manageptc" => "manage_ptc.php", "managefad" => "manage_fad.php", "manageflink" => "manage_flink.php", "managebannerad" => "manage_bannerad.php", "manageloginad" => "manage_loginad.php", "manageptsu" => "manage_ptsu.php", "ptsu_pending" => "ptsu_pending.php", "orders" => "orders.php", "deposits" => "deposits.php", "withdrawals" => "withdrawals.php", "support_settings" => "support_settings.php", "support" => "support.php", "news" => "news.php", "faq" => "faq.php", "sitebanners" => "site_banners.php", "tos" => "edit_tos.php", "addon_modules" => "addon_modules.php", "admin_advertisement" => "admin_advertisement.php", "repair_statistics" => "repair_statistics.php", "install_language" => "install_language.php", "install_template" => "install_template.php", "blacklist" => "blacklist.php", "multipleips" => "multiple_ips.php", "assignreferral" => "assignreferral.php", "linktracker" => "linktracker.php", "googleanalytics" => "googleanalytics.php", "topdomains" => "topdomains.php", "cheat_logs" => "cheat_logs.php", "general" => "general_settings.php", "captcha" => "captcha_settings.php", "automation" => "automation_settings.php", "gateways" => "gateways_settings.php", "membership" => "membership_settings.php", "template_settings" => "template_settings.php", "language_settings" => "language_settings.php", "buy_referrals" => "buyreferrals_settings.php", "rent_referrals" => "rentreferrals_settings.php", "specialpacks_settings" => "specialpacks_settings.php", "forum_settings" => "forum_settings.php", "administrators" => "administrators.php", "add_deposit" => "add_deposit.php", "email_template" => "email_template.php", "clean_cache" => "clean_cache.php");

	if (!isset($input->g['view']) || !isset($pages[$input->g['view']])) {
		include SOURCES . "home.php";
	}
	else {
		include SOURCES . $pages[$input->g['view']];
	}
}

exit();
?>