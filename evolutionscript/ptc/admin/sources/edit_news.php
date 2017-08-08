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

$data = $db->fetchRow("SELECT * FROM news WHERE id=" . $input->gc['edit']);

if ($input->p['do'] == "edit") {
	verifyajax();

	if ($input->p['title'] == "" || $input->p['news'] == "") {
		serveranswer(0, "پرکردن همه فیلدها ضروری است");
	}

	$loginads = ($input->p['loginads'] == 1 ? 1 : 0);
	$date = $input->p['date'];
	verifydemo();

	if ($loginads == 1) {
		$db->query("UPDATE news SET loginads=0");
	}


	if ($date == 1) {
		$set = array("title" => $input->pc['title'], "message" => $input->p['news'], "date" => TIMENOW, "loginads" => $loginads);
	}
	else {
		$set = array("title" => $input->pc['title'], "message" => $input->p['news'], "loginads" => $loginads);
	}

	$db->update("news", $set, "id=" . $data['id']);
	$cache->delete("news_list");
	serveranswer(3, "اخبار بروز شد.");
}

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

<div class=\"site_title\">ویرایش اخبار</div>
<div class=\"site_content\">
<form method=\"post\" onsubmit=\"return save_form(this.id);\" id=\"newsform\">
<input type=\"hidden\" name=\"do\" value=\"edit\" />
<table width=\"100%\" class=\"widget-tbl\">
  <tr>
    <td width=\"150\" align=\"right\">تیتر : </td>
    <td ><input name=\"title\" type=\"text\" style=\"width:80%\" value=\"";
echo $data['title'];
echo "\" /></td>
    </tr>
  <tr>
    <td colspan=\"2\" align=\"center\">
    <textarea name=\"news\" id=\"txthtml\">";
echo $data['message'];
echo "</textarea>
    </td>
  </tr>
  <tr>
    <td  align=\"right\">به تاریخ امروز به روز رسانی شود؟</td>
    <td  >
    	<select name=\"date\">
        	<option value=\"1\">بله</option>
            <option value=\"2\" selected=\"selected\">خیر</option>
       </select>
    </td>
  </tr>
  <tr>
    <td align=\"right\">افزودن این اخبار به تبلیغات ورود؟</td>
    <td ><input type=\"checkbox\" name=\"loginads\" value=\"1\" ";
echo $data['loginads'] == 1 ? "checked" : "";
echo " /></td>
    </tr>
  <tr>
  <tr>
  	<td></td>
    <td >
<input type=\"submit\" name=\"create\" value=\"ثبت\" />
<input type=\"button\" onclick=\"location.href='./?view=news';\" value=\"بازگشت\" />
    </td>
  </tr>
</table>
</form>
</div>

 ";
include SOURCES . "footer.php";
echo " ";
?>