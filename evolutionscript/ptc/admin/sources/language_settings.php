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

$langquery = $db->query("SELECT * FROM language ORDER BY name ASC");

if ($input->p['do'] == "update_settings") {
	verifyajax();
	verifydemo();
	$db->query("UPDATE settings SET value='" . $input->pc['allowchangelanguage'] . "' WHERE field='allowchangelanguage'");
	$db->query("UPDATE language SET default_lang=0");
	$db->query("UPDATE language SET default_lang=1 WHERE id=" . $input->pc['defaultlanguage']);
	$cache->delete("languages");
	$cache->delete("settings");
	serveranswer(1, "Settings were updated.");
}


if ($input->g['do'] == "uninstall") {
	if ($settings['demo'] != "yes") {
		if (is_numeric($input->g['i']) && $input->g['i'] != 1) {
			$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM language WHERE id=" . $input->g['i'] . " AND default_lang=1");

			if ($verify == 1) {
				$db->query("UPDATE language SET default_lang=1 WHERE id=1");
			}

			$delete = $db->delete("language", "id=" . $input->g['i']);
			$cache->delete("languages");
			header("location: ./?view=language_settings#tab-2");
			exit();
		}
	}
}

$paginator = new Pagination("language", $cond);
$paginator->setOrders("id", "ASC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=language_settings&" . $adlink);
$q = $paginator->getQuery();
include SOURCES . "header.php";
echo "<div class=\"site_title\">Language Settings</div>
<div class=\"site_content\">
<div id=\"tabs\">
	<ul>
    	<li><a href=\"#tab-1\">Settings</a></li>
        <li><a href=\"#tab-2\">Manage Languages</a></li>
    </ul>
    <div id=\"tab-1\">
    <form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
    <input type=\"hidden\" name=\"do\" value=\"update_settings\" />
    <table class=\"widget-tbl\" width=\"100%\">
      <tr>
        <td width=\"50%\" valign=\"top\"><strong>Default Language:</strong><br /><span style=\"font-weight:normal\">Select the default language for your site. This language will be used for all guests, and any members who have not expressed a language preference in their options.</span></td>
        <td valign=\"top\">
        ";

while ($langlist = $db->fetch_array($langquery)) {
	echo "        <input type=\"radio\" name=\"defaultlanguage\" id=\"lang";
	echo $langlist['id'];
	echo "\" value=\"";
	echo $langlist['id'];
	echo "\" ";

	if ($langlist['default_lang'] == 1) {
		echo "checked";
	}

	echo " />
        <label for=\"lang";
	echo $langlist['id'];
	echo "\">
        ";
	echo $langlist['name'];
	echo "        </label><br />
        ";
}

echo "        </td>
      </tr>
      <tr>
        <td valign=\"top\"><strong>Allow Users To Change Language</strong><br /><span style=\"font-weight:normal\">This allows users to set their preferred lanaguage. Setting this to 'No' disables that option and will force them to use whatever language has been specified.</span></td>
        <td valign=\"top\">
        ";
$allowchangelang = array("yes" => "Yes", "no" => "No");
foreach ($allowchangelang as $k => $v) {
	echo "<input type=\"radio\" name=\"allowchangelanguage\" value=\"" . $k . "\" id=\"lang" . $k . "\" ";

	if ($settings['allowchangelanguage'] == $k) {
		echo "checked=\"checked\"";
	}

	echo " /><label for=\"lang" . $k . "\">" . $v . "</label>";
}

echo "        </td>
      </tr>
      <tr>
      	<td></td>
        <td>
        <input type=\"submit\" name=\"btn\" value=\"Save\" />
        </td>
      </tr>
    </table>
    </form>
    </div>
    <div id=\"tab-2\">
          <table width=\"100%\" class=\"widget-tbl\">
            <tr class=\"titles\">
                <td>";
echo $paginator->linkorder("name", "Name");
echo "</td>
                <td align=\"center\">";
echo $paginator->linkorder("version", "Version Check");
echo "</td>
                <td align=\"center\">Uninstaller</td>
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
	echo $r['version'] == $software['version'] ? "<strong style=\"color:green\">Pass</strong>" : "<strong style=\"color:red\">Failed</strong>";
	echo "                </td>
                <td align=\"center\">";
	echo $r['id'] == 1 ? "This one can not be uninstalled" : "<input type=\"button\" name=\"btn\" onclick=\"location.href='./?view=language_settings&do=uninstall&i=" . $r['id'] . "';\" value=\"Uninstall\">";
	echo "	</td>
            </tr>
            ";
}


if ($paginator->totalResults() == 0) {
	echo "            <tr>
                <td colspan=\"8\" align=\"center\">Records not found</td>
            </tr>
            ";
}

echo "          </table>
            <div style=\"margin-top:10px\">
            <input type=\"button\" value=\"&larr; Prev Page\" ";
echo ($paginator->totalPages() == 1 || $paginator->getPage() == 1) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->prevpage() . "';\";";
echo " />

            <input type=\"button\" value=\"Next Page &rarr;\" ";
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