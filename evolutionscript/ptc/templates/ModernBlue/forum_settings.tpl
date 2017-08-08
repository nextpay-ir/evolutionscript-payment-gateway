<!-- Content -->
{if $settings.forum_signature == 'yes'}
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
{/if}
<div class="widget-main-title">{$lang.txt.forum_settings}</div>
<div class="widget-content">

<form id="settingsform" onsubmit="return submitform(this.id);">
<input type="hidden" name="a" value="submit" />
                <table cellpadding="4" width="100%" class="widget-tbl">
                <tr>
                    <td align="right" width="300">{$lang.txt.forum_avatar}:</td>
                    <td><input type="text" name="forum_avatar" id="forum_avatar" value="{$user_info.forum_avatar}" /></td>
                </tr>
                <tr>
                    <td align="right">{$lang.txt.show_statistics}:</td>
                    <td>
                            <label><input type="radio" name="forum_stats" value="yes" {if $user_info.forum_stats=='yes'}checked{/if} /> {$lang.txt.yes}</label>
                            <label><input type="radio" name="forum_stats" value="no" {if $user_info.forum_stats=='no'}checked{/if} /> {$lang.txt.no}</label>
                    </td>            
                </tr>
                {if $settings.forum_signature == 'yes'}
                
                <tr>
                    <td colspan="2" class="widget-title">
{$lang.txt.signature}:
                    </td>
                </tr>
                    <td colspan="2">
                    
        <div id="bbcodearea">
        </div>
         <textarea style="width:95%; height:200px" id="textarea" name="forum_signature">{$user_info.forum_signature}</textarea>
                    </td>            
                </tr>
                
                {/if}
                <tr>
                    <td></td>
                    <td>
                    <input type="submit" name="btn" value="{$lang.txt.send}" class="orange" />
                    </td>
                </tr>
                </table>  
</form>

</div>
<!-- End Content -->