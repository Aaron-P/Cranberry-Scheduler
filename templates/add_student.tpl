{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

{extends file="template.tpl"}
{block name="page_title"}Add student{/block}

{block name="page_head"}
<link rel="stylesheet" type="text/css" href="{$baseUrl}css/view_meetings.css" />
{/block}

{block name="page_content"}
<div id="stylized" class="myform">
    <form method="POST" action="{$baseUrl}post.php">
        <input type="hidden" name="source" value="add_student" />
        <input type="hidden" name="token" value="{$token}" />

        <h1>Add student</h1><br />

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

        <label class="label">Type:<br />
        <span class="small">&nbsp;</span>
        </label>
        <input type="checkbox" name="researcher">&nbsp; Researcher
        <input type="checkbox" name="teacher">&nbsp; Teacher
        <br />

        <center>
            <input type="submit" value="Submit" name="submit" id="submit" />
            <input type="submit" value="Cancel" name="cancel" id="submit" />
        </center>
    </form>
    <div class="spacer"></div>
</div>

{/block}
