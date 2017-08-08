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

include SMARTYLOADER;

if ($_GET['autopay'] == "on") {
	$data = array("autopay" => "yes");
	$upd = $db->update("members", $data, "id=" . $user_info['id']);
	header("location: index.php?view=account&page=rented_referrals");
	$db->close();
	exit();
}
else {
	if ($_GET['autopay'] == "off") {
		$data = array("autopay" => "no");
		$upd = $db->update("members", $data, "id=" . $user_info['id']);
		header("location: index.php?view=account&page=rented_referrals");
		$db->close();
		exit();
	}
}

$recycle_price = $db->fetchOne("SELECT recycle_cost FROM membership WHERE id=" . $user_info['type']);
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

$smarty->assign("renew_price", $price);
$smarty->assign("recycle_price", $recycle_price);
$q = $db->query("SELECT * FROM rent_discount ORDER BY days ASC");

while ($row = $db->fetch_array($q)) {
	$refdiscounts[] = $row;
}

$smarty->assign("ref_discount", $refdiscounts);

if ($input->p['do'] == "rentact") {
	verifyajax();

	if (!empty($input->p['action']) && is_array($input->p['ref'])) {
		$total_ref = count($input->p['ref']);

		if ($input->p['action'] == "recycle") {
			$countref = rentedreferralsleft($user_info['id']);
			$total_pay = $total_ref * $recycle_price;

			if ($user_info['purchase_balance'] < $total_pay) {
				serveranswer(0, $lang['txt']['nofoundspb']);
			}


			if ($countref < $total_ref) {
				serveranswer(0, $lang['txt']['norefav']);
			}

			foreach ($_POST['ref'] as $i => $refid) {
				$verifyuser = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE rented=" . $user_info['id'] . " AND id=" . $refid);

				if ($verifyuser != 0) {
					ebjdjjghee($user_info['id'], $refid);
					$upd = $db->query("UPDATE members SET purchase_balance=purchase_balance-" . $recycle_price . " WHERE id=" . $user_info['id']);
					continue;
				}
			}

			serveranswer(1, "location.href=location.href;");
		}
		else {
			if (is_numeric($_POST['action'])) {
				$discountid = $input->pc['action'];
				$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM rent_discount WHERE id=" . $discountid);

				if ($verify == 0) {
					serveranswer(0, $lang['txt']['selectdaysextend']);
				}

				$discount = $db->fetchRow("SELECT * FROM rent_discount WHERE id=" . $discountid);
				$daysmultiplier = floor($discount['days'] / 30 * 100) / 100;
				$price = $daysmultiplier * $price;
				$price = $price - $price * $discount['discount'] / 100;
				$total_pay = $total_ref * $price;

				if ($user_info['purchase_balance'] < $total_pay) {
					serveranswer(0, $lang['txt']['nofoundspb']);
				}

				foreach ($input->p['ref'] as $i => $refid) {
					$verifyuser = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE rented=" . $user_info['id'] . " AND id=" . $refid);

					if ($verifyuser != 0) {
						$rented_expires = $db->fetchOne("SELECT rented_expires FROM members WHERE id=" . $refid);
						$onemonth = $rented_expires + 60 * 60 * 24 * $discount['days'];
						$data = array("rented_expires" => $onemonth);
						$upd = $db->update("members", $data, "id=" . $refid);
						$upd = $db->query("UPDATE members SET purchase_balance=purchase_balance-" . $price . " WHERE id=" . $user_info['id']);
						continue;
					}
				}

				serveranswer(1, "location.href=location.href;");
			}
			else {
				serveranswer(0, $lang['txt']['invalidrequest']);
			}
		}
	}
	else {
		serveranswer(0, $lang['txt']['invalidrequest']);
	}
}

include INCLUDES . "class_pagination.php";
$orderby = $input->gc['orderby'];
$avgq = "(rented_clicks/((" . TIMENOW . " -rented_time)/86400))";

if ($orderby == "rented_avg") {
	$orderby = $items;
}

$allowed = array("id", "rented_time", "rented_expires", "rented_lastclick", "rented_clicks", "rented_earned", $avgq);
$paginator = new Pagination("members", "rented=" . $user_info['id']);
$paginator->setMaxResult($settings['max_result_page']);
$paginator->setOrders("rented_expires", "ASC");
$paginator->setPage($input->gc['p']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($orderby, $input->gc['sortby']);
$paginator->setLink("./?view=account&page=rented_referrals&");
$q = $paginator->getQuery();

while ($list = $db->fetch_array($q)) {
	$list['days_left'] = round(($list['rented_expires'] - time()) / 86400);
	$today_clicks = (TIMENOW - $list['rented_time']) / 86400;

	if (1 <= $today_clicks) {
		$list['avarage'] = number_format($list['rented_clicks'] / $today_clicks, 3);
	}
	else {
		$list['avarage'] = $list['rented_clicks'];
	}

	$items[] = $list;
}

$smarty->assign("paginator", $paginator);
$smarty->assign("thelist", $items);
unset($items);
$max_results = $settings['max_result_page'];
$smarty->assign("file_name", "rented_referrals.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>