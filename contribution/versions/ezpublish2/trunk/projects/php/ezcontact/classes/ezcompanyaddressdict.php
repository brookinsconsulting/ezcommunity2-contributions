<?

class eZCompanyAddressDict
{
    /*
      Constructor.
     */
    function eZCompanyAddressDict( )
    {

    }

    /*
      Lagrer en Company->adresse link i databasen.      
    */
    function store()
    {
        $this->dbInit();
        
        query( "INSERT INTO CompanyAddressDict set CompanyID='$this->CompanyID', AddressID='$this->AddressID' " );
        return mysql_insert_id();
    }
    
    /*
      Henter ut alle adressene lagret i databasen hvor CompanyID == $id.
    */
    function getByCompany( $id )
    {
        $this->dbInit();    
        $address_array = 0;
    
        array_query( $address_array, "SELECT * FROM CompanyAddressDict WHERE CompanyID='$id'" );
    
        return $address_array;
    }

    /*
      Henter ut med ID == $id
    */  
    function getByAddress( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $dict_array, "SELECT * FROM CompanyAddressDict WHERE AddressID='$id'" );
            if ( count( $dict_array ) > 1 )
            {
                die( "Feil: Flere dicter med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $dict_array ) == 1 )
            {
                $this->ID = $dict_array[ 0 ][ "ID" ];
                $this->CompanyID = $dict_array[ 0 ][ "CompanyID" ];
                $this->AddressID = $dict_array[ 0 ][ "AddressID" ];
            }
        }
    }
    

    /*
      Sletter dicten med ID == $id;
     */
    function delete()
    {
        $this->dbInit();
        
        query( "DELETE FROM CompanyAddressDict WHERE ID='$this->ID'" );
    }    

    /*
      Setter CompanyID variablen.
    */
    function setCompanyID( $value )
    {
        $this->CompanyID = $value;
    }

    /*
      Setter addressID variablen.
    */
    function setAddressID( $value )
    {
        $this->AddressID = $value;
    }
    
    /*
      Returnerer CompanyID'en.
    */
    function CompanyID()
    {
        return $this->CompanyID;
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
    var $CompanyID;
    var $AddressID;    
}

?>
