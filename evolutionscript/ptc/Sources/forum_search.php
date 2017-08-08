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


if (($settings['forum_search'] == 2 && !$_SESSION['logged']) || $settings['forum_search'] == 0) {
	header("location: ./forum.php");
	$db->close();
	exit();
}


if ($input->r['do'] == "search" && $input->r['search_word']) {
	$searchvars = array("search_word", "searchtype", "subjectonly", "bid", "author");

	if ($input->r['searchtype'] == "") {
		$input->r['searchtype'] = 1;
	}

	foreach ($searchvars as $k) {

		if ($input->r[$k]) {
			switch ($k) {
			case "search_word": {
					break;
				}

			case "subjectonly": {
					break;
				}

			case "searchtype": {
					if ($input->r['searchtype'] == 1) {
						if ($input->r['subjectonly'] == 1) {
							$cond .= "title LIKE '%" . $input->r['search_word'] . "%' AND id=topic_rel AND ";
						}
						else {
							$cond .= "message LIKE '%" . $input->r['search_word'] . "%' OR (title LIKE '%" . $input->r['search_word'] . "%' AND id=topic_rel) AND ";
						}
					}
					else {
						$vars = explode(" ", $input->r['search_word']);
						foreach ($vars as $v) {

							if ($input->r['subjectonly'] == 1) {
								$condb .= "(title LIKE '%" . $v . "%' AND id=topic_rel) OR ";
								continue;
							}

							$condb .= "(message LIKE '%" . $v . "%' OR (title LIKE '%" . $v . "%' AND id=topic_rel)) OR ";
						}

						$cond .= "(" . substr($condb, 0, -4) . ") AND ";
					}

					break;
				}
			}

			$cond .= $k . "='" . $input->rc[$k] . "' AND ";
			break;
			$adlink .= $k . "=" . $input->r[$k] . "&";
			continue;
		}
	}


	if ($cond) {
		$cond = substr($cond, 0, -5);
	}


	if ($adlink) {
		$adlink = "do=search&" . $adlink;
	}

	$allowed = array("date");
	$paginator = new Pagination("forum_posts", $cond);
	$paginator->setMaxResult(10);
	$paginator->setOrders("date", "DESC");
	$paginator->setPage($input->gc['p']);
	$paginator->allowedfield($allowed);
	$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
	$paginator->setLink("?page=search&" . $adlink);
	$q = $paginator->getQuery();
	$forum_flags = array("Afghanistan" => "af", "Aland Islands" => "ax", "Albania" => "al", "Algeria" => "dz", "American Samoa" => "as", "Andorra" => "ad", "Angola" => "ao", "Anguilla" => "ai", "Antarctica" => "aq", "Antigua and Barbuda" => "ag", "Argentina" => "ar", "Armenia" => "am", "Aruba" => "aw", "Asia-Pacific" => "ap", "Australia" => "au", "Austria" => "at", "Azerbaijan" => "az", "Bahamas" => "bs", "Bahrain" => "bh", "Bangladesh" => "bd", "Barbados" => "bb", "Belarus" => "by", "Belgium" => "be", "Belize" => "bz", "Benin" => "bj", "Bermuda" => "bm", "Bhutan" => "bt", "Bolivia" => "bo", "Bosnia and Herzegovina" => "ba", "Botswana" => "bw", "Bouvet Island" => "bv", "Brazil" => "br", "British Indian Ocean Territory" => "io", "Brunei Darussalam" => "bn", "Bulgaria" => "bg", "Burkina Faso" => "bf", "Burma (Myanmar)" => "mm", "Burundi" => "bi", "Cambodia" => "kh", "Cameroon" => "cm", "Canada" => "ca", "Cape Verde" => "cv", "Cayman Islands" => "ky", "Central African Republic" => "cf", "Chad" => "td", "Chile" => "cl", "China" => "cn", "Christmas Island" => "cx", "Cocos (Keeling) Islands" => "cc", "Colombia" => "co", "Comoros" => "km", "Congo" => "cg", "Cook Islands" => "ck", "Costa Rica" => "cr", "Croatia (Hrvatska)" => "hr", "Cuba" => "cu", "Cyprus" => "cy", "Czech Republic" => "cz", "Democratic Republic of Congo" => "cd", "Denmark" => "dk", "Djibouti" => "dj", "Dominica" => "dm", "Dominican Republic" => "do", "East Timor" => "tl", "Ecuador" => "ec", "Egypt" => "eg", "El Salvador" => "sv", "Equatorial Guinea" => "gq", "Eritrea" => "er", "Estonia" => "ee", "Ethiopia" => "et", "Europe" => "eu", "Falkland Islands (Malvinas)" => "fk", "Faroe Islands" => "fo", "Fiji" => "fj", "Finland" => "fi", "France" => "fr", "French Guiana" => "gf", "French Polynesia" => "pf", "French Southern Territories" => "tf", "Gabon" => "ga", "Gambia" => "gm", "Georgia" => "ge", "Germany" => "de", "Ghana" => "gh", "Gibraltar" => "gi", "Greece" => "gr", "Greenland" => "gl", "Grenada" => "gd", "Guadeloupe" => "gp", "Guam" => "gu", "Guatemala" => "gt", "Guinea" => "gn", "Guinea-Bissau" => "gw", "Guyana" => "gy", "Haiti" => "ht", "Heard and McDonald Islands" => "hm", "Honduras" => "hn", "Hong Kong" => "hk", "Hungary" => "hu", "Iceland" => "is", "India" => "in", "Indonesia" => "id", "Iran" => "ir", "Iraq" => "iq", "Ireland" => "ie", "Israel" => "il", "Italy" => "it", "Ivory Coast" => "ci", "Jamaica" => "jm", "Japan" => "jp", "Jordan" => "jo", "Kazakhstan" => "kz", "Kenya" => "ke", "Kiribati" => "ki", "Korea (North)" => "kp", "Korea (South)" => "kr", "Kuwait" => "kw", "Kyrgyzstan" => "kg", "Laos" => "la", "Latvia" => "lv", "Lebanon" => "lb", "Lesotho" => "ls", "Liberia" => "lr", "Libya" => "ly", "Liechtenstein" => "li", "Lithuania" => "lt", "Luxembourg" => "lu", "Macau" => "mo", "Macedonia" => "mk", "Madagascar" => "mg", "Malawi" => "mw", "Malaysia" => "my", "Maldives" => "mv", "Mali" => "ml", "Malta" => "mt", "Marshall Islands" => "mh", "Martinique" => "mq", "Mauritania" => "mr", "Mauritius" => "mu", "Mayotte" => "yt", "Mexico" => "mx", "Micronesia" => "fm", "Moldova" => "md", "Monaco" => "mc", "Mongolia" => "mn", "Montenegro" => "me", "Montserrat" => "ms", "Morocco" => "ma", "Mozambique" => "mz", "Namibia" => "na", "Nauru" => "nr", "Nepal" => "np", "Netherlands" => "nl", "Netherlands Antilles" => "an", "Neutral Zone" => "nt", "New Caledonia" => "nc", "New Zealand (Aotearoa)" => "nz", "Nicaragua" => "ni", "Niger" => "ne", "Nigeria" => "ng", "Niue" => "nu", "Norfolk Island" => "nf", "Northern Mariana Islands" => "mp", "Norway" => "no", "Oman" => "om", "Pakistan" => "pk", "Palau" => "pw", "Palestinian Territory, Occupied" => "ps", "Panama" => "pa", "Papua New Guinea" => "pg", "Paraguay" => "py", "Peru" => "pe", "Philippines" => "ph", "Pitcairn" => "pn", "Poland" => "pl", "Portugal" => "pt", "Private" => "01", "Puerto Rico" => "pr", "Qatar" => "qa", "Republic of Serbia" => "rs", "Reunion" => "re", "Romania" => "ro", "Russia" => "ru", "Rwanda" => "rw", "S. Georgia and S. Sandwich Isls." => "gs", "Saint Kitts and Nevis" => "kn", "Saint Lucia" => "lc", "Saint Vincent and the Grenadines" => "vc", "Samoa" => "ws", "San Marino" => "sm", "Sao Tome and Principe" => "st", "Saudi Arabia" => "sa", "Senegal" => "sn", "Serbia and Montenegro" => "cs", "Seychelles" => "sc", "Sierra Leone" => "sl", "Singapore" => "sg", "Slovak Republic" => "sk", "Slovenia" => "si", "Solomon Islands" => "sb", "Somalia" => "so", "South Africa" => "za", "Spain" => "es", "Sri Lanka" => "lk", "St. Helena" => "sh", "St. Pierre and Miquelon" => "pm", "Sudan" => "sd", "Suriname" => "sr", "Svalbard and Jan Mayen Islands" => "sj", "Swaziland" => "sz", "Sweden" => "se", "Switzerland" => "ch", "Syria" => "sy", "Taiwan" => "tw", "Tajikistan" => "tj", "Tanzania" => "tz", "Thailand" => "th", "Togo" => "tg", "Tokelau" => "tk", "Tonga" => "to", "Trinidad and Tobago" => "tt", "Tunisia" => "tn", "Turkey" => "tr", "Turkmenistan" => "tm", "Turks and Caicos Islands" => "tc", "Tuvalu" => "tv", "Uganda" => "ug", "Ukraine" => "ua", "United Arab Emirates" => "ae", "United Kingdom" => "gb", "United States" => "us", "Uruguay" => "uy", "Uzbekistan" => "uz", "Vanuatu" => "vu", "Vatican City State (Holy See)" => "va", "Venezuela" => "ve", "Viet Nam" => "vn", "Virgin Islands (British)" => "vg", "Virgin Islands (U.S.)" => "vi", "Wallis and Futuna Islands" => "wf", "Western Sahara" => "eh", "Yemen" => "ye", "Yugoslavia" => "yu", "Zambia" => "zm", "Zimbabwe" => "zw");
	$mems_q = $db->query("SELECT id, name FROM membership");

	while ($memr = $db->fetch_array($mems_q)) {
		$membership_name[$memr['id']] = $memr['name'];
	}

	$frmgroup_q = $db->query("SELECT * FROM forum_groups");

	while ($frmgr = $db->fetch_array($frmgroup_q)) {
		$forum_groups[$frmgr['id']] = $frmgr;
	}


	while ($list = $db->fetch_array($q)) {
		$list['message'] = BBCode2Html($list['message']);
		$member = $db->fetchRow("SELECT id, username, type, money, withdraw, referrals, rented_referrals, country, forum_posts, forum_stats, forum_avatar, forum_role, forum_signature, personal_msg, status FROM members WHERE username='" . $list['author'] . "'");
		$member['forum_signature'] = BBCode2Html($member['forum_signature'], 90);
		$flag = $forum_flags[$member['country']];
		$member['flag'] = strtolower($flag);
		$member['membership'] = $membership_name[$member['type']];
		$list['member'] = $member;
		$frm_group = $forum_groups[$member['forum_role']];
		$list['frmgroup'] = $frm_group;
		$items[] = $list;
	}

	$smarty->assign("paginator", $paginator);
	$smarty->assign("thelist", $items);
}

$boards = $db->query("SELECT * FROM forum_boards ORDER BY cat_id ASC, position ASC");

while ($row = $db->fetch_array($boards)) {
	$board[] = $row;
}

$smarty->assign("boardlist", $board);
$smarty->display("forum_search.tpl");
exit();
?>