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
	$required_fields = array("title", "membership", "instructions", "url", "country", "value", "credits");
	foreach ($required_fields as $k) {

		if (!$input->pc[$k]) {
			serveranswer(0, "پرکردن همه فیلدها ضروری است");
			continue;
		}
	}

	$myadvalue = $db->fetchRow("SELECT * FROM ptsu_value WHERE id='" . $input->pc['value'] . "'");
	foreach ($input->p['country'] as $k => $v) {
		$countries .= $v . ",";
	}

	$memberships = ",";
	foreach ($input->p['membership'] as $k => $v) {
		$memberships .= $v . ",";
	}

	$set = array("user_id" => 0, "title" => $input->pc['title'], "descr" => $input->pc['descr'], "instructions" => $input->pc['instructions'], "url" => $input->p['url'], "value" => $myadvalue['value'], "membership" => $memberships, "credits" => $input->pc['credits'], "country" => $countries, "status" => "Active");
	$update = $db->insert("ptsu_offers", $set);
	serveranswer(2, "تبلیغ اضافه شد . برای <a href='?view=manageptsu'>مدیریت آن کلیک کنید</a>");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">افزودن پرداخت به ازای ثبت نام جدید</div>
<div class=\"site_content\">
    <form method=\"post\" id=\"ptsuform\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"a\" value=\"create\" />
    <table class=\"widget-tbl\" width=\"100%\">
      <tr>
        <td width=\"150\">عنوان</td>
        <td><input name=\"title\" type=\"text\" id=\"title\" /></td>
        </tr>
      <tr>
        <td>موضوع</td>
        <td><input name=\"descr\" type=\"text\" id=\"subtitle\" /></td>
      </tr>
      <tr>
        <td>آدرس مقصد</td>
        <td><input  style=\"direction:ltr !important;\"  name=\"url\" type=\"text\" id=\"url\" /></td>
      </tr>
      <tr>
        <td>دستور العمل ها</td>
        <td><textarea name=\"instructions\" style=\"width:90%; height:100px;\"></textarea></td>
      </tr>
      <tr>
        <td>پلن حساب کاربری</td>
        <td>
        <div class=\"widget-title\"><input type=\"checkbox\" name=\"membership_all\" value=\"all\" id=\"checkall\" />همه</div>
        <div class=\"widget-content\" style=\"overflow:auto; height:75px; background:#FFFFFF\">
            ";
$memlist = $db->query("SELECT id, name FROM membership ORDER BY id ASC");

while ($row = $db->fetch_array($memlist)) {
	$vermembership = strpos($data['membership'], "," . $row['id'] . ",");
	echo "<input type=\"checkbox\" name=\"membership[]\" value=\"" . $row['id'] . "\" class=\"checkall\" /> " . $row['name'] . "<br />";
}

echo "        </div>
        </td>
      </tr>
      <tr>
        <td>کشور</td>
        <td>

            <div class=\"widget-title\"><input type=\"checkbox\" name=\"country_all\" value=\"all\" id=\"checkall2\" />همه</div>
            <div class=\"widget-content\" style=\"overflow:auto; height:100px; background:#FFFFFF\">
            ";
$listcountry = $db->query("SELECT * FROM ip2nationCountries ORDER BY country ASC");
$ad_countries = explode(",", $data['country']);

while ($country = $db->fetch_array($listcountry)) {
	echo "<input type=\"checkbox\" name=\"country[]\" value=\"" . $country['country'] . "\" class=\"checkall2\" /> " . $country['country'] . "<br />";
}

echo "            </div>
            </td>
      </tr>
      <tr>
        <td>قیمت</td>
        <td><select name=\"value\" id=\"advalue\">
          ";
$valuelist = $db->query("SELECT * FROM ptsu_value ORDER BY value ASC");

while ($advalue = $db->fetch_array($valuelist)) {
	echo "<option value=\"" . $advalue['id'] . "\">" . $advalue['value'] . " تومان </option>";
}

echo "        </select></td>
      </tr>
      <tr>
        <td>اعتبار</td>
        <td><input name=\"credits\" type=\"text\" id=\"credits\" value=\"0\" /></td>
      </tr>
      <tr>
      	<td></td>
        <td>
        <input type=\"submit\" name=\"create\" value=\"ثبت\"  />
        </td>
      </tr>
    </table>
    </form>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>