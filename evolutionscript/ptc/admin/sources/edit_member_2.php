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

echo "<table width=\"100%\" class=\"widget-tbl\">
    <tr>
        <td width=\"150\">مجموع پرداخت شده : </td>
        <td>";
echo $member['withdraw'];
echo " تومان </td>
        <td width=\"150\">درخواست تسویه حساب منتظر : </td>
        <td>";
echo $member['pending_withdraw'];
echo " تومان </td>
    </tr>
    ";
$totaldep = $db->fetchOne("SELECT SUM(amount) FROM deposit_history WHERE user_id=" . $member['id']);
$totaldep = ($totaldep == "" ? "0.00" : $totaldep);
$totalpur = $db->fetchOne("SELECT SUM(price) FROM order_history WHERE user_id=" . $member['id'] . " AND type!='purchase_balance'");
$totalpur = ($totalpur == "" ? "0.00" : $totalpur);
echo "    <tr>
        <td>مانده حساب شارژ : </td>
        <td>";
echo $totaldep;
echo " تومان </td>
        <td>مجموع خریدها : </td>
        <td>";
echo $totalpur;
echo "  تومان </td>
    </tr>
    <tr>
        <td>تعداد زمان تسویه شده : </td>
        <td>";
echo $member['cashout_times'];
echo " بار</td>
        <td>مجموع کلیک ها : </td>
        <td>";
echo $member['clicks'];
echo "</td>
    </tr>
    <tr>
        <td>تاریخ عضویت : </td>
        <td>";
echo jdate("d F Y ساعت h:i A", $member['signup']);
echo "</td>
        <td>وضعیت پرداخت خودکار : </td>
        <td>";

if ($member['autopay'] != "yes") {
	echo "غیرفعال";
}
else {
	echo "فعال";
}

echo "</td>
    </tr>
    <tr>
       <td>آخرین اجاره : </td>
       <td>";

if ($member['last_rent'] != 0) {
	echo jdate("d F Y ساعت h:i A", $member['last_rent']);
}
else {
	echo "هرگز";
}

echo "</td>
       <td>آخرین تسویه حساب : </td>
       <td>";

if ($member['last_cashout'] != 0) {
	echo jdate("d F Y ساعت h:i A", $member['last_cashout']);
}
else {
	echo "هرگز";
}

echo "</td>
    </tr>
    <tr>
      <td>آخرین آی پی : </td>
      <td><a href=\"./?view=members&do=search&ip=";
echo $member['last_ip'];
echo "\">";
echo $member['last_ip'];
echo "</a></td>
      <td>آی پی زمان ثبت نام : </td>
      <td><a href=\"./?view=members&do=search&ip=";
echo $member['signup_ip'];
echo "\">";
echo $member['signup_ip'];
echo "</a></td>
    </tr>
    <tr>
      <td>آخرین زمان ورود : </td>
      <td>";

if ($member['last_login'] != 0) {
	echo jdate("d F Y ساعت h:i A", $member['last_login']);
}
else {
	echo "هرگز";
}

echo "</td>
      <td>وضعیت : </td>
      <td>";
echo $member['status'];
echo "</td>
    </tr>
    <tr>
      <td>کلیک زیرمجموعه ها : </td>
      <td>";
echo $member['refclicks'];
echo "</td>
      <td>مبلغ بدست آورده از زیرمجموعه ها : </td>
      <td>";
echo $member['refearnings'];
echo " تومان </td>
    </tr>
    <tr>
    	<td>آمده از : </td>
        <td>";
echo $member['comes_from'];
echo "</td>
        <td></td>
        <td></td>
    </tr>
</table>

