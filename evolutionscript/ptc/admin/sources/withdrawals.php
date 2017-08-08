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


if (!$admin->permissions['withdrawals']) {
	header("location: ./");
	exit();
}


if ($input->p['action']) {
	if ($settings['demo'] == "yes") {
		$error_msg = "This is not possible in demo version";
	}
	else {
		if ($input->p['sesion_id'] != $_SESSION['sesion_id']) {
			$error_msg = "Invalid token try again please";
		}
		else {
			if (is_array($input->p['mid'])) {
				foreach ($input->p['mid'] as $mid) {
					$list = $db->fetchRow("SELECT user_id, method, amount, fee, status FROM withdraw_history WHERE id=" . $mid);
					switch ($input->p['a']) {
						case "delete":
							if ($list['status'] == "Pending") {
								$db->delete("withdraw_history", "id=" . $mid);
								$db->query("UPDATE members SET pending_withdraw=pending_withdraw-" . $list['amount'] . " WHERE id=" . $list['user_id']);
							}
							else {
								$db->delete("withdraw_history", "id=" . $mid);
							}
							break;

						case "process":
							if ($list['status'] == "Pending") {
								$setupd = array("status" => "Completed", "date" => TIMENOW);
								$db->update("withdraw_history", $setupd, "id=" . $mid);
								$db->query("UPDATE members SET pending_withdraw=pending_withdraw-" . $list['amount'] . ", withdraw=withdraw+" . $list['amount'] . " WHERE id=" . $list['user_id']);
								$upd = $db->query("UPDATE gateways SET total_withdraw=total_withdraw+" . $list['amount'] . " WHERE id=" . $list['method']);
								$upd = $db->query("UPDATE statistics SET value=value+" . $list['amount'] . " WHERE field='cashout'");
							}
							break;

						case "cancel":
							if ($list['status'] == "Pending") {
								$db->query("UPDATE withdraw_history SET status='Cancelled' WHERE id=" . $mid);
								$db->query("UPDATE members SET pending_withdraw=pending_withdraw-" . $list['amount'] . ", cashout_times=cashout_times-1 WHERE id=" . $list['user_id']);
							}
							break;

						case "refund":
							if ($list['status'] == "Pending") {
								$db->delete("withdraw_history", "id=" . $mid);
								$db->query(("UPDATE members SET pending_withdraw=pending_withdraw-" . $list['amount'] . ", cashout_times=cashout_times-1, money=money+" . $list['amount'] . "+") . $list['fee'] . " WHERE id=" . $list['user_id']);
							}
					}
				}
			}
		}
	}
}


if ($input->p['action'] == "Export List") {
	$gquery = $db->query("SELECT id, option5 FROM gateways");

	while ($r = $db->fetch_array($gquery)) {
		$gateways[$r['id']] = $r['option5'];
	}


	if (is_array($input->p['mid'])) {
		foreach ($input->p['mid'] as $mid) {
			$details = $db->fetchRow("SELECT * FROM withdraw_history WHERE id=" . $mid);
			$username = $db->fetchOne("SELECT username FROM members WHERE id=" . $details['user_id']);

			if ($details['method'] == 1) {
				$replace_1 = array("%sitename%", "%username%");
				$replace_2 = array($settings['site_name'], $username);
				$note = str_replace($replace_1, $replace_2, $gateways[$details['method']]);
				$payzalist[] = $details['account'] . "," . $details['amount'] . "," . $note . "
";
				continue;
			}


			if ($details['method'] == 2) {
				$replace_1 = array("%sitename%", "%username%");
				$replace_2 = array($settings['site_name'], $username);
				$note = str_replace($replace_1, $replace_2, $gateways[$details['method']]);
				$amount = floor($details['amount'] * 100) / 100;
				$paypallist[] = ($details['account'] . "	") . $amount . "	USD	" . $details['user_id'] . "	" . $note . "
";
				continue;
			}


			if ($details['method'] == 3) {
				$replace_1 = array("%sitename%", "%username%");
				$replace_2 = array($settings['site_name'], $username);
				$note = str_replace($replace_1, $replace_2, $gateways[$details['method']]);
				$amount = floor($details['amount'] * 100) / 100;
				$libertylist[] = $details['account'] . ", " . $amount . ", not-private, " . $note . "
";
				continue;
			}
		}
	}


	if (empty($payzalist)) {
		$masspaylist .= "Payza List:<br>";
		$masspaylist .= "<textarea style='width:98%; height:100px'>";
		foreach ($payzalist as $k => $v) {
			$masspaylist .= $amount;
		}

		$masspaylist .= "</textarea>";
	}


	if (empty($paypallist)) {
		$masspaylist .= "<br>PayPal List (Save the content as txt):<br>";
		$masspaylist .= "<textarea style='width:98%; height:100px'>";
		foreach ($paypallist as $k => $v) {
			$masspaylist .= $amount;
		}

		$masspaylist .= "</textarea>";
	}


	if (empty($libertylist)) {
		$masspaylist .= "<br>Liberty Reserve List (This format works for Liberty Reserve Mass Payment Tool, you can download from LibertyReserve.com) :<br>";
		$masspaylist .= "<textarea style='width:98%; height:100px'>";
		foreach ($libertylist as $k => $v) {
			$masspaylist .= $amount;
		}

		$masspaylist .= "</textarea>";
	}
}


