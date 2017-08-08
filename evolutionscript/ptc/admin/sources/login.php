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


if ($input->p['do'] == "login") {
	if ($input->p['username'] == "" || $input->p['password'] == "" || !is_numeric($input->p['pin_code'])) {
		$error_msg = "ورود ناموفق";
	}
	else {
		if ($input->p['sesion_id'] != $_SESSION['sesion_id']) {
			$error_msg = "Invalid token try again please";
		}
		else {
			$admin = new Admin($input->pc['username'], md5($input->pc['password']));

			if ($admin->verify() !== true || $admin->getPin() != md5($input->p['pin_code'])) {
				$username = $db->real_escape_string($input->pc['username']);
				$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM admin WHERE username='" . $username . "'");

				if ($chk != 0) {
					$data = array("date" => TIMENOW, "username" => $input->pc['username'], "ip" => (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : getenv("REMOTE_ADDR")), "agent" => (!empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $_ENV['HTTP_USER_AGENT']), "fail" => 1);
					$db->insert("admin_loginlog", $data);
				}

				$error_msg = "ورود ناموفق";
			}
			else {
				if ($admin->getStatus() != "enable") {
					$error_msg = $lang['account_is_disabled'];
				}
				else {
					$admin->startSession();
					$data = array("login" => TIMENOW, "last_login" => $admin->getLogin());
					$db->update("admin", $data, "id=" . $admin->getId());
					$data = array("date" => TIMENOW, "username" => $admin->getUsername(), "ip" => $admin->getIP(), "agent" => $admin->getBrowser());
					$db->insert("admin_loginlog", $data);

					if ($_SERVER['HTTP_REFERER']) {
						header("location: " . $_SERVER['HTTP_REFERER']);
					}
					else {
						header("location: ./");
					}

					exit();
				}
			}
		}
	}
}

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>EvolutionScript - Login</title>
<link href=\"./css/login.css\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"./css/global.css\" rel=\"stylesheet\" type=\"text/css\" />
<script type=\"text/javascript\">
function picknumber(n){
	var pin_code = document.getElementById(\"pin_code\");
	var pinc = document.getElementById(\"pinc\");
	pinc.value = pinc.value+\"*\";
	pin_code.value = pin_code.value+n;
}
function resetpicker(){
	var pinc = document.getElementById(\"pinc\");
	var pin_code = document.getElementById(\"pin_code\");
	pinc.value = '';
	pin_code.value = '';
}
</script>
</head>
<body>
	<div style=\"direction:rtl !important;\" id=\"wrapper\">
    	<div class=\"box corner-all\">
	    	<div id=\"logo\"></div>
    	    <div class=\"clear\"></div>
            <div style=\"direction:rtl !important;\" class=\"title corner-all\">ورود به سایت ";

echo "</div>
            <div>
            ";
echo $error_msg ? "<div class=\"error_box\">" . $error_msg . "</div>" : "";
echo "            <form method=\"post\">
            	<input type=\"hidden\" name=\"do\" value=\"login\" />
                <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
            	<table style=\"direction:rtl !important;\" cellpadding=\"4\" cellspacing=\"4\" align=\"center\">
                	<tr>
                    	<td align=\"right\">نام کاربری</td>
                        <td><input type=\"text\" name=\"username\" style=\"width:270px\" /></td>
                    </tr>
                	<tr>
                    	<td align=\"right\">کلمه عبور</td>
                        <td><input type=\"password\" name=\"password\" style=\"width:270px\" /></td>
                    </tr>
                	<tr>
                    	<td align=\"right\" valign=\"top\">پین کد</td>
                        <td>

                        <div class=\"pincode_box\">
                        	";
$numbers = range(0, 9);
shuffle($numbers);
foreach ($numbers as $number) {
	echo "<span class=\"pincode_button\" onclick=\"picknumber(" . $number . ");\">" . $number . "</span>";
}

echo "                            <span class=\"pincode_clear\" onclick=\"resetpicker();\">از نو</span>
                        </div>
                        <input type=\"password\" name=\"pinc\" id=\"pinc\" disabled=\"disabled\" style=\"width:160px\" />
                        <input type=\"hidden\" name=\"pin_code\" id=\"pin_code\" value=\"\" />
                        <div style=\"margin-top:10px\"><input type=\"submit\" name=\"btn\" value=\"ورود\" /></div></td>
                    </tr>
                	<tr>
                    	<td></td>
                        <td></td>
                    </tr>
                </table>
            </form>
            </div>
        </div>
    </div>

</body>
</html>
";
?>