";
$myclicks = array("mc1", "mc2", "mc3", "mc4", "mc5", "mc6", "mc7");
$refclicks = array("r1", "r2", "r3", "r4", "r5", "r6", "r7");
$rentedrefclicks = array("rr1", "rr2", "rr3", "rr4", "rr5", "rr6", "rr7");
$autopayclicks = array("ap1", "ap2", "ap3", "ap4", "ap5", "ap6", "ap7");
$dia[6] = time();
$dia[5] = $dia[6] - 86400;
$dia[4] = $dia[6] - 86400 * 2;
$dia[3] = $dia[6] - 86400 * 3;
$dia[2] = $dia[6] - 86400 * 4;
$dia[1] = $dia[6] - 86400 * 5;
$dia[0] = $dia[6] - 86400 * 6;
$n = 0;
$myclick = $member['chart_num'];

while ($n <= 6) {
	if ($n == 6) {
		$mydate = "Today";
	}
	else {
		if ($n == 5) {
			$mydate = "Yesterday";
		}
		else {
			$mydate = date("Y/m/d", $dia[$n]);
		}
	}


	if ($myclick == 6) {
		$myclick = 0;
	}
	else {
		$myclick = $myclick + 1;
	}

	$mcstats .= "<set label='" . $mydate . "' value='" . $member[$myclicks[$myclick]] . "'/>";
	$mrstats .= "<set label='" . $mydate . "' value='" . $member[$refclicks[$myclick]] . "'/>";
	$mrrstats .= "<set label='" . $mydate . "' value='" . $member[$rentedrefclicks[$myclick]] . "'/>";
	$mapstats .= "<set label='" . $mydate . "' value='" . $member[$autopayclicks[$myclick]] . "'/>";
	++$n;
}

echo "<script language=\"JavaScript\" src=\"./js/FusionCharts.js\"></script>
<table width=\"100%\">
    <tr>
        <td width=\"50%\">

    <div id=\"chartdiv\" align=\"center\">
        FusionCharts. </div>
      <script type=\"text/javascript\">
           var chart = new FusionCharts(\"./js/swf/stats.swf?ChartNoDataText=Please select a record above\", \"ChartId\", \"280\", \"144\", \"0\", \"0\");
           chart.setDataXML(\"<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='User Clicks' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='2' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'>";
echo $mcstats;
echo "</chart>\");
           chart.render(\"chartdiv\");
        </script>


        </td>

        <td>


    <div id=\"chartdiv2\" align=\"center\">
        FusionCharts. </div>
      <script type=\"text/javascript\">
           var chart = new FusionCharts(\"./js/swf/stats.swf?ChartNoDataText=Please select a record above\", \"ChartId\", \"280\", \"144\", \"0\", \"0\");
           chart.setDataXML(\"<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='Direct Referral Clicks' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='2' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'>";
echo $mrstats;
echo "</chart>\");
           chart.render(\"chartdiv2\");
        </script>


        </td>
    </tr>

    <tr>
        <td><br />

    <div id=\"chartdiv3\" align=\"center\">
        FusionCharts. </div>
      <script type=\"text/javascript\">
           var chart = new FusionCharts(\"./js/swf/stats.swf?ChartNoDataText=Please select a record above\", \"ChartId\", \"280\", \"144\", \"0\", \"0\");
           chart.setDataXML(\"<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='Rented Referral Clicks' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='2' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'>";
echo $mrrstats;
echo "</chart>\");
           chart.render(\"chartdiv3\");
        </script>

        </td>
        <td><br />

    <div id=\"chartdiv4\" align=\"center\">
        FusionCharts. </div>
      <script type=\"text/javascript\">
           var chart = new FusionCharts(\"./js/swf/stats.swf?ChartNoDataText=Please select a record above\", \"ChartId\", \"280\", \"144\", \"0\", \"0\");
           chart.setDataXML(\"<chart bgSWF='charts/chart.png' canvasBorderColor='e0e0e0' lineColor='33373e' showShadow='1' shadowColor='bdbdbd' anchorBgColor='f1cc2b' caption='Autopay' showLabels='0' numVDivLines='8' hoverCapBgColor='f7df39' decimalPrecision='4' formatNumberScale='0' showValues='0'  divLineAlpha='20' alternateHGridAlpha='6'>";
echo $mapstats;
echo "</chart>\");
           chart.render(\"chartdiv4\");
        </script>
        </td>

   </tr>
</table>";
?>