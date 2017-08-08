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


if ($input->p['do'] == "addblacklist") {
	verifyajax();
	verifydemo();
	$data = array("date" => TIMENOW, "type" => $input->pc['type'], "criteria" => $input->pc[$input->pc['type']], "note" => $input->pc['note']);
	$db->insert("blacklist", $data);
	serveranswer(4, "location.href=\"./?view=blacklist\";");
}


if ($input->p['action']) {
	if ($settings['demo'] == "yes") {
		$error_msg = "This is not possible in demo version";
	}
	else {
		if ($input->p['sesion_id'] != $_SESSION['sesion_id']) {
			$error_msg = "Invalid token try again please";
		}
		else {
			if (is_array($input->p['mid'])) {
				foreach ($input->p['mid'] as $mid) {
					switch ($input->p['a']) {
					   case "delete":
							$db->delete("blacklist", "id=" . $mid);
					}
				}
			}
		}
	}
}


if ($input->r['do'] == "search") {
	$searchvars = array("type", "criteria", "from_date", "to_date", "note");
	foreach ($searchvars as $k) {

		if ($input->r[$k]) {
			switch ($k) {
                case "criteria":
					$cond .= ("(") . $k . " LIKE '%" . $input->rc[$k] . "%') AND ";
					break;

                case "note":
					$cond .= ("(") . $k . " LIKE '%" . $input->rc[$k] . "%') AND ";
					break;

                case "from_date":
					$from_date = daterange($input->rc['from_date']);
					$cond .= "date >= " . $from_date[0] . " AND ";
					break;

                case "to_date":
					$to_date = daterange($input->rc['to_date']);
					$cond .= "date <= " . $to_date[1] . " AND ";
					break;

                default :
                    $cond .= $k . "='" . $input->rc[$k] . "' AND ";
                    break;
			}

			$adlink .= $k . "=" . $input->r[$k] . "&";
		}
	}


	if ($cond) {
		$cond = substr($cond, 0, -5);
	}


	if ($adlink) {
		$adlink = "do=search&" . $adlink;
	}
}

