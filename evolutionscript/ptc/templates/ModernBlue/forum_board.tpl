{include file="header.tpl"}


<div class="forum_shortlinks">
    <a href="{$uri}">{$lang.txt.forum} {$settings.site_name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}cat={$frm_category.id}">{$frm_category.name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}board={$frm_board.id}">{$frm_board.name}</a>
</div>
<div style="float:left">
	<h1 class="forum_title">{$frm_board.name}</h1>
</div>
<div style="float:right">
{include file="forum_search_form.tpl"}
</div>
<div class="clear"></div>

<div style="margin-bottom:10px">
    	{if $paginator->totalPages() > 1}
        <div class="forum_top_bar">
        {$paginator->getPagination($lang.txt.prev,$lang.txt.next)}
        </div> 
        {/if}
        <div style="float:right">
        {if $logged == 'yes'}
        {if $user_group.canposttopic == 'yes'}
        <input type="button" class="forumbtn" onclick="location.href='{$uri}board={$frm_board.id}&do=topic';" value="{$lang.txt.newtopic}" />
        {/if}
        {/if}
        </div>
        <div class="clear"></div>
</div>

<div class="frm-title">
	<ul>
    	<li>{$paginator->linkorder('date_updated', $lang.txt.recently_updated)}</li>
        <li>{$paginator->linkorder('replies', $lang.txt.most_replies)}</li>
        <li>{$paginator->linkorder('views', $lang.txt.most_viewed)}</li>
	</ul>
</div>
<div class="frm-content">
    <div class="forum-tblc">
<table width="100%" class="frm-tbl">   
        {foreach item=item from=$thelist}
            <tr>
            	<td align="center" width="50">
                <img src="images/forum/forum.png" />
                </td>
                <td>
                <div class="frm_title">
                {if $item.sticky == 1}
                <img src="images/forum/t_announcement.png" />
                {/if}
                {if $item.locked == 1}
                <img src="images/forum/t_locked.png" />
                {/if}
                <a href="forum.php?topic={$item.id}" title="{$item.descr}">{$item.title}</a> 
                </div>
                <div class="frm_description">{$lang.txt.started_by|replace:"%username%":$item.author},

            {if $item.date|date_format == $smarty.now|date_format}
           		{$lang.txt.todayat} {$item.date|date_format:"%H:%M"}
            {elseif $item.date|date_format == $yesterday|date_format}
            	{$lang.txt.yesterdayat} {$item.date|date_format:"%H:%M"}
            {else}
            	{$item.date|date_format:"%Y-%m-%d ساعت %H:%M"}
            {/if}
            
                </div>
                </td>
                <td align="right" width="100">
                {$item.replies} {$lang.txt.replies}<br />
                {$item.views} {$lang.txt.views}
                </td>
                <td valign="top" width="250">
                <div class="frm_last_post">
                {$item.last_poster}<br />
                {if $item.last_post_date|date_format == $smarty.now|date_format}
                    {$lang.txt.todayat} {$item.last_post_date|date_format:"%H:%M"}
                {elseif $item.last_post_date|date_format == $yesterday|date_format}
                    {$lang.txt.yesterdayat} {$item.date|date_format:"%H:%M"}
                {else}
                    {$item.last_post_date|date_format:"%Y-%m-%d ساعت %H:%M"}
                {/if}
                </div>
                </td>
            </tr>
        {/foreach}    
        {if $paginator->totalResults() == 0}
        <tr>
            <td colspan="5" align="center">{$lang.txt.no_records}</td>
        </tr>
        {/if}
</table>
    </div>
</div>


<div style="margin-top:10px">
    	{if $paginator->totalPages() > 1}
        <div class="forum_top_bar">
        {$paginator->getPagination($lang.txt.prev,$lang.txt.next)}
        </div> 
        {/if}
        <div style="float:right">
        {if $logged == 'yes'}
        {if $user_group.canposttopic == 'yes'}
        <input type="button" class="forumbtn" onclick="location.href='{$uri}board={$frm_board.id}&do=topic';" value="{$lang.txt.newtopic}" />
        {/if}
        {/if}
        </div>
        <div class="clear"></div>
</div>


{include file="footer.tpl"}