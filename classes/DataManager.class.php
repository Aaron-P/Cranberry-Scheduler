<?php
/**
 * Defines the DataManagerSingleton and DataManager classes.
 * @file      DataManager.class.php
 * @author    Aaron Papp
 * @author    Shawn LeMaster
 * @version   1.0
 * @date      2011-2012
 * @copyright University of Illinois/NCSA Open Source License
 */

error_reporting(E_ALL | E_STRICT);
require_once("DBHandler.class.php");

/**
 * A wrapper for interfacing with the database. One function per SQL query.
 * @class DataManagerSingleton
 */
class DataManagerSingleton
{
    private static $db; /**< Holds the DBHandler singleton for interfacing with the database. */

    /**
     * All SQL queries used in this class.
     */
    private $teamEventsSQL = "SELECT DISTINCT m.MeetingType,
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
        "SELECT Eid, FirstName, LastName, IsVolunteer, IsResearcher, IsTeacher
         FROM person
         WHERE Eid = :eid;";

    private $volunteerOpportunitiesSQL =
        "SELECT DISTINCT m.MeetingType,
                         m.Description,
                         DATE_FORMAT(m.StartTime, '%W, %M %e, %Y') AS Date,
                         DATE_FORMAT(m.StartTime, '%l:%i %p') AS Start,
                         DATE_FORMAT(m.EndTime, '%l:%i %p') AS End,
                         l.LocationName AS Location,
                         m.RequiredForms,
                         m.MeetingID
         FROM meeting As m, location AS l
         WHERE l.LocationID = m.LocationID
              AND m.MeetingType = 'Interview'
              AND m.NumVolunteers > (SELECT COUNT(v.MeetingID)
                                     FROM volunteer AS v
                                     WHERE v.MeetingID = m.MeetingID)
              AND m.StartTime > NOW()
         ORDER BY m.StartTime;";

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
						 UNIX_TIMESTAMP(m.StartTime) < UNIX_TIMESTAMP(NOW()) AS InPast,
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
            "SELECT DISTINCT 'Volunteer' AS MeetingType,
                             DATE_FORMAT(m.StartTime, '%b %e') AS Date,
                             m.MeetingID
             FROM meeting As m, location AS l, volunteer, person
             WHERE m.MeetingID = volunteer.MeetingID
                   AND l.LocationID = m.LocationID
                   AND volunteer.PersonID = person.PersonID
                   AND NOW() < m.StartTime
                   AND person.Eid = :eid;";

    private $volEventsDetailedSQL =
            "SELECT DISTINCT 'Volunteer' AS MeetingType,
                             DATE_FORMAT(m.StartTime, '%W, %M %e, %Y') AS Date,
                             DATE_FORMAT(m.StartTime, '%l:%i %p') AS Start,
                             DATE_FORMAT(m.EndTime, '%l:%i %p') AS End,
                             m.MeetingID,
                             m.Description
             FROM meeting As m, location AS l, volunteer, person
             WHERE m.MeetingID = volunteer.MeetingID
                   AND l.LocationID = m.LocationID
                   AND volunteer.PersonID = person.PersonID
                   AND NOW() < m.StartTime
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
        "SELECT LocationID, LocationName, IsUsable
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
                 StartTime = FROM_UNIXTIME(:startTime),
                 EndTime = FROM_UNIXTIME(:endTime),
                 LocationID = :locID,
                 NumVolunteers = :numVolunteers
             WHERE MeetingID = :meetID;";

    private $updateVolunteerSQL =
            "UPDATE volunteer AS v
             JOIN meeting AS m ON m.MeetingID = v.MeetingID
             SET Participated = Participated + :incValue
             WHERE m.MeetingID = :meetID && v.PersonID = :personID;";

    private $settingsSQL =
            "SELECT s.EmailAddress, s.EmailNotify, s.SMSNotify, s.reminderTime
            FROM setting s, person p
            WHERE s.PersonID = p.PersonID
            AND p.Eid = :eid;";

