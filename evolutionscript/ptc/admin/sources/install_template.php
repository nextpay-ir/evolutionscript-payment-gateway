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


if (!$admin->permissions['utilities']) {
	header("location: ./");
	exit();
}


if ($input->p['do'] == "install") {
	if ($settings['demo'] == "yes") {
		$error_msg = "This is not possible in demo version";
	}
	else {
		if ($input->p['sesion_id'] != $_SESSION['sesion_id']) {
			$error_msg = "Invalid token try again please";
		}
		else {
			if ($_FILES['file']['type'] != "text/xml") {
				$error_msg = "فایل غیرمجاز";
			}
			else {
				if (0 < $_FILES['file']['error']) {
					$error_msg = "فایل غیرمجاز";
				}
				else {
					move_uploaded_file($_FILES['file']['tmp_name'], "../upload/" . $_FILES['file']['name']);
					$doc = new DOMDocument();
					$doc->load("../upload/" . $_FILES['file']['name']);
					$templatenames = $doc->getElementsByTagName("name");
					$templatename = $templatenames->item(0)->nodeValue;
					$templateversions = $doc->getElementsByTagName("ptcevolution");
					$templateversion = $templateversions->item(0)->nodeValue;
					$templatedirectories = $doc->getElementsByTagName("directory");
					$templatedirectory = $templatedirectories->item(0)->nodeValue;

					if (empty($templatename) || empty($templateversion) || empty($templatedirectory)) {
						$error_msg = "فایل غیرمجاز";
					}
					else {
						if ($templateversion != $software['version']) {
							$error_msg = $templatename . " is not configured for PTCEvolution " . $software['version'];
						}
						else {
							$verifytpl = $db->fetchOne("SELECT COUNT(*) AS NUM FROM templates WHERE name='" . $tplxml->name . "' and version='" . $software['version'] . "'");

							if ($verifytpl == 0) {
								$datastored = array("name" => $templatename, "version" => $templateversion, "folder" => $templatedirectory);
								$insert = $db->insert("templates", $datastored);
								@mkdir("templates_c/" . $tplxml->directory . "/", 511);
								$success_msg = $templatename . " was sucessfully installed! <a href=\"?view=template_settings\"> برای مدیریت قالب نصب شده کلیک نمایید </a>";
							}
							else {
								$error_msg = $templatename . " روی سایت نصب شده است ";
							}
						}
					}

					@unlink("../upload/" . $_FILES['file']['name']);
					$cache->delete("template");
				}
			}
		}
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">نصب یک قالب جدید</div>
<div class=\"site_content\">
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

echo "    <form method=\"post\" enctype=\"multipart/form-data\">
        <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
        <input type=\"hidden\" name=\"do\" value=\"install\" />
    <table class=\"widget-tbl\" width=\"100%\">
      <tr>
        <td align=\"right\" width=\"300\">یک فایل با پسوند xml آپلود نمایدد</td>
        <td><input type=\"file\" name=\"file\" id=\"file\" /> </td>
      </tr>
      <tr>
      	<td></td>
        <td><input type=\"submit\" name=\"save\" value=\"نصب\" class=\"orange\" /></td>
      </tr>
    </table>
    </form>

</div>


        ";
include SOURCES . "footer.php";
echo " ";
?>