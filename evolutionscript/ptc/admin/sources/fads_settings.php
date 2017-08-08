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


if (!$admin->permissions['featuredads']) {
	header("location: ./");
	exit();
}

$fads_autoassign = $db->fetchOne("SELECT autoassign FROM buyoptions WHERE name='fad_credits'");

if ($input->p['a'] == "update") {
	verifyajax();
	verifydemo();

	if (!is_numeric($input->p['featuredad_chars_title'])) {
		serveranswer(0, "مقدار کاراکتر های به کار رفته بر روی عنوان غیرمجاز است");
	}


	if (200 < $input->p['featuredad_chars_title']) {
		serveranswer(0, "حداکثر 200 کاراکتر برای عنوان مجاز است");
	}

	$featuredad_chars_title = round($input->p['featuredad_chars_title']);

	if (!is_numeric($input->p['featuredad_chars_descr'])) {
		serveranswer(0, "مقدار کاراکتر های به کار رفته برای توضیحات غیرمجاز است");
	}


	if (200 < $input->p['featuredad_chars_descr']) {
		serveranswer(0, "حداکثر 200 کاراکتر برای توضیحات مجاز است");
	}

	$featuredad_chars_descr = round($input->p['featuredad_chars_descr']);
	$db->query("ALTER TABLE `featured_ads` CHANGE `ad` `ad` VARCHAR(" . $featuredad_chars_descr . ") CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ");
	$db->query("UPDATE settings SET value='" . $featuredad_chars_descr . "' WHERE field='featuredad_chars_descr'");
	$upd = $db->query("UPDATE buyoptions SET autoassign='" . $input->pc['fads_autoassign'] . "' WHERE name='fad_credits'");
	$updata = array("value" => $input->pc['fads_available']);
	$db->update("settings", $updata, "field='fads_available'");
	$upd = $db->query("ALTER TABLE `featured_ads` CHANGE `title` `title` VARCHAR(" . $featuredad_chars_title . ") CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ");
	$db->query("UPDATE settings SET value='" . $input->pc['fads_approval'] . "' WHERE field='fads_approval'");
	$upd = $db->query("UPDATE settings SET value='" . $featuredad_chars_title . "' WHERE field='featuredad_chars_title'");

	if (is_numeric($input->pc['show_fads'])) {
		$upd = $db->query("UPDATE settings SET value='" . $input->pc['show_fads'] . "' WHERE field='show_fads'");
	}

	$cache->delete("settings");
	serveranswer(1, "تنظیمات بروز شد");
}
else {
	if ($input->p['a'] == "newpack") {
		if ($settings['demo'] == "yes") {
			$error_newpack = "This is not possible in this demo version.";
		}
		else {
			if (!is_numeric($input->pc['credits']) || !is_numeric($input->pc['price'])) {
				$error_newpack = "بعضی فیلدها اشتباه هستند";
			}
			else {
				$set = array("credits" => $input->pc['credits'], "price" => $input->pc['price']);
				$db->insert("fads_price", $set);
				$newpackid = $db->lastInsertId();
				$success_newpack = 1;
			}
		}
	}
}


