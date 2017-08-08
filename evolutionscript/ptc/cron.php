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

define("EvolutionScript", 1);
define("ROOTPATH", dirname(__FILE__) . "/");
define("INCLUDES", ROOTPATH . "includes/");
require_once INCLUDES . "global.php";
$query = $db->query("SELECT * FROM cron_settings");

while ($result = $db->fetch_array($query)) {
	$cron[$result['field']] = $result['value'];
}

$todaysdate = date("Y-m-d");
$the_day = strftime("%Y-%m-%d", strtotime($todaysdate . " + 1 days ago"));

if ($cron['last_cron'] < $the_day) {
	include ROOTPATH . "admin/cronadmin.php";
}

?>