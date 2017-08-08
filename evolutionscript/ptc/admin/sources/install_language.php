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
					$langnames = $doc->getElementsByTagName("name");
					$langname = $langnames->item(0)->nodeValue;
					$langversions = $doc->getElementsByTagName("ptcevolution");
					$langversion = $langversions->item(0)->nodeValue;
					$langfiles = $doc->getElementsByTagName("filename");
					$langfile = $langfiles->item(0)->nodeValue;

					if (empty($langname) || empty($langversion) || empty($langfile)) {
						$error_msg = "فایل غیرمجاز";
					}
					else {
						if ($langversion != $software['version']) {
							$error_msg = $langname . " is not configured for PTCEvolution " . $software['version'];
						}
						else {
							$verifylang = $db->fetchOne("SELECT COUNT(*) AS NUM FROM language WHERE name='" . $langname . "' and version='" . $software['version'] . "'");

							if ($verifylang == 0) {
								$datastored = array("name" => $langname, "version" => $langversion, "filename" => $langfile);
								$insert = $db->insert("language", $datastored);
								$success_msg = $langname . "  was sucessfully installed! <a href=\"./?view=language_settings\"> برای مدیریت زبان نصب شده کلیک کنید </a>";
							}
							else {
								$error_msg = $langname . " برروی سایت نصب شده است  ";
							}
						}
					}

					@unlink("../upload/" . $_FILES['file']['name']);
					$cache->delete("languages");
				}
			}
		}
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">نصب زبان جدید</div>
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

echo "        <form method=\"post\" enctype=\"multipart/form-data\">
        <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
        <input type=\"hidden\" name=\"do\" value=\"install\" />
        <table class=\"widget-tbl\" width=\"100%\">
          <tr>
            <td width=\"300\" align=\"right\">یک فایل زبان با پسوند xml آپلود نمایید</td>
            <td><input type=\"file\" name=\"file\" id=\"file\" /> </td>
          </tr>
          <tr>
          	<td></td>
            <td><input type=\"submit\" name=\"save\" value=\"نصب\" /></td>
          </tr>
        </table>
        </form>

</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>