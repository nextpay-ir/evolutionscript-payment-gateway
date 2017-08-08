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

$auto_rentrefs = $db->fetchOne("SELECT autoassign FROM buyoptions WHERE name='rent_referrals'");

if ($input->p['do'] == "update_settings") {
	verifyajax();
	verifydemo();
	$db->query("UPDATE buyoptions SET autoassign='" . $input->pc['auto_rentrefs'] . "' WHERE name='rent_referrals'");

	if ($input->p['rent_referrals'] == "yes") {
		$data = array("value" => "yes");
		$data2 = array("enable" => "yes");
	}
	else {
		$data = array("value" => "no");
		$data2 = array("enable" => "no");
	}

	$upd = $db->update("settings", $data, "field='rent_referrals'");
	$upd = $db->update("buyoptions", $data2, "name='rent_referrals'");
	$db->query("UPDATE settings SET value='" . $input->pc['rentype'] . "' WHERE field='rentype'");
	$db->query("UPDATE settings SET value='" . $input->pc['rentref_filter'] . "' WHERE field='rentref_filter'");
	$db->query("UPDATE settings SET value='" . $input->pc['rentref_clicks'] . "' WHERE field='rentref_clicks'");
	$db->query("UPDATE settings SET value='" . $input->pc['rentref_days'] . "' WHERE field='rentref_days'");
	$cache->delete("settings");
	serveranswer(1, "تنظیمات بروز شد");
}


if ($input->p['a'] == "newdiscount") {
	if ($settings['demo'] == "yes") {
		$error_msg = "This is not possible in this demo version.";
	}
	else {
		if (!is_numeric($input->pc['days']) || !is_numeric($input->pc['discount'])) {
			$error_newpack = "Some fields are incorrect";
		}
		else {
			$set = array("days" => $input->pc['days'], "discount" => $input->pc['discount']);
			$db->insert("rent_discount", $set);
			$success_msg = 1;
		}
	}
}


