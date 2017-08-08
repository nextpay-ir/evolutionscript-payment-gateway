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
function validate_captcha($field1, $field2) {
	global $lang;

	if ($field1 != $_SESSION['captcha'] || $field1 == "") {
		unset($_SESSION['captcha']);
		$stored = array("status" => 0, "msg" => $lang['txt']['invalidimageverification']);
		echo json_encode($stored);
		exit();
	}

}


if (!defined("EvolutionScript")) {
	exit("Hacking attempt...");
}

$captcha_html .= "<div style=\"padding:5px 0px\"><a href='javascript:void(0);' onclick='captchareload();'><img src=\"modules/captcha/captcha.php\" id=\"captchaimg\" border=0 /></a></div>
";
$captcha_html .= "<div><input type=\"text\" name=\"captcha\" id=\"captcha\" /></div>
";
$captcha_html .= "<script>function captchareload(){
";
$captcha_html .= "$(\"#captchaimg\").attr('src','modules/captcha/captcha.php?x=34&y=75&z=119&?newtime=' + (new Date()).getTime());
";
$captcha_html .= "}</script>";
$smarty->assign("captcha", $captcha_html);
?>