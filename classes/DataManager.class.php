<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */
error_reporting(E_ALL | E_STRICT);
require_once("DBHandler.class.php");
require_once("classes/SessionHandler.class.php");

class DataManagerSingleton
{
    private static $db;

    private $teamEventsSQL =
        "SELECT DISTINCT m.MeetingType,
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
                
    private $personInfoSQL =
        "SELECT Eid, FirstName, LastName
         FROM person
         WHERE Eid = :eid;";

    private $volunteerOpportunitiesSQL =
        "SELECT DISTINCT m.MeetingType,
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

    private $upcomingTeamEventsDetailedSQL =
        "SELECT DISTINCT m.MeetingType,
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

    private $upcomingTeamEventsSQL =
        "SELECT DISTINCT m.MeetingType,
                         DATE_FORMAT(m.StartTime, '%b %e') AS Date,
                         m.MeetingID
         FROM meeting As m, teamperson, person
         WHERE m.TeamID = teamperson.TeamID
              AND teamperson.PersonID = person.PersonID
              AND NOW() < m.StartTime
              AND person.Eid = :eid;";

    private $eventInfoSQL =
        "SELECT DISTINCT m.MeetingType,
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

    private $meetingVolunteersSQL =
        "SELECT p.FirstName, p.LastName, p.Eid
         FROM person AS p, meeting AS m, volunteer AS v
         WHERE p.PersonID = v.PersonID
               AND m.MeetingID = :meetingID
               AND m.MeetingID = v.MeetingID;";

    private $teamEventsBetweenSQL =
        "SELECT DISTINCT m.MeetingType AS title,
                         UNIX_TIMESTAMP(m.StartTime) AS start,
                         UNIX_TIMESTAMP(m.EndTime) AS end,
                         m.MeetingID as id
         FROM meeting As m, teamperson, person
         WHERE m.TeamID = teamperson.TeamID
               AND teamperson.PersonID = person.PersonID
               AND person.Eid = :eid
               AND m.StartTime BETWEEN FROM_UNIXTIME(:start) AND FROM_UNIXTIME(:end);";

    private $volEventsSQL =
            "SELECT DISTINCT m.Description,
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

    private $teamEventsAtLocSQL =
        "SELECT DISTINCT l.LocationName AS Location,
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

    private $volEventsAtLocSQL =
            "SELECT DISTINCT l.LocationName AS Location,
                             m.Description,
                             DATE(m.StartTime) AS Date,
                             TIME(m.StartTime) AS Start,
                             TIME(m.EndTime) AS End
             FROM location AS l, meeting AS m, volunteer AS v, person AS p
             WHERE l.LocationID = m.LocationID
                   AND m.MeetingID = v. MeetingID
                   AND v.PersonID = p.PersonID
                   AND p.Eid = :eid && l.LocationName = :location;";

    private $nonTeamEventsAtLocSQL =
            "SELECT DISTINCT l.LocationName AS Location,
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

    private $volsAndPointsSQL =
            "SELECT DISTINCT p.FirstName, p.LastName, v.Participated AS ParticipationPoints
             FROM person, volunteer AS v
             JOIN person AS p ON p.PersonID = v.PersonID
             ORDER BY p.FirstName ASC;";

    private $volsAndPointsByLastNameSQL =
            "SELECT DISTINCT p.LastName,p.FirstName ,v.Participated AS ParticipationPoints
             FROM person, volunteer AS v
             JOIN person AS p ON p.PersonID = v.PersonID
             ORDER BY p.LastName ASC;";

    private $allLocationsSQL =
        "SELECT LocationName
         FROM location
         ORDER BY LocationName ASC;";

   private $locationIDSQL =
        "SELECT LocationID
         FROM location
         WHERE LocationName = :locName;";

    private $teamIDSQL =
        "SELECT teamperson.TeamID
         FROM teamperson, person
         WHERE teamperson.PersonID = person.PersonID
               AND person.Eid = :eid;";

