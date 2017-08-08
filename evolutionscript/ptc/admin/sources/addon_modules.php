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

$modulepath = "../modules/admin";

if (is_dir($modulepath) !== true) {
	$show_error = "yes";
}
else {
	$handle = opendir($modulepath);
	$addonlist = array();

	while (($file = readdir($handle)) !== false) {
		if (is_dir($modulepath . "/" . $file) === true && $file != "." && $file != "..") {
			$addonlist[$file] = ucwords(str_replace("_", " ", $file));
		}
	}
}


if ($input->g['module']) {
	if (array_key_exists($input->g['module'], $addonlist)) {
		$module = cleanfrm($_GET['module']);
		$modulelink = "?view=addon_modules&module=" . $module;
		include SOURCES . "header.php";
		echo "
		<div class=\"site_title\">ماژول های نصب شده</div>
		<div class=\"site_content\">
        <div class=\"widget-main-title\">";
		echo $addonlist[$module];
		echo "</div>
		";
		include $modulepath . "/" . $module . "/" . $module . ".php";
		echo "		</div>
";
		include SOURCES . "footer.php";
		exit();
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">ماژول های نصب شده</div>
<div class=\"site_content\">
";

if ($show_error == "yes") {
	echo "<div class=\"errorbox\">مسیر ماژول ها موجود نیست</div>";
}
else {
	if (is_array($addonlist)) {
		echo "<ul>";
		foreach ($addonlist as $k => $v) {
			echo "<li><a href=\"./?view=addon_modules&module=" . $k . "\">" . $v . "</a></li>";
		}

		echo "</ul>";
	}
}

echo "</div>

        ";
include SOURCES . "footer.php";
echo " ";
?>