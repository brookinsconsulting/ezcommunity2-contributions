<?

//require "ezphputils.php";

class eZCompany
{
  /*!
    Constructor
  */
  function eZCompany( )
  {
    $this->ID = 0;
  }

  /*!
    Lagrer informasjon til databasen.
  */
  function store( )
  {
    $this->dbInit();
    query( "INSERT INTO Company set Name='$this->Name',
	Comment='$this->Comment',
	ContactType='$this->ContactType',
	Owner='$this->Owner'" );
  }

  /*!
    Oppdaterer informasjonen som ligger i databasen.
  */
  function update( )
  {
    
  }
  
  /*!
    Henter ut et firma fra databasen.
  */
  function get( $id )
  {
    $this->dbInit();    
    if ( $id != "" )
    {
      array_query( $company_array, "SELECT * FROM Company WHERE ID='$id'" );
      if ( count( $company_array ) > 1 )
      {
          die( "Feil: Flere firma med samme ID funnet i database, dette skal ikke være mulig. " );
      }
      else if ( count( $company_array ) == 1 )
      {
        $this->ID = $company_array[ 0 ][ "ID" ];
        $this->Name = $company_array[ 0 ][ "Name" ];
        $this->Comment = $company_array[ 0 ][ "Comment" ];
        $this->Owner = $company_array[ 0 ][ "Owner" ];        
      }
    }
  }

  /*
    Henter ut alle firma lagret i databasen.
  */
  function getAll( )
  {
    $this->dbInit();    
    $company_array = 0;
    
    array_query( $company_array, "SELECT * FROM Company ORDER BY Name" );
    
    return $company_array;
  }

  /*
    Henter ut alle firma i databasen som inneholder søkestrengen.
  */
  function search( $query )
  {
    $this->dbInit();    
    $company_array = 0;
    
    array_query( $company_array, "SELECT * FROM Company WHERE Name LIKE '%$query%' ORDER BY Name" );
    
    return $company_array;
  }
    
  /*!
    Setter Navn.
  */
  function setName( $value )
  {
    $this->Name = $value;
  }

  /*!
    Setter kontakttype.
  */
  function setContactType( $value )
  {
    $this->ContactType = $value;
  }

/*!
    Setter kommentar.
  */
  function setComment( $value )
  {
    $this->Comment = $value;
  }

  /*!
    Setter eier.
  */
  function setOwner( $value )
  {
    $this->Owner = $value;
  }

  /*!
    Returnerer firmanavn.
  */
  function name()
  {
    return $this->Name;
  }

  /*!
    Returnerer ID til eier av firma ( brukeren som opprettet det ).
  */
  function owner()
  {
    return $this->Owner;
  }
    
  /*!
    Returnerer kontakttype.
  */
  function contactType()
  {
    return $this->ContactType;
  }
  
  /*!
    Returnerer kommentar.
  */
  function comment()
  {
    return $this->Comment;
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
  var $Owner;
  var $Name;
  var $Comment;
  var $ContactType;
}

?>
