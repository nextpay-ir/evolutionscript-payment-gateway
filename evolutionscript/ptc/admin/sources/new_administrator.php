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


if ($input->p['do'] == "create_admin") {
	verifyajax();
	verifydemo();

	if (!$input->p['username']) {
		serveranswer(0, "نام کاربری را وارد نمایید");
	}


	if (!$input->p['password']) {
		serveranswer(0, "کلمه عبور را وارد نمایید");
	}


	if (!$input->p['email']) {
		serveranswer(0, "ایمیل را وارد نمایید");
	}


	if (strlen($input->pc['password']) < 6) {
		serveranswer(0, "کلمه عبور باید حداقل 6 کاراکتری باشد");
	}


	if (!is_numeric($input->p['pin']) || strlen($input->p['pin']) != 6) {
		serveranswer(0, "پین کد باید فقط 6 رقم باشد");
	}


	if (validateEmail($input->p['email']) !== true) {
		serveranswer(0, "ایمیل نا معتبر است");
	}

	$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM admin WHERE username='" . $input->p['username'] . "'");

	if ($chk != 0) {
		serveranswer(0, "نام کاربری توسط فرد دیگری ثبت شده است");
	}

	$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM admin WHERE email='" . $input->p['email'] . "'");

	if ($chk != 0) {
		serveranswer(0, "این ایمیل قبلا توسط فرد دیگری ثبت شده است");
	}

  $perms = array();

	foreach ($admin_permissions as $k => $descr) {
		$perms[$k] = ($input->p['perm'][$k] ? $input->p['perm'][$k] : 0);
	}

	$perms = serialize($perms);
	$set = array("username" => $input->pc['username'], "password" => md5($input->pc['password']), "email" => $input->pc['email'], "pin" => md5($input->p['pin']), "permissions" => $perms);
	$db->insert("admin", $set);
	serveranswer(5, "مدیر جدید ایجاد شد, <a href='#' onclick='history.back();'>برای بازگشت کلیک کنید</a>");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">ایجاد مدیر جدید</div>
<div class=\"site_content\">
<form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"do\" value=\"create_admin\" />
<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tab-1\">کلی</a></li>
        <li><a href=\"#tab-2\">اجازه دسترسی</a></li>
    </ul>
    <div id=\"tab-1\">
    <table class=\"widget-tbl\" width=\"100%\">
      <tr>
        <td width=\"300\" align=\"right\">نام کاربری : </td>
        <td><input name=\"username\" type=\"text\" /></td>
        </tr>
      <tr>
        <td align=\"right\">کلمه عبور : </td>
        <td><input name=\"password\" type=\"password\" /> حداقل 6 کاراکتر</td>
      </tr>
      <tr>
        <td align=\"right\">پین کد : </td>
        <td><input name=\"pin\" type=\"password\" /> 6 رقم</td>
      </tr>
      <tr>
        <td align=\"right\">ایمیل</td>
        <td><input name=\"email\" type=\"text\" /></td>
      </tr>
      <tr>
      	        <td></td>
        <td>
        <input type=\"submit\" name=\"create\" value=\"ایجاد\" />
         <input type=\"button\" name=\"btn\" value=\"بازگشت\" onclick=\"history.back();\" />
        </td>
      </tr>
    </table>
	</div>
    <div id=\"tab-2\">
    <table class=\"widget-tbl\" width=\"100%\">
      ";
foreach ($admin_permissions as $k => $descr) {
	echo "      <tr>
        <td align=\"right\" width=\"300\">";
	echo $descr;
	echo "</td>
        <td><input type=\"checkbox\" name=\"perm[";
	echo $k;
	echo "]\" value=\"1\" checked=\"checked\" /></td>
      </tr>
      ";
}

echo "      <tr>
      	        <td></td>
        <td>
        <input type=\"submit\" name=\"create\" value=\"ایجاد\" />
         <input type=\"button\" name=\"btn\" value=\"بازگشت\" onclick=\"history.back();\" />
        </td>
      </tr>
    </table>
	</div>
</div>
    </form>

    </div>
        ";
include SOURCES . "footer.php";
echo " ";
?>