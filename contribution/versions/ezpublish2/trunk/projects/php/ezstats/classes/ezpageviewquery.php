<?
// 
// $Id: ezpageviewquery.php,v 1.6 2001/01/12 17:44:36 bf Exp $
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
include_once( "classes/ezdate.php" );
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
      Returns the visitors which has viewed most pages.

      The files are returned as an assiciative array of
      array( ID => $id, IP => $ip, HostName => $hostName, Count => $count ).
    */
    function &topVisitors( $limit=20 )
    {
        $this->dbInit();

        $return_array = array();
        $visitor_array = array();
        
        $this->Database->array_query( $visitor_array,
        "SELECT count(eZStats_PageView.ID) AS Count, eZStats_RemoteHost.ID, eZStats_RemoteHost.IP, eZStats_RemoteHost.HostName
         FROM eZStats_PageView, eZStats_RemoteHost
         WHERE eZStats_PageView.RemoteHostID=eZStats_RemoteHost.ID
         GROUP BY eZStats_RemoteHost.ID ORDER BY Count DESC
         LIMIT 0,$limit" );
        
        for ( $i=0; $i<count($visitor_array); $i++ )
        {
            $id = $visitor_array[$i]["ID"];
            $ip = $visitor_array[$i]["IP"];
            $hostName = $visitor_array[$i]["HostName"];

            // check if the domain name is fetched, if not try to fetch it 
            // and store the result in the table.
            if ( $hostName = "NULL" )
            {
                $hostName =& gethostbyaddr( $ip );
                $this->Database->query( "UPDATE eZStats_RemoteHost SET HostName='$hostName'
                                         WHERE ID='$id'" );
            }            
            
            $return_array[$i] = array( "ID" => $id,
                                       "IP" => $ip,
                                       "HostName" => $hostName,
                                       "Count" => $visitor_array[$i]["Count"] );
        }
        
        return $return_array;
    }

    /*!
      Returns the referers which is most frequent.

      The files are returned as an assiciative array of
      array( ID => $id, Domain => $domain, URI => $uri, Count => $count ).
    */
    function &topReferers( $limit=20, $excludeDomain="" )
    {
        $this->dbInit();

        $return_array = array();
        $visitor_array = array();
        
        $this->Database->array_query( $visitor_array,
        "SELECT count(eZStats_PageView.ID) AS Count, eZStats_RefererURL.ID, eZStats_RefererURL.Domain, eZStats_RefererURL.URI
         FROM eZStats_PageView, eZStats_RefererURL
         WHERE eZStats_PageView.RefererURLID=eZStats_RefererURL.ID AND eZStats_RefererURL.Domain != '$excludeDomain'
         GROUP BY eZStats_RefererURL.ID
         ORDER BY Count DESC
         LIMIT 0,$limit" );
        
        for ( $i=0; $i<count($visitor_array); $i++ )
        {
            $return_array[$i] = array( "ID" => $visitor_array[$i]["ID"],
                                       "Domain" => $visitor_array[$i]["Domain"],
                                       "URI" => $visitor_array[$i]["URI"],
                                       "Count" => $visitor_array[$i]["Count"] );
        }
        
        return $return_array;
    }

    /*!
      Returns the requests which is most frequent.

      The files are returned as an assiciative array of
      array( ID => $id, URI => $uri, Count => $count ).
    */
    function &topRequests( $limit=20 )
    {
        $this->dbInit();

        $return_array = array();
        $visitor_array = array();
        
        $this->Database->array_query( $visitor_array,
        "SELECT count(eZStats_PageView.ID) AS Count, eZStats_RequestPage.ID, eZStats_RequestPage.URI
         FROM eZStats_PageView, eZStats_RequestPage
         WHERE eZStats_PageView.RequestPageID=eZStats_RequestPage.ID
         GROUP BY eZStats_RequestPage.ID
         ORDER BY Count DESC
         LIMIT 0,$limit" );
        
        for ( $i=0; $i<count($visitor_array); $i++ )
        {
            $return_array[$i] = array( "ID" => $visitor_array[$i]["ID"],
                                       "URI" => $visitor_array[$i]["URI"],
                                       "Count" => $visitor_array[$i]["Count"] );
        }
        
        return $return_array;
    }

    /*!
      Returns the most frequent viewed products.
    */
    function &topProductRequests( $limit=20 )
    {
        $this->dbInit();

        $return_array = array();
        $visitor_array = array();
        
        $this->Database->array_query( $visitor_array,
        "SELECT count(eZStats_PageView.ID) AS Count, eZStats_RequestPage.ID, eZStats_RequestPage.URI
         FROM eZStats_PageView, eZStats_RequestPage
         WHERE eZStats_PageView.RequestPageID=eZStats_RequestPage.ID
         AND eZStats_RequestPage.URI LIKE '/trade/productview/%'
         GROUP BY eZStats_RequestPage.ID
         ORDER BY Count DESC
         LIMIT 0,$limit" );
        
        for ( $i=0; $i<count($visitor_array); $i++ )
        {
            $return_array[$i] = array( "ID" => $visitor_array[$i]["ID"],
                                       "URI" => $visitor_array[$i]["URI"],
                                       "Count" => $visitor_array[$i]["Count"] );
        }
        
        return $return_array;
    }

    /*!
      Returns the most frequent products added to the cart.
    */
    function &topProductAddToCart( $limit=20 )
    {
        $this->dbInit();

        $return_array = array();
        $visitor_array = array();
        
        $this->Database->array_query( $visitor_array,
        "SELECT count(eZStats_PageView.ID) AS Count, eZStats_RequestPage.ID, eZStats_RequestPage.URI
         FROM eZStats_PageView, eZStats_RequestPage
         WHERE eZStats_PageView.RequestPageID=eZStats_RequestPage.ID
         AND eZStats_RequestPage.URI LIKE '/trade/cart/add/%'
         GROUP BY eZStats_RequestPage.ID
         ORDER BY Count DESC
         LIMIT 0,$limit" );
        
        for ( $i=0; $i<count($visitor_array); $i++ )
        {
            $return_array[$i] = array( "ID" => $visitor_array[$i]["ID"],
                                       "URI" => $visitor_array[$i]["URI"],
                                       "Count" => $visitor_array[$i]["Count"] );
        }
        
        return $return_array;
    }
    
    /*!
      Returns the statistics for one month.

      Returns an array of days with the statistics as an associative array:
      array( "Days" => array( "Count" => $count )
    */
    function &monthStats( $year, $month )
    {
        $this->dbInit();

        $return_array = array();
        $visitor_array = array();
        $day_array = array();

        $date = new eZDate( $year, $month, 1 );

        if ( $month < 10 )
            $month = "0" . $month;

        $TotalPages = 0;
        // loop over the days
        for ( $day=1; $day<=$date->daysInMonth(); $day++ )
        {
            if ( $day < 10 )
                $sday = "0" . $day;
            else
                $sday = $day;
        
            $stamp = $year . $month . $sday;
            $this->Database->array_query( $visitor_array,
            "SELECT count(eZStats_PageView.ID) AS Count FROM eZStats_PageView WHERE Date LIKE '$stamp%'" );

            $TotalPages += $visitor_array[0]["Count"];
            $day_array[] = array( "Count" => $visitor_array[0]["Count"] );
        }

        $return_array = array( "TotalPages" => $TotalPages,
                               "PagesPrDay" => round( $TotalPages/$date->daysInMonth() ),
                               "Days" => $day_array );
        
        return $return_array;
    }

    /*!
      Returns the top exit pages.
    */
    function &topExitPage( )
    {
        $this->dbInit();

        $return_array = array();
        $visitor_array = array();
        
        $this->Database->array_query( $visitor_array,
        "SELECT Hit.ID, Page.URI, Page.ID AS PageID, Hit.RemoteHostID, Concat( Year(Hit.Date), DayOfYear(Hit.Date)) AS Date
         FROM eZStats_PageView AS Hit, eZStats_RequestPage AS Page
         WHERE Hit.RequestPageID=Page.ID
         ORDER BY Hit.RemoteHostID, Hit.Date ASC" );
        
        foreach ( $visitor_array as $visit )
        {
//              print( $visit["Date"] . " " .$visit["RemoteHostID"]. " ". $visit["URI"]  . " " . $visit["PageID"]. "<br>" );
            
//              $return_array[$visit["Date"]][$visit["RemoteHostID"]] = $visit["PageID"];
            
            $idx = $visit["Date"] . $visit["RemoteHostID"];
            
            $return_array[$idx] = $visit["PageID"];

        }
        
        return $return_array;
        
    }

    /*!
      Returns the top entry pages.
    */
    function &topEntryPage( )
    {
        $this->dbInit();

        $return_array = array();
        $visitor_array = array();
        
        $this->Database->array_query( $visitor_array,
        "SELECT Hit.ID, Page.URI, Page.ID AS PageID, Hit.RemoteHostID, Concat( Year(Hit.Date), DayOfYear(Hit.Date)) AS Date
         FROM eZStats_PageView AS Hit, eZStats_RequestPage AS Page
         WHERE Hit.RequestPageID=Page.ID
         ORDER BY Hit.RemoteHostID, Hit.Date DESC" );
        
        foreach ( $visitor_array as $visit )
        {
            $idx = $visit["Date"] . $visit["RemoteHostID"];
            
            $return_array[$idx] = $visit["PageID"];

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
