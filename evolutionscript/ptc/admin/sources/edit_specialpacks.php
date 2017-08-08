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

$special = $db->fetchRow("SELECT * FROM specialpacks WHERE id=" . $input->gc['edit']);

if ($input->p['action']) {
	if ($settings['demo'] == "yes") {
		$error_msg = "این امکان در ورژن دمو وجود ندارد";
	}
	else {
		if (is_array($input->p['mid'])) {
			foreach ($input->p['mid'] as $mid) {
				switch ($input->p['a']) {
					case "delete":
						$db->delete("specialpacks_list", "id=" . $mid);
				}
			}
		}
	}
}


if ($input->p['do'] == "update_pack") {
	verifyajax();
	verifydemo();
	$set = array("name" => $input->pc['name'], "price" => $input->pc['price'], "enable" => $input->pc['enable']);
	$db->update("specialpacks", $set, "id=" . $special['id']);
	serveranswer(1, "تغییرات بروز شد");
}
else {
	if ($input->p['do'] == "add_benefits") {
		if ($settings['demo'] == "yes") {
			$error_msg = "This is not possible in demo version";
		}
		else {
			if ($input->p['sesion_id'] != $_SESSION['sesion_id']) {
				$error_msg = "دوباره تلاش کنید";
			}
			else {
				if (empty($input->p['type']) || ($input->p['type'] != "membership" && !is_numeric($input->p['amount']))) {
					$error_msg = "بعضی فیلدها اشتباه هستند";
				}
				else {
					$type = $input->pc['type'];
					$amount = $input->pc['amount'];

					if ($type == "ptc_credits") {
						$title = "اعتبار پرداخت به ازای کلیک";
					}
					else {
						if ($type == "fads_credits") {
							$title = "اعتبار تبلیغ ویژه";
						}
						else {
							if ($type == "banerads_credits") {
								$title = "اعتبار تبلیغ بنری";
							}
							else {
								if ($type == "flink_credits") {
									$title = "اعتبار تبلیغ لینکی ویژه";
								}
								else {
									if ($type == "ptsu_credits") {
										$title = "اعتبار تبلیغ پرداخت به ازای ثبت نام";
									}
									else {
										if ($type == "direct_refs") {
											$title = "زیرمجموعه مستقیم";
										}
										else {
											if ($type == "rented_refs") {
												$title = "زیرمجموعه اجاره ای";
											}
											else {
												if ($type == "membership") {
													$title = "Membership";
													$amount = $input->pc['membershipid'];
												}
											}
										}
									}
								}
							}
						}
					}

					$set = array("specialpack" => $special['id'], "title" => $title, "type" => $type, "amount" => $amount);
					$db->insert("specialpacks_list", $set);
					$success_msg = "سود جدید بروز شد";
				}
			}
		}
	}
}

