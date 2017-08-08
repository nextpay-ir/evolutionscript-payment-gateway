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

$data = $db->fetchRow("SELECT * FROM membership WHERE id=" . $input->gc['edit']);

if ($input->p['do'] == "edit_membership") {
	verifyajax();
	verifydemo();

	if ($data['id'] != 1) {
		$set = array("name" => $input->pc['name'], "price" => $input->pc['price'], "click" => $input->pc['click'], "ref_click" => $input->pc['ref_click'], "minimum_payout" => $input->pc['minimum_payout'], "ref_upgrade" => $input->pc['ref_upgrade'], "ref_purchase" => $input->pc['ref_purchase'], "directref_limit" => $input->pc['directref_limit'], "duration" => $input->pc['duration'], "rentedref_limit" => $input->pc['rentedref_limit'], "recycle_cost" => $input->pc['recycle_cost'], "rent_time" => $input->pc['rent_time'], "rent_pack" => $input->pc['rent_pack'], "cashout_time" => $input->pc['cashout_time'], "referral_deletion" => $input->pc['referral_deletion'], "active" => $input->pc['active'], "instant_withdrawal" => $input->pc['instant_withdrawal'], "max_clicks" => $input->pc['max_clicks'], "max_withdraw" => $input->pc['max_withdraw'], "rent250" => $input->pc['rent250'], "rent500" => $input->pc['rent500'], "rent750" => $input->pc['rent750'], "rent1000" => $input->pc['rent1000'], "rent1250" => $input->pc['rent1250'], "rent1500" => $input->pc['rent1500'], "rent1750" => $input->pc['rent1750'], "rentover" => $input->pc['rentover'], "autopay250" => $input->pc['autopay250'], "autopay500" => $input->pc['autopay500'], "autopay750" => $input->pc['autopay750'], "autopay1000" => $input->pc['autopay1000'], "autopay1250" => $input->pc['autopay1250'], "autopay1500" => $input->pc['autopay1500'], "autopay1750" => $input->pc['autopay1750'], "autopayover" => $input->pc['autopayover'], "point_enable" => $input->pc['point_enable'], "point_ref" => $input->pc['point_ref'], "point_ptc" => $input->pc['point_ptc'], "point_post" => $input->pc['point_post'], "point_ptsu" => $input->pc['point_ptsu'], "point_deposit" => $input->pc['point_deposit'], "point_upgrade" => $input->pc['point_upgrade'], "point_upgraderate" => $input->pc['point_upgraderate'], "point_purchasebalance" => $input->pc['point_purchasebalance'], "point_cashrate" => $input->pc['point_cashrate']);
	}
	else {
		$set = array("name" => $input->pc['name'], "click" => $input->pc['click'], "ref_click" => $input->pc['ref_click'], "minimum_payout" => $input->pc['minimum_payout'], "ref_upgrade" => $input->pc['ref_upgrade'], "ref_purchase" => $input->pc['ref_purchase'], "directref_limit" => $input->pc['directref_limit'], "rentedref_limit" => $input->pc['rentedref_limit'], "recycle_cost" => $input->pc['recycle_cost'], "rent_time" => $input->pc['rent_time'], "rent_pack" => $input->pc['rent_pack'], "cashout_time" => $input->pc['cashout_time'], "referral_deletion" => $input->pc['referral_deletion'], "active" => $input->pc['active'], "instant_withdrawal" => $input->pc['instant_withdrawal'], "max_clicks" => $input->pc['max_clicks'], "max_withdraw" => $input->pc['max_withdraw'], "rent250" => $input->pc['rent250'], "rent500" => $input->pc['rent500'], "rent750" => $input->pc['rent750'], "rent1000" => $input->pc['rent1000'], "rent1250" => $input->pc['rent1250'], "rent1500" => $input->pc['rent1500'], "rent1750" => $input->pc['rent1750'], "rentover" => $input->pc['rentover'], "autopay250" => $input->pc['autopay250'], "autopay500" => $input->pc['autopay500'], "autopay750" => $input->pc['autopay750'], "autopay1000" => $input->pc['autopay1000'], "autopay1250" => $input->pc['autopay1250'], "autopay1500" => $input->pc['autopay1500'], "autopay1750" => $input->pc['autopay1750'], "autopayover" => $input->pc['autopayover'], "point_enable" => $input->pc['point_enable'], "point_ref" => $input->pc['point_ref'], "point_ptc" => $input->pc['point_ptc'], "point_post" => $input->pc['point_post'], "point_ptsu" => $input->pc['point_ptsu'], "point_deposit" => $input->pc['point_deposit'], "point_upgrade" => $input->pc['point_upgrade'], "point_upgraderate" => $input->pc['point_upgraderate'], "point_purchasebalance" => $input->pc['point_purchasebalance'], "point_cashrate" => $input->pc['point_cashrate']);
	}

	$db->update("membership", $set, "id=" . $data['id']);
	serveranswer(3, "تغییرات بروز رسانی شد.");
}

