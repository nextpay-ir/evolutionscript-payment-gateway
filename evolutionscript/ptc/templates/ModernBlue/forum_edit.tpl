{include file="header.tpl"}
<div class="forum_shortlinks">
    <a href="{$uri}">{$lang.txt.forum} {$settings.site_name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}cat={$frm_category.id}">{$frm_category.name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}board={$frm_board.id}">{$frm_board.name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}topic={$frm_topic.id}">{$frm_topic.title}</a>
</div>
<h1 class="forum_title">{$lang.txt.edit}</h1>

<link href="images/forum/bbcode/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="images/forum/bbcode/set.js"></script>
<script type="text/javascript" src="images/forum/bbcode/jquery.markitup.js"></script>
<script>
$(function(){
	$("#textarea").markItUp(mySettings);
    $('#emoticons a').click(function() {
        emoticon = $(this).attr("title");
        $.markItUp( { replaceWith:emoticon } );
    });

});
</script>


<div class="widget-content">
<form method="post" onsubmit="return submitform(this.id);" id="massage_form">
<input type="hidden" name="a" value="doit" />
<input type="hidden" name="post" id="post" value="{$frm_quote.id}" />
<input type="hidden" name="topic" id="topic" value="{$frm_topic.id}" />
<div id="errorbox" class="errorbox" style="display:none"></div>
<table width="100%" class="widget-tbl">
	<tr>
    	<td valign="top" width="220">
        <div id="bbcodearea">
        </div>

        </td>
        
        <td valign="top">
        	{if $frm_topic.id == $frm_quote.id}
        	<div> {$lang.txt.topictitle}:</div>
            <div><input type="text" name="topic_title" id="topic_title" value="{$frm_quote.title}" style="width:90%" /></div>
            <div>{$lang.txt.description}:</div>
            <div style="margin-bottom:5px"><input type="text" name="topic_descr" id="topic_descr" value="{$frm_quote.descr}" style="width:90%" /></div>
        	{/if}
        	<textarea name="message" style="width:95%; height:200px" id="textarea">{$frm_quote.message}</textarea>
            <div class="padding5">
            	<input type="submit" name="btn" value="{$lang.txt.edit}" />
                <input type="button" name="btn" value="{$lang.txt.preview}" onclick="forum_preview();" />
            </div>
            {if $user_info.forum_role == 1 && $frm_quote.topic=='yes'}
            <div class="padding5">
            	<div><input type="checkbox" name="sticky" value="1" id="sticky" {if $frm_quote.sticky == 1}checked{/if} /> {$lang.txt.sticktopic}.</div>
                <div><input type="checkbox" name="locked" value="1" id="locked" {if $frm_quote.locked == 1}checked{/if} /> {$lang.txt.locktopic}.</div>
            </div>
            {/if}
        </td>
    </tr>
</table>
</form>

</div>



 


{include file="footer.tpl"}