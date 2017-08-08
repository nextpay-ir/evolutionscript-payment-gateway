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


if (!$admin->permissions['setup']) {
	header("location: ./");
	exit();
}


if ($input->p['do'] == "update_settings") {
	verifyajax();
	verifydemo();
	$db->query("UPDATE settings SET value='" . $input->pc['forum_active'] . "' WHERE field='forum_active'");
	$db->query("UPDATE settings SET value='" . $input->pc['forum_signature'] . "' WHERE field='forum_signature'");
	$db->query("UPDATE settings SET value='" . $input->pc['forum_signature_maxchar'] . "' WHERE field='forum_signature_maxchar'");
	$db->query("UPDATE settings SET value='" . $input->pc['forum_search'] . "' WHERE field='forum_search'");
	$cache->delete("settings");
	serveranswer(1, "تنظیمات ذخیره شد");
}
else {
	if ($input->p['do'] == "new_group") {
		if ($settings['demo'] == "yes") {
			$error_msg = "This is not possible in demo version";
		}
		else {
			if ($input->p['sesion_id'] != $_SESSION['sesion_id']) {
				$error_msg = "Invalid token try again please";
			}
			else {
				if (empty($input->p['group'])) {
					$error_msg = "یک نام برای گروه انتخاب کنید";
				}
				else {
					$stored = array("name" => $input->pc['group']);
					$db->insert("forum_groups", $stored);
					$success_msg = "گروه اضافه شد";
				}
			}
		}
	}
	else {
		if ($input->p['do'] == "new_category") {
			if ($settings['demo'] == "yes") {
				$error_msg2 = "This is not possible in demo version";
			}
			else {
				if ($input->p['sesion_id2'] != $_SESSION['sesion_id2']) {
					$error_msg2 = "Invalid token try again please";
				}
				else {
					if (empty($input->p['name'])) {
						$error_msg2 = "یک نام برای دسته انتخاب نمایید";
					}
					else {
						if (!is_numeric($input->p['position'])) {
							$position = 0;
						}
						else {
							$position = $input->p['position'];
						}

						$stored = array("name" => $input->pc['name'], "position" => $position);
						$db->insert("forum_categories", $stored);
						$success_msg2 = "دسته افزوده شد";
					}
				}
			}
		}
		else {
			if ($input->p['do'] == "update_category") {
				$catid = $input->pc['cid'];

				if ($settings['demo'] == "yes") {
					$error_msg3 = "This is not possible in demo version";
				}
				else {
					if ($input->p['sesion_id3'] != $_SESSION['sesion_id3']) {
						$error_msg3 = "Invalid token try again please";
					}
					else {
						if (empty($input->p['name'][$catid])) {
							$error_msg3 = "یک نام برای دسته انتخاب کنید";
						}
						else {
							if (!is_numeric($input->p['position'][$catid])) {
								$position = 0;
							}
							else {
								$position = $input->p['position'][$catid];
							}

							$upddata = array("name" => $input->p['name'][$catid], "position" => $position);
							$db->update("forum_categories", $upddata, "id=" . $catid);
							$success_msg3 = "دسته بروز شد";
						}
					}
				}
			}
			else {
				if ($input->p['do'] == "delete_category") {
					$catid = $input->pc['cid'];

					if ($settings['demo'] == "yes") {
						$error_msg3 = "This is not possible in demo version";
					}
					else {
						if ($input->p['sesion_id3'] != $_SESSION['sesion_id3']) {
							$error_msg3 = "Invalid token try again please";
						}
						else {
							$boards = $db->query("SELECT * FROM forum_boards WHERE cat_id=" . $catid);

							while ($row = $db->fetch_array($boards)) {
								$posts = $db->query("SELECT * FROM forum_posts WHERE bid=" . $row['id']);

								while ($prow = $db->fetch_array($posts)) {
									$db->query("UPDATE members SET forum_posts=forum_posts-1 WHERE username='" . $prow['author'] . "'");
									$db->delete("forum_posts", "id=" . $prow['id']);
								}

								$db->delete("forum_log_boards", "id_board=" . $row['id']);
							}

							$db->delete("forum_boards", "cat_id=" . $catid);
							$db->delete("forum_categories", "id=" . $catid);
							$success_msg3 = "دسته حذف شد";
						}
					}
				}
				else {
					if ($input->p['do'] == "new_board") {
						if ($settings['demo'] == "yes") {
							$error_msg4 = "This is not possible in demo version";
						}
						else {
							if ($input->p['sesion_id4'] != $_SESSION['sesion_id4']) {
								$error_msg4 = "Invalid token try again please";
							}
							else {
								if (empty($input->p['name'])) {
									$error_msg4 = "Enter the board name.";
								}
								else {
									if (empty($input->p['cat_id'])) {
										$error_msg4 = "Select a category.";
									}
									else {
										if (!is_numeric($input->p['position'])) {
											$position = 0;
										}
										else {
											$position = $input->p['position'];
										}

										$stored = array("cat_id" => $input->pc['cat_id'], "name" => $input->pc['name'], "descr" => $input->pc['descr'], "position" => $position);
										$db->insert("forum_boards", $stored);
										$success_msg4 = "New board was added";
									}
								}
							}
						}
					}
					else {
						if ($input->p['do'] == "update_board") {
							$boardid = $input->pc['bid'];

							if ($settings['demo'] == "yes") {
								$error_msg5 = "This is not possible in demo version";
							}
							else {
								if ($input->p['sesion_id5'] != $_SESSION['sesion_id5']) {
									$error_msg5 = "Invalid token try again please";
								}
								else {
									if (empty($input->p['name'][$boardid])) {
										$error_msg5 = "Enter a board name.";
									}
									else {
										if (empty($input->p['cat_id'][$boardid])) {
											$error_msg5 = "Select a category.";
										}
										else {
											if (!is_numeric($input->p['position'][$boardid])) {
												$position = 0;
											}
											else {
												$position = $input->p['position'][$boardid];
											}

											$upddata = array("cat_id" => $input->p['cat_id'][$boardid], "name" => $input->p['name'][$boardid], "descr" => $input->p['descr'][$boardid], "position" => $input->p['position'][$boardid]);
											$db->update("forum_boards", $upddata, "id=" . $boardid);
											$success_msg5 = "Board was updated.";
										}
									}
								}
							}
						}
						else {
							if ($input->p['do'] == "delete_board") {
								$boardid = $input->pc['bid'];

								if ($settings['demo'] == "yes") {
									$error_msg5 = "This is not possible in demo version";
								}
								else {
									if ($input->p['sesion_id5'] != $_SESSION['sesion_id5']) {
										$error_msg5 = "Invalid token try again please";
									}
									else {
										$posts = $db->query("SELECT * FROM forum_posts WHERE bid=" . $boardid);

										while ($row = $db->fetch_array($posts)) {
											$db->query("UPDATE members SET forum_posts=forum_posts-1 WHERE username='" . $row['author'] . "'");
											$db->delete("forum_posts", "id=" . $row['id']);
										}

										$db->delete("forum_boards", "id=" . $boardid);
										$db->delete("forum_log_boards", "id_board=" . $boardid);
										$success_msg5 = "Board was removed.";
									}
								}
							}
						}
					}
				}
			}
		}
	}
}