    private $insertMeetingSQL =
        "INSERT INTO meeting(MeetingType, Description,
                     StartTime, EndTime, LocationID, 
                     TeamID, NumVolunteers) 
                VALUES (:type, :description, FROM_UNIXTIME(:start), FROM_UNIXTIME(:finish), :loc, :teamID, :numOfVolunteers);";

    private $updateMeetingSQL =
            "UPDATE meeting
             SET MeetingType = :meetType,
                 Description = :description,
                 StartTime = :startTime,
                 EndTime = :endTime,
                 LocationID = :locID,
                 TeamID = :teamID,
                 NumVolunteers = :numVolunteers,
                 RequiredForms = :reqForms
             WHERE MeetingID = :meetID;";

    private $updateVolunteerSQL =
            "UPDATE volunteer AS v
             JOIN meeting AS m ON m.MeetingID = v.MeetingID
             SET Participated = Participated + :incValue
             WHERE m.MeetingID = :meetID && v.PersonID = :personID;";

 
    protected static function Instance()
    {
        if (!self::$db)
            self::$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);
        return self::$db;
    }


    // Given an e-id for any team member, return the events for that team
    public function getTeamEvents($eid)
    {
        return self::$db->query($this->teamEventsSQL, array(":eid" => $eid));
    }


    public function getPersonInfo($eid)
    {
        $result = self::$db->query($this->personInfoSQL, array(":eid" => $eid));
        return $result[0];
    }


    public function getVolunteerOpportunities()
    {
        return self::$db->query($this->volunteerOpportunitiesSQL);
    }


    public function getUpcomingTeamEventsDetailed($eid)
    {
        return self::$db->query($this->upcomingTeamEventsDetailedSQL, array(":eid" => $eid));
    }


    public function getUpcomingTeamEvents($eid)
    {
        return self::$db->query($this->upcomingTeamEventsSQL, array(":eid" => $eid));
    }


    public function getEventInfo($eventID)
    {
        $result = self::$db->query($this->eventInfoSQL, array(":eventID" => $eventID));
        return $result[0];
    }


    public function getMeetingVolunteers($meetingID)
    {
        return self::$db->query($this->meetingVolunteersSQL, array(":meetingID" => $meetingID));
    }


    // Given an e-id for any team member, return the events for that team
    // that happen between the times $start and $end (UNIX timestamps)
    public function getTeamEventsBetween($eid, $start, $end)
    {
        return self::$db->query($this->teamEventsBetweenSQL, array(":eid" => $eid, ":start" => $start, ":end" => $end));
    }


    // Given an e-id for a volunteer, return that volunteer's events
    public function getVolEvents($eid)
    {
        return self::$db->query($this->volEventsSQL, array(":eid" => $eid));
    }


    // Given an e-id for any team member and a location, return the events for
    // that team at that location.
    public function getTeamEventsAtLoc($eid, $location)
    {
        return self::$db->query($this->teamEventsAtLocSQL, array(":eid" => $eid, ":location" => $location));
    }


    // Given the e-id for a volunteer and a location, return the events for
    // that volunteer at that location.
    public function getVolEventsAtLoc($eid, $location)
    {
        return self::$db->query($this->volEventsAtLocSQL, array(":eid" => $eid, ":location" => $location));
    }


    // Given an e-id for any team member and a location, return the events in
    // that location that are NOT for that team.
    public function getNonTeamEventsAtLoc($eid, $location)
    {
         return self::$db->query($this->nonTeamEventsAtLocSQL, array(":eid" => $eid, ":location" => $location));
    }


    // Selects name and participation points for all volunteers
    public function getVolsAndPoints()
    {
         return self::$db->query($this->volsAndPointsSQL);
    }


    // Selects name and participation points for all volunteers, ordered by last name
    public function getVolsAndPointsByLastName()
    {
        return self::$db->query($this->volsAndPointsByLastNameSQL);
    }


    public function getAllLocations($locName)
    {
        return self::$db->query($this->allLocationsSQL);
    }


    public function getLocationID($locName)
    {
        $result = self::$db->query($this->locationIDSQL, array(":locName" => $locName));
        return $result[0][0];
    }


    public function getTeamID($eid)
    {
        $result = self::$db->query($this->teamIDSQL, array(":eid" => $eid));
        return $result[0][0];
    }


    public function insertMeeting($type, $description, $start, $finish, $locName, $numOfVolunteers)
    {
        $sh = new SessionHandler();
        $eid = $sh->get("username");
        $loc = $this->getLocationID($locName);
        $teamID = $this->getTeamID($eid);

        if ($numOfVolunteers == NULL || $numOfVolunteers <= 0)
            $numOfVolunteers = 0;

        $sqlVars = array(
            ":type" => $type,
            ":description" => $description,
            ":start" => $start,
            ":finish" => $finish,
            ":loc" => $loc,
            ":teamID" => $teamID,
            ":numOfVolunteers" => $numOfVolunteers
        );

        return self::$db->query($this->insertMeetingSQL, $sqlVars);
    }


    // Update a meeting
    public function updateMeeting($meetID, $meetType, $description, $startTime, $endTime, $locID, $teamID, $numVolunteers, $reqForms)
    {
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

        return self::$db->query($this->updateMeetingSQL, $sqlVars);
    }


    // Update a volunteer
    public function updateVolunteer($personID, $meetID, $incValue)
    {
        $sqlVars = array(
            ":personID" => $personID,
            ":meetID" => $meetID,
            ":incValue" => $incValue
            );

        return self::$db->query($this->updateVolunteerSQL, $sqlVars);
    }
}


class DataManager extends DataManagerSingleton
{
    public function __construct()
    {
        DataManagerSingleton::Instance();
    }
}

?>