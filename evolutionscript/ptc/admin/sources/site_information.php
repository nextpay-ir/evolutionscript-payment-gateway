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

$contrynum = $db->fetchOne("SELECT COUNT(*) AS NUM FROM country");
$statsquery = $db->query("SELECT * FROM statistics");

while ($list = $db->fetch_array($statsquery)) {
	$stats[$list['field']] = $list['value'];
}

$unreferred = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE ref1='0'");
$rented = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE rented!='0'");
$upgraded = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE type!='1'");
$usersbalance = $db->fetchOne("SELECT SUM(money) FROM members");
$totalmembers = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE username!='BOT'");
$timespaid = $db->fetchOne("SELECT COUNT(*) AS NUM FROM withdraw_history WHERE status='Completed'");
$active_members = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE status='Active'");

if (empty($pendingcashout)) {
	$pendingcashout = 0;
}

echo "                <div class=\"widget-title\">اطلاعات</div>
                <div class=\"widget-content corner-all\">

        <table width=\"100%\" class=\"widget-tbl\">
          <tr>
            <td width=\"300\" align=\"right\">
           ورژن اسکریپت:            </td>
            <td>
            ";
echo $software['version'];
echo "            </td>
          </tr>
          <tr>
            <td align=\"right\">
            زیر مجموعه های موجود برای اجاره :             </td>
            <td>
             ";
echo rentedreferralsleft();
echo "            </td>
          </tr>
          <tr>
            <td align=\"right\">
             زیر مجموعه های اجاره شده :             </td>
           <td>
            ";
echo $rented;
echo "            </td>
          </tr>
          <tr>
            <td align=\"right\">
            تعداد اعضایی که حساب خود را ارتقا داده اند :            </td>
            <td>
             ";
echo $upgraded;
echo "            </td>
          </tr>
          <tr>
            <td align=\"right\">
            مجموع اعضای فعال :             </td>
            <td>
             ";
echo $active_members;
echo "            </td>
          </tr>
          <tr>
            <td align=\"right\">
            مجموع همه درآمد ها :            </td>
            <td>
            ";
echo round($usersbalance, 3);
echo "      تومان       </td>
          </tr>
          <tr>
            <td align=\"right\">
             مجموع مبالغ شارژ شده :            </td>
            <td>
              ";
echo $deposits;
echo "      تومان       </td>
          </tr>
          <tr>
            <td align=\"right\">
             میانگین در آمد هر عضو :             </td>
            <td>
              ";

if ($totalmembers == 0) {
	echo 0;
}
else {
	echo round($usersbalance / $totalmembers, 3);
}

echo "      تومان       </td>
          </tr>
          <tr>
            <td align=\"right\">
             مجموع پرداختی ها :             </td>
            <td>
             ";
echo $stats['cashout'];
echo "      تومان       </td>
          </tr>
          <tr>
            <td align=\"right\">
            مجموع منتظر پرداخت :            </td>
            <td>
             ";
echo $pendingcashout;
echo "      تومان       </td>
          </tr>
          <tr>
            <td align=\"right\">
            تعداد دفعات پرداخت :           </td>
            <td>
            ";
echo $timespaid;
echo " بار
            </td>
          </tr>
        </table>


</div>";
?>