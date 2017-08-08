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

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>";
echo $settings['site_name'];
echo " - Under Maintenance</title>
</head>
<style>
body{
background:#ffffff;
font-family:Arial, Helvetica, sans-serif;
color:#3f3f3b;
}
</style>
<body>
<div style=\"padding-top:100px; width:500px; margin:0 auto;\">
	<div><img src=\"/modules/maintenance/logo.png\" /></div>
    <h2>Sorry, We are Currently Performing Maintenance!</h2>
    <div>";
echo $settings['maintenance_msg'];
echo "</div>
	";

if ($settings['copyright'] != "0") {
	echo "    <div style=\"font-size:13px; padding-top:20px;\">Powered by <a href=\"http://www.evolutionscript.com\" style=\"color:#37b6bd; text-decoration:none;\">EvolutionScript</a> ";
	echo $software['version'];
	echo "</div>
    ";
}

echo "</div>


</div>
</body>
</html>
";
?>