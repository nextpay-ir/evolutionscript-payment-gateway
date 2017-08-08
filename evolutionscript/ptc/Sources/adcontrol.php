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

$pages = array("ads", "banner_ads", "featured_ads", "featured_link");

if ($input->g['a'] == "submit") {
	if ($input->g['request'] == "control") {
		if (!in_array($input->p['class'], $pages)) {
			serveranswer(0, $lang['txt']['invalidrequest']);
		}


		if (!is_numeric($input->p['id'])) {
			serveranswer(0, $lang['txt']['invalidad']);
		}


		if ($input->p['class'] == "ads") {
			$actions = array("1" => "start", "2" => "pause", "3" => "delete");

			if (!in_array($input->p['action'], $actions)) {
				serveranswer(0, $lang['txt']['invalidrequest']);
			}

			$adid = $db->real_escape_string($input->p['id']);
			$verifyad = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ads WHERE id=" . $adid . " and user_id=" . $user_info['id']);

			if ($verifyad == 0) {
				serveranswer(0, $lang['txt']['invalidad']);
			}


			if ($input->p['action'] == "delete") {
				$advalue = $db->fetchRow("SELECT value, click_pack  FROM ads WHERE id=" . $adid);
				$adcredit = $db->fetchOne("SELECT credits FROM ad_value WHERE value=" . $advalue['value']);
				$credits = $adcredit * $advalue['click_pack'];
				$upd = $db->query("UPDATE members SET ad_credits=ad_credits+" . $credits . " WHERE id=" . $user_info['id']);
				$db->delete("ads", "id=" . $adid);
				serveranswer(1, "");
			}


			if ($input->p['action'] == "start") {
				$newset = "Active";
			}
			else {
				if ($input->p['action'] == "pause") {
					sleep(40);
					$newset = "Paused";
				}
			}

			$set = array("status" => $newset);
			$upd = $db->update("ads", $set, "id=" . $adid);
			serveranswer(1, "");
		}
		else {
			if ($input->p['class'] == "banner_ads" || $input->p['class'] == "featured_ads" || $input->p['class'] == "featured_link") {
				if ($input->p['action'] == "delete") {
					$post_class = $db->real_escape_string(cleanfrm($input->p['class']));
					$post_id = $db->real_escape_string($input->p['id']);
					$verifyitem = $db->fetchOne("SELECT COUNT(*) AS NUM FROM " . $post_class . " WHERE id='" . $post_id . "' AND user_id=" . $user_info['id']);

					if ($verifyitem == 0) {
						serveranswer(0, $lang['txt']['invalidrequest']);
					}
					else {
						if ($input->p['class'] == "banner_ads") {
							$adcredit = $db->fetchOne("SELECT credits FROM banner_ads WHERE id='" . $post_id . "'");
							$upd = $db->query("UPDATE members SET banner_credits=banner_credits+" . $adcredit . " WHERE id=" . $user_info['id']);
						}
						else {
							if ($input->p['class'] == "featured_ads") {
								$adcredit = $db->fetchOne("SELECT credits FROM featured_ads WHERE id='" . $post_id . "'");
								$upd = $db->query("UPDATE members SET fads_credits=fads_credits+" . $adcredit . " WHERE id=" . $user_info['id']);
							}
						}

						$delete = $db->delete($post_class, ("id='" . $post_id . "'"));
						serveranswer(1, "");
					}
				}
				else {
					serveranswer(0, $lang['txt']['invalidrequest']);
				}
			}
		}
	}
	else {
		serveranswer(0, $lang['txt']['notallowedtoviewpage']);
	}

	$db->close();
	exit();
	return 1;
}

header("location: index.php");
$db->close();
exit();
?>