if ($input->g['do'] == "delete_group") {
	if ($settings['demo'] != "yes") {
		if (5 < $input->g['gid']) {
			$db->delete("forum_groups", "id=" . $input->g['gid']);
			$db->query("UPDATE members SET forum_role=3 WHERE forum_role=" . $input->g['gid']);
		}
	}

	header("location: ./?view=forum_settings#tab-2");
	exit();
}
else {
	if ($input->g['do'] == "edit_group") {
		$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_groups WHERE id=" . $input->g['gid']);

		if ($chk != 0) {
			include SOURCES . "forum_group.php";
		}
		else {
			header("location: ./?view=forum_settings#tab-2");
		}

		exit();
	}
	else {
		if ($input->g['do'] == "show_group") {
			$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_groups WHERE id=" . $input->g['gid']);

			if ($chk != 0) {
				include SOURCES . "forum_members.php";
			}
			else {
				header("location: ./?view=forum_settings#tab-2");
			}

			exit();
		}
		else {
			if ($input->g['do'] == "addto_group") {
				$chk = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_groups WHERE id=" . $input->g['gid']);

				if ($chk != 0) {
					include SOURCES . "forum_addmember.php";
				}
				else {
					header("location: ./?view=forum_settings#tab-2");
				}

				exit();
			}
		}
	}
}

$categories = $db->query("SELECT * FROM forum_categories  ORDER BY position ASC");
$catlist = array();

while ($row = $db->fetch_array($categories)) {
	$catlist[$row['id']] = $row['name'];
}

include "header.php";
echo "<div class=\"site_title\">Forum Integration</div>
<div class=\"site_content\">

