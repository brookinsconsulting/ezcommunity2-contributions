<?
class eZHit
{
    /*
      Constructor
    */

    function eZHit()
    {
        
    }

    /*
      Lagrer i databasen
    */
    function store()
    {
        $this->dbInit();
        query( "INSERT INTO Hit SET
				RemoteIP='$this->RemoteIP',
                ID='$this->ID',
                Link='$this->Link'" );
    }


    /*
      Oppgraderer databasen
    */
    function update()
    {
        $this->dbInit();
        query( "UPDATE Hit SET
				RemoteIP='$this->RemoteIP',
                Link='$this->Link',
                WHERE ID='$this->ID'" );
    }

    /*
      Sletter fra databasen
    */
    function delete()
    {
        $this->dbInit();                
        query( "DELETE FROM Hit WHERE ID='$ID'" );
    }

    /*
      Henter ut antall hits p� en bestemt link.
     */

    function getLinkHits( $id )
    {
        $this->dbInit();        
        array_query( $hit_array, "SELECT * FROM Hit WHERE Link='$id'" );        
        $count = count( $hit_array );
        return $count;
    }
    function get( $id )
    {
        $this->dbInit();
        array_query( $hit_array, "SELECT * FROM Hit WHERE ID='$id'" );
        return count( $hit_array );
    }

    /*
      Setter link id'en
    */

    function setLink( $value )
    {
        $this->Link = ( $value );
    }

    /*
      Setter ip'en til brukeren.
    */
    function setRemoteIP( $value )
    {
        $this->RemoteIP = ( $value );
    }
    
    /*
      Returnerer description
    */

    function link()
    {
        return $this->Link;
    }

    /*
      Returnerer description
    */
    function time()
    {
        return $this->Time;
    }

    /*
      Returnerer ip'en til brukeren.
    */
    function remoteIP( )
    {
        return $this->RemoteIP;
    }
    
    function dbInit()
    {
        require "ezlink/dbsettings.php";
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

        
    var $ID;
    var $Link;
    var $Time;
    var $RemoteIP;    

}

?>
