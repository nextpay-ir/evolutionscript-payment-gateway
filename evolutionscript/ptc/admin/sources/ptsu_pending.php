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

switch ($input->p['shortaction']) {
	case "approve":
		$mid = $input->pc['ptsureq'];
		$acc2pay = $db->fetchRow("SELECT user_id, value, ptsu_id FROM ptsu_requests WHERE id='" . $mid . "'");
		$upd = $db->query("UPDATE ptsu_requests SET status='Completed' WHERE id=" . $mid);
		$upd = $db->query("UPDATE members SET money=money+" . $acc2pay['value'] . " WHERE id=" . $acc2pay['user_id']);
		$upd = $db->query("UPDATE ptsu_offers SET approved=approved+1, pending=pending-1 WHERE id=" . $acc2pay['ptsu_id']);
		$membershiptype = $db->fetchOne("SELECT type FROM members WHERE id=" . $acc2pay['user_id']);
		$membership = $db->fetchRow("SELECT point_enable, point_ptsu FROM membership WHERE id=" . $membershiptype);

		if ($membership['point_enable'] == 1) {
			addpoints($acc2pay['user_id'], $membership['point_ptsu']);
		}
		break;

	case "reject":
		$mid = $input->pc['ptsureq'];
		$datastored = array("status" => "Cancelled");
		$upd = $db->update("ptsu_requests", $datastored, "id=" . $mid);
		$req = $db->fetchRow("SELECT ptsu_id, user_id FROM ptsu_requests WHERE id=" . $mid);
		$upd = $db->query("UPDATE ptsu_offers SET credits=credits+1, pending=pending-1 WHERE id=" . $req['ptsu_id']);
		$upd = $db->query("UPDATE members SET ptsu_denied=ptsu_denied+1 WHERE id=" . $req['user_id']);
		break;

	case "delete":
		$mid = $input->pc['ptsureq'];
		$ptsudetails = $db->fetchOne("SELECT ptsu_id, status FROM ptsu_requests WHERE id=" . $mid);
		$db->delete("ptsu_requests", "id=" . $mid);
		$db->query("UPDATE ptsu_offers SET pending=pending-1, credits=credits+1 WHERE id=" . $ptsudetails['ptsu_id']);
}


if ($input->p['action']) {
	if ($settings['demo'] == "yes") {
		header("location: ./?view=ptsu_pending&msg=this_is_demo");
		exit();
	}


	if (is_array($input->p['mid'])) {
		foreach ($input->p['mid'] as $mid) {
			$req = $db->fetchRow("SELECT * FROM ptsu_requests WHERE id='" . $mid . "'");
			switch ($input->p['a']) {
				case "delete":
					$db->delete("ptsu_requests", "id=" . $mid);
					$db->query("UPDATE ptsu_offers SET pending=pending-1, credits=credits+1 WHERE id=" . $req['ptsu_id']);
					break;

				case "approve":
					if ($req['status'] != "Completed") {
						$upd = $db->query("UPDATE ptsu_requests SET status='Completed' WHERE id=" . $mid);
						$upd = $db->query("UPDATE members SET money=money+" . $req['value'] . " WHERE id=" . $req['user_id']);
						$upd = $db->query("UPDATE ptsu_offers SET approved=approved+1, pending=pending-1 WHERE id=" . $req['ptsu_id']);
						$membershiptype = $db->fetchOne("SELECT type FROM members WHERE id=" . $req['user_id']);
						$membership = $db->fetchRow("SELECT point_enable, point_ptsu FROM membership WHERE id=" . $membershiptype);

						if ($membership['point_enable'] == 1) {
							addpoints($req['user_id'], $membership['point_ptsu']);
						}
					}
					break;

				case "reject":
					if ($req['status'] != "Cancelled") {
						$datastored = array("status" => "Cancelled");
						$upd = $db->update("ptsu_requests", $datastored, "id=" . $mid);
						$ptsuid = $db->fetchOne("SELECT ptsu_id FROM ptsu_requests WHERE id=" . $mid);
						$upd = $db->query("UPDATE ptsu_offers SET credits=credits+1, pending=pending-1 WHERE id=" . $ptsuid);
						$upd = $db->query("UPDATE members SET ptsu_denied=ptsu_denied+1 WHERE id=" . $req['user_id']);
					}
			}
		}
	}
}


