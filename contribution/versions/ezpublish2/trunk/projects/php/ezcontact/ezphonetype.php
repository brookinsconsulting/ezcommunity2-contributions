<?

class eZPhoneType
{
  /*
    Constructor.
  */
  function eZPhoneType( )
  {
        
  }

  /*!
    Lagrer en telefontyperow til databasen.
  */
  function store()
  {
    $this->dbInit();
    query( "INSERT INTO PhoneType set Name='$this->Name'" );
  }
  

  /*
    Henter ut alle telefontypene lagret i databasen.
  */
  function getAll( )
  {
    $this->dbInit();    
    $phone_type_array = 0;
    
    array_query( $phone_type_array, "SELECT * FROM PhoneType" );
    
    return $phone_type_array;
  }
  

  /*!
    Setter navnet.
  */
  function setName( $value )
  {
    $this->Name = $value;
  }

  /*!
    Returnerer navnet.
  */
  function name(  )
  {
    return $this->Name;
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
  var $Name;

}

?>
