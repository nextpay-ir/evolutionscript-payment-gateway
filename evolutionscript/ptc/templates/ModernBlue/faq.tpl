{include file="header.tpl"}
<!-- Content -->
<div class="site_title">{$lang.txt.faq_name}</div>
<div class="site_content">
    {foreach $faq as $f}
    {$n = $n+1}
    <div class="faq_num">{$n}</div>
   	<div class="faq">
        <div class="faq_question">
           {$f.question}
        </div>
         <div class="faq_answer">
         {$f.answer}
         </div>    
    </div>
    {/foreach}
</div>
<!-- End Content -->
{include file="footer.tpl"}