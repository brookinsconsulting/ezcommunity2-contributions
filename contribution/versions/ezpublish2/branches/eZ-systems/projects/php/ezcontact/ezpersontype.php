<?

class eZPersonType
{
  /*!
    Conctructor.
  */
  function eZPersonType( )
  {

  }

  /*!
    Lagrer informasjonen til databasen.
  */
  function store()
  {
    $this->dbInit();
    query( "INSERT INTO PersonType set Name='$this->Name', Comment='$this->Comment'" );
  }

  /*!

  */
  function get( $id )
  {

  }

  /*!

  */
  function getAll( )
  {
    $this->dbInit();
    $person_type_array = 0;
    
    array_query( $person_type_array, "SELECT * FROM PersonType ORDER BY Name" );
    
    return $person_type_array;
  }
  
  /*!
    Returnerer navnet.
  */
  function name( )
  {
    return $this->Name;
  }
  
  /*!
    Returnerer kommentaren.
  */
  function comment( )
  {
    return $this->Comment;
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
