<!-- Content -->
<div class="widget-main-title">{$lang.txt.buyrefs}</div>
<div class="widget-content">
{if $countref == 0}
<div id="error_box" class="errorbox">{$lang.txt.noenoughrefs}</div>
{else}
<form class="formclass" onsubmit="return submitpayment();" id="checkoutform">
<input type="hidden" name="action" value="buy" />
<input type="hidden" name="buy" value="referrals" />
<table class="widget-tbl" width="100%">
	<tr>
    	<td align="right">{$lang.txt.item}:</td>
        <td>
                <select name="item" id="item">
                    {foreach item=referral from=$buy_refs}
                    <option value="{$referral.id}">{$referral.refs} {$lang.txt.referrals} - {$referral.price} تومان</option>
                    {/foreach}
                </select>
        </td>
    </tr>
    <tr>
	    <td align="right">{$lang.txt.purchasebalance}:</td>
        <td>{$user_info.purchase_balance} تومان</td>
    </tr>
    <tr>
    	<td colspan="2" align="center"><input type="submit" name="btn" value="{$lang.txt.buynow}" class="orange"></td>
    </tr>
</table>

</form>
{/if}
</div>
