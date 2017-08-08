<!-- Content -->
<link href="images/forum/bbcode/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="images/forum/bbcode/set.js"></script>
<script type="text/javascript" src="images/forum/bbcode/jquery.markitup.js"></script>
{literal}
<script>
$(function(){
	$("#textarea").markItUp(mySettings);
    $('#emoticons a').click(function() {
        emoticon = $(this).attr("title");
        $.markItUp( { replaceWith:emoticon } );
    });

});


</script>
{/literal}

<form method="post" onsubmit="return submitform(this.id)" id="massage_form">
<table width="100%" class="widget-tbl">
	<tr>
    	<td valign="top" width="220" rowspan="4">
        <div id="bbcodearea">
        </div>
        </td>

        <td align="right">{$lang.txt.to}:</td>
        <td>{if $usrid != 0}
            <input type="text" disabled="disabled" value="{$user_to}" />
            <input type="hidden" name="user_to_id" value="{$usrid}" />
            {else}
            <input type="text" name="user_to" />
            {/if}
        </td>
   </tr>
   <tr>
		<td align="right">{$lang.txt.subject}:</td>
        <td><input type="text" name="subject" value="{$subject}" /></td>
	</tr>
    <tr>
    	<td align="right">{$lang.txt.message}:</td>
        <td><textarea name="message" style="width:95%; height:100px" id="textarea">{if !empty($message)}[quote source={$user_to}]{$message}[/quote]{/if}</textarea></td>
	</tr>
    <tr>
        <td colspan="2" align="center">
            	<input type="hidden" name="do" value="send" />
            	<input type="submit" name="btn" value="{$lang.txt.send}" />
                <input type="button" name="btn" value="{$lang.txt.preview}" onclick="forum_preview();" />

        </td>
    </tr>
</table>
</form>



<!-- End Content -->