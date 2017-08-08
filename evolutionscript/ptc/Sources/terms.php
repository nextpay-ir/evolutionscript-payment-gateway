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

include SMARTYLOADER;
$terms_content = $cache->get("terms_content");

if ($terms_content == null) {
	$terms_content = $db->fetchOne("SELECT content FROM site_content WHERE id='terms'");
	$cache->set("terms_content", $terms_content, 604800);
}

$smarty->assign("terms_content", $terms_content);
$smarty->display("terms.tpl");
$db->close();
exit();
?>