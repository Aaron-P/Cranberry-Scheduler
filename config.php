<?php
/**
 * Holds the configuration information for the scheduler.
 * @file      config.php
 * @author    Aaron Papp
 * @version   1.0
 * @date      2011-2012
 * @copyright University of Illinois/NCSA Open Source License
 */

/**
 * The host for the LDAP server.
 */
define("LDAP_SERVER", "");

/**
 * The amount of time in minutes to wait for an access before timing out.
 */
define("ACCESS_TIMEOUT_LIMIT", 30 * 60);
/**
 * The amount of time in minutes after logging in to time out.
 */
define("SESSION_TIMEOUT_LIMIT", 60 * 60);

/**
 * The database host name.
 */
define("DB_HOSTNAME", "");
/**
 * The database username.
 */
define("DB_USERNAME", "");
/**
 * The database password.
 */
define("DB_PASSWORD", "");
/**
 * The database name..
 */
define("DB_DATABASE", "cranberryscheduler");
?>