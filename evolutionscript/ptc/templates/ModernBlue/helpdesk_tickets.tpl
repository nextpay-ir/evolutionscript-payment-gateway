{include file="header.tpl"}
<!-- Content -->
<div class="site_title">{$settings.site_name} {$lang.txt.support}</div>
<div class="site_content">
{if $helpdesk_enable != 'yes'}
{$lang.txt.supportcenterdisabled}
{else}
	<div class="widget-title">{$lang.txt.ticketinformation}</div>
    <div class="widget-content">
<table width="100%" class="widget-tbl">
	<tr>
    	<td width="150" align="right"><strong>{$lang.txt.ticketid}:</strong></td>
        <td>{$ticket_info.ticket}</td>
    </tr>
	<tr>
    	<td align="right"><strong>{$lang.txt.ticketadded}:</strong></td>
        <td>{$ticket_info.date|date_format:"%A %B %e %Y در ساعت %I:%M %p"}</td>
    </tr>
	<tr>
    	<td align="right"><strong>{$lang.txt.ticketstatus}:</strong></td>
        <td>{$ticket_info.status_name}</td>
    </tr>
	<tr>
    	<td align="right"><strong>{$lang.txt.ticketsubject}:</strong></td>
        <td>{$ticket_info.subject}</td>
    </tr>
</table>  
    </div>
    
    <div class="widget-title">{$lang.txt.conversation}</div>

<fieldset class="ticket-user">
	<legend>You, {$ticket_info.date|date_format:"%A %B %e %Y در ساعت %I:%M %p"}</legend>
    {$ticket_info.message}
</fieldset>    

{section name=r loop=$reply_info}
  {if $reply_info[r].user_reply != 0}
<fieldset class="ticket-user">  
  <legend>You, {$reply_info[r].date|date_format:"%A %B %e %Y در ساعت %I:%M %p"}</legend>
	{else}
<fieldset class="ticket-admin">  
  <legend><strong>Administrator, {$reply_info[r].date|date_format:"%A %B %e %Y در ساعت %I:%M %p"}</strong></legend>
    {/if}
{$reply_info[r].message}
</fieldset>
{/section}  
    
 {if $ticket_info.status != 4}
<form method="post" id="frmreply" onsubmit="return submitform(this.id);">
<input type="hidden" name="ticketid" id="ticketid" value="{$ticket_info.ticket}">
<input type="hidden" name="action" value="reply">
<div align="center" style="padding-top:10px; padding-bottom:5px;">
	<div style="padding-bottom:5px">
	<textarea style="width:90%; height:100px" name="message" id="hdmessage"></textarea>
    </div>
    <input type="submit" name="sent" value="{$lang.txt.reply}">
    <input type="button" name="btn" value="بازگشت" onclick="history.back();" />
</div>
</form>
{/if}


{/if}


</div>


<!-- End Content -->
{include file="footer.tpl"}