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

session_start();
define("INCLUDES_ADMIN", dirname(__FILE__) . "/includes/");
define("SOURCES", dirname(__FILE__) . "/sources/");
define("ROOTPATH", dirname(dirname(__FILE__)) . "/");
define("MODULESPATH", ROOTPATH . "modules/");
require_once INCLUDES_ADMIN . "functions.php";
require_once ROOTPATH . "includes/global.php";
require_once INCLUDES_ADMIN . "admin_inc.php";
$query = $db->query("SELECT * FROM settings");

while ($result = $db->fetch_array($query)) {
	$settings[$result['field']] = $result['value'];
}


if (in_array($settings['timezone'], $timezone)) {
	date_default_timezone_set($settings['timezone']);
}

$chkadmin = new VData();
$localkey = $db->fetchOne("SELECT localkey FROM localkey WHERE id=1");
$chkadmin->getinfo($localkey);
$chkadmin->validate($ptcevolution->config['Misc']['license']);

if ($chkadmin->checkstatus !== true) {
	$chkadmin->details($ptcevolution->config['Misc']['license']);
	$chkadmin->masterkey;
	$chkadmin->getinfo();
	$chkadmin->validate($ptcevolution->config['Misc']['license']);
	$db->query("UPDATE settings SET value='1' WHERE field='copyright'");

	if ($chkadmin->checkstatus !== true) {
		$chkadmin->response();
		return 1;
	}

	$data = array("localkey" => $chkadmin->masterkey);
	$db->update("localkey", $data, "id=1");

	if ($chkadmin->info['copyright'] == "0") {
		$db->query("UPDATE settings SET value='0' WHERE field='copyright'");
	}
}

?>