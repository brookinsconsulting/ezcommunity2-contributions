<?
// 
// $Id: ezhit.php,v 1.13 2000/09/15 12:47:35 bf-cvs Exp $
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
        include_once( "classes/INIFile.php" );
        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "eZLinkMain", "Server" );
        $DATABASE = $ini->read_var( "eZLinkMain", "Database" );
        $USER = $ini->read_var( "eZLinkMain", "User" );
        $PWD = $ini->read_var( "eZLinkMain", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

        
    var $ID;
    var $Link;
    var $Time;
    var $RemoteIP;    

}

?>
