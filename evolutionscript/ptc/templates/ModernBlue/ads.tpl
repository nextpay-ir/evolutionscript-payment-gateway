{include file="header.tpl"}
<div class="site_title">{$lang.txt.viewads}</div>
<div class="site_content">
    <script>
	$(function(){
		$(".blockthis").l2block("#ffffff", 35);
	});
	</script>
{if $logged == 'yes'}
    {if $show_advice == 'yes'}
        <div class="dashboardbox">{eval var=$lang.txt.adsviewed}<br />
        {eval var=$lang.txt.needadstoview}
        </div>
    {/if}

<div class="adtimereset corner-all">
	{$lang.txt.time_reset} {$adsreset|date_format:"%H:%M"}
</div>    

{/if}


{if $myads != 0}
    {if !empty($adminAdvertisement) && $user_info.adminad == 0}
    <div id="admin_advertisement">
        <div align="center">{$lang.txt.click_to_unlock_ads}</div>
        <div class="ad-div">
            <div class="ad-title pointer">
                <span class="ad-name" onclick="window.open('index.php?view=surfer&t=YWRtaW5hZHZlcnRpc2VtZW50','_blank'); ">{$adminAdvertisement.ad_title|stripslashes}</span>
            </div>
            <div class="ad-content">
                {$adminAdvertisement.ad_descr|stripslashes}
            </div>
        </div>
    </div>
    {/if}
    
	{section name=c loop=$adcategory}
	    {assign var="adnumber" value="0"}
        {foreach item=ad from=$advertisement}
        	{if $ad.category == $adcategory[c].id}
                {math equation="x + 1" x=$adnumber assign="adnumber"}
            {/if}
       	{/foreach} 
        
        {if $adnumber > 0}
    		<h3>{$adcategory[c].name}</h3>
            {foreach item=ad from=$advertisement}
            	{if $ad.category == $adcategory[c].id}
                <div class="ad-block {if !empty($adminAdvertisement) && $user_info.adminad == 0}blockthis{/if} {if in_array($ad.id,$advisited)}disabled{/if}" id="{$ad.token}" style="margin-bottom:5px; {if $smarty.session.adSync == $ad.token}display:none{/if}">
                
                
                        <div class="ad-title">
                            <span class="pointer" onclick="window.open('index.php?view=surfer&t={$ad.token}','_blank');">{$ad.title|stripslashes}</span>
                        </div>
                        {if $adcategory[c].hide_descr != 1}
                        <div class="ad-content">
                        <table cellpadding="5" width="100%">
                        <tr>
            {if !empty($ad.img) && $ad.img != 'http://'}
            <td valign="top" width="100">
            <img src="{$ad.img}" style="max-height:100px; max-width:100px" />
            </td>
            {/if}
                 <td valign="top">{$ad.descr|stripslashes}</td>
                 </tr>
                 </table>
                        </div>
                        {/if}
                        <div class="ad-footer">
                        {$ad.value} تومان به دست آورید
                        </div>
                </div> 
                {/if}
            {/foreach} 
            <div class="clear"></div>
        {/if}
    {/section}

	{foreach item=ad from=$advertisement}
    
    
    

    
    

                {/foreach}    
    
   

    
	

    	
    
{else}
<div class="error_box">{$lang.txt.noadsfoundmsg}</div>
{/if}


</div>
<!-- End Content -->
{include file="footer.tpl"}