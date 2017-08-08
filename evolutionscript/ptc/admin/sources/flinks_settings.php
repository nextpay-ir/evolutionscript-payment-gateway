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


if (!$admin->permissions['featuredlinks']) {
	header("location: ./");
	exit();
}

$flinks_autoassign = $db->fetchOne("SELECT autoassign FROM buyoptions WHERE name='flink_credits'");

if ($input->p['a'] == "update") {
	verifyajax();
	verifydemo();

	if (!is_numeric($input->p['featuredlink_chars_title'])) {
		serveranswer(0, "مقداد کاراکتر عنوان غیرمجاز است");
	}


	if (200 < $input->p['featuredlink_chars_title']) {
		serveranswer(0, "حداکثر طول مجاز عنوان 200 کاراکتر است");
	}

	$featuredlink_chars_title = round($input->p['featuredlink_chars_title']);
	$db->query("ALTER TABLE `featured_link` CHANGE `title` `title` VARCHAR(" . $featuredlink_chars_title . ") CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ");
	$db->query("UPDATE settings SET value='" . $featuredlink_chars_title . "' WHERE field='featuredlink_chars_title'");
	$upd = $db->query("UPDATE buyoptions SET autoassign='" . $input->pc['flinks_autoassign'] . "' WHERE name='flink_credits'");
	$upd = $db->query("UPDATE settings SET value='" . $input->pc['flinks_available'] . "' WHERE field='flinks_available'");
	$upd = $db->query("UPDATE settings SET value='" . $input->pc['flinks_approval'] . "' WHERE field='flinks_approval'");

	if (is_numeric($input->pc['show_flinks'])) {
		$upd = $db->query("UPDATE settings SET value='" . $input->pc['show_flinks'] . "' WHERE field='show_flinks'");
	}

	$cache->delete("settings");
	serveranswer(1, "Settings were updated.");
}
else {
	if ($input->p['a'] == "newpack") {
		if ($settings['demo'] == "yes") {
			$error_newpack = "This is not possible in this demo version.";
		}
		else {
			if (!is_numeric($input->pc['month']) || !is_numeric($input->pc['price'])) {
				$error_newpack = "بعضی فیلدها اشتباه هستند";
			}
			else {
				$set = array("month" => $input->pc['month'], "price" => $input->pc['price']);
				$db->insert("flinks_price", $set);
				$success_newpack = 1;
			}
		}
	}
	else {
		if ($input->p['a'] == "updatepack") {
			verifyajax();
			verifydemo();
			$month = $input->p['month'][$input->pc['packid']];
			$price = $input->p['price'][$input->pc['packid']];

			if (!is_numeric($month) || !is_numeric($price)) {
				serveranswer(0, "بعضی فیلدها اشتباه هستند");
			}

			$set = array("month" => $month, "price" => $price);
			$db->update("flinks_price", $set, "id=" . $input->pc['packid']);
			serveranswer(1, "پکیج بروز شد");
		}
		else {
			if ($input->p['a'] == "deletepack") {
				verifyajax();
				verifydemo();
				$db->delete("flinks_price", "id=" . $input->pc['packid']);
				serveranswer(6, "$(\"#pack" . $input->pc['packid'] . "\").remove();");
			}
		}
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">تنظیمات تبلیغ لینکی ویژه</div>
<div class=\"site_content\">
    <div id=\"tabs\">
        <ul>
            <li><a href=\"#tabs-1\">تنظیمات کلی</a></li>
            <li><a href=\"#tabs-2\">پکیج</a></li>
        </ul>
        <div id=\"tabs-1\">
            <form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
            <input type=\"hidden\" name=\"a\" value=\"update\" />
            <table class=\"widget-tbl\" width=\"100%\">
              <tr>
                <td align=\"right\" width=\"300\">تبلیغ فعال شود؟</td>
                <td><input type=\"checkbox\" name=\"flinks_available\" id=\"flinks_available\" value=\"yes\" ";
echo $settings['flinks_available'] == "yes" ? "checked" : "";
echo " /></td>
              </tr>
              <tr>
                <td align=\"right\">نیاز به تایید مدیر است؟</td>
                <td><input type=\"checkbox\" name=\"flinks_approval\" id=\"flinks_approval\" value=\"yes\" ";
echo $settings['flinks_approval'] == "yes" ? "checked" : "";
echo " /></td>
              </tr>
              <tr>
                <td align=\"right\">اختصاص خودکار اعتبار بعد پرداخت ؟</td>
                <td><input type=\"checkbox\" name=\"flinks_autoassign\" id=\"flinks_autoassign\" value=\"yes\" ";
echo $flinks_autoassign == "yes" ? "checked" : "";
echo " /></td>
              </tr>
              <tr>
                <td align=\"right\">مقدار تبلیغی که در هرصفحه باید نمایش داده شود؟</td>
                <td><input type=\"text\" name=\"show_flinks\" value=\"";
echo $settings['show_flinks'];
echo "\" size=\"4\" /></td>
              </tr>
          <tr>
            <td align=\"right\">ماکزیمم کاراکتر مجاز برای عنوان</td>
            <td><input type=\"text\" name=\"featuredlink_chars_title\" value=\"";
echo $settings['featuredlink_chars_title'];
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
        	<div class=\"widget-title\">افزودن پکیج</div>
    		";

if ($error_newpack) {
	echo "<div class=\"error_box\">" . $error_newpack . "</div>";
}


if ($success_newpack) {
	echo "<div class=\"success_box\">پکیج اضافه شد</div>";
}

echo "            <div class=\"widget-content\">
            <form method=\"post\" action=\"./?view=flinks_settings#tabs-2\">
                <input type=\"hidden\" name=\"a\" value=\"newpack\" />
                <table class=\"widget-tbl\" width=\"100%\">
                    <tr>
                    <td align=\"right\">

                ماه : 
                	</td>
                    <td>
                <input type=\"text\" name=\"month\" value=\"0\" />
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

            <div class=\"widget-title\">مدیریت پکیج : </div>
            	<form method=\"post\" id=\"frm3\" onsubmit=\"return submitform(this.id);\">
                <input type=\"hidden\" name=\"packid\" id=\"packid\" value=\"0\" />
                <input type=\"hidden\" name=\"a\" id=\"packaction\" value=\"\" />
            	<table width=\"100%\" class=\"widget-tbl\">
                	<tr class=\"titles\">
                    	<td>ماه</td>
                        <td>قیمت به تومان</td>
                        <td>اقدام</td>
                    </tr>
					";
$query = $db->query("SELECT * FROM flinks_price ORDER BY price ASC");

while ($r = $db->fetch_array($query)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "
                    <tr id=\"pack";
	echo $r['id'];
	echo "\" class=\"";
	echo $tr;
	echo "\">
                    	<td><input type=\"text\" name=\"month[";
	echo $r['id'];
	echo "]\" value=\"";
	echo $r['month'];
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
                        <input type=\"submit\" name=\"btn\" value=\"حذف\" class=\"cancel\" onclick=\"updfrmvars({'packid': '";
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