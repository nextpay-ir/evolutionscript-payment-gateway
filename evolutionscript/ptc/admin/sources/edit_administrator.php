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

$data = $db->fetchRow("SELECT * FROM admin WHERE id=" . $input->gc['edit']);
$data['permissions'] = unserialize($data['permissions']);

if (!is_array($data['permissions'])) {
	$data['permissions'] = array();
}


if ($input->p['a'] == "update_permissions") {
	verifyajax();
	verifydemo();
	$perms = array();
	foreach ($admin_permissions as $k => $descr) {
		$perms[$k] = ($input->p['perm'][$k] ? $input->p['perm'][$k] : 0);
	}

	$perms = serialize($perms);
	$set = array("permissions" => $perms);
	$db->update("admin", $set, "id=" . $data['id']);
	serveranswer(1, "تغییرات اعمال شد");
}


if ($input->p['a'] == "update_general") {
	verifyajax();
	verifydemo();

	if ($input->p['username'] != $data['username']) {
		$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM admin WHERE username='" . $input->p['username'] . "'");

		if ($chk != 0) {
			serveranswer(0, "نام کابری قبلا توسط فرد دیگری ثبت شده است");
		}
	}


	if ($input->p['email'] != $data['email']) {
		if (validateEmail($input->p['email']) !== true) {
			serveranswer(0, "ایمیل نامعتبر است");
		}

		$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM admin WHERE email='" . $input->p['email'] . "'");

		if ($chk != 0) {
			serveranswer(0, "ایمیل توسط فرد دیگری ثبت شده است");
		}
	}


	if ($input->p['password']) {
		if (strlen($input->pc['password']) < 6) {
			serveranswer(0, "کلمه عبور باید حداقل 6 کاراکتر باشد");
		}

		$newpassword = md5($input->pc['password']);
	}
	else {
		$newpassword = $data['password'];
	}


	if ($input->p['pin']) {
		if (strlen($input->p['pin']) != 6 || !is_numeric($input->p['pin'])) {
			serveranswer(0, "پین کد باید 6 رقمی باشد");
		}

		$newpin = md5($input->p['pin']);
	}
	else {
		$newpin = $data['pin'];
	}

	$set = array("username" => $input->pc['username'], "password" => $newpassword, "pin" => $newpin, "notes" => $input->pc['notes'], "email" => $input->pc['email']);
	$db->update("admin", $set, "id=" . $data['id']);

	if ($data['id'] == $admin->getId()) {
		setcookie("c_uid", $input->pc['username']);

		if ($input->p['password']) {
			setcookie("c_pwd", md5($input->pc['password']));
		}
	}

	serveranswer(1, "حساب بروز رسانی شد");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">ویرایش مدیر</div>
<div class=\"site_content\">

<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tab-1\">کلی</a></li>
        <li><a href=\"#tab-2\">اجازه</a></li>
    </ul>
    <div id=\"tab-1\">
	<form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"a\" value=\"update_general\" />
    <table class=\"widget-tbl\" width=\"100%\">
      <tr>
        <td width=\"300\" align=\"right\">نام کاربری</td>
        <td><input name=\"username\" type=\"text\" value=\"";
echo $data['username'];
echo "\" /></td>
        </tr>
      <tr>
        <td align=\"right\">پسورد جدید</td>
        <td><input name=\"password\" type=\"password\" /> در صورتی که نمیخواهید کلمه عبور را تغییر دهید خالی بگذارید</td>
      </tr>
      <tr>
        <td align=\"right\">پین کد (6 رقم)</td>
        <td><input name=\"pin\" type=\"password\" /> در صورتی که نمیخواهید کلمه عبور را تغییر دهید خالی بگذارید</td>
      </tr>
      <tr>
        <td align=\"right\">ایمیل</td>
        <td><input name=\"email\" type=\"text\" value=\"";
echo $data['email'];
echo "\" /></td>
      </tr>
      <tr>
        <td align=\"right\">یادداشت</td>
        <td><textarea name=\"notes\" rows=\"5\" cols=\"45\">";
echo $data['notes'];
echo "</textarea></td>
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
    <div id=\"tab-2\">
	<form method=\"post\" id=\"frm2\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"a\" value=\"update_permissions\" />
    <table class=\"widget-tbl\" width=\"100%\">
      ";
foreach ($admin_permissions as $k => $descr) {
	echo "      <tr>
        <td align=\"right\" width=\"300\">";
	echo $descr;
	echo "</td>
        <td><input type=\"checkbox\" name=\"perm[";
	echo $k;
	echo "]\" value=\"1\" ";
	echo $data['permissions'][$k] == 1 ? "checked" : "";
	echo " /></td>
      </tr>
      ";
}

echo "      <tr>
      	        <td></td>
        <td>
        <input type=\"submit\" name=\"create\" value=\"ثبت\" />
         <input type=\"button\" name=\"btn\" value=\"بازگشت\" onclick=\"history.back();\" />
        </td>
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