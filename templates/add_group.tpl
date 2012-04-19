{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Add group{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
    <form method="POST" action="{$baseUrl}post.php">
        <input type="hidden" name="source" value="add_group" />
        <input type="hidden" name="token" value="{$token}" />

        <h1>Add group</h1><br />

        <label class="label">Group name:<br />
        <span class="small">&nbsp;</span>
        </label>
        <input type="text" name="name" id="name" /><br />

        <label class="label">People:<br />
        <span class="small">&nbsp;</span>
        </label>
        <select multiple size=12 name="people[]">
            {foreach $people as $p}
            <option value={$p["PersonID"]}>{$p["FirstName"]} {$p["LastName"]}
            {/foreach}
        </select>
        <br /><br />

        <center>
            <input type="submit" value="Create" name="submit" id="submit" />
            <input type="submit" value="Cancel" name="cancel" id="submit" />
        </center>
    </form>
    <div class="spacer"></div>
</div>

{/block}
