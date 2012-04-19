{**
 * @copyright University of Illinois/NCSA Open Source License
 *}

<div id="stylized" class="myform">
    <h1>Volunteer opportunities</h1><br />

    {foreach $opportunities as $o}
    <h3>{$o["Date"]}</h3>
    <table border="0">
        <tr>
            <td width="150" valign="top">{$o["Start"]} - {$o["End"]}</td>
            <td>{$o["Description"]}<br /><a href="{$baseUrl}index.php?page=meeting_overview&eventID={$o.MeetingID}">Sign up</a></td>
        </tr>
    </table>
    <br />
    {/foreach}

</div>