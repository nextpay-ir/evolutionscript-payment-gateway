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

$myclicks = array("mc1", "mc2", "mc3", "mc4", "mc5", "mc6", "mc7");
$refclicks = array("r1", "r2", "r3", "r4", "r5", "r6", "r7");
$rentedrefclicks = array("rr1", "rr2", "rr3", "rr4", "rr5", "rr6", "rr7");
$autopayclicks = array("ap1", "ap2", "ap3", "ap4", "ap5", "ap6", "ap7");

if (!empty($_REQUEST['t'])) {
	$ad_token = cleanfrm($_REQUEST['t']);

	if ($_SESSION['adSync'] == $ad_token) {
		$typecheat = 1;
		$message = "User " . $user_info['username'] . " was detected using autoclick software";
		$datstored = array("date" => TIMENOW, "type" => $typecheat, "log" => $message, "user_id" => $user_info['id']);
		$inset = $db->insert("cheat_log", $datstored);
		$block = $db->query("UPDATE members SET status='Suspended' WHERE id='" . $user_info['id'] . "'");
		logout();
	}

	$ad_token = $db->real_escape_string($ad_token);

	if ($ad_token == "YWRtaW5hZHZlcnRpc2VtZW50" && $_SESSION['logged'] == "yes") {
		$check_ad = "yes";
		$adminAdvertisement = $db->fetchRow("SELECT * FROM admin_advertisement");
		$ad_info['token'] = "YWRtaW5hZHZlcnRpc2VtZW50";
		$ad_info['title'] = $adminAdvertisement['ad_title'];
		$ad_info['descr'] = $adminAdvertisement['ad_descr'];
		$ad_info['url'] = $adminAdvertisement['ad_url'];
		$ad_info['time'] = $adminAdvertisement['ad_time'];
	}
	else {
		$chkad = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ads WHERE token='" . $ad_token . "'");

		if ($chkad != 0) {
			$ad_info = $db->fetchRow("SELECT * FROM ads WHERE token='" . $ad_token . "'");
			$check_ad = "yes";

			if ($ad_info['status'] != "Active") {
				$check_ad = "no";
				$error_msg = $error_msg = $lang['txt']['invalidad'];
			}


			if ($_SESSION['logged'] == "yes") {
				if (strpos($ad_info['country'], $user_info['country']) === false) {
					$check_ad = "no";
					$error_msg = $lang['txt']['invalidad'];
				}
			}
		}
		else {
			$check_ad = "no";
			$ad_info['url'] = $settings['site_url'];
			$error_msg = $error_msg = $lang['txt']['invalidad'];
		}
	}


	if ($check_ad == "yes") {
		if ($_SESSION['logged'] == "yes") {
			if ($settings['unique_ip'] == "yes") {
				$chk = $db->fetchOne("SELECT COUNT(ip) FROM ip_ptc WHERE ip='" . $_SERVER['REMOTE_ADDR'] . ("' AND ad_id='" . $ad_info['id'] . "'"));

				if ($chk != 0) {
					$check_ad = "no";
					$error_msg = $lang['txt']['ip_ad_error'];
				}
			}

			$advisited = explode(", ", $user_info['advisto']);

			if ($ad_info['clicks_day'] != 0 && $ad_info['clicks_day'] <= $ad_info['clicks_today']) {
				$error_msg = $lang['txt']['adexpired'];
			}


			if (in_array($ad_info['id'], $advisited)) {
				$error_msg = $lang['txt']['adviewed'];
			}


			if ($ad_info['click_pack'] == 0 || $ad_info['status'] != "Active") {
				$error_msg = $lang['txt']['adexpired'];
			}

			$vermembership = strpos($ad_info['membership'], "," . $user_info['type'] . ",");

			if ($vermembership === false) {
				$error_msg = $lang['txt']['adexpired'];
			}

			$verifyAdminAd = $db->fetchOne("SELECT COUNT(*) AS NUM FROM admin_advertisement WHERE ad_expires>=" . time());

			if ($verifyAdminAd != 0 && $user_info['adminad'] == 0) {
				$error_msg = $lang['txt']['adminadrequired'];
			}


			if ($ad_token == "YWRtaW5hZHZlcnRpc2VtZW50" && $user_info['adminad'] == 1) {
				$error_msg = $lang['txt']['adexpired'];
			}


			if ($ad_token == "YWRtaW5hZHZlcnRpc2VtZW50" && $user_info['adminad'] == 0) {
				unset($error_msg);
			}

			$max_clicks = $db->fetchOne("SELECT max_clicks FROM membership WHERE id=" . $user_info['type']);
			$myclicks = array("mc1", "mc2", "mc3", "mc4", "mc5", "mc6", "mc7");

			if ($max_clicks != 0 && $max_clicks <= $user_info[$myclicks[$user_info['chart_num']]]) {
				$error_msg = "You only can view " . $max_clicks . " advertisements per day";
			}
		}
		else {
			$error_msg = $lang['txt']['notloggedin'];
			$set = array("outside_clicks" => $ad_info['outside_clicks'] + 1);
			$upd = $db->update("ads", $set, "id = " . $ad_info['id']);
		}
	}

	getnumbers();

	if ($_POST['action'] == "validate" && !empty($_SESSION['adtime']) && $_SESSION['logged'] == "yes") {
		verifyajax();
		$time_viewed = time() - $_SESSION['adtime'];

		if ($time_viewed < $ad_info['time']) {
			exit($lang['txt']['invalidtoken']);
		}
		else {
			if (!empty($error_msg)) {
				exit($error_msg);
			}
			else {
				if ($_POST['masterkey'] != $_SESSION['valid_key'] || !$_SESSION['valid_key']) {
					unset($_SESSION['valid_key']);
					unset($_SESSION['vnumbers']);
					unset($_SESSION['the_number']);
					unset($_SESSION['adtime']);
					exit($lang['txt']['invalidimageverification']);
				}


				if ($ad_token == "YWRtaW5hZHZlcnRpc2VtZW50") {
					$upd = $db->query("UPDATE members SET adminad=1 WHERE id=" . $user_info['id']);
					exit("ok");
				}

				$membership = $db->fetchRow("SELECT point_enable, point_ptc, click FROM membership WHERE id=" . $user_info['type']);
				$adpercent = $membership['click'];
				$admoney = $ad_info['value'] * $adpercent / 100;
				$set = array("advisto" => $user_info['advisto'] . ", " . $ad_info['id'], "money" => $user_info['money'] + $admoney, "clicks" => $user_info['clicks'] + 1, $myclicks[$user_info['chart_num']] => $user_info[$myclicks[$user_info['chart_num']]] + 1);

				if ($membership['point_enable'] == 1) {
					addpoints($user_info['id'], $membership['point_ptc']);
				}

				$upd = $db->update("members", $set, "id = " . $user_info['id']);

				if ($ad_info['click_pack'] == 1) {
					$set2 = array("status" => "Expired");
				}
				else {
					$set2 = array("status" => "Active");
				}

				$set = array("clicks" => $ad_info['clicks'] + 1, "click_pack" => $ad_info['click_pack'] - 1, "clicks_today" => $ad_info['clicks_today'] + 1);
				$set = array_merge($set, $set2);
				$upd = $db->update("ads", $set, "id = " . $ad_info['id']);
				$dataip = array("ip" => $_SERVER['REMOTE_ADDR'], "ad_id" => $ad_info['id']);
				$db->insert("ip_ptc", $dataip);
				$checkadcat = $db->fetchOne("SELECT earn_ref FROM ad_value WHERE id='" . $ad_info['category'] . "'");

				if ($user_info['ref1'] != 0 && $checkadcat == 1) {
					$refclass = new RefCron($user_info['ref1']);
					$ref = $refclass->get_info();

					if (($settings['clicks_necessary'] <= $refclass->yesterdayclicks() && $settings['click_yesterday'] == "yes") || $settings['click_yesterday'] != "yes") {
						$ref_type = $db->fetchRow("SELECT id, ref_click FROM membership WHERE id=" . $ref['type']);
						$refmoney = $ad_info['value'] * $ref_type['ref_click'] / 100;
						$set = array("money" => $ref['money'] + $refmoney, "refearnings" => $ref['refearnings'] + $refmoney, "refclicks" => $ref['refclicks'] + 1, $refclicks[$refclass->get_chartnum()] => $ref[$refclicks[$refclass->get_chartnum()]] + 1);
						$upd = $db->update("members", $set, "id = " . $ref['id']);
						$upd = $db->query("UPDATE members SET for_refclicks=for_refclicks+1, for_refearned=for_refearned+" . $refmoney . ", for_reflastclick=" . TIMENOW . (" WHERE id=" . $user_info['id']));
					}

					unset($ref);
				}


				if ($user_info['rented'] != 0 && $checkadcat == 1) {
					$refclass = new RefCron($user_info['rented']);
					$ref = $refclass->get_info();

					if (($settings['clicks_necessary'] <= $refclass->yesterdayclicks() && $settings['click_yesterday'] == "yes") || $settings['click_yesterday'] != "yes") {
						$ref_type = $db->fetchRow("SELECT * FROM membership WHERE id=" . $ref['type']);
						$refmoney = $ad_info['value'] * $ref_type['ref_click'] / 100;
						$set = array("money" => $ref['money'] + $refmoney, "refearnings" => $ref['refearnings'] + $refmoney, "refclicks" => $ref['refclicks'] + 1, $rentedrefclicks[$refclass->get_chartnum()] => $ref[$rentedrefclicks[$refclass->get_chartnum()]] + 1);

						if ($ref['autopay'] == "yes" && $user_info['rented_autopay'] == "0") {
							if ($ref['rented_referrals'] <= 250) {
								$autopay = $ref_type['autopay250'];
							}
							else {
								if (250 < $ref['rented_referrals'] && $ref['rented_referrals'] <= 500) {
									$autopay = $ref_type['autopay500'];
								}
								else {
									if (500 < $ref['rented_referrals'] && $ref['rented_referrals'] <= 750) {
										$autopay = $ref_type['autopay750'];
									}
									else {
										if (750 < $ref['rented_referrals'] && $ref['rented_referrals'] <= 1000) {
											$autopay = $ref_type['autopay1000'];
										}
										else {
											if (1000 < $ref['rented_referrals'] && $ref['rented_referrals'] <= 1250) {
												$autopay = $ref_type['autopay1250'];
											}
											else {
												if (1250 < $ref['rented_referrals'] && $ref['rented_referrals'] <= 1500) {
													$autopay = $ref_type['autopay1500'];
												}
												else {
													if (1500 < $ref['rented_referrals'] && $ref['rented_referrals'] <= 1750) {
														$autopay = $ref_type['autopay1750'];
													}
													else {
														if (1750 < $ref['rented_referrals']) {
															$autopay = $ref_type['autopayover'];
														}
													}
												}
											}
										}
									}
								}
							}


							if ($autopay < $ref['purchase_balance']) {
								$set2 = array("purchase_balance" => $ref['purchase_balance'] - $autopay, $autopayclicks[$refclass->get_chartnum()] => $ref[$autopayclicks[$refclass->get_chartnum()]] + $autopay);
								$set = array_merge($set, $set2);
								$onedaymore = 60 * 60 * 24;
								$upd = $db->query("UPDATE members SET rented_autopay=1, rented_expires=rented_expires+" . $onedaymore . " WHERE id=" . $user_info['id']);
							}
						}

						$upd = $db->update("members", $set, "id = " . $ref['id']);
						$data = array("rented_earned" => $user_info['rented_earned'] + $refmoney);
						$upd = $db->update("members", $data, "id = " . $user_info['id']);
						$upd = $db->query("UPDATE members SET rented_clicks=rented_clicks+1, rented_lastclick='" . time() . ("' WHERE id=" . $user_info['id']));
					}
				}

				unset($_SESSION['valid_key']);
				unset($_SESSION['vnumbers']);
				unset($_SESSION['the_number']);
				unset($_SESSION['adtime']);
				exit("ok");
			}
		}
	}
	else {
		$_SESSION['adtime'] = time();

		if (empty($ad_info['url'])) {
			$ad_info['url'] = $site_url;
		}

		$smarty->assign("error_msg", $error_msg);
		$smarty->assign("ad_info", $ad_info);
		$smarty->display("surfer.tpl");
		$db->close();
		exit();
	}
}
else {
	header("location: index.php");
	$db->close;
	exit();
}

exit();
?>