    private $updateSettingsSQL =
			"INSERT INTO setting (PersonID, EmailAddress, EmailNotify, reminderTime)
			VALUES (:pid, :email, :enotify, :rtime)
			ON DUPLICATE KEY UPDATE EmailAddress = :email, EmailNotify = :enotify, reminderTime = :rtime";

    private $personIDSQL =
            "SELECT PersonID
            FROM person
            WHERE Eid = :eid;";

	private $locationEventsBetweenSQL =
			"SELECT DISTINCT m.MeetingType AS title,
                         UNIX_TIMESTAMP(m.StartTime) AS start,
                         UNIX_TIMESTAMP(m.EndTime) AS end,
                         m.MeetingID as id
	         FROM meeting As m
	         WHERE m.StartTime BETWEEN FROM_UNIXTIME(:start) AND FROM_UNIXTIME(:end)
	         AND m.LocationID = :location";

	private $reminderInfo = "SELECT TIME_TO_SEC(TIMEDIFF(m.StartTime, NOW())) AS diff, s.EmailAddress, m.MeetingType, m.Description, UNIX_TIMESTAMP(m.StartTime) AS StartTime, UNIX_TIMESTAMP(m.EndTime) AS EndTime, l.LocationName, p.FirstName, p.LastName
							FROM setting s, person p, teamperson t, meeting m, location l
							WHERE s.PersonID = p.PersonID
							AND p.PersonID = t.PersonID
							AND t.TeamID = m.TeamID
							AND m.LocationID = l.LocationID
							AND m.StartTime > NOW()
							AND s.EmailNotify = 1";

	private $isInMeetingSQL = "SELECT COUNT(*) AS Count
							FROM meeting m, teamperson t, person p
							WHERE m.TeamID = t.TeamID
							AND t.PersonID = p.PersonID
							AND m.MeetingID = :meetingId
							AND p.Eid = :eid;";

    private $isVolunteerSQL = "SELECT COUNT(*) AS Count
                            FROM meeting As m, location AS l, volunteer, person
                            WHERE m.MeetingID = volunteer.MeetingID
                            AND m.MeetingID = :meetingId
                            AND l.LocationID = m.LocationID
                            AND volunteer.PersonID = person.PersonID
                            AND NOW() < m.StartTime
                            AND person.Eid = :eid;";

	private $unconfirmedVolunteersSQL = "SELECT m.*, p.PersonID, p.FirstName, p.LastName
										FROM meeting m, volunteer v, person p
										WHERE m.NumVolunteers > 0
										AND m.AllVolunteersConfirmed = '0'
										AND m.MeetingID = v.MeetingID
										AND UNIX_TIMESTAMP(m.EndTime) < UNIX_TIMESTAMP(NOW())
										AND v.PersonID = p.PersonID
										AND m.TeamID = (SELECT t.TeamID
														 FROM teamperson t, person p
														 WHERE t.PersonID = p.PersonID
														 AND p.Eid = :eid)";

	private $unconfirmedVolunteersCountSQL = "SELECT COUNT(*) AS Count
									FROM meeting m, volunteer v, person p
									WHERE m.NumVolunteers > 0
									AND m.AllVolunteersConfirmed = '0'
									AND m.MeetingID = v.MeetingID
									AND UNIX_TIMESTAMP(m.EndTime) < UNIX_TIMESTAMP(NOW())
									AND v.PersonID = p.PersonID
									AND m.TeamID = (SELECT t.TeamID
													 FROM teamperson t, person p
													 WHERE t.PersonID = p.PersonID
													 AND p.Eid = :eid)";

	private $meetingDataSQL = "SELECT m.MeetingID, m.MeetingType, m.Description, m.LocationID, m.NumVolunteers,
								DATE_FORMAT(m.StartTime, '%m/%e/%Y') AS Date,
								DATE_FORMAT(m.StartTime, '%l:%i %p') AS Start,
								DATE_FORMAT(m.EndTime, '%l:%i %p') AS End
								FROM meeting m
								WHERE m.MeetingID = :meetingId";