if ($input->p['a'] == "updatepack") {
	verifyajax();
	verifydemo();
	$credits = $input->p['credits'][$input->pc['packid']];
	$price = $input->p['price'][$input->pc['packid']];

	if (!is_numeric($credits) || !is_numeric($price)) {
		serveranswer(0, "بعضی فیلدها اشتباه هستند");
	}

	$set = array("credits" => $credits, "price" => $price);
	$db->update("fads_price", $set, "id=" . $input->pc['packid']);
	serveranswer(1, "پکیج بروز شد");
}
else {
	if ($input->p['a'] == "deletepack") {
		verifyajax();
		verifydemo();
		$db->delete("fads_price", "id=" . $input->pc['packid']);
		serveranswer(6, "$(\"#pack" . $input->pc['packid'] . "\").remove();");
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">تنظیمات تبلیغ ویژه</div>
<div class=\"site_content\">
    <div id=\"tabs\">
        <ul>
            <li><a href=\"#tabs-1\">تنظیمات عمومی</a></li>
            <li><a href=\"#tabs-2\">پکیج</a></li>
        </ul>
        <div id=\"tabs-1\">
            <form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
            <input type=\"hidden\" name=\"a\" value=\"update\" />
            <table class=\"widget-tbl\" width=\"100%\">
              <tr>
                <td align=\"right\" width=\"300\">تبلیغ فعال شود؟</td>
                <td><input type=\"checkbox\" value=\"yes\" ";
echo $settings['fads_available'] == "yes" ? "checked" : "";
echo " name=\"fads_available\" /></td>
              </tr>
              <tr>
                <td align=\"right\">نیاز به تایید مدیریت است؟ </td>
                <td><input type=\"checkbox\" id=\"fads_approval\" name=\"fads_approval\" value=\"yes\" ";
echo $settings['fads_approval'] == "yes" ? "checked" : "";
echo " /></td>
              </tr>
              <tr>
                <td align=\"right\">اختصاص خودکار اعتبار بعد از پرداخت؟</td>
                <td><input type=\"checkbox\" name=\"fads_autoassign\" value=\"yes\" ";
echo $fads_autoassign == "yes" ? "checked" : "";
echo " /></td>
              </tr>
              <tr>
                <td align=\"right\">مقدار آگهی نمایش داده شونده در هر صفحه</td>
                <td><input type=\"text\" name=\"show_fads\" value=\"";
echo $settings['show_fads'];
echo "\" size=\"4\" /></td>
              </tr>
          <tr>
            <td align=\"right\">ماکزیمم کاراکتر مجاز برای عنوان</td>
            <td><input type=\"text\" name=\"featuredad_chars_title\" value=\"";
echo $settings['featuredad_chars_title'];
echo "\" /> (200 max)</td>
          </tr>
          <tr>
            <td align=\"right\">ماکزیمم کاراکتر مجاز برای توضیحات</td>
            <td><input type=\"text\" name=\"featuredad_chars_descr\" value=\"";
echo $settings['featuredad_chars_descr'];
echo "\" /> (200 max)</td>
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
        <div id=\"tabs-2\">
        	<div class=\"widget-title\">افزودن پکیج جدید</div>
    		";

if ($error_newpack) {
	echo "<div class=\"error_box\">" . $error_newpack . "</div>";
}


if ($success_newpack) {
	echo "<div class=\"success_box\">پکیج بروز شد</div>";
}

echo "            <div class=\"widget-content\">
		<form method=\"post\" action=\"./?view=fads_settings#tabs-2\">
		<input type=\"hidden\" name=\"a\" value=\"newpack\" />
                <table width=\"100%\" class=\"widget-tbl\">
                <tr>
                <td align=\"right\">
					اعتبار : 
				</td>
                <td>
                	<input type=\"text\" name=\"credits\" value=\"0\" />
				</td>
                <td align=\"right\">
					قیمت به تومان : 
				</td>
                <td>
                	<input type=\"text\" name=\"price\" value=\"0.00\" />

                	<input type=\"submit\" name=\"btn\" value=\"افزودن\" />
                </td>
                </tr>
                </table>
    	</form>
            </div>
            <div class=\"widget-title\">مدیریت پکیج</div>
            <form method=\"post\" id=\"frm3\" onsubmit=\"return submitform(this.id);\">
                <input type=\"hidden\" name=\"packid\" id=\"packid\" value=\"0\" />
                <input type=\"hidden\" name=\"a\" id=\"packaction\" value=\"\" />
	            <table width=\"100%\" class=\"widget-tbl\">
                	<tr class=\"titles\">
                    	<td>اعتبار</td>
                        <td>قیمت به تومان</td>
                        <td>اقدام</td>
                    </tr>
					";
$query = $db->query("SELECT * FROM fads_price ORDER BY price ASC");

while ($r = $db->fetch_array($query)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "
                    <tr id=\"pack";
	echo $r['id'];
	echo "\" class=\"";
	echo $tr;
	echo "\">
                    	<td><input type=\"text\" name=\"credits[";
	echo $r['id'];
	echo "]\" value=\"";
	echo $r['credits'];
	echo "\" /></td>
                        <td><input type=\"text\" name=\"price[";
	echo $r['id'];
	echo "]\" value=\"";
	echo $r['price'];
	echo "\" /></td>
                		<td>
                        	<input type=\"submit\" name=\"btn\" value=\"ذخیره\" onclick=\"updfrmvars({'packid': '";
	echo $r['id'];
	echo "', 'packaction': 'updatepack'});\" />
	                        <input type=\"submit\" name=\"packaction\" value=\"حذف\" class=\"cancel\" onclick=\"updfrmvars({'packid': '";
	echo $r['id'];
	echo "', 'packaction': 'deletepack'});\" />
                            </td>
                    </tr>
                    ";
}

echo "                </table>
                </form>
        </div>

	</div>
</div>
";
include SOURCES . "footer.php";
echo " ";
?>