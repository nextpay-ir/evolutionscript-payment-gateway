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
class EgoPayAuth {
	protected $sAccountName;
	protected $sApiId;
	protected $sApiPass;

	function __construct($sAccountName, $sApiId, $sApiPass) {
		$this->sAccountName = $sAccountName;
		$this->sApiId = $sApiId;
		$this->sApiPass = $sApiPass;
	}

	function getAccountName() {
		return $this->sAccountName;
	}

	function getApiId() {
		return $this->sApiId;
	}

	function getApiPass() {
		return $this->sApiPass;
	}
}

class EgoPayApiException extends Exception {
}

class TransactionDetails {
	var $sId;
	var $sDate;
	var $fAmount;
	var $fFee;
	var $sEmail;
	var $sType;
	var $sDetails;
	var $sStatus;
}

include_once "EgoPayJsonApiAgent.php";
?>