<!-- Content -->
<div class="widget-main-title">{$lang.txt.upgaccount}</div>
<div class="widget-content">
<table width="100%" cellpadding="4" class="widget-tbl">
	<tr class="titles">
    	<td></td>
		{section name=m loop=$themembership}
		<td>{$themembership[m].name}</td>
		{/section}        
    </tr>

    <tr>
    	<td>{$lang.txt.duration}</td>
        {section name=m loop=$themembership}
         <td>
         {if $themembership[m].id == 1}
         نامحدود
         {else}
         {$themembership[m].duration} روز
         {/if}
         </td>
        {/section} 
    </tr>
    <tr>
    	<td>{$lang.txt.perclick}</td>
        {section name=m loop=$themembership}
         <td><strong>{$themembership[m].click}%</strong> هر کلیک</td>
        {/section} 
    </tr>
    <tr>
    	<td>{$lang.txt.perrefclick}</td>
        {section name=m loop=$themembership}
         <td><strong>{$themembership[m].ref_click}%</strong> هر کلیک</td>
        {/section} 
    </tr>
    <tr>
    	<td>{$lang.txt.maxdirectref}</td>
        {section name=m loop=$themembership}
        <td>
            {if $themembership[m].directref_limit==-1}{$lang.txt.nolimit}{else}{$themembership[m].directref_limit}{/if}
   		</td>
        {/section} 
    </tr>
    <tr>
    	<td>{$lang.txt.maxrentref}</td>
        {section name=m loop=$themembership}
        <td>
            {if $themembership[m].rentedref_limit==-1}{$lang.txt.nolimit}{else}{$themembership[m].rentedref_limit}{/if}
   		</td>
        {/section} 
    </tr>
    <tr>
    	<td>{$lang.txt.refupgcom}</td>
        {section name=m loop=$themembership}
        <td>
          {$themembership[m].ref_upgrade} تومان
   		</td>
        {/section} 
    </tr>
    <tr>
    	<td>{$lang.txt.refadcom}</td>
        {section name=m loop=$themembership}
        <td>
          {$themembership[m].ref_purchase}%
   		</td>
        {/section} 
    </tr>
    
    <tr>
    	<td>{$lang.txt.recycleprice}</td>
        {section name=m loop=$themembership}
        <td>
          {$themembership[m].recycle_cost} تومان
   		</td>
        {/section} 
    </tr>
    
    <tr>
    	<td>{$lang.txt.rentframe}</td>
        {section name=m loop=$themembership}
        <td>
          {$themembership[m].rent_time} روز
   		</td>
        {/section} 
    </tr>
    <tr>
    	<td>{$lang.txt.withdrawalframe}</td>
        {section name=m loop=$themembership}
        <td>
          {$themembership[m].cashout_time} روز
   		</td>
        {/section} 
    </tr>
    
    <tr>
    	<td>{$lang.txt.refdeletion}</td>
        {section name=m loop=$themembership}
        <td>
          {if $themembership[m].referral_deletion == 0}رایگان{else}{$themembership[m].referral_deletion}{/if}
   		</td>
        {/section} 
    </tr>
    <tr  style="display:none !important;">
    	<td>{$lang.txt.instantwithdrawal}</td>
        {section name=m loop=$themembership}
        <td>
          {if $themembership[m].instant_withdrawal == 'yes'}بلی{else}خیر{/if}
   		</td>
        {/section} 
    </tr>
    <tr>
    	<td>{$lang.txt.price}</td>
        {section name=m loop=$themembership}
         <td><strong>{$themembership[m].price} تومان</strong></td>
        {/section} 
    </tr>
</table>

<div id="tabs">
	<ul>
    	{if $settings.upgrade_purchasebalance == 'yes'}
    	<li><a href="#tab-1">{$lang.txt.upgrade_using_purchasebalance}</a></li>
        {/if}
        {if is_array($gateways)}
        <li><a href="#tab-2">{$lang.txt.upgrade_using_gateways}</a></li>
        {/if}
    </ul>
    {if $settings.upgrade_purchasebalance == 'yes'}
    <div id="tab-1">
<form class="formclass" onsubmit="return submitpayment();" id="checkoutform">
<input type="hidden" name="action" value="buy" />
<input type="hidden" name="buy" value="membership" />
        	<table cellpadding="3" width="100%" class="widget-tbl">
            	<tr>
                	<td width="50%" align="right">{$lang.txt.item}:</td>
                    <td>
                <select name="item">
                    {foreach item=membership from=$themembership}
                    {if $mymembership.price>$membership.price || $membership.id==1}
                        {continue}
                    {/if}
                    <option value="{$membership.id}">{$membership.name}</option>
                    {/foreach}
                </select>
                    </td>
                </tr>
                
                <tr>
                	<td align="right">{$lang.txt.purchasebalance}:</td>
                    <td>{$user_info.purchase_balance} تومان</td>
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
    {if is_array($gateways)}
    <div id="tab-2">
    
<script type="text/javascript">
membership = Array;
{section name=n loop=$themembership}
membership[{$themembership[n].id}] = '{$themembership[n].price}';
{/section}

function complete_deposit(){
	$("#error_box").hide();
	var gatewayid = $("#gateway_list").val();
	var membershipid = $("#membership_list").val();
	if(gatewayid == ''){
		$("#error_box").html('{$lang.txt.selectmethod}');
		$("#error_box").fadeIn();
	}else{
		$( "#amount"+gatewayid).val(membership[membershipid]);
		$( "#upgrade"+gatewayid).val(membershipid);
		$("#addfrm").hide();
		$("#gateway-"+gatewayid).fadeIn();
	}
}
function hide_gateways(){
	$(".gatewayfrm").hide();
	$("#addfrm").fadeIn();
}
</script>
        	<table cellpadding="3" width="100%" class="widget-tbl" id="addfrm">
            	<tr>
                	<td width="50%" align="right">{$lang.txt.item}:</td>
                    <td>
                <select name="item" id="membership_list">
                    {foreach item=membership from=$themembership}
                    {if $mymembership.price>$membership.price || $membership.id==1}
                        {continue}
                    {/if}
                    <option value="{$membership.id}">{$membership.name}</option>
                    {/foreach}
                </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">{$lang.txt.method}:</td>
                    <td>
                    <select name="gateway" id="gateway_list">
                    <option value=""></option>
                    {section name=n loop=$gateways}
                    <option value="{$gateways[n].id}">{$gateways[n].name}</option>
                    {/section}
                    </select>
                    </td>
                    <tr>
                        <td></td>
                        <td><input type="button" name="btn" value="{$lang.txt.send}" onclick="complete_deposit();" /></td>
                    </tr>
                </tr>
           </table>
            {section name=n loop=$gateways}
            <div id="gateway-{$gateways[n].id}" style="display:none" class="gatewayfrm">
                <div class="info_box">{$lang.txt.click_complete_order}</div>
                <div align="center">
                <img src="images/gateways/{$gateways[n].id}.png" width="100" class="pointer" onclick="document.forms['checkout{$gateways[n].id}'].submit();" />
                     <div><a href="javascript:void(0);" onclick="hide_gateways();">[{$lang.txt.return}]</a></div>
                    <span style="display:none">{$gateways[n].formvar}</span>
                </div>
            </div>
            {/section}
    </div>
    {/if}

</div>




</div>
<!-- End Content -->