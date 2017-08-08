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


if ($user_group['canviewtopic'] == "no") {
	$smarty->display("forum_blocked.tpl");
	$db->close();
	exit();
}

$topic_id = $input->gc['topic'];
$check_topic = verify_topic($topic_id);

if ($check_topic !== false) {
	$upd_views = $db->query("UPDATE forum_posts SET views=views+1 WHERE id=" . $topic_id);
	$frm_topic = get_topic($topic_id);
	$frm_board = get_board($frm_topic['bid']);
	$frm_category = get_category($frm_board['cat_id']);

	if ($input->g['openclosetopic'] == "do") {
		verifyajax();

		if ($frm_topic['author'] != $user_info['username'] && $user_group['canopenclosetopics'] != "yes") {
			serveranswer(0, $lang['txt']['errcommand']);
		}
		else {
			if ($frm_topic['author'] == $user_info['username'] && $user_group['canopencloseowntopics'] != "yes") {
				serveranswer(0, $lang['txt']['errcommand']);
			}
			else {
				if ($frm_topic['locked'] == 0) {
					$db->query("UPDATE forum_posts SET locked=1 WHERE id=" . $frm_topic['id']);
				}
				else {
					$db->query("UPDATE forum_posts SET locked=0 WHERE id=" . $frm_topic['id']);
				}

				serveranswer(1, "");
			}
		}
	}


	if ($_REQUEST['do'] == "reply" && $_SESSION['logged'] == "yes") {
		if ($frm_topic['locked'] != 1 || $user_info['forum_role'] == 1) {
			if ($user_group['canposttopic'] == "no") {
				$smarty->display("forum_blocked.tpl");
				exit();
			}


			if ($_POST['a'] == "doit") {
				verifyajax();
				$new_post = cleanfrm($_POST['message']);

				if (strlen($new_post) == 0) {
					serveranswer(0, $lang['txt']['fieldsempty']);
				}
				else {
					$datastored = array("bid" => $frm_topic['bid'], "topic_rel" => $frm_topic['id'], "title" => $frm_topic['title'], "descr" => $frm_topic['descr'], "message" => $new_post, "date" => time(), "author" => $user_info['username'], "topic" => "no");
					$db->insert("forum_posts", $datastored);
					$upd = $db->query("UPDATE forum_boards SET posts=posts+1 WHERE id=" . $frm_topic['bid']);
					$upd = $db->query("UPDATE forum_posts SET replies=replies+1, date_updated='" . time() . ("' WHERE id=" . $frm_topic['id']));
					$membership = $db->fetchRow("SELECT point_enable, point_post FROM membership WHERE id=" . $user_info['type']);
					$data = array("forum_posts" => $user_info['forum_posts'] + 1);

					if ($membership['point_enable'] == 1) {
						addpoints($user_info['id'], $membership['point_post']);
					}

					$db->update("members", $data, "id=" . $user_info['id']);
					board_recheck($user_info['id'], $frm_topic['bid']);
					serveranswer(1, "location.href=\"forum.php?topic=" . $frm_topic['id'] . "\";");
				}
			}


			if (is_numeric($_REQUEST['post'])) {
				$postquote = cleanfrm($_REQUEST['post']);
				$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_posts WHERE id=" . $postquote);

				if ($verify != 0) {
					$quote = $db->fetchRow("SELECT author, message FROM forum_posts WHERE id=" . $postquote);
					$forum_role = $db->fetchOne("SELECT forum_role FROM members WHERE username='" . $quote['author'] . "'");

					if ($forum_role != 4) {
						$smarty->assign("frm_quote", $quote);
					}
				}
			}

			$smarty->assign("frm_category", $frm_category);
			$smarty->assign("frm_board", $frm_board);
			$smarty->assign("frm_topic", $frm_topic);
			$smarty->assign("topic", $topic);
			$smarty->assign("memberinfo", $memberinfo);
			$smarty->display("forum_reply.tpl");
			$db->close();
			exit();
		}
	}


	if ($_REQUEST['do'] == "edit" && $_SESSION['logged'] == "yes") {
		if ($frm_topic['locked'] != 1 || $user_info['forum_role'] == 1) {
			if (is_numeric($_REQUEST['post'])) {
				$postquote = cleanfrm($_REQUEST['post']);
				$verify = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_posts WHERE id=" . $postquote);

				if ($verify != 0) {
					$quote = $db->fetchRow("SELECT id, author, message, title, descr, topic, sticky, locked FROM forum_posts WHERE id=" . $postquote);

					if ($user_group['caneditownpost'] == "no" && $quote['author'] == $user_info['username']) {
						$smarty->display("forum_blocked.tpl");
						exit();
					}


					if ($user_group['caneditotherspost'] == "no" && $quote['author'] != $user_info['username']) {
						$smarty->display("forum_blocked.tpl");
						exit();
					}


					if ($_POST['a'] == "doit") {
						verifyajax();
						$new_post = $input->pc['message'];
						$topic_title = $input->pc['topic_title'];
						$topic_descr = $input->pc['topic_descr'];

						if (strlen($new_post) == 0) {
							serveranswer(0, $lang['txt']['fieldsempty']);
						}
						else {
							if ($user_info['forum_role'] == 1) {
								$edited_auth = "Admin";
							}
							else {
								$edited_auth = $user_info['username'];
							}

							$datastored = array("message" => $new_post, "edit_date" => time(), "edited" => 1, "edited_author" => $edited_auth);

							if ($topic_id == $quote['id']) {
								$datastored2 = array("title" => $topic_title, "descr" => $topic_descr);
								$datastored = array_merge($datastored, $datastored2);
							}

							$upd = $db->update("forum_posts", $datastored, "id=" . $quote['id']);

							if ($user_info['forum_role'] == 1 && $quote['topic'] == "yes") {
								$data = array("sticky" => $input->pc['sticky'], "locked" => $input->pc['locked']);
								$db->update("forum_posts", $data, "id=" . $quote['id']);
							}

							serveranswer(1, "location.href=\"forum.php?topic=" . $frm_topic['id'] . "\";");
						}
					}

					$smarty->assign("frm_quote", $quote);
				}
			}
			else {
				$smarty->display("forum_blocked.tpl");
			}

			$smarty->assign("frm_category", $frm_category);
			$smarty->assign("frm_board", $frm_board);
			$smarty->assign("frm_topic", $frm_topic);
			$smarty->assign("topic", $topic);
			$smarty->assign("memberinfo", $memberinfo);
			$smarty->display("forum_edit.tpl");
			$db->close();
			exit();
		}
	}


	if ($_SESSION['logged'] == "yes") {
		board_checked($user_info['id'], $frm_board['id']);
	}

	$forum_flags = array("Afghanistan" => "af", "Aland Islands" => "ax", "Albania" => "al", "Algeria" => "dz", "American Samoa" => "as", "Andorra" => "ad", "Angola" => "ao", "Anguilla" => "ai", "Antarctica" => "aq", "Antigua and Barbuda" => "ag", "Argentina" => "ar", "Armenia" => "am", "Aruba" => "aw", "Asia-Pacific" => "ap", "Australia" => "au", "Austria" => "at", "Azerbaijan" => "az", "Bahamas" => "bs", "Bahrain" => "bh", "Bangladesh" => "bd", "Barbados" => "bb", "Belarus" => "by", "Belgium" => "be", "Belize" => "bz", "Benin" => "bj", "Bermuda" => "bm", "Bhutan" => "bt", "Bolivia" => "bo", "Bosnia and Herzegovina" => "ba", "Botswana" => "bw", "Bouvet Island" => "bv", "Brazil" => "br", "British Indian Ocean Territory" => "io", "Brunei Darussalam" => "bn", "Bulgaria" => "bg", "Burkina Faso" => "bf", "Burma (Myanmar)" => "mm", "Burundi" => "bi", "Cambodia" => "kh", "Cameroon" => "cm", "Canada" => "ca", "Cape Verde" => "cv", "Cayman Islands" => "ky", "Central African Republic" => "cf", "Chad" => "td", "Chile" => "cl", "China" => "cn", "Christmas Island" => "cx", "Cocos (Keeling) Islands" => "cc", "Colombia" => "co", "Comoros" => "km", "Congo" => "cg", "Cook Islands" => "ck", "Costa Rica" => "cr", "Croatia (Hrvatska)" => "hr", "Cuba" => "cu", "Cyprus" => "cy", "Czech Republic" => "cz", "Democratic Republic of Congo" => "cd", "Denmark" => "dk", "Djibouti" => "dj", "Dominica" => "dm", "Dominican Republic" => "do", "East Timor" => "tl", "Ecuador" => "ec", "Egypt" => "eg", "El Salvador" => "sv", "Equatorial Guinea" => "gq", "Eritrea" => "er", "Estonia" => "ee", "Ethiopia" => "et", "Europe" => "eu", "Falkland Islands (Malvinas)" => "fk", "Faroe Islands" => "fo", "Fiji" => "fj", "Finland" => "fi", "France" => "fr", "French Guiana" => "gf", "French Polynesia" => "pf", "French Southern Territories" => "tf", "Gabon" => "ga", "Gambia" => "gm", "Georgia" => "ge", "Germany" => "de", "Ghana" => "gh", "Gibraltar" => "gi", "Greece" => "gr", "Greenland" => "gl", "Grenada" => "gd", "Guadeloupe" => "gp", "Guam" => "gu", "Guatemala" => "gt", "Guinea" => "gn", "Guinea-Bissau" => "gw", "Guyana" => "gy", "Haiti" => "ht", "Heard and McDonald Islands" => "hm", "Honduras" => "hn", "Hong Kong" => "hk", "Hungary" => "hu", "Iceland" => "is", "India" => "in", "Indonesia" => "id", "Iran" => "ir", "Iraq" => "iq", "Ireland" => "ie", "Israel" => "il", "Italy" => "it", "Ivory Coast" => "ci", "Jamaica" => "jm", "Japan" => "jp", "Jordan" => "jo", "Kazakhstan" => "kz", "Kenya" => "ke", "Kiribati" => "ki", "Korea (North)" => "kp", "Korea (South)" => "kr", "Kuwait" => "kw", "Kyrgyzstan" => "kg", "Laos" => "la", "Latvia" => "lv", "Lebanon" => "lb", "Lesotho" => "ls", "Liberia" => "lr", "Libya" => "ly", "Liechtenstein" => "li", "Lithuania" => "lt", "Luxembourg" => "lu", "Macau" => "mo", "Macedonia" => "mk", "Madagascar" => "mg", "Malawi" => "mw", "Malaysia" => "my", "Maldives" => "mv", "Mali" => "ml", "Malta" => "mt", "Marshall Islands" => "mh", "Martinique" => "mq", "Mauritania" => "mr", "Mauritius" => "mu", "Mayotte" => "yt", "Mexico" => "mx", "Micronesia" => "fm", "Moldova" => "md", "Monaco" => "mc", "Mongolia" => "mn", "Montenegro" => "me", "Montserrat" => "ms", "Morocco" => "ma", "Mozambique" => "mz", "Namibia" => "na", "Nauru" => "nr", "Nepal" => "np", "Netherlands" => "nl", "Netherlands Antilles" => "an", "Neutral Zone" => "nt", "New Caledonia" => "nc", "New Zealand (Aotearoa)" => "nz", "Nicaragua" => "ni", "Niger" => "ne", "Nigeria" => "ng", "Niue" => "nu", "Norfolk Island" => "nf", "Northern Mariana Islands" => "mp", "Norway" => "no", "Oman" => "om", "Pakistan" => "pk", "Palau" => "pw", "Palestinian Territory, Occupied" => "ps", "Panama" => "pa", "Papua New Guinea" => "pg", "Paraguay" => "py", "Peru" => "pe", "Philippines" => "ph", "Pitcairn" => "pn", "Poland" => "pl", "Portugal" => "pt", "Private" => "01", "Puerto Rico" => "pr", "Qatar" => "qa", "Republic of Serbia" => "rs", "Reunion" => "re", "Romania" => "ro", "Russia" => "ru", "Rwanda" => "rw", "S. Georgia and S. Sandwich Isls." => "gs", "Saint Kitts and Nevis" => "kn", "Saint Lucia" => "lc", "Saint Vincent and the Grenadines" => "vc", "Samoa" => "ws", "San Marino" => "sm", "Sao Tome and Principe" => "st", "Saudi Arabia" => "sa", "Senegal" => "sn", "Serbia and Montenegro" => "cs", "Seychelles" => "sc", "Sierra Leone" => "sl", "Singapore" => "sg", "Slovak Republic" => "sk", "Slovenia" => "si", "Solomon Islands" => "sb", "Somalia" => "so", "South Africa" => "za", "Spain" => "es", "Sri Lanka" => "lk", "St. Helena" => "sh", "St. Pierre and Miquelon" => "pm", "Sudan" => "sd", "Suriname" => "sr", "Svalbard and Jan Mayen Islands" => "sj", "Swaziland" => "sz", "Sweden" => "se", "Switzerland" => "ch", "Syria" => "sy", "Taiwan" => "tw", "Tajikistan" => "tj", "Tanzania" => "tz", "Thailand" => "th", "Togo" => "tg", "Tokelau" => "tk", "Tonga" => "to", "Trinidad and Tobago" => "tt", "Tunisia" => "tn", "Turkey" => "tr", "Turkmenistan" => "tm", "Turks and Caicos Islands" => "tc", "Tuvalu" => "tv", "Uganda" => "ug", "Ukraine" => "ua", "United Arab Emirates" => "ae", "United Kingdom" => "gb", "United States" => "us", "Uruguay" => "uy", "Uzbekistan" => "uz", "Vanuatu" => "vu", "Vatican City State (Holy See)" => "va", "Venezuela" => "ve", "Viet Nam" => "vn", "Virgin Islands (British)" => "vg", "Virgin Islands (U.S.)" => "vi", "Wallis and Futuna Islands" => "wf", "Western Sahara" => "eh", "Yemen" => "ye", "Yugoslavia" => "yu", "Zambia" => "zm", "Zimbabwe" => "zw");
	$mems_q = $db->query("SELECT id, name FROM membership");

	while ($memr = $db->fetch_array($mems_q)) {
		$membership_name[$memr['id']] = $memr['name'];
	}

	$frmgroup_q = $db->query("SELECT * FROM forum_groups");

	while ($frmgr = $db->fetch_array($frmgroup_q)) {
		$forum_groups[$frmgr['id']] = $frmgr;
	}

	$allowed = array("date");
	$paginator = new Pagination("forum_posts", "topic_rel=" . $frm_topic['id']);
	$paginator->setMaxResult(10);
	$paginator->setOrders("date", "ASC");
	$paginator->setPage($input->gc['p']);
	$paginator->allowedfield($allowed);
	$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
	$paginator->setLink("?topic=" . $frm_topic['id'] . "&");
	$q = $paginator->getQuery();

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
	unset($items);
	$smarty->assign("frm_category", $frm_category);
	$smarty->assign("frm_board", $frm_board);
	$smarty->assign("frm_topic", $frm_topic);
	$smarty->assign("topic", $topic);
	$smarty->assign("memberinfo", $memberinfo);
	$smarty->display("forum_topic.tpl");
	$db->close();
	exit();
}

?>