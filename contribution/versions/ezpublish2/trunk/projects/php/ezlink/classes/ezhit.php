<?
// 
// $Id: ezhit.php,v 1.14 2000/10/17 10:27:29 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <15-Sep-2000 14:40:06 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
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
        query( "INSERT INTO eZLink_Hit SET
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
        query( "UPDATE eZLink_Hit SET
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
        query( "DELETE FROM eZLink_Hit WHERE ID='$ID'" );
    }

    /*!
      Henter ut antall hits på en bestemt link.
     */

    function getLinkHits( $id )
    {
        $this->dbInit();        
        array_query( $hit_array, "SELECT * FROM eZLink_Hit WHERE Link='$id'" );        
        $count = count( $hit_array );
        return $count;
    }
    
    /*!
      Henter ut antall hits på en bestemt link.
     */
    function get( $id )
    {
        $this->dbInit();
        array_query( $hit_array, "SELECT * FROM eZLink_Hit WHERE ID='$id'" );
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
