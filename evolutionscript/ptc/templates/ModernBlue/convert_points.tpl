<!-- Content -->
{if $mymembership.point_purchasebalance == 1}
<script type="text/javascript">
var points_rate = {$mymembership.point_cashrate};
$(function(){
	calculatepoints();
});
function calculatepoints(){
	var newvalue = $("#mpoints").val()/points_rate;
	$("#total_points").text("$"+newvalue);	
}
</script>
{/if}
<div class="widget-main-title">{$lang.txt.convert_points}</div>
<div class="info_box">{$lang.txt.convert_your_points_touse}</div>
<div id="tabs">
	<ul>
    	{if $mymembership.point_upgrade == 1}
    	<li><a href="#tab-1">{$lang.txt.upgrade_using_points}</a></li>
        {/if}
    	{if $mymembership.point_purchasebalance == 1}
    	<li><a href="#tab-2">{$lang.txt.convert_to_cash}</a></li>
        {/if}
    </ul>
    {if $mymembership.point_upgrade == 1}
    <div id="tab-1">
<form class="formclass" onsubmit="return submitpayment();" id="checkoutform">
<input type="hidden" name="action" value="buy" />
<input type="hidden" name="buy" value="membership_points" />
        	<table cellpadding="3" width="100%" class="widget-tbl">
            	<tr>
                	<td width="50%" align="right">{$lang.txt.item}:</td>
                    <td>
                <select name="item">
                    {foreach item=membership from=$themembership}
                    {if $mymembership.price>$membership.price || $membership.id==1}
                        {continue}
                    {/if}
                    <option value="{$membership.id}">{$membership.name} - {$membership.price*$mymembership.point_upgraderate}pts</option>
                    {/foreach}
                </select>
                    </td>
                </tr>
                
                <tr>
                	<td align="right">{$lang.txt.points}:</td>
                    <td>{$user_info.points}pts</td>
                </tr>
                <tr>
                	<td colspan="2" align="center">
                    <input type="submit" name="send" value="{$lang.txt.send}">
                    <input type="button" name="btn" value="{$lang.txt.cancel}" onclick="location.href='index.php?view=account';">
                    </td>
                </tr>
            </table>
</form>
    </div>
    {/if}
    
    {if $mymembership.point_purchasebalance == 1}
    <div id="tab-2">
<form onsubmit="return submitform(this.id);" id="frm2">
<input type="hidden" name="do" value="convertpoints" />
        	<table cellpadding="3" width="100%" class="widget-tbl">
            	<tr>
                	<td width="50%" align="right">{$lang.txt.conversion_rate}:</td>
                    <td><strong>$1 = {$mymembership.point_cashrate} pts</strong></td>
                </tr>
                
                <tr>
                	<td align="right">{$lang.txt.points}:</td>
                    <td>{$user_info.points} pts</td>
                </tr>
                <tr>
                    <td align="right">{$lang.txt.points_to_convert}:</td>
                    <td ><input type="text" name="points" value="{$mymembership.point_cashrate}" id="mpoints"  onkeyup="calculatepoints();" /></td>
                </tr>
                <tr>
                    <td align="right">{$lang.txt.youwillreceive}:</td>
                    <td ><div id="total_points"></div></td>
                </tr>
                <tr>
                	<td colspan="2" align="center">
                    <input type="submit" name="send" value="{$lang.txt.send}">
                    <input type="button" name="btn" value="{$lang.txt.cancel}" onclick="location.href='index.php?view=account';">
                    </td>
                </tr>
            </table>
</form>
    </div>
    {/if}
</div>

<!-- End Content -->