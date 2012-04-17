<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

require_once("classes/DataManager.class.php");
require_once("classes/swift/swift_required.php");

// we need code to get info from the database for reminders

$dataManager = new DataManager();
$reminderInfo = $dataManager->getReminderInfo();

foreach ($reminderInfo AS $reminder)
{
	$reminder["StartTime"] = date("l F jS \\f\\r\\o\\m g:i A", $reminder["StartTime"]);
	$reminder["EndTime"] = date("\\t\\o g:i A", $reminder["EndTime"]);

$test = <<<EOL
To: {$reminder["EmailAddress"]}
Subject: {$reminder["MeetingType"]} Reminder!

{$reminder["FirstName"]} {$reminder["LastName"]},

You have a scheduled {$reminder["MeetingType"]} on {$reminder["StartTime"]} to {$reminder["EndTime"]} at {$reminder["LocationName"]}.
EOL;
echo "<pre>".$test."</pre>";

}


/*
Array
(
    [0] => Array
        (
            [diff] => 13686
            [EmailAddress] => test@siue.edu
            [MeetingType] => Rehearsal
            [Description] =>
            [StartTime] => 2012-04-17 12:00:00
            [EndTime] => 2012-04-17 13:00:00
            [LocationName] => HCI Lab
            [FirstName] => Maybelline
            [LastName] => Agg
        )

)
 */

/*
{MeetingType} Reminder!

{FirstName} {LastName},

You have a scheduled {MeetingType} meeting from {StartTime} to {EndTime} at {Location}.
*/
?>