<?php


if (!defined("EvolutionScript")) {
	exit("Hacking attempt...");
}


if (is_numeric($input->g['cat'])) {
	$cat_id = $db->real_escape_string($input->g['cat']);
	$verify_cat = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_categories WHERE id=" . $cat_id);

	if ($verify_cat != 0) {
		$cat_query = "WHERE id=" . $cat_id;
	}
}

$db_cat = $db->query("SELECT * FROM forum_categories " . $cat_query . " ORDER BY position ASC");

while ($cat_fetch = $db->fetch_array($db_cat)) {
	$cat[] = $cat_fetch;
	$db_board = $db->query("SELECT * FROM forum_boards WHERE cat_id='" . $cat_fetch['id'] . "' ORDER by position ASC");

	while ($board_fetch = $db->fetch_array($db_board)) {
		$board[$cat_fetch['id']][] = $board_fetch;
		$verify_lastpost = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_posts WHERE bid=" . $board_fetch['id']);

		if ($verify_lastpost != 0) {
			$lastmsg = $db->fetchRow("SELECT * FROM forum_posts WHERE bid=" . $board_fetch['id'] . " ORDER by date DESC LIMIT 1");
			$lastmsg['title'] = (30 < strlen($lastmsg['title']) ? substr($lastmsg['title'], 0, 27) . "..." : $lastmsg['title']);
			$last_msg[$board_fetch['id']] = $lastmsg;
		}


		if ($_SESSION['logged'] == "yes") {
			$db_checkboard = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_log_boards WHERE id_member=" . $user_info['id'] . " and id_board=" . $board_fetch['id']);

			if ($db_checkboard == 0) {
				$board_checked[$board_fetch['id']] = "new";
			}

			$board_checked[$board_fetch['id']] = "old";
		}

		$board_checked[$board_fetch['id']] = "old";
	}
}

$smarty->assign("cat", $cat);
unset($cat);
$smarty->assign("board", $board);
unset($board);
$smarty->assign("last_msg", $last_msg);
unset($last_msg);
$smarty->assign("board_checked", $board_checked);
unset($board_checked);
$smarty->display("forum_index.tpl");
$db->close();
exit();
?>