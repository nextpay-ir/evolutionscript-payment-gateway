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


if (!$admin->permissions['setup']) {
	header("location: ./");
	exit();
}

$auto_buyrefs = $db->fetchOne("SELECT autoassign FROM buyoptions WHERE name='referrals'");

if ($input->p['do'] == "update_settings") {
	verifyajax();
	verifydemo();
	$db->query("UPDATE buyoptions SET autoassign='" . $input->pc['auto_buyrefs'] . "' WHERE name='referrals'");

	if ($input->p['buy_referrals'] == "yes") {
		$data = array("value" => "yes");
		$data2 = array("enable" => "yes");
	}
	else {
		$data = array("value" => "no");
		$data2 = array("enable" => "no");
	}

	$upd = $db->update("settings", $data, "field='buy_referrals'");
	$upd = $db->update("buyoptions", $data2, "name='referrals'");
	$db->query("UPDATE settings SET value='" . $input->pc['buyref_filter'] . "' WHERE field='buyref_filter'");
	$db->query("UPDATE settings SET value='" . $input->pc['buyref_clicks'] . "' WHERE field='buyref_clicks'");
	$db->query("UPDATE settings SET value='" . $input->pc['buyref_days'] . "' WHERE field='buyref_days'");
	$cache->delete("settings");
	serveranswer(1, "تنظیمات ذخیره شد");
}
else {
	if ($input->p['do'] == "new_pack") {
		if ($settings['demo'] == "yes") {
			$error_msg = "این امکان در ورژن دمو وجود ندارد";
		}
		else {
			if ($input->p['sesion_id'] != $_SESSION['sesion_id']) {
				$error_msg = "دوباره تلاش کنید";
			}
			else {
				if (!is_numeric($input->p['credits']) || !is_numeric($input->p['price'])) {
					$error_msg = "بعضی فیلدها نامعتبر هستند";
				}
				else {
					$set = array("refs" => $input->p['credits'], "price" => $input->p['price']);
					$db->insert("referral_price", $set);
					$success_msg = "پکیج جدید افزوده شد";
				}
			}
		}
	}
	else {
		if ($input->p['do'] == "update_pack") {
			verifyajax();
			verifydemo();
			$id = $input->pc['packid'];

			if (!is_numeric($input->p['refs'][$id]) || !is_numeric($input->p['price'][$id])) {
				serveranswer(0, "بعضی فیلدها اشتباه هستند");
			}

			$set = array("refs" => $input->p['refs'][$id], "price" => $input->p['price'][$id]);
			$db->update("referral_price", $set, "id=" . $id);
			serveranswer(1, "پکیج بروز شد");
		}
		else {
			if ($input->p['do'] == "delete_pack") {
				verifyajax();
				verifydemo();
				$id = $input->pc['packid'];
				$db->delete("referral_price", "id=" . $id);
				serveranswer(6, "$(\"#tr_" . $id . "\").remove();");
			}
		}
	}
}

$paginator = new Pagination("referral_price", $cond);
$paginator->setOrders("refs", "ASC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=buy_referrals&" . $adlink);
$q = $paginator->getQuery();
include SOURCES . "header.php";
echo "<div class=\"site_title\">خرید زیر مجموعه</div>
<div class=\"site_content\">
<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tab-1\">مدیریت پکیج</a></li>
    	<li><a href=\"#tab-2\">تنظیمات</a></li>
    </ul>
<div id=\"tab-1\">
    	<div class=\"widget-title\">افزودن پکیج</div>
        <div class=\"widget-content\">
		";

if ($error_msg) {
	echo "        <div class=\"error_box\">";
	echo $error_msg;
	echo "</div>
        ";
}

echo "		";

if ($success_msg) {
	echo "        <div class=\"success_box\">";
	echo $success_msg;
	echo "</div>
        ";
}

echo "        <form method=\"post\" action=\"./?view=buy_referrals\">
        <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
        <input type=\"hidden\" name=\"do\" value=\"new_pack\" />
            <table class=\"widget-tbl\" width=\"100%\">
              <tr>
                <td align=\"right\" width=\"100\">
           زیر مجموعه ها : 
            	</td>
                <td>
            <input type=\"text\" name=\"credits\" value=\"0\" />
            	</td>
                <td align=\"right\" width=\"100\">
            قیمت به تومان : 
            	</td>
                <td>
            <input type=\"text\" name=\"price\" value=\"0.00\" />
            <input type=\"submit\" name=\"btn\" value=\"افزودن\" class=\"orange\" />
            </td>
            </tr>
            </table>
    	</form>
        </div>

        <div class=\"widget-title\">مدیریت</div>
        <form method=\"post\" id=\"frmlist\" onsubmit=\"return submitform(this.id);\">

          <table width=\"100%\" class=\"widget-tbl\">
            <tr class=\"titles\">
                <td align=\"center\">";
