{if $settings.forum_search == 1 || ($logged == yes && $settings['forum_search'] == 2)}
<div class="forum_search">
<form method="post" action="./forum.php?page=search">
<input type="hidden" name="do" value="search" />
<input type="text" name="search_word" class="frm_search_txt" /> 
<input type="hidden" />
<input type="submit" name="btn" value="{$lang.txt.search}" class="frm_search_btn" />
</div>
{/if}