    private $addLocationSQL = "INSERT INTO location(LocationName)
                               VALUES (:locname);";

    private $disableLocationSQL = "UPDATE location
                                    SET IsUsable = :usable
                                    WHERE LocationID = :id;";

    private $deleteLocationSQL = "DELETE FROM location
                                    WHERE LocationID = :id";

    private $addCourseSQL = "INSERT INTO course(CourseName)
                            VALUES (:name);";

    private $allCoursesSQL = "SELECT * FROM course;";

    private $deleteCourseSQL = "DELETE FROM course WHERE CourseID = :id;";

    private $addPersonSQL = "INSERT INTO courseperson(CourseID, PersonID)
                            VALUES (:courseID, :personID);";

    private $allPeopleSQL = "SELECT PersonID, FirstName, LastName FROM person;";

    private $deletePersonSQL = "DELETE FROM courseperson
                                WHERE PersonID = :personID
                                AND CourseID = :courseID;";

    private $groupIDByNameSQL = "SELECT TeamID FROM team WHERE TeamName = :name;";

    private $addGroupSQL = "INSERT INTO team(TeamName) VALUES (:tname);";

    private $addGroupPersonSQL = "INSERT INTO teamPerson(TeamID, PersonID) VALUES (:tid, :pid);";

    private $allGroupsSQL = "SELECT TeamID, TeamName FROM team ORDER BY TeamName;";

    private $deleteGroupSQL = "DELETE FROM team WHERE TeamID = :id;";

	private $checkEidExistsSQL = "SELECT COUNT(*) AS Count FROM person p WHERE p.Eid = :eid;";

    private $updateUserLevelSQL = "UPDATE person
                                    SET IsResearcher = :isResearcher,
                                        IsTeacher = :isTeacher
                                    WHERE PersonID = :personID;";

    private $ownsMeetingSQL =
        "SELECT m.MeetingID
        FROM meeting m, person p, teamperson tp
        WHERE m.TeamID = tp.TeamID
        AND p.Eid = :eid
        AND p.PersonID = tp.PersonID
        AND m.MeetingID = :meetingID
        AND p.IsResearcher = 1;";

    private $signUpSQL = "INSERT INTO volunteer(MeetingID, PersonID) 
                        VALUES (:mid, :pid);";


    /**
     * Constructs the DataManagerSingleton object.
     */
    protected static function Instance()
    {
        if (!self::$db)
            self::$db = new DBHandler("cranberryscheduler", "127.0.0.1", null, "root", null);
        return self::$db;
    }


    /**
     * Checks to see if an e-id exists in the database.
     * @param[in] $eid The e-id (username) that is checked
     */
	public function eidExists($eid)
	{
		$result = self::$db->query($this->checkEidExistsSQL, array(":eid" => $eid));
		if ($result[0]["Count"] !== "0")
			return true;
		return false;
	}


    /**
     * Given an e-id for any team member, return the events for that team.
     * @param[in] $eid The e-id (username) of one of the teammates on a team
     */
    public function getTeamEvents($eid)
    {
        return self::$db->query($this->teamEventsSQL, array(":eid" => $eid));
    }


    /**
     * Grabs detailed information about a person in the database.
     * @param[in] $eid The e-id (username) of the person
     */
    public function getPersonInfo($eid)
    {
        $result = self::$db->query($this->personInfoSQL, array(":eid" => $eid));
        return $result[0];
    }


    /**
     * Retrieves all available volunteer opportunities.
     */
    public function getVolunteerOpportunities()
    {
        return self::$db->query($this->volunteerOpportunitiesSQL);
    }


    /**
     * Grabs detailed information about a team's upcoming events.
     * @param[in] $eid The e-id (username) of one of the teammates
     */
    public function getUpcomingTeamEventsDetailed($eid)
    {
        return self::$db->query($this->upcomingTeamEventsDetailedSQL, array(":eid" => $eid));
    }


