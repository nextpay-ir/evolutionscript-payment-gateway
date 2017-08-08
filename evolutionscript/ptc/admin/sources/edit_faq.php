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

$data = $db->fetchRow("SELECT * FROM faq WHERE id=" . $input->gc['edit']);

if ($input->p['do'] == "edit") {
	verifyajax();
	verifydemo();

	if (empty($input->p['question']) || empty($input->p['answer']) || empty($input->p['forder'])) {
		serveranswer(0, "پر کردن همه فیلدها ضروری است");
	}

	$set = array("forder" => $input->p['forder'], "question" => $input->pc['question'], "answer" => $input->p['answer']);
	$db->update("faq", $set, "id=" . $data['id']);
	$cache->delete("faq_data");
	serveranswer(3, "بروز رسانی موفق بود");
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
<div class=\"site_title\">ویرایش پرسشهای متداول</div>
<div class=\"site_content\">
<form method=\"post\" onsubmit=\"return save_form(this.id);\" id=\"faqform\">
<input type=\"hidden\" name=\"do\" value=\"edit\" />
<table width=\"100%\" class=\"widget-tbl\">
  <tr>
    <td width=\"100\" align=\"right\">سوال : </td>
    <td><input name=\"question\" type=\"text\" style=\"width:80%\" value=\"";
echo $data['question'];
echo "\" /></td>
    </tr>
  <tr>
    <td colspan=\"2\" align=\"center\">
    <textarea name=\"answer\" id=\"txthtml\">";
echo $data['answer'];
echo "</textarea>
    </td>
  </tr>
  <tr>
    <td align=\"right\">ترتیب</td>
    <td><input name=\"forder\" type=\"text\" value=\"";
echo $data['forder'];
echo "\" />  </td>
  </tr>
  <tr>
  <td></td>
    <td>
<input type=\"submit\" name=\"create\" value=\"ذخیره\" /> <input type=\"button\" name=\"btn\" value=\"بازگشت\" onclick=\"location.href='./?view=faq';\" />
    </td>
  </tr>
</table>
</form>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>