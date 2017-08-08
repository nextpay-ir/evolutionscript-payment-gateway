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


if (!$admin->permissions['site_content']) {
	header("location: ./");
	exit();
}


if ($input->p['do'] == "addnew") {
	if ($settings['demo'] == "yes") {
		$error_msg = "This is not possible in demo version";
	}
	else {
		if ($input->p['sesion_id'] != $_SESSION['sesion_id']) {
			$error_msg = "Invalid token try again please";
		}
		else {
			if (empty($input->p['url']) || empty($input->p['width']) || empty($input->p['height'])) {
				$error_msg = "پرکردن همه فیلدها ضروری است";
			}
			else {
				if (!is_numeric($input->p['width'])) {
					$error_msg = "طول را اشتباه وارد کردید . فقط عدد وارد کنید";
				}
				else {
					if (!is_numeric($input->p['height'])) {
						$error_msg = "ارتفاع را اشتباه وارد کردید . فقط عدد وارد کنید";
					}
					else {
						$data = array("url" => $input->p['url'], "width" => $input->p['width'], "height" => $input->p['height']);
						$db->insert("site_banners", $data);
						$success_msg = "بنر جدید افزوده شد";
					}
				}
			}
		}
	}
}
else {
	if ($input->p['do'] == "update") {
		verifyajax();
		verifydemo();

		if (empty($input->p['url']) || empty($input->p['width']) || empty($input->p['height']) || empty($input->p['bannerid'])) {
			serveranswer(0, "پرکردن همه فیلدها ضروری است");
		}


		if (!is_numeric($input->p['width'])) {
			serveranswer(0, "طول را اشتباه وارد کردید . فقط عدد وارد کنید");
		}


		if (!is_numeric($input->p['height'])) {
			serveranswer(0, "ارتفاع را اشتباه وارد کردید . فقط عدد وارد کنید");
		}

		$data = array("url" => $input->p['url'], "width" => $input->pc['width'], "height" => $input->pc['height']);
		$db->update("site_banners", $data, "id=" . $input->pc['bannerid']);
		serveranswer(6, "$(\"#bannerimg" . $input->pc['bannerid'] . "\").attr(\"src\", \"" . $input->p['url'] . "\");");
	}
	else {
		if ($input->p['do'] == "delete") {
			verifyajax();
			verifydemo();
			$db->delete("site_banners", "id=" . $input->pc['bannerid']);
			serveranswer(6, "$(\"#banner-" . $input->pc['bannerid'] . "\").remove();");
		}
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">مدیریت بنرها</div>
<div class=\"site_content\">

<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tabs-1\">افزودن بنر</a></li>
        <li><a href=\"#tabs-2\">مدیریت بنرها</a></li>
    </ul>
    <div id=\"tabs-1\">
         <form method=\"post\" action=\"./?view=sitebanners#tabs-1\">
         <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
        <input type=\"hidden\" name=\"do\" value=\"addnew\" />
        <div class=\"info_box\">
این بنرها در قسمت کاربران ( صفحه بنرها) برای ابزارهای زیرمجموعه ها نمایش داده خواهد شد 
</div>
	";

if ($error_msg) {
	echo "    <div class=\"error_box\">";
	echo $error_msg;
	echo "</div>
    ";
}

echo "	";

if ($success_msg) {
	echo "    <div class=\"success_box\">";
	echo $success_msg;
	echo "</div>
    ";
}

echo "        <table width=\"100%\" class=\"widget-tbl\">
          <tr>
            <td width=\"100\" align=\"right\">آدرس بنر</td>
            <td><input name=\"url\" style=\"direction:ltr !important;\"  type=\"text\" value=\"";
echo $input->p['url'] ? $input->p['url'] : $settings['site_url'];
echo "\" style=\"width:80%\" /></td>
            </tr>
          <tr>
            <td align=\"right\">طول</td>
            <td><input name=\"width\" type=\"text\" value=\"";
echo $input->p['width'];
echo "\" />
              px</td>
          </tr>
          <tr>
            <td align=\"right\">ارتفاع</td>
            <td><input name=\"height\" type=\"text\" value=\"";
echo $input->p['height'];
echo "\" />
              px</td>
          </tr>

          <tr>
          	<td></td>
            <td>
            <input type=\"submit\" name=\"create\" value=\"ایجاد\" />
             </td>
          </tr>
        </table>
        </form>
    </div>

    <div id=\"tabs-2\">
		";
$q = $db->query("SELECT * FROM site_banners ORDER BY id ASC");
$n = 0;

while ($r = $db->fetch_array($q)) {
	$n = $n + 1;
	echo "        		<div id=\"banner-";
	echo $r['id'];
	echo "\" style=\"margin-bottom:5px\">
        		<div class=\"widget-title\">Banner #";
	echo $n;
	echo "</div>
				<form id=\"frm";
	echo $r['id'];
	echo "\" method=\"post\" onsubmit=\"return submitform(this.id);\">
					<table width=\"100%\" class=\"widget-tbl\">
					<tr>
				        <td colspan=\"2\">
					        <div align=\"center\">
				    	    <img src=\"";
	echo $r['url'];
	echo "\" width=\"";
	echo $r['width'];
	echo "\" height=\"";
	echo $r['height'];
	echo "\" id=\"bannerimg";
	echo $r['id'];
	echo "\">
				        	</div>
				        </td>
				    </tr>
				    <tr>
				    	<td width=\"100\" align=\"right\">آدرس بنر</td>
				        <td><input style=\"direction:ltr !important;\"  name=\"url\" type=\"text\" class=\"input_text\" value=\"";
	echo $r['url'];
	echo "\" /></td>
					</tr>
				    <tr>
				        <td align=\"right\">طول</td>
				        <td><input name=\"width\" type=\"text\" style=\"width:75px\" value=\"";
	echo $r['width'];
	echo "\" />px</td>
					</tr>
				    <tr>
				        <td align=\"right\">ارتفاع</td>
				        <td><input name=\"height\" type=\"text\" style=\"width:75px\" value=\"";
	echo $r['height'];
	echo "\" />px</td>
					</tr>
				    <tr>
				    	<td colspan=\"2\" align=\"center\">
                        	<input type=\"hidden\" name=\"do\" value=\"\" id=\"action";
	echo $r['id'];
	echo "\" />
                            <input type=\"hidden\" name=\"bannerid\" value=\"";
	echo $r['id'];
	echo "\" />
                        	<input type=\"submit\" name=\"btn\" value=\"بروز رسانی\" onclick=\"updfrmvars({'action";
	echo $r['id'];
	echo "': 'update'});\" />
                        	<input type=\"submit\" name=\"btn\" value=\"حذف\" onclick=\"updfrmvars({'action";
	echo $r['id'];
	echo "': 'delete'});\" />
				        </td>
				    </tr>
				</table>
			</form>
            </div>
          ";
}

echo "    </div>
</div>

</div>

        ";
include SOURCES . "footer.php";
echo " ";
?>