<?

/*
  Denne klassen håndterer link mellon companyer og telefonnummer.
  Dette slik at en company kan ha flere telefonnummer uten at dette
  har konflikt med firma som er registrert.
*/

class eZCompanyPhoneDict
{
    /*
      Constructor.
     */
    function eZCompanyPhoneDict( )
    {

    }
    
    /*
      Lagrer en company->telefonnummer link i databasen.      
    */
    function store()
    {
        $this->dbInit();
        
        query( "INSERT INTO CompanyPhoneDict set CompanyID='$this->CompanyID',	PhoneID='$this->PhoneID' " );
        return mysql_insert_id();
    }

    /*
      Henter ut med ID == $id
    */  
    function getByPhone( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $dict_array, "SELECT * FROM CompanyPhoneDict WHERE PhoneID='$id'" );
            if ( count( $dict_array ) > 1 )
            {
                die( "Feil: Flere dicter med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $dict_array ) == 1 )
            {
                $this->ID = $dict_array[ 0 ][ "ID" ];
                $this->FirstName = $dict_array[ 0 ][ "CompanyID" ];
                $this->LastName = $dict_array[ 0 ][ "PhoneID" ];
            }
        }
    }

    /*
      Sletter dicten med ID == $id;
     */
    function delete()
    {
        $this->dbInit();
        
        query( "DELETE FROM CompanyPhoneDict WHERE ID='$this->ID'" );
    }

    /*
      Henter ut alle telefonnummer lagret i databasen hvor CompanyID == $id.
    */
    function getByCompany( $id )
    {
        $this->dbInit();    
        $phone_array = 0;
    
        array_query( $phone_array, "SELECT * FROM CompanyPhoneDict WHERE CompanyID='$id'" );
    
        return $phone_array;
    }
    

    /*
      Setter companyID variablen.
    */
    function setCompanyID( $value )
    {
        $this->CompanyID = $value;
    }

    /*
      Setter phoneID variablen.
    */
    function setPhoneID( $value )
    {
        $this->PhoneID = $value;
    }
    
    /*
      Returnerer companyID'en.
    */
    function companyID()
    {
        return $this->CompanyID;
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
    var $CompanyID;
    var $PhoneID;
}

?>
