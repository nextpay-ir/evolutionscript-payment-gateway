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


if ($settings['rent_referrals'] != "yes") {
	header("location: index.php?view=account");
	$db->close();
	exit();
}

include SMARTYLOADER;
$mymembership = $db->fetchRow("SELECT * FROM membership WHERE id=" . $user_info['type']);

if ($user_info['rented_referrals'] <= 250) {
	$price = $mymembership['rent250'];
}
else {
	if (250 < $user_info['rented_referrals'] && $user_info['rented_referrals'] <= 500) {
		$price = $mymembership['rent500'];
	}
	else {
		if (500 < $user_info['rented_referrals'] && $user_info['rented_referrals'] <= 750) {
			$price = $mymembership['rent750'];
		}
		else {
			if (750 < $user_info['rented_referrals'] && $user_info['rented_referrals'] <= 1000) {
				$price = $mymembership['rent1000'];
			}
			else {
				if (1000 < $user_info['rented_referrals'] && $user_info['rented_referrals'] <= 1250) {
					$price = $mymembership['rent1250'];
				}
				else {
					if (1250 < $user_info['rented_referrals'] && $user_info['rented_referrals'] <= 1500) {
						$price = $mymembership['rent1500'];
					}
					else {
						if (1500 < $user_info['rented_referrals'] && $user_info['rented_referrals'] <= 1750) {
							$price = $mymembership['rent1750'];
						}
						else {
							if (1750 < $user_info['rented_referrals']) {
								$price = $mymembership['rentover'];
							}
						}
					}
				}
			}
		}
	}
}

$next_rent = $user_info['last_rent'] + $mymembership['rent_time'] * 60 * 60 * 24;

if (time() < $next_rent) {
	$show_error = "yes";
	$error = str_replace("%nextrent", date("jS F, Y (h:i A)", $next_rent), $lang['txt']['nextrent']);
}
else {
	if ($mymembership['rentedref_limit'] <= $user_info['rented_referrals'] && $mymembership['rentedref_limit'] != -1) {
		$show_error = "yes";
		$error = $lang['txt']['cannotrent'];
	}
	else {
		$ref_package = explode(",", $mymembership['rent_pack']);

		if ($settings['rentype'] == 2) {
			$addrentedsql = " AND status='Active'";
		}
		else {
			if ($settings['rentype'] == 3) {
				$addrentedsql = " AND ref1=0";
			}
			else {
				if ($settings['rentype'] == 4) {
					$addrentedsql = " AND status='Active' AND ref1=0";
				}
			}
		}

		$refs_available = rentedreferralsleft($user_info['id']);

		if ($refs_available < $ref_package[0]) {
			$show_error = "yes";
			$error = $lang['txt']['noreftorent'];
		}
		else {
			foreach ($ref_package as $val => $pack) {

				if ($refs_available < $pack) {
					array_splice($ref_package, $val);
					continue;
				}


				if ($mymembership['rentedref_limit'] < $user_info['rented_referrals'] + $pack && $mymembership['rentedref_limit'] != -1) {
					array_splice($ref_package, $val);
					continue;
				}
			}
		}
	}
}

$smarty->assign("show_error", $show_error);
$smarty->assign("error", $error);
$smarty->assign("ref_pack", $ref_package);
$smarty->assign("rent_price", $price);
$smarty->assign("file_name", "rent_referrals.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>