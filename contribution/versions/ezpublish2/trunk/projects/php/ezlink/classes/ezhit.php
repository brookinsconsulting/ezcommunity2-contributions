<?php
// 
// $Id: ezhit.php,v 1.24 2001/07/11 08:09:37 jhe Exp $
//
// Definition of eZHit class
//
// Bård Farstad <bf@ez.no>
// Created on: <15-Sep-2000 14:40:06 bf>
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

//!! eZLink
//! The eZHit class handles URL hits. 
/*!
  The eZHit class stores hits and information about hits to the database.

   \sa eZLink eZLinkgroup eZQuery
*/

include_once( "classes/ezdb.php" );

class eZHit
{
    /*!
      Constructor
    */
    function eZHit( $id=-1, $fetch=true )
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
      Store to the database.
    */
    function store()
    {
        $this->dbInit();
        
        $res = $this->Database->query( "INSERT INTO eZLink_Hit
                                        (RemoteIP, ID, Link)
                                        VALUES
                                        ('$this->RemoteIP','$this->ID','$this->Link')" );
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Update to the database
    */
    function update()
    {
        $this->dbInit();
        $this->Database->query( "UPDATE eZLink_Hit SET
				RemoteIP='$this->RemoteIP',
                Link='$this->Link',
                WHERE ID='$this->ID'" );
    }

    /*!
      Delete from the database.
    */
    function delete()
    {
        $this->dbInit();                
        $this->Database->query( "DELETE FROM eZLink_Hit WHERE ID='$ID'" );
    }

    /*!
      Get out the count for one link.
     */

    function &getLinkHits( $id )
    {
        $this->dbInit();        
        $this->Database->array_query( $hit_array, "SELECT * FROM eZLink_Hit WHERE Link='$id'" );        
        $count = count( $hit_array );

        return $count;
    }
    
    /*!
      Get out the count for one link.
     */
    function get( $id )
    {
        $this->dbInit();
        $this->Database->array_query( $hit_array, "SELECT * FROM eZLink_Hit WHERE ID='$id'" );

        return count( $hit_array );
    }

    /*!
      Set the linkid.
    */
    function setLink( $value )
    {
        $this->Link = ( $value );
    }

    /*!
      Set the remote ip.
    */
    function setRemoteIP( $value )
    {
        $this->RemoteIP = ( $value );
    }

    /*!
      Return the id of the hit.
    */
    function id()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ID;
    }

    
    /*!
      Return linkid.
    */
    function link()
    {
        return $this->Link;
    }

    /*!
      Return the time.
    */
    function time()
    {
        return $this->Time;
    }

    /*!
      Return the ip to the link hit.
    */
    function remoteIP( )
    {
        return $this->RemoteIP;
    }
    
    /*!
      Initializing the database
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database =& eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }
      
    var $ID;
    var $Link;
    var $Time;
    var $RemoteIP;    

    /// Is true if the object has database connection, false if not.
    var $IsConnected;

    /// database connection indicator
    var $Database;

    /// internal object state
    var $State_;
}

?>