<div id=\"tabs\">
    <ul>
        <li><a href=\"#tab-1\">General Settings</a></li>
        <li><a href=\"#tab-2\">Forum Groups</a></li>
        <li><a href=\"#tab-3\">Forum Categories</a></li>
        <li><a href=\"#tab-4\">Forum Boards</a></li>
    </ul>
    <div id=\"tab-1\">
    	<form method=\"post\" onsubmit=\"return submitform(this.id);\" id=\"frm1\">
        	<input type=\"hidden\" name=\"do\" value=\"update_settings\" />
    	<table width=\"100%\" class=\"widget-tbl\">
        	<tr>
            	<td align=\"right\" width=\"300\">Forum Active?:</td>
                <td><input type=\"checkbox\" name=\"forum_active\" value=\"yes\" ";
echo $settings['forum_active'] == "yes" ? "checked" : "";
echo " /> Tick to enable - forum integration when enabled</td>
            </tr>
        	<tr>
            	<td align=\"right\">Allow signatures:</td>
                <td><input type=\"checkbox\" name=\"forum_signature\" value=\"yes\" ";
echo $settings['forum_signature'] == "yes" ? "checked" : "";
echo " /> Tick to enable - forum signatures allowed when enabled</td>
            </tr>
        	<tr>
            	<td align=\"right\">Forum signature max characters:</td>
                <td><input type=\"text\" name=\"forum_signature_maxchar\" value=\"";
echo $settings['forum_signature_maxchar'];
echo "\" /></td>
            </tr>
        	<tr>
            	<td align=\"right\">Forum search enable?:</td>
                <td><select name=\"forum_search\">
                	<option value=\"0\">Disabled</option>
                    <option value=\"1\" ";
echo $settings['forum_search'] == 1 ? "selected" : "";
echo ">Enable for all</option>
                    <option value=\"2\" ";
echo $settings['forum_search'] == 2 ? "selected" : "";
echo ">Enable for members only</option>
                </select>
                </td>
            </tr>
            <tr>
            	<td></td>
                <td><input type=\"submit\" name=\"btn\" value=\"Update\" /></td>
            </tr>
        </table>
        </form>
    </div>
    <div id=\"tab-2\">
    	<div class=\"widget-title\">Add New Group</div>
        <div class=\"widget-content\">
		";

if ($error_msg) {
	echo "        <div class=\"error_box\">";
	echo $error_msg;
	echo "</div>
        ";
}

echo "		";

if ($success_msg) {
	echo "        <div class=\"success_box\">";
	echo $success_msg;
	echo "</div>
        ";
}

echo "            <form method=\"post\" action=\"./?view=forum_settings#tab-2\">
            <input type=\"hidden\" name=\"do\" value=\"new_group\" />
            <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
             <table class=\"widget-tbl\" width=\"100%\">
                <tr>
                    <td width=\"150\">
             Add New Group:
                    </td>
                    <td>
             <input type=\"text\" name=\"group\" /> <input type=\"submit\" name=\"btn\" value=\"Create\" />
                    </td>
                </tr>
                </table>

            </form>
        </div>

        <div class=\"widget-title\">Manage Groups</div>
        <script type=\"text/javascript\">
		function doaction(val,rid){
			if(val != ''){
				location.href='./?view=forum_settings&do='+val+'&gid='+rid;
			}
		}
		</script>
          <table width=\"100%\" class=\"widget-tbl\">
            <tr class=\"titles\">
                <td>Name</td>
                <td>Primary Users</td>
                <td></td>
            </tr>
            ";
$q = $db->query("SELECT * FROM forum_groups ORDER BY id ASC");
$count = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_groups");

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "            <tr class=\"";
	echo $tr;
	echo " normal_linetbl\">

                <td><span style=\"color:#000099\">";
	echo $r['name'];
	echo "</span>
                </td>
                <td>
                    <span style=\"color:green\">";
	echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM members WHERE forum_role=" . $r['id']);
	echo "</span>
                </td>
                <td>
    <select name=\"action\" onchange=\"doaction(this.value,";
	echo $r['id'];
	echo ");\">
    <option value=\"\">Select one</option>
    <option value=\"edit_group\">Edit Group</option>
    ";

	if ($r['id'] != 5) {
		echo "    <option value=\"show_group\">Show all users of this group</option>
    ";
	}

	echo "    ";

	if (5 < $r['id']) {
		echo "    <option value=\"delete_group\">Delete this group</option>
    ";
	}

	echo "    ";

	if ($r['id'] != 5) {
		echo "    <option value=\"addto_group\">Add a member to this group</option>
    ";
	}

	echo "    </select>
                </td>
            </tr>
            ";
}


