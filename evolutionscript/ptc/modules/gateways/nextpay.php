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
@session_start();
define("EvolutionScript", 1);
require_once "global.php";
$gateway = $db->fetchRow("SELECT * FROM gateways WHERE id=500");
$m_api = $gateway['api_key'];

if ($input->p['type'] == "deposit") {
	$upgrade = 0;
	$upgrade_id = 0;
}
else {
	$upgrade = 1;
	$upgrade_id = $db->real_escape_string($_POST['upgradeid']);
}


$_SESSION["upgrades"] = $upgrade;


$_SESSION["upgrade_ids"] = $upgrade_id;



$user_id = $db->real_escape_string($_POST['user']);
$user_info = $db->fetchRow("SELECT * FROM members WHERE id=" . $user_id);

$_SESSION["user_ids"]=$user_id;





//marboote be dargah
$id = $db->lastInsertId();
$_SESSION["ids"]=$id;


$amount = $_POST['amount'];
$_SESSION["amounts"]=$amount;

$callback = "" . $settings['site_url'] . "modules/gateways/nextpay_verify.php";


$client = new SoapClient('http://api.nextpay.org/gateway/token.wsdl', array('encoding' => 'UTF-8'));
$result = $client->TokenGenerator(
	array(
		'api_key' 	=> $m_api,
		'order_id'	=> $id,
		'amount' 		=> $amount,
		'callback_uri' 	=> $callback
	)
);

$result = $result->TokenGeneratorResult;

if(intval($result->code) == -1)
{
Header('Location: https://api.nextpay.org/gateway/payment/'.$result->trans_id);
} else {
echo'ERR: '.$result->code;
}

?>
