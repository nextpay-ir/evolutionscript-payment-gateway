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


if (!$admin->permissions['support_manager']) {
	header("location: ./");
	exit();
}

$ticketstatus = array(1 => "Open", 2 => "Answered", 3 => "Awaiting Reply", 4 => "Closed");
$statuscolours = array(1 => "#779500", 2 => "#000000", 3 => "#ff6600", 4 => "#888888");

if (is_numeric($input->g['showt'])) {
	$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM helpdesk_ticket WHERE id=" . $input->gc['showt']);

	if ($chk != 0) {
		include SOURCES . "support_ticket.php";
		exit();
	}
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
					switch ($input->p['a']) {
						case "delete":
							$db->delete("helpdesk_replies", "ticket_id=" . $mid);
							$db->delete("helpdesk_ticket", "id=" . $mid);
							break;

						case "close":
							$data = array("status" => 4);
							$db->update("helpdesk_ticket", $data, "id=" . $mid);
							break;

						case "open":
							$data = array("status" => 1);
							$db->update("helpdesk_ticket", $data, "id=" . $mid);
					}
				}
			}
		}
	}
}

$department = array();
$q = $db->query("SELECT * FROM helpdesk_department");

while ($r = $db->fetch_array($q)) {
	$department[$r['id']] = $r['name'];
}


if ($input->r['do'] == "search") {
	$searchvars = array("ticket", "subject", "department", "status", "from_date", "to_date");
	foreach ($searchvars as $k) {

		if ($input->r[$k]) {
			switch ($k) {
				case "subject":
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

$paginator = new Pagination("helpdesk_ticket", $cond);
$paginator->setOrders("last_update", "DESC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=support&" . $adlink);
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
<div class=\"site_title\">مدیریت کاربران</div>
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
                    <td align=\"right\">آیدی تیکت</td>
                    <td><input type=\"text\" name=\"ticket\" value=\"";
echo $input->r['ticket'];
echo "\" /></td>
                    <td align=\"right\">موضوع</td>
                    <td><input type=\"text\" name=\"subject\" value=\"";
echo $input->r['subject'];
echo "\" /></td>
                </tr>
                <tr>
                    <td align=\"right\">دپارتمان (بخش)</td>
                    <td>
                    <select name=\"department\">
                    	<option value=\"\">همه</option>
                        ";
foreach ($department as $k => $v) {

	if ($input->r['department'] == $k) {
		echo "<option value=\"" . $k . "\" selected>" . $v . "</option>";
		continue;
	}

	echo "<option value=\"" . $k . "\">" . $v . "</option>";
}

echo "                    </select>
                    </td>
                    <td align=\"right\">وضعیت</td>
                    <td>
            <select name=\"status\">
            <option value=\"\">همه</option>
            ";
foreach ($ticketstatus as $k => $v) {

	if ($k == $input->r['status']) {
		echo "<option value='" . $k . "' selected>" . $v . "</option>";
		continue;
	}

	echo "<option value='" . $k . "'>" . $v . "</option>";
}

echo "            </select>
                    </td>
                </tr>
                <tr>
                    <td align=\"right\">از تاریخ : </td>
                    <td><input type=\"text\" name=\"from_date\" value=\"";
echo $input->r['from_date'];
echo "\" id=\"hannandate\" /></td>
                    <td align=\"right\">تا تاریخ :</td>
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
  <table width=\"100%\" class=\"widget-tbl\">
  	<tr class=\"titles\">
    	<td width=\"20\"><input type=\"checkbox\" id=\"checkall\"></td>
        <td>";
echo $paginator->linkorder("jdate", "تاریخ اضافه شدن");
echo "</td>
        <td>";
echo $paginator->linkorder("ticket", "آی دی تیکت");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("department", "دپارتمان (پخش)");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("subject", "موضوع");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("name", "ارسال کننده");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("status", "وضعیت");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("last_update", "آخرین آپدیت");
echo "</td>
    </tr>
    ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "  	<tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
    	<td align=\"center\"><input type=\"checkbox\" name=\"mid[]\" value=\"";
	echo $r['id'];
	echo "\" class=\"checkall\" /></td>
        <td>";
	echo jdate("d F Y ساعت h:i A", $r['date']);
	echo "</td>
        <td><a href=\"./?view=support&showt=";
	echo $r['id'];
	echo "\">";
	echo $r['ticket'];
	echo "</a></td>
        <td align=\"center\">
			<span style=\"color:#000099\">";
	echo $department[$r['department']];
	echo "</span>
        </td>
        <td>
			";
	echo $r['subject'];
	echo "        </td>
        <td align=\"center\">
			<span style=\"color:#333333\">";
	echo $r['name'];
	echo "</span>
        </td>
        <td align=\"center\"><span style=\"color:";
	echo $statuscolours[$r['status']];
	echo "\">";
	echo $ticketstatus[$r['status']];
	echo "</span></td>
        <td align=\"center\">";
	echo datepass($r['last_update']);
	echo "</td>
    </tr>
    ";
}


if ($paginator->totalResults() == 0) {
	echo "    <tr>
    	<td colspan=\"10\" align=\"center\">موردی یافت نشد</td>
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
            	<option value=\"\">یک مورد را انتخاب کنید</option>
            	<option value=\"delete\">حذف</option>
                <option value=\"close\">بسته</option>
                <option value=\"open\">باز</option>
            </select>
            <input type=\"submit\" name=\"action\" value=\"ذخیره\" />
        </div>
    ";
}

echo "    <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
</form>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>