if ($input->p['a'] == "updatediscount") {
	verifyajax();
	verifydemo();
	$days = $input->p['days'][$input->pc['packid']];
	$discount = $input->p['discount'][$input->pc['packid']];

	if (!is_numeric($days) || !is_numeric($discount)) {
		serveranswer(0, "بعضی فیلدها اشتباه هستند");
	}

	$set = array("days" => $days, "discount" => $discount);
	$db->update("rent_discount", $set, "id=" . $input->pc['packid']);
	serveranswer(1, "تخفیف بروز شد");
}
else {
	if ($input->p['a'] == "deletediscount") {
		verifyajax();
		verifydemo();
		$db->delete("rent_discount", "id=" . $input->pc['packid']);
		serveranswer(6, "$(\"#pack" . $input->pc['packid'] . "\").remove();");
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">اجاره زیرمجموعه</div>
<div class=\"site_content\">
<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tab-1\">تنظیمات</a></li>
        <li><a href=\"#tab-2\">تخفیف و گسترش</a></li>
    </ul>
    <div id=\"tab-1\">
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
            <td align=\"right\" width=\"300\">اختصاص خودکار زیرمجموعه اجاره ای بعد از پرداخت؟</td>
            <td><input type=\"checkbox\" name=\"auto_rentrefs\" id=\"auto_rentrefs\" value=\"yes\" ";

if ($auto_rentrefs == "yes") {
	echo "checked";
}

echo " /></td>
          </tr>
          <tr>
            <td align=\"right\">زیر مجموعه اجاره فعال شود؟</td>
            <td><input type=\"checkbox\" name=\"rent_referrals\" id=\"rent_referrals\" value=\"yes\" ";

if ($settings['rent_referrals'] == "yes") {
	echo "checked";
}

echo " /></td>
          </tr>
          <tr>
            <td align=\"right\">اعضای قابل اجاره</td>
            <td>
            <select name=\"rentype\">
                <option value=\"1\" ";

if ($settings['rentype'] == 1) {
	echo "selected";
}

echo ">همه اعضا</option>
                <option value=\"2\" ";

if ($settings['rentype'] == 2) {
	echo "selected";
}

echo ">فقط اعضای فعال</option>
                <option value=\"3\" ";

if ($settings['rentype'] == 3) {
	echo "selected";
}

echo ">همه اعضای بدون بالا دستی</option>
                <option value=\"4\" ";

if ($settings['rentype'] == 4) {
	echo "selected";
}

echo ">همه اعضای فعال بدون بالا دستی</option>
            </select>
            </td>
          </tr>
          <tr>
          	<td align=\"right\">فیلتر زیرمجموعه ها؟</td>
            <td><select name=\"rentref_filter\" onchange=\"chkref_filter(this.value);\"><option value=\"disable\">غیرفعال</option><option value=\"enable\" ";
echo $settings['rentref_filter'] == "enable" ? "selected" : "";
echo ">فعال</option></select></td>
          </tr>
          <tr style=\"";
echo $settings['rentref_filter'] == "enable" ? "" : "display:none";
echo "\" id=\"ref_filter\">
            <td colspan=\"2\" align=\"center\">فروش زیر مجموع هایی که در نهایت حداقل <input type=\"text\" name=\"rentref_clicks\" value=\"";
echo $settings['rentref_clicks'];
echo "\" class=\"default\" size=\"5\" /> کلیک داشته اند<select name=\"rentref_days\" class=\"default\">
            <option ";
echo $settings['rentref_days'] == 1 ? "selected" : "";
echo ">1</option>
            <option ";
echo $settings['rentref_days'] == 2 ? "selected" : "";
echo ">2</option>
            <option ";
echo $settings['rentref_days'] == 3 ? "selected" : "";
echo ">3</option>
            <option ";
echo $settings['rentref_days'] == 4 ? "selected" : "";
echo ">4</option>
            <option ";
echo $settings['rentref_days'] == 5 ? "selected" : "";
echo ">5</option>
            <option ";
echo $settings['rentref_days'] == 6 ? "selected" : "";
echo ">6</option>
            <option ";
echo $settings['rentref_days'] == 7 ? "selected" : "";
echo ">7</option>
            </select> روز</td>
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
 	<div id=\"tab-2\">
    	<div class=\"widget-main-title\">افزودن تخفیف جدید</div>
        <div class=\"widget-content\">
    		";

if ($error_msg) {
	echo "<div class=\"error_box\">" . $error_msg . "</div>";
}


if ($success_msg) {
	echo "<div class=\"success_box\">تخفیف جدید ایجاد شد</div>";
}

echo "            <div class=\"info_box\">این ابزار به شما اجازه می دهد تا با اضافه کردن برخی از تخفیف اضافی برای گسترش زیرمجموعه ها اقدام کنید.</div>
             <form method=\"post\" id=\"comfrm\" action=\"./?view=rent_referrals#tab-2\">
             <input type=\"hidden\" name=\"a\" value=\"newdiscount\" />
            <table class=\"widget-tbl\" width=\"100%\">
                <tr>
                    <td align=\"right\" width=\"300\">روز : </td>
                    <td><input type=\"text\" name=\"days\" size=\"5\" value=\"90\" /></td>
                 </tr>
                 <tr>
                    <td align=\"right\">تخفیف : </td>
                    <td><input type=\"text\" name=\"discount\" value=\"0\" size=\"5\" />% </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type=\"submit\" name=\"btn\" value=\"ذخیره\" /></td>
                </tr>
              </table>
            </form>
         </div>

         <div class=\"widget-title\">مدیریت تخفیف ها</div>
         <div class=\"widget-content\">
            <form method=\"post\" id=\"frm3\" onsubmit=\"return submitform(this.id);\">
                <input type=\"hidden\" name=\"packid\" id=\"packid\" value=\"0\" />
                <input type=\"hidden\" name=\"a\" id=\"packaction\" value=\"\" />
	            <table width=\"100%\" class=\"widget-tbl\">
                	<tr class=\"titles\">
                    	<td>روز</td>
                        <td>تخفیف</td>
                        <td>اقدام</td>
                    </tr>
					";
$query = $db->query("SELECT * FROM rent_discount ORDER BY days ASC");

while ($r = $db->fetch_array($query)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "
                    <tr id=\"pack";
	echo $r['id'];
	echo "\" class=\"";
	echo $tr;
	echo "\">
                    	<td><input type=\"text\" name=\"days[";
	echo $r['id'];
	echo "]\" value=\"";
	echo $r['days'];
	echo "\" /></td>
                        <td><input type=\"text\" name=\"discount[";
	echo $r['id'];
	echo "]\" value=\"";
	echo $r['discount'];
	echo "\" /></td>
                		<td>
                        	<input type=\"submit\" name=\"btn\" value=\"ذخیره\" onclick=\"updfrmvars({'packid': '";
	echo $r['id'];
	echo "', 'packaction': 'updatediscount'});\" />
	                        <input type=\"submit\" name=\"packaction\" value=\"حذف\" class=\"cancel\" onclick=\"updfrmvars({'packid': '";
	echo $r['id'];
	echo "', 'packaction': 'deletediscount'});\" />
                            </td>
                    </tr>
                    ";
}

echo "                </table>
                </form>
         </div>
    </div>
</div>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>