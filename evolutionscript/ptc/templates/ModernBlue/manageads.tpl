<!-- Content -->
<script>

function showconfirm(adid,classid){
	$( "#dialog-confirm" ).dialog({
		resizable: false,
		autoOpen: false,
		modal: true,
		width:500,
		buttons: {
			"{$lang.txt.delete}": function() {
				$( this ).dialog( "close" );
				adcontrol(adid, 'delete', classid);
			},
			"{$lang.txt.cancel}": function() {
				$( this ).dialog( "close" );
				return false;
			}
		}
	});
	return $( "#dialog-confirm" ).dialog( "open" );
}
</script>
<div id="dialog-confirm" title="{$lang.txt.myads} - {$page_title}" style="display:none">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Do you really want to delete this Ad? - This action is not reversible!</p>
</div>

<div class="widget-main-title">{$lang.txt.manage}</div>


<table width="100%" class="widget-tbl">
	<tr>
    	<td align="right">{$lang.txt.manage}:</td>
    	<td>
               <select name="type" onchange="return location.href='./?view=account&page=manageads&class='+this.value;" id="manage_ads">
               {foreach from=$pages key=k item=v}
               		{if $smarty.request.class == $pagesid[$k]}
	               	<option value="{$pagesid[$k]}" selected>{$v}</option>
                	{else}
                    <option value="{$pagesid[$k]}">{$v}</option>
                    {/if}
               {/foreach}
               </select>
       </td>
     </tr>
</table>     
<div style="margin-top:5px"></div>


<div class="widget-title">{$lang.txt.myads} - {$page_title}</div>
<div class="widget-content">
<div class="info_box"><a href="./?view=account&page=newad&class={$page_id}">{$lang.txt.click_to_add_advertisement}</a></div>



	<div id="admanagefrm">
{if $page_id=='ads'}
<table width="100%" class="widget-tbl">
<tr class="titles">
        <td>{$paginator->linkorder('title', $lang.txt.ad)}</td>
        <td align="center">{$paginator->linkorder('click_pack', $lang.txt.credits)}</td>
        <td align="center">{$paginator->linkorder('clicks', $lang.txt.clicks)} / {$paginator->linkorder('outside_clicks', $lang.txt.outside)}</td>
        <td align="center">{$paginator->linkorder('clicks_today', $lang.txt.clicks_today)}</td>
        <td align="center">{$lang.txt.action}</td>
    </tr>

        {foreach item=item from=$thelist}
            {if $item.status == 'Inactive'}
                <tr>
                    <td><a href="{$item.url}" target="_blank">{$item.title}</a></td>
                    <td align="center" colspan="3"><strong><a href="index.php?view=account&page=validate&id={$item.id}" target="_blank">{$lang.txt.clicktovalidatead}</a></strong></td>
                </tr>
            {elseif $item.status == 'Pending'}
                <tr>
                    <td><a href="{$item.url}" target="_blank">{$item.title}</a></td>
                    <td align="center" colspan="3"><strong>{$lang.txt.pendingreview}</strong></td>
                </tr>
            {else}
            <tr id="tbody{$item.id}">
                <td><a href="{$item.url}" target="_blank">{$item.title}</a></td>
                <td align="center">{$item.click_pack}</td>
                <td align="center"><span style="color:#FF9900">{$item.clicks}</span> / <span style="color:green">{$item.outside_clicks}</span></td>
                <td align="center">{$item.clicks_today}</td>
                <td align="center">
				<span id="control{$item.id}">
                	{if $item.status == 'Paused'}
                    	<a href="javascript:void(0);" onclick="adcontrol('{$item.id}', 'start', '{$page_id}');">{$lang.txt.start}</a>
                    {else if $item.status == 'Active'}
                    	<a href="javascript:void(0);" onclick="adcontrol('{$item.id}', 'pause', '{$page_id}');">{$lang.txt.pause}</a>
                    {/if}</span> / 
                    
                    <a href="javascript:void(0);" onclick="showconfirm('{$item.id}','{$page_id}');">{$lang.txt.delete}</a> / 
                    <a href="./?view=account&page=allocate&class={$page_id}&aid={$item.id}">{$lang.txt.allocatecredits}</a> /
                    <a href="./?view=account&page=ptcmaxclicks&aid={$item.id}">{$lang.txt.setmaxclick}</a>
                </td>
            </tr>
            {/if}
        {/foreach}    

        {if $paginator->totalResults() == 0}
        <tr>
            <td colspan="5" align="center">{$lang.txt.no_records}</td>
        </tr>
        {/if}
</table>