$paginator = new Pagination("specialpacks_list", "specialpack=" . $special['id']);
$paginator->setOrders("id", "DESC");
$paginator->setPage($input->gc['page']);
$paginator->allowedfield($allowed);
$paginator->setNewOrders($input->gc['orderby'], $input->gc['sortby']);
$paginator->setLink(("./?view=specialpacks_settings&edit=" . $special['id'] . "&") . $adlink);
$q = $paginator->getQuery();
include SOURCES . "header.php";
echo "<script type=\"text/javascript\">
function showoptions(){
	typeval = $(\"#type\").val();
	if(typeval == 'membership'){
		$(\"#amount\").hide();
		$(\"#membership\").fadeIn();
	}else{
		$(\"#membership\").hide();
		$(\"#amount\").fadeIn();
	}
}
</script>
<div class=\"site_title\">ویرایش پکیج ویژه</div>
<div class=\"site_content\">
	<div class=\"widget-title\">کلی</div>
    <div class=\"widget-content\">
        <form method=\"post\" id=\"frm1\" onsubmit=\"return submitform(this.id);\">
        <input type=\"hidden\" name=\"do\" value=\"update_pack\" />
        <table width=\"100%\" class=\"widget-tbl\">
          <tr>
            <td align=\"right\" width=\"300\">نام</td>
            <td><input type=\"text\" name=\"name\" value=\"";
echo $special['name'];
echo "\" /></td>
          </tr>
          <tr>
            <td align=\"right\">قیمت به تومان</td>
            <td><input type=\"text\" name=\"price\" value=\"";
echo $special['price'];
echo "\" /></td>
          </tr>
          <tr>
            <td align=\"right\">فعال</td>
            <td><input type=\"checkbox\" name=\"enable\"  value=\"yes\" ";

if ($special['enable'] == "yes") {
	echo "checked";
}

echo " /></td>
          </tr>
            <tr>
            	<td></td>
                <td>
                    <input type=\"submit\" name=\"save\" value=\"ذخیره\" />
                    <input type=\"button\" name=\"btn\" value=\"بازگشت\" onclick=\"location.href='./?view=specialpacks_settings'\" />
                </td>
            </tr>
        </table>
        </form>

    </div>

    <div class=\"widget-title\">افزودن سود</div>
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

echo "		<form method=\"post\" id=\"frm2\">
		<input type=\"hidden\" name=\"do\" value=\"add_benefits\" />
        <input type=\"hidden\" name=\"sesion_id\" value=\"";
echo sesion_id();
echo "\" />
       <table class=\"widget-tbl\" width=\"100%\">
        	<tr>

                <td width=\"200\">
					نوع : 
                	<select name=\"type\" onchange=\"showoptions();\" id=\"type\">
                    	<option value=\"ptc_credits\">اعتبار پرداخت به ازای هر کلیک</option>
	                    <option value=\"fads_credits\">اعتبار تبلیغ ویژه</option>
    	                <option value=\"banerads_credits\">اعتبار تبلیغ بنری</option>
        	            <option value=\"flink_credits\">اعتبار تبلیغ لینکی ویژه</option>
            	        <option value=\"ptsu_credits\">اعتبار پرداخت به ازای ثبت نام</option>
                        <option value=\"direct_refs\">زیرمجموعه مستقیم</option>
                        <option value=\"rented_refs\">زیرمجموعه اجاره ای</option>
                        <option value=\"membership\">پلن حساب کاربری</option>
                	</select>




                    </td>
        		<td>
                	<span id=\"amount\">
					مقدار : 
                	<input type=\"text\" name=\"amount\" />
                    </span>
                    <span id=\"membership\" style=\"display:none\">
                   یک پلن حساب کاربری انتخاب کنید : 
                    <select name=\"membershipid\">
                    	";
$mquery = $db->query("SELECT id, name FROM membership WHERE id!=1 ORDER BY price ASC");

while ($row = $db->fetch_array($mquery)) {
	echo "<option value=\"" . $row['id'] . "\">" . $row['name'] . "</option>";
}

echo "                    </select>
                    </span>
                    <input type=\"submit\" name=\"btn\" value=\"Add New\" />
                </td>
       	  </tr>
        </table>

        </form>

<form method=\"post\" action=\"";
echo $paginator->gotopage();
echo "\">
          <table width=\"100%\" class=\"widget-tbl\">
            <tr class=\"titles\">
                <td width=\"20\"><input type=\"checkbox\" id=\"checkall\"></td>
                <td>";
echo $paginator->linkorder("type", "نوع");
echo "</td>
                <td>";
echo $paginator->linkorder("amount", "مقدار");
echo "</td>
            </tr>
            ";

while ($r = $db->fetch_array($q)) {
	$tr = ($tr == "tr1" ? "tr2" : "tr1");

	if ($r['type'] == "membership") {
		$amountname = $db->fetchOne("SELECT name FROM membership WHERE id=" . $r['amount']);
	}
	else {
		$amountname = $r['amount'];
	}

	echo "            <tr class=\"";
	echo $tr;
	echo " normal_linetbl\">
                <td align=\"center\"><input type=\"checkbox\" name=\"mid[]\" value=\"";
	echo $r['id'];
	echo "\" class=\"checkall\" /></td>
                <td><span style=\"color:#000099\">";
	echo $r['title'];
	echo "</span>
                </td>
                <td>
                    <span style=\"color:green\">";
	echo $amountname;
	echo "</span>
                </td>
            </tr>
            ";
}


if ($paginator->totalResults() == 0) {
	echo "            <tr>
                <td colspan=\"8\" align=\"center\">موردی یافت نشد</td>
            </tr>
            ";
}

echo "          </table>
            <div style=\"margin-top:10px\">
            <input type=\"button\" value=\"&larr; صفحه قبل\" ";
echo ($paginator->totalPages() == 1 || $paginator->getPage() == 1) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->prevpage() . "';\";";
echo " />

            <input type=\"button\" value=\"صفحه بعد &rarr;\" ";
echo ($paginator->totalPages() == 0 || $paginator->totalPages() == $paginator->getPage()) ? "disabled class=\"btn-disabled\"" : "onclick=\"location.href='" . $paginator->nextpage() . "';\";";
echo " />
                ";

if (1 < $paginator->totalPages()) {
	echo "                <div style=\"float:right\">
                Jump to page:
                <select name=\"p\" style=\"min-width:inherit;\" id=\"pagid\" onchange=\"gotopage(this.value)\">
                    ";
	$i = 1;

	while ($i <= $paginator->totalPages()) {
		if ($i == $paginator->getPage()) {
			echo "<option selected value=\"" . $paginator->gotopage($i) . "\">" . $i . "</option>";
		}
		else {
			echo "<option value=\"" . $paginator->gotopage($i) . "\">" . $i . "</option>";
		}

		++$i;
	}

	echo "                </select>
                <script type=\"text/javascript\">
                    function gotopage(pageid){
                        location.href=pageid;
                    }
                </script>
                </div>
                <div class=\"clear\"></div>
                ";
}

echo "            </div>

            ";

if (0 < $paginator->totalPages()) {
	echo "            <div class=\"widget-title\" style=\"margin-top:5px\">اقدام</div>
                <div class=\"widget-content\">
                    <select name=\"a\">
                        <option value=\"\">یک مورد را انتخاب نمایید</option>
                        <option value=\"delete\">حذف</option>
                    </select>
                    <input type=\"submit\" name=\"action\" value=\"ثبت\" />
                </div>
            ";
}

echo "        </form>
    </div>

</div>


        ";
include SOURCES . "footer.php";
echo " ";
?>