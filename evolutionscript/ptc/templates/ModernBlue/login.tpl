{include file="header.tpl"}
<div class="PageTitle"><h1>{$lang.txt.loginacc}</h1></div>
<div class="site_content">
<div style="width:600px; margin:0 auto;">
<div class="widget-title">{$lang.txt.loginacc}</div>
<form id="loginform" method="post" onsubmit="return submitform(this.id);">
<input type="hidden" name="token" value="{getToken('login')}" />
<input type="hidden" name="a" value="submit" />
	<table width="100%" class="widget-tbl">
    	<tr>
        	<td align="right" width="200">{$lang.txt.username}:</td>
            <td><input dir="ltr" type="text" name="username" size="40" /></td>
		</tr>
        <tr>
            <td align="right">{$lang.txt.password}:</td>
            <td><input dir="ltr" type="password" name="password" size="40" /></td>
        </tr>
        <tr>
            <td colspan="2">
            	<div style="width:360px; margin:0 auto;">
            {literal}
            <ul id="keyboard">
                <li class="symbol"><span class="off">`</span><span class="on">~</span></li>
                <li class="symbol"><span class="off">1</span><span class="on">!</span></li>
                <li class="symbol"><span class="off">2</span><span class="on">@</span></li>
                <li class="symbol"><span class="off">3</span><span class="on">#</span></li>
                <li class="symbol"><span class="off">4</span><span class="on">$</span></li>
                <li class="symbol"><span class="off">5</span><span class="on">%</span></li>
                <li class="symbol"><span class="off">6</span><span class="on">^</span></li>
                <li class="symbol"><span class="off">7</span><span class="on">&amp;</span></li>
                <li class="symbol"><span class="off">8</span><span class="on">*</span></li>
                <li class="symbol"><span class="off">9</span><span class="on">(</span></li>
                <li class="symbol"><span class="off">0</span><span class="on">)</span></li>
                <li class="symbol"><span class="off">-</span><span class="on">_</span></li>
                <li class="symbol"><span class="off">=</span><span class="on">+</span></li>
                <li class="delete lastitem">delete</li>
                <li class="tab">tab</li>
                <li class="letter">q</li>
                <li class="letter">w</li>
                <li class="letter">e</li>
                <li class="letter">r</li>
                <li class="letter">t</li>
                <li class="letter">y</li>
                <li class="letter">u</li>
                <li class="letter">i</li>
                <li class="letter">o</li>
                <li class="letter">p</li>
                <li class="symbol"><span class="off">[</span><span class="on">{</span></li>
                <li class="symbol"><span class="off">]</span><span class="on">}</span></li>
                <li class="symbol lastitem"><span class="off">\</span><span class="on">|</span></li>
                <li class="capslock">caps lock</li>
                <li class="letter">a</li>
                <li class="letter">s</li>
                <li class="letter">d</li>
                <li class="letter">f</li>
                <li class="letter">g</li>
                <li class="letter">h</li>
                <li class="letter">j</li>
                <li class="letter">k</li>
                <li class="letter">l</li>
                <li class="symbol"><span class="off">;</span><span class="on">:</span></li>
                <li class="symbol"><span class="off">'</span><span class="on">&quot;</span></li>
                <li class="return lastitem">return</li>
                <li class="left-shift">shift</li>
                <li class="letter">z</li>
                <li class="letter">x</li>
                <li class="letter">c</li>
                <li class="letter">v</li>
                <li class="letter">b</li>
                <li class="letter">n</li>
                <li class="letter">m</li>
                <li class="symbol"><span class="off">,</span><span class="on">&lt;</span></li>
                <li class="symbol"><span class="off">.</span><span class="on">&gt;</span></li>
                <li class="symbol"><span class="off">/</span><span class="on">?</span></li>
                <li class="right-shift lastitem">shift</li>
                <li class="space lastitem">&nbsp;</li>
              </ul>
              {/literal}              
              </div>
              </td>
        </tr>
        {if $settings.captcha_login == 'yes'} 
        	{if $settings.captcha_type == 1}
            <tr>
                <td></td>
                <td>{$captcha_login}</td>
            </tr>
            <tr>
                <td></td>
                <td><input style="text-align:center" type="text" name="captcha" id="captcha_login" size="40" /></td>
            </tr>
        	{else}
            <tr>
            	<td colspan="2" align="center">{$captcha}
                </td>
            </tr>            
            {/if}
        {/if}
        <tr>
        	<td></td>
            <td><input type="submit" name="login" value="{$lang.txt.login}" /></td>
        </tr>
        <tr>
        	<td colspan="2" align="center">
            <a href="./?view=forgot">{$lang.txt.forgotpassword}</a>
            {if $settings.register_activation == 'yes'}
            &nbsp;&nbsp;&bull;&nbsp;&nbsp; <a href="./?view=forgot&page=resend_activation">{$lang.txt.resendactivation}</a>
            {/if}
            </td>
        </tr>
    </table>

</form>
 
</div>
{literal}       
<script type="text/javascript">
$(function(){
loginkeyboard();		
});</script>       
{/literal}

</div>
<!-- End Content -->
{include file="footer.tpl"}