{include file="header.tpl"}

{include file="forum_search_form.tpl"}

{section name=c loop=$cat}
<div style="margin-bottom:10px">
	<div class="frm-title pointer" onclick="location.href='forum.php?cat={$cat[c].id}';">{$cat[c].name}</div>
    <div class="frm-content">
        <div class="forum-tblc">
        <table width="100%" class="frm-tbl">
        {assign var='catid' value=$cat[c].id}
        {foreach item=boards from=$board[$catid]}
        <tr>
            <td width="50" align="center">
            {if $board_checked[$boards.id] == 'old'}
            <img src="images/forum/forum.png" />
            {else}
            <img src="images/forum/forum_new.png" />
            {/if}
            </td>
            <td>
                <div class="frm_title"><a href="forum.php?board={$boards.id}">{$boards.name}</a></div>
                <div class="">{$boards.descr}</div>
            </td>
            <td align="right" width="100">
            <strong>{$boards.topics}</strong> {$lang.txt.topics}<br />
            <strong>{$boards.posts}</strong> {$lang.txt.posts}
            </td>
            <td width="300" align="left" valign="top">
            <div class="frm_last_post">
            {if empty($last_msg[$boards.id])}
            	{$lang.txt.never}
            {else}

                <a href="forum.php?topic={$last_msg[$boards.id].topic_rel}">{$last_msg[$boards.id].title}</a>
                <div>by <strong>{$last_msg[$boards.id].author}</strong></div>
                <span>
            {if $last_msg[$boards.id].date|date_format == $smarty.now|date_format}
           		{$lang.txt.todayat} {$last_msg[$boards.id].date|date_format:"%H:%M"}
            {elseif $last_msg[$boards.id].date|date_format == $yesterday|date_format}
            	{$lang.txt.yesterdayat} {$last_msg[$boards.id].date|date_format:"%H:%M"}
            {else}
            	{$last_msg[$boards.id].date|date_format:"%Y-%m-%d ساعت %H:%M"}
            {/if}
            	</span>
                    {/if}
            </div>
            </td>
        </tr>
        {/foreach} 
        </table>
        </div>
    </div>
</div>
{/section}


{include file="footer.tpl"}