<!-- Content -->
<div class="widget-main-title">{$lang.txt.rentrefs}</div>
<div class="widget-content">
{if $show_error == 'yes'}
{$error}
{else}
<div style="display:none" id="errorbox" class="errorbox"></div> 
<form class="formclass" onsubmit="return submitpayment();" id="checkoutform">
<input type="hidden" name="action" value="buy" />
<input type="hidden" name="buy" value="rent_referrals" />
<table class="widget-tbl" width="100%">
	<tr>
    	<td align="right">{$lang.txt.item}:</td>
        <td>
                <select name="item" id="item">
                	{foreach item=pack from=$ref_pack}
                    <option value="{$pack}">{$pack} {$lang.txt.referrals} - {$rent_price*$pack} تومان</option>
                   	{/foreach}
                </select>
		</td>                
    </tr>
    <tr>
    	<td align="right">{$lang.txt.purchasebalance}:</td>
        <td>{$user_info.purchase_balance} تومان</td>
    </tr>
    <tr>
    	<td colspan="2" align="center"><input type="submit" name="btn" value="{$lang.txt.rentnow}" class="orange" /></td>
    </tr>
</table>

</form>
{/if}
</div>
<!-- End Content -->