if ($input->r['do'] == "search") {
	$searchvars = array("username", "account", "method", "status", "from_date", "to_date");
	foreach ($searchvars as $k) {

		if ($input->r[$k]) {
			switch ($k) {
				case "username":
					$user_id = $db->fetchOne("SELECT id FROM members WHERE username='" . $input->rc['username'] . "'");
					$cond .= "user_id=" . $user_id . " AND ";
					break;

				case "from_date":
					$from_date = daterange($input->rc['from_date']);
					$cond .= "date >= " . $from_date[0] . " AND ";
					break;

				case "to_date":
					$to_date = daterange($input->rc['to_date']);
					$cond .= "date <= " . $to_date[1] . " AND ";
					break;

				default:
					$cond .= $k . "='" . $input->rc[$k] . "' AND ";
					break;
			}

			$adlink .= $k . "=" . $input->r[$k] . "&";
		}
	}


	if ($cond) {
		$cond = substr($cond, 0, -5);
	}


	if ($adlink) {
		$adlink = "do=search&" . $adlink;
	}
}

$gat_q = $db->query("SELECT id, name FROM gateways");
$gateway = array();

while ($r = $db->fetch_array($gat_q)) {
	$gateway[$r['id']] = $r['name'];
}

$mem_q = $db->query("SELECT id, name FROM membership");
$membership = array();

while ($r = $db->fetch_array($mem_q)) {
	$membership[$r['id']] = $r['name'];
}

$paginator = new Pagination("withdraw_history", $cond);
$paginator->setOrders("date", "DESC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=withdrawals&" . $adlink);
$q = $paginator->getQuery();
include SOURCES . "header.php";
echo "    <script>
    $(function() {
        $(\"#from_date\").datepicker({
            defaultDate: \"+1w\",
            numberOfMonths: 1,
            onClose: function(selectedDate) {
                $(\"#to_date\").datepicker(\"option\", \"minDate\", selectedDate);
            }
        });
        $(\"#to_date\").datepicker({
            defaultDate: \"+1w\",
            numberOfMonths: 1,
            onClose: function(selectedDate) {
                $(\"#from_date\").datepicker(\"option\", \"maxDate\", selectedDate);
            }
        });
    });
    </script>
<div class=\"site_title\">مدیریت تسویه حساب ها</div>
<div class=\"site_content\">
";

if ($error_msg) {
	echo "<div class=\"error_box\">";
	echo $error_msg;
	echo "</div>
";
}

echo "

<div class=\"ui-tabs ui-widget ui-widget-content ui-corner-all\">
   <ul class=\"ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all\">
     <li class=\"ui-state-default ui-corner-top ui-tabs-selected ui-state-active\"><a href=\"javascript:void(0);\" onclick=\"$('#search-content').slideToggle();\" style=\"cursor:pointer\">جستجو</a></li>
  </ul>
   <div class=\"ui-tabs-panel ui-widget-content ui-corner-bottom\" id=\"search-content\" ";
echo !$adlink ? "style=\"display:none\"" : "";
echo ">
<form method=\"post\">
            <input type=\"hidden\" name=\"do\" value=\"search\" />
            <table width=\"100%\" class=\"widget-tbl\">
                <tr>
                    <td align=\"right\">اعضا</td>
                    <td>
                    <input type=\"text\" name=\"username\" value=\"";
echo $input->r['username'];
echo "\" />
					</td>
                    <td align=\"right\">آیدی پرداخت</td>
                    <td><input type=\"text\" name=\"account\" value=\"";
echo $input->r['account'];
echo "\" /></td>
                </tr>
                <tr>
                    <td align=\"right\">دروازه پرداخت</td>
                    <td><select name=\"method\">
                    	<option value=\"\">همه</option>
						";
foreach ($gateway as $k => $v) {

	if ($input->r['method'] == $k) {
		echo "<option value=\"" . $k . "\" selected>" . $v . "</option>";
		continue;
	}

	echo "<option value=\"" . $k . "\">" . $v . "</option>";
}

echo "                    </select>
                    </td>
                    <td align=\"right\">وضعیت</td>
                    <td><select name=\"status\">
            <option value=\"\">همه</option>
            ";
