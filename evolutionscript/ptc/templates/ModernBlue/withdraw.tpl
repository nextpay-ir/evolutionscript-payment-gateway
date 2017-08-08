<!-- Content -->
<div class="widget-main-title">{$lang.txt.withdrawtitle}</div>

<div class="widget-content">
{if $user_info.money < $minimum_cashout}
	<div class="error_box">
	{$lang.txt.nocashout|replace:"%money":$minimum_cashout}
    </div>
{elseif $user_info.clicks < $settings.withdraw_clicks}
	<div class="error_box">
	{$lang.txt.nocashout2|replace:"%minclicks":$settings.withdraw_clicks}
    </div>
{elseif $can_cashout == 'no'}
	<div class="error_box">
	{$lang.txt.nocashout3|replace:"%days":$mymembership.cashout_time|replace:"%nextcashout":$next_datec}
    </div>
{else}
<div style="display:none" id="message_sent">{$lang.txt.paymentsent}</div>
<script>
function showoption(id){
	$("#paymentgateways").hide();
	$("#gateway-"+id).fadeIn();
}
function showgateways(id){
	$("#gateway-"+id).hide();
	$("#paymentgateways").fadeIn();
}
</script>
    <div id="paymentgateways" align="center">
    	<div class="padding5" align="right">لطفا یک روش برای تسویه حساب انتخاب نمایید</div>
    	{section name=n loop=$gateway}
        	<img src="images/gateways/{$gateway[n].id}.png" class="pointer" onclick="showoption({$gateway[n].id});" />
        {/section}
    </div>

    {section name=n loop=$gateway}
	<div id="gateway-{$gateway[n].id}" style="display:none">
<form class="formclass" id="withdrawform-{$gateway[n].id}" onsubmit="return submitform(this.id);">
<table cellpadding="4" width="100%" class="widget-tbl">
<tr>
	<td align="right" width="50%">{$lang.txt.paymentmethod}:</td>
    <td>{$gateway[n].name}</td>
</tr>
<tr>
	<td align="right">{$lang.txt.paymentacc}:</td>
    <td>
	{section name=u loop=$usrgateway}
    	{if $usrgateway[u].id == $gateway[n].id}
        	{$usrgateway[u].account}
        {/if}
    {/section}
    </td>
</tr>
{if $mymembership.max_withdraw > 0}
<tr>
	<td align="right">{$lang.txt.max_witdraw}:</td>
    <td>{$mymembership.max_withdraw} تومان</td>
</tr>
{/if}
<tr>
	<td align="right">{$lang.txt.withdrawfee}:</td>
    <td>{$gateway[n].withdraw_fee}% + {$gateway[n].withdraw_fee_fixed} تومان</td>
</tr>
<tr id="total">
	<td colspan="2" align="center">{$lang.txt.youwillreceive} <strong>
    {if $mymembership.max_withdraw >0}
    	{if $user_info.money-$gateway[n].withdraw_fee_fixed-$user_info.money*$gateway[n].withdraw_fee/100 > $mymembership.max_withdraw}
			{$mymembership.max_withdraw-$gateway[n].withdraw_fee_fixed-$mymembership.max_withdraw*$gateway[n].withdraw_fee/100}
        {else}
        	{$user_info.money-$gateway[n].withdraw_fee_fixed-$user_info.money*$gateway[n].withdraw_fee/100}
        {/if}
   	{else}
    	{$user_info.money-$gateway[n].withdraw_fee_fixed-$user_info.money*$gateway[n].withdraw_fee/100}
    {/if}
        تومان</strong></td>
</tr>
<tr>
	<td align="center" colspan="2">
    <input type="hidden" name="a" value="submit" />
    <input type="hidden" name="gatewayid" value="{$gateway[n].id}" />
    <input type="submit" name="send" value="{$lang.txt.send}" />
    <input type="button" name="btn" value="{$lang.txt.cancel}" class="buttonblue" onclick="showgateways({$gateway[n].id});" />
	</td>
</tr>
</table>



</form>
    
    </div>
    {/section}
    
    

{/if}
</div>




<!-- End Content -->