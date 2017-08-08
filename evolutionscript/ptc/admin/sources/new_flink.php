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
	$required_fields = array("title", "url", "expires");
	foreach ($required_fields as $k) {

		if (!$input->p[$k]) {
			serveranswer(0, "پرکردن همه فیلدها ضروری است");
			continue;
		}
	}

	$daterange = daterange($input->pc['expires']);
	$expires = $daterange[1];
	$data = array("title" => $input->pc['title'], "url" => $input->p['url'], "expires" => $expires, "status" => "Active");
	$insert = $db->insert("featured_link", $data);
	serveranswer(2, "تبلیغ اضافه شد . برای <a href='?view=manageflink'>مدیریت آن کلیک کنید</a>");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">افزودن آگهی لینکی ویژه</div>
<div class=\"site_content\">
<form method=\"post\" id=\"flinksform\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"a\" value=\"create\" />
<table class=\"widget-tbl\" width=\"100%\">
  <tr>
    <td width=\"150\">عنوان</td>
    <td><input name=\"title\" type=\"text\" id=\"title\" /></td>
    </tr>
  <tr>
    <td>آدرس مقصد</td>
    <td><input  style=\"direction:ltr !important;\" name=\"url\" type=\"text\" id=\"url\" value=\"http://\" /></td>
  </tr>
  <tr>
    <td>تاریخ انقضا</td>
    <td><input name=\"expires\" type=\"text\" id=\"credits\" placeholder=\"mm/dd/yyyy\" class=\"hannandate\" /></td>
  </tr>
  <tr>
  	<td></td>
    <td>
	<input type=\"submit\" name=\"create\" value=\"ذخیره\" />
    </td>
  </tr>
</table>
</form>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>