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

$data = $db->fetchRow("SELECT * FROM ptsu_offers WHERE id=" . $input->gc['edit']);

if ($input->p['a'] == "update") {
	verifyajax();
	verifydemo();
	$required_fields = array("title", "membership", "country", "instructions", "url", "value", "credits");
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
    $memberships.= $v . ",";
  }

	$set = array("title" => $input->pc['title'], "descr" => $input->pc['descr'], "instructions" => $input->pc['instructions'], "url" => $input->pc['url'], "value" => $myadvalue['value'], "membership" => $memberships, "credits" => $input->pc['credits'], "country" => $countries);
	$update = $db->update("ptsu_offers", $set, "id=" . $data['id']);
	serveranswer(1, "بروز رسانی شد");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">ویرایش پرداخت به ازای ثبت نام</div>

<div class=\"site_content\">
    <form method=\"post\" id=\"ptsuform\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"a\" value=\"update\" />
    <table class=\"widget-tbl\" width=\"100%\">
      <tr>
        <td width=\"150\">عنوان</td>
        <td><input name=\"title\" type=\"text\" id=\"title\" value=\"";
echo $data['title'];
echo "\" /></td>
        </tr>
      <tr>
        <td>موضوع</td>
        <td><input name=\"descr\" type=\"text\" id=\"subtitle\" value=\"";
echo $data['descr'];
echo "\" /></td>
      </tr>
      <tr>
        <td>آدرس مقصد</td>
        <td><input style=\"direction:ltr !important;\" name=\"url\" type=\"text\" id=\"url\" value=\"";
echo $data['url'];
echo "\" /></td>
      </tr>
      <tr>
        <td>دستورالعمل</td>
        <td><textarea name=\"instructions\" rows=\"5\" cols=\"50\">";
echo $data['instructions'];
echo "</textarea></td>
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

	if ($vermembership === false) {
		echo "<input type=\"checkbox\" name=\"membership[]\" value=\"" . $row['id'] . "\" class=\"checkall\" /> " . $row['name'] . "<br />";
	}

	echo "<input type=\"checkbox\" name=\"membership[]\" value=\"" . $row['id'] . "\" class=\"checkall\" checked /> " . $row['name'] . "<br />";
}

echo "    </div>
	</td>
  </tr>
      <tr>
        <td>کشور مقصد</td>
        <td>

            <div class=\"widget-title\"><input type=\"checkbox\" name=\"country_all\" value=\"all\" id=\"checkall2\" />تمام کشورها</div>
            <div class=\"widget-content\" style=\"overflow:auto; height:100px; background:#FFFFFF\">
            ";
$listcountry = $db->query("SELECT * FROM ip2nationCountries ORDER BY country ASC");
$ad_countries = explode(",", $data['country']);

while ($country = $db->fetch_array($listcountry)) {
	if (!in_array($country['country'], $ad_countries)) {
		echo "<input type=\"checkbox\" name=\"country[]\" value=\"" . $country['country'] . "\" class=\"checkall2\" /> " . $country['country'] . "<br />";
	}

	echo "<input type=\"checkbox\" name=\"country[]\" value=\"" . $country['country'] . "\" class=\"checkall2\" checked /> " . $country['country'] . "<br />";
}

echo "            </div>
            </td>
      </tr>
      <tr>
        <td>مقدار پرداخت به ازای ثبت نام</td>
        <td><select name=\"value\" class=\"input_text2\" id=\"advalue\">
          ";

$valuelist = $db->query("SELECT * FROM ptsu_value ORDER BY value ASC");

while ($advalue = $db->fetch_array($valuelist)) {
	if ($advalue['value'] == $data['value']) {
		echo "<option value=\"" . $advalue['id'] . "\" selected>" . $advalue['value'] . " تومان </option>";
	}

	echo "<option value=\"" . $advalue['id'] . "\">" . $advalue['value'] . " تومان </option>";
}

echo "        </select></td>
      </tr>
      <tr>
        <td>اعتبار</td>
        <td><input name=\"credits\" type=\"text\" class=\"input_text2\" id=\"credits\" value=\"";
echo $data['credits'];
echo "\" /></td>
      </tr>
      <tr>
        <td></td>
        <td>
        <input type=\"submit\" name=\"create\" value=\"بروزرسانی\" />
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