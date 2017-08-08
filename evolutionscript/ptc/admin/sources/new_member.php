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


if (!$admin->permissions['add_new_member']) {
	header("location: ./");
	exit();
}


if ($input->p['a'] == "create") {
	verifyajax();
	verifydemo();
	$required_fields = array("fullname", "username", "password", "email", "country");
	foreach ($required_fields as $v) {

		if (!$input->p[$v]) {
			serveranswer(0, "پر کردن همه فیلدها ضروری است");
		}


		if ($v == "email" && validateEmail($input->pc[$v]) !== true) {
			serveranswer(0, "ایمیل نامعتبر است" . $v);
			continue;
		}
	}


	if (strlen($input->pc['password']) < 6) {
		serveranswer(0, "کلمه عبور باید حداقل 6 کاراکتر باشد");
	}

	$verifyusername = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE username='" . $input->pc['username'] . "'");

	if ($verifyusername != 0) {
		serveranswer(0, "این نام کاربری توسط فرد دیگری گرفته شده است");
	}

	$verifyemail = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE email='" . $input->pc['email'] . "'");

	if ($verifyemail != 0) {
		serveranswer(0, "این ایمیل  توسط فرد دیگری گرفته شده است");
	}

	$computerid = ascii2hex(substr(md5($input->pc['username'] . TIMENOW), 0, 10));
	$data = array("fullname" => $input->pc['fullname'], "username" => $input->pc['username'], "password" => md5($input->pc['password']), "email" => $input->pc['email'], "signup" => TIMENOW, "country" => $input->pc['country'], "signup_ip" => $_SERVER['REMOTE_ADDR'], "status" => "Active");
	$insert = $db->insert("members", $data);
	$usrid = $db->lastInsertId();
	$countcountry = $db->fetchOne("SELECT COUNT(*) AS NUM FROM country WHERE name='" . $input->pc['country'] . "'");

	if ($countcountry == 0) {
		$ipset = array("name" => $input->pc['country'], "users" => "1");
		$upd = $db->insert("country", $ipset);
	}
	else {
		$db->query("UPDATE country SET users=users+1 WHERE name='" . $input->pc['country'] . "'");
	}

	$db->query("UPDATE statistics SET value=value+1 WHERE field='members'");
	$db->query("UPDATE statistics SET value=value+1 WHERE field='members_today'");
	serveranswer(2, "New account was successfully created. <a href='./?view=members&edit=" . $usrid . "'>Click here to manage this member</a>");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">افزودن کاربر جدید</div>
<div class=\"site_content\">
 <form method=\"post\" id=\"createuser\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"a\" value=\"create\" />
<table cellpadding=\"5\" width=\"100%\" class=\"widget-tbl\">
  <tr>
    <td width=\"100\">نام کامل</td>
    <td><input name=\"fullname\" type=\"text\" /></td>
    </tr>
  <tr>
    <td>نام کاربری</td>
    <td><input name=\"username\" type=\"text\" /></td>
  </tr>
  <tr>
    <td>پسورد</td>
    <td><input name=\"password\" type=\"text\" /></td>
  </tr>
  <tr>
    <td>ایمیل</td>
    <td><input name=\"email\" type=\"text\" /></td>
  </tr>
  <tr>
    <td>کشور</td>
    <td>
    	<select name=\"country\" id=\"country\">
        	";
$countrylist = $db->query("SELECT country FROM ip2nationCountries ORDER BY country ASC");

while ($list = $db->fetch_array($countrylist)) {
	echo "<option value=\"" . $list['country'] . "\">" . $list['country'] . "</option>";
}

echo "        </select>    </td>
  </tr>
  <tr>
  	<td>
    </td>
    <td>
	<input type=\"submit\" name=\"create\" value=\"ایجاد\" />
    </td>
  </tr>
</table>
</form>

</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>