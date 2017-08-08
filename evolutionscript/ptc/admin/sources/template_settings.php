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


if (!$admin->permissions['setup']) {
	header("location: ./");
	exit();
}


if ($input->p['do'] == "update_settings") {
	verifyajax();
	verifydemo();
	$db->query("UPDATE settings SET value='" . $input->pc['allowchangetemplate'] . "' WHERE field='allowchangetemplate'");
	$db->query("UPDATE templates SET default_tpl=0");
	$db->query("UPDATE templates SET default_tpl=1 WHERE id=" . $input->pc['defaulttemplate']);
	$cache->delete("template");
	$cache->delete("settings");
	serveranswer(1, "تنظیمات بروز شد");
}


if ($input->g['do'] == "uninstall") {
	if ($settings['demo'] != "yes") {
		if (is_numeric($input->g['i']) && $input->g['i'] != 1) {
			$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM templates WHERE id=" . $input->g['i'] . " AND default_tpl=1");
			$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM language WHERE id=" . $input->g['i'] . " AND default_lang=1");

			if ($verify == 1) {
				$db->query("UPDATE templates SET default_tpl=1 WHERE id=1");
			}

			$foldername = $db->fetchOne("SELECT folder FROM templates WHERE id=" . $input->g['i']);
			$delete = $db->delete("templates", "id=" . $input->g['i']);
			$cache->delete("template");
			header("location: ./?view=template_settings#tab-2");
			exit();
		}
	}
}

$paginator = new Pagination("templates", $cond);
$paginator->setOrders("id", "ASC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=language_settings&" . $adlink);
$q = $paginator->getQuery();
include SOURCES . "header.php";
echo "<div class=\"site_title\">تنظیمات قالب</div>
<div class=\"site_content\">
<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tab-1\">تنظیمات</a></li>
        <li><a href=\"#tab-2\">مدیریت قالب ها</a></li>
    </ul>
    <div id=\"tab-1\">
    <form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"do\" value=\"update_settings\" />
        <table class=\"widget-tbl\" width=\"100%\">
          <tr>
            <td width=\"50%\" valign=\"top\"><strong>قالب پیشفرض</strong>:<br />
            <div style=\"font-weight:normal;\">یک قالب برای سایت انتخاب کنید</div></td>
            <td valign=\"top\">
            ";
$tplquery = $db->query("SELECT * FROM templates ORDER BY name ASC");

while ($tpllist = $db->fetch_array($tplquery)) {
	echo "            <input type=\"radio\" name=\"defaulttemplate\" id=\"tpl";
	echo $tpllist['id'];
	echo "\" value=\"";
	echo $tpllist['id'];
	echo "\" ";

	if ($tpllist['default_tpl'] == 1) {
		echo "checked";
	}

	echo " />
            <label for=\"tpl";
	echo $tpllist['id'];
	echo "\">
            ";
	echo $tpllist['name'];
	echo "            </label><br />
            ";
}

echo "            </td>
          </tr>
          <tr>
            <td valign=\"top\"><strong>به کاربران اجازه تغییر قالب دهید (فک نکنم تو این نسخه بشه)</strong><br />
            <div style=\"font-weight:normal;\">
برای فعالسازی بله را بزنید            
</div>
            </td>
            <td valign=\"top\">
            ";
$allowchangelang = array("yes" => "بله", "no" => "خیر");
foreach ($allowchangelang as $k => $v) {
	echo "<input type=\"radio\" name=\"allowchangetemplate\" value=\"" . $k . "\" id=\"tpl" . $k . "\" ";

	if ($settings['allowchangetemplate'] == $k) {
		echo "checked=\"checked\"";
	}

	echo " /><label for=\"tpl" . $k . "\">" . $v . "</label>";
}

echo "            </td>
          </tr>
          <tr>
          	<td></td>
            <td>
            <input type=\"submit\" name=\"btn\" value=\"ذخیره\" />
            </td>
          </tr>
        </table>
    </form>
    </div>
    <div id=\"tab-2\">
          <table width=\"100%\" class=\"widget-tbl\">
            <tr class=\"titles\">
                <td>";
echo $paginator->linkorder("name", "نام");
echo "</td>
                <td align=\"center\">";
echo $paginator->linkorder("version", "ورژن");
echo "</td>
                <td align=\"center\">Uninstaller : پاک کننده نصب</td>
            </tr>
            ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "            <tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
                <td><span style=\"color:#000099\">";
	echo $r['name'];
	echo "</span>
                </td>
                <td align=\"center\">
                   ";
	echo $r['version'] == $software['version'] ? "<strong style=\"color:green\">پاس شده</strong>" : "<strong style=\"color:red\">مردود</strong>";
	echo "                </td>
                <td align=\"center\">";
	echo $r['id'] == 1 ? "نصب نمیتواند پاک شود" : "<input type=\"button\" name=\"btn\" onclick=\"location.href='./?view=template_settings&do=uninstall&i=" . $r['id'] . "';\" value=\"Uninstall\">";
	echo "	</td>
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


    </div>
</div>

</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>