$statusvalues = array("Pending", "Completed", "Cancelled");
foreach ($statusvalues as $v) {

	if ($v == $input->r['status']) {
		echo "<option value='" . $v . "' selected>" . $v . "</option>";
		continue;
	}

	echo "<option value='" . $v . "'>" . $v . "</option>";
}

echo "            </select>
                    </td>
                </tr>
                <tr>
                    <td align=\"right\">از تاریخ : </td>
                    <td><input type=\"text\" name=\"from_date\" id=\"hannandate\" value=\"";
echo $input->r['from_date'];
echo "\" /></td>
                    <td align=\"right\">تا تاریخ : </td>
                    <td><input type=\"text\" name=\"to_date\" id=\"hannandate2\" value=\"";
echo $input->r['to_date'];
echo "\" /></td>
                </tr>
                <tr>
                     <td colspan=\"4\" align=\"center\"><input type=\"submit\" name=\"send\" value=\"جستجو\">
                </tr>
            </table>
            </form>
   </div>
</div>



<form method=\"post\" action=\"";
echo $paginator->gotopage();
echo "\">
  <table width=\"100%\" class=\"widget-tbl\">
  	<tr class=\"titles\">
    	<td width=\"20\"><input type=\"checkbox\" id=\"checkall\"></td>
        <td>";
echo $paginator->linkorder("jdate", "تاریخ");
echo "</td>
        <td>";
echo $paginator->linkorder("user_id", "اعضا");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("amount", "مبلغ");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("method", "دروازه پرداخت");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("account", "شماره حساب/کارت");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("status", "وضعیت");
echo "</td>
        <td></td>
    </tr>
    ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	$member = $db->fetchRow("SELECT * FROM members WHERE id=" . $r['user_id']);
	$username = "<a href=\"./?view=members&edit=" . $r['user_id'] . "\">" . $member['username'] . "</a>";
	echo "  	<tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
    	<td align=\"center\"><input type=\"checkbox\" name=\"mid[]\" value=\"";
	echo $r['id'];
	echo "\" class=\"checkall\" /></td>
        <td>";
	echo jdate("d F Y ساعت h:i A", $r['date']);
	echo "</td>
        <td>";
	echo $username;
	echo "</td>
        <td align=\"center\">
			<span style=\"color:green\">";
	echo $r['amount'];
	echo "</span>
        </td>
        <td align=\"center\">
        <img src=\"../images/proofs/";
	echo $r['method'];
	echo ".gif\">
        </td>
        <td align=\"center\">
        ";
	echo $r['account'];
	echo "        </td>

        <td align=\"center\"><span style=\"color:
        ";
	switch ($r['status']) {
		case "Completed":
			echo "#009900";
			break;

		case "Pending":
			echo "orange";
			break;

		case "Cancelled":
			echo "#990000";
			break;

		default:
			echo "#996600";
			break;
	}

	echo "        \">";
	echo $r['status'];
	echo "</span></td>
<td align=\"center\"><a href=\"javascript:void(0);\" onClick=\"openWindows('<span style=\'font-weight:normal\'>Member:</span> ";
	echo $member['username'];
	echo "', 'info-";
	echo $r['id'];
	echo "');\"><img src=\"./css/images/info.png\" title=\"More info\" border=\"0\" /></a>
<div id=\"info-";
	echo $r['id'];
	echo "\" style=\"display:none\">
