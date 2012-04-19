{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Delete student(s){/block}

{block name="page_head"}
{*<link type="text/css" rel="stylesheet" href="{$baseUrl}css/base_style.css">*}
{*<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />*}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/settings.css" />
{*<link type="text/css" rel="stylesheet" href="{$baseUrl}css/schedule_meeting.css">*}
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
    <form method="POST" action="{$baseUrl}post.php">
        <input type="hidden" name="source" value="delete_student">
        <input type="hidden" name="token" value="{$token}" />

        <h1>Delete student(s)</h1>
        <br />
     
        {if $students}
        <div class="padded">
            {foreach $students as $s}
            <input type="checkbox" name="students[]" value={$s["PersonID"]}>&nbsp; {$s["FirstName"]} {$s["LastName"]}<br />
            {/foreach}
        </div><br />

        <center>
            <input type="submit" value="Submit" id="submit" name="submit" />
        </center>
        {else}
            <p><b>No students available.</b></p>
        {/if}

        <div class="spacer"></div>
    </form>
</div>
{/block}
