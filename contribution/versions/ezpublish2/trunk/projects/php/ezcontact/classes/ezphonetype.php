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
            array_query( $phone_type_array, "SELECT * FROM eZContact_PhoneType WHERE ID='$id'" );
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

    /*
    Henter ut alle telefontypene lagret i databasen.
  */
    function getAll( )
    {
        $this->dbInit();    
        $phone_type_array = 0;
    
        array_query( $phone_type_array, "SELECT * FROM eZContact_PhoneType" );
    
        return $phone_type_array;
    }
    


    /*!
      Lagrer en telefontyperow til databasen.
    */
    function store()
    {
        $this->dbInit();
        query( "INSERT INTO eZContact_PhoneType set Name='$this->Name'" );
    }

    /*
      Sletter adressetypen fra databasen.
     */
    function delete()
    {
        $this->dbInit();
        query( "DELETE FROM eZContact_PhoneType WHERE ID='$this->ID'" );
    }

    
    /*!
      Oppdaterer tabellen.
    */
    function update()
    {
        $this->dbInit();
        query( "UPDATE eZContact_PhoneType set Name='$this->Name' WHERE ID='$this->ID'" );
    }
  

    /*
      Henter ut alle telefontypene lagret i databasen.
    */
    function getAll( )
    {
        $this->dbInit();    
        $phone_type_array = 0;
    
        array_query( $phone_type_array, "SELECT * FROM eZContact_PhoneType" );
    
        return $phone_type_array;
    }
  

    function setName( $value )
    {
        $this->Name = $value;
    }

    function setID( $value )
    {
        $this->ID = $value;
    }
    
    function name(  )
    {
        return $this->Name;
    }  

    function id(  )
    {
        return $this->ID;
    }  
    
    /*!
      Privat funksjon, skal kun brukes av ezusergroup klassen.
      Funksjon for å åpne databasen.
    */
    function dbInit()
    {
        include_once( "class.INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "site", "Server" );
        $DATABASE = $ini->read_var( "site", "Database" );
        $USER = $ini->read_var( "site", "User" );
        $PWD = $ini->read_var( "site", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $Name;

}

?>
