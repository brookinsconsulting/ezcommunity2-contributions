<?

//!! eZContact
//!
/*!

*/

class eZCompanyConsultDict
{
    /*
      Constructor
    */
    function eZCompanyConsultDict()
    {
        
    }

    /*
      Lagrer en company->consult link i databasen.
    */
    function store()
    {
        $this->dbInit();

        query( "INSERT INTO eZContact_CompanyConsultDict set CompanyID='$this->CompanyID', ConsultID='$this->ConsultID'" );
        return mysql_insert_id();
    }

    /*
      Henter ut alle konsultasjoner lagret i databasen hvor CompanyID == $id.
    */
    function getByCompany( $id )
    {
        $this->dbInit();

        array_query( $consult_array,"SELECT * FROM eZContact_CompanyConsultDict WHERE CompanyID='$id'" );

        return $consult_array;
    }

    /*
      Henter ut konsultasjon med ID == $id
    */
    function getByConsult( $id )
    {
        $this->dbInit();
        if ( $id != "" )
        {
            array_query( $dict_array, "SELECT * FROM eZContact_CompanyConsultDict WHERE ConsultID='$id'" );
            if ( count( $dict_array ) > 1 )
            {
                die( "Feil: flere dickter med samme id ble funnet" );
            }
            else if ( count( $dict_array ) == 1 )
            {
                $this->ID = $dict_array[ 0 ][ "ID" ];
                $this->CompanyID = $dict_array[ 0 ][ "CompanyID" ];
                $this->ConsultID = $dict_array[ 0 ][ "ConsultID" ];
            }
        }
    }
    
    /*
      Sletter dict med ID == $id.
    */
    function delete()
    {
        $this->dbInit();

        query( "DELETE FROM eZContact_CompanyConsultDict WHERE ID='$this->ID'" );
    }
    
    /*
      Setter companyID variablen.
    */
    function setCompanyID( $value )
    {
        $this->CompanyID = $value;
    }

    /*
      Setter consultID variablen.
    */
    function setConsultID( $value )
    {
        $this->ConsultID = $value;
    }

    /*
      Returnerer companyID'en.
    */
    function companyID()
    {
        return $this->CompanyID;
    }
    
    /*
      Returnerer consultID'en.1
    */
    function consultID()
    {
        return $this->ConsultID;
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
    var $CompanyID;
    var $ConsultID;

}

?>
