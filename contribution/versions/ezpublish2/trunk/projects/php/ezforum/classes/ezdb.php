<?
/*!
    $Id: ezdb.php,v 1.2 2000/07/14 13:07:07 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:01:15 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

/*!
  openDB() : opens the database for queries
*/
include('ezforum/dbsettings.php');

function openDB()
{
    global $HOST;
    global $USER;
    global $PWD;
    global $DB;
    
    mysql_connect($HOST,$USER,$PWD);
    mysql_select_db($DB);
}
?>
