<?
/*!
    $Id: ezsession.php,v 1.1 2000/07/14 12:55:45 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no> (Bård Farstad <bf@ez.no>)
    
    Created on: Created on: <14-Jul-2000 13:06:16 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
class eZSession
{
    var $ID;
    var $Hash;
    var $UserID;

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

        mysql_query( "INSERT INTO session( sid, usr) VALUES( '$this->Hash', '$this->UserID')" )
            or die("could not insert session into db, dying...");

        
        return mysql_insert_id();
    }


    /*!
      Henter ut en session dersom $AuthenticatedSession er satt og
      den fortsatt er gyldig.
      -- foreløpig må vi ha en sessioncookie som parameter.. hmm, liker ikke dette
     */
    function get( $hash )
    {
//        $hash = $AuthenticatedSession;
        $ret = 1;
        $this->dbInit();    
        if ( $hash != "" )
        {
            array_query( $session_array, "SELECT * FROM session WHERE sid='$hash'" );
            if ( count( $session_array ) > 1 )
            {
                die( "Feil: Flere session med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $session_array ) == 1 )
            {
                $this->ID = $session_array[ 0 ][ "id" ];
                $this->Hash = $session_array[ 0 ][ "sid" ];
                $this->UserID = $session_array[ 0 ][ "usr" ];
                $ret = 1;
            }
            if (count ( $session_array ) == 1)
            {
                $ret = 0;
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
  
    /*!
    Initiering av database.
  */
    function dbInit()
    {
        require "ezforum/dbsettings.php";
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til server." );
        mysql_select_db( $DB ) or die( "Kunne ikke velge database" );
    }

    // alias for get. returns 0 (true) if validated, and 1 (false) if the session was not found in the DB
    function validate( $hash )
    {
        return $this->get( $hash );
    }

    function delete( $hash )
    {
        $this->dbInit();

        mysql_query("DELETE FROM session WHERE sid='$hash'")
            or die("delete session $hash failed, dying...");
    }
}

?>
