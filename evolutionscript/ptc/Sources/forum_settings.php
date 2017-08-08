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


if ($settings['forum_active'] != "yes") {
	header("location: ./?view=account");
	exit();
}


if ($input->p['a'] == "submit") {
	verifyajax();
	$forum_avatar = $input->pc['forum_avatar'];
	$forum_stats = $input->pc['forum_stats'];

	if ($forum_avatar != "") {
		$ck = @getimagesize($forum_avatar);

		if (!is_array($ck)) {
			serveranswer(0, "Invalid forum avatar");
		}
	}

	$data = array("forum_avatar" => $forum_avatar, "forum_stats" => $forum_stats);

	if ($settings['forum_signature'] == "yes") {
		if ($settings['forum_signature_maxchar'] < strlen($input->pc['forum_signature'])) {
			serveranswer(0, str_replace("%chars%", $settings['forum_signature_maxchar'], $lang['txt']['frmsignature_max_char']));
		}

		$data['forum_signature'] = $input->pc['forum_signature'];
	}

	$upd = $db->update("members", $data, "id=" . $user_info['id']);
	serveranswer(4, $lang['txt']['forum_settings_updated']);
}

include SMARTYLOADER;
$smarty->assign("file_name", "forum_settings.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>