if ($count == 0) {
	echo "            <tr>
                <td colspan=\"8\" align=\"center\">Records not found</td>
            </tr>
            ";
}

echo "          </table>
    </div>
    <div id=\"tab-3\">
    	<div class=\"widget-title\">Add new category</div>
        <div class=\"widget-content\">
		";

if ($error_msg2) {
	echo "        <div class=\"error_box\">";
	echo $error_msg2;
	echo "</div>
        ";
}

echo "		";

if ($success_msg2) {
	echo "        <div class=\"success_box\">";
	echo $success_msg2;
	echo "</div>
        ";
}

echo "            <form method=\"post\" action=\"./?view=forum_settings#tab-3\">
            <input type=\"hidden\" name=\"do\" value=\"new_category\" />
            <input type=\"hidden\" name=\"sesion_id2\" value=\"";
echo sesion_id("sesion_id2");
echo "\" />
        <table width=\"100%\" class=\"widget-tbl\">
            <tr>
                <td>Name:
                <input type=\"text\" name=\"name\" />
                Order:
                <input type=\"text\" name=\"position\" style=\"width:50px\" value=\"";
echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_categories") + 1;
echo "\" />
               <input type=\"submit\" name=\"category\" value=\"Add\" /> </td>
            </tr>
        </table>
            </form>
        </div>
        <div class=\"widget-title\">Manage Categories</div>
        <div class=\"widget-content\">
		";

if ($error_msg3) {
	echo "        <div class=\"error_box\">";
	echo $error_msg3;
	echo "</div>
        ";
}

echo "		";

if ($success_msg3) {
	echo "        <div class=\"success_box\">";
	echo $success_msg3;
	echo "</div>
        ";
}

echo "        <form method=\"post\" action=\"./?view=forum_settings#tab-3\" id=\"catlistfrm\">
        <input type=\"hidden\" name=\"do\" id=\"cat_action\" />
        <input type=\"hidden\" name=\"cid\" id=\"cat_id\" />
        <input type=\"hidden\" name=\"sesion_id3\" value=\"";
echo sesion_id("sesion_id3");
echo "\" />
<table width=\"100%\" class=\"widget-tbl\">
            <tr class=\"titles\">
                <td>Name</td>
                <td>Position</td>
                <td></td>
            </tr>
            ";
$q = $db->query("SELECT * FROM  forum_categories ORDER BY position ASC");
$count = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_categories");

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");
	echo "            <tr class=\"";
	echo $tr;
	echo " normal_linetbl\">

                <td><input type=\"text\" name=\"name[";
	echo $r['id'];
	echo "]\" value=\"";
	echo $r['name'];
	echo "\" /></td>
                <td><input type=\"text\" name=\"position[";
	echo $r['id'];
	echo "]\" value=\"";
	echo $r['position'];
	echo "\" /></td>
                <td>
                <input type=\"button\" name=\"btn\" value=\"Update\" onclick=\"updfrmvars({'cat_id': '";
	echo $r['id'];
	echo "', 'cat_action': 'update_category'}); $('#catlistfrm').submit();\" />
                <input type=\"button\" name=\"btn\" value=\"Remove\" onclick=\"if(confirm('If you delete this category, you also will delete all boards, topics and posts of it')){ updfrmvars({'cat_id': '";
	echo $r['id'];
	echo "', 'cat_action': 'delete_category'});  $('#catlistfrm').submit(); }else{ return false;};\" />
                </td>
            </tr>
            ";
}


if ($count == 0) {
	echo "            <tr>
                <td colspan=\"8\" align=\"center\">Records not found</td>
            </tr>
            ";
}

echo "          </table>
          </form>
        </div>

    </div>
    <div id=\"tab-4\">
    	<div class=\"widget-title\">Add new board</div>
        <div class=\"widget-content\">
		";

if ($error_msg4) {
	echo "        <div class=\"error_box\">";
	echo $error_msg4;
	echo "</div>
        ";
}

echo "		";

if ($success_msg4) {
	echo "        <div class=\"success_box\">";
	echo $success_msg4;
	echo "</div>
        ";
}

echo "            <form method=\"post\" action=\"./?view=forum_settings#tab-4\">
            <input type=\"hidden\" name=\"do\" value=\"new_board\" />
            <input type=\"hidden\" name=\"sesion_id4\" value=\"";
