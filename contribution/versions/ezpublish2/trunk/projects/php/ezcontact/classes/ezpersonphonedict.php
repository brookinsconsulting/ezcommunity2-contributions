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
