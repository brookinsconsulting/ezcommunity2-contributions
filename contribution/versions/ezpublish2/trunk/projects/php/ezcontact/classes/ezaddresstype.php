<?
//!! eZContact
//!
/*!

*/

class eZAddressType
{
    /*!
      Constructor.
    */
    function eZAddressType( $id="-1", $fetch=true)
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
    
    /*!
      Lagrer en addressetyperow til databasen.      
    */
    function store()
    {
        $this->dbInit();

        $ret = false;
        
        if ( !isSet( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZContact_AddressType set Name='$this->Name'" );
            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $this->Database->query( "UPDATE eZContact_AddressType set Name='$this->Name' WHERE ID='$this->ID'" );

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
        $this->Database->query( "DELETE FROM eZContact_AddressType WHERE ID='$this->ID'" );
    }
    
  /*
    Henter ut en adressetype med ID == $id
  */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $address_type_array, "SELECT * FROM eZContact_AddressType WHERE ID='$id'" );
            if ( count( $address_type_array ) > 1 )
            {
                die( "Feil: Flere addresstype med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $address_type_array ) == 1 )
            {
                $this->ID = $address_type_array[ 0 ][ "ID" ];
                $this->Name = $address_type_array[ 0 ][ "Name" ];
            }
        }
    }

    /*
    Henter ut alle adresstypene lagret i databasen.
  */
    function getAll( )
    {
        $this->dbInit();    
        $online_type_array = 0;

        $address_type_array = array();
        $return_array = array();
    
        $this->Database->array_query( $address_type_array, "SELECT ID FROM eZContact_AddressType" );

        foreach( $address_type_array as $addressTypeItem )
        {
            $return_array[] = new eZAddressType( $addressTypeItem["ID"] );
        }
    
        return $return_array;
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

    /*!
      Returnerer id.
    */
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
