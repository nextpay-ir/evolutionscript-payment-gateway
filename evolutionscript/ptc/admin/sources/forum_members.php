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


if (is_numeric($input->g['unassign'])) {
	if ($settings['demo'] != "yes" && $input->g['gid'] != 3) {
		$db->query("UPDATE members SET forum_role=3 WHERE id=" . $input->g['unassign']);
	}

	header("location: ./?view=forum_settings&do=show_group&gid=" . $input->g['gid']);
	exit();
}

$membership = array();
$q = $db->query("SELECT id, name FROM membership");

while ($r = $db->fetch_array($q)) {
	$membership[$r['id']] = $r['name'];
}

$group = $db->fetchRow("SELECT * FROM forum_groups WHERE id=" . $input->g['gid']);
$paginator = new Pagination("members", "forum_role=" . $group['id']);
$paginator->setOrders("id", "DESC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink(("./?view=forum_settings&do=show_group&gid=" . $group['id'] . "&") . $adlink);
$q = $paginator->getQuery();
include SOURCES . "header.php";
echo "<div class=\"site_title\">Group ";
echo $group['name'];
echo " - Members List</div>
<div class=\"site_content\">
<input type=\"button\" onclick=\"location.href='./?view=forum_settings#tab-2';\" value=\"Return\" />

          <table width=\"100%\" class=\"widget-tbl\">
            <tr class=\"titles\">
                <td>";
echo $paginator->linkorder("username", "Username");
echo " / ";
echo $paginator->linkorder("email", "E-mail");
echo "</td>
                <td align=\"center\">";
echo $paginator->linkorder("type", "Membership");
echo "</td>
                <td align=\"center\">";
echo $paginator->linkorder("referrals", "Refs");
echo " / ";
echo $paginator->linkorder("rented_referrals", "Rented Refs");
echo "</td>
                <td align=\"center\">";
echo $paginator->linkorder("money", "Balance");
echo " / ";
echo $paginator->linkorder("purchase_balance", "Purchase");
echo "</td>
                <td align=\"center\">";
echo $paginator->linkorder("withdraw", "Paid");
echo " / ";
echo $paginator->linkorder("pending_withdraw", "Pending");
echo "</td>
                <td align=\"center\">";
echo $paginator->linkorder("country", "Country");
echo "</td>
                <td align=\"center\">";
echo $paginator->linkorder("forum_posts", "Forum Posts");
echo "</td>
                <td></td>
            </tr>
            ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "  	<tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
        <td>";
	echo $r['username'];
	echo "<div style=\"font-size:11px\">";
	echo $r['email'];
	echo "</div></td>
        <td align=\"center\">";
	echo $membership[$r['type']];
	echo "</td>
        <td align=\"center\">
			<span style=\"color:#000099\">";
	echo $r['referrals'];
	echo "</span> / <span style=\"color:orange\">";
	echo $r['rented_referrals'];
	echo "</span>
        </td>
        <td align=\"center\">
			<span style=\"color:green\">";
	echo $r['money'];
	echo "</span> / <span style=\"color:#990000\">";
	echo $r['purchase_balance'];
	echo "</span>
        </td>
        <td align=\"center\">
			<span style=\"color:#990000\">";
	echo $r['withdraw'];
	echo "</span> / <span style=\"color:#000099\">";
	echo $r['pending_withdraw'];
	echo "</span>
        </td>
        <td align=\"center\">";
	echo $r['country'];
	echo "</td>
        <td align=\"center\">";
	echo $r['forum_posts'];
	echo "</td>
        <td align=\"center\"><input type=\"button\" name=\"btn\" value=\"Unassign\" onclick=\"location.href='./?view=forum_settings&do=show_group&gid=";
	echo $group['id'];
	echo "&unassign=";
	echo $r['id'];
	echo "';\" />
        </td>
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
        ";
include SOURCES . "footer.php";
echo " ";
?>