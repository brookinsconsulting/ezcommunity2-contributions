<?
// 
// $Id: ezlinkgroup.php,v 1.34 2000/10/26 13:23:26 ce-cvs Exp $
//
// Definition of eZLinkGroup class
//
// Bård Farstad <bf@ez.no>
// Created on: <15-Sep-2000 14:40:06 bf>
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

//!! eZLink
//! The eZLinkGroup class handles URL links.
/*!

  Example code:

  \code
  // Create a new group and set some values.
  $group = new eZLinkGroup();
  $group->setTitle( "PHP" );
  $group->setParent( "ParentID" );

  // Store the group in to the database.
  $group->store();

  \endcode
  
  \sa eZLink eZHit eZQuery
*/

/*!TODO
  Rename title to name (also in the database).
*/


include_once( "classes/ezdb.php" );

class eZLinkGroup
{
    /*!
      Counstructor
    */
    function eZLinkGroup( $id=-0, $fetch=true )
    {

        $this->IsConnected = false;
        if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
            }
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Lager linkgruppe i databasen
    */
    function store()
    {
        $this->dbInit();
        $this->Database->query( "INSERT INTO eZLink_LinkGroup SET
                ID='$this->ID',
                Title='$this->Title',
                Parent='$this->Parent'" );
    }

    /*!
      Oppgraderer databasen
    */
    function update()
    {
        $this->dbInit();
        $this->Database->query( "UPDATE eZLink_LinkGroup SET 
                Title='$this->Title',
                Parent='$this->Parent'
                WHERE ID='$this->ID'" );
    }

    /*!
      Slette fra databasen
    */
    function delete( )
    {
        $this->dbInit();
        $this->Database->query( "DELETE FROM eZLink_Link WHERE LinkGroup='$this->ID'" );
        $this->Database->query( "DELETE FROM eZLink_LinkGroup WHERE ID='$this->ID'" );
    }

    /*!
      Henter ut alle gruppene fra databasen.
    */
    function get( $id )
    {
        $this->dbInit();
        $this->Database->array_query( $linkgroup_array,  "SELECT * FROM eZLink_LinkGroup WHERE ID='$id'" );
        if ( count( $linkgroup_array ) > 1 )
        {
            die( "feil, flere grupper med samme id" );
        }
        else if ( count( $linkgroup_array ) == 1 )
        {
            $this->ID = $linkgroup_array[ 0 ][ "ID" ];
            $this->Title = $linkgroup_array[ 0 ][ "Title" ];
            $this->Parent = $linkgroup_array[ 0 ][ "Parent" ];
        }
    }

    /*!
      Rekursiv funksjon, skriver ut hele pathen til gruppen.
    */
    function printPath( $id, $url )
    {
        $lg = new eZLinkGroup();
        $lg->get( $id );

        $path = "";

        if ( $lg->parent() != 0 )
        {
            $path .= $this->printPath( $lg->parent(),  $url );
        }
        else
        {
            $path .= "<img src=\"/ezlink/images/path-arrow.gif\" border=\"0\" height=\"10\" width=\"15\"> <a class=\"path\" href=\"/link/group/0\">" . "Kategorier" . "</a>";
        }
        $path .= " <img src=\"/ezlink/images/path-slash.gif\" border=\"0\" height=\"10\" width=\"20\"> <a class=\"path\" href=\"/link/group/$id\">" . $lg->title() . "</a>";
        return $path;
    }


    /*!
      Henter ut parent
    */
    function &getByParent( $id )
    {

        $this->dbInit();
        $parent_array = array();
        $return_array = array();

        $this->Database->array_query( $parent_array, "SELECT ID FROM eZLink_LinkGroup WHERE Parent='$id' ORDER BY Title" );

        for( $i=0; $i<count( $parent_array ); $i++ )
        {
            $return_array[] = new eZLinkGroup( $parent_array[$i][ "ID" ] );
        }

        return $return_array;
                   
    }

    /*!
      Returnerer antall linker i alle underkategoriene.
     */
    function getTotalSubLinks( $id, $start_id )
    {
        $count = 0;
        $sibling_array = $this->getByParent( $id );

        if ( $id == $start_id )
        {
            $this->Database->array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE LinkGroup='$id' AND Accepted='Y'" );
            $count += $link_count[0][ "LinkCount" ];
        }
        
        for ( $i=0; $i<count( $sibling_array ); $i++ )
        {
            $group_id =  $sibling_array[ $i][ "ID" ];
            $count += $this->getTotalSubLinks( $group_id, $start_id );
            $this->Database->array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE LinkGroup='$group_id' AND Accepted='Y'" );
            $count += $link_count[0][ "LinkCount" ];            
        }
        return $count;
    }

    /*!
      Returnerer antall nye linker i alle underkategoriene.
      Alle linker som er nyere enn $new_limit antall dager blir regnet som nye.
     */
    function getNewSubLinks( $id, $start_id, $new_limit )
    {

        $count = 0;
        $sibling_array = $this->getByParent( $id );

        if ( $id == $start_id )
        {
            $this->Database->array_query( $link_count, "SELECT COUNT( ID ) AS LinkCount from eZLink_Link WHERE LinkGroup='$id' AND Accepted='Y' AND ( TO_DAYS( Now() ) - TO_DAYS( Created ) ) <= $new_limit  ORDER BY Title" );
            $count += $link_count[0][ "LinkCount" ];
        }
        
        for ( $i=0; $i<count( $sibling_array ); $i++ )
        {
            $group_id =  $sibling_array[ $i][ "ID" ];
            $count += $this->getNewSubLinks( $group_id, $start_id, $new_limit );
            $this->Database->array_query( $link_count, "SELECT COUNT( ID ) AS LinkCount  from eZLink_Link WHERE LinkGroup='$group_id' AND Accepted='Y' AND ( To_DAYS( Now() ) - TO_DAYS( Created ) ) <= $new_limit  ORDER BY Title" );
            $count += $link_count[0][ "LinkCount" ];            
        }
        return $count;
    }

    /*!
      Returnerer antall i incoming.
     */
    function getTotalIncomingLinks()
    {
        $count = 0;
        $this->Database->array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE Accepted='N'" );
        $count = $link_count[0][ "LinkCount" ];
        return $count;
    }
    
    /*!
      Henter ut _alt_
    */
    function getAll()
    {
        $this->dbInit();
        $parnet_array = array();
        $return_array = array();

        $this->Database->array_query( $parent_array, "SELECT ID FROM eZLink_LinkGroup ORDER BY Title" );

        for( $i=0; $i<count( $parent_array ); $i++ )
        {
            $return_array[$i] = new eZLinkGroup( $parent_array[$i]["ID"] );
        }

        return $return_array;
    }

    /*!
      Returns the id of the linkgroup.
    */
    function id()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ID;
    }

    /*!
      Setter navn.
    */
    function setTitle( $value )
    {
        $this->Title = ( $value );
    }

    /*!
      Setter parent.
    */
    function setParent( $value )
    {
        $this->Parent = ( $value );
    }

    /*!
      returnerer navn.
    */

    function Title()
    {
        return $this->Title;
    }

    /*!
      returnerer parent.
    */
    function parent()
    {
        return $this->Parent;

    }
    
	/*!
      Initiering av database
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Title;
    var $Parent;

    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>


