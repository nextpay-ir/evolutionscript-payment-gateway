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

$query = $db->query("SELECT * FROM mail_settings");

while ($result = $db->fetch_array($query)) {
	$mail_settings[$result['field']] = $result['value'];
}


if (!$admin->permissions['setup']) {
	header("location: ./");
	exit();
}


if ($input->p['do'] == "update1") {
	verifyajax();
	verifydemo();
	$vars = array("site_name", "site_title", "site_url", "email_support", "ssl_host", "site_stats", "payment_proof", "max_result_page", "usersonline", "timezone");

	if (substr($input->p['site_url'], -1) != "/") {
		serveranswer(0, "آدرس سایت را اشتباه وارد کرده اید . در آخر آدرس باید یک اسلش \"/\"  وجود داشته باشد ");
	}

	foreach ($vars as $k) {
		$db->query("UPDATE settings SET value='" . $db->real_escape_string($input->pc[$k]) . ("' WHERE field='" . $k . "'"));
	}

	$cache->delete("settings");
	serveranswer(1, "تنظیمات بروز شد");
}


if ($input->p['do'] == "update2") {
	verifyajax();
	verifydemo();
	$vars = array("register_activation", "emailchange_activation", "withdraw_sameprocessor", "inactive_days", "money_transfer", "amount_transfer", "message_system", "message_per_page", "cancel_pendingwithdraw");
	foreach ($vars as $k) {
		$db->query("UPDATE settings SET value='" . $db->real_escape_string($input->pc[$k]) . ("' WHERE field='" . $k . "'"));
	}

	$cache->delete("settings");
	serveranswer(1, "تنظیمات بروز شد");
}


if ($input->p['do'] == "update3") {
	verifyajax();
	verifydemo();
	$vars = array("maintenance", "maintenance_msg");
	foreach ($vars as $k) {
		$db->query("UPDATE settings SET value='" . $db->real_escape_string($input->pc[$k]) . ("' WHERE field='" . $k . "'"));
	}

	$cache->delete("settings");
	serveranswer(1, "تنظیمات بروز شد");
}


if ($input->p['do'] == "update4") {
	verifyajax();
	verifydemo();
	$vars = array("email_from_address", "email_from_name", "email_type", "smtp_host", "smtp_username", "smtp_password", "smtp_port", "smtp_ssl");
	foreach ($vars as $k) {
		$db->query("UPDATE mail_settings SET value='" . $db->real_escape_string($input->pc[$k]) . ("' WHERE field='" . $k . "'"));
	}

	$cache->delete("mail_settings");
	serveranswer(1, "تنظیمات ایمیل بروز شد");
}


