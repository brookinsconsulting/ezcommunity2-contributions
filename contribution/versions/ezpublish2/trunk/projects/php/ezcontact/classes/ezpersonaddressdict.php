<?

class eZPersonAddressDict
{
    /*
      Constructor.
     */
    function eZPersonAddressDict( )
    {

    }

    /*
      Lagrer en person->adresse link i databasen.      
    */
    function store()
    {
        $this->dbInit();
        
        query( "INSERT INTO PersonAddressDict set PersonID='$this->PersonID', AddressID='$this->AddressID' " );
        return mysql_insert_id();
    }
    
    /*
      Henter ut alle adressene lagret i databasen hvor PersonID == $id.
    */
    function getByPerson( $id )
    {
        $this->dbInit();    
        $address_array = 0;
    
        array_query( $address_array, "SELECT * FROM PersonAddressDict WHERE PersonID='$id'" );
    
        return $address_array;
    }

    
    /*
      Setter personID variablen.
    */
    function setPersonID( $value )
    {
        $this->PersonID = $value;
    }

    /*
      Setter addressID variablen.
    */
    function setAddressID( $value )
    {
        $this->AddressID = $value;
    }
    
    /*
      Returnerer personID'en.
    */
    function personID()
    {
        return $this->PersonID;
    }

    /*
      Returnerer addressID'en.
    */
    function addressID()
    {
        return $this->AddressID;
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
    var $AddressID;    
}

?>