    /**
     * Grabs less detailed information about a team's upcoming events.
     * @param[in] $eid The e-id (username) of one of the teammates
     */
    public function getUpcomingTeamEvents($eid)
    {
        return self::$db->query($this->upcomingTeamEventsSQL, array(":eid" => $eid));
    }


    /**
     * Grabs the complete details of a single event.
     * @param[in] $eventID The event's ID
     */
    public function getEventInfo($eventID)
    {
        $result = self::$db->query($this->eventInfoSQL, array(":eventID" => $eventID));
        return $result[0];
    }


    /**
     * Gets the information about a meeting's scheduled volunteers.
     * @param[in] $meetingID The meeting's ID
     */
    public function getMeetingVolunteers($meetingID)
    {
        return self::$db->query($this->meetingVolunteersSQL, array(":meetingID" => $meetingID));
    }


    /**
     * Return the events for a team scheduled between the specified periods of time
     * @param[in] $eid The e-id (username) of a person on the team
     * @param[in] $start The UNIX timestamp of the start of the time interval
     * @param[in] $end The UNIX timestamp of the end of the time interval
     */
    public function getTeamEventsBetween($eid, $start, $end)
    {
        return self::$db->query($this->teamEventsBetweenSQL, array(":eid" => $eid, ":start" => $start, ":end" => $end));
    }


    /**
     * Return the events scheduled at a location between the specified periods of time
     * @param[in] $location The location ID
     * @param[in] $start The UNIX timestamp of the start of the time interval
     * @param[in] $end The UNIX timestamp of the end of the time interval
     */
	public function getLocationEventsBetween($location, $start, $end)
	{
		return self::$db->query($this->locationEventsBetweenSQL, array(":location" => $location, ":start" => $start, ":end" => $end));
	}


    /**
     * Grabs all the events that a volunteer is signed up for.
     * @param[in] $eid The volunteer's e-id (username)
     */
    public function getVolEvents($eid)
    {
        return self::$db->query($this->volEventsSQL, array(":eid" => $eid));
    }


    /**
     * Grabs all the events (including all details) that a volunteer is signed up for.
     * @param[in] $eid The volunteer's e-id (username)
     */
    public function getVolEventsDetailed($eid)
    {
        return self::$db->query($this->volEventsDetailedSQL, array(":eid" => $eid));
    }
    

    /**
     * Grabs all the events for a team at a particular location.
     * @param[in] $eid The e-id (username) of one of the teammate's
     * @param[in] $location The location ID
     */
    public function getTeamEventsAtLoc($eid, $location)
    {
        return self::$db->query($this->teamEventsAtLocSQL, array(":eid" => $eid, ":location" => $location));
    }


    /**
     * Grabs all the events for a volunteer at a particular location.
     * @param[in] $eid The e-id (username) of the volunteer
     * @param[in] $location The location ID
     */
    public function getVolEventsAtLoc($eid, $location)
    {
        return self::$db->query($this->volEventsAtLocSQL, array(":eid" => $eid, ":location" => $location));
    }


    /**
     * Grabs all the events at a location that do NOT belong to a team.
     * @param[in] $eid The e-id (username) of one of the teammates
     * @param[in] $location The location ID
     */
    public function getNonTeamEventsAtLoc($eid, $location)
    {
         return self::$db->query($this->nonTeamEventsAtLocSQL, array(":eid" => $eid, ":location" => $location));
    }


    /**
     * Grabs the names and associated participation points of all volunteers.
     */
    public function getVolsAndPoints()
    {
         return self::$db->query($this->volsAndPointsSQL);
    }


    /**
     * Grabs the names and associated participation points of all volunteers
     * ordered ascending by last name.
     */
    public function getVolsAndPointsByLastName()
    {
        return self::$db->query($this->volsAndPointsByLastNameSQL);
    }


    /**
     * Returns all the locations in the database.
     */
    public function getAllLocations()
    {
        return self::$db->query($this->allLocationsSQL);
    }