include SOURCES . "header.php";
echo "
<div class=\"site_title\">ویرایش پلن حساب کاربری</div>
<div class=\"site_content\">
<form method=\"post\" id=\"newfrm\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"do\" value=\"edit_membership\" />
		        <table class=\"widget-tbl\" width=\"100%\">
        	<tr>

            	<td width=\"300\" align=\"right\">نام</td>
                <td><input name=\"name\" type=\"text\" value=\"";
echo $data['name'];
echo "\" /></td>
           	</tr>
            ";

if ($data['id'] != 1) {
	echo "            <tr>
            	<td align=\"right\">دوره</td>
                <td>
                <input name=\"duration\" type=\"text\" value=\"";
	echo $data['duration'];
	echo "\"> روز</td>
            </tr>
        	<tr>
        	  <td align=\"right\"> قیمت</td>
      	      <td><input name=\"price\" type=\"text\" value=\"";
	echo $data['price'];
	echo "\" /></td>
       	  </tr>
          ";
}

echo "        	<tr>
        	  <td align=\"right\"> کلیک</td>
        	  <td><input name=\"click\" type=\"text\" value=\"";
echo $data['click'];
echo "\" />%</td>
       	  </tr>
          <tr>
          	<td align=\"right\"> ماکزیمم کلیک روزانه</td>
<td><input name=\"max_clicks\" type=\"text\" id=\"max_clicks\" value=\"";
echo $data['max_clicks'];
echo "\"> 0 = نامحدود</td>
          </tr>
        	<tr>
        	  <td align=\"right\"> کلیک زیرمجموعه ها</td>
        	  <td><input name=\"ref_click\" type=\"text\" id=\"ref_click\" value=\"";
echo $data['ref_click'];
echo "\" />%</td>
       	  </tr>


        	<tr>
        	  <td align=\"right\">کمیسیون خرید</td>
        	  <td><input name=\"ref_purchase\" type=\"text\" value=\"";
echo $data['ref_purchase'];
echo "\" />%</td>
      	  </tr>
        	<tr>
        	  <td align=\"right\">کمیسیون ارتقای حساب</td>
        	  <td><input name=\"ref_upgrade\" type=\"text\" value=\"";
echo $data['ref_upgrade'];
echo "\" /></td>
      	  </tr>
        	<tr>
        	  <td align=\"right\">محدودیت زیرمجموعه مستقیم</td>
        	  <td><input name=\"directref_limit\" type=\"text\"  value=\"";
echo $data['directref_limit'];
echo "\" /> -1 = نامحدود</td>
      	  </tr>
                  	<tr>
        	  <td align=\"right\">هزینه حذف زیرمجموعه های مستقیم</td>
        	  <td><input name=\"referral_deletion\" type=\"text\" value=\"";
echo $data['referral_deletion'];
echo "\" /> 0 = رایگان</td>
      	  </tr>

        	<tr>
        	  <td align=\"right\">فعال سازی پلن؟</td>
        	  <td><input type=\"checkbox\" name=\"active\" value=\"yes\"   ";
