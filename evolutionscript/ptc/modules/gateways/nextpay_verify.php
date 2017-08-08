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



//marboot be dargah
$m_api = $gateway['api_key'];
$id = $_SESSION["ids"];
$amount = $_SESSION["amounts"]; 
$au = $_SESSION["aus"];
//marboot be dargah


$order_id = $_SESSION["user_ids"];
$upgrade = $_SESSION["$upgrades"];
$upgrade_id = $_SESSION["upgrade_ids"];
$today = TIMENOW;



$trans_id = $_POST['trans_id'];
$batch = $trans_id;

$client = new SoapClient('https://api.nextpay.org/gateway/verify.wsdl', array('encoding' => 'UTF-8'));
$result = $client->PaymentVerification(
    array(
        'api_key' => $m_api,
        'trans_id'  => $trans_id,
        'amount'	 => $amount,
        'order_id'	=> $id
    )
);
$result = $result->PaymentVerificationResult;


if(intval($result->code) == 0){

		if (is_numeric($upgrade_id)) {
	        include GATEWAYS . "process_upgrade.php";
	        header("location:" . $settings['site_url'] . "index.php?view=account&page=thankyou&type=upgrade");
                exit();
		}
		
                else {		
		include GATEWAYS . "process_deposit.php";
	        header("location:" . $settings['site_url'] . "index.php?view=account&page=thankyou&type=funds");
                exit();
                }


}

else {

if (is_numeric($upgrade_id)) {
header("location:" . $settings['site_url'] . "index.php?view=account&page=upgrade");
exit();} 

else  {
header("location: " . $settings['site_url'] . "index.php?view=account&page=addfunds");
exit();}


}









?>