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

$processor_form = "
<form action=\"/modules/gateways/zaringate.php\" method=\"post\" id=\"checkout[id]\">
<input type=\"hidden\" name=\"itemname\" value=\"[itemname]\">
<input type=\"hidden\" name=\"user\" value=\"[userid]\">
<input type=\"hidden\" name=\"amount\" id=\"amount[id]\" value=\"[price]\">
<input type=\"hidden\" name=\"upgrade\" value=\"upgrade\">
<input type=\"hidden\" name=\"upgradeid\" value=\"\" id=\"upgrade[id]\"/>
</form>
";
?>

