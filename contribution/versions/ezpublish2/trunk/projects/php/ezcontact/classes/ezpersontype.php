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
    Oppdaterer informasjonen til databasen.
  */
    function update()
    {
        $this->dbInit();

        print( $this->ID );

        query( "UPDATE eZContact_PersonType set Name='$this->Name', Description='$this->Description' WHERE ID='$this->ID'" );
    }



/*!
    Lagrer informasjonen til databasen.
  */
    function store()
    {
        $this->dbInit();

        query( "INSERT INTO eZContact_PersonType set Name='$this->Name', Description='$this->Description'" );
    }

    /*
      Sletter adressetypen fra databasen.
     */
    function delete()
    {
        $this->dbInit();
        query( "DELETE FROM eZContact_PersonType WHERE ID='$this->ID'" );
    }
    
    /*!

  */
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $persontype_array, "SELECT * FROM eZContact_PersonType WHERE ID='$id'" );
            if ( count( $persontype_array ) > 1 )
            {
                die( "Feil: Flere userer med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $persontype_array ) == 1 )
            {
                $this->ID = $persontype_array[ 0 ][ "ID" ];
                $this->Name = $persontype_array[ 0 ][ "Name" ];
                $this->Description = $persontype_array[ 0 ][ "Description" ];
            }
        }
    }

    /*!

  */
    function getAll( )
    {
        $this->dbInit();
        $person_type_array = 0;
    
        array_query( $person_type_array, "SELECT * FROM eZContact_PersonType ORDER BY Name" );
    
        return $person_type_array;
    }

    /*!
      Setter navn.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }
    /*!
      Setter navn.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
        
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
    function description( )
    {
        return $this->Description;
    }    

    /*!
      Privat funksjon, skal kun brukes av ezusergroup klassen.
      Funksjon for å åpne databasen.
    */
    function dbInit()
    {
        include_once( "classes/class.INIFile.php" );

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
    var $Description;    
}

?>
