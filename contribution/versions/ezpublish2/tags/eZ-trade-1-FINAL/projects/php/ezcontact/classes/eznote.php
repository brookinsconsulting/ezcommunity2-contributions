<?

//!! eZContact
//!
/*!

*/

class eZNote
{
    /*
      Constructor.
     */
    function eZNote( )
    {
    }

    /*
      Lagrer en notat i databasen.
     */
    function store( )
    {
        $this->dbInit();
        query( "INSERT INTO eZContact_Note set Title='$this->Title',
		Body='$this->Body',
		UserID='$this->UserID' " );
        return mysql_insert_id();
    }

    /*
      Sletter adressetypen fra databasen.
     */
    function delete()
    {
        $this->dbInit();
        query( "DELETE FROM eZContact_Note WHERE ID='$this->ID'" );
    }

    
    /*!
      Oppdaterer tabellen.
    */
    function update()
    {
        $this->dbInit();
        query( "UPDATE eZContact_Note set Title='$this->Title', Body='$this->Body' WHERE ID='$this->ID'" );
    }


    
    /*
      Henter ut Notat med ID == $id
    */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $note_array, "SELECT * FROM eZContact_Note WHERE ID='$id'" );
            if ( count( $note_array ) > 1 )
            {
                die( "Feil: Flere notater med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $note_array ) == 1 )
            {
                $this->ID = $note_array[ 0 ][ "ID" ];
                $this->Title = $note_array[ 0 ][ "Title" ];
                $this->Body = $note_array[ 0 ][ "Body" ];
                $this->UserID = $note_array[ 0 ][ "UserID" ];
            }
        }
    }


    /*
      Henter ut alle notatene lagret i databasen med UserID == $id.
    */
    function getAllByUser( $id )
    {
        $this->dbInit();    
        $note_array = 0;
    
        array_query( $note_array, "SELECT * FROM eZContact_Note WHERE UserID='$id' ORDER BY Title" );
    
        return $note_array;
    }    

    function setTitle( $value )
    {
        $this->Title = $value;
    }

    function setBody( $value )
    {
        $this->Body = $value;
    }

    function setUserID( $value )
    {
        $this->UserID = $value;
    }

    function title( )
    {
        return $this->Title;
    }

    function body( )
    {
        return $this->Body;
    }

    function userID(  )
    {
        return $this->UserID;
    }
    

    /*
      Privat: Initiering av database. 
    */
    function dbInit()
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
    var $Title;
    var $Body;
    var $UserID;
}

?>
