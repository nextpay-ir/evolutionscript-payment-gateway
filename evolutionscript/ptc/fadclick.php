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

define("EvolutionScript", 1);
define("ROOTPATH", dirname(__FILE__) . "/");
define("INCLUDES", ROOTPATH . "includes/");
require_once INCLUDES . "global.php";

if (!is_numeric($input->g['id'])) {
	header("location: index.php");
	$db->close();
	exit();
	return 1;
}

$ad_id = $db->real_escape_string($input->g['id']);
$res = $db->fetchOne("SELECT COUNT(*) AS NUM FROM featured_ads WHERE id=" . $ad_id);

if ($res != 0) {
	$featuredad = $db->fetchOne("SELECT url from featured_ads WHERE id=" . $ad_id);
	$upd = $db->query("UPDATE featured_ads SET clicks=clicks+1 WHERE id=" . $ad_id);
	header("location:" . $featuredad);
	$db->close();
	exit();
	return 1;
}

header("location: index.php");
$db->close();
exit();
?>