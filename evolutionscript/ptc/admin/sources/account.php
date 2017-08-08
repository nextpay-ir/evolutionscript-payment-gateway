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


if ($input->p['a'] == "profile") {
	verifyajax();
	verifydemo();
	$notes = $input->pc['notes'];
	$email = $input->pc['email'];
	$protection = ($input->p['protection'] == "yes" ? "1" : "0");
	$signature = $input->pc['signature'];

	if (validateEmail($email) !== true) {
		serveranswer(0, "آدرس ایمیل صحیح نیست");
	}

	$data = array("email" => $email, "notes" => $notes, "signature" => $signature, "protection" => $protection);
	$db->update("admin", $data, "id=" . $admin->getId());
	serveranswer(1, "پروفایل ویرایش شد" . $restrict);
}


if ($input->p['a'] == "password") {
	verifyajax();
	verifydemo();

	if (md5($input->pc['password']) != $admin->getPassword()) {
		serveranswer(0, "پسورد قبلی صحیح نیست");
	}


	if (strlen($input->pc['new_password']) < 6) {
		serveranswer(0, "حداقل میبایست 6 کاراکتر باشد");
	}


	if ($input->p['new_password'] != $input->p['new_password2']) {
		serveranswer(0, "کلمه های عبور جدید یکسان نیستند");
	}

	$data = array("password" => md5($input->pc['new_password']));
	$db->update("admin", $data, "id=" . $admin->getId());
	setcookie("c_pwd", md5($input->pc['new_password']));
	serveranswer(2, "کلمه عبور تغییر یافت");
}


if ($input->p['a'] == "pincode") {
	verifyajax();
	verifydemo();

	if (md5($input->p['pin_code']) != $admin->getPin()) {
		serveranswer(0, "پین قبلی صحیح نیست");
	}


	if (strlen($input->p['newpin_code']) != 6 || !is_numeric($input->p['newpin_code'])) {
		serveranswer(0, "پین باید حداقل 6 رقم باشد");
	}


	if ($input->p['newpin_code'] != $input->p['newpin_code2']) {
		serveranswer(0, "پین های جدید یکسان نیستند");
	}

	$data = array("pin" => md5($input->p['newpin_code']));
	$db->update("admin", $data, "id=" . $admin->getId());
	serveranswer(4, "$(\"#\"+id).l2success(\"پین با موفقیت تغییر یافت و به زودی شما از سایت بیرون خواهید افتاد\"); setTimeout(\"location.href=location.href;\", 1000);");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">ویرایش اکانت</div>
<div class=\"site_content\">
  <div id=\"tabs\">
        <ul>
            <li><a href=\"#tabs-1\">پروفایل</a></li>
            <li><a href=\"#tabs-2\">تغییر رمز</a></li>
            <li><a href=\"#tabs-3\">تغییر PIN Code</a></li>
        </ul>
        <div id=\"tabs-1\">
        <form method=\"post\" id=\"frm\" onsubmit=\"return submitform(this.id);\">
            <input type=\"hidden\" name=\"a\" value=\"profile\" />
            <table width=\"100%\" class=\"widget-tbl\">
              <tr>
                <td align=\"right\">ایمیل</td>
                <td><input name=\"email\" type=\"text\" size=\"30\" value=\"";
echo $admin->getEmail();
echo "\" /></td>
              </tr>
              <tr>
                <td align=\"right\">امنیت اکانتی</td>
                <td><input type=\"checkbox\" name=\"protection\" value=\"yes\" ";
echo $admin->getProtection() == 1 ? "checked" : "";
echo " />  با فعال کردن آن در صورت تغییر آی پی وارد شونده به اکانت شما برای شما ایمیل ارسال خواهد شد</td>
              </tr>
              <tr>
                <td width=\"200\" align=\"right\">یادداشت شخصی</td>
                <td><textarea name=\"notes\" id=\"mynotes2\" cols=\"45\" rows=\"5\">";
echo $admin->getNotes();
echo "</textarea></td>
              </tr>
              <tr>
                <td width=\"200\" align=\"right\">امضای نشان داده شده در تیکت ها</td>
                <td><textarea name=\"signature\"  cols=\"45\" rows=\"5\">";
echo $admin->getSignature();
echo "</textarea></td>
              </tr>
              <tr>
                <td></td>
                <td>
                <input type=\"submit\" name=\"send\" value=\"ثبت\" />
                </td>
              </tr>
            </table>
        </form>
        </div>
        <div id=\"tabs-2\">
        <form method=\"post\" id=\"frm2\" onsubmit=\"return submitform(this.id);\">
            <input type=\"hidden\" name=\"a\" value=\"password\" />
            <table width=\"100%\" class=\"widget-tbl\">
              <tr>
                <td width=\"200\" align=\"right\">پسورد قبلی</td>
                <td><input name=\"password\" type=\"password\" size=\"30\" /></td>
              </tr>
              <tr>
                <td align=\"right\">پسورد جدید</td>
                <td><input name=\"new_password\" type=\"password\" size=\"30\" /></td>
              </tr>
              <tr>
                <td align=\"right\">تکرار پسورد جدید</td>
                <td><input name=\"new_password2\" type=\"password\" size=\"30\" /></td>
              </tr>
              <tr>
                <td></td>
                <td>
                <input type=\"submit\" name=\"send\" value=\"ثبت\" />
                </td>
              </tr>
            </table>
        </form>


        </div>
		<div id=\"tabs-3\">
        <div class=\"info_box\">پین باید حداقل 6 رقم باشد</div>
        <form method=\"post\" id=\"frm3\" onsubmit=\"return submitform(this.id);\">
            <input type=\"hidden\" name=\"a\" value=\"pincode\" />
            <table width=\"100%\" class=\"widget-tbl\">
              <tr>
                <td width=\"200\" align=\"right\">پین قبلی</td>
                <td><input name=\"pin_code\" type=\"password\" size=\"30\" /></td>
              </tr>
              <tr>
                <td align=\"right\">پین جدید</td>
                <td><input name=\"newpin_code\" type=\"password\" size=\"30\" /></td>
              </tr>
              <tr>
                <td align=\"right\">پین جدید</td>
                <td><input name=\"newpin_code2\" type=\"password\" size=\"30\" /></td>
              </tr>
              <tr>
                <td></td>
                <td>
                <input type=\"submit\" name=\"send\" value=\"ثبت\" />
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