<!-- Content -->
<script>
$(function(){
	$("#tablelist tr:even").addClass("tr1");
	$("#tablelist tr:odd").addClass("tr2");
});
</script>

<div class="widget-main-title">{$lang.txt.myads} - {$lang.txt.ptsu}</div>
<div class="widget-content">
<div class="info_box">{$lang.txt.cheatmsg}</div>
<table width="100%" class="widget-tbl">
<tr class="titles">
        <td align="center">{$paginator->linkorder('date', $lang.txt.date)}</td>
        <td align="center">{$paginator->linkorder('user_id', $lang.txt.username)}</td>
        <td align="center"></td>
    </tr>
    
    <tbody id="tablelist">
        {foreach item=item from=$thelist}
            <tr>
                <td align="center">{$item.date|date_format:"%e %B %Y %r"}</td>
                <td align="center">{$item.ptcusername}</td>
                <td align="center">
                {if $item.status != 'Rejected1'} 
<a href="javascript:void(0);" onClick="openWindows('<span style=\'font-weight:normal\'>Member:</span> {$item.ptcusername}', 'info-{$item.id}');">Details</a>
<div id="info-{$item.id}" style="display:none">
<form class="submitptsu{$item.id}" id="submitptsu{$item.id}">
<input type="hidden" name="rid" value="{$item.id}" />
		<table class="widget-tbl" width="100%">
        	<tr>
            	<td align="right" width="50%">{$lang.txt.ptsu_descr3}:</td>
                <td>{$item.username}</td>
            </tr>
        	<tr>
            	<td align="right">{$lang.txt.ptsu_descr4}:</td>
                <td>{$item.message}</td>
            </tr>
        	<tr>
            	<td align="right">{$lang.txt.message} ({$lang.txt.onlyifdecline}):</td>
                <td><textarea name="message" id="message{$item.id}" onkeypress="$('#message{$item.id}').val((this.value));" onkeyup="$('#message{$item.id}').val((this.value));" onkeydown="$('#message{$item.id}').val((this.value));" style="height: 100px; width:95%"></textarea></td>
            </tr>
            <tr>
            	<td></td>
                <td align="right">
                <input type="button" name="btn" class="buttonorange" value="{$lang.txt.accept}" onclick="ptsuadvaction('{$item.id}', '{$ptsu_id}', 'accept')" />
                <input type="button" name="btn" class="buttonblue" value="{$lang.txt.reject}" onclick="ptsuadvaction('{$item.id}', '{$ptsu_id}', 'reject')" />

                </td>
            </tr>
        </table>

</form>
</div>
                {else}
                <strong><span style="color:#FF0000">{$lang.txt.waiting4adminreview}</span></strong>
                {/if}
                </td>
            </tr>
        {/foreach}    
    </tbody>
        {if $paginator->totalResults() == 0}
        <tr>
            <td colspan="5" align="center">{$lang.txt.no_records}</td>
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



<!-- End Content -->
