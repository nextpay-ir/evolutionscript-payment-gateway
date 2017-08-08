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




if (!$admin->permissions['administrators']) {
	$conditional = ("username='" . $admin->getUsername() . "'");
}

$allowed = array("fail", "username", "ip", "date");
$paginator = new Pagination("admin_loginlog", $conditional);
$paginator->setMaxResult(10);
$paginator->setOrders("date", "DESC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=loginlog&");
$q = $paginator->getQuery();
include SOURCES . "header.php";
echo "<script type=\"text/javascript\">
$(document).on('ready', function(){
	$(\"#tabs\").tabs();
});
</script>

<div class=\"site_title\">تاریخچه ورود</div>
<div class=\"site_content\">

  <table width=\"100%\" class=\"widget-tbl\">
  	<tr class=\"titles\">
    	<td width=\"20\">";
echo $paginator->linkorder("fail", "&nbsp;");
echo "</td>
        <td width=\"400\">";
echo $paginator->linkorder("username", "اطلاعات کاربر");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("ip", "IP آدرس");
echo "</td>
        <td align=\"center\">";
echo $paginator->linkorder("date", "تاریخ");
echo "</td>
    </tr>
    ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "  	<tr class=\"";
	echo $tr;
	echo "\">
    	<td align=\"center\"><img src=\"./css/images/";
	echo $r['fail'] == 0 ? "accept" : "fail";
	echo ".png\"</td>
        <td>
            <div ";
	echo $r['fail'] == 1 ? "class=\"fail_td\"" : "";
	echo ">
            ";
	echo $r['username'];
	echo "<br />
            <strong>User Agent:</strong> ";
	echo $r['agent'];
	echo "            </div>
        </td>
        <td align=\"center\">";
	echo $r['ip'];
	echo "</td>
        <td align=\"center\">";
	echo jdate("Y/m/d ساعت h:i A", $r['date']);
	echo "</td>
    </tr>
    ";
}


if ($paginator->totalResults() == 0) {
	echo "    <tr>
    	<td colspan=\"4\" align=\"center\">Records not found</td>
    </tr>
    ";
}

echo "  </table>
    <div style=\"margin-top:10px\">
    <input type=\"button\" value=\"&larr; صفحه قبل\" ";
echo ($paginator->totalPages() == 1 || $paginator->getPage() == 1) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->prevpage() . "';\";";
echo " />

    <input type=\"button\" value=\"صفحه بعد &rarr;\" ";
echo ($paginator->totalPages() == 0 || $paginator->totalPages() == $paginator->getPage()) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->nextpage() . "';\";";
echo " />
    	";

if (1 < $paginator->totalPages()) {
	echo "     صفحه :  <div style=\"float:right\">
        
        <select name=\"p\" style=\"min-width:inherit;\" id=\"pagid\" onchange=\"gotopage(this.value)\">
            ";
	$i = 1;

	while ($i <= $paginator->totalPages()) {
		if ($i == $paginator->getPage()) {
			echo "<option selected value=\"" . $paginator->gotopage($i) . "\">" . $i . "</option>";
		}
		else {
			echo "<option value=\"" . $paginator->gotopage($i) . "\">" . $i . "</option>";
		}

		++$i;
	}

	echo "        </select>
        <script type=\"text/javascript\">
			function gotopage(pageid){
				location.href=pageid;
			}
		</script>
        </div>
        <div class=\"clear\"></div>
        ";
}

echo "    </div>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>