if ($input->p['do'] == "update5") {
	verifyajax();
	verifydemo();
	$vars = array("click_yesterday", "clicks_necessary", "withdraw_clicks", "force_viewads", "autoloadad_secs");
	foreach ($vars as $k) {
		$db->query("UPDATE settings SET value='" . $db->real_escape_string($input->pc[$k]) . ("' WHERE field='" . $k . "'"));
	}

	$cache->delete("settings");
	serveranswer(1, "تنظیمات بروز شد");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">تنظیمات کلی</div>
<div class=\"site_content\">
<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tabs-1\">اطلاعات سایت</a></li>
        <li><a href=\"#tabs-2\">مرتبط اعضا</a></li>
        <li><a href=\"#tabs-5\">تنظیمات کلیکی تبلیغ</a>
        <li><a href=\"#tabs-3\">محافظت</a></li>
        <li><a href=\"#tabs-4\">ایمیل</a></li>
    </ul>
    <div id=\"tabs-1\">
    <form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"do\" value=\"update1\" />
    <table width=\"100%\" class=\"widget-tbl\">
    	<tr>
        	<td align=\"right\" width=\"200\">نام سایت :</td>
            <td><input type=\"text\" name=\"site_name\" value=\"";
echo $settings['site_name'];
echo "\" /></td>
        </tr>
        <tr>
            <td align=\"right\">عنوان سایت :</td>
            <td><input name=\"site_title\" type=\"text\" value=\"";
echo $settings['site_title'];
echo "\" /> عنوان مرورگر</td>
        </tr>
        <tr>
            <td align=\"right\">آدرس سایت : </td>
            <td><input style=\"direction:ltr !important;\" name=\"site_url\" type=\"text\" value=\"";
echo $settings['site_url'];
echo "\" />  <p style=\"direction:ltr;\"> with the slash '/' in the end. Ex: http://www.ptcevolution.com/ </p> </td>
        </tr>
        <tr>
            <td align=\"right\">ایمیل پشتیبانی</td>
            <td><input name=\"email_support\" type=\"text\" value=\"";
echo $settings['email_support'];
echo "\" /> این ایمیل برای اطلاع رسانی کاربران استفاده میشود</td>
        </tr>
        <tr>
            <td align=\"right\">استفاده از ssl?</td>
            <td><input type=\"checkbox\" name=\"ssl_host\" value=\"yes\"  ";
echo $settings['ssl_host'] == "yes" ? "checked" : "";
echo " />
               برای فعال سازی تیک بزنید - آدرس به https منتقل میشود</td>
        </tr>
        <tr>
            <td align=\"right\">نمایش آمار سایت؟</td>
            <td><input type=\"checkbox\" name=\"site_stats\" value=\"yes\"  ";
echo $settings['site_stats'] == "yes" ? "checked" : "";
echo " />
            برای فعالسازی تیک بزنید</td>
        </tr>
        <tr>
            <td align=\"right\">نمایش کاربران آنلاین در آمار</td>
            <td><input type=\"checkbox\" name=\"usersonline\" value=\"1\"  ";
echo $settings['usersonline'] == "1" ? "checked" : "";
echo " />
           برای فعالسازی تیک بزنید <strong>Disable this option to save MySQL resources.</strong></td>
        </tr>
        <tr>
            <td align=\"right\">نمایش صفحه مدارک پرداخت</td>
            <td><input type=\"checkbox\" name=\"payment_proof\" value=\"yes\" ";

if ($settings['payment_proof'] == "yes") {
	echo "checked";
}

echo " />
          برای فعالسازی تیک بزنید - کاربران و مهمانان میتوان آخرین پرداخت ها را ببینند</td>
        </tr>
          <tr>
            <td align=\"right\">ماکزیمم نتایج در هر صفحه</td>
            <td><input name=\"max_result_page\" type=\"text\" id=\"max_result_page\" value=\"";
echo $settings['max_result_page'];
echo "\" /> (Referrals, ads, etc)</td>
          </tr>
          <tr>
            <td align=\"right\">منطقه زمانی</td>
            <td>                            <select name=\"timezone\">
                            	<option value=\"\">-- استفاده از پیشفرض سیستم --</option>
                                ";
foreach ($timezone as $v) {

	if ($v == $settings['timezone']) {
		echo "<option selected>" . $v . "</option>
";
		continue;
	}

	echo "<option>" . $v . "</option>
";
}

echo "                            </select></td>
          </tr>
          <tr>
          	<td></td>
            <td><input type=\"submit\" name=\"btn\" value=\"ذخیره\" /></td>
          </tr>
    </table>
    </form>
    </div>
    <div id=\"tabs-2\">
    <form method=\"post\" id=\"frm2\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"do\" value=\"update2\" />
    <table width=\"100%\" class=\"widget-tbl\">
        <tr>
            <td align=\"right\">؟نیاز به ایمیل تاییدیه</td>
            <td><input type=\"checkbox\" name=\"register_activation\" value=\"yes\" ";
echo $settings['register_activation'] == "yes" ? "checked" : "";
echo " />
            برای فعالسازی تیک بزنید - کاربران برای فعالسازی حساب ؛ نیاز به تایید از طریق ایمیل دارند</td>
        </tr>
        <tr>
            <td align=\"right\">نیاز به تاییدیه ایمیل جدید</td>
            <td><input type=\"checkbox\" name=\"emailchange_activation\" value=\"yes\" ";
echo $settings['emailchange_activation'] == "yes" ? "checked" : "";
echo " />
            برای فعالسازی تیک بزنید - مجبور کردن کاربران برای تایید ایمیل جدیدشان وقتی پروفایلشان آپدیت میشود</td>
        </tr>
        <tr>
        	<td align=\"right\">
اجازه به کاربران برای تسویه حساب از طریق همان پروسه ای که حساب خود را شارژ میکردند

</td>
            <td><input type=\"checkbox\" name=\"withdraw_sameprocessor\" value=\"yes\" ";
echo $settings['withdraw_sameprocessor'] == "yes" ? "checked" : "";
echo " />
این آپشن به صورت خودکار برای کاربرانی که سپرده حساب شارژ شده ای ندارند غیرفعال است
</td>
        </tr>
        <tr>
            <td align=\"right\">ماکزیمم تعداد روزهایی که یک حساب میتواند غیرفعال باشد</td>
            <td><input name=\"inactive_days\" type=\"text\" value=\"";
echo $settings['inactive_days'];
echo "\" /></td>
        </tr>
        <tr>
            <td align=\"right\">انتقال پول</td>
            <td><input type=\"checkbox\" name=\"money_transfer\" value=\"yes\" ";

if ($settings['money_transfer'] == "yes") {
	echo "checked";
}

echo " />
           برای فعال سازی تیک بزنید - کاربران میتوانند مبالغ را از درآمد خود به حساب شارژ شده شان منتقل کنند</td>
        </tr>
        <tr>
            <td align=\"right\">حداقل مبلغ برای انتقال</td>
            <td><input type=\"text\" name=\"amount_transfer\" value=\"";
echo $settings['amount_transfer'];
echo "\" /> تومان </td>
        </tr>
        <tr>
            <td align=\"right\">سیستم پیام</td>
            <td><input type=\"checkbox\" name=\"message_system\" value=\"yes\" ";

if ($settings['message_system'] == "yes") {
	echo "checked";
}

echo " />
            برای فعال سازی تیک بزنید - ارتباط پیامی بین کاربران</td>
        </tr>
        <tr>
            <td align=\"right\">پیام های هرصفحه:</td>
            <td><input type=\"text\" name=\"message_per_page\" value=\"";
echo $settings['message_per_page'];
echo "\" /></td>
        </tr>
        <tr>
            <td align=\"right\">اجازه دادن به کاربران برای کنسل کردن درخواست تسویه حساب</td>
            <td><input type=\"checkbox\" name=\"cancel_pendingwithdraw\" value=\"yes\" ";

if ($settings['cancel_pendingwithdraw'] == "yes") {
	echo "checked";
}

echo " />
           برای فعال سازی تیک بزنید</td>
        </tr>
          <tr>
          	<td></td>
            <td><input type=\"submit\" name=\"btn\" value=\"ذخیره\" /></td>
          </tr>
    </table>
    </form>
    </div>
    <div id=\"tabs-5\">
    <form method=\"post\" id=\"frm5\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"do\" value=\"update5\" />
    <table width=\"100%\" class=\"widget-tbl\">
        <tr>
            <td align=\"right\">حداقل کلیک هایی که برای تسویه حساب نیاز است؟</td>
            <td><input name=\"withdraw_clicks\" type=\"text\" class=\"input_text2\" value=\"";
echo $settings['withdraw_clicks'];
echo "\" />
              (0 = disabled)</td>
        </tr>
        <tr>
            <td align=\"right\">نیاز به داشتن کلیک برای قادر بودن به کسب در آمد در روز بعد</td>
            <td><input type=\"checkbox\" name=\"click_yesterday\" value=\"yes\" ";

if ($settings['click_yesterday'] == "yes") {
	echo "checked";
}

echo " />
            برای فعالسازی تیک بزنید - force users to make clicks today to earn from referrals tomorrow</td>
        </tr>
        <tr>
            <td align=\"right\">کلیک ها ضروری برای بدست آوردن در آمد از زیرمجموعه ها در روز بعدی</td>
            <td><input name=\"clicks_necessary\" type=\"text\" value=\"";
echo $settings['clicks_necessary'];
echo "\" /></td>
        </tr>
        <tr>
            <td align=\"right\">مجبور کردن اعضا برای مشاهده تبلیغ</td>
            <td><input type=\"checkbox\" name=\"force_viewads\" value=\"1\" ";
echo $settings['force_viewads'] == 1 ? "checked" : "";
echo " />درصورتی که کاربر پنجره مرورگر را عوض کند تایمر خواهد ایستاد</td>
        </tr>
        <tr>
            <td align=\"right\">Seconds to autorun surfbar if ad is slow:</td>
            <td><input type=\"text\" name=\"autoloadad_secs\" value=\"";
echo $settings['autoloadad_secs'];
echo "\" /> ثانیه (0 <= disabled)</td>
        </tr>
          <tr>
          	<td></td>
            <td><input type=\"submit\" name=\"btn\" value=\"ذخیره\" /></td>
          </tr>
    </table>
    </form>
    </div>

    <div id=\"tabs-3\">
    <form method=\"post\" id=\"frm3\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"do\" value=\"update3\" />
    <table width=\"100%\" class=\"widget-tbl\">
        <tr>
            <td align=\"right\">حالت تعمیر؟</td>
            <td><input type=\"checkbox\" name=\"maintenance\" value=\"yes\" ";

if ($settings['maintenance'] == "yes") {
	echo "checked";
}

echo " />
              در صوریتکه تیک بزنید سایت از دسترس خارج خواهد شد</td>
        </tr>
        <tr>
            <td align=\"right\">پیام حالت تعمیر : </td>
            <td><input name=\"maintenance_msg\" type=\"text\" value=\"";
echo $settings['maintenance_msg'];
echo "\" /></td>
        </tr>
          <tr>
          	<td></td>
            <td><input type=\"submit\" name=\"btn\" value=\"ذخیره\" /></td>
          </tr>
    </table>
    </form>
    </div>

    <div id=\"tabs-4\">
    <script type=\"text/javascript\">
	$(function(){
		mailtypeverification();
	});

	function mailtypeverification(){
		mailtype = $(\"#email_type\").val();
		if(mailtype == 'smtp'){
			$(\"#smtp_details\").show();
		}else{
			$(\"#smtp_details\").hide();
		}
	}
	</script>
    <form id=\"mailfrmsettings\" method=\"post\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"do\" value=\"update4\" />
    <table width=\"100%\" class=\"widget-tbl\">
    	<tr>
        	<td align=\"right\" width=\"200\">نام ارسال کننده ایمیل ها</td>
            <td><input type=\"text\" name=\"email_from_name\" value=\"";
echo $mail_settings['email_from_name'];
echo "\" /></td>
        </tr>
    	<tr>
        	<td align=\"right\" width=\"200\">ایمیل ارسال کننده ایمیل ها</td>
            <td><input type=\"text\" name=\"email_from_address\" value=\"";
echo $mail_settings['email_from_address'];
echo "\" /></td>
        </tr>
    	<tr>
        	<td align=\"right\">نوع ایمیل</td>
            <td><select name=\"email_type\" id=\"email_type\" onchange=\"mailtypeverification();\">
            		<option value=\"php\" ";
echo $mail_settings['email_type'] == "php" ? "selected" : "";
echo ">PHP Mail()</option>
                    <option value=\"smtp\" ";
echo $mail_settings['email_type'] == "smtp" ? "selected" : "";
echo ">SMTP</option>
            	</select>
            </td>
        </tr>
        <tbody id=\"smtp_details\" style=\"display:none\">
    	<tr>
        	<td align=\"right\">SMTP پورت</td>
            <td><input type=\"text\" name=\"smtp_port\" value=\"";
echo $mail_settings['smtp_port'];
echo "\" /> پورتی که سرور ایمیل شما استفاده میکند</td>
        </tr>
    	<tr>
        	<td align=\"right\">SMTP هاست</td>
            <td><input type=\"text\" name=\"smtp_host\" value=\"";
echo $mail_settings['smtp_host'];
echo "\" /></td>
        </tr>
    	<tr>
        	<td align=\"right\">SMTP نام کاربری</td>
            <td><input type=\"text\" name=\"smtp_username\" value=\"";
echo $mail_settings['smtp_username'];
echo "\" /></td>
        </tr>
    	<tr>
        	<td align=\"right\">SMTP پسورد</td>
            <td><input type=\"password\" name=\"smtp_password\" value=\"";
echo $mail_settings['smtp_password'];
echo "\" /></td>
        </tr>
    	<tr>
        	<td align=\"right\">SMTP SSL Type</td>
            <td>
            	<label><input type=\"radio\" name=\"smtp_ssl\" value=\"\" ";
echo $mail_settings['smtp_ssl'] == "" ? "checked" : "";
echo "  /> None</label>
                <label><input type=\"radio\" name=\"smtp_ssl\" value=\"ssl\" ";
echo $mail_settings['smtp_ssl'] == "ssl" ? "checked" : "";
echo " /> SSL</label>
                <label><input type=\"radio\" name=\"smtp_ssl\" value=\"tls\" ";
echo $mail_settings['smtp_ssl'] == "tls" ? "checked" : "";
echo " /> TLS</label>
             </td>
        </tr>
        </tbody>
        <tr>
        	<td></td>
            <td><input type=\"submit\" name=\"btn\" value=\"ذخیره\" /></td>
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