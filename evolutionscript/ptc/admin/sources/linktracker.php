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


if ($input->p['do'] == "create") {
	verifyajax();
	verifydemo();

	if (empty($input->p['descr'])) {
		serveranswer(0, "یک نام معتبر وارد کنید");
	}


	if (empty($input->p['name'])) {
		serveranswer(0, "یک آدرس اینترنتی معتبر وارد کنید");
	}


	if (!chdjjfigje($input->p['name'])) {
		serveranswer(0, "نام باید شامل حروف و عدد باشد");
	}

	$data = array("date" => TIMENOW, "name" => $input->pc['name'], "descr" => $input->pc['descr']);
	$db->insert("linktracker", $data);
	serveranswer(4, "location.href='./?view=linktracker';");
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
							$db->delete("linktracker", "id=" . $mid);
					}
				}
			}
		}
	}
}

$paginator = new Pagination("linktracker", $cond);
$paginator->setOrders("date", "DESC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=linktracker&" . $adlink);
$q = $paginator->getQuery();
include SOURCES . "header.php";
echo "<div class=\"site_title\">آمار گیر کمپین ها</div>
<div class=\"site_content\">
<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tabs-1\">مدیریت کمپین ها</a></li>
        <li><a href=\"#tabs-2\">افزودن کمپین جدید</a></li>
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
echo $paginator->linkorder("jdate", "تاریخ افزوده شدن");
echo "</td>
                        <td>";
echo $paginator->linkorder("descr", "نام");
echo "</td>
                        <td>";
echo $paginator->linkorder("name", "آدرس");
echo "</td>
                        <td align=\"center\">";
echo $paginator->linkorder("hits", "مجموع بازدیدها");
echo "</td>
                        <td align=\"center\">";
echo $paginator->linkorder("uniquehits", "مجموع بازدید کننده ها");
echo "</td>
                        <td align=\"center\">";
echo $paginator->linkorder("signups", "مجموع ثبت نام ها");
echo "</td>
                    </tr>
                    ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "                    <tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
                        <td align=\"center\"><input type=\"checkbox\" name=\"mid[]\" value=\"";
	echo $r['id'];
	echo "\" class=\"checkall\" /></td>
                        <td>";
	echo jdate("d F Y ساعت h:i A", $r['date']);
	echo "</td>
                        <td><span style=\"color:#000099\">";
	echo $r['descr'];
	echo "</span>
                        </td>
                        <td>
                            <span style=\"color:green\">";
	echo $settings['site_url'] . "?track=" . $r['name'];
	echo "</span>
                        </td>
                        <td align=\"center\"><span style=\"color:orange\">";
	echo $r['hits'];
	echo "</span></td>
                        <td align=\"center\"><span style=\"color:#000099\">";
	echo $r['uniquehits'];
	echo "</span></td>
                        <td align=\"center\"><span style=\"color:#990000\">";
	echo $r['signups'];
	echo "</span></td>
                    </tr>
                    ";
}


if ($paginator->totalResults() == 0) {
	echo "                    <tr>
                        <td colspan=\"8\" align=\"center\">موردی یافت نشد</td>
                    </tr>
                    ";
}

echo "                  </table>
                    <div style=\"margin-top:10px\">
                    <input type=\"button\" value=\"&larr; صفحه قبل\" ";
echo ($paginator->totalPages() == 1 || $paginator->getPage() == 1) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->prevpage() . "';\";";
echo " />

                    <input type=\"button\" value=\"صفحه بعد &rarr;\" ";
echo ($paginator->totalPages() == 0 || $paginator->totalPages() == $paginator->getPage()) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->nextpage() . "';\";";
echo " />
                        ";

if (1 < $paginator->totalPages()) {
	echo "                        <div style=\"float:right\">
                        Jump to page:
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

	echo "                        </select>
                        <script type=\"text/javascript\">
                            function gotopage(pageid){
                                location.href=pageid;
                            }
                        </script>
                        </div>
                        <div class=\"clear\"></div>
                        ";
}

echo "                    </div>

                    ";

if (0 < $paginator->totalPages()) {
	echo "                    <div class=\"widget-title\" style=\"margin-top:5px\">Action</div>
                        <div class=\"widget-content\">
                            <select name=\"a\">
                                <option value=\"\">Select one</option>
                                <option value=\"delete\">Delete</option>
                            </select>
                            <input type=\"submit\" name=\"action\" value=\"Submit\" />
                        </div>
                    ";
}

echo "                </form>
    </div>
    <div id=\"tabs-2\">
    	<div class=\"info_box\">آمارگیر کمپین ها این کمک را به شما میکند که ببینید ترافیک ها از کجا می آیند</div>
    <form method=\"post\" id=\"addnewcamp\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"do\" value=\"create\" />
    <table width=\"100%\" class=\"widget-tbl\">
      <tr>
        <td width=\"200\" align=\"right\">نام آمار گیر کمپین</td>
        <td><input type=\"text\" name=\"descr\" /></td>
      </tr>
      <tr>
        <td width=\"300\" style=\"direction:ltr !important;\" align=\"right\">آدرس اینترنتی</td>
        <td style=\"direction:ltr !important;\" >";
echo $settings['site_url'];
echo "?track=<input style=\"direction:ltr !important;\"  type=\"text\" name=\"name\" /></td>
      </tr>
      <tr>
      <td></td>
      <td>
        <input type=\"submit\" name=\"save\" value=\"افزودن\" />
      </td>
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