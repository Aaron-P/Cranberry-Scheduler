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