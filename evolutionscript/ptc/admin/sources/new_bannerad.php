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


if ($input->p['a'] == "create") {
	verifyajax();
	verifydemo();
	$required_fields = array("title", "url", "banner", "credits");
	foreach ($required_fields as $k) {

		if (!$input->p[$k]) {
			serveranswer(0, "پرکردن همه فیلدها ضروری است");
			continue;
		}
	}

	$data = array("title" => $input->pc['title'], "url" => $input->p['url'], "img" => $input->p['banner'], "credits" => $input->pc['credits'], "status" => "Active");
	$insert = $db->insert("banner_ads", $data);
	serveranswer(2, "تبلیغ اضافه شد . برای <a href='?view=managebannerad'>مدیریت آن کلیک کنید</a>");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">افزودن تبلیغ بنری جدید</div>
<div class=\"site_content\">
<form method=\"post\" id=\"banneradsform\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"a\" value=\"create\" />
<table class=\"widget-tbl\" width=\"100%\">
  <tr>
    <td width=\"100\">عنوان</td>
    <td><input name=\"title\" type=\"text\" id=\"title\" /></td>
    </tr>
  <tr>
    <td>آدرس مقصد</td>
    <td><input style=\"direction:ltr !important;\" name=\"url\" type=\"text\" id=\"url\" value=\"http://\" /></td>
  </tr>
  <tr>
    <td>آدرس بنر</td>
    <td><input  style=\"direction:ltr !important;\"  name=\"banner\" type=\"text\" id=\"banner\" value=\"http://\" /></td>
  </tr>
  <tr>
    <td>اعتبار</td>
    <td><input name=\"credits\" type=\"text\" id=\"credits\" value=\"0\" /></td>
  </tr>
  <tr>
  	<td></td>
  	<td>
    <input type=\"submit\" name=\"create\" value=\"ذخیره\" class=\"orange\" />
    </td>
  </tr>
</table>
</form>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>