if ($input->r['do'] == "search") {
	$searchvars = array("owner", "username", "status", "applicant", "ptsu_id", "title", "url");
	foreach ($searchvars as $k) {

		if ($input->r[$k]) {
			switch ($k) {
				case "owner":
					if ($input->rc[$k] == "selfsponsored") {
						$cond .= "owner_id=0 AND ";
					}
					else {
						if ($input->rc[$k] == "single" && $input->rc['username'] != "") {
							$user_id = $db->fetchOne("SELECT id FROM members WHERE username='" . $input->rc['username'] . "'");
							$cond .= "owner_id=" . $user_id . " AND ";
						}
					}
					break;

				case "username":
					break;

				case "applicant":
					$user_id = $db->fetchOne("SELECT id FROM members WHERE username='" . $input->rc['applicant'] . "'");
					$cond .= "user_id=" . $user_id . " AND ";
					break;

				case "url":
					$cond .= ("(") . $k . " LIKE '%" . $input->rc[$k] . "%') AND ";
					break;

				case "title":
					$cond .= ("(") . $k . " LIKE '%" . $input->rc[$k] . "%') AND ";
					break;

				case "status":
					if ($input->rc[$k] != "Pending2") {
						$cond .= $k . "='" . $input->rc[$k] . "' AND ";
					}
					else {
						$cond .= "(status='Reject1' OR (status='Pending' AND owner_id=0))  AND ";
					}
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


if ($cond) {
	$cond = "(status!='Completed' AND status!='Cancelled') AND " . $cond;
}
else {
	$cond = "status!='Completed'";
}

$paginator = new Pagination("ptsu_requests", $cond);
$paginator->setOrders("id", "DESC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=ptsu_pending&" . $adlink);
$q = $paginator->getQuery();
include SOURCES . "header.php";
echo "<script>
$(function()
{
	$(\".datepicker\").datepicker({ minDate: \"-2Y\" });

});
</script>
<div class=\"site_title\">مدیریت PTSU منتظر</div>
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
                    <td align=\"right\">آگهی دهنده</td>
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
                    <td align=\"right\">وضعیت:</td>
                    <td>            <select name=\"status\">
            <option value=\"\">همه</option>
            ";
$statusvalues = array("Pending" => "منتظر بررسی توسط آگهی دهنده", "Rejected1" => "رد شده - منتظر بررسی توسط مدیر", "Pending2" => "کلا منتظر بررسی توسط مدیر");
foreach ($statusvalues as $k => $v) {

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
                    <td align=\"right\">متقاضی:</td>
                    <td><input type=\"text\" name=\"applicant\" value=\"";
echo $input->r['applicant'];
echo "\" /></td>
                    <td align=\"right\">PTSU آیدی:</td>
                    <td><input type=\"text\" name=\"ptsu_id\" value=\"";
echo $input->r['ptsu_id'];
echo "\" /></td>
                </tr>
                <tr>
                    <td align=\"right\">تبلیغ:</td>
                    <td><input type=\"text\" name=\"title\" value=\"";
echo $input->r['title'];
echo "\" /></td>
                    <td align=\"right\">آدرس:</td>
                    <td><input type=\"text\"  style=\"direction:ltr !important;\"  name=\"url\" value=\"";
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
echo $paginator->linkorder("ptsu_id", "PTSU آیدی");
echo "</td>
        <td>";
echo $paginator->linkorder("jdate", "تاریخ");
echo "</td>
        <td>";
echo $paginator->linkorder("title", "تبلیغ");
echo " / ";
echo $paginator->linkorder("url", "آدرس");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("owner_id", "آگهی دهنده");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("user_id ", "متقاضی");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("value ", "مقدار");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("status", "وضعیت");
echo "</td>
        <td></td>
    </tr>
    ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	$owner = ($r['owner_id'] == 0 ? "<span style=\"color:green\">مدیریت</span>" : "<a href=\"./?view=members&edit=" . $r['owner_id'] . "\">" . $db->fetchOne("SELECT username FROM members WHERE id=" . $r['owner_id']) . "</a>");
	$member = $db->fetchRow("SELECT username, ptsu_denied FROM members WHERE id=" . $r['user_id']);
	$applicant = "<a href=\"./?view=members&edit=" . $r['user_id'] . "\">" . $member['username'] . "</a>";
	echo "  	<tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
    	<td align=\"center\"><input type=\"checkbox\" name=\"mid[]\" value=\"";
	echo $r['id'];
	echo "\" class=\"checkall\" /></td>
        <td>";
	echo $r['ptsu_id'];
	echo "</td>
        <td>";
	echo jdate("d F Y ساعت h:i A", $r['date']);
	echo "</td>
        <td>";
	$adtitle = $r['title'];

	if (35 < strlen($adtitle)) {
		echo substr($adtitle, 0, 35) . "...";
	}
	else {
		echo $adtitle;
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
			";
	echo $owner;
	echo "        </td>
        <td align=\"center\">
			";
	echo $applicant;
	echo "        </td>
        <td align=\"center\">
			<span style=\"color:#000099\">";
	echo $r['value'];
	echo "</span>
        </td>
        <td align=\"center\">
        ";
	switch ($r['status']) {
		case "Pending":
			if ($r['owner_id'] != 0) {
				echo "<span style=\"color:orange\">" . $r['status'] . " بازبینی توسط آگهی دهنده</span>";
			}
			else {
				echo "<span style=\"color:#996600\">" . $r['status'] . " بازبینی توسط مدیر</span>";
			}
			break;

		case "Rejected1":
			echo "<span style=\"color:red\">رد شده - بازبینی توسط مدیر</span>";
			break;

		default:
			echo "<span style=\"color:#996600\">" . $r['status'] . "</span>";
			break;
	}

	echo "        </td>
        <td align=\"center\">
        <a href=\"javascript:void(0);\" onClick=\"openWindows('<span style=\'font-weight:normal\'>PTSU:</span> ";
	echo $adtitle;
	echo "', 'info-";
	echo $r['id'];
	echo "');\"><img src=\"./css/images/info.png\" title=\"More info\" border=\"0\" /></a>
<div id=\"info-";
	echo $r['id'];
	echo "\" style=\"display:none\">
<table width=\"100%\" class=\"widget-tbl\">
    <tr>
        <td align=\"right\" width=\"25%\">PTSU:</td>
        <td width=\"25%\"><strong>";
	echo $r['title'];
	echo "</strong></td>
    </tr>
    <tr>
        <td align=\"right\">Link:</td>
        <td style=\"color:green\">";
	echo $r['url'];
	echo "</td>
    </tr>
    <tr>
        <td align=\"right\">Value:</td>
        <td style=\"color:green\">";
	echo $r['value'];
	echo "</td>
    </tr>
    <tr>
        <td align=\"right\">Applicant:</td>
        <td style=\"color:red\"><strong>";
	echo $applicant;
	echo "</strong> (PTSU's Denied: ";
	echo $member['ptsu_denied'];
	echo ")</td>
    </tr>
    <tr>
        <td align=\"right\">Instructions:</td>
        ";
	$instructions = $db->fetchOne("SELECT instructions FROM ptsu_offers WHERE id=" . $r['ptsu_id']);
	echo "        <td style=\"color:brown\">";
	echo nl2br($instructions);
	echo "</td>
    </tr>
    <tr>
        <td align=\"right\">Username or Email used in signup process:</td>
        <td style=\"color:#000099\">";
	echo $r['username'];
	echo "</td>
    </tr>
    <tr>
        <td align=\"right\">Welcome email or any extra information:</td>
        <td style=\"color:#FF9900\">";
	echo nl2br($r['message']);
	echo "</td>
    </tr>
    <tr>
        <td align=\"right\">Deny Reason:</td>
        <td style=\"color:#000099\"><textarea rows=\"10\" cols=\"45\" name=\"reason\">";
	echo $r['advertiser_notes'];
	echo "</textarea></td>
    </tr>
    <tr>
        <td align=\"center\" colspan=\"2\">
        <input type=\"submit\" name=\"approve\" value=\"Approve\" onclick=\"ptsu_action('approve', ";
	echo $r['id'];
	echo ");\" />
        <input type=\"submit\" name=\"reject\" value=\"Reject\" onclick=\"ptsu_action('reject', ";
	echo $r['id'];
	echo ");\" />
        <input type=\"submit\" name=\"delete\" value=\"Delete\" onclick=\"ptsu_action('delete', ";
	echo $r['id'];
	echo ");\" />
        </td>
    </tr>
</table>
</div>
        </td>
    </tr>
    ";
}


if ($paginator->totalResults() == 0) {
	echo "    <tr>
    	<td colspan=\"9\" align=\"center\">موردی یافت نشد</td>
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
	echo "    <div class=\"widget-title\" style=\"margin-top:5px\">Action</div>
        <div class=\"widget-content\">
        	<select name=\"a\">
            	<option value=\"\">یک مورد را انتخاب کنید</option>
                <option value=\"delete\">حذف</option>
                <option value=\"approve\">تایید</option>
                <option value=\"reject\">رد</option>
            </select>
            <input type=\"submit\" name=\"action\" value=\"Submit\" />
        </div>
    ";
}

echo "</form>

<script type=\"text/javascript\">
function ptsu_action(act, ptsu){
	$(\"#ptsuaction\").html('<input type=\"hidden\" name=\"shortaction\" value=\"'+act+'\"><input type=\"hidden\" name=\"ptsureq\" value=\"'+ptsu+'\">');
	$(\"#ptsuaction\").submit();
}
</script>
<form id=\"ptsuaction\" method=\"post\"  action=\"";
echo $paginator->gotopage();
echo "\"></form>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>