    /**
     * Fetches the location ID of a location given its name.
     * @param[in] $locName The name of the location
     */
    public function getLocationID($locName)
    {
        $result = self::$db->query($this->locationIDSQL, array(":locName" => $locName));
        return $result[0]["LocationID"];
    }


    /**
     * Gets the ID of a person's team
     * @param[in] $eid The e-id (username) of one of the teammates
     */
    public function getTeamID($eid)
    {
        $result = self::$db->query($this->teamIDSQL, array(":eid" => $eid));
        return $result[0]["TeamID"];
    }


    /**
     * Inserts a new meeting into the database
     * @param[in] $type The type of meeting (rehearsal or interview)
     * @param[in] $description The meeting description
     * @param[in] $start The UNIX timestamp of when the meeting begins
     * @param[in] $finish The UNIX timestamp of when the meeting ends
     * @param[in] $locName The name of the location where the meeting will take place
     * @param[in] $numOfVolunteers The number of requested volunteers
     * @param[in] $eid The e-id of the person who scheduled the meeting
     */
    public function insertMeeting($type, $description, $start, $finish, $locName, $numOfVolunteers, $eid)
    {
        $loc = $locName;//$this->getLocationID($locName);
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


    /**
     * Gets the details of a meeting
     * @param[in] $meetingId The ID of the meeting
     */
	public function getMeetingData($meetingId)
	{
		$result = self::$db->query($this->meetingDataSQL, array(":meetingId" => $meetingId));
		return $result[0];
	}

    
    /**
     * Updates the details of a meeting.
     * @param[in] $meetID The meeting ID
     * @param[in] $meetType The type of meeting (e.g. rehearsal or interview)
     * @param[in] $description The meeting description
     * @param[in] $startTime The UNIX timestamp of when the meeting begins
     * @param[in] $endTime The UNIX timestamp of when the meeting ends
     * @param[in] $locID The ID of the location where the meeting is to take place
     * @param[in] $numVolunteers The number of volunteers requested for the meeting
     */
    public function updateMeeting($meetID, $meetType, $description, $startTime, $endTime, $locID, $numVolunteers)
    {
        $sqlVars = array(
            ":meetID" => $meetID,
            ":meetType" => $meetType,
            ":description" => $description,
            ":startTime" => $startTime,
            ":endTime" => $endTime,
            ":locID" => $locID,
            ":numVolunteers" => $numVolunteers
        );
		return self::$db->query($this->updateMeetingSQL, $sqlVars);
    }


    /**
     * Updates the information of a volunteer after he/she participates
     * in a research meeting.
     * @param[in] $personID The ID of the volunteer
     * @param[in] $meetID The ID of the meeting the volunteer participated in
     * @param[in] $incValue The number of participation points earned
     */
    public function updateVolunteer($personID, $meetID, $incValue)
    {
        $sqlVars = array(
            ":personID" => $personID,
            ":meetID" => $meetID,
            ":incValue" => $incValue
            );

        return self::$db->query($this->updateVolunteerSQL, $sqlVars);
    }


    /**
     * Gets the website settings for a person
     * @param[in] $eid The person's e-id (username)
     */
    public function getSettings($eid)
    {
		$result = self::$db->query($this->settingsSQL, array(":eid" => $eid));
		if (!empty($result))
	        return $result[0];
		return null;
	}


    /**
     * Updates a person's settings
     * @param[in] $eid The ID of the person
     * @param[in] $email The email address of the person
     * @param[in] $enotify Whether or not to send the person an email reminder
     * @param[in] $rtime The amount of time (in hours) before an event that the person will receive an email reminder
     */
    public function updateSettings($eid, $email, $enotify, $rtime)
    {
        $pid = $this->getPersonID($eid);
        $sqlVars = array(
            ":pid" => $pid,
            ":email" => $email,
            ":enotify" => $enotify,
            ":rtime" => $rtime
        );
        return self::$db->query($this->updateSettingsSQL, $sqlVars);
    }


    /**
     * Gets the ID (the ID internal to the databse) of a person given their e-id
     * @param[in] $eid The person's e-id (username)
     */
    public function getPersonID($eid)
    {
        $result = self::$db->query($this->personIDSQL, array(":eid" => $eid));
        return $result[0]["PersonID"];
    }


    /**
     * Grabs the time remaining until scheduled events occur. Can be utilized
     * when sending out email reminders X hours before a meeting is scheduled.
     */
	public function getReminderInfo()
	{
		$result = self::$db->query($this->reminderInfo, array());
        return $result;
	}


    /**
     * Checks if a person is participating in a meeting as a researcher
     * @param[in] $meetingId The ID of the meeting
     * @param[in] $eid The researcher's e-id (username)
     */
	public function isInMeeting($meetingId, $eid)
	{
		$result = self::$db->query($this->isInMeetingSQL, array(":meetingId" => $meetingId, ":eid" => $eid));
		return (bool)$result[0]["Count"];
	}


    /**
     * Checks if a person is participating in a meeting as a volunteer
     * @param[in] $meetingId The ID of the meeting
     * @param[in] $eid The volunteer's e-id (username)
     */
    public function isVolunteer($meetingId, $eid)
    {
        $result = self::$db->query($this->isVolunteerSQL, array(":meetingId" => $meetingId, ":eid" => $eid));
        return (bool)$result[0]["Count"];
    }


    /**
     * Checks if a person's team has volunteers that are still uncomfirmed
     * (in terms of participation) from a previously held meeting.
     * @param[in] $eid The e-id of the person
     */
	public function areUnconfirmedVolunteers($eid)
	{
		$result = self::$db->query($this->unconfirmedVolunteersCountSQL, array(":eid" => $eid));
		return (bool)$result[0]["Count"];
	}


    /**
     * Gets the uncomfirmed volunteers for a person's team.
     * @param[in] $eid The e-id of the person
     */
	public function getUnconfirmedVolunteers($eid)
	{
		$result = self::$db->query($this->unconfirmedVolunteersSQL, array(":eid" => $eid));
		return $result;
	}


    /**
     * Adds a new location in the database to be used as a meeting place
     * @param[in] $location The name of the new location
     */
    public function addLocation($location)
    {
        return self::$db->query($this->addLocationSQL, array(":locname" => $location));
    }


    /**
     * Disables or deletes locations from the database. If disabled, a location
     * cannot be used when scheduling new meetings. Deleting removes a location
     * from the database completely - even currently scheduled events. So use
     * with caution.
     * @param[in] $locations The IDs of the locations
     * @param[in] $locations boolean that determines whether or not to delete the locations
     */
    public function updateDisabledLocations($locations, $delete)
    {
        $result = array();
        if ($delete)
        {
            foreach ($locations as $l)
                $result[] = self::$db->query($this->deleteLocationSQL, array(":id" => $l));
        }
        $allLocations = $this->getAllLocations();
        foreach ($allLocations as $l)
            self::$db->query($this->disableLocationSQL, array(":id" => $l['LocationID'], ":usable" => 1));
        if (!$delete)
            foreach ($locations as $id)
                $result[] = self::$db->query($this->disableLocationSQL, array(":id" => $id, ":usable" => 0));
        return $result;
    }


    /**
     * Adds a new course to the database
     * @param[in] $courseName The name of the new course
     */
    public function addCourse($courseName)
    {
        return self::$db->query($this->addCourseSQL, array(":name" => $courseName));
    }


    /**
     * Retrieves all courses from the database
     */
    public function getAllCourses()
    {
        return self::$db->query($this->allCoursesSQL);
    }


    /**
     * Deletes a course from the database.
     * @param[in] $courseID The ID of the course
     */
    public function deleteCourse($courseID)
    {
        return self::$db->query($this->deleteCourseSQL, array(":id" => $courseID));
    }


    /**
     * Adds a person to a course
     * @param[in] $personID The ID of the person
     * @param[in] $courseID The ID of the course
     * @param[in] $isResearcher boolean determining if this person is a researcher
     * @param[in] $isTeacher boolean determining if this person is a teacher/admin
     */
    public function addPerson($personID, $courseID, $isResearcher, $isTeacher)
    {
        $sqlVars = array(
            ":isResearcher" => $isResearcher,
            ":isTeacher" => $isTeacher,
            ":personID" => $personID
        );
        $result[] = self::$db->query($this->updateUserLevelSQL, $sqlVars);

        $sqlVars = array(
            ":personID" => $personID,
            ":courseID" => $courseID
        );
        $result[] = self::$db->query($this->addPersonSQL, $sqlVars);
        
        return $result;
    }


    /**
     * Retrieves all people in the database
     */
    public function getAllPeople()
    {
        return self::$db->query($this->allPeopleSQL);
    }


    /**
     * Deletes a person from a course and revises their persmissions.
     * @param[in] $personID The ID of the person
     * @param[in] $courseID The ID of the course
     * @param[in] $isResearcher boolean determining if this person is a researcher
     * @param[in] $isTeacher boolean determining if this person is a teacher/admin
     */
    public function deletePerson($personID, $courseID, $isResearcher, $isTeacher)
    {
        $sqlVars = array(
            ":isResearcher" => $isResearcher,
            ":isTeacher" => $isTeacher,
            ":personID" => $personID
        );
        $result[] = self::$db->query($this->updateUserLevelSQL, $sqlVars);

        $sqlVars = array(
            ":personID" => $personID,
            ":courseID" => $courseID
        );
        $result[] = self::$db->query($this->deletePersonSQL, $sqlVars);

        return $result;
    }


    /**
     * Gets the group ID of a group.
     * @param[in] $name The name of the group
     */
    public function getGroupIDByName($name)
    {
        $result = self::$db->query($this->groupIDByNameSQL, array(":name" => $name));
        if (!empty($result))
            return $result[0]["TeamID"];
        return null;
    }


    /**
     * Adds a new group to the database
     * @param[in] $name The name of the group
     */
    public function addGroup($name)
    {
        return self::$db->query($this->addGroupSQL, array(":tname" => $name));
    }


    /**
     * Adds a person to a group
     * @param[in] $teamID The ID of the team
     * @param[in] $personID The ID of the person
     */
    public function addGroupPerson($teamID, $personID)
    {
        return self::$db->query($this->addGroupPersonSQL, array(":tid" => $teamID, ":pid" => $personID));
    }


    /**
     * Fetches all groups in the database
     */
    public function getAllGroups()
    {
        return self::$db->query($this->allGroupsSQL);
    }


    /**
     * Deletes a group from the database
     * @param[in] $teamID The ID of the team
     */
    public function deleteGroup($teamID)
    {
        return self::$db->query($this->deleteGroupSQL, array(":id", $teamID));
    }


    /**
     * Determines if a person is in a group that owns a particular meeting
     * @param[in] $meetingID The ID of the meeting
     * @param[in] $eid The e-id of the person (username)
     */
    public function ownsMeeting($meetingID, $eid)
    {
        $sqlVars = array(
            ":eid" => $eid,
            ":meetingID" => $meetingID
        );
        $result = self::$db->query($this->ownsMeetingSQL, $sqlVars);
        return !empty($result);
    }


    /**
     * Signs a volunteer up for a meeting
     * @param[in] $eid The e-id of the volunteer
     * @param[in] $meetingID The ID of the meeting being signed up for
     */
    public function volunteerSignUp($eid, $meetingID)
    {
        $pid = $this->getPersonID($eid);
        return self::$db->query($this->signUpSQL, array(":mid" => $meetingID, ":pid" => $pid));
    }
}



/**
 * A wrapper for the DataManagerSingleton class.
 * @class DataManager
 */
class DataManager extends DataManagerSingleton
{
    /**
     * Constructs the DataManager object.
     */
    public function __construct()
    {
        DataManagerSingleton::Instance();
    }
}
?>