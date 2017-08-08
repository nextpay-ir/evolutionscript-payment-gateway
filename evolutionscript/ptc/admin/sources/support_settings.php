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


if (!$admin->permissions['support']) {
	header("location: ./");
	exit();
}


if ($input->p['do'] == "save") {
	verifyajax();
	verifydemo();
	$helpdesk_enable = $input->pc['helpdesk_enable'];
	$members_only = $input->pc['members_only'];
	$upd = $db->query("UPDATE helpdesk_settings SET value='" . $helpdesk_enable . "' WHERE field='helpdesk_enable'");
	$upd = $db->query("UPDATE helpdesk_settings SET value='" . $members_only . "' WHERE field='members_only'");
	$cache->delete("members_support");
	serveranswer(1, "تنظیمات ذخیره شد");
}
else {
	if ($input->p['do'] == "create") {
		$department_name = $input->pc['department_name'];

		if ($settings['demo'] == "yes") {
			$error_msg = "This is not possible in this demo version";
		}
		else {
			if (empty($department_name)) {
				$error_msg = "Please enter a department name";
			}
			else {
				$stored = array("name" => $department_name);
				$db->insert("helpdesk_department", $stored);
				$success_msg = 1;
			}
		}
	}
	else {
		if ($input->p['do'] == "delete") {
			verifyajax();
			verifydemo();
			$department_id = $input->pc['depid'];
			$ticketdpts = $db->query("SELECT id FROM helpdesk_ticket WHERE department=" . $department_id);

			while ($row = $db->fetch_array($ticketdpts)) {
				$db->delete("helpdesk_replies", "ticket_id=" . $row['id']);
			}

			$db->delete("helpdesk_ticket", "department=" . $department_id);
			$db->delete("helpdesk_department", "id=" . $department_id);
			serveranswer(6, "$(\"#dep" . $department_id . "\").remove();");
		}
		else {
			if ($input->p['do'] == "update") {
				verifyajax();
				verifydemo();
				$department_id = $input->pc['depid'];
				$depname = $input->p['name'][$input->pc['depid']];
				$data = array("name" => $depname);
				$db->update("helpdesk_department", $data, "id=" . $department_id);
				serveranswer(1, "نام دپارتمان (بخش) بروز شد");
			}
		}
	}
}

$query = $db->query("SELECT * FROM helpdesk_settings");

while ($result = $db->fetch_array($query)) {
	$support[$result['field']] = $result['value'];
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">تنظیمات پشتیبانی</div>
<div class=\"site_content\">
<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tabs-1\">تنظیمات کلی</a></li>
        <li><a href=\"#tabs-2\">مدیریت دپارتمان (بخش) ها</a></li>
    </ul>
    <div id=\"tabs-1\">
        <form method=\"post\" id=\"supportsettings\" onsubmit=\"return submitform(this.id)\">
        <input type=\"hidden\" name=\"do\" value=\"save\" />
        <table class=\"widget-tbl\" width=\"100%\">
          <tr>
            <td>فعالسازی مرکز پشتیبانی</td>
            <td valign=\"top\"><input type=\"checkbox\" name=\"helpdesk_enable\" value=\"yes\" ";

if ($support['helpdesk_enable'] == "yes") {
	echo "checked";
}

echo " />
        برای فعالسازی تیک بزنید</td>
          </tr>
          <tr>
            <td>فقط اعضا</td>
            <td valign=\"top\"><input type=\"checkbox\" name=\"members_only\" value=\"yes\" ";

if ($support['members_only'] == "yes") {
	echo "checked";
}

echo " />
        برای فعالسازی تیک بزنید . افراد غیر عضو قادر به ارتباط با مرکز پشتیبانی نخواهند بود</td>
          </tr>
          <tr>
          	<td></td>
            <td>
            <input type=\"submit\" name=\"support_settings\" value=\"ذخیره\" />
            </td>
          </tr>
        </table>
        </form>
    </div>
    <div id=\"tabs-2\">
    	<div class=\"widget-title\">افزودن دپارتمان</div>
        <div class=\"widget-content\">
    		";

if ($error_msg) {
	echo "<div class=\"error_box\">" . $error_msg . "</div>";
}


if ($success_msg) {
	echo "<div class=\"success_box\">دپارتمان افزوده شد</div>";
}

echo "        <form method=\"post\" action=\"./?view=support_settings#tabs-2\">
        <input type=\"hidden\" name=\"do\" value=\"create\" />
        <table class=\"widget-tbl\" width=\"100%\">
          <tr>
            <td width=\"150\">افزودن یک دپارتمان جدید:</td>
            <td valign=\"top\" width=\"150\"><input name=\"department_name\" id=\"department_name\" type=\"text\" /></td>
            <td>
                <input type=\"submit\" name=\"send\" value=\"ایجاد\" class=\"orange\" />
            </td>
          </tr>
        </table>
        </form>
        </div>

        <div class=\"widget-title\">مدیریت دپارتمان ها</div>
        <form method=\"post\" id=\"frm3\" onsubmit=\"return submitform(this.id);\">
        <input type=\"hidden\" name=\"depid\" id=\"depid\" value=\"0\" />
        <input type=\"hidden\" name=\"do\" id=\"depaction\" value=\"\" />
        <table width=\"100%\" class=\"widget-tbl\">
            <tr class=\"titles\">
                <td>نام دپارتمان</td>
                <td>تیکت های باز</td>
                <td>تیکت های جواب داده شده</td>
                <td>تیکت های منتظر پاسخ</td>
                <td>تیکت های بسته</td>
                <td></td>
            </tr>
            ";
$query = $db->query("SELECT * FROM helpdesk_department ORDER BY id ASC");

while ($r = $db->fetch_array($query)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "            <tr class=\"";
	echo $tr;
	echo "\" id=\"dep";
	echo $r['id'];
	echo "\">
                <td><input type=\"text\" name=\"name[";
	echo $r['id'];
	echo "]\" value=\"";
	echo $r['name'];
	echo "\" /></td>
                <td>";
	echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM helpdesk_ticket WHERE status='1' AND department=" . $r['id']);
	echo "</td>
                <td>";
	echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM helpdesk_ticket WHERE status='2' AND department=" . $r['id']);
	echo "</td>
                <td>";
	echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM helpdesk_ticket WHERE status='3' AND department=" . $r['id']);
	echo "</td>
                <td>";
	echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM helpdesk_ticket WHERE status='4' AND department=" . $r['id']);
	echo "</td>
                <td><input type=\"submit\" name=\"btn\" value=\"ذخیره\" onclick=\"updfrmvars({'depid': '";
	echo $r['id'];
	echo "', 'depaction': 'update'});\" /> <input type=\"submit\" name=\"btn\" value=\"حذف\" class=\"cancel\"onclick=\"updfrmvars({'depid': '";
	echo $r['id'];
	echo "', 'depaction': 'delete'});\"  /></td>
            </tr>
            ";
}

echo "        </table>
        </form>


    </div>
</div>
</div>

        ";
include SOURCES . "footer.php";
echo " ";
?>