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
class RefCron {
	function RefCron($ref_id) {
		global $db;

		$this->ref_id = $ref_id;
		$this->ref = $db->fetchRow("SELECT id, type, money, refearnings, purchase_balance, refclicks, rented_referrals, autopay, mc1, mc2, mc3, mc4, mc5, mc6, mc7, r1, r2, r3, r4, r5, r6, r7, rr1, rr2, rr3, rr4, rr5, rr6, rr7, ap1, ap2, ap3, ap4, ap5, ap6, ap7, last_cron, chart_num FROM members WHERE id=" . $this->ref_id);
		$this->verify();
	}

	function get_info() {
		return $this->ref;
	}

	function verify() {
		$today_date = date("Y-m-d");
		$myclicks = array("mc1", "mc2", "mc3", "mc4", "mc5", "mc6", "mc7");

		if ($this->ref['last_cron'] == "") {
			$this->clicks_yesterday = 0;
			$this->ref['yesterday_chart_num'] = 0;
			return null;
		}

		$days_diff = dateDiff($this->ref['last_cron'], $today_date);

		if ($days_diff == 0) {
			if ($this->ref['chart_num'] == 0) {
				$this->clicks_yesterday = $this->ref[$myclicks[6]];
				$this->ref['yesterday_chart_num'] = 6;
				return null;
			}

			$this->clicks_yesterday = $this->ref[$myclicks[$this->ref['chart_num'] - 1]];
			$this->ref['yesterday_chart_num'] = $this->ref['chart_num'] - 1;
			return null;
		}

		$this->clicks_yesterday = 0;
		$this->ref['yesterday_chart_num'] = 0;
	}

	function get_chartnum() {
		return $this->ref['chart_num'];
	}

	function get_lastchartnum() {
		return $this->ref['yesterday_chart_num'];
	}

	function yesterdayclicks() {
		return $this->clicks_yesterday;
	}
}

?>