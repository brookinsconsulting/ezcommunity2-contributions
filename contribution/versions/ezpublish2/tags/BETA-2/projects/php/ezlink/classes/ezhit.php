<?
// 
// $Id: ezhit.php,v 1.18 2000/10/26 13:23:26 ce-cvs Exp $
//
// Definition of eZHit class
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
    function eZHit()
    {
        
    }

    /*!
      Lagrer i databasen
    */
    function store()
    {
        $this->dbInit();
        $this->Database->query( "INSERT INTO eZLink_Hit SET
				RemoteIP='$this->RemoteIP',
                ID='$this->ID',
                Link='$this->Link'" );
    }

    /*!
      Oppgraderer databasen
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
      Sletter fra databasen
    */
    function delete()
    {
        $this->dbInit();                
        $this->Database->query( "DELETE FROM eZLink_Hit WHERE ID='$ID'" );
    }

    /*!
      Henter ut antall hits på en bestemt link.
     */

    function getLinkHits( $id )
    {
        $this->dbInit();        
        $this->Database->array_query( $hit_array, "SELECT * FROM eZLink_Hit WHERE Link='$id'" );        
        $count = count( $hit_array );
        return $count;
    }
    
    /*!
      Henter ut antall hits på en bestemt link.
     */
    function get( $id )
    {
        $this->dbInit();
        $this->Database->array_query( $hit_array, "SELECT * FROM eZLink_Hit WHERE ID='$id'" );
        return count( $hit_array );
    }

    /*!
      Setter link id'en
    */
    function setLink( $value )
    {
        $this->Link = ( $value );
    }

    /*!
      Setter ip'en til brukeren.
    */
    function setRemoteIP( $value )
    {
        $this->RemoteIP = ( $value );
    }
    
    /*!
      Returnerer description
    */
    function link()
    {
        return $this->Link;
    }

    /*!
      Returnerer description
    */
    function time()
    {
        return $this->Time;
    }

    /*!
      Returnerer ip'en til brukeren.
    */
    function remoteIP( )
    {
        return $this->RemoteIP;
    }
    
    /*!
      Initierer database koplingen.
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
    var $Link;
    var $Time;
    var $RemoteIP;    

    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
