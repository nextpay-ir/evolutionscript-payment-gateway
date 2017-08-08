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

session_start();
define("EvolutionScript", 1);
define("DISABLE_TEMPLATE", 1);
define("ROOTPATH", dirname(__FILE__) . "/");
define("INCLUDES", ROOTPATH . "includes/");
define("MODULES", ROOTPATH . "modules/");
define("SOURCES", ROOTPATH . "Sources/");
require INCLUDES . "init.php";
require INCLUDES . "bbcode.php";
require INCLUDES . "forum_functions.php";
include INCLUDES . "class_pagination.php";

if ($settings['forum_active'] != "yes") {
	header("location: index.php");
	exit();
}


if ($_SESSION['logged'] == "yes") {
	$user_group = $db->fetchRow("SELECT * FROM forum_groups WHERE id=" . $user_info['forum_role']);
}
else {
	$user_group = $db->fetchRow("SELECT * FROM forum_groups WHERE id=5");
}

include SOURCES . "forum_global.php";

if ($user_group['canviewforum'] == "no") {
	$smarty->display("forum_blocked.tpl");
	$db->close();
	exit();
}


if ($input->g['page'] == "search") {
	include SOURCES . "forum_search.php";
	exit();
}


if (is_numeric($input->g['ban']) && $_SESSION['logged'] == "yes") {
	if ($user_group['canbanmembers'] == "yes") {
		if ($input->g['ban'] != $user_info['id']) {
			$userdet = $db->fetchRow("SELECT forum_role FROM members WHERE id=" . $input->g['ban']);

			if ($userdet['forum_role'] != 1) {
				if ($userdet['forum_role'] == 4) {
					$data = array("forum_role" => 3);
				}
				else {
					$data = array("forum_role" => 4);
				}

				$db->update("members", $data, "id=" . $input->g['ban']);
			}
		}
	}


	if ($_SERVER['HTTP_REFERER']) {
		header("location: " . $_SERVER['HTTP_REFERER']);
	}
	else {
		header("location: ?");
	}

	exit();
}


if (is_numeric($input->g['suspend']) && $_SESSION['logged'] == "yes") {
	if ($user_group['cansuspendmember'] == "yes") {
		if ($input->g['suspend'] != $user_info['id']) {
			$userdet = $db->fetchOne("SELECT forum_role, status FROM members WHERE id=" . $input->g['suspend']);

			if ($userdet['forum_role'] != 1) {
				if ($userdet['forum_role'] != 4) {
					$data = array("forum_role" => 4);
				}


				if ($userdet['status'] != "Suspended") {
					$data['status'] = "Suspended";
				}

				$db->update("members", $data, "id=" . $input->g['suspend']);
			}
		}
	}


	if ($_SERVER['HTTP_REFERER']) {
		header("location: " . $_SERVER['HTTP_REFERER']);
	}
	else {
		header("location: ?");
	}

	exit();
}


