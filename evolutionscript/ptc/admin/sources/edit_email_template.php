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


if ($input->p['do'] == "update_mail") {
	verifyajax();
	verifydemo();

	if ($input->p['type'] != "plain" & $input->p['type'] != "html") {
		serveranswer(0, "Select a valid Email Format");
	}
	else {
		if (empty($input->p['subject'])) {
			serveranswer(0, "Enter a subject");
		}
		else {
			if ($input->p['type'] == "plain" && empty($input->p['message_plain'])) {
				serveranswer(0, "Enter your Plain Message");
			}
			else {
				if ($input->p['type'] == "html" && empty($input->p['message_html'])) {
					serveranswer(0, "Enter your HTML Message");
				}
			}
		}
	}


	if ($input->p['type'] == "plain") {
		$message_id = "message_plain";
		$message = str_replace("\r\n", "\n", $input->p['message_plain']);

	}
	else {
		$message_id = "message_html";
		$message = $input->p['message_html'];
	}

	$data = array("type" => $input->p['type'], "subject" => $input->p['subject'], $message_id => $message);
	$db->update("email_template", $data, ("id='" . $template_id . "'"));
	$cache->delete("email_template");
	serveranswer(1, "Email template has been updated.");
}

$tpl = $db->fetchRow("SELECT * from email_template WHERE id='" . $template_id . "'");
include SOURCES . "header.php";
echo "<script type=\"text/javascript\">
$(function(){
	check_mailformat();
});
function check_mailformat(){
	var datatype = $(\"#emailtype\").val();
	if(datatype == 'plain'){
		$(\"#plain_format\").show();
		$(\"#html_format\").hide();
	}else{
		$(\"#html_format\").show();
		$(\"#plain_format\").hide();
	}
}
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

<div class=\"site_title\">Email Templates / ";
echo $tpl['name'];
echo "</div>
<div class=\"site_content\">
<form method=\"post\" id=\"frmmaileditor\" onsubmit=\"return save_form(this.id);\">
<input type=\"hidden\" name=\"do\" value=\"update_mail\" />
<table class=\"widget-tbl\" width=\"100%\">
	<tr>
	    <td align=\"right\" width=\"300\"><strong>Email Format:</strong></td>
        <td><select name=\"type\" id=\"emailtype\" onchange=\"check_mailformat();\"><option value=\"plain\" ";
echo $tpl['type'] == "plain" ? "selected" : "";
echo ">Plain Format</option><option value=\"html\"  ";
echo $tpl['type'] == "html" ? "selected" : "";
echo ">HTML Format</option></select></td>
	</tr>
    <tr>
    	<td align=\"right\"><strong>Subject:</strong></td>
        <td><input type=\"text\" name=\"subject\" value=\"";
echo $tpl['subject'];
echo "\" /></td>
    </tr>
    <tr id=\"plain_format\" style=\"display:none\">
    	<td align=\"right\"><strong>Plain message:</strong></td>
        <td><textarea name=\"message_plain\" style=\"width:90%; height:200px\">";
echo $tpl['message_plain'];
echo "</textarea></td>
    </tr>
    <tr id=\"html_format\" style=\"display:none\">
    	<td align=\"right\"><strong>HTML message:</strong></td>
        <td><textarea id=\"txthtml\" name=\"message_html\">";
echo $tpl['message_html'];
echo "</textarea></td>
    </tr>
    <tr>
    	<td></td>
        <td><input type=\"submit\" name=\"btn\" value=\"Save\" /> <input type=\"button\" name=\"btn\" value=\"Cancel\" onclick=\"history.back();\" /></td>
    </tr>
</table>
</form>
</div>
";
include SOURCES . "footer.php";
echo " ";
?>