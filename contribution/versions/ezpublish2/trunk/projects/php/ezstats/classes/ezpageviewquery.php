<?
// 
// $Id: ezpageviewquery.php,v 1.1 2001/01/07 12:31:48 bf Exp $
//
// Definition of eZPageViewQuery class
//
// Bård Farstad <bf@ez.no>
// Created on: <04-Jan-2001 18:00:08 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

//!! eZStats
//! The eZPageViewQuery handled queries on the stored page views.
/*!
  \sa eZPageView
*/

/*!TODO
 */

include_once( "classes/ezdb.php" );
include_once( "ezuser/classes/ezuser.php" );

class eZPageViewQuery
{
    /*!
      Constructs a new eZPageViewQuery object.
    */
    function eZPageViewQuery()
    {
        $this->IsConnected = false;
    }

    /*!
      Returns the total number of pageviews.
    */
    function totalPageViews()
    {
        $this->dbInit();

        $this->Database->array_query( $pageview_array, "SELECT count(ID) AS Count FROM eZStats_PageView" );

        return $pageview_array[0]["Count"];        
    }

    /*!
      Returns the total number of pageviews on the given date.

      returns 0 if the argument is not a  eZDate object.
    */
    function totalPageViewsDay( $dayObject )
    {
        $ret = 0;
        
        if ( get_class( $dayObject ) == "ezdate" )
        {
            $year = $dayObject->year();
            $month = $dayObject->month();
            $day = $dayObject->day();

            if ( $month < 10 )
                $month = "0" . $month;

            if ( $day < 10 )
                $day = "0" . $day;

            $dateStamp = $year . $month . $day;
            
            $this->Database->array_query( $pageview_array,
            "SELECT count(ID) AS Count
             FROM eZStats_PageView
             WHERE Date LIKE '$dateStamp%' ");
            
            $ret = $pageview_array[0]["Count"];
        }

        return $ret;
    }


    /*!
      Returns the total number of pageviews on the given month.

      returns 0 if the argument is not a  eZDate object.
    */
    function totalPageViewsMonth( $dayObject )
    {
        $ret = 0;
        
        if ( get_class( $dayObject ) == "ezdate" )
        {
            $year = $dayObject->year();
            $month = $dayObject->month();

            if ( $month < 10 )
                $month = "0" . $month;

            $dateStamp = $year . $month;
            
            $this->Database->array_query( $pageview_array,
            "SELECT count(ID) AS Count
             FROM eZStats_PageView
             WHERE Date LIKE '$dateStamp%' ");
            
            $ret = $pageview_array[0]["Count"];
        }
        return $ret;
    }


    /*!
      Returns the latest pageviews.

      The files are returned as an array of eZPageView objects.
    */
    function &latest( $limit=20 )
    {
        $this->dbInit();

        $return_array = array();
        $pageview_array = array();
        
        $this->Database->array_query( $pageview_array,
        "SELECT ID FROM eZStats_PageView
         ORDER BY Date DESC
         LIMIT 0,$limit" );
        
        for ( $i=0; $i<count($pageview_array); $i++ )
        {
            $return_array[$i] = new eZPageView( $pageview_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }
    
    
    /*!
      \private
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    ///  Variable for keeping the database connection.
    var $Database;

    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
