{include file="header.tpl"}


<div class="forum_shortlinks">
    <a href="{$uri}">{$lang.txt.forum} {$settings.site_name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}cat={$frm_category.id}">{$frm_category.name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}board={$frm_board.id}">{$frm_board.name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}topic={$frm_topic.id}">{$frm_topic.title}</a>
</div>

<div style="float:left">
    <h1 class="forum_title">{$frm_topic.title}</h1>
    {$lang.txt.started_by|replace:"%username%":$frm_topic.author}
    {if $frm_topic.date|date_format == $smarty.now|date_format}
        {$lang.txt.todayat} {$frm_topic.date|date_format:"%H:%M"}
    {elseif $frm_topic.date|date_format == $yesterday|date_format}
        {$lang.txt.yesterdayat} {$frm_topic.date|date_format:"%H:%M"}
    {else}
        {$frm_topic.date|date_format:"%Y-%m-%d ساعت %H:%M"}
    {/if}
</div>
<div style="float:right">
	{include file="forum_search_form.tpl"}
</div>
<div class="clear"></div>

<div class="error_box" id="error_box" style="display:none"></div>
<div style="margin-bottom:10px">
    	{if $paginator->totalPages() > 1}
        <div class="forum_top_bar">
        {$paginator->getPagination($lang.txt.prev,$lang.txt.next)}
        </div> 
        {/if}
        <div style="float:right">
        {if $logged == 'yes'}
            {if $frm_topic.locked != 1 || $user_info.forum_role == 1}
            	{if $user_info.forum_role != 4}
                <input type="button" class="forumbtn" value="{$lang.txt.reply}" onclick="location.href='{$uri}topic={$frm_topic.id}&do=reply';" />
                {/if}
        	{/if}
        	{if 
	        ($user_group.canopenclosetopics == 'yes' && $frm_topic.author != $user_info.username) || 
            ($user_group.canopencloseowntopics == 'yes' && $frm_topic.author == $user_info.username)        
    	    }
            <input type="button" class="forumbtn" onclick="forum_openclosetopic('{$frm_topic.id}');" value="{if $frm_topic.locked == 0}{$lang.txt.locktopic}{else}{$lang.txt.unlocktopic}{/if}" />
        	{/if}
            
        	{if 
	        ($user_group.canmoveotherstopic == 'yes' && $frm_topic.author != $user_info.username) || 
            ($user_group.canmoveowntopics == 'yes' && $frm_topic.author == $user_info.username)        
    	    }     
        <input type="button" class="forumbtn" onclick="location.href='{$uri}movetopic={$frm_topic.id}';" value="{$lang.txt.movetopic}" />       
            {/if}
        
        {/if}
        </div>
        <div class="clear"></div>
</div>


<div class="frm-title">
	<span>{$lang.txt.replies_to_topic|replace:"%replies%":$frm_topic.replies}</span>

