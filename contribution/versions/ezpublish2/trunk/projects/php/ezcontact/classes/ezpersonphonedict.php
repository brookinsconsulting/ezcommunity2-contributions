<?

/*
  Denne klassen h�ndterer link mellon personer og telefonnummer.
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
        
        query( "INSERT INTO PersonPhoneDict set PersonID='$this->PersonID',	PhoneID='$this->PhoneID' " );
        return mysql_insert_id();
    }

    /*
      Henter ut alle telefonnummer lagret i databasen hvor PersonID == $id.
    */
    function getByPerson()
    {
        $this->dbInit();
        $phone_array = 0;

        array_query( $phone_array, "SELECT * FROM PersonPhoneDict WHERE PersonID='$ID'" );

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
            array_query( $dict_array, "SELECT * FROM PersonPhoneDict WHERE PhoneID='$id'" );
            if ( count( $dict_array ) > 1 )
            {
                die( "Feil: Flere dicter med samme ID funnet i database, dette skal ikke v�re mulig. " );
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
        
        query( "DELETE FROM PersonPhoneDict WHERE ID='$this->ID'" );
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
    
    /*
      Privat: Initiering av database. 
    */
    function dbInit()
    {
        require "ezcontact/dbsettings.php";
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $PersonID;
    var $PhoneID;
}

?>
