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
