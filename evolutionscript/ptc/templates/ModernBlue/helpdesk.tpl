{include file="header.tpl"}
<!-- Content -->
<div class="PageTitle"><h1>{$settings.site_name} :: {$lang.txt.support}</h1></div>
<div class="site_content">
{if $helpdesk_enable != 'yes'}
{$lang.txt.supportcenterdisabled}
{else}

{if $smarty.get.t == 'new'}
<form method="post" onsubmit="return submitform(this.id);" id="helpdeskform">
<input type="hidden" name="action" value="open" />
<table width="100%" class="widget-tbl">
	{if $logged != 'yes'}
	<tr>
    	<td align="right">{$lang.txt.fullname}:</td>
        <td><input type="text" name="name" id="hdname" /></td>
	</tr>
	<tr>
    	<td align="right">{$lang.txt.email}:</td>
        <td><input type="text" name="email" id="hdemail" /></td>
	</tr>
    {/if}
    <tr>
    	<td width="150" align="right">{$lang.txt.department}:</td>
        <td>
			<select name="department" id="departmentshd">
{section name=d loop=$daparment}
	<option value="{$daparment[d].id}" class="whoo">{$daparment[d].name}</option>
{/section} 
			</select>
        </td>
    </tr>
	<tr>
    	<td align="right">{$lang.txt.subject}:</td>
        <td><input type="text" name="subject" id="hdsubject" />		</td>
	</tr>
    <tr>
    	<td colspan="2" align="center">
        <textarea name="message" id="hdmessage" style="width:95%; height:200px"></textarea>
        </td>
    </tr>
    {if $settings.captcha_contact=='yes' && $settings.captcha_type != 0}
    <tr>
    	<td align="right" valign="top">{$lang.txt.imgverification}:</td>
        <td>{$captcha}</td>
    </tr>
    {/if}
    <tr>
    	<td></td>
        <td><input type="submit" name="send" value="{$lang.txt.send}"> <input type="button" name="btn" value="بازگشت" onclick="history.back();" /></td>
    </tr>
</table>
</form>
          
{else}


{if $logged != 'yes'}

<p>با سلام خدمت شما . در صورتی که نیاز به ارسال پیام و یا پشتیبانی دارید میتوانید از باکس سمت راست استفاده نمایید و اگر قبلا تیکتی ارسال کرده اید ، برای پیگیری آن از باکس سمت چپ استفاده نمایید . تشکر</p>

<form method="post" id="checkticketsfrm" onsubmit="return submitform(this.id);">
<input type="hidden" name="do" value="checkticket" />
<table>
	<tr>
    	<td width="50%" valign="top">
        	<div class="widget-title">{$lang.txt.opennewticket}</div>
            <div class="widget-content" style="height:130px">
                <div class="open-ticket-box">
                    <div>{$lang.txt.opennewticketdescr}</div>
                    <div><input type="button" onclick="location.href='./?view=contact&t=new';" value="{$lang.txt.opennewticket}" />
                </div>
            </div>
        </td>
        <td valign="top">
        	<div class="widget-title">{$lang.txt.checkticketstatus}</div>
            <div class="widget-content" style="height:130px">
        	<div class="view-ticket-box">
                <div style="padding-bottom:10px;">برای پیگری تیکت ارسالی خود کافیست ID آن را وارد نموده و دکمه زیر استفاده نمایید </div>
                <div>
                                <table>
                	<tr>
                    	<td>{$lang.txt.ticketid}:</td>
                        <td><input type="text" name="ticketid" id="ticketid" class="input_txt"></td>
                    </tr>
                    <tr>
                    	<td></td>
                    	<td>
                        	<input type="submit" name="send" value="{$lang.txt.viewticket}">
                        </td>
                    </tr>
                </table>
                </div>
            </div>
            </div>
        </td>
    </tr>
