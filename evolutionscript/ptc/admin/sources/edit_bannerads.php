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

$data = $db->fetchRow("SELECT * FROM banner_ads WHERE id=" . $input->gc['edit']);

if ($input->p['a'] == "update") {
	verifyajax();
	verifydemo();
	$required_fields = array("title", "url", "banner", "credits");
	foreach ($required_fields as $k) {

		if (!$input->pc[$k]) {
			serveranswer(0, "لطفا همه فیلدهای ضروری را پر نمایید");
			continue;
		}
	}

	$set = array("title" => $input->pc['title'], "url" => $input->p['url'], "img" => $input->p['banner'], "credits" => $input->pc['credits'], "status" => "Active");
	$insert = $db->update("banner_ads", $set, "id=" . $data['id']);
	serveranswer(1, "تنظیمات ذخیره شد");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">ویرایش تبلیغات بنری</div>

<div class=\"site_content\">
    <form method=\"post\" id=\"banneradsform\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"a\" value=\"update\" />
    <table width=\"100%\" class=\"widget-tbl\">
      <tr>
        <td width=\"100\">عنوان</td>
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
        <td>آدرس تصویر بنر</td>
        <td><input style=\"direction:ltr !important;\" name=\"banner\" type=\"text\" id=\"banner\" value=\"";
echo $data['img'];
echo "\" /></td>
      </tr>
      <tr>
        <td>اعتبار</td>
        <td><input name=\"credits\" type=\"text\" id=\"credits\" value=\"";
echo $data['credits'];
echo "\" /></td>
      </tr>
      <tr>
        <td></td>
        <td>
        <input type=\"submit\" name=\"create\" value=\"ثبت\" />

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