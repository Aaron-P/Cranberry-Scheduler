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
        <label class="label">SIUE e-id:<br />
        <span class="small">&nbsp;</span>
        </label>
        <input type="text" name="eid" id="name" /><br />

        <label class="label">First name:<br />
        <span class="small">&nbsp;</span>
        </label>
        <input type="text" name="firstName" id="name" /><br />

        <label class="label">Last name:<br />
        <span class="small">&nbsp;</span>
        </label>
        <input type="text" name="lastName" id="name" /><br />

        <label class="label">Type:<br />
        <span class="small">&nbsp;</span>
        </label>
        <input type="checkbox" name="volunteer" checked>&nbsp; Volunteer
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
