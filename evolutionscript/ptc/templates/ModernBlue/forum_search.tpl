{include file="header.tpl"}
<div class="forum_shortlinks">
    <a href="./forum.php">{$lang.txt.forum} {$settings.site_name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}">{$lang.txt.search}</a>
</div>

<div class="frm-title">
	<span>{$lang.txt.set_search_params}</span>
</div>
<div class="widget-content" style="margin-top:5px">
	<div class="forum-topiclist" style="padding:10px">
    	<form method="post">
        <input type="hidden" name="do" value="search" />
   		<table align="center">
        	<tr>
            	<td><strong>Search for:</strong></td>
                <td><strong>by user:</strong></td>
            </tr>
            <tr>
            	<td>
                	<input type="text" name="search_word" value="{$smarty.request.search_word|escape:'htmlall'}" />
                	<select name="searchtype">
                    	<option value="1">Match all words</option>
                        <option value="2" {if $smarty.request.searchtype == 2}selected{/if}>Match any words</option>
                    </select>
                </td>
                <td><input type="text" name="author" value="{$smarty.request.author|escape:'htmlall'}" /></td>
            </tr>
        	<tr>
            	<td><strong>Choose a board to search in:</strong></td>
            	<td><strong>Options:</strong></td>
            </tr>
            <tr>
            	<td>
                <select name="bid">
                	<option value="">All</option>
                    {section name=n loop=$boardlist}
                    	<option value="{$boardlist[n].id}" {if $boardlist[n].id == $smarty.request.bid}selected{/if}>{$boardlist[n].name}</option>
                    {/section}
                </select>
                	</td>
            	<td><label><input type="checkbox" name="subjectonly" value="1" {if $smarty.request.subjectonly == 1}checked{/if} />  Search in topic subjects only </label></td>
            </tr>
            <tr>
            	<td colspan="2"><input type="submit" name="btn" value="{$lang.txt.search}" class="frm_search_btn" /></td>
            </tr>
        </table>
        </form>
{if $paginator}
<div style="margin-bottom:10px">
    	{if $paginator->totalPages() > 1}
        <div class="forum_top_bar">
        {$paginator->getPagination($lang.txt.prev,$lang.txt.next)}
        </div> 
        {/if}
        <div class="clear"></div>
</div>
{/if}
        {foreach item=item from=$thelist}
        <div class="forum_username pointer" onclick="location.href='./forum.php?topic={$item.topic_rel}';">
       	{$item.title}
        </div>
        <div style="padding:10px;">
                <div class="forum_post_date">
                 Message by {$item.author}
                <div style="float:right">
                Posted
                {if $item.date|date_format == $smarty.now|date_format}
                    {$lang.txt.todayat} {$item.date|date_format:"%H:%M"}
                {elseif $item.date|date_format == $yesterday|date_format}
                    {$lang.txt.yesterdayat} {$item.date|date_format:"%H:%M"}
                {else}
                    on {$item.date|date_format:"%Y-%m-%d ساعت %H:%M"}
                {/if}
                </div>
                <div class="clear"></div>
                </div>
        {$item.message}
        </div>
        {/foreach}
    </div>
</div>


{include file="footer.tpl"}