echo $data['active'] == "yes" ? "checked" : "";
echo ">
       برای فعال سازی تیک بزنید</td>
      	  </tr>

          <tr>
          	<td colspan=\"2\" class=\"widget-title\">تنظیمات زیرمجموعه های اجاره ای</td>
          </tr>
        	<tr>
        	  <td align=\"right\">پکیج زیرمجموعه های اجاره ای</td>
        	  <td><input name=\"rent_pack\" type=\"text\" value=\"";
echo $data['rent_pack'];
echo "\" /> مثلا: 5,10,50,100</td>
      	  </tr>

        	<tr>
        	  <td align=\"right\">محدودیت زیرمجموعه اجاره ای </td>
        	  <td><input name=\"rentedref_limit\" type=\"text\" value=\"";
echo $data['rentedref_limit'];
echo "\" /> -1 = نامحدود</td>
      	  </tr>
        	<tr>
        	  <td align=\"right\">هزینه تمدید :</td>
        	  <td><input name=\"recycle_cost\" type=\"text\" value=\"";
echo $data['recycle_cost'];
echo "\" /> </td>
      	  </tr>
        	<tr>
        	  <td align=\"right\">تعداد زمان اجاره زیرمجموعه</td>
        	  <td><input name=\"rent_time\" type=\"text\" value=\"";
echo $data['rent_time'];
echo "\" /> 0 = غیرفعال</td>
      	  </tr>
          <tr>
          	<td colspan=\"2\">
   	<table width=\"100%\" class=\"widget-tbl\">
            <tr>
            	<td class=\"widget-title\" align=\"center\">زیرمجموعه ای</td>
                <td class=\"widget-title\" align=\"center\">ماهانه</td>
                <td class=\"widget-title\" align=\"center\">پرداخت خودکار</td>
            </tr>
            <tr>
            <td align=\"center\">0 -&gt; 250</td>
            <td align=\"center\"><input type=\"text\" name=\"rent250\" value=\"";
echo $data['rent250'];
echo "\" /></td>
            <td align=\"center\"><input type=\"text\" name=\"autopay250\" value=\"";
echo $data['autopay250'];
echo "\" /></td>
            </tr>
            <tr>
            <td align=\"center\">251 -&gt; 500</td>
            <td align=\"center\"><input type=\"text\" name=\"rent500\" value=\"";
echo $data['rent500'];
echo "\" /></td>
            <td align=\"center\"><input type=\"text\" name=\"autopay500\" value=\"";
echo $data['autopay500'];
echo "\" /></td>
            </tr>
            <tr>
            <td align=\"center\">501 -&gt; 750</td>
            <td align=\"center\"><input type=\"text\" name=\"rent750\" value=\"";
echo $data['rent750'];
echo "\" /></td>
            <td align=\"center\"><input type=\"text\" name=\"autopay750\" value=\"";
echo $data['autopay750'];
echo "\" /></td>
            </tr>
            <tr>
            <td align=\"center\">751 -&gt; 1000</td>
            <td align=\"center\"><input type=\"text\" name=\"rent1000\" value=\"";
echo $data['rent1000'];
echo "\" /></td>
            <td align=\"center\"><input type=\"text\" name=\"autopay1000\" value=\"";
echo $data['autopay1000'];
echo "\" /></td>
            </tr>
            <tr>
            <td align=\"center\">1001 -&gt; 1250</td>
            <td align=\"center\"><input type=\"text\" name=\"rent1250\" value=\"";
echo $data['rent1250'];
echo "\" /></td>
            <td align=\"center\"><input type=\"text\" name=\"autopay1250\" value=\"";
echo $data['autopay1250'];
echo "\" /></td>
            </tr>
            <tr>
            <td align=\"center\">1251 -&gt; 1500</td>
            <td align=\"center\"><input type=\"text\" name=\"rent1500\" value=\"";