if (is_numeric($input->g['movetopic'])) {
	if ($user_group['canviewtopic'] == "no") {
		$smarty->display("forum_blocked.tpl");
		$db->close();
		exit();
	}

	$topic_id = $input->gc['movetopic'];
	$check_topic = verify_topic($topic_id);

	if ($check_topic !== false) {
		$frm_topic = get_topic($topic_id);
		$frm_board = get_board($frm_topic['bid']);
		$frm_category = get_category($frm_board['cat_id']);

		if ($frm_topic['author'] != $user_info['username'] && $user_group['canmoveotherstopic'] != "yes") {
			$smarty->display("forum_blocked.tpl");
			$db->close();
			exit();
		}


		if ($frm_topic['author'] == $user_info['username'] && $user_group['canmoveowntopics'] != "yes") {
			$smarty->display("forum_blocked.tpl");
			$db->close();
			exit();
		}


		if ($input->p['newcategory']) {
			verifyajax();

			if (!is_numeric($input->p['newcategory'])) {
				serveranswer(0, $lang['txt']['errcommand']);
			}

			$newcategory = $input->pc['newcategory'];
			$total_posts = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_posts WHERE topic_rel=" . $frm_topic['id']);
			$check_board = bbcbbchef($newcategory);

			if ($check_board !== false) {
				$db->query("UPDATE forum_boards SET topics=topics-1, posts=posts-" . $total_posts . " WHERE id=" . $frm_board['id']);
				$db->query("UPDATE forum_boards SET topics=topics+1, posts=posts+" . $total_posts . " WHERE id=" . $newcategory);
				$db->query("UPDATE forum_posts SET bid=" . $newcategory . " WHERE topic_rel=" . $frm_topic['id']);
				serveranswer(1, "location.href=\"forum.php?topic=" . $topic_id . "\";");
			}
			else {
				serveranswer(0, $lang['txt']['errcommand']);
			}
		}

		$boards = $db->query("SELECT * FROM forum_boards WHERE id!=" . $frm_board['id']);

		while ($row = $db->fetch_array($boards)) {
			$row['category'] = $db->fetchOne("SELECT name FROM forum_categories WHERE id=" . $row['cat_id']);
			$board_list[] = $row;
		}

		$smarty->assign("boards_list", $board_list);
		unset($board_list);
		$smarty->assign("frm_category", $frm_category);
		$smarty->assign("frm_board", $frm_board);
		$smarty->assign("frm_topic", $frm_topic);
		$smarty->display("forum_movetopic.tpl");
		$db->close();
		exit();
	}
}


if ($_REQUEST['dopost'] == "delete") {
	if (!is_numeric($_POST['post'])) {
		serveranswer(0, $lang['txt']['errcommand']);
	}

	$postid = cleanfrm($_POST['post']);
	$verifypost = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_posts WHERE id=" . $postid);

	if ($verifypost != 0) {
		$postinfo = $db->fetchRow("SELECT id, bid, topic_rel, author FROM forum_posts WHERE id=" . $postid);

		if ($user_info['username'] == $postinfo['author'] && $user_group['candeleteownpost'] == "no") {
			serveranswer(0, $lang['txt']['errcommand']);
		}


		if ($user_info['username'] != $postinfo['author'] && $user_group['candeleteotherspost'] == "no") {
			serveranswer(0, $lang['txt']['errcommand']);
		}


		if ($postinfo['id'] != $postinfo['topic_rel']) {
			$upd = $db->query("UPDATE forum_boards SET posts=posts-1 WHERE id='" . $postinfo['bid'] . "'");
			$upd = $db->query("UPDATE members SET forum_posts=forum_posts-1 WHERE username='" . $postinfo['author'] . "'");
			$upd = $db->query("UPDATE forum_posts SET replies=replies-1 WHERE id='" . $postinfo['topic_rel'] . "'");
			$upd = $db->delete("forum_posts", "id=" . $postinfo['id']);
		}
		else {
			$findq = $db->query("SELECT id, bid, topic_rel, author FROM forum_posts WHERE topic_rel=" . $postid);

			while ($row = $db->fetch_array($findq)) {
				$upd = $db->query("UPDATE forum_boards SET posts=posts-1 WHERE id='" . $row['bid'] . "'");
				$upd = $db->query("UPDATE members SET forum_posts=forum_posts-1 WHERE username='" . $row['author'] . "'");
				$upd = $db->delete("forum_posts", "id=" . $row['id']);
			}

			$upd = $db->query("UPDATE forum_boards SET topics=topics-1 WHERE id='" . $postinfo['bid'] . "'");
		}

		serveranswer(1, "");
	}
	else {
		serveranswer(0, $lang['txt']['errcommand']);
	}
}


if (is_numeric($input->g['topic'])) {
	include SOURCES . "forum_topic.php";
	exit();
}


if (is_numeric($input->g['board'])) {
	include SOURCES . "forum_board.php";
	exit();
}

include SOURCES . "forum_index.php";
exit();
?>