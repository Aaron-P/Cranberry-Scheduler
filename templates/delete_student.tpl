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
     
        {if $people}
        <label class="label">Class:<br />
        <span class="small">&nbsp;</span>
        </label>
        <select id="location" class="input" name="courseID">
                <option selected="selected" value="">-- Select course --</option>
                {foreach $courses as $c}
                <option value="{$c["CourseID"]}">{$c["CourseName"]}</option>
                {/foreach}
        </select><br />
        
        <label class="label">People:<br />
        <span class="small">&nbsp;</span>
        </label>
        <select multiple size=12 name="people[]">
            {foreach $people as $p}
            <option value={$p["PersonID"]}>{$p["FirstName"]} {$p["LastName"]}
            {/foreach}
        </select><br /><br />

        <label class="label">Revise permissions:<br />
        <span class="small">&nbsp;</span>
        </label>
        <input type="checkbox" name="researcher">&nbsp; Researcher
        <input type="checkbox" name="teacher">&nbsp; Teacher
        <br />

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
