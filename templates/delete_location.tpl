{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Settings{/block}

{block name="page_head"}
{*<link type="text/css" rel="stylesheet" href="{$baseUrl}css/base_style.css">*}
{*<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />*}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/settings.css" />
{*<link type="text/css" rel="stylesheet" href="{$baseUrl}css/schedule_meeting.css">*}
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
    <form method="POST" action="{$baseUrl}post.php">
        <input type="hidden" name="source" value="delete_location">
        <input type="hidden" name="token" value="{$token}" />

        <h1>Disable Location(s)</h1>
        <br />

        <p>Disabling a location prevents a location from being used to create new events, but all
        currently scheduled events and past events remain. If you would like to delete ALL information
        and events pertaining to this location from the database, please select the delete option
        before submitting. Disabled locations may be re-enabled any time.</p>
        <br />
     
        <div class="padded">
            {foreach $locations as $l}
            <input type="checkbox" name="locations[]" value={$l["LocationID"]} {if $l["IsUsable"] == 0}checked{/if}>&nbsp; {$l["LocationName"]}<br />
            {/foreach}
        </div><br />

        <center>
            Delete selected location(s)?&nbsp;
            <input type="radio" name="delete" value="yes">&nbsp;Yes
            <input type="radio" name="delete" value="no" checked>&nbsp;No, just disable<br />
            <br />
            <input type="submit" value="Submit" id="submit" name="submit" />
        </center>

        <div class="spacer"></div>
    </form>
</div>
{/block}
