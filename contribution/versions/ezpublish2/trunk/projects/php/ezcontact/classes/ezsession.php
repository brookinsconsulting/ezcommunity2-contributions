<?

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

        setcookie ( "AuthenticatedSession", $this->Hash, time() + 3600, "/",  "", 0 ) or die( "Feil: kunne ikke sette cookie." );
    
        query( "INSERT INTO Session set SID='$this->Hash', Usr='$this->UserID'" );

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
        $ret = 0;
        $this->dbInit();    
        if ( $hash != "" )
        {
            array_query( $session_array, "SELECT * FROM Session WHERE SID='$hash'" );
            if ( count( $session_array ) > 1 )
            {
                die( "Feil: Flere session med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $session_array ) == 1 )
            {
                $this->ID = $session_array[ 0 ][ "ID" ];
                $this->Hash = $session_array[ 0 ][ "SID" ];
                $this->UserID = $session_array[ 0 ][ "Usr" ];
                $ret = 1;
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
        require "ezcontact/dbsettings.php";
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $Hash;
    var $UserID;
}

?>
