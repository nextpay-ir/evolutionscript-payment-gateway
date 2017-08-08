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

$ticket_info = $db->fetchRow("SELECT * FROM helpdesk_ticket WHERE id=" . $input->gc['showt']);

if ($input->p['do'] == "update") {
	verifyajax();
	verifydemo();
	$newmsg = $input->pc['newmsg'];
	$msgid = $input->pc['msgid'];

	if (empty($newmsg)) {
		serveranswer(0, "پیام نمیتواند خالی باشد");
	}


	if ($msgid == 0) {
		$data = array("message" => $newmsg);
		$db->update("helpdesk_ticket", $data, "id=" . $ticket_info['id']);
	}
	else {
		$data = array("message" => $newmsg);
		$db->update("helpdesk_replies", $data, "id=" . $msgid);
	}

	serveranswer(6, "hideeditmsg(" . $msgid . "); $('#msgtxt-" . $msgid . "').html('" . addslashes($newmsg) . "');");
}
else {
	if ($input->p['do'] == "delete") {
		verifyajax();
		verifydemo();
		$msgid = $input->pc['msgid'];

		if ($msgid == 0) {
			$db->delete("helpdesk_ticket", "id=" . $ticket_info['id']);
			$db->delete("helpdesk_replies", "ticket_id=" . $ticket_info['id']);
			serveranswer(4, "location.href=\"./?view=support\";");
		}
		else {
			$db->delete("helpdesk_replies", "id=" . $msgid);
			serveranswer(6, "$(\"#conversation-" . $msgid . "\").remove();");
		}
	}
	else {
		if ($input->p['do'] == "reply") {
			if ($settings['demo'] == "yes") {
				$error_msg = "This is not possible in this demo version";
			}
			else {
				if (empty($input->pc['replymsg'])) {
					$error_msg = "یک پیام برای پاسخ بنویسید";
				}
				else {
					if ($input->p['sesion_id'] != $_SESSION['sesion_id']) {
						$error_msg = "Invalid token try again please";
					}
					else {
						$replymsg = $input->pc['replymsg'];
						$stored = array("ticket_id" => $ticket_info['id'], "user_reply" => 0, "message" => $replymsg, "date" => TIMENOW);
						$db->insert("helpdesk_replies", $stored);
						$tstatus = ($input->p['close_ticket'] ? 4 : 2);
						$db->query("UPDATE helpdesk_ticket SET last_update='" . TIMENOW . ("', status=" . $tstatus . " WHERE id=" . $ticket_info['id']));
						$str2find = array("%site_name%", "%site_url%", "%ticket_id%");
						$str2change = array($settings['site_name'], $settings['site_url'], $ticket_info['ticket']);
						$data_mail = array("mail_id" => "support_ticket_answer", "str2find" => $str2find, "str2change" => $str2change, "receiver" => $ticket_info['email']);
						$mail = new MailSystem($data_mail);
						$mail->send();
						$success_msg = 1;
					}
				}
			}
		}
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">Ticket #";
echo $ticket_info['ticket'];
echo "</div>
<div class=\"site_content\">
	<div class=\"widget-title\">اطلاعات تیکت</div>
    <div class=\"widget-content\">
    <table width=\"100%\" class=\"widget-tbl\">
      <tr>
        <td width=\"150\"><strong>آیدی تیکت</strong></td>
        <td style=\"color:#000099\">";
echo $ticket_info['ticket'];
echo "</td>
      </tr>
      ";

if ($ticket_info['user_id'] != 0) {
	echo "      <tr>
        <td  ><strong>نام کاربری</strong></td>
        <td  >
        ";
	$username = $db->fetchOne("SELECT username FROM members WHERE id=" . $ticket_info['user_id']);
	echo "<a href='./?view=members&edit=" . $ticket_info['user_id'] . "'><strong>" . $username . "</strong></a>";
	echo "        </td>
      </tr>
      ";
}
else {
	echo "      <tr>
        <td  ><strong>نام</strong></td>
        <td  >";
	echo $ticket_info['name'];
	echo "</td>
      </tr>
      <tr>
        <td  ><strong>ایمیل</strong></td>
        <td  >";
	echo $ticket_info['email'];
	echo "</td>
      </tr>
      ";
}

echo "      <tr>
        <td ><strong>تاریخ افزوده شدن</strong></td>
        <td >";
echo jdate('d F Y ساعت h:i:s A', $ticket_info['date']);
echo "</td>
      </tr>
      <tr>
        <td ><strong>وضعیت تیکت</strong></td>
        <td  >";
echo "<span style='color:" . $statuscolours[$ticket_info['status']] . ";'>" . $ticketstatus[$ticket_info['status']] . "</span>";
echo "</td>
      </tr>
      <tr>
        <td ><strong>موضوع تیکت</strong></td>
        <td  >";
echo $ticket_info['subject'];
echo "</td>
      </tr>
    </table>
    </div>
    <div class=\"widget-title\">مکالمات</div>
    <fieldset class=\"ticket-user\" id=\"conversation-0\">
    <legend>کاربر, ";
echo jdate('d F Y ساعت h:i:s A ', $ticket_info['date']);
echo "</legend>
        <form id=\"formsg-0\" method=\"post\" onsubmit=\"return submitform(this.id)\">
        <input type=\"hidden\" name=\"msgid\" value=\"0\">
        <input type=\"hidden\" name=\"do\" value=\"\" id=\"action0\" />
        <div  id=\"msg-0\">
        	<span id=\"msgtxt-0\">";
echo nl2br($ticket_info['message']);
echo "</span>
        	<div style=\"margin-top:5px\">
                <a href=\"javascript:void(0)\" onclick=\"showeditmsg(0)\">ویرایش</a> |
                <a href=\"javascript:void(0)\" onclick=\"updfrmvars({'action0': 'delete'}); submitform('formsg-0');\">حذف</a>
          	</div>
        </div>
        <div style=\"margin-top:5px; display:none\" id=\"edit-0\">
        	<textarea style=\"width:90%; height:60px\" id=\"msgtxt-0\" name=\"newmsg\">";
echo $ticket_info['message'];
echo "</textarea>
        	<div style=\"margin-top:5px\">
            <input type=\"submit\" value=\"Save\" onclick=\"updfrmvars({'action0': 'update'});\" />
            <input type=\"button\" class=\"cancel\" value=\"Cancel\" onclick=\"hideeditmsg(0)\">
            </div>
        </div>
        </form>
    </fieldset>
    ";
$q = $db->query("SELECT * FROM helpdesk_replies WHERE ticket_id=" . $ticket_info['id'] . " ORDER BY date ASC");

while ($r = $db->fetch_array($q)) {
	echo "    <fieldset class=\"";
	echo $r['user_reply'] == 0 ? "ticket-admin" : "ticket-user";
	echo "\" id=\"conversation-";
	echo $r['id'];
	echo "\">
    <legend>";
	echo $r['user_reply'] == 0 ? "شما" : "کاربر";
	echo ", ";
	echo jdate('d F Y ساعت h:i:s A', $r['date']);
	echo "</legend>
        <form id=\"formsg-";
	echo $r['id'];
	echo "\" method=\"post\" onsubmit=\"return submitform(this.id)\">
        <input type=\"hidden\" name=\"msgid\" value=\"";
	echo $r['id'];
	echo "\">
        <input type=\"hidden\" name=\"do\" value=\"\" id=\"action";
	echo $r['id'];
	echo "\" />
        <div  id=\"msg-";
	echo $r['id'];
	echo "\">
        	<span id=\"msgtxt-";
	echo $r['id'];
	echo "\">";
	echo nl2br($r['message']);
	echo "</span>
        	<div style=\"margin-top:5px\">
                <a href=\"javascript:void(0)\" onclick=\"showeditmsg(";
	echo $r['id'];
	echo ")\">ویرایش</a> |
                <a href=\"javascript:void(0)\" onclick=\"updfrmvars({'action";
	echo $r['id'];
	echo "': 'delete'}); submitform('formsg-";
	echo $r['id'];
	echo "');\">حذف</a>
          	</div>
        </div>
        <div style=\"margin-top:5px; display:none\" id=\"edit-";
	echo $r['id'];
	echo "\">
        	<textarea style=\"width:90%; height:60px\" id=\"msgtxt-";
	echo $r['id'];
	echo "\" name=\"newmsg\">";
	echo $r['message'];
	echo "</textarea>
        	<div style=\"margin-top:5px\">
            <input type=\"submit\" value=\"ذخیره\" onclick=\"updfrmvars({'action";
	echo $r['id'];
	echo "': 'update'});\" />
            <input type=\"button\" class=\"کنسل\" value=\"Cancel\" onclick=\"hideeditmsg(";
	echo $r['id'];
	echo ")\">
            </div>
        </div>
        </form>
    </fieldset>

    ";
}

echo "    		";

if ($error_msg) {
	echo "<div class=\"error_box\">" . $error_msg . "</div>";
}


if ($success_msg) {
	echo "<div class=\"success_box\">پیام افزوده شد</div>";
}

echo "	<form method=\"post\" name=\"reply\" id=\"reply\" action=\"./?view=support&showt=";
echo $ticket_info['id'];
echo "#reply\">
	<input type=\"hidden\" name=\"do\" value=\"reply\">
    <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
	<div align=\"center\" style=\"padding-top:5px\">
    <table width=\"100%\">
    	<tr>
        	<td colspan=\"2\"><textarea name=\"replymsg\" style=\"height:100px; width:90%\">";
echo $input->p['replymsg'] == "" ? "


" . $admin->getSignature() : $input->p['replymsg'];
echo "</textarea>
            </td>
        </tr>
        <tr>
        	<td align=\"right\"><input type=\"submit\" name=\"send\" value=\"پاسخ\"></td>
        	<td width=\"50%\"><label><input type=\"checkbox\" name=\"close_ticket\" />بستن تیکت بعد از پاسخ</label></td>

        </tr>
    </table>


    </div>
	</form>
</div>
";
include SOURCES . "footer.php";
echo " ";
?>