<!-- Content -->
<div class="widget-main-title">{$lang.txt.ptsu}</div>
<div class="widget-content">
    <div id="message_sent" style="display:none">
    {$lang.txt.ptsu_received}
    <form class="formclass" id="redirectme">
    <input type="button" name="btn" value="{$lang.txt.continuebutton}" onclick="location.href='./?view=account&page=ptsu'" />
    </form>
    </div>
    
    {if $myads != 0}
    <form class="formclass" id="submitptsu" onsubmit="return submitform(this.id);" method="post">
    <input type="hidden" name="action" value="submit" />
    <div class="info_box">{$lang.txt.cheatmsg}</div>
    <table class="widget-tbl" width="100%">
        <tr>
            <td align="right" width="300">{$lang.txt.link}:</td>
            <td><strong><a href="{$ptsu_details.url}" target="_blank">{$ptsu_details.url}</a></strong> <div style="font-size:10px">({$lang.txt.ptsu_descr1})</div>
            </td>                
        </tr>
        <tr>
            <td align="right">{$lang.txt.ptsu_descr2}:</td>
            <td><strong style="color:#009900">{$ptsu_details.value} تومان</strong></td>
        </tr>
        <tr>
            <td align="right">{$lang.txt.instructions}:</td>
            <td>{$ptsu_details.instructions}</td>
        </tr>
        <tr>
            <td align="right">{$lang.txt.ptsu_descr3}:</td>
            <td><input type="text" name="username" /></td>
        </tr>
        <tr>
            <td align="right">{$lang.txt.ptsu_descr4}:</td>
            <td><textarea name="message" style="width:95%; height:100px"></textarea></td>
        </tr>
        <tr>
        	<td></td>
            <td><input type="submit" name="btn" value="{$lang.txt.send}" />
            <input type="button" name="btn" value="{$lang.txt.return}" onclick="history.back();"/>
            </td>
        </tr>
    </table>
    </form>        
        
    {else}
        <strong>   {$lang.txt.adnotfound}</strong>
        <p>{$lang.txt.noadsfoundmsg}</p>
    {/if}

</div>
<!-- End Content -->
