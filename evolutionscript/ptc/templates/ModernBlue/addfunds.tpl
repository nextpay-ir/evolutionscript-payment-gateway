<script type="text/javascript">
	{if $countgateway != 0}
		gateway = Array;
        {section name=n loop=$gateways}
		gateway[{$gateways[n].id}] = '{$gateways[n].min_deposit}';
        {/section}
    {/if}  
function set_gateway(val){
	if(val != ''){
		if(val == 'balance'){
			$("#min_deposit").html('{$lang.txt.min_deposit}: {$settings.amount_transfer} تومان');
		}else{
			$("#min_deposit").html('{$lang.txt.min_deposit}: '+gateway[val]+' تومان ');
		}
		$("#min_deposit").show();
	}else{
		$("#min_deposit").hide();
	}
}
function complete_deposit(){
	$("#error_box").hide();
	var gatewayid = $("#gateway_list").val();
	var amount = $("#amount_deposit").val();
	if(isNaN(parseFloat(amount))){
			$("#error_box").html('{$lang.txt.min_deposit}: {$settings.amount_transfer} تومان');
			$("#error_box").fadeIn();
			return false;
	}
	amount = parseFloat(amount);
	if(gatewayid == ''){
		$("#error_box").html('{$lang.txt.selectmethod}');
		$("#error_box").fadeIn();
	}else
	if(gatewayid == 'balance'){
		if(amount < {$settings.amount_transfer}){
			$("#error_box").html('{$lang.txt.min_deposit}: {$settings.amount_transfer} تومان');
			$("#error_box").fadeIn();
		}else{
			$("#acc_amount").val(amount);
			$("#addfrm").hide();
			$("#acc_balancefrm").fadeIn();
		}
	}else{
		if(amount < gateway[gatewayid]){
			$("#error_box").html('{$lang.txt.min_deposit}: '+gateway[gatewayid]+' تومان ');
			$("#error_box").fadeIn();
		}else{
			$( "#amount"+gatewayid).val(amount);
			$("#addfrm").hide();
			$("#gateway-"+gatewayid).fadeIn();
		}
	}
}
function hide_gateways(){
	$(".gatewayfrm").hide();
	$("#addfrm").fadeIn();
}
</script>
<div class="widget-main-title">{$lang.txt.addfunds}</div>
<div class="menu-content">
    <div class="error_box" id="error_box" style="display:none"></div>
	<table width="100%" class="widget-tbl" id="addfrm">
    	<tr>
        	<td align="right" width="200">{$lang.txt.method}:</td>
            <td>
            <select name="gateway" onchange="set_gateway(this.value);" id="gateway_list">
            <option value=""></option>
            {if $settings.money_transfer == 'yes'}
            <option value="balance">{$lang.txt.account_balance}</option>
            {/if}
            {if $countgateway != 0}
                {section name=n loop=$gateways}
                <option value="{$gateways[n].id}">{$gateways[n].name}</option>
              	{/section}
            {/if}
            </select>
            </td>
        </tr>
        <tr>
        	<td align="right">{$lang.txt.amount}:</td>
            <td><input type="text" name="amount" value="0.00" id="amount_deposit" /> <span style="font-size:10px; color:#0000CC" id="min_deposit"></span>
            </td>
        </tr>
        <tr>
        	<td></td>
            <td><input type="button" name="btn" value="{$lang.txt.send}" onclick="complete_deposit();" /></td>
        </tr>
    </table>	
    
    
     {if $settings.money_transfer == 'yes'}
     	<div id="acc_balancefrm" style="display:none" class="gatewayfrm">
            <div class="info_box">{$lang.txt.click_complete_order}</div>
            <div align="center">
            <form class="formclass" onsubmit="return submitpayment();" id="checkoutform">
            <input type="hidden" name="action" value="buy" />
            <input type="hidden" name="buy" value="purchase_balance" />
            <input type="hidden" id="acc_amount" name="item" />
            <input type="image" src="images/gateways/ab.png" width="100" />
            <div><a href="javascript:void(0);" onclick="hide_gateways();">[{$lang.txt.return}]</a></div>
            </form>
            </div>
        </div>
      {/if}  
	{if $countgateway != 0}
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
    {/if} 
    
       
</div>

           


<!-- End Content -->