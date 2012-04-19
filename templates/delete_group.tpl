{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Delete group(s){/block}

{block name="page_head"}
{*<link type="text/css" rel="stylesheet" href="{$baseUrl}css/base_style.css">*}
{*<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />*}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/settings.css" />
{*<link type="text/css" rel="stylesheet" href="{$baseUrl}css/schedule_meeting.css">*}
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
    <form method="POST" action="{$baseUrl}post.php">
        <input type="hidden" name="source" value="delete_group">
        <input type="hidden" name="token" value="{$token}" />

        <h1>Delete group(s)</h1>
        <br />
     
        {if $groups}
        <div class="padded">
            {foreach $groups as $g}
            <input type="checkbox" name="groups[]" value={$g["TeamID"]}>&nbsp; {$g["TeamName"]}<br />
            {/foreach}
        </div><br />

        <center>
            <input type="submit" value="Submit" id="submit" name="submit" />
        </center>
        {else}
            <p><b>No groups available.</b></p>
        {/if}

        <div class="spacer"></div>
    </form>
</div>
{/block}