echo $data['rent1500'];
echo "\" /></td>
            <td align=\"center\"><input type=\"text\" name=\"autopay1500\" value=\"";
echo $data['autopay1500'];
echo "\" /></td>
            </tr>
            <tr>
            <td align=\"center\">1501 -&gt; 1750</td>
            <td align=\"center\"><input type=\"text\" name=\"rent1750\" value=\"";
echo $data['rent1750'];
echo "\" /></td>
            <td align=\"center\"><input type=\"text\" name=\"autopay1750\" value=\"";
echo $data['autopay1750'];
echo "\" /></td>
            </tr>
            <tr>
            <td align=\"center\">بیش از 1750</td>
            <td align=\"center\"><input type=\"text\" name=\"rentover\" value=\"";
echo $data['rentover'];
echo "\" /></td>
            <td align=\"center\"><input name=\"autopayover\" type=\"text\" value=\"";
echo $data['autopayover'];
echo "\" /></td>
            </tr>
        </table>
            </td>
          </tr>
          <tr>
          	<td colspan=\"2\" class=\"widget-title\">تنظیمات تسویه حساب</td>
          </tr>
        	<tr>
        	  <td align=\"right\">ماکزیمم تسویه حساب روزانه : </td>
        	  <td><input name=\"max_withdraw\" type=\"text\" value=\"";
echo $data['max_withdraw'];
echo "\"> 0 = بدون محدودیت</td>
       	  </tr>
        	<tr>
        	  <td align=\"right\">حداقل پرداخت : </td>
        	  <td><input name=\"minimum_payout\" type=\"text\" value=\"";
echo $data['minimum_payout'];
echo "\"> مثلا : 1.00,3.00,4.00</td>
       	  </tr>
        	<tr>
        	  <td align=\"right\">تعداد زمان تسویه حساب</td>
        	  <td><input name=\"cashout_time\" type=\"text\" value=\"";
echo $data['cashout_time'];
echo "\" /> 0 = غیر فعال</td>
      	  </tr>
        	<tr>
        	  <td align=\"right\">تسویه حساب فوری : توجه : این قسمت در ایران کاربرد ندارد و منظور پرداخت خودکار پول از شماره حساب مدیریت به کاربر است پس آن را غیر فعال کنید زیر امکان نرم افزاری آن در سیستم وجود ندارد  </td>
              <td><input type=\"checkbox\" name=\"instant_withdrawal\" value=\"yes\"  ";
echo $data['instant_withdrawal'] == "yes" ? "checked" : "";
echo ">
      برای فعال سازی تیک بزنید</td>
      	  </tr>
          <tr>
          	<td colspan=\"2\" class=\"widget-title\">سیستم امتیاز</td>
          </tr>
        	<tr>
        	  <td align=\"right\">آیا سیستم امتیازی فعال شود ؟</td>
              <td><select name=\"point_enable\">
              <option value=\"1\" ";
echo $data['point_enable'] == 1 ? "selected" : "";
echo ">بله</option>
              <option value=\"0\" ";
echo $data['point_enable'] != 1 ? "selected" : "";
echo ">خیر</option>
              </select></td>
      	  </tr>
        	<tr>
        	  <td align=\"right\"><span class=\"pointer\" title=\"The total number of points credited to member per direct referral signup.\">امتیاز برای هر زیرمجموعه مستقیم</span></td>
              <td><input type=\"text\" name=\"point_ref\" value=\"";
echo $data['point_ref'];
echo "\" /></td>
      	  </tr>
        	<tr>
        	  <td align=\"right\"><span class=\"pointer\" title=\"The total number of points credited to member per ptc click.\">امتیاز برای هر تبلیغ کلیکی</span></td>
              <td><input type=\"text\" name=\"point_ptc\" value=\"";
echo $data['point_ptc'];
echo "\" /></td>
      	  </tr>
        	<tr>
        	  <td align=\"right\"><span class=\"pointer\" title=\"The total number of points credited to member per forum post.\">امتیاز برای هر پست انجمن</span></td>
              <td><input type=\"text\" name=\"point_post\" value=\"";
