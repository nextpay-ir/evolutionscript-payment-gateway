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
include INCLUDES . "class_pagination.php";
$allowed = array("date", "fromacc", "method", "amount");
$paginator = new Pagination("deposit_history", "user_id=" . $user_info['id']);
$paginator->setMaxResult($settings['max_result_page']);
$paginator->setOrders("date", "DESC");
$paginator->setPage($input->gc['p']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink("./?view=account&page=deposit_history&");
$q = $paginator->getQuery();

while ($list = $db->fetch_array($q)) {
	$items[] = $list;
}

$smarty->assign("paginator", $paginator);
$smarty->assign("thelist", $items);
unset($items);
$smarty->assign("file_name", "deposit_history.tpl");
$smarty->display("account.tpl");
$db->close();
exit();
?>