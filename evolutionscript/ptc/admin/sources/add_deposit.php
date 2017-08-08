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

$gat_q = $db->query("SELECT id, name FROM gateways");
$gateway = array();

while ($r = $db->fetch_array($gat_q)) {
	$gateway[$r['id']] = $r['name'];
}


if ($input->p['a'] == "add_deposit") {
	verifyajax();
	verifydemo();
	$username = $db->real_escape_string($input->pc['username']);
	$chk = $db->fetchOne("SELECT COUNT(id) FROM members WHERE username='" . $username . "'");

	if ($chk == 0) {
		serveranswer(0, "نام کاربری غیرمجاز");
	}


	if (!is_numeric($input->p['amount']) || $input->p['amount'] < 0) {
		serveranswer(0, "مبلغ غیر مجاز");
	}


	if (!array_key_exists($input->p['method'], $gateway)) {
		serveranswer(0, "یکی از دروازه های پرداخت معتبر را انتخاب نمایید");
	}

	$user_id = $db->fetchOne("SELECT id FROM members WHERE username='" . $username . "'");
	$data = array("user_id" => $user_id, "method" => $input->p['method'], "fromacc" => $input->p['account'], "amount" => $input->p['amount'], "batch" => $input->p['batch'], "date" => TIMENOW);
	$db->insert("deposit_history", $data);

	if ($input->p['purchasebalance'] == "yes") {
		$db->query("UPDATE members SET purchase_balance=purchase_balance+" . $input->p['amount'] . " WHERE id=" . $user_id);
	}

	$db->query("UPDATE gateways SET total_deposit=total_deposit+" . $input->p['amount'] . " WHERE id=" . $input->p['method']);
	serveranswer(2, "عملیات با موفقیت انجام شد");
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">افزودن سپرده حساب</div>
<div class=\"site_content\">
<form method=\"post\" id=\"newdepositfrm\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"a\" value=\"add_deposit\" />
<table class=\"widget-tbl\" width=\"100%\">
  <tr>
    <td width=\"150\">نام کاربری</td>
    <td><input name=\"username\" type=\"text\" /></td>
    </tr>
  <tr>
    <td>مقدار</td>
    <td><input name=\"amount\" type=\"text\" value=\"0.00\" /></td>
  </tr>
  <tr>
    <td>دروازه پرداخت</td>
    <td><select name=\"method\">
                    	<option value=\"\">همه</option>
						";
foreach ($gateway as $k => $v) {

	if ($input->r['method'] == $k) {
		echo "<option value=\"" . $k . "\" selected>" . $v . "</option>";
		continue;
	}

	echo "<option value=\"" . $k . "\">" . $v . "</option>";
}

echo "                    </select></td>
  </tr>
  <tr>
    <td>آیدی تراکنش : </td>
    <td><input name=\"account\" type=\"text\" /></td>
  </tr>
  <tr>
    <td>آیدی پرداخت</td>
    <td><input name=\"batch\" type=\"text\" value=\"\" /></td>
  </tr>
  <tr>
  	<td colspan=\"2\"><input type=\"checkbox\" name=\"purchasebalance\" value=\"yes\" checked=\"checked\" /> افزودن مبلغ به حساب شارژ شده</td>
  </tr>
  <tr>
  	<td></td>
    <td>
	<input type=\"submit\" name=\"create\" value=\"ثبت\" />
    </td>
  </tr>
</table>
</form>


</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>