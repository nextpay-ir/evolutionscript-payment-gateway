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
$query = $db->query("SELECT * FROM helpdesk_settings");

while ($result = $db->fetch_array($query)) {
	$support[$result['field']] = $result['value'];
}


if ($settings['captcha_contact'] == "yes") {
	if ($settings['captcha_type'] == "1") {
		require_once "modules/captcha/getcaptcha.php";
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


if ($support['helpdesk_enable'] == "yes") {
	if ($support['members_only'] == "yes" && $_SESSION['logged'] != "yes") {
		header("location:index.php");
		$db->close();
		exit();
	}
	else {
		$helpdesk_enable = "yes";
	}
}
else {
	$helpdesk_enable = "no";
}

$smarty->assign("helpdesk_enable", $helpdesk_enable);

if ($helpdesk_enable == "yes") {
	if ($input->p['do'] == "checkticket") {
		$verifyticket = $db->fetchOne("SELECT COUNT(*) AS NUM FROM helpdesk_ticket WHERE ticket='" . $input->pc['ticketid'] . "'");

		if ($verifyticket == 0) {
			serveranswer(0, $lang['txt']['invalidticket']);
		}

		serveranswer(1, "location.href=\"./?view=contact&view_ticket=" . $input->p['ticketid'] . "\";");
	}


	if ($input->p['action'] == "open") {
		verifyajax();
		$subject = $input->pc['subject'];
		$department = $input->pc['department'];
		$message = $input->pc['message'];
		$name = $input->pc['name'];
		$email = $input->pc['email'];
		$captcha = strtoupper($input->pc['captcha']);

		if ($settings['captcha_contact'] == "yes") {
			if ($settings['captcha_type'] == "1") {
				$resp = validate_captcha($captcha, "");
			}
			else {
				if ($settings['captcha_type'] == "2") {
					$resp = validate_captcha($input->p['recaptcha_challenge_field'], $input->p['recaptcha_response_field']);
				}
				else {
					if ($settings['captcha_type'] == "3") {
						$resp = validate_captcha();
					}
				}
			}
		}


		if (empty($subject) || !is_numeric($department) || empty($message)) {
			serveranswer(0, $lang['txt']['all_fields_required']);
		}
		else {
			$verifydpto = $db->fetchOne("SELECT COUNT(*) AS NUM FROM helpdesk_department WHERE id=" . $department);

			if ($verifydpto == 0) {
				serveranswer(0, $lang['txt']['invalid_department']);
			}
			else {
				$ticketid = substr(strtoupper(md5(TIMENOW)), 0, 16);
				$ticketid = substr_replace($ticketid, "-", 4, 0);
				$ticketid = substr_replace($ticketid, "-", 9, 0);
				$ticketid = substr_replace($ticketid, "-", 14, 0);

				if ($_SESSION['logged'] == "yes") {
					$user_id = $user_info['id'];
					$name = $user_info['fullname'];
					$email = $user_info['email'];
				}
				else {
					if (empty($name) || empty($email)) {
						serveranswer(0, $lang['txt']['all_fields_required']);
					}
					else {
						if (validateEmail($email) !== true) {
							serveranswer(0, $lang['txt']['invalidemail']);
						}
					}

					$user_id = "0";
					$name = $lang;
					$email = $subject;
				}

				$datastored = array("ticket" => $ticketid, "department" => $department, "user_id" => $user_id, "name" => $name, "email" => $email, "subject" => $subject, "message" => $message, "date" => time(), "last_update" => time());
				$db->insert("helpdesk_ticket", $datastored);
				$str2find = array("%site_name%", "%site_url%", "%ticket_id%");
				$str2change = array($settings['site_name'], $settings['site_url'], $ticketid);
				$data_mail = array("mail_id" => "support_ticket_creation", "str2find" => $str2find, "str2change" => $str2change, "receiver" => $email);
				$mail = new MailSystem($data_mail);
				$mail->send();
				serveranswer(3, $lang['txt']['ticketsent'] . "<br>" . $lang['txt']['ticketid'] . (": <a href='./?view=contact&view_ticket=" . $ticketid . "'>" . $ticketid . "</a>"));
			}
		}
	}


	if ($input->g['t'] == "new") {
		$departmentsql = $db->query("SELECT * FROM helpdesk_department ORDER BY id ASC");
		$daparment = array();

		while ($row = $db->fetch_array($departmentsql)) {
			$row['name'] = stripslashes($row['name']);
			$daparment[] = $row;
		}

		$smarty->assign("daparment", $daparment);
		$smarty->display("helpdesk.tpl");
		$db->close();
		exit();
	}
}


if ($input->g['view_ticket']) {
	$ticket = $db->real_escape_string($input->gc['view_ticket']);

	if ($_SESSION['logged'] == "yes") {
		$verifyticket = $db->fetchOne("SELECT COUNT(*) AS NUM FROM helpdesk_ticket WHERE ticket='" . $ticket . "' AND user_id=" . $user_info['id']);
	}
	else {
		$verifyticket = $db->fetchOne("SELECT COUNT(*) AS NUM FROM helpdesk_ticket WHERE ticket='" . $ticket . "' AND user_id=0");
	}


	if ($verifyticket == 0) {
		header("location: ./?view=contact");
		$db->close();
		exit();
	}

	$ticketinfo = $db->fetchRow("SELECT * FROM helpdesk_ticket WHERE ticket='" . $ticket . "'");

	if ($input->p['action'] == "reply" && $ticketinfo['status'] != 4) {
		if (empty($input->p['message'])) {
			serveranswer(0, $lang['txt']['entermessage']);
		}

		$checklasticket = $db->fetchOne("SELECT COUNT(*) AS NUM FROM helpdesk_replies WHERE ticket_id=" . $ticketinfo['id'] . " AND user_reply!=0");

		if ($checklasticket != 0) {
			$lastickettime = $db->fetchOne("SELECT date FROM helpdesk_replies WHERE ticket_id=" . $ticketinfo['id'] . " AND user_reply!=0 ORDER BY date DESC LIMIT 1");
			$timeleft = time() - $lastickettime;

			if ($timeleft <= 60) {
				serveranswer(0, $lang['txt']['wait_to_replyticket']);
			}
		}

		$newmessage = cleanfrm($_POST['message']);
		$lastreply = time();
		$stored = array("ticket_id" => $ticketinfo['id'], "user_reply" => 1, "message" => $newmessage, "date" => $lastreply);
		$db->insert("helpdesk_replies", $stored);
		$upd = $db->query("UPDATE helpdesk_ticket SET status=3, last_update=" . $lastreply . " WHERE id=" . $ticketinfo['id']);
		serveranswer(3, $lang['txt']['msgsent']);
	}

	$ticketinfo['message'] = nl2br($ticketinfo['message']);

	if ($ticketinfo['status'] == 1 || $ticketinfo['status'] == 2 || $ticketinfo['status'] == 3) {
		$ticketinfo['status_name'] = "Open";
	}
	else {
		$ticketinfo['status_name'] = "Closed";
	}

	$repliesql = $db->query("SELECT * FROM helpdesk_replies WHERE ticket_id=" . $ticketinfo['id'] . " ORDER BY date ASC");
	$reply_info = array();

	while ($row = $db->fetch_array($repliesql)) {
		$row['message'] = nl2br(stripslashes($row['message']));
		$reply_info[] = $row;
	}

	$smarty->assign("ticket_info", $ticketinfo);
	$smarty->assign("reply_info", $reply_info);
	$smarty->display("helpdesk_tickets.tpl");
	$db->close();
	exit();
}


if ($_SESSION['logged'] == "yes") {
	include INCLUDES . "class_pagination.php";
	$allowed = array("ticket", "subject", "last_update");
	$cond = "user_id=" . $user_info['id'];
	$urlcon = "";

	if ($input->g['sort'] == 1) {
		$cond .= " AND status=1";
		$urlcon .= "sort=1&";
	}
	else {
		if ($input->g['sort'] == 2) {
			$cond .= " AND (status=2 OR status=3)";
			$urlcon .= "sort=2&";
		}
		else {
			if ($input->g['sort'] == 3) {
				$cond .= " AND status=4";
				$urlcon .= "sort=3&";
			}
		}
	}

	$paginator = new Pagination("helpdesk_ticket", $cond);
	$paginator->setMaxResult(10);
	$paginator->setOrders("last_update", "DESC");
	$paginator->setPage($input->gc['p']);
	$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
	$paginator->setLink("./?view=contact&" . $urlcon);
	$q = $paginator->getQuery();

	while ($list = $db->fetch_array($q)) {
		$items[] = $list;
	}

	$smarty->assign("paginator", $paginator);
	$smarty->assign("thelist", $items);
	unset($items);
	$smarty->display("helpdesk.tpl");
	$db->close();
	exit();
}

$smarty->display("helpdesk.tpl");
$db->close();
exit();
?>