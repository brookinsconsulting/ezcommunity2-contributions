<?
/*!
    $Id: ezsession.php,v 1.1 2000/08/16 11:40:56 bf-cvs Exp $

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
        openDB();
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
      -- foreløpig må vi ha en sessioncookie som parameter.. hmm, liker ikke dette
     */
    function get( $hash )
    {

        $ret = 1;

        openDB();
        if ( $hash != "" )
        {
            array_query( $session_array, "SELECT * FROM SessionTable WHERE sid='$hash'" );
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
  
    // alias for get. returns 0 (true) if validated, and 1 (false) if the session was not found in the DB
    function validate( $hash )
    {
        return $this->get( $hash );
    }

    function delete( $hash )
    {
        global $PREFIX;
        
        openDB();
        mysql_query("DELETE FROM SessionTable WHERE sid='$hash'")
            or die("delete session $hash failed, dying...");
    }
}

?>