echo $paginator->linkorder("refs", "زیر مجموعه ها");
echo "</td>
                <td align=\"center\">";
echo $paginator->linkorder("price", "قیمت");
echo "</td>
                <td></td>
            </tr>
            ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "            <tr class=\"";
	echo $tr;
	echo " normal_linetbl\" id=\"tr_";
	echo $r['id'];
	echo "\">
                <td align=\"center\">
                <input type=\"text\" name=\"refs[";
	echo $r['id'];
	echo "]\" value=\"";
	echo $r['refs'];
	echo "\" />
                </td>
                <td align=\"center\">
                	 <input type=\"text\" name=\"price[";
	echo $r['id'];
	echo "]\" value=\"";
	echo $r['price'];
	echo "\" />
                </td>
                <td align=\"center\">
                <input type=\"submit\" name=\"btn\" value=\"ذخیره\" onclick=\"updfrmvars({'do_action': 'update_pack', 'packid': '";
	echo $r['id'];
	echo "'});\" />
                <input type=\"submit\" name=\"btn\" value=\"حذف\" onclick=\"updfrmvars({'do_action': 'delete_pack', 'packid': '";
	echo $r['id'];
	echo "'});\" />
                </td>
            </tr>
            ";
}


if ($paginator->totalResults() == 0) {
	echo "            <tr>
                <td colspan=\"8\" align=\"center\">موردی یافت نشد</td>
            </tr>
            ";
}

echo "          </table>
          <input type=\"hidden\" name=\"do\" value=\"\" id=\"do_action\" />
          <input type=\"hidden\" name=\"packid\" value=\"\" id=\"packid\" />

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

        </form>
    </div>
    <div id=\"tab-2\">
    	<script type=\"text/javascript\">
		function chkref_filter(val){
			if(val == 'enable'){
				$(\"#ref_filter\").show();
			}else{
				$(\"#ref_filter\").hide();
			}
		}
		</script>
        <form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
        <input type=\"hidden\" name=\"do\" value=\"update_settings\" />
        <table class=\"widget-tbl\" width=\"100%\">
          <tr>
            <td align=\"right\" width=\"300\">اختصاص خودکار زیر مجموعه بعد پرداخت؟</td>
            <td><input type=\"checkbox\" name=\"auto_buyrefs\" id=\"auto_buyrefs\" value=\"yes\" ";

if ($auto_buyrefs) {
	echo "checked";
}

echo " /></td>
          </tr>
          <tr>
            <td align=\"right\">فعال بودن خرید زیرمجموعه ؟</td>
            <td><input type=\"checkbox\" name=\"buy_referrals\" id=\"buy_referrals\" value=\"yes\" ";

if ($settings['buy_referrals'] == "yes") {
	echo "checked";
}

echo " /></td>
          </tr>
          <tr>
          	<td align=\"right\">فیلتر زیرمجموعه ها؟</td>
            <td><select name=\"buyref_filter\" onchange=\"chkref_filter(this.value);\"><option value=\"disable\">Disable</option><option value=\"enable\" ";
echo $settings['buyref_filter'] == "enable" ? "selected" : "";
echo ">Enable</option></select></td>
          </tr>
          <tr style=\"";
echo $settings['buyref_filter'] == "enable" ? "" : "display:none";
echo "\" id=\"ref_filter\">
            <td colspan=\"2\" align=\"center\">فروش زیر مجموعه هایی که حداقل <input type=\"text\" name=\"buyref_clicks\" value=\"";
echo $settings['buyref_clicks'];
echo "\" class=\"default\" size=\"5\" /> کلیک در حداقل <select name=\"buyref_days\" class=\"default\">
            <option ";
echo $settings['buyref_days'] == 1 ? "selected" : "";
echo ">1</option>
            <option ";
echo $settings['buyref_days'] == 2 ? "selected" : "";
echo ">2</option>
            <option ";
echo $settings['buyref_days'] == 3 ? "selected" : "";
echo ">3</option>
            <option ";
echo $settings['buyref_days'] == 4 ? "selected" : "";
echo ">4</option>
            <option ";
echo $settings['buyref_days'] == 5 ? "selected" : "";
echo ">5</option>
            <option ";
echo $settings['buyref_days'] == 6 ? "selected" : "";
echo ">6</option>
            <option ";
echo $settings['buyref_days'] == 7 ? "selected" : "";
echo ">7</option>
            </select> روز داشته اند</td>
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