</table>
</form>
{else}
<script type="text/javascript">
$(function(){
	$("#tablelist tr:even").addClass("tr1");
	$("#tablelist tr:odd").addClass("tr2");
});
</script>
<div class="widget-content">
	<table width="100%" class="tbl-content">
    	<tr>
        	<td width="20"><span class="system-icon application_form_add"></span></td>
            <td width="200"><a href="/?view=contact&t=new"><strong>ارسال تیکت پشتیبانی &raquo;</strong></a></td>
            <td>تیکت های پشتیبانی بعد از بررسی در اسرع وقت پاسخ داده خواهند شد</td>
        </tr>
        <tr>
        	<td><span class="system-icon flag_orange"></span></td>
            <td><a href="/?view=contact&sort=2">مشاهده تیکت های فعال شما</a></td>
            <td>تیکت هایی که تازه پاسخ داده شدند یا پاسخ داده اید.</td>
        </tr>
        <tr>
        	<td><span class="system-icon flag_green"></span></td>
            <td><a href="/?view=contact&sort=1">نمایش تیکت های باز</a></td>
            <td>تیکت های باز به دلیل عدم رفع مشکل شما.</td>
        </tr>
        <tr>
        	<td><span class="system-icon flag_red"></span></td>
            <td><a href="/?view=contact&sort=3">نمایش تیکت های بسته</a></td>
            <td>تیکت های بسته به دلیل بر طرف شدن مشکل شما.</td>
        </tr>
    </table>
</div>
<div class="widget-title">تیکت های من</div>
<table width="100%" class="widget-tbl">
	<tr class="titles">
        <td align="center">ID تیکت</td>
        <td align="center">موضوع</td>
        <td align="center">آخرین بروزرسانی</td>
        <td align="center">وضعیت</td>
    </tr>
    <tbody id="tablelist">
        {foreach item=item from=$thelist}
            <tr>
                <td align="center"><a href="/?view=contact&view_ticket={$item.ticket}">{$item.ticket}</a></td>
                <td align="center">{$item.subject}</td>
                <td align="center">{$item.last_update|date_format:"%e %B %Y %r"}</td>
                <td align="center">
                {if $item.status == 1}
                	<span style="color:green">باز</span>
               {elseif $item.status == 2}
                	<span style="color:#000000">پاسخ داده شده</span>
               {elseif $item.status == 3}
                	<span style="color:#ff6600">در انتظار پاسخ</span>
               {elseif $item.status == 4}
                	<span style="color:#888888">بسته</span>
               	{/if}
                </td>
            </tr>
        {/foreach}    
    </tbody>
        {if $paginator->totalResults() == 0}
        <tr>
            <td colspan="10" align="center">موردی یافت نشد</td>
        </tr>
        {/if}

</table>

<div style="margin-top:10px">
    <input type="button" value="&rarr; {$lang.txt.prev_page}" {if $paginator->totalPages() == 1 || $paginator->getPage()==1}disabled class="btn-disabled"{else}onclick="location.href='{$paginator->prevpage()}';"{/if} />

    <input type="button" value="{$lang.txt.next_page} &larr;" {if $paginator->totalPages() == 0 || $paginator->totalPages() == $paginator->getPage()}disabled class="btn-disabled"{else}onclick="location.href='{$paginator->nextpage()}';"{/if} />
    	{if $paginator->totalPages() > 1}
        <div style="float:right">
        {$lang.txt.jump_page}: 
        <select name="p" style="min-width:inherit;" id="pagid" onchange="gotopage(this.value)">
           {for $i=1 to $paginator->totalPages()}
				{if $i == $paginator->getPage()}
                	<option selected value="{$paginator->gotopage($i)}">{$i}</option>
				{else}
					<option value="{$paginator->gotopage($i)}">{$i}</option>
				{/if}
            {/for}
        </select> 
        <script type="text/javascript">
			function gotopage(pageid){
				location.href=pageid;
			}
		</script>
        </div> 
        <div class="clear"></div>
        {/if}
    </div>
{/if}

{/if}

{/if}

</div>
<!-- End Content -->
{include file="footer.tpl"}