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

echo "<div class=\"widget-title\">اطلاعات سیستم</div>
<div class=\"widget-content\">
<table width=\"100%\" class=\"widget-tbl\">
<tr>
<td width=\"200\"><strong>ثبت شده به نام :</strong></td>
<td>";
echo $chkadmin->info['clientname'];
echo " (";
echo $chkadmin->info['clientemail'];
echo ")</td>
</tr>
<tr>
<td><strong>نوع لایسنس</strong></td>
<td>";
echo $chkadmin->info['product'];
echo "</td>
</tr>
<tr>
<td><strong>لایسنس کی</strong></td>
<td>";
echo $ptcevolution->config['Misc']['license'];
echo "</td>
</tr>
<tr>
<td><strong>دامنه</strong></td>
<td>";
echo $chkadmin->info['domain'];
echo "</td>
</tr>
<tr>
<td><strong>انقضای لایسنس</strong></td>
<td>";

if ($chkadmin->info['expires'] == 0) {
	echo "هرگز";
}
else {
	jdate("d F Y ساعت h:i A", $chkadmin->info['expires']);
}

echo "</td>
</tr>
<tr>
<td><strong>انقضای پشتیبانی</strong></td>
<td>";

if ($chkadmin->info['support'] == 0) {
	echo "هرگز";
}
else {
	echo jdate("d F Y ساعت h:i A", $chkadmin->info['support']);
}

echo "</td>
</tr>

</table>
</div>
<div class=\"widget-title\">فارسی سازی و توسعه</div>
<div class=\"widget-content\">
<table width=\"100%\"  class=\"widget-tbl\">
<tr>
<td width=\"200\"><strong>ارائه نسخه اولیه</strong></td>
<td>امیرحسین طاووسی</td>
</tr>
<tr>
<td><strong>تکمیل و توسعه نظیر : فارسی سازی مدیریت + شمسی سازی + افزودن درگاه ایرانی</strong></td>
<td><a href=\"http://arshad98.ir\">حنان ابراهیمی ستوده</a></td>
</tr>
<tr>
<td><strong>حامی : </strong></td>
<td><a href=\"http://zarinpal.com\">زرین پال</a></td>
</tr>
<tr>
<td><strong>انتشار یافته در : </strong></td>
<td><a href=\"http://persianscript.ir\">پرشین اسکریپت</a></td>
</tr>
</table>
    </div>";
?>