<div class="widget-main-title">{$lang.txt.message_center}</div>
        {if $user_info.personal_msg != 'yes'}
        	<div align="center" class="widget-content"><h3>{$lang.txt.msgcenterdisabled}. <a href="index.php?view=account&page=settings">{$lang.txt.click2activate}</a></h3></div>
        
        {else}

<div id="tabs">
	<ul>
    	<li><a href="#tab-1">{$lang.txt.inbox}</a></li>
        <li><a href="#tab-2">{$lang.txt.compose}</a></li>
    </ul>
    <div id="tab-1">
        <form method="post" onsubmit="return submitform(this.id);" id="msglist">
        <table width="100%" class="widget-tbl">
        <tr class="titles">
                <td>{$paginator->linkorder('user_read', '&nbsp;')}</td>
                <td align="center">{$paginator->linkorder('date', $lang.txt.date)}</td>
                <td align="center">{$paginator->linkorder('subject', $lang.txt.subject)}</td>
                <td align="center">{$paginator->linkorder('user_from', $lang.txt.from)}</td>
                <td align="center"><input type="checkbox" id="checkall" /></td>
            </tr>
            

                {foreach item=item from=$thelist}
                    <tr {if $item.user_read == 'no'}style="font-weight:bold;"{/if}>
                        <td align="center"><img src="images/memberbar/email{if $item.user_read != 'no'}_open{/if}.png" /></td>
                       <td align="center">{$item.date|date_format:"%e %B %Y %r"}</td>
                        <td align="center"><a href="./?view=account&page=messages&read={$item.id}">{$item.subject}</a></td>
                        <td align="center">{$item.user_from}</td>
                        <td align="center"><input type="checkbox" name="msg[]" value="{$item.id}" class="checkall" /></td>
                    </tr>
                {/foreach}    
 
                {if $paginator->totalResults() == 0}
                <tr>
                    <td colspan="10" align="center">{$lang.txt.nohavemessages}</td>
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
            
        
        {if $paginator->totalResults() != 0}
                            <table><tr><td>{$lang.txt.selectedmessages}: </td><td>
                            <input type="hidden" name="do" value="action" />
                            <select name="action" id="msgaction">
                                <option value="1">{$lang.txt.delete}</option>
                                <option value="2">{$lang.txt.setasunread}</option>
                                <option value="3">{$lang.txt.setasread}</option>
                            </select></td><td>
                            <input type="submit" name="btn" value="{$lang.txt.go}" />
                            </td>
                            </tr>
                            </table>
        {/if}
        </form>
    </div>
    <div id="tab-2">
    	{include file="messages_compose.tpl"}
    </div>
</div>
		{/if}