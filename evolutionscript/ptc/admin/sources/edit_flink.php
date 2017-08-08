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

$data = $db->fetchRow("SELECT * FROM featured_link WHERE id=" . $input->gc['edit']);

if ($input->p['a'] == "update") {
	verifyajax();
	verifydemo();
	$required_fields = array("title", "url", "expires");
	foreach ($required_fields as $k) {

		if (!$input->pc[$k]) {
			serveranswer(0, "پر کردن همه فیلدها ضروری است");
			continue;
		}
	}

	$daterange = daterange($input->pc['expires']);
	$expires = $daterange[1];

	if (TIMENOW < $expires) {
		$status = "Active";
	}
	else {
		$status = $data['status'];
	}

	$set = array("title" => $input->pc['title'], "url" => $input->p['url'], "expires" => $expires, "status" => $status);
	$insert = $db->update("featured_link", $set, "id=" . $data['id']);
	serveranswer(1, "بروز رسانی انجام شد");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">ویرایش تبلیغ لینکی ویژه</div>
<div class=\"site_content\">
	<form method=\"post\" id=\"flinksform\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"a\" value=\"update\" />
    <table class=\"widget-tbl\" width=\"100%\">
      <tr>
        <td width=\"150\">عنوان</td>
        <td><input name=\"title\" type=\"text\" id=\"title\" value=\"";
echo $data['title'];
echo "\" /></td>
        </tr>
      <tr>
        <td>آدرس مقصد</td>
        <td><input style=\"direction:ltr !important;\" name=\"url\" type=\"text\" id=\"url\" value=\"";
echo $data['url'];
echo "\" /></td>
      </tr>
      <tr>
        <td>تاریخ انقضا</td>
        <td><input name=\"expires\" type=\"text\" id=\"credits\" value=\"";
echo date("m/d/Y", $data['expires']);
echo "\" class=\"hannandate\" /></td>
      </tr>
      <tr>
      	        <td></td>
        <td>
        <input type=\"submit\" name=\"create\" value=\"ذخیره\" />
         <input type=\"button\" name=\"btn\" value=\"بازگشت\" onclick=\"history.back();\" />
        </td>
      </tr>
    </table>
    </form>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>