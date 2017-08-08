{include file="header.tpl"}
<div class="forum_shortlinks">
    <a href="{$uri}">{$lang.txt.forum} {$settings.site_name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}cat={$frm_category.id}">{$frm_category.name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}board={$frm_board.id}">{$frm_board.name}</a>
</div>

<h1 class="forum_title">{$lang.txt.newtopic}: {$frm_board.name}</h1>
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
<input type="hidden" name="board" id="board" value="{$frm_board.id}" />
<input type="hidden" name="a" value="doit" />
<table width="100%" cellpadding="4" class="widget-tbl">
	<tr>
    	<td valign="top" width="220">
        <div id="bbcodearea">
        </div>
        </td>
        <td class="forum_cell" valign="top">
        	<div>{$lang.txt.topictitle}:</div>
            <div><input type="text" name="topic_title" id="topic_title" style="width:90%" /></div>
            <div>{$lang.txt.description}:</div>
            <div style="margin-bottom:5px"><input type="text" name="topic_descr" id="topic_descr" style="width:90%" /></div>
        
         <textarea style="width:95%; height:200px" id="textarea" name="message"></textarea></div>
            <div class="padding5">
            	<input type="submit" name="btn" value="{$lang.txt.newtopic}" />
                <input type="button" name="btn" value="{$lang.txt.preview}" onclick="forum_preview();" />
            </div>
            {if $user_info.forum_role == 1}
            <div class="padding5">
            	<div><input type="checkbox" name="sticky" value="1" id="sticky" /> {$lang.txt.sticktopic}.</div>
                <div><input type="checkbox" name="locked" value="1" id="locked" /> {$lang.txt.locktopic}.</div>
            </div>
            {/if}
        </td>
    </tr>
</table>
</form>
</div>






{include file="footer.tpl"}