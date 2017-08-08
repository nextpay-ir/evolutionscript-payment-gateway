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
require_once "includes/global.php";
$query = $db->query("SELECT * FROM settings");

while ($result = $db->fetch_array($query)) {
	$settings[$result['field']] = $result['value'];
}

$gateway = $db->fetchRow("SELECT * FROM gateways WHERE id=6");

if ($input->p['status'] == "ACCEPTED") {
	$amount = $input->pc['amount'];
	$db->query("UPDATE withdraw_history SET status='Completed', date='" . TIMENOW . ("' WHERE id=" . $input->pc['udf1']));
	$upd = $db->query("UPDATE gateways SET total_withdraw=total_withdraw+" . $amount . " WHERE id=" . $gateway['id']);
	$db->query("UPDATE members SET pending_withdraw=pending_withdraw-" . $amount . ", withdraw=withdraw+" . $amount . " WHERE id=" . $input->pc['udf2']);
}

?>