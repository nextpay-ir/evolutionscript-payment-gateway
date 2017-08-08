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
	serveranswer(0, "You can rent referrals again on <strong>" . date("jS F, Y (h:i A)", $next_rent) . "</strong>");
}


if ($mymembership['rentedref_limit'] < $user_info['rented_referrals'] && $mymembership['rentedref_limit'] != -1) {
	serveranswer(0, "You can not rent more referrals, you have exceeded the permissible limit of rented referrals.");
}

$ref_package = explode(",", $mymembership['rent_pack']);
$refs_available = rentedreferralsleft($user_info['id']);

if ($refs_available < $item) {
	serveranswer(0, "There are not referrals available to rent.");
}


if (!in_array($item, $ref_package)) {
	serveranswer(0, $lang['txt']['invaliditem']);
}


if ($mymembership['rentedref_limit'] < $user_info['rented_referrals'] + $item && $mymembership['rentedref_limit'] != -1) {
	serveranswer(0, "You can not rent more referrals, you have exceeded the permissible limit of rented referrals.");
}

$product['price'] = number_format($price * $item, 5, ".", "");
$product['id'] = $item;

if ($user_info['purchase_balance'] < $product['price']) {
	serveranswer(0, $lang['txt']['nofoundspb']);
}

$descr = str_replace("%descr", $item, $buyoptions['descr']);
$todayis = time();
$upd = $db->query("UPDATE members SET last_rent='" . $todayis . "' WHERE id=" . $user_info['id']);
?>