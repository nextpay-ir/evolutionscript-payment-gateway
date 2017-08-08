{if $smarty.server.SCRIPT_NAME=='/forum.php'}</div>{/if}

        <div class="processorlist">
        </div>
	</div>
   </div> 
	<div id="footer">
    <div style=" width:1040px; margin:0 auto; ">
        <div class="links">
        <a href="index.php">{$lang.txt.home}</a>
        <a href="index.php?view=faq">{$lang.txt.faq}</a>
        <a href="index.php?view=contact">{$lang.txt.support}</a>
        <a href="index.php?view=terms">{$lang.txt.terms}</a>
        {if $settings.payment_proof == 'yes'}
        <a href="index.php?view=payment_proof">{$lang.txt.paymentproof}</a>
        {/if}
        <a href="index.php?view=news">{$lang.txt.news}</a>
        {if $forum_active == 'yes'}
        <a href="forum.php">{$lang.txt.forum}</a>
        {/if}
        <a style="float:left;" href="http://www.persianscript.ir/">انتشار در پرشین اسکریپت</a>
        </div>
        <div class="clear"></div>
        <div style="float: right;padding: 7px 10px;width: 600px;text-align: right;">
        کلیه حقوق برای وبسایت رسمی هیت باکس محفوظ است و هرگونه کپی برداری پیگرد قانونی خواهد داشت.
        </div>
    </div>
</div>
</div>


</body>
</html>    
    
