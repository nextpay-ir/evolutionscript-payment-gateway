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


if (!$admin->permissions['orders']) {
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
					$order = $db->fetchRow("SELECT * FROM order_history WHERE id=" . $mid);
					switch ($input->p['a']) {
						case "delete":
							$db->delete("order_history", "id=" . $mid);
							break;

						case "process":
							if ($order['status'] != "Completed") {
								include MODULESPATH . "buyoptions/admin/" . $order['type'] . ".php";

								if (!$error_msg) {
									if ($order['ref'] != 0) {
										$upd = $db->query("UPDATE members SET money=money+" . $order['ref_comission'] . ", refearnings=refearnings+" . $order['ref_comission'] . " WHERE id=" . $order['ref']);
									}

									$db->query("UPDATE order_history SET status='Completed' WHERE id=" . $order['id']);
								}
							}
							break;

						case "cancel":
							if ($order['status'] == "Pending") {
								$db->query("UPDATE order_history SET status='Cancelled' WHERE id=" . $mid);
							}

							break;

						case "refund":
							if ($order['status'] == "Pending") {
								$db->query("UPDATE members SET purchase_balance=purchase_balance+" . $order['price'] . " WHERE id=" . $order['user_id']);
								$db->query("UPDATE order_history SET status='Cancelled' WHERE id=" . $mid);
							}
					}
				}
			}
		}
	}
}


if ($input->r['do'] == "search") {
	$searchvars = array("username", "type", "name", "status", "from_date", "to_date");
	foreach ($searchvars as $k) {

		if ($input->r[$k]) {
			switch ($k) {
				case "username":
					$user_id = $db->fetchOne("SELECT id FROM members WHERE username='" . $input->rc['username'] . "'");
					$cond .= "user_id=" . $user_id . " AND ";
					break;

				case "name":
					$cond .= ("(") . $k . " LIKE '%" . $input->rc[$k] . "%') AND ";
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

$paginator = new Pagination("order_history", $cond);
$paginator->setOrders("date", "DESC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=orders&" . $adlink);
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
<div class=\"site_title\">Manage Orders</div>
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
                    <td align=\"right\">عضو</td>
                    <td>
                    <input type=\"text\" name=\"username\" value=\"";
echo $input->r['username'];
echo "\" />
					</td>
                    <td align=\"right\">نوع آگهی</td>
                    <td>            <select name=\"type\">
            <option value=\"\">همه</option>
            ";
$ordertypes = array("ptc_credits" => "PTC credits", "ptsu_credits" => "PTSU credits", "fad_credits" => "Featured Ads credits", "bannerad_credits" => "Banner Ads credits", "flink_credits" => "Featured Link credits", "membership" => "Membership", "referrals" => "Referrals Purchased", "rent_referrals" => "Rented Referral", "specialpack" => "Special Packs Purchased", "purchase_balance" => "Transfer to Purchase Balance");
foreach ($ordertypes as $k => $v) {

	if ($k == $input->r['type']) {
		echo "<option value='" . $k . "' selected>" . $v . "</option>";
		continue;
	}

	echo "<option value='" . $k . "'>" . $v . "</option>";
}

$opt_query = $db->query("SELECT name FROM buyoptions");

while ($r = $db->fetch_array($opt_query)) {
	if (!array_key_exists($r['name'], $ordertypes)) {
		if ($r['name'] == $input->r['type']) {
			echo "<option value='" . $r['name'] . "' selected>" . ucwords(str_replace("_", " ", $r['name'])) . "</option>";
		}

		echo "<option value='" . $r['name'] . "'>" . ucwords(str_replace("_", " ", $r['name'])) . "</option>";
	}
}

echo "            </select>
                    </td>
                </tr>
                <tr>
                    <td align=\"right\">نام آگهی</td>
                    <td>
                    <input type=\"text\" name=\"name\" value=\"";
echo $input->r['name'];
echo "\" />
					</td>
                    <td align=\"right\">وضعیت</td>
                    <td>            <select name=\"status\">
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
                    <td align=\"right\">از تاریخ</td>
                    <td><input type=\"text\" name=\"from_date\" value=\"";
echo $input->r['from_date'];
echo "\" id=\"hannandate\" /></td>
                    <td align=\"right\">تا تاریخ</td>
                    <td><input type=\"text\" name=\"to_date\" value=\"";
echo $input->r['to_date'];
echo "\" id=\"hannandate2\" /></td>
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
<input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
  <table width=\"100%\" class=\"widget-tbl\">
  	<tr class=\"titles\">
    	<td width=\"20\"><input type=\"checkbox\" id=\"checkall\"></td>
        <td>";
echo $paginator->linkorder("jdate", "تاریخ");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("user_id", "عضو");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("type", "آگهی");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("price", "قیمت به تومان");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("status", "وضعیت");
echo "</td>
    </tr>
    ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	$username = "<a href=\"./?view=members&edit=" . $r['user_id'] . "\">" . $db->fetchOne("SELECT username FROM members WHERE id=" . $r['user_id']) . "</a>";
	echo "  	<tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
    	<td align=\"center\"><input type=\"checkbox\" name=\"mid[]\" value=\"";
	echo $r['id'];
	echo "\" class=\"checkall\" /></td>
        <td>";
	echo jdate("d F Y ساعت h:i A", $r['date']);
	echo "</td>
        <td align=\"center\">";
	echo $username;
	echo "</td>
        <td><span style=\"color:#000099\">";
	echo $r['name'];
	echo "</span>
        </td>
        <td align=\"center\">
			<span style=\"color:green\">";
	echo $r['price'];
	echo "</span>
        </td>
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
            	<option value=\"\">یک مورد را انتخاب نمایید</option>
                <option value=\"delete\">حذف</option>
                <option value=\"process\">تایید</option>
                <option value=\"cancel\">کنسل</option>
                <option value=\"refund\">کنسل و پس فرستادن</option>
            </select>
            <input type=\"submit\" name=\"action\" value=\"ثبت\" />
        </div>
    ";
}

echo "</form>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>