{elseif $page_id=='ptsu_offers'}
<table width="100%" class="widget-tbl">
<tr class="titles">
        <td>{$paginator->linkorder('title', $lang.txt.ad)}</td>
        <td align="center">{$paginator->linkorder('credits', $lang.txt.slots)}</td>
        <td align="center">{$paginator->linkorder('approved', $lang.txt.approved)}</td>
        <td align="center">{$paginator->linkorder('pending', $lang.txt.pending)}</td>
        <td align="center">{$lang.txt.action}</td>
    </tr>

        {foreach item=item from=$thelist}
            {if $item.status == 'Pending'}
                <tr>
                    <td><a href="{$item.url}" target="_blank">{$item.title}</a></td>
                    <td align="center" colspan="4"><strong>{$lang.txt.pendingreview}</strong></td>
                </tr>
            {else}
            <tr id="tbody{$item.id}">
                <td><a href="{$item.url}" target="_blank">{$item.title}</a></td>
                <td align="center">{$item.credits}</td>
                <td align="center"><span style="color:green">{$item.approved}</span></td>
                <td align="center"><span><a href="index.php?view=account&page=pending_ptsu&id={$item.id}" style="color:#FF9900">{$item.pending}</a></span></td>
                <td align="center">
                    <a href="./?view=account&page=allocate&class={$page_id}&aid={$item.id}">{$lang.txt.allocatecredits}</a>
                </td>
            </tr>
            {/if}
        {/foreach}    
        {if $paginator->totalResults() == 0}
        <tr>
            <td colspan="10" align="center">{$lang.txt.no_records}</td>
        </tr>
        {/if}
</table>


{elseif $page_id=='banner_ads' || $page_id=='featured_ads'}
<table width="100%" class="widget-tbl">
<tr class="titles">
        <td>{$paginator->linkorder('title', $lang.txt.ad)}</td>
        <td align="center">{$paginator->linkorder('credits', $lang.txt.credits)}</td>
        <td align="center">{$paginator->linkorder('views', $lang.txt.views)} / {$paginator->linkorder('clicks', $lang.txt.clicks)}</td>
        <td align="center">{$lang.txt.action}</td>
    </tr>
    
        {foreach item=item from=$thelist}
            {if $item.status == 'Pending'}
                <tr>
                    <td><a href="{$item.url}" target="_blank">{$item.title}</a></td>
                    <td align="center" colspan="3"><strong>{$lang.txt.pendingreview}</strong></td>
                </tr>
            {else}
            <tr id="tbody{$item.id}">
                <td><a href="{$item.url}" target="_blank">{$item.title}</a></td>
                <td align="center">{$item.credits}</td>
                <td align="center"><span style="color:#FF9900">{$item.views}</span> / <span style="color:green">{$item.clicks}</span></td>
                <td align="center">
				<span id="control{$item.id}">                   
                    <a href="javascript:void(0);" onclick="showconfirm('{$item.id}','{$page_id}');">{$lang.txt.delete}</a> / 
                    <a href="./?view=account&page=allocate&class={$page_id}&aid={$item.id}">{$lang.txt.allocatecredits}</a>
                </td>
            </tr>
            {/if}
        {/foreach}    

        {if $paginator->totalResults() == 0}
        <tr>
            <td colspan="5" align="center">{$lang.txt.no_records}</td>
        </tr>
        {/if}
</table>




{elseif $page_id=='featured_link' ||  $page_id=='login_ads'}
<table width="100%" class="widget-tbl">
<tr class="titles">
        <td>{$paginator->linkorder('title', $lang.txt.ad)}</td>
        <td align="center">{$paginator->linkorder('expires', $lang.txt.expires)}</td>
        <td align="center">{$paginator->linkorder('views', $lang.txt.views)} / {$paginator->linkorder('clicks', $lang.txt.clicks)}</td>
        <td align="center">{$lang.txt.action}</td>
    </tr>
    
  
        {foreach item=item from=$thelist}
            {if $item.status == 'Pending'}
                <tr>
                    <td><a href="{$item.url}" target="_blank">{$item.title}</a></td>
                    <td align="center" colspan="3"><strong>{$lang.txt.pendingreview}</strong></td>
                </tr>
            {else}
            <tr id="tbody{$item.id}">
                <td><a href="{$item.url}" target="_blank">{$item.title}</a></td>
                <td align="center">{if $item.expires==0}{$lang.txt.noactive}{else}{$item.expires|date_format:"%d-%m-%Y"}{/if}</td>
                <td align="center"><span style="color:#FF9900">{$item.views}</span> / <span style="color:green">{$item.clicks}</span></td>
                <td align="center">
				<span id="control{$item.id}">                   
                    <a href="javascript:void(0);" onclick="showconfirm('{$item.id}','{$page_id}');">{$lang.txt.delete}</a> / 
                    <a href="./?view=account&page=allocate&class={$page_id}&aid={$item.id}">{$lang.txt.allocatecredits}</a>
                </td>
            </tr>
            {/if}
        {/foreach}    
 
        {if $paginator->totalResults() == 0}
        <tr>
            <td colspan="5" align="center">{$lang.txt.no_records}</td>
        </tr>
        {/if}
</table>
{/if}

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

</div>
<!-- End Content -->