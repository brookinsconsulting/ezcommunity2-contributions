<?

class eZPhoneType
{
    /*
      Constructor.
    */
    function eZPhoneType( )
    {
        
    }


    /*
      Henter ut en adressetype med ID == $id
    */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $phone_type_array, "SELECT * FROM PhoneType WHERE ID='$id'" );
            if ( count( $phone_type_array ) > 1 )
            {
                die( "Feil: Flere phonetype med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $phone_type_array ) == 1 )
            {
                $this->ID = $phone_type_array[ 0 ][ "ID" ];
                $this->Name = $phone_type_array[ 0 ][ "Name" ];
            }
        }
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
      Sletter adressetypen fra databasen.
     */
    function delete()
    {
        $this->dbInit();
        query( "DELETE FROM PhoneType WHERE ID='$this->ID'" );
    }

    
    /*!
      Oppdaterer tabellen.
    */
    function update()
    {
        $this->dbInit();
        query( "UPDATE PhoneType set Name='$this->Name' WHERE ID='$this->ID'" );
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
