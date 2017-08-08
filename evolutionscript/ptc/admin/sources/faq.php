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
	$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM faq WHERE id=" . $input->gc['edit']);

	if ($chk != 0) {
		include SOURCES . "edit_faq.php";
		exit();
	}
}


if ($input->p['do'] == "create") {
	verifyajax();
	verifydemo();

	if (empty($input->p['question']) || empty($input->p['answer']) || empty($input->p['forder'])) {
		serveranswer(0, "پرکردن همه فیلدها ضروری است");
	}

	$data = array("forder" => $input->p['forder'], "question" => $input->pc['question'], "answer" => $input->p['answer']);
	$db->insert("faq", $data);
	$cache->delete("faq_data");
	serveranswer(4, "location.href=\"./?view=faq\";");
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
							$upd = $db->delete("faq", "id=" . $mid);
					}
				}
			}

			$cache->delete("faq_data");
		}
	}
}

$paginator = new Pagination("faq", $cond);
$paginator->setOrders("forder", "ASC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=faq&" . $adlink);
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
<div class=\"site_title\">پرسشهای متداول</div>
<div class=\"site_content\">
<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tabs-1\">مدیریت پرسش ها</a></li>
        <li><a href=\"#tabs-2\">ایجاد پرسش جدید</a></li>
    </ul>
    <div id=\"tabs-1\">
		";

if ($error_msg) {
	echo "        <div class=\"error_box\">";
	echo $error_msg;
	echo "</div>
        ";
}

echo "        <form method=\"post\" action=\"";
echo $paginator->gotopage();
echo "#tabs-1\">
          <table width=\"100%\" class=\"widget-tbl\">
            <tr class=\"titles\">
                <td width=\"20\"><input type=\"checkbox\" id=\"checkall\"></td>
                <td width=\"50\">";
echo $paginator->linkorder("forder", "ترتیب");
echo "</td>
                <td>";
echo $paginator->linkorder("question", "سوال");
echo "</td>
                <td align=\"center\"></td>
            </tr>
            ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "            <tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
                <td align=\"center\"><input type=\"checkbox\" name=\"mid[]\" value=\"";
	echo $r['id'];
	echo "\" class=\"checkall\" /></td>
                <td>";
	echo $r['forder'];
	echo "</td>
                <td>";
	echo $r['question'];
	echo "</td>
                <td align=\"center\"><a href=\"./?view=faq&edit=";
	echo $r['id'];
	echo "\"><img src=\"./css/images/edit.png\" border=\"0\" title=\"Edit FAQ\" /></a>
                </td>
                    </tr>
            ";
}


if ($paginator->totalResults() == 0) {
	echo "            <tr>
                <td colspan=\"10\" align=\"center\">موردی یافت نشد</td>
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

            ";

if (0 < $paginator->totalPages()) {
	echo "            <div class=\"widget-title\" style=\"margin-top:5px\">اقدام</div>
                <div class=\"widget-content\">
                    <select name=\"a\">
                        <option value=\"\">یکی را انتخاب کنید</option>
                        <option value=\"delete\">حذف</option>
                    </select>
                    <input type=\"submit\" name=\"action\" value=\"ثبت\" />
                </div>
            ";
}

echo "            <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
        </form>
    </div>
    <div id=\"tabs-2\">
        <form method=\"post\" onsubmit=\"return save_form(this.id);\" id=\"faqform\" name=\"formwysiwyg\">
        <input type=\"hidden\" name=\"do\" value=\"create\" />
        <table width=\"100%\" class=\"widget-tbl\">
          <tr>
            <td width=\"100\" align=\"right\">سوال : </td>
            <td><input name=\"question\" type=\"text\" style=\"width:80%\" /></td>
            </tr>
          <tr>
            <td colspan=\"2\" align=\"center\">
            <textarea name=\"answer\" id=\"txthtml\"></textarea>
            </td>
          </tr>
          <tr>
            <td align=\"right\">ترتیب</td>
            <td><input name=\"forder\" type=\"text\" value=\"";
echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM faq") + 1;
echo "\" />  </td>
          </tr>
          <tr>
          <td></td>
            <td>
        <input type=\"submit\" name=\"create\" value=\"ثبت\" />
            </td>
          </tr>
        </table>
        </form>

    </div>
</div>
       <script type=\"text/javascript\" src=\"./js/jquery.wysiwyg.js\"></script>
        <script type=\"text/javascript\" src=\"./js/jquery.simplemodal.js\"></script>
    <script type=\"text/javascript\">
(function($)
{
  $('#wysiwyg').wysiwyg({
    controls: {
      strikeThrough : { visible : true },
      underline     : { visible : true },

      justifyLeft   : { visible : true },
      justifyCenter : { visible : true },
      justifyRight  : { visible : true },
      justifyFull   : { visible : true },

      indent  : { visible : true },
      outdent : { visible : true },

      subscript   : { visible : true },
      superscript : { visible : true },

      undo : { visible : true },
      redo : { visible : true },

      insertOrderedList    : { visible : true },
      insertUnorderedList  : { visible : true },
      insertHorizontalRule : { visible : true },

      h4: {
              visible: true,
              className: 'h4',
              command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
              arguments: ($.browser.msie || $.browser.safari) ? '<h4>' : 'h4',
              tags: ['h4'],
              tooltip: 'Header 4'
      },
      h5: {
              visible: true,
              className: 'h5',
              command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
              arguments: ($.browser.msie || $.browser.safari) ? '<h5>' : 'h5',
              tags: ['h5'],
              tooltip: 'Header 5'
      },
      h6: {
              visible: true,
              className: 'h6',
              command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
              arguments: ($.browser.msie || $.browser.safari) ? '<h6>' : 'h6',
              tags: ['h6'],
              tooltip: 'Header 6'
      },

      cut   : { visible : false },
      copy  : { visible : false },
      paste : { visible : false },
      html  : { visible: true },
      exam_html: { exec: function() { this.insertHtml('<abbr title=\"exam\">Jam</abbr>') }, visible: true  }
    },
    events: {
      click : function(e)
      {
        if ($('#click-inform:checked').length > 0)
        {
          e.preventDefault();
          alert('You have clicked jWysiwyg content!');
        }
      }
    }
  });

  $('#wysiwyg').wysiwyg('insertHtml', '');

})(jQuery);

    </script>
</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>