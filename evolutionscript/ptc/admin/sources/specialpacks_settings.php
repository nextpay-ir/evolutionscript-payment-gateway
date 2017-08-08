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


if (!$admin->permissions['specialpacks']) {
	header("location: ./");
	exit();
}


if (is_numeric($input->g['edit'])) {
	$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM specialpacks WHERE id=" . $input->gc['edit']);

	if ($chk != 0) {
		include SOURCES . "edit_specialpacks.php";
		exit();
	}
}

$special_autoassign = $db->fetchOne("SELECT autoassign FROM buyoptions WHERE name='specialpack'");

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
							$db->delete("specialpacks", "id=" . $mid);
							$db->delete("specialpacks_list", "specialpack=" . $mid);
					}
				}
			}
		}
	}
}


if ($input->p['do'] == "update_settings") {
	verifyajax();
	verifydemo();
	$upd = $db->query("UPDATE buyoptions SET autoassign='" . $input->p['special_autoassign'] . "' WHERE name='specialpack'");
	$upd = $db->query("UPDATE settings SET value='" . $input->pc['special_available'] . "' WHERE field='special_available'");
	$upd = $db->query("UPDATE buyoptions SET enable='" . $input->pc['special_available'] . "' WHERE name='specialpack'");
	$cache->delete("settings");
	serveranswer(1, "تنظیمات بروز شد");
}
else {
	if ($input->p['do'] == "new_pack") {
		verifyajax();
		verifydemo();

		if (empty($input->p['name']) || !is_numeric($input->p['price'])) {
			serveranswer(0, "بعضی فیلدها اشتباه هستند");
		}

		$set = array("name" => $input->pc['name'], "price" => $input->pc['price'], "enable" => $input->pc['enable'], "date" => TIMENOW);
		$db->insert("specialpacks", $set);
		$packid = $db->lastInsertId();
		serveranswer(4, "location.href='./?view=specialpacks_settings&edit=" . $packid . "';");
	}
}

$paginator = new Pagination("specialpacks", $cond);
$paginator->setOrders("date", "DESC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=specialpacks_settings&" . $adlink);
$q = $paginator->getQuery();
include SOURCES . "header.php";
echo "<div class=\"site_title\">پکیج ویژه</div>
<div class=\"site_content\">
<div id=\"tabs\">
	<ul>
        <li><a href=\"#tab-1\">مدیریت پکیج ها</a></li>
        <li><a href=\"#tab-2\">افزودن پکیج جدید</a></li>
    	<li><a href=\"#tab-3\">تنظیمات</a></li>
    </ul>
    <div id=\"tab-1\">
		";

if ($error_msg) {
	echo "        <div class=\"error_box\">";
	echo $error_msg;
	echo "</div>
        ";
}

echo "<form method=\"post\" action=\"";
echo $paginator->gotopage();
echo "\">
        <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
          <table width=\"100%\" class=\"widget-tbl\">
            <tr class=\"titles\">
                <td width=\"20\"><input type=\"checkbox\" id=\"checkall\"></td>
                <td>";
echo $paginator->linkorder("jdate", "تاریخ اضافه شدن");
echo "</td>
                <td>";
echo $paginator->linkorder("name", "نام");
echo "</td>
                <td align=\"center\">";
echo $paginator->linkorder("buys", "خریدها");
echo "</td>
                <td align=\"center\">";
echo $paginator->linkorder("price", "قمیت");
echo "</td>
                <td align=\"center\">";
echo $paginator->linkorder("enable", "فعال");
echo "</td>
                <td></td>
            </tr>
            ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "            <tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
                <td align=\"center\"><input type=\"checkbox\" name=\"mid[]\" value=\"";
	echo $r['id'];
	echo "\" class=\"checkall\" /></td>
                <td>";
	echo jdate("d F Y ساعت h:i A", $r['date']);
	echo "</td>
                <td><span style=\"color:#000099\">";
	echo $r['name'];
	echo "</span>
                </td>
                <td align=\"center\">
                    <span style=\"color:green\">";
	echo $r['buys'];
	echo "</span>
                </td>
                <td align=\"center\"><span style=\"color:#990000\">";
	echo $r['price'];
	echo "</span></td>
                <td align=\"center\">";
	echo $r['enable'] == "yes" ? "بله" : "خیر";
	echo "</td>
                <td align=\"center\"><a href=\"./?view=specialpacks_settings&edit=";
	echo $r['id'];
	echo "\"><img src=\"./css/images/edit.png\" border=\"0\" title=\"Edit Special Pack\" /></a></td>
            </tr>
            ";
}


