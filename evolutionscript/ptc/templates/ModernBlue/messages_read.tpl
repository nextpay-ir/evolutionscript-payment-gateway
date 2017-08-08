<!-- Content -->
<div class="widget-main-title">{$lang.txt.message_center}</div>
<div class="widget-content">
    <table width="100%" class="widget-tbl">
        <tr>
            <td width="100" align="right"><strong>{$lang.txt.from}:</strong></td>
            <td>{$msg_info.user_from}</td>
        </tr>
        <tr>
            <td align="right"><strong>{$lang.txt.subject}:</strong></td>
            <td>{$msg_info.subject}</td>
        </tr>
        <tr>
            <td align="right"><strong>{$lang.txt.date}:</strong></td>
            <td>{$msg_info.date|date_format:"%e %B %Y %r"}</td>
        </tr>
    </table>
<div  style="margin-top:5px"></div>
<div style class="widget-content">{$msg_info.message}</div>
<form class="formclass">
    <input type="button" name="btn" value="{$lang.txt.reply}" onclick="location.href='./?view=account&page=messages&reply={$msg_info.id}#tab-2'; "class="buttonblue" />
    
    <input type="button" name="btn" value="{$lang.txt.quote}" onclick="location.href='./?view=account&page=messages&quote={$msg_info.id}#tab-2';" class="buttonblue" />
    
    <input type="button" name="btn" value="{$lang.txt.delete}" onclick="location.href='./?view=account&page=messages&read={$msg_info.id}&do=delete';" class="buttonblue" />
    <input type="button" name="btn" value="{$lang.txt.return}" onclick="history.back();" />
</form>
</div>
        


<!-- End Content -->