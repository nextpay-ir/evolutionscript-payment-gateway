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


if (!$admin->permissions['send_mail']) {
	header("location: ./");
	exit();
}


if ($input->p['a'] == "sendmail") {
	verifyajax();
	verifydemo();
	$required_fields = array("subject", "message", "receiverlist", "name");
	foreach ($required_fields as $k) {

		if (!$input->p[$k]) {
			serveranswer(0, "پرکردن همه فیلدها اجباری است");
			continue;
		}
	}


	if ($input->p['receiverlist'] == "single") {
		$userid = getuserid($input->pc['username']);

		if ($userid == 0) {
			$serveranswer(0, "نام کاربری <strong>" . $input->pc['username'] . "</strong> وجود ندارد");
		}

		$userdetails = $db->fetchRow("SELECT fullname, username, money, pending_withdraw, email, purchase_balance FROM members WHERE id=" . $userid);
		$oldphrase = array("%fullname%", "%username%", "%balance%", "%pending_withdrawal%", "%purchase_balance%");
		$newphrase = array($userdetails['fullname'], $userdetails['username'], $userdetails['money'], $userdetails['pending_withdraw'], $userdetails['purchase_balance']);
		$message_text = str_replace($oldphrase, $newphrase, $input->p['message']);
		$message = $nextpage;
		$mail = new mail();
		$mail->setFrom($settings['email_support'], $input->pc['name']);
		$mail->addTo($userdetails['email']);
		$mail->setSubject($input->pc['subject']);
		$mail->setBodyText($message);
		$mail->send();
		serveranswer(2, "ایمیل به کاربر <strong>" . $input->pc['username'] . "</strong> ارسال شد");
	}
	else {
		if (empty($input->p['page']) || !is_numeric($input->p['page'])) {
			$page = 1;
		}
		else {
			$page = $input->pc['page'];
		}


		if (empty($input->p['seconds'])) {
			$seconds = 10000;
		}
		else {
			$seconds = $input->pc['seconds'];
		}


		if (!is_numeric($input->p['massamount'])) {
			$max_display = 5;
		}
		else {
			$max_display = $input->p['massamount'];
		}

		$from = $max_display * $page - $max_display;
		$countmember = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE status='Active' AND acceptmails='yes' AND username!='BOT'");
		$usersquery = $db->query("SELECT username, fullname, email, money, pending_withdraw, purchase_balance FROM members WHERE status='Active' and acceptmails='yes' AND username!='BOT' ORDER BY id ASC LIMIT " . $from . ", " . $max_display);
		$total_pages = ($countmember == 0 ? 0 : ceil($countmember / $max_display));

		while ($userdetails = $db->fetch_array($usersquery)) {
			$oldphrase = array("%fullname%", "%username%", "%balance%", "%pending_withdrawal%", "%purchase_balance%");
			$newphrase = array($userdetails['fullname'], $userdetails['username'], $userdetails['money'], $userdetails['pending_withdraw'], $userdetails['purchase_balance']);
			$newmessage_text = str_replace($oldphrase, $newphrase, $input->p['message']);
			$message = $newphrase;
			$mail = new mail();
			$mail->setFrom($settings['email_support'], $input->pc['name']);
			$mail->addTo($userdetails['email']);
			$mail->setSubject($input->pc['subject']);
			$mail->setBodyText($message);
			$mail->send();
			$textsent .= "<i>Mail sent to " . $userdetails['username'] . " (" . $userdetails['email'] . ").</i><br>";
		}

		$textsent .= "<br><strong>لطفا صبور باشید</strong>";

		if ($page == $total_pages) {
			serveranswer(2, "ایمیل به تمام کاربران فرستاده شد");
		}
		else {
			$nextpage = $page + 1;
			serveranswer(4, ("$(\"#sendmessage\").l2success(\"" . $textsent . "\"); $(\"#pagenum\").val(" . $nextpage . "); setTimeout('$(\"#sendmessage\").l2unblock(); submitform(\"sendmessage\");', " . $seconds . ")"));
		}
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">Send Mail</div>
<div class=\"site_content\">

<form method=\"post\"  onsubmit=\"$('#pagenum').val(1); return submitform(this.id);\" id=\"sendmessage\">
<input type=\"hidden\" name=\"a\" value=\"sendmail\" />
<input type=\"hidden\" name=\"page\" id=\"pagenum\" value=\"1\" />
<table cellpadding=\"4\" width=\"100%\" class=\"widget-tbl\">
  <tr>
    <td width=\"150\" align=\"right\">ارسال از :</td>
    <td>
    <input name=\"name\" type=\"text\" id=\"name\" value=\"";
echo $settings['site_name'];
echo " Admin\" /> (";
echo $settings['email_support'];
echo ") [<a href=\"?view=general\">ویرایش</a>]
      </td>
    </tr>
  <tr>
    <td align=\"right\">لیست گیرندگاهن</td>
    <td>
    <select name=\"receiverlist\" id=\"receiverlist\" onchange=\"receivertype();\">
    	<option value=\"\"></option>
        <option value=\"all\">همه اعضا</option>
        <option value=\"single\" ";
echo $input->g['member'] ? "selected" : "";
echo ">تک کاربره</option>
    </select>
    </td>
  </tr>
  <tbody id=\"singlemember\" ";
echo !$input->g['member'] ? "style=\"display:none;\"" : "";
echo ">
  <tr>
    <td width=\"150\" align=\"right\">نام کاربری</td>
    <td>
    <input name=\"username\" type=\"text\" id=\"username\" value=\"";
echo $_GET['member'];
echo "\" />
      </td>
  </tr>
  </tbody>
  <tr>
    <td align=\"right\">موضع</td>
    <td><input name=\"subject\" type=\"text\" /></td>
  </tr>
  <tr>
    <td align=\"right\">متن ایمیل : </td>
    <td>
";
$topmsg = "*****************************************************************
";
$topmsg .= "This message was sent from " . $settings['site_name'] . " Administrator
";
$topmsg .= ("URL: " . $settings['site_url'] . "
");
$topmsg .= "*****************************************************************

";
$topmsg .= "پیام شما اینجا ....";
echo "    <textarea name=\"message\" id=\"message\" style=\"width:90%; height:150px\">";
echo $input->r['message'] == "" ? $topmsg : $input->r['message'];
echo "</textarea></td>
  </tr>
  <tbody id=\"maxperpage\"  ";
echo $input->g['member'] ? "style=\"display:none;\"" : "";
echo ">
  <tr>
  	<td align=\"right\">بیشتر پیام در هر صفحه</td>
	<td>
        <select name=\"massamount\">
        	<option value=\"5\">5 ایمیل</option>
            <option value=\"10\">10  ایمیل</option>
            <option value=\"25\">25  ایمیل</option>
            <option value=\"50\">50  ایمیل</option>
            <option value=\"100\">100  ایمیل</option>
        </select>
        در هر
        <select name=\"seconds\">
        	<option value=\"10000\">10 ثانیه</option>
            <option value=\"30000\">30 ثانیه</option>
            <option value=\"60000\">1 دقیقه</option>
            <option value=\"120000\">2 دقیقه</option>
            <option value=\"300000\">5 دقیقه</option>
            <option value=\"1800000\">30 دقیقه</option>
            <option value=\"3600000\">1 ساعت</option>
        </select>
        </td>
  </tr>
  </tbody>
  <tr>
    <td align=\"right\">تگ های قابل استفاده در متن ایمیل</td>
    <td><table width=\"100%\">
      <tr>
        <td width=\"120\">نام کامل</td>
        <td>%fullname%</td>
      </tr>
      <tr>
        <td>نام کاربری</td>
        <td>%username%</td>
      </tr>
      <tr>
        <td>درآمد</td>
        <td>%balance%</td>
      </tr>
      <tr>
        <td>تسویه حساب منتظر</td>
        <td>%pending_withdrawal%</td>
      </tr>
      <tr>
        <td>موجودی شارژ حساب</td>
        <td>%purchase_balance%</td>
      </tr>
    </table>
      </td>
    </tr>
    <tr>
    	<td></td>
        <td>
 			<input type=\"submit\" name=\"create\" value=\"ارسال\" class=\"orange\" />
        </td>
    </tr>
</table>
</form>

</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>