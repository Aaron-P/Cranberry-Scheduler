<?php

require_once("DBHandler.class.php");

class DataManager
{
	// Given an e-id for any team member, return the events for that team
	public function getTeamEvents($eid)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT DISTINCT m.MeetingType,
								m.Description,
								DATE(m.StartTime) AS Date,
								TIME(m.StartTime) AS Start,
								TIME(m.EndTime) AS End,
               					l.LocationName AS Location,
               					m.NumVolunteers,
               					m.RequiredForms
				FROM meeting As m, location AS l, teamperson, person
				WHERE m.TeamID = teamperson.TeamID
				AND l.LocationID = m.LocationID
				AND teamperson.PersonID = person.PersonID
				AND person.Eid = :eid;";

		return $db->query($sql, array(":eid" => $eid));
	}


	public function getPersonInfo($eid)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT Eid, FirstName, LastName
				FROM person
				WHERE Eid = :eid;";

		return $db->query($sql, array(":eid" => $eid));
	}


	public function getVolunteerOpportunities()
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT DISTINCT m.MeetingType,
								m.Description,
								DATE_FORMAT(m.StartTime, '%W, %M %e, %Y') AS Date,
								DATE_FORMAT(m.StartTime, '%l:%i %p') AS Start,
								DATE_FORMAT(m.EndTime, '%l:%i %p') AS End,
               					l.LocationName AS Location,
               					m.RequiredForms
				FROM meeting As m, location AS l
				WHERE l.LocationID = m.LocationID
				AND m.MeetingType = 'Interview'
				AND m.NumVolunteers > (SELECT COUNT(v.MeetingID)
												FROM volunteer AS v
												WHERE v.MeetingID = m.MeetingID);";
		
		return $db->query($sql);
	}


	public function getUpcomingTeamEventsDetailed($eid)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT DISTINCT m.MeetingType,
								DATE_FORMAT(m.StartTime, '%W, %M %e, %Y') AS Date,
								DATE_FORMAT(m.StartTime, '%l:%i %p') AS Start,
								DATE_FORMAT(m.EndTime, '%l:%i %p') AS End,
								m.MeetingID,
								m.Description
				FROM meeting As m, teamperson, person
				WHERE m.TeamID = teamperson.TeamID
				AND teamperson.PersonID = person.PersonID
				AND NOW() < m.StartTime
				AND person.Eid = :eid;";

		return $db->query($sql, array(":eid" => $eid));
	}


	public function getUpcomingTeamEvents($eid)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT DISTINCT m.MeetingType,
								DATE_FORMAT(m.StartTime, '%b %e') AS Date,
								m.MeetingID
				FROM meeting As m, teamperson, person
				WHERE m.TeamID = teamperson.TeamID
				AND teamperson.PersonID = person.PersonID
				AND NOW() < m.StartTime
				AND person.Eid = :eid;";

		return $db->query($sql, array(":eid" => $eid));
	}


	public function getEventInfo($eventID)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT DISTINCT m.MeetingType,
								m.Description,
								DATE_FORMAT(m.StartTime, '%W, %M %e, %Y') AS Date,
								DATE_FORMAT(m.StartTime, '%l:%i %p') AS Start,
								DATE_FORMAT(m.EndTime, '%l:%i %p') AS End,
               					l.LocationName AS Location,
               					m.NumVolunteers,
               					m.RequiredForms
				FROM meeting As m, location AS l
				WHERE m.MeetingID = :eventID
				AND l.LocationID = m.LocationID;";

		return $db->query($sql, array(":eventID" => $eventID));
	}


	public function getMeetingVolunteers($meetingID)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT p.FirstName, p.LastName, p.Eid
				FROM person AS p, meeting AS m, volunteer AS v
				WHERE p.PersonID = v.PersonID
				AND m.MeetingID = :meetingID
				AND m.MeetingID = v.MeetingID;";

		return $db->query($sql, array(":meetingID" => $meetingID));
	}


	// Given an e-id for any team member, return the events for that team
	// that happen between the times $start and $end (UNIX timestamps)
	public function getTeamEventsBetween($eid, $start, $end)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT DISTINCT m.MeetingType AS title,
								UNIX_TIMESTAMP(m.StartTime) AS start,
								UNIX_TIMESTAMP(m.EndTime) AS end,
								m.MeetingID as id
				FROM meeting As m, teamperson, person
				WHERE m.TeamID = teamperson.TeamID
				AND teamperson.PersonID = person.PersonID
				AND person.Eid = :eid
				AND m.StartTime BETWEEN FROM_UNIXTIME(:start) AND FROM_UNIXTIME(:end);";

		return $db->query($sql, array(":eid" => $eid, ":start" => $start, ":end" => $end));
	}


	// Given an e-id for a volunteer, return that volunteer's events
	public function getVolEvents($eid)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT DISTINCT m.Description, 
								DATE(m.StartTime) AS Date,
								TIME(m.StartTime) AS Start,
								TIME(m.EndTime) AS End,
                				l.LocationName AS Location,
                				m.RequiredForms 
				FROM meeting As m, location AS l, volunteer, person
				WHERE m.MeetingID = volunteer.MeetingID
				AND l.LocationID = m.LocationID
				AND volunteer.PersonID = person.PersonID
				AND person.Eid = :eid;";

		return $db->query($sql, array(":eid" => $eid));
	}


	// Given an e-id for any team member and a location, return the events for
	// that team at that location.
	public function getTeamEventsAtLoc($eid, $location)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT DISTINCT l.LocationName AS Location,
								m.MeetingType,
								m.Description, 
               					DATE(m.StartTime) AS Date,
               					TIME(m.StartTime) AS Start,
               					TIME(m.EndTime) AS End				              
				FROM location AS l, meeting AS m, teamperson AS t, person AS p
				WHERE l.LocationID = m.LocationID
				AND m.TeamID = t.TeamID
				AND t.PersonID = p.PersonID 
				AND p.Eid = :eid && l.LocationName = :location;";

		return $db->query($sql, array(":eid" => $eid, ":location" => $location));
	}


	// Given the e-id for a volunteer and a location, return the events for
	// that volunteer at that location.
	public function getVolEventsAtLoc($eid, $location)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT DISTINCT l.LocationName AS Location,
								m.Description,
								DATE(m.StartTime) AS Date,    
                            	TIME(m.StartTime) AS Start,
                            	TIME(m.EndTime) AS End
				FROM location AS l, meeting AS m, volunteer AS v, person AS p
				WHERE l.LocationID = m.LocationID 
				AND m.MeetingID = v. MeetingID
				AND v.PersonID = p.PersonID
				AND p.Eid = :eid && l.LocationName = :location;";

		return $db->query($sql, array(":eid" => $eid, ":location" => $location));
	}


	// Given an e-id for any team member and a location, return the events in
	// that location that are NOT for that team.
	public function getNonTeamEventsAtLoc($eid, $location)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT DISTINCT l.LocationName AS Location,
								m.MeetingType,
								m.Description,
            					DATE(m.StartTime) AS Date,
            					TIME(m.StartTime) AS Start,
            					TIME(m.EndTime) AS End				              
				FROM location AS l, meeting AS m, teamperson AS t, person AS p
				WHERE l.LocationID = m.LocationID
				AND m.TeamID != t.TeamID
				AND t.PersonID = p.PersonID 
				AND p.Eid = :eid && l.LocationName = :location;";

		return $db->query($sql, array(":eid" => $eid, ":location" => $location));
	}


	// Selects name and participation points for all volunteers
	public function getVolsAndPoints()
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT DISTINCT p.FirstName, p.LastName, v.Participated AS ParticipationPoints
				FROM person, volunteer AS v
				JOIN person AS p ON p.PersonID = v.PersonID
				ORDER BY p.FirstName ASC;";

		return $db->query($sql, array());
	}


	// Selects name and participation points for all volunteers, ordered by last name
	public function getVolsAndPointsByLastName()
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "SELECT DISTINCT p.LastName,p.FirstName ,v.Participated AS ParticipationPoints
				FROM person, volunteer AS v
				JOIN person AS p ON p.PersonID = v.PersonID
				ORDER BY p.LastName ASC;";

		return $db->query($sql, array());
	}


	// Update a meeting
	public function updateMeeting($meetID, $meetType, $description, $startTime, $endTime, $locID, $teamID, $numVolunteers, $reqForms)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "UPDATE meeting
				SET MeetingType = :meetType,
					Description = :description, 
                 	StartTime = :startTime,
                 	EndTime = :endTime,
                 	LocationID = :locID,
                 	TeamID = :teamID,
    				NumVolunteers = :numVolunteers,
    				RequiredForms = :reqForms
				WHERE MeetingID = :meetID;";

		$sqlVars = array(
			":meetID" => $meetID,
			":meetType" => $meetType,
			":description" => $description,
			":startTime" => $startTime,
			":endTime" => $endTime,
			":locID" => $locID,
			":teamID" => $teamID,
			":numVolunteers" => $numVolunteers,
			":reqForms" => $reqForms
			);

		return $db->query($sql, $sqlVars);
	}


	// Update a volunteer
	public function updateVolunteer($personID, $meetID, $incValue)
	{
		$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);

		$sql = "UPDATE volunteer AS v
				JOIN meeting AS m ON m.MeetingID = v.MeetingID
				SET Participated = Participated + :incValue
				WHERE m.MeetingID = :meetID && v.PersonID = :personID;";

		$sqlVars = array(
			":personID" => $personID,
			":meetID" => $meetID,
			":incValue" => $incValue
			);

		return $db->query($sql, $sqlVars);
	}
}

?>