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

$language = $cache->get("languages");

if ($language == null) {
	$q = $db->query("SELECT * FROM language WHERE version='" . $software['version'] . "' ORDER BY name DESC");

	while ($r = $db->fetch_array($lq)) {
		if ($r['default_lang'] == 1) {
			$default_language = $r['id'];
		}

		$langlist[$r['id']] = $r;
	}


	if (!is_array($langlist)) {
		$langlist[1] = array("id" => 1, "name" => "English (US)", "version" => $software['version'], "filename" => "english.php", "default_lang" => 1);
		$default_language = 1;
	}

	$language = array("default" => $default_language, "data" => $langlist);
	$cache->set("language", $language, 604800);
}


if (($settings['allowchangelanguage'] == "yes" && is_numeric($_GET['lang'])) || isset($_SESSION['language'])) {
	if (isset($input->g['lang'])) {
		$lang_id = $input->gc['lang'];
	}
	else {
		$lang_id = $_SESSION['language'];
	}


	if (!array_key_exists($lang_id, $language['data'])) {
		$filename = "languages/english.php";
		unset($lang_id);
		unset($_SESSION['language']);
	}
	else {
		$lang_data = $language['data'][$lang_id];
		$filename = "languages/" . $lang_data['filename'];
		$_SESSION['language'] = $lang_data['id'];
	}


	if (isset($input->g['lang'])) {
		header("location: /");
		exit();
	}
}
else {
	$default_id = $language['default'];
	$lang = $language['data'][$default_id];
	$filename = "languages/" . $lang['filename'];

	if (!file_exists($filename)) {
		$filename = "languages/english.php";
	}
}

include $filename;

if ($settings['allowchangelanguage'] == "yes") {
	foreach ($language['data'] as $k => $v) {
		$langlist[] = $v;
	}

	$current_lang = $language['default'];
}

?>