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


if (!$admin->permissions['utilities']) {
	header("location: ./");
	exit();
}


if ($input->p['do'] == "update") {
	verifyajax();
	verifydemo();
	$multi_registration = $input->pc['multi_registration'];
	$multi_login = $input->pc['multi_login'];
	$multi_country = $input->pc['multi_country'];
	$data = array("multi_registration" => $multi_registration, "multi_login" => $multi_login, "multi_country" => $multi_country);
	foreach ($data as $field => $value) {
		$db->query("UPDATE settings SET value='" . $value . "' WHERE field='" . $field . "'");
	}

	serveranswer(1, "تنظیمات بروز شد");
	$cache->delete("settings");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">تنظیمات آی پی چندگانه استفاده شده</div>
<div class=\"site_content\">
<form method=\"post\" id=\"formcontent\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"do\" value=\"update\" />
<table class=\"widget-tbl\" width=\"100%\">
  <tr>
    <td width=\"200\" align=\"right\">جلوگیری از ثبت نام آی پی</td>
    <td><input type=\"checkbox\" name=\"multi_registration\" value=\"yes\" ";

if ($settings['multi_registration'] == "yes") {
	echo "checked";
}

echo " />
برای فعال سازی جلوگیری از ثبت چندین حساب با یک آی پی تیک بزنید</td>
  </tr>
  <tr>
    <td align=\"right\">جلوگیری از ورود</td>
    <td><input type=\"checkbox\" name=\"multi_login\" value=\"yes\" ";

if ($settings['multi_login'] == "yes") {
	echo "checked";
}

echo " />
برای فعال سازی جلوگیری از ورود چندین حساب با یک آی پی تیک بزنید</td>
  </tr>
  <tr>
    <td align=\"right\">جلوگیری از چندین کشور</td>
    <td><input type=\"checkbox\" name=\"multi_country\" value=\"yes\" ";

if ($settings['multi_country'] == "yes") {
	echo "checked";
}

echo " />
برای فعال سازی تیک بزنید که اگر کشور کاربر هنگام ورود با کشور موقع ثبت نام متفاوت بود از ورود او جلوگیری شود.البته نیاز به آپدیت ماهانه دیتابیس اسکریپت است .</td>
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


        ";
include SOURCES . "footer.php";
echo " ";
?>