<!-- AddThis Button BEGIN -->
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style " style="float:right; padding-top:2px">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_pinterest_pinit"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-50b4e8ad128a95ae"></script>
<!-- AddThis Button END -->
<!-- AddThis Button END -->
</div>
<div class="frm-content">
    <div class="forum-topiclist">
    {foreach item=item from=$thelist}
    <div id="postid{$item.id}" style="padding:10px;">
        <div class="forum_username">
        <table width="100%" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="200">
        {if $item.member.forum_role == 1}
            {$item.frmgroup.name}
        {else}
            {if $item.member.forum_role != 1}
                <img src="images/forum/flags/{$item.member.flag}.png" title="{$item.member.country}" />
            {/if}
            {$item.author}
        {/if}
        		</td>
                <td><div class="forum_post_date">Posted
                {if $item.date|date_format == $smarty.now|date_format}
                    {$lang.txt.todayat} {$item.date|date_format:"%H:%M"}
                {elseif $item.date|date_format == $yesterday|date_format}
                    {$lang.txt.yesterdayat} {$item.date|date_format:"%H:%M"}
                {else}
                    {$item.date|date_format:"%Y-%m-%d ساعت %H:%M"}
                {/if}
                </div>
                </td>
			</tr>
		</table>
        </div>
        <div style="padding:10px 0px;">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="210" valign="top" rowspan="2" {if $item.member.forum_role == 4}class="disabled"{/if}>
                <div class="forum_usercolumn">
    <table align="center">
                {if $item.member.forum_role != 1}
                    <tr>
                        <td>
                            <img src="images/memberbar/forumf.png" /> {$item.member.membership}
                        </td>
                    </tr>
                {/if}
                    <tr>
                        <td align="center" style="border:1px solid #d5d5d5; background:#f4f4f4">
                            <div style="background:url(
                            {if !empty($item.member.forum_avatar)}
                            {$item.member.forum_avatar}
                            {else}
                            images/forum/user_no_avatar.jpg
                            {/if}
                            ) no-repeat scroll center; width:138px; height:150px;"></div>
                        </td>
                    </tr>
                    {if $settings.message_system == 'yes' && $logged == 'yes'}
                        {if $item.member.personal_msg == 'yes'}
                    <tr>
                        <td align="center">
                        <a href="./?view=account&page=messages&to={$item.member.id}#tab-2"><img src="images/forum/mail.gif" border="0" /></a>
                        </td>
                    </tr>
                        {/if}
                    {/if}
                    {if $ite.member.forum_role != 1 && $item.member.forum_role != 3}
                    <tr>
                        <td>
                            <img src="images/memberbar/forumf.png" /> {$item.frmgroup.name}
                        </td>
                    </tr>
                    {/if}
                    <tr>
                        <td>
                        <img src="images/memberbar/forumf.png" />
                            {$lang.txt.posts}: {$item.member.forum_posts} 
                        </td>
                    </tr>
                    {if $item.member.forum_stats == 'yes' && $item.member.forum_role!=1}
                    <tr>
                        <td>
                        <img src="images/memberbar/forumf.png" />
                            {$lang.txt.received}: {$item.member.withdraw} تومان
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <img src="images/memberbar/forumf.png" />
                            {$lang.txt.balance}: {$item.member.money} تومان
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <img src="images/memberbar/forumf.png" />
                            {$lang.txt.referrals}: {$item.member.referrals+$item.member.rented_referrals}
                        </td>
                    </tr>
                    {/if}
                </table>
                </div>
                </td>
                <td valign="top">
                
                {if $item.member.forum_role == 4}
                    {$lang.txt.forumbanned}<br /><strong>{$settings.site_name} Team.</strong>
                {else}
                    {$item.message}
                {/if}
                </td>
            </tr>
            <tr>
                <td valign="bottom">
            {if $item.member.forum_role != 4}
                {if $item.edited == 1}
                    <div class="topic_edited">&laquo; {$lang.txt.lastedit}: 
                
                {if $item.edit_date|date_format == $smarty.now|date_format}
                    <strong>{$lang.txt.todayat}</strong> {$item.edit_date|date_format:"%H:%M"}
                {elseif $item.edit_date|date_format == $yesterday|date_format}
                    <strong>{$lang.txt.yesterdayat}</strong> {$item.edit_date|date_format:"%H:%M"}
                {else}
                    <strong>{$item.edit_date|date_format:"%Y-%m-%d ساعت %H:%M"}</strong>
                {/if}
                by {$item.edited_author} &raquo
                    </div>
                {/if}
            {/if}


            
            {if $settings.forum_signature == 'yes'}
            	{if $item.member.forum_signature}
                <div class="forum_signature">
                	{$item.member.forum_signature}
                </div>
                {/if}
            {/if}
                </td>
            </tr>
            <tr>
            	<td colspan="2">
            {if $logged=='yes'}
                <div class="forum_topic_footer">
                {if $frm_topic.locked != 1 || $user_info.forum_role == 1}
                	{if $user_info.forum_role !=4}
                    <input type="button" value="{$lang.txt.quote}" onclick="location.href='{$uri}topic={$frm_topic.id}&post={$item.id}&do=reply';" class="forumbtn" />
                    {/if}
                    {if ($user_group.caneditownpost == 'yes' && $item.member.username == $user_info.username) ||
                    ($user_group.caneditotherspost == 'yes' && $item.member.username != $user_info.username)}
                    <input type="button" value="{$lang.txt.edit}" onclick="location.href='{$uri}topic={$frm_topic.id}&post={$item.id}&do=edit';" class="forumbtn" />
                    {/if}
                    {if ($user_group.candeleteownpost == 'yes' && $item.member.username == $user_info.username) ||
                    ($user_group.candeleteotherspost == 'yes' && $item.member.username != $user_info.username)}
                    <input type="button" value="{$lang.txt.delete}" onclick="forum_postdelete('{$item.id}');" class="forumbtn" />
                    {/if}
                {/if}
                {if $user_group.canbanmembers == 'yes' && $item.member.username != $user_info.username}
                	<input type="button" value="{if $item.frmgroup.id == 4}{$lang.txt.unban}{else}{$lang.txt.ban}{/if}" onclick="location.href='{$uri}ban={$item.member.id}';" class="forumbtn" />
                {/if}
                {if $user_group.cansuspendmember == 'yes' && $item.member.username != $user_info.username}
                	{if $item.member.status != 'Suspended'}
                	<input type="button" value="{$lang.txt.suspend}" onclick="location.href='{$uri}suspend={$item.member.id}';" class="forumbtn" />
                    {/if}
                {/if}
                </div>
            {/if}
                </td>
            </tr>
        </table>
        </div>
    </div>
    {/foreach}
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
            {if $frm_topic.locked != 1 || $user_info.forum_role == 1}
            	{if $user_info.forum_role != 4}
                <input type="button" class="forumbtn" value="{$lang.txt.reply}" onclick="location.href='{$uri}topic={$frm_topic.id}&do=reply';" />
                {/if}
        	{/if}
        	{if 
	        ($user_group.canopenclosetopics == 'yes' && $frm_topic.author != $user_info.username) || 
            ($user_group.canopencloseowntopics == 'yes' && $frm_topic.author == $user_info.username)        
    	    }
            <input type="button" class="forumbtn" onclick="forum_openclosetopic('{$frm_topic.id}');" value="{if $frm_topic.locked == 0}{$lang.txt.locktopic}{else}{$lang.txt.unlocktopic}{/if}" />
        	{/if}
            
        	{if 
	        ($user_group.canmoveotherstopic == 'yes' && $frm_topic.author != $user_info.username) || 
            ($user_group.canmoveowntopics == 'yes' && $frm_topic.author == $user_info.username)        
    	    }     
        <input type="button" class="forumbtn" onclick="location.href='{$uri}movetopic={$frm_topic.id}';" value="{$lang.txt.movetopic}" />       
            {/if}
        {/if}
        </div>
        <div class="clear"></div>
</div>



<div id="dialog" title="Confirm"></div>
{include file="footer.tpl"}