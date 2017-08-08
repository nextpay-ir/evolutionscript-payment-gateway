<!-- Content -->
    {literal}
    <script>
	$(function(){
		$(".ad_info").hover(function(){
			$(this).addClass('ui-state-hover');
		}, function(){
			$(this).removeClass('ui-state-hover');
		});
		$(".ad-visited td").addClass('ui-state-default');
	});
	</script>
    {/literal}
<div class="widget-main-title">{$lang.txt.ptsu}</div>

<div class="widget-content">
{if $myads != 0}
<table width="100%">
	<tr>
    	<td><strong>{$lang.txt.title}</strong></td>
        <td width="80" align="center"><strong>{$lang.txt.reward}</strong></td>
    </tr>
                {foreach item=ad from=$advertisement}
    <tr>
    	<td class="ptsu_content">
        <div onclick="location.href='index.php?view=account&page=ptsu&id={$ad.id}';">
        <div class="ptsu_title">{$ad.title}</div>
        <div class="ptsu_descr">{$ad.descr}</div>
        <div class="ptsu_url">{$ad.web}</div>
        </div>
        </td>
        <td align="center" class="ptsu_content ptsu_reward">
        {$ad.value} تومان
        </td>
    </tr>           
                                
                {/foreach}    
</table>

    
	

    	
    
{else}

	{$lang.txt.adnotfound}

{/if}
</div>

   
<!-- End Content -->
