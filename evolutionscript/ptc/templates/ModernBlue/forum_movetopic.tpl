{include file="header.tpl"}
<div class="forum_shortlinks">
    <a href="{$uri}">{$lang.txt.forum} {$settings.site_name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}cat={$frm_category.id}">{$frm_category.name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}board={$frm_board.id}">{$frm_board.name}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}topic={$frm_topic.id}">{$frm_topic.title}</a> &nbsp; &rarr; &nbsp;
    <a href="{$uri}movetopic={$frm_topic.id}">{$lang.txt.movetopic}</a>
</div>


<div class="frm-title">
	{$lang.txt.movetopic}
</div>
<div class="frm-content">
	<div class="forum-topiclist" style="padding:10px;">
        <form method="post" class="formclass" id="moveforumform" onsubmit="return submitform(this.id);">
        <table align="center" cellpadding="5">
            <tr>        
                <td width="100" align="right">{$lang.txt.topic}:</td>
                <td>{$frm_topic.title} (<strong>{$frm_board.name}</strong>)</td>
            </tr>
            <tr>        
                <td align="right">{$lang.txt.moveto}:</td>
                <td>
                    <select id="boards" name="newcategory">
                        <option value="selected">{$lang.txt.selectone}</option>
                        {foreach item=boards from=$boards_list name=foo}
                        <option value="{$boards.id}">{$boards.name} - {$boards.category}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                <input type="submit" name="btn" class="buttonblue" value="{$lang.txt.movetopic}" />
                </td>
            </tr>
        </table>
        </form>
    </div>
</div>
{include file="footer.tpl"}