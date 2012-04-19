{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Add course{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
    <form method="POST" action="{$baseUrl}post.php">
        <input type="hidden" name="source" value="add_course" />
        <input type="hidden" name="token" value="{$token}" />

        <h1>Add course</h1><br />
        <label class="label">Course name:<br />
        <span class="small">&nbsp;</span>
        </label>
        <input type="text" name="course" id="name" /><br />

        <center>
            <input type="submit" value="Create" name="submit" id="submit" />
            <input type="submit" value="Cancel" name="cancel" id="submit" />
        </center>
    </form>
    <div class="spacer"></div>
</div>

{/block}
