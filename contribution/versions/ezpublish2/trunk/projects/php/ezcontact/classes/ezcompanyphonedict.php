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
