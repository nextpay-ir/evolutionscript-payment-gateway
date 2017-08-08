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


if ($input->p['do'] == "update1") {
	verifyajax();
	verifydemo();
	$set = array("captcha_type", "captcha_contact", "captcha_login", "captcha_register", "captcha_surfbar");
	foreach ($set as $k) {
		$updarray = array("value" => $input->pc[$k]);
		$db->update("settings", $updarray, ("field='" . $k . "'"));
	}

	$cache->delete("settings");
	serveranswer(1, "تنظیمات بروز شد");
}
else {
	if ($input->p['do'] == "update2") {
		verifyajax();
		verifydemo();
		$set = array("recaptcha_publickey", "recaptcha_privatekey", "recaptcha_theme", "captcha_surfbar");
		foreach ($set as $k) {
			$updarray = array("value" => $input->pc[$k]);
			$db->update("settings", $updarray, ("field='" . $k . "'"));
		}

		$cache->delete("settings");
		serveranswer(1, "تنظیمات بروز شد");
	}
	else {
		if ($input->p['do'] == "update3") {
			verifyajax();
			verifydemo();
			$set = array("solvemedia_ckey", "solvemedia_vkey", "solvemedia_hkey");
			foreach ($set as $k) {
				$updarray = array("value" => $input->pc[$k]);
				$db->update("settings", $updarray, ("field='" . $k . "'"));
			}

			$cache->delete("settings");
			serveranswer(1, "تنظیمات بروز شد");
		}
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">تنظیمات کپچا</div>
<div class=\"site_content\">

    <div class=\"info_box\">
    <strong>تاییدیه ی <a href=\"http://www.google.com/recaptcha\" target=\"_blank\">reCAPTCHA&trade;</a></strong><br />
      تصویری شامل 2 کلمه به کاربر نمایش داده میشود<br /> که شامل صدا برای استفاده افراد روشندل است
      </div>
<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tabs-1\">کلی</a></li>
        <li><a href=\"#tabs-2\">تنظیمات ریکپچا</a></li>
        <li><a href=\"#tabs-3\">تنظیمات کپچای رسانه ای</a></li>
    </ul>
    <div id=\"tabs-1\">
        <form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
        <input type=\"hidden\" name=\"do\" value=\"update1\" />
        <table class=\"widget-tbl\" width=\"100%\">
          <tr>
            <td width=\"300\"><strong>تاییدکننده تصویر</strong><br />
            <span style=\"font-weight:normal\">
              تصویر متشکل از حروف مختلف فونت / شکل / اندازه را به کاربر نشان داده میشود.<br />تصویر را میتوانید از طریق تنظمات مدیریت کنید
              </span>
              </td>
            <td valign=\"top\">
            ";
$captcha = array(0 => "غیرفعال", 1 => "تصویر", 2 => "ریکپچا", 3 => "کپچای رسانه ای");
foreach ($captcha as $id => $value) {

	if ($id == $settings['captcha_type']) {
		echo "<input type=\"radio\" name=\"captcha_type\" id=\"" . $value . "\" value=\"" . $id . "\" checked />";
		echo "<label for=\"" . $value . "\">" . $value . "</label><br>";
		continue;
	}

	echo "<input type=\"radio\" name=\"captcha_type\" id=\"" . $value . "\" value=\"" . $id . "\" />";
	echo "<label for=\"" . $value . "\">" . $value . "</label><br>";
}

echo "      </td>
            </tr>
          <tr>
            <td align=\"right\">استفاده از کپچا در ....</td>
            <td valign=\"top\">
            ";
$captchaon = array("captcha_contact" => "صفحه پشتیبانی", "captcha_login" => "صفحه ورود", "captcha_register" => "صفحه ثبت نام");
foreach ($captchaon as $field => $value) {

	if ($settings[$field] == "yes") {
		echo "<input type=\"checkbox\" name=\"" . $field . "\" id=\"" . $field . "\" value=\"yes\" checked />";
		echo "<label for=\"" . $value . "\">" . $value . "</label><br>";
		continue;
	}

	echo "<input type=\"checkbox\" name=\"" . $field . "\" id=\"" . $field . "\" value=\"yes\" />";
	echo "<label for=\"" . $value . "\">" . $value . "</label><br>";
}

echo "    </td>
          </tr>
          <tr>
          	<td></td>
            <td><input type=\"submit\" name=\"create\" value=\"ذخیره\" /></td>
          </tr>
        </table>
        </form>

    </div>
    <div id=\"tabs-2\">
    	<form id=\"frm2\" onsubmit=\"return submitform(this.id);\">
        <input type=\"hidden\" name=\"do\" value=\"update2\" />
        <table class=\"widget-tbl\" width=\"100%\">
          <tr>
            <td>reCAPTCHA&trade; public key</td>
            <td valign=\"top\"><input type=\"text\" name=\"recaptcha_publickey\" value=\"";
echo $settings['recaptcha_publickey'];
echo "\" class=\"input_text2\" /></td>
          </tr>
          <tr>
            <td>reCAPTCHA&trade; private key</td>
            <td valign=\"top\">
              <input type=\"text\" name=\"recaptcha_privatekey\" value=\"";
echo $settings['recaptcha_privatekey'];
echo "\" class=\"input_text2\" /></td>
          </tr>
          <tr>
            <td valign=\"top\">reCAPTCHA&trade; تم</td>
            <td valign=\"top\">
            ";
$recaptchatheme = array("red" => "قرمز", "white" => "سفید", "blackglass" => "تیره شیشه ای", "clean" => "روشن");
foreach ($recaptchatheme as $theme => $value) {

	if ($settings['recaptcha_theme'] == $theme) {
		echo "<input type=\"radio\" name=\"recaptcha_theme\" value=\"" . $theme . "\" id=\"" . $value . "\" checked />";
		echo "<label for=\"" . $value . "\">" . $value . "</label><br>";
		continue;
	}

	echo "<input type=\"radio\" name=\"recaptcha_theme\" value=\"" . $theme . "\" id=\"" . $value . "\" />";
	echo "<label for=\"" . $value . "\">" . $value . "</label><br>";
}

echo "            </td>
          </tr>
          <tr>
          	<td></td>
            <td><input type=\"submit\" name=\"create\" value=\"ذخیره\" /></td>
          </tr>
        </table>
        </form>
    </div>
    <div id=\"tabs-3\">
    	<form id=\"frm3\" onsubmit=\"return submitform(this.id);\">
        <input type=\"hidden\" name=\"do\" value=\"update3\" />
        <table class=\"widget-tbl\" width=\"100%\">
          <tr>
            <td>Challenge Key (C-key)</td>
            <td valign=\"top\"><input type=\"text\" name=\"solvemedia_ckey\" value=\"";
echo $settings['solvemedia_ckey'];
echo "\" class=\"input_text2\" /></td>
          </tr>
          <tr>
            <td>Verification Key (V-key)</td>
            <td valign=\"top\">
              <input type=\"text\" name=\"solvemedia_vkey\" value=\"";
echo $settings['solvemedia_vkey'];
echo "\" class=\"input_text2\" /></td>
          </tr>
          <tr>
            <td>اهراز هویت : Hash Key (H-key)</td>
            <td valign=\"top\">
              <input type=\"text\" name=\"solvemedia_hkey\" value=\"";
echo $settings['solvemedia_hkey'];
echo "\" class=\"input_text2\" /></td>
          </tr>

          <tr>
          	<td></td>
            <td><input type=\"submit\" name=\"create\" value=\"ذخیره\" /></td>
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