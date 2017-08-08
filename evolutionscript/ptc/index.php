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

session_start();
define("EvolutionScript", 1);
define("DISABLE_TEMPLATE", 1);
require_once "./includes/init.php";
$site_url = $settings['site_url'];
$_requestedHost = $_SERVER['HTTP_HOST'];
$_configuredHost = parse_url($site_url, PHP_URL_HOST);
$uri = ($_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : @getenv("REQUEST_URI"));
$_t = parse_url($site_url);
$_toTest = ($_t['path'] && $_t['path'] != "/") ? preg_replace(("#^" . $_t['path'] . "#"), "", $uri) : str_replace($_t['scheme'] . "://" . $_t['host'], "", $uri);

if (strpos($_configuredHost, "www.") === 0 && strpos($_requestedHost, "www.") !== 0) {
	header("location: " . $site_url . $_toTest);
	exit();
}
else {
	if (strpos($_configuredHost, "www.") !== 0 && strpos($_requestedHost, "www.") === 0) {
		header("location: " . $site_url . $_toTest);
		exit();
	}
}


if ($input->g['view']) {
	$controller = strtolower(htmlentities($input->g['view'])) . ".php";

	if (!file_exists(SOURCES . $controller)) {
		$controller = "home.php";
	}
}
else {
	$controller = "home.php";
}


if ($input->g['view'] == "register" || $input->g['view'] == "surfer") {
	include SMARTYLOADER;
}

include SOURCES . $controller;
exit();
?>