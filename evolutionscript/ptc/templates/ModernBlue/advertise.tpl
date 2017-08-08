{include file="header.tpl"}
<script type="text/javascript">
{if $settings.special_available == 'yes'}
	{$specialpackitems}
	{literal}
		$(function(){
			updatepack();
		});
	{/literal}
{/if}
</script>
<!-- Content -->   
<div class="PageTitle"><h1>{$lang.txt.advertiseon|replace:"%mysite":$settings.site_name}</h1></div>
<div class="site_content">
<div class="menu-content">
	<div style="display:none" id="errorbox" class="errorbox"></div>  
    
 	<div id="ads_list">
    
    
    <div class="shopcart">
    	<div class="shoptitle"><div class="carticon">{$lang.txt.ptcads}</div></div>
        <div class="shopcontent">
    	<form method="post" id="ptcform" onsubmit="return prepare_payment('ptcform');" class="formclass">
        <input type="hidden" id="ptcformproduct" name="ptcformproduct" value="{$lang.txt.ptcads}">
        <input type="hidden" name="buy" value="ptc_credits">
                       <select name="item" id="ptcformlist">
                        {section name=n loop=$ad_prices}
                            <option value="{$ad_prices[n].id}">{$ad_prices[n].credits} {$lang.txt.credits} - {$ad_prices[n].price} تومان</option>
                        {/section}
                        </select>
                        <input type="submit" name="send" value="{$lang.txt.buynow}">
        </form>
        </div>
    </div>
    
    {if $settings.loginads_available == 'yes'}
    <div class="shopcart">
    	<div class="shoptitle"><div class="carticon">{$lang.txt.loginads}</div></div>
        <div class="shopcontent">
    	<form method="post" id="loginadform" onsubmit="return prepare_payment('loginadform');">
        <input type="hidden" id="loginadformproduct" name="loginadformproduct" value="{$lang.txt.loginads}">
        <input type="hidden" name="buy" value="loginads_credits">
                       <select name="item" id="loginadformlist">
                        {section name=n loop=$loginad_prices}
                            <option value="{$loginad_prices[n].id}">{$loginad_prices[n].days} {$lang.txt.days} - {$loginad_prices[n].price} تومان</option>
                        {/section}
                        </select>
                        <input type="submit" name="send" value="{$lang.txt.buynow}">
        </form>
        </div>
    </div>
    {/if}
    
    {if $settings.ptsu_available == 'yes'}
    <div class="shopcart">
    	<div class="shoptitle"><div class="carticon">{$lang.txt.ptsu}</div></div>
        <div class="shopcontent">
    	<form method="post" id="ptsuform" onsubmit="return prepare_payment('ptsuform');" class="formclass">
        <input type="hidden" id="ptsuformproduct" name="ptsuformproduct" value="{$lang.txt.ptsu}">
        <input type="hidden" name="buy" value="ptsu_credits"> 
                        <select name="item" id="ptsuformlist">
                        {section name=n loop=$ptsu_price}
                            <option value="{$ptsu_price[n].id}">{$ptsu_price[n].credits} {$lang.txt.credits} - {$ptsu_price[n].price} تومان</option>
                        {/section}
                        </select>   
                        <input type="submit" name="send" value="{$lang.txt.buynow}">
        </form>
        </div>
    </div>
    {/if}
    
    {if $settings.fads_available == 'yes'}
    <div class="shopcart">
    	<div class="shoptitle"><div class="carticon">{$lang.txt.featuredad}</div></div>
        <div class="shopcontent">
    	<form method="post" id="feadform" onsubmit="return prepare_payment('feadform');" class="formclass">
        <input type="hidden" id="feadformproduct" name="feadformproduct" value="{$lang.txt.featuredad}">
        		<input type="hidden" name="buy" value="fad_credits">
                        <select name="item" id="feadformlist">
                        {section name=n loop=$fads_price}
                            <option value="{$fads_price[n].id}">{$fads_price[n].credits} {$lang.txt.credits} - {$fads_price[n].price} تومان</option>
                        {/section}
                        </select>  
                        <input type="submit" name="send" value="{$lang.txt.buynow}">
        </form>
        </div>
    </div>   
    {/if}
    
    
    {if $settings.bannerads_available == 'yes'}
    <div class="shopcart">
    	<div class="shoptitle"><div class="carticon">{$lang.txt.bannerad}</div></div>
        <div class="shopcontent">
    	<form method="post" id="bannerform" onsubmit="return prepare_payment('bannerform');" class="formclass">
        	<input type="hidden" id="bannerformproduct" name="bannerformproduct" value="{$lang.txt.bannerad}">
        		<input type="hidden" name="buy" value="bannerad_credits">
                        <select name="item" id="bannerformlist">
                        {section name=n loop=$banner_price}
                            <option value="{$banner_price[n].id}">{$banner_price[n].credits} {$lang.txt.credits} - {$banner_price[n].price} تومان</option>
                        {/section}
                        </select> 
                        <input type="submit" name="send" value="{$lang.txt.buynow}">
        </form>
        </div>
    </div> 
    {/if}

    
    {if $settings.flinks_available == 'yes'}
    <div class="shopcart">
    	<div class="shoptitle"><div class="carticon">{$lang.txt.featuredlink}</div></div>
        <div class="shopcontent">
    	<form method="post" id="flinkform" onsubmit="return prepare_payment('flinkform');" class="formclass">
        	<input type="hidden" id="flinkformproduct" name="flinkformproduct" value="{$lang.txt.featuredlink}">
        		<input type="hidden" name="buy" value="flink_credits">
                        <select name="item" id="flinkformlist">
                        {section name=n loop=$flinks_price}
                            <option value="{$flinks_price[n].id}">{$flinks_price[n].month} {$lang.txt.months} - {$flinks_price[n].price} تومان</option>
                        {/section}
                        </select> 
                        <input type="submit" name="send" value="{$lang.txt.buynow}">
        </form>
        </div>
    </div>
    {/if}
    
    
 {if $settings.special_available == 'yes'}    
    <div class="shopcart">
    	<div class="shoptitle"><div class="carticon">{$lang.txt.specialpacks}</div></div>
        <div class="shopcontent">
    	<form method="post" id="spackform" onsubmit="return prepare_payment('spackform');" class="formclass">
        		<input type="hidden" name="spackformproduct" id="spackformproduct" value="{$lang.txt.specialpacks}" />
                <input type="hidden" name="buy" value="specialpack">
                        <select class="primary textbox" name="item" id="spackformlist" onchange="updatepack();">
                        {section name=n loop=$specialpacks}
                            <option value="{$specialpacks[n].id}">{$specialpacks[n].name} - {$specialpacks[n].price} تومان</option>
                        {/section}
                        </select> 
                        <input type="submit" name="send" value="{$lang.txt.buynow}">
        </form>
        </div>
    </div>
   {/if}  	  
 
             	
            	

    
    <div class="clear"></div>
	{if $settings.special_available == 'yes'}
    	<div id="specialdescription"><div id="specialpackdescr"></div></div>
    {/if}
    </div>
    
    
            <div id="payment_form" style="display:none;">
            {if $logged == 'yes'}
            <form method="post" id="checkoutform" onsubmit="return submitpayment();" class="formclass">
            <input type="hidden" name="action" value="buy" />
            <div id="payment_details"></div>
            <table align="center" class="widget-tbl" width="500">
            	<tr class="titles">
                	<td colspan="2">تکمیل سفارش</td>
                </tr>
            	<tr>
                	<td align="right"><strong>{$lang.txt.product}:</strong></td>
                    <td><span id="productname"></span></td>
                </tr>
            	<tr>
                	<td align="right"><strong>{$lang.txt.item}:</strong></td>
                    <td><span id="itemname"></span></td>
                </tr>
            	<tr>
                	<td align="right"><strong>{$lang.txt.purchasebalance}:</strong></td>
                    <td>{$user_info.purchase_balance} تومان</td>
                </tr>
                <tr>
                	<td colspan="2" align="center">
                    	<input type="submit" name="send" value="{$lang.txt.buynow}">
                        <input type="button" onclick="cancel_payad();" name="cancel" value="{$lang.txt.cancel}">
                   </td>
                </tr>
            </table>
            </form>
            {else}
            <div align="center" style="padding:10px;"><strong>{$lang.txt.notloggedin}</strong><br><br>
            <input type="button" value="{$lang.txt.cancel}" onclick="cancel_payad();" />
            </div>
            {/if}
            </div>
</div>
</div>
<!-- End Content -->
{include file="footer.tpl"}