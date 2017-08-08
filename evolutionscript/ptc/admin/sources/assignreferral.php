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


if (!$admin->permissions['utilities']) {
	header("location: ./");
	exit();
}


if ($input->p['do'] == "update") {
	verifyajax();
	verifydemo();
	$userid = $db->fetchOne("SELECT id FROM members WHERE username='" . $input->pc['username'] . "'");
	$refs2buy = referralsleft($userid);
	$refs2rent = rentedreferralsleft($userid);

	if (!$userid) {
		serveranswer(0, "نام کاربری نامعتبر");
	}


	if (!is_numeric($input->p['refs'])) {
		serveranswer(0, "یک مقدار به زیر مجموعه ها اختصاص دهید");
	}

	switch ($input->p['dothings']) {
		case "direct":
			if ($refs2buy < $input->pc['refs']) {
				serveranswer(0, "زیر مجموعه به مقدار کافی موجود نیست");
			}
			else {
				addboughtmembers($userid, $input->pc['refs']);
				serveranswer(1, $input->pc['refs'] . " زیر مجموعه مستقیم اضافه شد به : " . $input->pc['username']);
			}
			break;

		case "rented":
			if ($refs2rent < $input->pc['refs']) {
				serveranswer(0, "زیر مجموعه به مقدار کافی موجود نیست");
			}
			else {
				addrentreferrals($userid, $input->pc['refs']);
				serveranswer(1, $input->pc['refs'] . " زیر مجموعه اجاره ای اضافه شد به :  " . $input->pc['username']);
			}
			break;

		default :
			serveranswer(0, "نوع زیر مجموعه را انتخاب نمایید");
			break;
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">اختصاص زیر مجموعه</div>
<div class=\"site_content\">
	<div class=\"info_box\">
    <strong>زیر مجموعه های موجود برای فروش : </strong> ";
echo referralsleft();
echo "<br />
<strong>زیر مجموعه های موجود برای فروش : </strong> ";
echo rentedreferralsleft();
echo "</div>
<form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
<input type=\"hidden\" name=\"do\" value=\"update\" />
<table class=\"widget-tbl\" width=\"100%\">
  <tr>
    <td width=\"150\">نام کاربری</td>
    <td><input type=\"text\" name=\"username\" id=\"username\" /></td>
  </tr>
  <tr>
    <td>زیر مجموعه ها</td>
    <td><input type=\"text\" name=\"refs\" id=\"refs\" /></td>
  </tr>
  <tr>
    <td>نوع زیر مجموعه</td>
    <td><select name=\"dothings\">
    		<option value=\"\">یکی را انتخاب کنید</option>
            <option value=\"direct\">مسقیم</option>
            <option value=\"rented\">اجاره ای</option>
            </select></td>
  </tr>
  <tr>
  	<td></td>
    <td>
  		<input type=\"submit\" name=\"save\" value=\"ثبت\" class=\"orange\" />
     </td>
  </tr>
</table>
</form>

</div>


        ";
include SOURCES . "footer.php";
echo " ";
?>