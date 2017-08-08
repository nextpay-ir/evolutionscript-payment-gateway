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


if (!$admin->permissions['send_messages']) {
	header("location: ./");
	exit();
}


if ($input->p['a'] == "sendmessage") {
	verifyajax();
	verifydemo();
	$required_fields = array("subject", "message", "receiverlist");
	foreach ($required_fields as $k) {

		if (!$input->p[$k]) {
			serveranswer(0, "پرکردن همه فیلدها ضروری است");
			continue;
		}
	}


	if ($input->p['receiverlist'] == "single") {
		$userid = getuserid($input->pc['username']);

		if ($userid == 0) {
			serveranswer(0, "نام کاربری <strong>" . $username . "</strong> وجود ندارد");
		}

		$userdetails = $db->fetchRow("SELECT id, fullname, username, money, purchase_balance, pending_withdraw, email FROM members WHERE id=" . $userid);
		$oldphrase = array("%fullname%", "%username%", "%balance%", "%purchase_balance%", "%pending_withdrawal%");
		$newphrase = array($userdetails['fullname'], $userdetails['username'], $userdetails['money'], $userdetails['purchase_balance'], $userdetails['pending_withdraw']);
		$message = str_replace($oldphrase, $newphrase, $input->pc['message']);
		$data = array("user_from" => 0, "user_to" => $userdetails['id'], "subject" => $input->pc['subject'], "message" => $message, "date" => TIMENOW);
		$insert = $db->insert("messages", $data);
		serveranswer(2, "پیام به کاربر <strong>" . $input->pc['username'] . "</strong> ارسال شد");
	}
	else {
		if (!$input->p['page'] || !is_numeric($input->p['page'])) {
			$page = 1;
		}
		else {
			$page = $input->pc['page'];
		}


		if (!is_numeric($input->p['massamount'])) {
			$max_display = 5;
		}
		else {
			$max_display = $input->p['massamount'];
		}

		$from = $max_display * $page - $max_display;
		$countmember = $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE status='Active' AND username!='BOT'");
		$usersquery = $db->query("SELECT id, fullname, username, money, purchase_balance, pending_withdraw, email FROM members WHERE status='Active' AND username!='BOT' ORDER BY id ASC LIMIT " . $from . ", " . $max_display);
		$total_pages = ceil($countmember / $max_display);

		while ($userdetails = $db->fetch_array($usersquery)) {
			$oldphrase = array("%fullname%", "%username%", "%balance%", "%pending_withdrawal%", "%purchase_balance%");
			$newphrase = array($userdetails['fullname'], $userdetails['username'], $userdetails['money'], $userdetails['pending_withdraw'], $userdetails['purchase_balance']);
			$message = str_replace($oldphrase, $newphrase, $input->pc['message']);
			$data = array("user_from" => 0, "user_to" => $userdetails['id'], "subject" => $input->pc['subject'], "message" => $message, "date" => TIMENOW);
			$insert = $db->insert("messages", $data);
			$textsent .= "<i>Message sent to " . $userdetails['username'] . ".</i><br>";
		}

		$textsent .= "<br><strong>Please wait...</strong>";

		if ($page == $total_pages) {
			serveranswer(2, "پیام برای همه کاربران فرستاده شد");
		}
		else {
			$nextpage = $page + 1;
			serveranswer(4, "$(\"#sendmessage\").l2success(\"" . $textsent . "\"); $(\"#pagenum\").val(" . $nextpage . "); setTimeout('$(\"#sendmessage\").l2unblock(); submitform(\"sendmessage\");', 5000)");
		}
	}
}

include SOURCES . "header.php";
echo "<div class=\"site_title\">ارسال پیام به کاربران سایت از طریق سیستم پیام سایت</div>
<div class=\"site_content\">
     <form method=\"post\" onsubmit=\"$('#pagenum').val(1); return submitform(this.id);\" id=\"sendmessage\">
    <input type=\"hidden\" name=\"a\" value=\"sendmessage\" />
    <input type=\"hidden\" name=\"page\" id=\"pagenum\" value=\"1\" />
    <table cellpadding=\"4\" width=\"100%\" class=\"widget-tbl\">
  <tr>
    <td width=\"150\">لیست گیرندگان</td>
    <td>
    <select name=\"receiverlist\" id=\"receiverlist\" onchange=\"receivertype();\">
    	<option value=\"\"></option>
        <option value=\"all\">تمام اعضا</option>
        <option value=\"single\" ";

if ($input->g['member']) {
	echo "selected";
}

echo ">تک کاربره</option>
    </select>
    </td>
  </tr>
  <tbody id=\"singlemember\" ";

if (!$input->g['member']) {
	echo "style=\"display:none\"";
}

echo ">
  <tr>
    <td width=\"150\">نام کاربری</td>
    <td>
    <input name=\"username\" type=\"text\" id=\"username\" value=\"";
echo $input->gc['member'];
echo "\" />
      </td>
  </tr>
  </tbody>
      <tr>
        <td>موضوع</td>
        <td><input name=\"subject\" type=\"text\" /></td>
      </tr>
      <tr>
        <td>متن پیام</td>
        <td><textarea name=\"message\" id=\"message\" class=\"messagearea\" style=\"width:90%; height:150px\"></textarea></td>
      </tr>
       <tbody id=\"maxperpage\" ";

if ($input->gc['member']) {
	echo "style=\"display:none\"";
}

echo ">
      <tr>
        <td>بیشترین پیام در صفحه</td>
            <td>
            <select name=\"massamount\">
                <option value=\"5\">5</option>
                <option value=\"10\">10</option>
                <option value=\"25\">25</option>
                <option value=\"50\">50</option>
                <option value=\"100\">100</option>
                <option value=\"500\">500</option>
            </select>        </td>
      </tr>
      </tbody>
      <tr>
        <td>تگ های قابل استفاده در متن پیام</td>
        <td><table width=\"100%\">
          <tr>
            <td width=\"120\">نام کامل : </td>
            <td>%fullname%</td>
          </tr>
          <tr>
            <td>نام کاربری : </td>
            <td>%username%</td>
          </tr>
          <tr>
            <td>در آمد : </td>
            <td>%balance%</td>
          </tr>
          <tr>
            <td>تسویه حساب منتظر </td>
            <td>%pending_withdrawal%</td>
          </tr>
          <tr>
          	<td>موجودی شارژ حساب </td>
            <td>%purchase_balance%</td>
          </tr>
        </table>      </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type=\"submit\" name=\"send\" value=\"ارسال\" />        </td>
        </tr>
    </table>
    </form>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>