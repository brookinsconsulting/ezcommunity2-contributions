<?
// 
// $Id: ezsession.php,v 1.9 2000/09/25 15:10:12 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>, Lars Wilhelmsen <lw@ez.no>
// Created on: <14-Jul-2000 13:06:16 lw>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//


//!! eZCommon
//! The eZSession class provides session handling.
/*!
  
*/

class eZSession
{

   /*!
      Constructor.
    */
    function eZSession( )
    {
         
    }

    /*!    
      Lagrer en session og setter en cookie. AuthenticatedSession
    */    
    function store( )
    {
        $this->dbInit();
        $this->Hash = md5( time() );

        setcookie ( "AuthenticatedSession", $this->Hash, 0, "/",  "", 0 )
            or die( "Feil: kunne ikke sette cookie." );


        mysql_query( "INSERT INTO SessionTable( sid, usr) VALUES( '$this->Hash', '$this->UserID')" )
            or die("could not insert session into db, dying...");

        
        return mysql_insert_id();
    }


    /*!
      Henter ut en session dersom $AuthenticatedSession er satt og
      den fortsatt er gyldig.
     */
    function get( $hash="" )
    {
        $ret = true;

        if ( $hash == "" )
        {
            $hash = $GLOBALS["AuthenticatedSession"];
        }
        
        $this->dbInit();
        if ( $hash != "" )
        {
            array_query( $session_array, "SELECT * FROM SessionTable WHERE sid='$hash'" );
            if ( count( $session_array ) > 1 )
            {
                print ( count( $session_array ) );
                die( "Feil: Flere session med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $session_array ) == 1 )
            {
                $this->ID = $session_array[ 0 ][ "id" ];
                $this->Hash = $session_array[ 0 ][ "sid" ];
                $this->UserID = $session_array[ 0 ][ "usr" ];
                $ret = false;
            }
        }
        return $ret;
    }
  
    /*!
    Setter hash.
  */
    function setHash( $value )
    {
        $this->Hash = $value;
    }

    /*!
    Setter brukerid'en til gjeldende session.
  */
    function setUserID( $value )
    {
        $this->UserID = $value;
    }  

    /*!
      Returnerer hash.
    */
    function hash( )
    {
        return $this->Hash;
    }

    /*!
      Returnerer brukerid'en til gjeldende session.
    */
    function userID(  )
    {
        return $this->UserID;
    }  
  
    // alias for get. returns 0 (true) if validated, and 1 (false)
    // if the session was not found in the DB
    function validate( $hash )
    {
        return $this->get( $hash );
    }

    function delete( $hash )
    {
        setcookie ( "AuthenticatedSession", "", 0, "/",  "", 0 )
            or die( "Feil: kunne ikke sette cookie." );
        
        $this->dbInit();
        mysql_query("DELETE FROM SessionTable WHERE sid='$hash'")
            or die("delete session $hash failed, dying...");
    }

    /*!
      Privat funksjon, skal kun brukes ac ezuser klassen.
      Funksjon for å åpne databasen.
    */
    function dbInit( )
    {
        include_once( "classes/INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "site", "Server" );
        $DATABASE = $ini->read_var( "site", "Database" );
        $USER = $ini->read_var( "site", "User" );
        $PWD = $ini->read_var( "site", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }
    
    var $ID;
    var $Hash;
    var $UserID;
}

?>
