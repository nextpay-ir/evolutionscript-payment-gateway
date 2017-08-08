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


if ($_SESSION['logged'] == "yes") {
	logout();
	header("location: index.php?view=logout");
	$db->close();
	exit();
}

require SMARTYLOADER;
$smarty->assign("logout_class", "current");
$smarty->assign("loginout_process", "logout");
$smarty->display("loginoutprocess.tpl");
$db->close();
exit();
?>