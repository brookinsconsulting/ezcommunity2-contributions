<?

//!! eZContact
//!
/*!

*/

class eZPhoneType
{
    /*
      Constructor.
    */
    function eZPhoneType( $id="-1", $fetch=true )
    {
        $this->IsConnected = false;

        if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
                
            }
        }
        else
        {
            $this->State_ = "New";
        }
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

        $phone_type_edit = array();
        $return_array = array();
    
        $this->Database->array_query( $phone_type_array, "SELECT ID FROM eZContact_PhoneType" );

        foreach( $phone_type_array as $phoneTypeItem )
        {
            $return_array[] = new eZPhoneType( $phone_type_array["ID"] );
        }
        return $return_array;
    }


    /*!
      Lagrer en telefontyperow til databasen.
    */
    function store()
    {
        $this->dbInit();

        $ret = false;
        
        if ( !isSet( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZContact_PhoneType set Name='$this->Name'" );
            
            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $this->Database->query( "UPDATE eZContact_PhoneType set Name='$this->Name' WHERE ID='$this->ID'" );
            
            $this->State_ = "Coherent";
            $ret = true;
        }
        return $ret;
    }

    /*
      Sletter adressetypen fra databasen.
     */
    function delete()
    {
        $this->dbInit();
        $this->Database->query( "DELETE FROM eZContact_PhoneType WHERE ID='$this->ID'" );
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
      \private
      Open the database.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Name;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

    
}

?>
