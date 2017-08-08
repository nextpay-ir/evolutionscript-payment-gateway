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


if ($input->p['do'] == "verify" && !$input->p['resend_code']) {
	if ($input->p['verification'] == "") {
		$error_msg = "کد فعالسازی صحیح نیست";
	}
	else {
		if ($input->p['sesion_id'] != $_SESSION['sesion_id']) {
			$error_msg = "Invalid token try again please";
		}
		else {
			if (md5($input->p['verification']) != $admin->getCheckcode()) {
				$error_msg = "کد فعالسازی صحیح نیست";
			}
			else {
				$data = array("last_ip" => $_SERVER['REMOTE_ADDR'], "check_code " => "");
				$db->update("admin", $data, "id=" . $admin->getId());

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

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>EvolutionScript - Login</title>
<link href=\"/admin/css/login.css\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"/admin/css/global.css\" rel=\"stylesheet\" type=\"text/css\" />
</head>
<body>
	<div id=\"wrapper\">
    	<div class=\"box corner-all\">
	    	<div id=\"logo\"></div>
    	    <div class=\"clear\"></div>
            <div class=\"title corner-all\">EvolutionScript ";
echo $software['version'];
echo " Admin Control Panel</div>
            <div>
            ";
echo $error_msg ? "<div class=\"error_box\">" . $error_msg . "</div>" : "";
echo "            <form method=\"post\">
            	<input type=\"hidden\" name=\"do\" value=\"verify\" />
                <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
            	<table cellpadding=\"4\" cellspacing=\"4\" align=\"center\">
                	<tr>
                    	<td align=\"right\">کد فعالسازی : </td>
                        <td><input type=\"text\" name=\"verification\" size=\"40\" /></td>
                    </tr>
                	<tr>
                    	<td align=\"right\" valign=\"top\"></td>
                        <td><input type=\"submit\" name=\"btn\" value=\"تایید\" />
                        ";

if (!$_SESSION['resend_code']) {
	echo "                        <input type=\"submit\" name=\"resend_code\" value=\"درخواست کد فعالسازی جدید\" />
                        ";
}

echo "                        </td>
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