$paginator = new Pagination("blacklist", $cond);
$paginator->setOrders("type", "DESC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=blacklist&" . $adlink);
$q = $paginator->getQuery();
$blacktype = array("country" => "Country", "ip" => "IP", "email" => "E-mail", "username" => "Username");
include SOURCES . "header.php";
echo "<script type=\"text/javascript\">
$(document).on('ready', function(){
	var check = $(\"#typesel\").val();
	verifytypeblock(check);
        $(\"#from_date\").datepicker({
            defaultDate: \"+1w\",
            numberOfMonths: 1,
            onClose: function(selectedDate) {
                $(\"#to_date\").datepicker(\"option\", \"minDate\", selectedDate);
            }
        });
        $(\"#to_date\").datepicker({
            defaultDate: \"+1w\",
            numberOfMonths: 1,
            onClose: function(selectedDate) {
                $(\"#from_date\").datepicker(\"option\", \"maxDate\", selectedDate);
            }
        });
});
function verifytypeblock(block){
	$(\".info_box\").hide();
	$(\"#\"+block+\"_info\").fadeIn();
	$(\".tr_info\").hide();
	$(\"#\"+block+\"_tr\").fadeIn();
}
</script>
<div class=\"site_title\">لیست سیاه</div>
<div class=\"site_content\">
<div id=\"tabs\">
	<ul>
        <li><a href=\"#tabs-1\">مدیریت لیست سیاه</a></li>
    	<li><a href=\"#tabs-2\">افزودن به لیست سیاه</a></li>
    </ul>
    <div id=\"tabs-1\">
		";

if ($error_msg) {
	echo "        <div class=\"error_box\">";
	echo $error_msg;
	echo "</div>
        ";
}

echo "
        <div class=\"ui-tabs ui-widget ui-widget-content ui-corner-all\" style=\"margin-bottom:5px\">
           <ul class=\"ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all\">
             <li class=\"ui-state-default ui-corner-top ui-tabs-selected ui-state-active\"><a href=\"javascript:void(0);\" onclick=\"$('#search-content').slideToggle();\" style=\"cursor:pointer\">جستجو</a></li>
          </ul>
           <div class=\"ui-tabs-panel ui-widget-content ui-corner-bottom\" id=\"search-content\" ";
echo !$adlink ? "style=\"display:none\"" : "";
echo ">
        <form method=\"post\">
                    <input type=\"hidden\" name=\"do\" value=\"search\" />
                    <table width=\"100%\" class=\"widget-tbl\">
                        <tr>
                            <td align=\"right\">نوع : </td>
                            <td>
                	<select name=\"type\">
                    	<option value=\"\">همه</option>
                    	";
foreach ($blacktype as $k => $v) {

	if ($input->r['type'] == $k) {
		echo "<option value=\"" . $k . "\" selected>" . $v . "</option>";
		continue;
	}

	echo "<option value=\"" . $k . "\">" . $v . "</option>";
}

echo "                    </select>
                            </td>
                            <td align=\"right\">مقدار</td>
                            <td><input  style=\"direction:ltr;\" type=\"text\" name=\"criteria\" value=\"";
echo $input->r['criteria'];
echo "\" /></td>
                        </tr>
                        <tr>
                            <td align=\"right\">از تاریخ : </td>
                            <td><input type=\"text\" name=\"from_date\" value=\"";
echo $input->r['from_date'];
echo "\" id=\"hannandate\" /></td>
                            <td align=\"right\">تا تاریخ : </td>
                            <td><input type=\"text\" name=\"to_date\" value=\"";
echo $input->r['to_date'];
echo "\" id=\"hannandate2\" /></td>
                        </tr>
                        <tr>
                        	<td align=\"right\">یادداشت : </td>
                            <td colspan=\"3\"><input type=\"text\" name=\"note\" /></td>
                        </tr>
                        <tr>
                             <td colspan=\"4\" align=\"center\"><input type=\"submit\" name=\"send\" value=\"جستجو\">
                        </tr>
                    </table>
                    </form>
           </div>
        </div>

        <form method=\"post\" action=\"";
echo $paginator->gotopage();
echo "\">
        <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
          <table width=\"100%\" class=\"widget-tbl\">
            <tr class=\"titles\">
                <td width=\"20\"><input type=\"checkbox\" id=\"checkall\"></td>
                <td>";
echo $paginator->linkorder("jdate", "زمان افزوده شدن");
echo "</td>
                <td>";
echo $paginator->linkorder("type", "نوع");
echo "</td>
                <td>";
echo $paginator->linkorder("criteria", "مقدار");
echo "</td>
                <td>";
echo $paginator->linkorder("note", "یادداشت");
echo "</td>
            </tr>
            ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "            <tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
                <td align=\"center\"><input type=\"checkbox\" name=\"mid[]\" value=\"";
	echo $r['id'];
	echo "\" class=\"checkall\" /></td>
                <td>";
	echo jdate("d F Y ساعت h:i A", $r['date']);
	echo "</td>
                <td><span style=\"color:#000099\">";
	echo $blacktype[$r['type']];
	echo "</span>
                </td>
                <td>
                    <span style=\"color:green\">";
	echo $r['criteria'];
	echo "</span>
                </td>
                <td><span style=\"color:orange\">";
	echo $r['note'];
	echo "</span></td>
            </tr>
            ";
}


if ($paginator->totalResults() == 0) {
	echo "            <tr>
                <td colspan=\"8\" align=\"center\">موردی یافت نشد</td>
            </tr>
            ";
}

echo "          </table>
            <div style=\"margin-top:10px\">

            <input type=\"button\" value=\"&larr; صفحه قبل\" ";
echo ($paginator->totalPages() == 1 || $paginator->getPage() == 1) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->prevpage() . "';\";";
echo " />

            <input type=\"button\" value=\"صفحه بعد &rarr;\" ";
echo ($paginator->totalPages() == 0 || $paginator->totalPages() == $paginator->getPage()) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->nextpage() . "';\";";
echo " />
                ";

if (1 < $paginator->totalPages()) {
	echo "                <div style=\"float:right\">
                رفتن به صفحه : 
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

	echo "                </select>
                <script type=\"text/javascript\">
                    function gotopage(pageid){
                        location.href=pageid;
                    }
                </script>
                </div>
                <div class=\"clear\"></div>
                ";
}

echo "            </div>

            ";

if (0 < $paginator->totalPages()) {
	echo "            <div class=\"widget-title\" style=\"margin-top:5px\">اقدام</div>
                <div class=\"widget-content\">
                    <select name=\"a\">
                        <option value=\"\">یکی را انتخاب کنید</option>
                        <option value=\"delete\">حذف</option>
                    </select>
                    <input type=\"submit\" name=\"action\" value=\"اجرا\" />
                </div>
            ";
}

echo "        </form>


    </div>
    <div id=\"tabs-2\">
    	<form method=\"post\" onsubmit=\"return submitform(this.id);\" id=\"frm1\">
        <input type=\"hidden\" name=\"do\" value=\"addblacklist\" />
        <div class=\"info_box\" id=\"ip_info\" style=\"display:none\">
       <strong>مثال</strong><br />
مثلا اگر میخواهید آی پی های بازه 100.100.255.255 - 100.100.0.0 را بن کنید وارد کنید : *.100.100.100 یعنی آخرین رقم را ستاره قرار دهید <br />

مثلا اگر میخواهید آی پی های بازه 100.100.255.255 - 100.100.0.0 را بن کنید وارد کنید : *.*.100.100 یعنی دو رقم آخر را ستاره قرار دهید <br />


مثلا اگر میخواهید آی پی های بازه 100.255.255.255 - 100.0.0.0 را بن کنید وارد کنید : *.*.*.100 یعنی سه رقم آخر را ستاره قرار دهید <br />

        </div>
        <div class=\"info_box\" id=\"email_info\" style=\"display:none\">
<strong>مثال</strong> <br />
مثلا اگر میخواهید تمام ایمیلهای یاهو را بن نمایید جای آیدی ستاره قرار دهید : yahoo.com@*<br />
پس به طور کلی به صورت مقابل است : the-banned-email@the-email-domain.com 
        </div>
        <table width=\"100%\" class=\"widget-tbl\">
        	<tr>
            	<td align=\"right\" width=\"200\">نوع : </td>
                <td>
                	<select name=\"type\" onchange=\"verifytypeblock(this.value);\" id=\"typesel\">
                    	";
foreach ($blacktype as $k => $v) {
	echo "<option value=\"" . $k . "\">" . $v . "</option>";
}

echo "                    </select>
                </td>
            </tr>
            <tr id=\"country_tr\" style=\"display:none\" class=\"tr_info\">
            	<td align=\"right\">کشور</td>
                <td>
                <select name=\"country\">
        ";
$listcountry = $db->query("SELECT * FROM ip2nationCountries ORDER BY country ASC");

while ($country = $db->fetch_array($listcountry)) {
	echo "<option value=\"" . $country['country'] . "\"> " . $country['country'] . "</option>";
}

echo "                </select>
                </td>
            </tr>
            <tr id=\"ip_tr\" style=\"display:none\" class=\"tr_info\">
            	<td align=\"right\">IP</td>
                <td><input type=\"text\" name=\"ip\" style=\"direction:ltr;\"/>
                </td>
            </tr>
            <tr id=\"email_tr\" style=\"display:none\" class=\"tr_info\">
            	<td align=\"right\">ایمیل</td>
                <td><input type=\"text\" name=\"email\"  style=\"direction:ltr;\"/>
                </td>
            </tr>
            <tr id=\"username_tr\" style=\"display:none\" class=\"tr_info\">
            	<td align=\"right\">نام کاربری</td>
                <td><input type=\"text\" name=\"username\" style=\"direction:ltr;\" />
                </td>
            </tr>
            <tr>
            	<td align=\"right\">یادداشت</td>
                <td><input type=\"text\" name=\"note\" />
                </td>
            </tr>
            <tr>
            	<td></td>
                <td><input type=\"submit\" name=\"btn\" value=\"افزودن به لیست سیاه\" /></td>
            </tr>
        </table>
        </form>
    </div>
</div>

</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>