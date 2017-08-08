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


if ($input->p['do'] == "cleancache") {
	verifyajax();
	verifydemo();
	$cache->clean();
	serveranswer(1, "کش ها پاک شدند");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">پاک کردن کش ها</div>
<div class=\"site_content\">
<form method=\"post\" id=\"repairstatistics\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"do\" value=\"cleancache\" />
<div class=\"info_box\">این عملیات ، کش سایت شما را پاک خواهد کرد</div>
    <input type=\"submit\" name=\"btn\" value=\"پاک کردن کش ها\" />
</form>
</div>

";
include SOURCES . "footer.php";
echo " ";
?>