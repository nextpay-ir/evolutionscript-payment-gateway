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

include INCLUDES . "plugins/phpmailer/class.phpmailer.php";
include INCLUDES . "classes/class_core.php";
include INCLUDES . "classes/class_mail.php";
include INCLUDES . "classes/class_mail_system.php";
include INCLUDES . "plugins/smarty/Smarty.class.php";
include INCLUDES . "plugins/phpfastcache/phpfastcache_registry.php";
include INCLUDES . "functions.php";
include INCLUDES . "adfunctions.php";
$ptcevolution = new Registry();
$input = new Input_Cleaner();
$db = new Database();
$db->connect($ptcevolution->config['Database']['dbname'], $ptcevolution->config['Database']['servername'], $ptcevolution->config['Database']['username'], $ptcevolution->config['Database']['password']);
$software['version'] = $ptcevolution->config['Misc']['version'];
?>