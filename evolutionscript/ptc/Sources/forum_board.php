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

$board_id = $db->real_escape_string($input->g['board']);
$check_board = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_boards WHERE id=" . $board_id);

if ($check_board != 0) {
	$frm_board = $db->fetchRow("SELECT * FROM forum_boards WHERE id=" . $board_id);
	$frm_category = $db->fetchRow("SELECT * FROM forum_categories WHERE id=" . $frm_board['cat_id']);

	if ($_REQUEST['do'] == "topic" && $_SESSION['logged'] == "yes") {
		if ($user_group['canposttopic'] == "no") {
			$smarty->display("forum_blocked.tpl");
			$db->close();
			exit();
		}


		if ($input->p['a'] == "doit") {
			verifyajax();
			$new_post = cleanfrm($input->p['message']);
			$topic_title = cleanfrm($input->p['topic_title']);
			$topic_descr = cleanfrm($input->p['topic_descr']);

			if ($user_info['forum_role'] == 1) {
				$sticky = ($input->p['sticky'] == 1 ? 1 : 0);
				$locked = ($input->p['locked'] == 1 ? 1 : 0);
			}
			else {
				$sticky = 0;
				$locked = 0;
			}


			if (empty($topic_title)) {
				serveranswer(0, $lang['txt']['fieldsempty']);
			}
			else {
				if (strlen($new_post) == 0) {
					serveranswer(0, $lang['txt']['fieldsempty']);
				}
				else {
					$datastored = array("bid" => $frm_board['id'], "title" => $topic_title, "descr" => $topic_descr, "message" => $new_post, "date" => time(), "date_updated" => time(), "author" => $user_info['username'], "topic" => "yes", "sticky" => $sticky, "locked" => $locked);
					$db->insert("forum_posts", $datastored);
					$postid = $db->lastInsertId();
					$upd = $db->query("UPDATE forum_posts SET topic_rel=" . $postid . " WHERE id=" . $postid);
					$upd = $db->query("UPDATE forum_boards SET topics=topics+1, posts=posts+1 WHERE id=" . $frm_board['id']);
					$membership = $db->fetchRow("SELECT point_enable, point_post FROM membership WHERE id=" . $user_info['type']);
					$data = array("forum_posts" => $user_info['forum_posts'] + 1);

					if ($membership['point_enable'] == 1) {
						addpoints($user_info['id'], $membership['point_post']);
					}

					$db->update("members", $data, "id=" . $user_info['id']);
					board_recheck($user_info['id'], $board_id);
					serveranswer(1, "location.href=\"forum.php?topic=" . $postid . "\";");
				}
			}
		}

		$smarty->assign("frm_category", $frm_category);
		$smarty->assign("frm_board", $frm_board);
		$smarty->display("forum_newtopic.tpl");
		$db->close();
		exit();
	}


	if ($_SESSION['logged'] == "yes") {
		board_checked($user_info['id'], $frm_board['id']);
	}

	$allowed = array("date_updated", "replies", "views", "sticky");
	$paginator = new Pagination("forum_posts", "topic='yes' and bid=" . $frm_board['id']);
	$paginator->setMaxResult(10);
	$paginator->setOrders("sticky", "DESC");

	if ($input->g['orderby'] == "sticky" || !$input->g['orderby']) {
		$paginator->setMultiOrder("date_updated", "DESC");
	}

	$paginator->setPage($input->gc['p']);
	$paginator->allowedfield($allowed);
	$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
	$paginator->setLink("?board=" . $frm_board['id'] . "&");
	$q = $paginator->getQuery();

	while ($list = $db->fetch_array($q)) {
		$lastpost = $db->fetchRow("SELECT date, author FROM forum_posts WHERE topic_rel=" . $list['id'] . " ORDER by date DESC LIMIT 1");
		$list['last_poster'] = $lastpost['author'];
		$list['last_post_date'] = $lastpost['date'];
		$items[] = $list;
	}

	$smarty->assign("paginator", $paginator);
	$smarty->assign("thelist", $items);
	unset($items);
	$smarty->assign("frm_category", $frm_category);
	$smarty->assign("frm_board", $frm_board);
	$smarty->assign("topic", $topic);
	$smarty->assign("topic_lastmsg", $topic_lastmsg);
	$smarty->display("forum_board.tpl");
	$db->close();
	exit();
}

?>