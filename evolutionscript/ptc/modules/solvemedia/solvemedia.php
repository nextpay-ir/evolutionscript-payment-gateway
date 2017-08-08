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

function validate_captcha() {
	global $privatekey;
	global $hashkey;
	global $lang;

	$resp = solvemedia_check_answer($privatekey, $_SERVER['REMOTE_ADDR'], $_POST['adcopy_challenge'], $_POST['adcopy_response'], $hashkey);

	if (!$resp->is_valid) {
		$stored = array("status" => 0, "msg" => $lang['txt']['invalidimageverification']);
		echo json_encode($stored);
		exit();
	}

}

if (!defined("EvolutionScript")) {
	exit("Hacking attempt...");
}

require_once "modules/solvemedia/solvemedialib.php";

if (empty($settings['solvemedia_ckey']) || empty($settings['solvemedia_vkey']) || empty($settings['solvemedia_hkey'])) {
	exit("Invalid solvemedia configuration. Contact to site administrator");
}

$resp = null;
$error = null;

if (!empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "on") {
	$use_ssl = true;
}
else {
	$use_ssl = false;
}

$captcha_html = solvemedia_get_html($settings['solvemedia_ckey'], $error, $use_ssl);
$captcha_html .= "<script>function captchareload(){ ACPuzzle.reload(''); }</script>
";
$smarty->assign("captcha", $captcha_html);
$privatekey = $settings['solvemedia_vkey'];
$hashkey = $settings['solvemedia_hkey'];
?>