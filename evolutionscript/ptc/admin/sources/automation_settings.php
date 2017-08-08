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


if (!$admin->permissions['setup']) {
	header("location: ./");
	exit();
}

$query = $db->query("SELECT * FROM cron_settings");

while ($result = $db->fetch_array($query)) {
	$cron[$result['field']] = $result['value'];
}


if ($input->p['do'] == "update") {
	verifyajax();
	verifydemo();
	$set = array("reset_ptc", "delete_inactive", "suspend_inactive", "delete_ptc", "delete_fads", "delete_flinks", "delete_bannerads");
	foreach ($set as $k) {
		$updarray = array("value" => $input->pc[$k]);
		$db->update("cron_settings", $updarray, ("field='" . $k . "'"));
	}

	serveranswer(1, "تنظیمات بروز شد");
}
else {
	if ($input->p['do'] == "execute") {
		verifyajax();
		verifydemo();
		$todaysdate = date("Y-m-d");
		$the_day = strftime("%Y-%m-%d", strtotime($todaysdate . " + 1 days ago"));
		$delete_logs = TIMENOW - 60 * 60 * 24 * 7;
		include ADMINPATH . "cronadmin.php";
		serveranswer(1, "کرون به خوبی اعمال شد");
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">تنظیمات اتوماسیون : در صورتی که دانش فنی ندارید از این قسمت خارج شوید</div>
<div class=\"site_content\">
	<div class=\"widget-title\">وضعیت</div>
    <div class=\"widget-content\">
        <table width=\"100%\" class=\"widget-tbl\">
          <tr>
            <td width=\"150\" align=\"right\">آخرین زمان ریست : </td>
            <td valign=\"top\"><strong>";
echo $cron['last_cron'];
echo "</strong></td>
          </tr>
          <tr>
            <td colspan=\"2\">    <div align=\"center\">ایجاد کرون جابز با استفاده از WGET :<br />
                <input type=\"text\" style=\"width:90%\" value=\"0 0 * * * wget -O - -q -t 1 ";
echo $settings['site_url'];
echo "cron.php\" /><br />
                <input type=\"text\" style=\"width:90%\" value=\"30 9 * * * wget -O - -q -t 1 ";
echo $settings['site_url'];
echo "cron_ads.php\" /><br />
                <input type=\"text\" style=\"width:90%\" value=\"30 15 * * * wget -O - -q -t 1 ";
echo $settings['site_url'];
echo "cron_clean.php\" /><br />
        <strong>OR</strong><br />
        ایجاد کرون جابز با استفاده از CURL : <br />
                <input type=\"text\" style=\"width:90%\" value=\"0 0 * * * curl  ";
echo $settings['site_url'];
echo "cron.php\" /><br />
                <input type=\"text\" style=\"width:90%\" value=\"30 9 * * * curl ";
echo $settings['site_url'];
echo "cron_ads.php\" /><br />
                <input type=\"text\" style=\"width:90%\" value=\"30 15 * * * curl ";
echo $settings['site_url'];
echo "cron_clean.php\" />
            </div></td>
            </tr>
        </table>
    </div>

    <div class=\"widget-title\">توابع ماژول های اتوماتیک</div>
    <div class=\"widget-content\">
        <form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
        <table class=\"widget-tbl\" width=\"100%\">
          <tr>
            <td align=\"right\">ریست تنظیمات کلیکی : </td>
            <td><input name=\"reset_ptc\" type=\"checkbox\" id=\"reset_ptc\" value=\"yes\" ";

if ($cron['reset_ptc'] == "yes") {
	echo "checked";
}

echo " />
        این باکس مربوط به ریست تنظیمات کلیکی است</td>
          </tr>

          <tr>
            <td align=\"right\">حذف کاربران غیرفعال</td>
            <td><input name=\"delete_inactive\" type=\"checkbox\" value=\"yes\" ";

if ($cron['delete_inactive'] == "yes") {
	echo "checked";
}

echo " />
             تیک زدن برای حذف کاربران غیر فعال است</td>
          </tr>
          <tr>
            <td align=\"right\">تعلیق کاربران غیر فعال : </td>
            <td><input name=\"suspend_inactive\" type=\"checkbox\" id=\"suspend_inactive\" value=\"yes\" ";

if ($cron['suspend_inactive'] == "yes") {
	echo "checked";
}

echo " />
        تیک زدن برای تعلیق کاربران غیر فعال است</td>
          </tr>

          <tr>
            <td align=\"right\">حذف تبلیغات کلیکی منقضی یا غیر فعال</td>
            <td><input name=\"delete_ptc\" type=\"checkbox\" id=\"delete_ptc\" value=\"yes\" ";

if ($cron['delete_ptc'] == "yes") {
	echo "checked";
}

echo " />
        تیک زدن برای حذف تبلیغات کلیکی منقضی یا غیر فعال است</td>
          </tr>
          <tr>
            <td align=\"right\">حذف تبلیغات ویژه منقضی یا غیر فعال</td>
            <td valign=\"top\"><input name=\"delete_fads\" type=\"checkbox\" id=\"delete_fads\" value=\"yes\" ";

if ($cron['delete_fads'] == "yes") {
	echo "checked";
}

echo " />
        تیک زدن برای حذف تبلیغات ویژه منقضی یا غیر فعال است</td>
          </tr>
          <tr>
            <td align=\"right\">حذف تبلیغات لینکی ویژه منقضی یا غیر فعال</td>
            <td valign=\"top\"><input name=\"delete_flinks\" type=\"checkbox\" id=\"delete_flinks\" value=\"yes\" ";

if ($cron['delete_flinks'] == "yes") {
	echo "checked";
}

echo " />
        تیک زدن برای حذف تبلیغات لینکی ویژه منقضی یا غیر فعال است</td>
          </tr>
          <tr>
            <td align=\"right\">حذف تبلیغات بنری منقضی یا غیر فعال</td>
            <td valign=\"top\"><input name=\"delete_bannerads\" type=\"checkbox\" id=\"delete_bannerads\" value=\"yes\" ";

if ($cron['delete_bannerads'] == "yes") {
	echo "checked";
}

echo " />
        تیک زدن برای حذف تبلیغات بنری منقضی یا غیر فعال است</td>
          </tr>
          <tr>
            <td align=\"right\">حذف تبلیغات ورود منقضی یا غیر فعال</td>
            <td><input name=\"delete_loginads\" type=\"checkbox\" id=\"delete_loginads\" value=\"yes\" ";

if ($cron['delete_loginads'] == "yes") {
	echo "checked";
}

echo " />
        تیک زدن برای حذف تبلیغات ورود منقضی یا غیر فعال است</td>
          </tr>
          <tr>
          	<td></td>
            <td>
            <input type=\"hidden\" name=\"do\" value=\"\" id=\"doaction\" />
        <input type=\"submit\" name=\"btn\" value=\"ذخیره\" onclick=\"updfrmvars({'doaction': 'update'});\" />

        <input type=\"submit\" name=\"btn\" value=\"اجرای کرون جابز\" onclick=\"updfrmvars({'doaction': 'execute'});\" />
            </td>
          </tr>
        </table>
        </form>
    </div>
</div>


";
include SOURCES . "footer.php";
echo " ";
?>