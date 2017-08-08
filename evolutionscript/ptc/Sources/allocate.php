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

$pages = array("ads", "login_ads", "banner_ads", "featured_ads", "featured_link", "ptsu_offers");
$usertbl = array("ad_credits", "loginads_credits", "banner_credits", "fads_credits", "flink_credits");
$pages_title = array($lang['txt']['ptcads'], $lang['txt']['loginads'], $lang['txt']['bannerad'], $lang['txt']['featuredad'], $lang['txt']['featuredlink'], $lang['txt']['ptsu']);

if ($input->p['a'] == "submit" && !empty($input->p['class'])) {
	verifyajax();
	$creditallocate = round($db->real_escape_string($input->p['allocate']));

	if (!in_array($input->p['class'], $pages)) {
		serveranswer(0, $lang['txt']['errcommand']);
	}


	if (!is_numeric($input->p['adid']) || !is_numeric($creditallocate)) {
		serveranswer(0, $input->p['txt']['errcommand']);
	}

	$adid = $db->real_escape_string($input->p['adid']);
	$request_class = $db->real_escape_string(cleanfrm($_REQUEST['class']));
	$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM " . $request_class . " WHERE id=" . $adid);

	if ($verify == 0) {
		serveranswer(0, $lang['txt']['invalidad']);
	}


	if ($creditallocate <= 0) {
		serveranswer(0, $lang['txt']['errcommand']);
	}


	if ($input->p['class'] == "ads") {
		if ($user_info['ad_credits'] < $creditallocate || $user_info['ad_credits'] == 0) {
			serveranswer(0, $lang['txt']['noenoughcredits']);
		}

		$advalue = $db->fetchOne("SELECT value FROM ads WHERE id=" . $adid);
		$adcreditvalue = $db->fetchOne("SELECT credits FROM ad_value WHERE value='" . $advalue . "'");
		$adcost = $adcreditvalue * $creditallocate;

		if ($user_info['ad_credits'] < $adcost) {
			serveranswer(0, $lang['txt']['noenoughcredits']);
		}

		$db->query("UPDATE ads SET click_pack=click_pack+" . $creditallocate . ", status='Active' WHERE id=" . $adid);
		$db->query("UPDATE members SET ad_credits=ad_credits-" . $adcost . " WHERE id=" . $user_info['id']);
	}
	else {
		if ($input->p['class'] == "ptsu_offers") {
			if ($user_info['ptsu_credits'] < $creditallocate || $user_info['ptsu_credits'] == 0) {
				serveranswer(0, $lang['txt']['noenoughcredits']);
			}

			$advalue = $db->fetchOne("SELECT value FROM ptsu_offers WHERE id=" . $adid);
			$adcreditvalue = $db->fetchOne("SELECT credits FROM ptsu_value WHERE value='" . $advalue . "'");
			$adcost = $adcreditvalue * $creditallocate;

			if ($user_info['ptsu_credits'] < $adcost) {
				serveranswer(0, $lang['txt']['noenoughcredits']);
			}

			$db->query("UPDATE ptsu_offers SET credits=credits+" . $creditallocate . ", status='Active' WHERE id=" . $adid);
			$db->query("UPDATE members SET ptsu_credits=ptsu_credits-" . $adcost . " WHERE id=" . $user_info['id']);
		}
		else {
			if ($input->p['class'] == "banner_ads") {
				if ($user_info['banner_credits'] < $creditallocate || $user_info['banner_credits'] == 0) {
					serveranswer(0, $lang['txt']['noenoughcredits']);
				}

				$db->query("UPDATE banner_ads SET credits=credits+" . $creditallocate . ", status='Active' WHERE id=" . $adid);
				$db->query("UPDATE members SET banner_credits=banner_credits-" . $creditallocate . " WHERE id=" . $user_info['id']);
			}
			else {
				if ($input->p['class'] == "featured_ads") {
					if ($user_info['fads_credits'] < $creditallocate || $user_info['fads_credits'] == 0) {
						serveranswer(0, $lang['txt']['noenoughcredits']);
					}

					$db->query("UPDATE featured_ads SET credits=credits+" . $creditallocate . ", status='Active' WHERE id=" . $adid);
					$db->query("UPDATE members SET fads_credits=fads_credits-" . $creditallocate . " WHERE id=" . $user_info['id']);
				}
				else {
					if ($input->p['class'] == "featured_link") {
						if ($user_info['flink_credits'] < $creditallocate || $user_info['flink_credits'] == 0) {
							serveranswer(0, $lang['txt']['noenoughcredits']);
						}

						$monthvalue = $db->fetchOne("SELECT expires FROM featured_link WHERE id=" . $adid);
						$months = 60 * 60 * 24 * 30 * $creditallocate;

						if ($monthvalue < time()) {
							$adexpires = $months + time();
						}
						else {
							$adexpires = $monthvalue + $months;
						}

						$db->query("UPDATE featured_link SET expires=" . $adexpires . ", status='Active' WHERE id=" . $adid);
						$db->query("UPDATE members SET flink_credits=flink_credits-" . $creditallocate . " WHERE id=" . $user_info['id']);
					}
					else {
						if ($input->p['class'] == "login_ads") {
							if ($user_info['loginads_credits'] < $creditallocate || $user_info['loginads_credits'] == 0) {
								serveranswer(0, $lang['txt']['noenoughcredits']);
							}

							$dayvalue = $db->fetchOne("SELECT expires FROM login_ads WHERE id=" . $adid);
							$days = 60 * 60 * 24 * $creditallocate;

							if ($dayvalue < TIMENOW) {
								$adexpires = $days + TIMENOW;
							}
							else {
								$adexpires = $dayvalue + $days;
							}

							$db->query("UPDATE login_ads SET expires=" . $adexpires . ", status='Active' WHERE id=" . $adid);
							$db->query("UPDATE members SET loginads_credits=loginads_credits-" . $creditallocate . " WHERE id=" . $user_info['id']);
						}
					}
				}
			}
		}
	}

	serveranswer(1, "");
}
else {
	serveranswer(0, $lang['txt']['errcommand']);
}

$db->close();
exit();
?>