<table width=\"100%\" class=\"widget-tbl\">
    <tr>
        <td align=\"right\" width=\"25%\">Fullname:</td>
        <td width=\"25%\"><strong>";
	echo $member['fullname'];
	echo "</strong></td>
        <td align=\"right\" width=\"25%\">Country:</td>
        <td width=\"25%\" style=\"color:#000099\">";
	echo $member['country'];
	echo "</td>
    </tr>
    <tr>
        <td align=\"right\">پلن حساب کاربری</td>
        <td style=\"color:green\">";
	echo $membership[$member['type']];
	echo "</td>
        <td align=\"right\">مجموع تسویه حساب</td>
        <td style=\"color:#990000\">";
	echo $member['withdraw'];
	echo "</td>
    </tr>
    <tr>
        <td align=\"right\">درآمد</td>
        <td style=\"color:orange\">";
	echo $member['money'];
	echo "</td>
        <td align=\"right\">موجودی شارژ حساب</td>
        <td style=\"color:green\">";
	echo $member['purchase_balance'];
	echo "</td>
    </tr>
    ";
	$totaldep = $db->fetchOne("SELECT SUM(amount) FROM deposit_history WHERE user_id=" . $r['user_id']);
	$totaldep = ($totaldep == "" ? "0.00" : $totaldep);
	$totalpur = $db->fetchOne("SELECT SUM(price) FROM order_history WHERE user_id=" . $r['user_id'] . " AND type!='purchase_balance'");
	$totalpur = ($totalpur == "" ? "0.00" : $totalpur);
	echo "    <tr>
        <td align=\"right\">مجموع شارژ حساب</td>
        <td style=\"color:green\">";
	echo $totaldep;
	echo "</td>
        <td align=\"right\">مجموع پرداخت ها</td>
        <td style=\"color:orange\">";
	echo $totalpur;
	echo "</td>
    </tr>
    <tr>
        <td align=\"right\">تعداد باز تسویه حساب</td>
        <td style=\"color:green\">";
	echo $member['cashout_times'];
	echo "</td>
        <td align=\"right\">مجموع کلیکها</td>
        <td style=\"color:#990000\">";
	echo $member['clicks'];
	echo "</td>
    </tr>
    <tr>
        <td align=\"right\">زیرمجموعه ها</td>
        <td style=\"color:green\">";
	echo $member['referrals'];
	echo "</td>
        <td align=\"right\">زیرمجموعه های اجاره ای</td>
        <td style=\"color:#990000\">";
	echo $member['rented_referrals'];
	echo "</td>
    </tr>
    <tr>
        <td align=\"right\">تاریخ عضویت</td>
        <td style=\"color:#000099\">";
	echo jdate("d F Y ساعت h:i A", $member['signup']);
	echo "</td>
        <td align=\"right\">تاریخ آخرین ورود</td>
        <td style=\"color:#000099\">";
	echo jdate("d F Y ساعت h:i A", $member['last_login']);
	echo "</td>
    </tr>
    <tr>
        <td align=\"right\">یادداشت مدیر</td>
        <td colspan=\"3\" style=\"color:#990033\">";
	echo $member['adminnotes'];
	echo "</td>
    </tr>
</table>
</div>
        </td>
    </tr>
    ";
}


if ($paginator->totalResults() == 0) {
	echo "    <tr>
    	<td colspan=\"8\" align=\"center\">موردی یافت نشد</td>
    </tr>
    ";
}

echo "  </table>
    <div style=\"margin-top:10px\">
    <input type=\"button\" value=\"&larr; صفحه قبل\" ";
echo ($paginator->totalPages() == 1 || $paginator->getPage() == 1) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->prevpage() . "';\";";
echo " />

    <input type=\"button\" value=\"صفحه بعد &rarr;\" ";
echo ($paginator->totalPages() == 0 || $paginator->totalPages() == $paginator->getPage()) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->nextpage() . "';\";";
echo " />
    	";

if (1 < $paginator->totalPages()) {
	echo "        <div style=\"float:right\">
        Jump to page:
        <select name=\"p\" style=\"min-width:inherit;\" id=\"pagid\" onchange=\"gotopage(this.value)\">
            ";
	$i = 1;

	while ($i <= $paginator->totalPages()) {
		if ($i == $paginator->getPage()) {
			echo "<option selected value=\"" . $paginator->gotopage($i) . "\">" . $i . "</option>";
		}
		else {
			echo "<option value=\"" . $paginator->gotopage($i) . "\">" . $i . "</option>";
		}

		++$i;
	}

	echo "        </select>
        <script type=\"text/javascript\">
			function gotopage(pageid){
				location.href=pageid;
			}
		</script>
        </div>
        <div class=\"clear\"></div>
        ";
}

echo "    </div>

	";

if (0 < $paginator->totalPages()) {
	echo "    <div class=\"widget-title\" style=\"margin-top:5px\">اقدام</div>
        <div class=\"widget-content\">
        	<select name=\"a\">
            	<option value=\"\">یک مورد انتخاب کنید</option>
                <option value=\"delete\">حذف غیرقابل برگشت</option>
                <option value=\"process\">تنظیم بعنوان کامل شده</option>
                <option value=\"cancel\">تنظیم بعنوان کنسل شده</option>
                <option value=\"refund\">نادیده گرفت درخواست و برگردانندن موجودی به حساب</option>
            </select>
            <input type=\"submit\" name=\"action\" value=\"ثبت\" />
        </div>
	<fieldset>
		<legend>Export Pending Withdrawal to MassPay Format</legend>
		<div style=\"padding-bottom:5px\">This tool is available for PayPal, Payza and Liberty Reserve</div>
			<input type=\"submit\" name=\"action\" value=\"Export List\" class=\"buttonblue\">
	</fieldset>
    ";
}

echo "
<input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
</form>


    ";

if ($masspaylist) {
	echo "<div class=\"widget-title\">Mass pay list</div>";
	echo "<div class=\"widget-content\">";
	echo $masspaylist;
	echo "</div>";
}

echo "</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>