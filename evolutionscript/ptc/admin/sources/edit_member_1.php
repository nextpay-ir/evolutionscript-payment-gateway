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

echo "<form method=\"post\" id=\"editmemberform\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"a\" value=\"update\" />
<table cellpadding=\"4\" width=\"100%\" class=\"widget-tbl\">
  <tr>
    <td width=\"200\">نام کامل</td>
    <td><input name=\"fullname\" type=\"text\" value=\"";
echo $member['fullname'];
echo "\" /></td>
    </tr>
  <tr>
    <td>پسورد جدید</td>
    <td><input name=\"password\" type=\"text\" /> در صورتی که نمیخواهید تغییر دهید خالی بگذارید</td>
  </tr>
  <tr>
    <td>ایمیل</td>
    <td><input name=\"email\" type=\"text\" value=\"";
echo $member['email'];
echo "\" /></td>
  </tr>
  <tr>
    <td>کشور</td>
    <td><select name=\"country\">
    <option value=\"-\">-</option>
      ";
$countrylist = $db->query("SELECT country FROM ip2nationCountries ORDER BY country ASC");

while ($list = $db->fetch_array($countrylist)) {
	if ($member['country'] == $list['country']) {
		echo "<option value=\"" . $list['country'] . "\" selected>" . $list['country'] . "</option>";
	}

	echo "<option value=\"" . $list['country'] . "\">" . $list['country'] . "</option>";
}

echo "    </select></td>
  </tr>
  ";

if (is_array($gateway)) {
	foreach ($gateway as $k => $v) {
		echo "  <tr>
    <td>شماره حساب / کارت ";
		echo $v['name'];
		echo ":</td>
    <td><input type=\"text\" name=\"gateway[";
		echo $v['id'];
		echo "]\" value=\"";
		echo $user_gateway[$v['id']];
		echo "\" /></td>
  </tr>
  ";
	}
}

echo "
  <tr>
    <td>ارجاع دهنده</td>
    <td>

    ";

if ($member['ref1'] == 0) {
	$refname = "";
}
else {
	$refname = $db->fetchOne("SELECT username FROM members WHERE id=" . $member['ref1']);
}

echo "         <input name=\"refname\" type=\"text\" id=\"refname\" value=\"";
echo $refname;
echo "\" />

         درصورتی که قصد اختصاص ندارید خالی بگذارید</td>
  </tr>
  <tr>
    <td>نوع پلن حساب کاربری</td>
    <td>
        <select name=\"type\" id=\"type\">
            ";
$membershiplist = $db->query("SELECT id, name FROM membership ORDER BY price ASC");

while ($membership = $db->fetch_array($membershiplist)) {
	if ($membership['id'] == $member['type']) {
		echo "<option value=\"" . $membership['id'] . "\" selected>" . $membership['name'] . "</option>";
	}

	echo "<option value=\"" . $membership['id'] . "\">" . $membership['name'] . "</option>";
}

echo "        </select>    </td>
  </tr>
  <tr>
    <td>انقضای حساب کاربری</td>
    <td>
        ";

if ($member['upgrade_ends'] == 0) {
	echo "هرگز";
}
else {
	echo jdate("d F Y ساعت h:i A", $member['upgrade_ends']);
}

echo "    </td>
  </tr>
  <tr>
    <td>تمدید عضویت : <br />مقدار را برحسب روز وارد کنین<br /></td>
    <td><input type=\"text\" name=\"extend\" id=\"extend\" value=\"-1\" />
      (0 = Never Expires, -1 = does not make changes)</td>
  </tr>
  <tr>
    <td>درآمد به تومان</td>
    <td><input type=\"text\" name=\"money\" id=\"money\" value=\"";
echo $member['money'];
echo "\" /></td>
  </tr>
  <tr>
    <td>مبلغ شارژ به تومان</td>
    <td><input type=\"text\" name=\"purchase_balance\" value=\"";
echo $member['purchase_balance'];
echo "\" /></td>
  </tr>
  <tr>
    <td>امتیاز</td>
    <td><input type=\"text\" name=\"points\" value=\"";
echo $member['points'];
echo "\" /></td>
  </tr>
  <tr>
    <td>اعتبار تبلیغ کلیکی</td>
    <td><input type=\"text\" name=\"ad_credits\" id=\"ad_credits\" value=\"";
echo $member['ad_credits'];
echo "\" /></td>
  </tr>
  <tr>
    <td>اعتبار تبلیغ ورودی</td>
    <td><input type=\"text\" name=\"loginads_credits\" id=\"loginads_credits\" value=\"";
echo $member['loginads_credits'];
echo "\" /></td>
  </tr>

  <tr>
    <td>اعتبار تبلیغ ویژه</td>
    <td><input type=\"text\" name=\"fads_credits\" id=\"fads_credits\" value=\"";
echo $member['fads_credits'];
echo "\" /></td>
  </tr>
  <tr>
    <td>اعتبار تبلیغ لینک ویژه</td>
    <td><input type=\"text\" name=\"flink_credits\" id=\"flink_credits\" value=\"";
echo $member['flink_credits'];
echo "\" /></td>
  </tr>
  <tr>
    <td>اعتبار تبلیغ بنری</td>
    <td><input type=\"text\" name=\"banner_credits\" id=\"banner_credits\" value=\"";
echo $member['banner_credits'];
echo "\" /></td>
  </tr>
  <tr>
    <td>اعتبار پرداخت به ازای ثبت نام</td>
    <td><input type=\"text\" name=\"ptsu_credits\" id=\"ptsu_credits\" value=\"";
echo $member['ptsu_credits'];
echo "\" /></td>
  </tr>
  <tr>
    <td>پرداخت به ازای ثبت نام منع شده</td>
    <td><input type=\"text\" name=\"ptsu_denied\" value=\"";
echo $member['ptsu_denied'];
echo "\" /></td>
  </tr>
  <tr>
    <td>زیر مجموعه</td>
    <td><a href=\"./?view=members&do=search&ref1=";
echo $member['username'];
echo "\">";
echo $member['referrals'];
echo "</a></td>
  </tr>
  <tr>
    <td>زیر مجموعه اجاره ای</td>
    <td><a href=\"./?view=members&do=search&rented=";
echo $member['username'];
echo "\">";
echo $member['rented_referrals'];
echo "</a></td>
  </tr>
  <tr>
    <td>یادداشت مدیر<br />(برای استفاده داخلی):</td>
    <td><textarea name=\"adminnotes\" style=\"width:100%; height:100px\">";
echo $member['adminnotes'];
echo "</textarea></td>
  </tr>


  <tr>
    <td colspan=\"2\" align=\"center\">
        <input type=\"submit\" name=\"btn\" value=\"بروزرسانی\" />
    </td>
  </tr>
</table>
</form>";
?>