if ($paginator->totalResults() == 0) {
	echo "            <tr>
                <td colspan=\"8\" align=\"center\">موری یافت نشد</td>
            </tr>
            ";
}

echo "          </table>
            <div style=\"margin-top:10px\">
            <input type=\"button\" value=\"&larr; صفحه قبل\" ";
echo ($paginator->totalPages() == 1 || $paginator->getPage() == 1) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->prevpage() . "';\";";
echo " />

            <input type=\"button\" value=\"صفحه بعد &rarr;\" ";
echo ($paginator->totalPages() == 0 || $paginator->totalPages() == $paginator->getPage()) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->nextpage() . "';\";";
echo " />
                ";

if (1 < $paginator->totalPages()) {
	echo "                <div style=\"float:right\">
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

	echo "                </select>
                <script type=\"text/javascript\">
                    function gotopage(pageid){
                        location.href=pageid;
                    }
                </script>
                </div>
                <div class=\"clear\"></div>
                ";
}

echo "            </div>

            ";

if (0 < $paginator->totalPages()) {
	echo "            <div class=\"widget-title\" style=\"margin-top:5px\">اقدام</div>
                <div class=\"widget-content\">
                    <select name=\"a\">
                        <option value=\"\">یک مورد را انتخاب کنید</option>
                        <option value=\"delete\">حذف</option>
                    </select>
                    <input type=\"submit\" name=\"action\" value=\"ثبت\" />
                </div>
            ";
}

echo "        </form>
    </div>
    <div id=\"tab-2\">
        <form method=\"post\" id=\"frmnewpack\" onsubmit=\"return submitform(this.id);\">
        <input type=\"hidden\" name=\"do\" value=\"new_pack\" />
        <table width=\"100%\" class=\"widget-tbl\">
          <tr>
            <td align=\"right\" width=\"300\">نام</td>
            <td><input type=\"text\" name=\"name\" /></td>
          </tr>
          <tr>
            <td align=\"right\">قیمت</td>
            <td><input type=\"text\" name=\"price\" /></td>
          </tr>
          <tr>
            <td align=\"right\">فعال</td>
            <td><input type=\"checkbox\" name=\"enable\" value=\"yes\" /></td>
          </tr>
            <tr>
            	<td></td>
                <td>
                    <input type=\"submit\" name=\"save\" value=\"ذخیره\" />
                </td>
            </tr>
        </table>
        </form>
    </div>
    <div id=\"tab-3\">
        <form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
            <input type=\"hidden\" name=\"do\" value=\"update_settings\" />
        <table class=\"widget-tbl\" width=\"100%\">
          <tr>
            <td valign=\"top\" align=\"right\" width=\"300\">پکیج های ویژه فعال شود؟</td>
            <td valign=\"top\"><input type=\"checkbox\" name=\"special_available\" id=\"special_available\" value=\"yes\" ";

if ($settings['special_available'] == "yes") {
	echo "checked";
}

echo " /></td>
          </tr>

          <tr>
            <td valign=\"top\" align=\"right\">اختصاص خودکار پکیج بعد از پرداخت؟</td>
            <td valign=\"top\"><input type=\"checkbox\" name=\"special_autoassign\" id=\"special_autoassign\" value=\"yes\" ";

if ($special_autoassign == "yes") {
	echo "checked";
}

echo " /></td>
          </tr>
            <tr>
            	<td></td>
                <td>
        <input type=\"submit\" name=\"btn\" value=\"ذخیره\" />
                </td>
            </tr>
        </table>
        </form>
    </div>

</div>

</div>


        ";
include SOURCES . "footer.php";
echo " ";
?>