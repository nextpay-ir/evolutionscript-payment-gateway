<div class="widget-main-title">{$lang.txt.ptsuhistory}</div>
<div class="widget-content">
<table width="100%" class="widget-tbl">
<tr class="titles">
        <td align="center">{$paginator->linkorder('date', $lang.txt.date)}</td>
        <td align="center">{$paginator->linkorder('title', $lang.txt.ad)}</td>
        <td align="center">{$paginator->linkorder('value', $lang.txt.advalue)}</td>
        <td align="center">{$paginator->linkorder('status', $lang.txt.status)}</td>
    </tr>

        {foreach item=item from=$thelist}
            <tr>
                <td align="center">{$item.date|date_format:"%e %B %Y %r"}</td>
                <td align="center"><a href="{$item.url}" target="_blank">{$item.title}</a></td>
                <td align="center">{$item.value}</td>
                <td align="center">
                {if $item.status == 'Completed'}<span class="item_completed">{$lang.txt.statuscompleted}</span>
                {elseif $item.status == 'Cancelled'}<strong style="color:red">{$lang.txt.statuscancelled}</strong>
                {elseif $item.status == 'Rejected1'}<strong style="color:red">{$lang.txt.waiting4adminreview}</strong>
                {else}<span class="item_pending">{$lang.txt.statuspending}</span>
                {/if}
                </td>
            </tr>
        {/foreach}    
        {if $paginator->totalResults() == 0}
        <tr>
            <td colspan="5" align="center">{$lang.txt.no_logs}</td>
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