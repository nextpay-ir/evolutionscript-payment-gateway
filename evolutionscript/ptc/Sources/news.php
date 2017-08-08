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


if ($input->g['get'] == "quicknews") {
	$query = $db->query("SELECT * FROM news ORDER BY date DESC LIMIT 0, " . $settings['quick_news']);

	while ($r = $db->fetch_array($query)) {
		$r['message'] = htmlspecialchars_decode($r['message']);
		$row['date'] = date("d-m-Y", $row['date']);
		$faq[] = $row;
		echo "<div class=\"widget-news-title\">";
		echo $r['title'];
		echo "</div>
<div class=\"widget-news-date\">";
		echo str_replace("%date", date("d M, Y", $r['date']), $lang['txt']['published']);
		echo "</div>
<div class=\"widget-news-content\">";
		echo $r['message'];
		echo "</div>
<div style=\"padding-bottom:20px;\"></div>
";
	}

	echo "<a href=\"./?view=news\">Read more &raquo;</a>
";
	$db->close();
	exit();
}

include SMARTYLOADER;
$news_list = $cache->get("news_list");

if ($news_list == null) {
	$query = $db->query("SELECT * FROM news ORDER BY date DESC LIMIT 0, " . $settings['show_news']);

	while ($row = $db->fetch_array($query)) {
		$row['message'] = htmlspecialchars_decode(stripslashes($row['message']));
		$row['date'] = date("d-m-Y", $row['date']);
		$news_list[] = $row;
	}

	$cache->set("news_list", $news_list, 604800);
}

$smarty->assign("news", $news_list);
$smarty->display("news.tpl");
$db->close();
exit();
?>