echo sesion_id("sesion_id4");
echo "\" />
            <table class=\"widget-tbl\" width=\"100%\">
                <tr>
                    <td>Name: </td>
                    <td><input type=\"text\" name=\"name\" /></td>
                   	<td>Description: </td>
                    <td><input type=\"text\" name=\"descr\" /></td>
               </tr>
               <tr>
               		<td>Category:</td>
                    <td>
                    <select name=\"cat_id\" class=\"categorylist\">
                        ";
foreach ($catlist as $i => $v) {
	echo "<option value=\"" . $i . "\">" . $v . "</option>";
}

echo "                    </select>
                    </td>
                    <td>Order: </td>
                    <td><input type=\"text\" name=\"position\" value=\"";
echo $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_boards") + 1;
echo "\" /></td>
                </tr>
                <tr>
                	 <td colspan=\"4\" align=\"center\"><input type=\"submit\" name=\"board\" value=\"Add\" /> </td>
                </tr>
            </table>
            </form>
        </div>
        <div class=\"widget-title\">Manage Boards</div>
        <div class=\"widget-content\">
		";

if ($error_msg5) {
	echo "        <div class=\"error_box\">";
	echo $error_msg5;
	echo "</div>
        ";
}

echo "		";

if ($success_msg5) {
	echo "        <div class=\"success_box\">";
	echo $success_msg5;
	echo "</div>
        ";
}

echo "        <form method=\"post\" action=\"./?view=forum_settings#tab-4\" id=\"boardlistfrm\">
        <input type=\"hidden\" name=\"do\" id=\"board_action\" />
        <input type=\"hidden\" name=\"bid\" id=\"board_id\" />
        <input type=\"hidden\" name=\"sesion_id5\" value=\"";
echo sesion_id("sesion_id5");
echo "\" />
        ";
foreach ($catlist as $i => $v) {
	echo "        	<div class=\"widget-title\">Category: ";
	echo $v;
	echo "</div>
            <div class=\"widget-content\">
            <table width=\"100%\" class=\"widget-tbl\">
                        <tr class=\"titles\">
                            <td>Name</td>
                            <td>Descripion</td>
                            <td>Position</td>
                            <td>Category</td>
                            <td></td>
                        </tr>
                        ";
	$q = $db->query("SELECT * FROM  forum_boards WHERE cat_id=" . $i . " ORDER BY position ASC");
	$count = $db->fetchOne("SELECT COUNT(*) AS NUM FROM forum_boards WHERE cat_id=" . $i);

	while ($r = $db->fetch_array($q)) {
		$tr = ($tr == "tr1" ? "tr2" : "tr1");
		echo "                        <tr class=\"";
		echo $tr;
		echo " normal_linetbl\">

                            <td><input type=\"text\" name=\"name[";
		echo $r['id'];
		echo "]\" value=\"";
		echo $r['name'];
		echo "\" /></td>
                            <td><input type=\"text\" name=\"descr[";
		echo $r['id'];
		echo "]\" value=\"";
		echo $r['descr'];
		echo "\" /></td>
                            <td><input type=\"text\" name=\"position[";
		echo $r['id'];
		echo "]\" value=\"";
		echo $r['position'];
		echo "\" /></td>
                            <td>
    	<select name=\"cat_id[";
		echo $r['id'];
		echo "]\">
        	";
		foreach ($catlist as $i => $cn) {

			if ($i == $r['cat_id']) {
				echo "<option value=\"" . $i . "\" selected>" . $cn . "</option>";
				continue;
			}

			echo "<option value=\"" . $i . "\">" . $cn . "</option>";
		}

		echo "        </select>
                            </td>
                            <td>
                            <input type=\"button\" name=\"btn\" value=\"Update\" onclick=\"updfrmvars({'board_id': '";
		echo $r['id'];
		echo "', 'board_action': 'update_board'}); $('#boardlistfrm').submit();\" />
                            <input type=\"button\" name=\"btn\" value=\"Remove\" onclick=\"if(confirm('If you delete this board, you also will delete all topics and posts of it')){ updfrmvars({'board_id': '";
		echo $r['id'];
		echo "', 'board_action': 'delete_board'});  $('#boardlistfrm').submit(); }else{ return false;};\" />
                            </td>
                        </tr>
                        ";
	}


	if ($count == 0) {
		echo "                        <tr>
                            <td colspan=\"8\" align=\"center\">Records not found</td>
                        </tr>
                        ";
	}

	echo "                      </table>
                  </div>
         ";
}

echo "          </form>
        </div>

    </div>
</div>

</div>
        ";
include SOURCES . "footer.php";
echo " ";
?>