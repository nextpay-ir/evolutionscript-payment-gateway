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


if (!$admin->permissions['ptsuoffers_manager']) {
	header("location: ./");
	exit();
}


if ($input->g['new_ptsu']) {
	include SOURCES . "new_ptsu.php";
	exit();
}


if (is_numeric($input->g['edit'])) {
	$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM ptsu_offers WHERE id=" . $input->gc['edit']);

	if ($chk != 0) {
		include SOURCES . "edit_ptsu.php";
		exit();
	}
}


if ($input->p['action']) {
	if ($settings['demo'] == "yes") {
		header("location: ./?view=manageptc&msg=this_is_demo");
		exit();
	}


	if (is_array($input->p['mid'])) {
		foreach ($input->p['mid'] as $mid) {
			switch ($input->p['a']) {
				case "delete":
					$db->delete("ptsu_offers", "id=" . $mid);
					$db->delete("ptsu_requests", "ptsu_id=" . $mid);
					break;

				case "unhook":
					$addetails = $db->fetchRow("SELECT user_id, credits, value FROM ptsu_offers WHERE id=" . $mid);
					$adratio = $db->fetchOne("SELECT credits FROM ptsu_value WHERE value='" . $addetails['value'] . "'");
					$credits2refund = $addetails['credits'] * $adratio;
					$setupd = array("credits" => "0");
					$db->update("ptsu_offers", $setupd, "id=" . $mid);

					if ($addetails['user_id'] != 0) {
						$db->query("UPDATE members SET ptsu_credits=ptsu_credits+" . $credits2refund . " WHERE id=" . $addetails['user_id']);
					}

					break;

				case "selfsponsored":
					$setupd = array("user_id" => "0");
					$db->update("ptsu_offers", $setupd, "id=" . $mid);
					$setupd = array("owner_id" => "0");
					$db->update("ptsu_requests", $setupd, "ptsu_id=" . $mid);
					break;

				default:
					$setupd = array("status" => $input->p['a']);
					$db->update("ptsu_offers", $setupd, "id=" . $mid);
					break;				
			}
		}
	}
}


