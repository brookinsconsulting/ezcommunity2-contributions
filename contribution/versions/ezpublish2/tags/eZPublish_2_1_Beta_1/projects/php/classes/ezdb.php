<?php
// 
// $Id: ezdb.php,v 1.36 2001/03/01 14:06:24 jb Exp $
//
// Definition of eZDB class
//
// Bård Farstad <bf@ez.no>
// Created on: Created on: <14-Jul-2000 13:01:15 bf>
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
  to query functions.

   
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
        $ini =& INIFile::globalINI();

        $this->Server =& $ini->read_var( "site", "Server" );
        $this->DB =& $ini->read_var( "site", "Database" );
        $this->User =& $ini->read_var( "site", "User" );
        $this->Password =& $ini->read_var( "site", "Password" );
        
        mysql_pconnect( $this->Server, $this->User, $this->Password )
            or print( "Error: could not connect to the database." );
        
        mysql_select_db( $this->DB )
            or print( "Error: could not connect to the database." );;
    }

    /*!
      Execute a query on the global MySQL database link.  If it returns an error,
      the script is halted and the attempted SQL query and MySQL error message are printed.
    */
    function &query( $sql, $print=false )
    {
        $result = mysql_query( $sql );

//          eZLog::writeNotice( $sql );

        if ( $print )
        {
            print( $sql . "<br>");
        }
        
        if ( $result )
        {
            return $result;
        }
        else
        {
            $this->Error = "<code>" . htmlentities( $sql ) . "</code><br>\n<b>" . htmlentities(mysql_error()) . "</b>\n" ;
            if ( $GLOBALS["DEBUG"] )
            {
                print( $this->Error );
                exit();
            }
            return false;
        }
    }

    /*!
      Executes a SELECT query that returns multiple rows and puts the results into the passed
      array as an indexed associative array.  The array is cleared first.  The results start with
      the array start at 0, and the number of results can be found with the count() function.
    */
    function array_query( &$array, $sql, $min = 0, $max = -1 )
    {
        $array = array();
        return $this->array_query_append( $array, $sql, $min, $max );
    }

    /*!
      Same as array_query() but expects to recieve 1 row only (no array), no more no less.
    */
    function query_single( &$row, $sql )
    {
        $array = array();
        $ret = $this->array_query_append( $array, $sql, 1, 1 );
        $row = $array[0];
        return $ret;
    }

    /*!
      Differs from the above function only by not creating av empty array,
      but simply appends to the array passed as an argument.
     */    
    function array_query_append( &$array, $sql, $min = 0, $max = -1 )
    {
        $result =& $this->query( $sql );

        if ( $result == false )
        {
            print( $this->Error );
            eZLog::writeWarning( $this->Error );
            return false;
        }

        $offset = count( $array );
//          if ( count( $result ) > 0 )
        
        if ( mysql_num_rows( $result ) > 0 )
        { 
            for($i = 0; $i < mysql_num_rows($result); $i++)
                $array[$i + $offset] =& mysql_fetch_array($result);
        }

        if ( count( $array ) < $min )
        {
            $this->Error = "<code>" . htmlentities( $sql ) . "</code><br>\n<b>" .
                                      htmlentities( "Received " . count( $array ) . " rows, minimum is $min" ) . "</b>\n" ;
        }
        if ( $max >= 0 )
        {
            if ( count( $array ) > $max )
            {
                $this->Error = "<code>" . htmlentities( $sql ) . "</code><br>\n<b>" .
                                          htmlentities( "Received " . count( $array ) . " rows, maximum is $max" ) . "</b>\n" ;
            }
        }
    }

    /*!
      Returns the last error message.
    */
    function error()
    {
        return $this->Error;
    }

    /*!
      Returns the ID of the last inserted row.
    */
    function insertID()
    {
        return mysql_insert_id();
    }

    /*!
      \static

      Closes the database connection.
    */
    function close()
    {
        mysql_close();
    }
    

    /*!
      \static
      Returns a reference to the global database object, if it doesn't exists it is initialized.
      This is safe to call without an object since it does not access member variables.
    */
    function &globalDatabase()
    {
        $eZDB =& $GLOBALS["eZDB"];
                
        if ( get_class( $eZDB ) != "ezdb" )
        {
            $eZDB = new eZDB( "site.ini", "site" );
        }
        return $eZDB;
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