echo $data['point_post'];
echo "\" /></td>
      	  </tr>
        	<tr>
        	  <td align=\"right\"><span class=\"pointer\" title=\"The total number of points credited to member per completed offer.\">امتیاز برای هر پیشنهاد کامل : پرداخت به ازای ثبت نام</span></td>
              <td><input type=\"text\" name=\"point_ptsu\" value=\"";
echo $data['point_ptsu'];
echo "\" /></td>
      	  </tr>
        	<tr>
        	  <td align=\"right\"><span class=\"pointer\" title=\"The total number of points credited to member per dollar deposited.\">امتیاز برای هر 1 تومان شارژ</span></td>
              <td><input type=\"text\" name=\"point_deposit\" value=\"";
echo $data['point_deposit'];
echo "\" /></td>
      	  </tr>
        	<tr>
        	  <td align=\"right\">ارتقا حساب به وسیله امتیاز ؟</td>
              <td><select name=\"point_upgrade\">
              <option value=\"1\" ";
echo $data['point_upgrade'] == 1 ? "selected" : "";
echo ">بله</option>
              <option value=\"0\" ";
echo $data['point_upgrade'] != 1 ? "selected" : "";
echo ">خیر</option>
              </select></td>
      	  </tr>
        	<tr>
        	  <td align=\"right\"><span class=\"pointer\" title=\"Example: if set to 5 and upgrade costs $10 you will need 50 points to upgrade.\">امتیاز هر تومان برای ارتقا : </span></td>
              <td><input type=\"text\" name=\"point_upgraderate\" value=\"";
echo $data['point_upgraderate'];
echo "\" /></td>
      	  </tr>
        	<tr>
        	  <td align=\"right\">تبدیل امتیاز به شارژ حساب؟</td>
              <td><select name=\"point_purchasebalance\">
              <option value=\"1\" ";
echo $data['point_purchasebalance'] == 1 ? "selected" : "";
echo ">بله</option>
              <option value=\"0\" ";
echo $data['point_purchasebalance'] != 1 ? "selected" : "";
echo ">خیر</option>
              </select></td>
      	  </tr>
        	<tr>
        	  <td align=\"right\"><span class=\"pointer\" title=\"Ex. 10, where 10 = $1\">امتیاز هر تومان برای تبدیل به موجودی شارژ حساب</span></td>
              <td><input type=\"text\" name=\"point_cashrate\" value=\"";
echo $data['point_cashrate'];
echo "\" /></td>
      	  </tr>
        	<tr>
            	<td></td>
        	  <td><input type=\"submit\" name=\"btn\" value=\"بروزرسانی\" />
              <input type=\"button\" name=\"btn\" value=\"بازگشت\" onclick=\"location.href='./?view=membership';\" />
              </td>
      	  </tr>
        </table>
    </form>
<div class=\"info_box\">
<strong>Click:</strong> Percentage of click value for each member click.<br />
<strong>Maximum clicks per day:</strong> You can limit the amount of clicks per day per each membership. 0 = Unlimited.<br />
<strong>Referral Click:</strong> Percentage of click value for each direct referral click.<br />
<strong>Minimum Payout:</strong> Amount required to make a cashout. Ex: 2.00,5.00 (first cashout is $2.00, second cashout $5.00)<br />
<strong>Purchases Commission:</strong> Percentage of revenues generated from direct referrals' ad purchases.<br />
<strong>Upgrade Commission:</strong> Amount of comission per direct referral upgrade.<br />
<strong>Rent Referrals Pack:</strong> Rented referral pack available to member. Enter with commas and without space, ex: 25,75,100<br />
<strong>Rent Referrals Time:</strong> Days between referral rentals<br />
<strong>Cashout Time:</strong> Days between cashouts<br />
</div>


</div>
";
include SOURCES . "footer.php";
?>