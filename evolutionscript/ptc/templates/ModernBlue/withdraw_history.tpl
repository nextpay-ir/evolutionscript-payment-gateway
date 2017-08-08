<!-- Content -->

<div class="widget-main-title">{$lang.txt.withdrawhistory}</div>
<div class="widget-content">
<table width="100%" class="widget-tbl">
<tr class="titles">
        <td align="center">{$paginator->linkorder('date', $lang.txt.date)}</td>
        <td align="center">{$paginator->linkorder('account', $lang.txt.account)}</td>
        <td align="center">{$paginator->linkorder('method', $lang.txt.method)}</td>
        <td align="center">{$paginator->linkorder('amount', $lang.txt.amount)}</td>
        <td align="center">{$paginator->linkorder('status', $lang.txt.status)}</td>
    </tr>
    
        {foreach item=item from=$thelist}
            <tr>
                <td align="center">{$item.date|date_format:"%e %B %Y %r"}</td>
                <td align="center">{$item.account}</td>
                <td align="center"><img src="images/proofs/{$item.method}.gif" /></td>
                <td align="center">{$item.amount}</td>
                <td align="center">
                {if $item.status == 'Completed'}
                <span class="item_completed">{$lang.txt.statuscompleted}</span>
                {elseif $item.status == 'Pending'}
                <span class="item_pending">{$lang.txt.statuspending}</span>
                {if $settings.cancel_pendingwithdraw == 'yes'}- <a href="./?view=account&page=withdraw_history&cancel={$item.id}">{$lang.txt.cancel}</a>{/if}
                {else}<span class="item_pending">{$item.status}</span>{/if}
                </td>
            </tr>
        {/foreach}    

        {if $paginator->totalResults() == 0}
        <tr>
            <td colspan="5" align="center">{$lang.txt.no_withdraw}</td>
        </tr>
        {/if}
</table>
<div style="margin-top:10px">
    <input type="button" value="&larr; {$lang.txt.prev_page}" {if $paginator->totalPages() == 1 || $paginator->getPage()==1}disabled class="btn-disabled"{else}onclick="location.href='{$paginator->prevpage()}';"{/if} />

    <input type="button" value="{$lang.txt.next_page} &rarr;" {if $paginator->totalPages() == 0 || $paginator->totalPages() == $paginator->getPage()}disabled class="btn-disabled"{else}onclick="location.href='{$paginator->nextpage()}';"{/if} />
    	{if $paginator->totalPages() > 1}
        <div style="float:right">
        {$lang.txt.jump_page}: 
        <select name="p" style="min-width:inherit;" id="pagid" onchange="gotopage(this.value)">
           {for $i=1 to $paginator->totalPages()}
				{if $i == $paginator->getPage()}
                	<option selected value="{$paginator->gotopage($i)}">{$i}</option>
				{else}
					<option value="{$paginator->gotopage($i)}">{$i}</option>
				{/if}
            {/for}
        </select> 
        <script type="text/javascript">
			function gotopage(pageid){
				location.href=pageid;
			}
		</script>
        </div> 
        <div class="clear"></div>
        {/if}
    </div>
</div>
<!-- End Content -->