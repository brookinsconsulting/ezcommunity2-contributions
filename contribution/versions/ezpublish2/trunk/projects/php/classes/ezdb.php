<?php
// 
// $Id: ezdb.php,v 1.40 2001/06/21 10:03:50 bf Exp $
//
// Definition of eZDB class
//
// Bård Farstad <bf@ez.no>
// Created on: <14-Jul-2000 13:01:15 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!! eZCommon
//! The eZDB class provides database functions.
/*!
  The eZDB class hanles database connections and is a wrapper
  to query functions for the selected database.
   
*/

/*!TODO
  Add a generic query builder for use with search. A more advanced version of the query
  class found in ezlink/class/ezquery.
*/

include_once( "classes/ezlog.php" );
include_once( "classes/INIFile.php" );

class eZDB
{
    /*!
      Constructs a new eZDB object, connects to the database and
      selects the desired table.

      The eZDB constructor takes a .ini file as an argument.
      The second argument defines under what category in the .ini
      file the database information is located.
    */
    function eZDB( $iniFile, $category )
    {
        print( "This object should not be created use eZDB::globalDatabase();" );
    }

    /*!
      Execute a query on the global MySQL database link.  If it returns an error,
      the script is halted and the attempted SQL query and MySQL error message are printed.
    */
    function &query( $sql, $print=false )
    {
        print( "Obsolete function.. Do NOT USE!" );
    }

    /*!
      Executes a SELECT query that returns multiple rows and puts the results into the passed
      array as an indexed associative array.  The array is cleared first.  The results start with
      the array start at 0, and the number of results can be found with the count() function.
      The minimum and maximum expected rows can be set by supplying $min and $max,
      default is to allow zero or more rows.
      If a string is supplied to $column it is used for extracting a specific column from the
      query into the resulting array, this is useful when you just want one column from
      the query and don't want to iterate over it afterwards to extract the column.
    */
    function array_query( &$array, $sql, $min = 0, $max = -1, $column = false )
    {
        print( "Obsolete function.. Do NOT USE!" );
    }

    /*!
      Same as array_query() but expects to recieve 1 row only (no array), no more no less.
      $column is the same as in array_query().
    */
    function query_single( &$row, $sql, $column = false )
    {
        print( "Obsolete function.. Do NOT USE!" );
    }

    /*!
      Differs from the above function only by not creating av empty array,
      but simply appends to the array passed as an argument.
     */    
    function array_query_append( &$array, $sql, $min = 0, $max = -1, $column = false )
    {
        print( "Obsolete function.. Do NOT USE!" );
    }

    /*!
      Returns the last error message.
    */
    function error()
    {
        print( "Obsolete function.. Do NOT USE!" );
    }

    /*!
      Returns the ID of the last inserted row.
    */
    function insertID()
    {
        print( "Obsolete function.. Do NOT USE!" );
    }

    /*!
      \static

      Closes the database connection.
    */
    function close()
    {
        print( "Obsolete function.. Do NOT USE!" );
    }
    
/*
    function &globalDatabase()
    {
        $eZDB =& $GLOBALS["eZDB"];
                
        if ( get_class( $eZDB ) != "ezdb" )
        {
            $eZDB = new eZDB( "site.ini", "site" );
        }
        return $eZDB;
    }
*/

    /*!
      \static
      Returns a reference to the global database object, if it doesn't exists it is initialized.
      This is safe to call without an object since it does not access member variables.
    */
    function &globalDatabase(  )
    {
        $impl =& $GLOBALS["eZDB"];

        $class =& get_class( $impl );
        if ( !preg_match( "/ez.*?db/", $class ) )
        {
            $ini =& INIFile::globalINI();

            $server =& $ini->read_var( "site", "Server" );
            $db =& $ini->read_var( "site", "Database" );
            $user =& $ini->read_var( "site", "User" );
            $password =& $ini->read_var( "site", "Password" );
            $databaseImplementation =& $ini->read_var( "site", "DatabaseImplementation" );
            
            switch ( $databaseImplementation )
            {
                case "mysql" :
                {
                    include_once( "classes/ezmysqldb.php" );

                    $impl = new eZMySQLDB( $server, $db, $user, $password );
                }
                break;

                case "postgresql" :
                {
                    include_once( "classes/ezpostgresqldb.php" );
                
                    $impl = new eZPostgreSQLDB( $server, $db, $user, $password );
                }
                break;

                case "informix" :
                {
                    include_once( "classes/ezinformixdb.php" );
                    $impl = new eZInformixDB( $server, $db, $user, $password );
                }
                break;
            
                default :
                {
                    print( "Database error: have no support for $implementation" );
                }
                break;
            }
        }
        

        return $impl;
    }


    /// the server to connect to
    var $Server;
    /// the database to use
    var $DB;
    /// the username to use
    var $User;
    /// the password to use
    var $Password;

    // the last error message
    var $Error;
}

?>
