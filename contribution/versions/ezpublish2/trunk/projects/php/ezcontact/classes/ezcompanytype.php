<?

class eZCompanyType
{
    /*!
      Constructor.
    */
    function eZCompanyType( )
    {
    
    }

    /*!
      Lagrer informasjonen til databasen.
    */
    function store()
    {
        $this->dbInit();
        query( "INSERT INTO CompanyType set Name='$this->Name', Comment='$this->Comment'" );
    }

    /*
      Henter ut en firmatype med ID == $id
    */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $company_type_array, "SELECT * FROM CompanyType WHERE ID='$id'" );
            if ( count( $company_type_array ) > 1 )
            {
                die( "Feil: Flere companytype med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $company_type_array ) == 1 )
            {
                $this->ID = $company_type_array[ 0 ][ "ID" ];
                $this->Name = $company_type_array[ 0 ][ "Name" ];
                $this->Comment = $company_type_array[ 0 ][ "Name" ];
            }
        }
    }
    
    /*!

     */
    function getAll( )
    {
        $this->dbInit();
        $company_type_array = 0;
    
        array_query( $company_type_array, "SELECT * FROM CompanyType ORDER BY Name" );
    
        return $company_type_array;
    }
  
  
    /*!
      Privat: Initiering av database. 
    */
    function dbInit()
    {
        require "ezcontact/dbsettings.php";
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $Name;
    var $Comment;
}

?>
