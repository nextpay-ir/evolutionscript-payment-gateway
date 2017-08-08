{include file="header.tpl"}
<!-- Content -->
<div class="PageTitle"><h1>{$lang.txt.resendactivationtitle}</h1></div>
<div class="site_content">
   <form method="post" id="recoveryform" onsubmit="return submitform(this.id);">
   <table cellpadding="4" width="400" align="center">
   	<tr>
    	<td align="right">{$lang.txt.username}:</td>
        <td><input type="text" name="username" style="width:100%" /></td>
    </tr>
   	<tr>
    	<td align="right">{$lang.txt.email}:</td>
        <td><input type="text" name="email" style="width:100%" /></td>
    </tr>
    <tr>
    	<td></td>
    	<td>
        <input type="hidden" name="a" value="submit" />
        <input type="hidden" name="class" value="activation" />
        <input type="submit" name="send" value="{$lang.txt.send}" />
        </td>
    </tr>
   </table>
   </form>
    
</div>    
<!-- End Content -->
{include file="footer.tpl"}