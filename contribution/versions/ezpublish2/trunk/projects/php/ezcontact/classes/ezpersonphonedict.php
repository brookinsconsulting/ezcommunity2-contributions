<?

/*
  Denne klassen håndterer link mellon personer og telefonnummer.
  Dette slik at en person kan ha flere telefonnummer uten at dette
  har konflikt med firma som er registrert.
*/

class eZPersonPhoneDict
{
    /*
      Constructor.
     */
    function eZPersonPhoneDict( )
    {

    }
    
    /*
      Lagrer en person->telefonnummer link i databasen.      
    */
    function store()
    {
        $this->dbInit();
        
        query( "INSERT INTO eZContact_PersonPhoneDict set PersonID='$this->PersonID',	PhoneID='$this->PhoneID' " );
        return mysql_insert_id();
    }

    /*
      Henter ut alle telefonnummer lagret i databasen hvor PersonID == $id.
    */
    function getByPerson( $id )
    {
        $this->dbInit();
        $phone_array = 0;

        array_query( $phone_array, "SELECT * FROM eZContact_PersonPhoneDict WHERE PersonID='$id'" );

        return $phone_array;
    }
    /*
      Henter ut med ID == $id
    */  
    function getByPhone( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $dict_array, "SELECT * FROM eZContact_PersonPhoneDict WHERE PhoneID='$id'" );
            if ( count( $dict_array ) > 1 )
            {
                die( "Feil: Flere dicter med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $dict_array ) == 1 )
            {
                $this->ID = $dict_array[ 0 ][ "ID" ];
                $this->PersonID = $dict_array[ 0 ][ "PersonID" ];
                $this->PhoneID = $dict_array[ 0 ][ "PhoneID" ];
            }
        }

        print( "phoneid:" . $this->ID );
    }

    /*
      Sletter dicten med ID == $id;
     */
    function delete()
    {
        $this->dbInit();
        
        query( "DELETE FROM eZContact_PersonPhoneDict WHERE ID='$this->ID'" );
    }

    /*
      Setter personID variablen.
    */
    function setPersonID( $value )
    {
        $this->PersonID = $value;
    }

    /*
      Setter phoneID variablen.
    */
    function setPhoneID( $value )
    {
        $this->PhoneID = $value;
    }
    
    /*
      Returnerer personID'en.
    */
    function personID()
    {
        return $this->PersonID;
    }

    /*
      Returnerer phoneID'en.
    */
    function phoneID()
    {
        return $this->PhoneID;
    }

    function id()
    {
        return $this->ID;
    }
    
    /*!
      Privat funksjon, skal kun brukes av ezusergroup klassen.
      Funksjon for å åpne databasen.
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
    var $PersonID;
    var $PhoneID;
}

?>
