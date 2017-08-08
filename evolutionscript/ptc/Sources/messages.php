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

require_once "includes/bbcode.php";

if ($settings['message_system'] != "yes") {
	header("location: index.php?view=account");
	$db->close();
	exit();
}

include SMARTYLOADER;

if (is_numeric($input->g['read'])) {
	if ($user_info['personal_msg'] != "yes") {
		header("location: index.php?view=account&page=messages");
		$db->close();
		exit();
	}

	$msgid = $input->gc['read'];
	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM messages WHERE id=" . $msgid . " AND user_to=" . $user_info['id']);

	if ($verify != 0) {
		if ($input->g['do'] == "delete") {
			$db->delete("messages", "id=" . $msgid);
			header("location: ./?view=account&page=messages");
			$db->close();
			exit();
		}

		$db->query("UPDATE messages SET user_read='yes' WHERE id='" . $msgid . "'");
		$msg_info = $db->fetchRow("SELECT * FROM messages WHERE id=" . $msgid);

		if ($msg_info['user_from'] == 0) {
			$msg_info['user_from'] = "Administrator";
		}
		else {
			$msg_info['user_from'] = $db->fetchOne("SELECT username FROM members WHERE id=" . $msg_info['user_from']);
		}

		$msg_info['message'] = BBCode2Html($msg_info['message']);
		$msg_info['date'] = date("F d, Y, h:i A", $msg_info['date']);
		$smarty->assign("msg_info", $msg_info);
		$smarty->assign("messages_class", "ui-state-default");
		$smarty->assign("file_name", "messages_read.tpl");
		$smarty->display("account.tpl");
		$db->close();
		exit();
	}
}


if ($input->p['do'] == "send") {
	verifyajax();

	if (empty($input->pc['subject'])) {
		serveranswer(0, $lang['txt']['fieldsempty']);
	}


	if (empty($input->pc['message'])) {
		serveranswer(0, $lang['txt']['fieldsempty']);
	}


	if (is_numeric($input->pc['user_to_id'])) {
		$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE id='" . $input->pc['user_to_id'] . "' AND personal_msg='yes'");

		if ($verify != 0) {
			$user_to = $input->pc['user_to_id'];

			if ($user_to == $user_info['id']) {
				serveranswer(0, $lang['txt']['cannotmsgurself']);
			}
		}
		else {
			serveranswer(0, $lang['txt']['invalidrecipient']);
		}
	}
	else {
		if (!empty($input->pc['user_to'])) {
			$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE username='" . $input->pc['user_to'] . "' AND personal_msg='yes'");

			if ($verify != 0) {
				$user_to = $db->fetchOne("SELECT id FROM members WHERE username='" . $input->pc['user_to'] . "'");

				if ($user_to == $user_info['id']) {
					serveranswer(0, $lang['txt']['cannotmsgurself']);
				}
			}
			else {
				serveranswer(0, $lang['txt']['invalidrecipient']);
			}
		}
		else {
			serveranswer(0, $lang['txt']['invalidrecipient']);
		}
	}

	$stored = array("user_from" => $user_info['id'], "user_to" => $user_to, "subject" => $input->pc['subject'], "message" => $input->pc['message'], "date" => TIMENOW, "user_read" => "no");
	$db->insert("messages", $stored);
	serveranswer(3, $lang['txt']['msgsent']);
}


if ($input->p['do'] == "action") {
	verifyajax();

	if (!is_array($input->p['msg'])) {
		serveranswer(0, $lang['txt']['selectone']);
	}

	foreach ($input->p['msg'] as $v) {
		$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM messages WHERE id='" . $v . "' AND user_to=" . $user_info['id']);

		if ($verify != 0) {
			switch ($input->p['action']) {
				case 1:
					$db->delete("messages", "id=" . $v);
					break;

				case 2:
					$db->query("UPDATE messages SET user_read='no' WHERE id='" . $v . "'");
					break;

				case 3:
					$db->query("UPDATE messages SET user_read='yes' WHERE id='" . $v . "'");
					break;

				default:
					serveranswer(0, $lang['txt']['selectone']);
					break;
			}
		}
	}

	serveranswer(1, "location.href=\"./?view=account&page=messages\";");
}


if (is_numeric($input->g['reply']) || is_numeric($input->g['quote'])) {
	if (is_numeric($input->g['reply'])) {
		$msgid = $input->gc['reply'];
	}
	else {
		if (is_numeric($input->g['quote'])) {
			$msgid = $input->gc['quote'];
		}
	}

	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM messages WHERE id='" . $msgid . "' AND user_to=" . $user_info['id']);

	if ($verify != 0) {
		$msg_info = $db->fetchRow("SELECT * FROM messages WHERE id='" . $msgid . "'");
		$usrid = $msg_info['user_from'];
		$from_info = $db->fetchRow("SELECT username, forum_role FROM members WHERE id=" . $usrid);

		if ($from_info['forum_role'] == 1) {
			$user_to = $db->fetchOne("SELECT name FROM forum_groups WHERE id=1");
		}
		else {
			$user_to = $from_info['username'];
		}

		$msg_info['subject'] = "Re: " . $msg_info['subject'];

		if (is_numeric($_GET['quote'])) {
			$message = $msg_info['message'];
		}

		$smarty->assign("subject", $msg_info['subject']);
		$smarty->assign("message", $message);
	}
}


if (is_numeric($_GET['to'])) {
	$usrid = $input->gc['to'];
	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE id='" . $usrid . "' AND personal_msg='yes'");

	if ($verify != 0) {
		$toinfo = $db->fetchRow("SELECT username, forum_role FROM members WHERE id=" . $usrid);

		if ($toinfo['forum_role'] == 1) {
			$user_to = $db->fetchOne("SELECT name FROM forum_groups WHERE id=1");
		}
		else {
			$user_to = $toinfo['username'];
		}
	}
}

$smarty->assign("usrid", $usrid);
$smarty->assign("user_to", $user_to);
include INCLUDES . "class_pagination.php";
$allowed = array("user_read", "date", "subject", "user_from");
$paginator = new Pagination("messages", "user_to=" . $user_info['id']);
$paginator->setMaxResult($settings['max_result_page']);
$paginator->setOrders("date", "DESC");
$paginator->setPage($input->gc['p']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=account&page=messages&");
$q = $paginator->getQuery();

while ($list = $db->fetch_array($q)) {
	if ($list['user_from'] == 0) {
		$list['user_from'] = "Administrator";
	}
	else {
		$list['user_from'] = $db->fetchOne("SELECT username FROM members WHERE id=" . $list['user_from']);
	}

	$items[] = $list;
}

$smarty->assign("paginator", $paginator);
$smarty->assign("thelist", $items);
unset($items);
$smarty->assign("messages_class", "ui-state-default");
$smarty->assign("file_name", "messages_inbox.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>