{include file="header.tpl"}
<div class="forum_shortlinks">
    <a href="{$uri}">{$lang.txt.forum} {$settings.site_name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}cat={$frm_category.id}">{$frm_category.name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}board={$frm_board.id}">{$frm_board.name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}topic={$frm_topic.id}">{$frm_topic.title}</a>
</div>

<h1 class="forum_title">{$lang.txt.reply}: {$frm_topic.title}</h1>
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
<form method="post" onsubmit="return submitform(this.id);" id="massage_form" class="formclass">
<input type="hidden" name="a" value="doit" />
<input type="hidden" name="topic" id="topic" value="{$frm_topic.id}" />
<table width="100%" cellpadding="4" class="widget-tbl">
	<tr>
    	<td id="preview" style="display:none" class="forum_cell" colspan="2">
 	<div class="celltop padding5">{$lang.txt.preview}</div> 	
    <div class="cellcontent padding5" style="margin:1px 0px;" id="preview_msg"></div>
		</td>
    </tr>
	<tr>
    	<td valign="top" width="220">
        <div id="bbcodearea">
        </div>
        </td>
        <td class="forum_cell" valign="top">
         <textarea style="width:95%; height:200px" id="textarea" name="message">{if !empty($frm_quote)}[quote source={$frm_quote.author}]{$frm_quote.message}[/quote]{/if}</textarea>
            <div class="padding5">
            	<input type="submit" name="btn" value="{$lang.txt.reply}" />
                <input type="button" name="btn" value="{$lang.txt.preview}" onclick="forum_preview();" />
            </div>
        </td>
    </tr>
</table>
</form>
</div>




{include file="footer.tpl"}