if ($input->r['do'] == "search") {
	$searchvars = array("owner", "username", "status", "url", "title", "ad");
	foreach ($searchvars as $k) {

		if ($input->r[$k]) {
			switch ($k) {
				case "owner":
					if ($input->rc[$k] == "selfsponsored") {
						$cond .= "user_id=0 AND ";
					}
					else {
						if ($input->rc[$k] == "single" && $input->rc['username'] != "") {
							$user_id = $db->fetchOne("SELECT id FROM members WHERE username='" . $input->rc['username'] . "'");
							$cond .= "user_id=" . $user_id . " AND ";
						}
					}
					break;

				case "username":
					break;

				case "url":
					$cond .= ("(") . $k . " LIKE '%" . $input->rc[$k] . "%') AND ";
					break;

				case "title":
					$cond .= ("(") . $k . " LIKE '%" . $input->rc[$k] . "%') AND ";
					break;

				case "ad":
					$cond .= ("(") . $k . " LIKE '%" . $input->rc[$k] . "%') AND ";
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

$paginator = new Pagination("ptsu_offers", $cond);
$paginator->setOrders("id", "DESC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=manageptsu&" . $adlink);
$q = $paginator->getQuery();
include SOURCES . "header.php";
echo "<script>
$(function()
{
	$(\".datepicker\").datepicker({ minDate: \"-2Y\" });

});
</script>
<div class=\"site_title\">مدیریت پیشنهاد پرداخت به ازای ثبت نام</div>
<div class=\"site_content\">

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
                    <td align=\"right\">مالک</td>
                    <td>
                    <select name=\"owner\" onchange=\"selectowner();\" id=\"owner\">
                        <option value=\"\">همه اعضا</option>
                        <option value=\"single\" ";
echo $input->r['owner'] == "single" ? "selected" : "";
echo ">کاربر</option>
                        <option value=\"selfsponsored\" ";
echo $input->r['owner'] == "selfsponsored" ? "selected" : "";
echo ">حمایت شده از جانب مدیریت</option>
                    </select>
                    <input type=\"text\" name=\"username\" value=\"";
echo $input->r['username'];
echo "\" id=\"username\" ";
echo $input->r['owner'] == "single" ? "" : "style=\"display:none\"";
echo " />
					</td>
                    <td align=\"right\">وضعیت</td>
                    <td>            <select name=\"status\">
            <option value=\"\">همه</option>
            ";
$statusvalues = array("Active", "Inactive", "Pending", "Expired", "Paused");
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
                    <td align=\"right\">عنوان تبلیغ</td>
                    <td><input type=\"text\" name=\"title\" value=\"";
echo $input->r['title'];
echo "\" /></td>
                    <td align=\"right\">آدرس</td>
                    <td colspan=\"3\"><input type=\"text\" name=\"url\" value=\"";
echo $input->r['url'];
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
echo $paginator->linkorder("id", "ID");
echo "</td>
        <td>";
echo $paginator->linkorder("user_id", "مالک");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("title", "تبلیغ");
echo " / ";
echo $paginator->linkorder("url", "آدرس");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("credits", "اعتبار کم شده");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("approved", "تایید شده");
echo " / ";
echo $paginator->linkorder("pending", "منتظر");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("value", "مقدار");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("status", "وضعیت");
echo "</td>
        <td></td>
    </tr>
    ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	$username = ($r['user_id'] == 0 ? "<span style=\"color:green\">مدیریت</span>" : "<a href=\"./?view=members&edit=" . $r['user_id'] . "\">" . $db->fetchOne("SELECT username FROM members WHERE id=" . $r['user_id']) . "</a>");
	echo "  	<tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
    	<td align=\"center\"><input type=\"checkbox\" name=\"mid[]\" value=\"";
	echo $r['id'];
	echo "\" class=\"checkall\" /></td>
        <td>";
	echo $r['id'];
	echo "</td>
        <td>";
	echo $username;
	echo "</td>
        <td>";

	if (35 < strlen($r['title'])) {
		echo substr($r['title'], 0, 35) . "...";
	}
	else {
		echo $r['title'];
	}

	echo "<br><span style=\"font-size:11px\">
        <a href=\"";
	echo $r['url'];
	echo "\" target=\"_blank\" style=\"color:#CC66CC\">
		";

	if (35 < strlen($r['url'])) {
		echo substr($r['url'], 0, 35) . "...";
	}
	else {
		echo $r['url'];
	}

	echo "        </a>
		</span></td>
        <td align=\"center\">
			<span style=\"color:#000099\">";
	echo $r['credits'];
	echo "</span>
        </td>
        <td align=\"center\">
			<span style=\"color:green\">";
	echo $r['approved'];
	echo "</span> / <span style=\"color:#990000\">";
	echo $r['pending'];
	echo "</span>
        </td>
        <td align=\"center\">
			<span style=\"color:#000099\">";
	echo $r['value'];
	echo "</span>
        </td>
        <td align=\"center\"><span style=\"color:
        ";
	switch ($r['status']) {
		case "Active":
			echo "#009900";
			break;

		case "Inactive":
			echo "orange";
			break;

		case "Expired":
			echo "#990000";
			break;

		default:
			echo "#996600";
			break;
	}

	echo "        \">";
	echo $r['status'];
	echo "</span></td>
        <td align=\"center\">
        <a href=\"./?view=manageptsu&edit=";
	echo $r['id'];
	echo "\"><img src=\"./css/images/edit.png\" border=\"0\" title=\"Edit PTSU\" /></a>

        <a href=\"./?view=ptsu_pending&do=search&ptsu_id=";
	echo $r['id'];
	echo "\"><img src=\"./css/images/clock.png\" border=\"0\" title=\"مشاهده درخواست های منتظر\" /></a>
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
            	<option value=\"\">یکی را انتخاب کنید</option>
                <option value=\"delete\">حذف غیرقابل بازگشت</option>
                <option value=\"unhook\">پاکسازی اعتبار</option>
                <option value=\"Active\">Active</option>
                <option value=\"Inactive\">Inactive</option>
                <option value=\"Expired\">Expired</option>
                <option value=\"Paused\">Paused</option>
                <option value=\"selfsponsored\">حمایت از جانب مدیریت</option>
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