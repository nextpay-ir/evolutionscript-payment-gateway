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


if (!$admin->permissions['site_content']) {
	header("location: ./");
	exit();
}


if (is_numeric($input->g['edit'])) {
	$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM news WHERE id=" . $input->gc['edit']);

	if ($chk != 0) {
		include SOURCES . "edit_news.php";
		exit();
	}
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
							$upd = $db->delete("news", "id=" . $mid);
					}
				}
			}

			$cache->delete("news_list");
		}
	}
}


if ($input->p['do'] == "create") {
	verifyajax();
	verifydemo();

	if ($input->p['title'] == "" || $input->p['news'] == "") {
		serveranswer(0, "پرکردن همه فیلدها ضروری است");
	}

	$loginads = ($input->p['loginads'] == 1 ? 1 : 0);

	if ($loginads == 1) {
		$db->query("UPDATE news SET loginads=0");
	}

	$set = array("title" => $input->pc['title'], "message" => $input->p['news'], "loginads" => $loginads, "date" => TIMENOW);
	$db->insert("news", $set);
	$cache->delete("news_list");
	serveranswer(4, "location.href=\"./?view=news\";");
}
else {
	if ($input->p['do'] == "save_settings") {
		verifyajax();
		verifydemo();
		$vars = array("show_news", "quick_news");
		foreach ($vars as $k) {
			$db->query("UPDATE settings SET value='" . $input->pc[$k] . "' WHERE field='" . $k . "'");
		}

		$cache->delete("settings");
		serveranswer(1, "تنظیمات بروز شد");
	}
}

$paginator = new Pagination("news", $cond);
$paginator->setOrders("date", "DESC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=news&" . $adlink);
$q = $paginator->getQuery();
include SOURCES . "header.php";
echo "<script type=\"text/javascript\">
function save_form(id){
	tinyMCE.get('txthtml').save();
	submitform(id);
	return false;
}
</script>
<script type=\"text/javascript\" src=\"./js/tinymce/tinymce.min.js\"></script>
<script type=\"text/javascript\">
tinymce.init({
    selector: \"textarea#txthtml\",
	theme: \"modern\",
	height: 200,
    plugins: [
         \"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker\",
         \"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking\",
         \"save table contextmenu directionality emoticons template paste textcolor\"
   ],
	toolbar: \"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | preview media fullpage | forecolor backcolor emoticons\",
 });
</script>
<div class=\"site_title\">اخبار</div>
<div class=\"site_content\">
<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tabs-1\">مدیریت اخبار</a></li>
        <li><a href=\"#tabs-2\">خبر جدید</a></li>
        <li><a href=\"#tabs-3\">تنظیمات</a></li>
    </ul>
    <div id=\"tabs-1\">
	";

if ($error_msg) {
	echo "    <div class=\"error_box\">";
	echo $error_msg;
	echo "</div>
    ";
}

echo "    <form method=\"post\" action=\"";
echo $paginator->gotopage();
echo "#tabs-1\">
      <table width=\"100%\" class=\"widget-tbl\">
        <tr class=\"titles\">
            <td width=\"20\"><input type=\"checkbox\" id=\"checkall\"></td>
            <td>";
echo $paginator->linkorder("jdate", "تاریخ ایجاد");
echo "</td>
            <td>";
echo $paginator->linkorder("title", "عنوان");
echo "</td>
            <td align=\"center\"></td>
        </tr>
        ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "        <tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
            <td align=\"center\"><input type=\"checkbox\" name=\"mid[]\" value=\"";
	echo $r['id'];
	echo "\" class=\"checkall\" /></td>
            <td>";
	echo jdate("d F Y ساعت h:i A", $r['date']);
	echo "</td>
            <td><a href=\"./?view=news&edit=";
	echo $r['id'];
	echo "\">";
	echo $r['title'];
	echo "</a></td>
            <td align=\"center\"><a href=\"./?view=news&edit=";
	echo $r['id'];
	echo "\"><img src=\"./css/images/edit.png\" border=\"0\" title=\"ویرایش\" /></a>
            </td>
                </tr>
        ";
}


if ($paginator->totalResults() == 0) {
	echo "        <tr>
            <td colspan=\"10\" align=\"center\">موردی یافت نشد</td>
        </tr>
        ";
}

echo "      </table>
        <div style=\"margin-top:10px\">
        <input type=\"button\" value=\"&larr; صفحه قبل\" ";
echo ($paginator->totalPages() == 1 || $paginator->getPage() == 1) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->prevpage() . "';\";";
echo " />

        <input type=\"button\" value=\"صفحه بعد &rarr;\" ";
echo ($paginator->totalPages() == 0 || $paginator->totalPages() == $paginator->getPage()) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->nextpage() . "';\";";
echo " />
            ";

if (1 < $paginator->totalPages()) {
	echo "            <div style=\"float:right\">
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

	echo "            </select>
            <script type=\"text/javascript\">
                function gotopage(pageid){
                    location.href=pageid;
                }
            </script>
            </div>
            <div class=\"clear\"></div>
            ";
}

echo "        </div>

        ";

if (0 < $paginator->totalPages()) {
	echo "        <div class=\"widget-title\" style=\"margin-top:5px\">اقدام</div>
            <div class=\"widget-content\">
                <select name=\"a\">
                    <option value=\"\">یک مورد انتخاب کنید</option>
                    <option value=\"delete\">حذف</option>
                </select>
                <input type=\"submit\" name=\"action\" value=\"ایجاد\" />
            </div>
        ";
}

echo "        <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
    </form>
    </div>

    <div id=\"tabs-2\">
<form method=\"post\" onsubmit=\"return save_form(this.id);\" id=\"newsform\">
<input type=\"hidden\" name=\"do\" value=\"create\" />
<table width=\"100%\" class=\"widget-tbl\">
  <tr>
    <td width=\"150\" align=\"right\">عنوان</td>
    <td ><input name=\"title\" type=\"text\" style=\"width:80%\" /></td>
    </tr>
  <tr>
    <td colspan=\"2\" align=\"center\">
    <textarea name=\"news\" id=\"txthtml\"></textarea>
    </td>
  </tr>
  <tr>
    <td align=\"right\">افزودن این خبر به تبلیغ ورود</td>
    <td ><input type=\"checkbox\" name=\"loginads\" value=\"1\" /></td>
    </tr>
  <tr>
  <tr>
  	<td></td>
    <td >
<input type=\"submit\" name=\"create\" value=\"ایجاد\" />
    </td>
  </tr>
</table>
</form>
    </div>
    <div id=\"tabs-3\">
    <form method=\"post\" onsubmit=\"return submitform(this.id);\" id=\"newsettfrm\">
    <input type=\"hidden\" name=\"do\" value=\"save_settings\" />
    <table width=\"100%\" class=\"widget-tbl\">
        <tr>
            <td align=\"right\" width=\"300\">تعداد آخرین اخبار برای نمایش</td>
            <td><input name=\"show_news\" type=\"text\" value=\"";
echo $settings['show_news'];
echo "\" /></td>
        </tr>
        <tr>
            <td align=\"right\" width=\"300\">تعداد اخبار فوری برای نمایش</td>
            <td><input name=\"quick_news\" type=\"text\" value=\"";
echo $settings['quick_news'];
echo "\" /></td>
        </tr>
          <tr>
          	<td></td>
            <td><input type=\"submit\" name=\"btn\" value=\"ذخیره\" /></td>
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