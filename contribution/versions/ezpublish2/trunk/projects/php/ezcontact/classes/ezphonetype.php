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
            $this->Database->array_query( $phone_type_array, "SELECT * FROM eZContact_PhoneType WHERE ID='$id'" );
            if ( count( $phone_type_array ) > 1 )
            {
                die( "Feil: Flere phonetype med samme ID funnet i database, dette skal ikke v�re mulig. " );
            }
            else if ( count( $phone_type_array ) == 1 )
            {
                $this->ID = $phone_type_array[ 0 ][ "ID" ];
                $this->Name = $phone_type_array[ 0 ][ "Name" ];
                $this->ListOrder = $phone_type_array[ 0 ][ "ListOrder" ];
            }
            else
            {
                $this->ID = "";
                $this->State_ = "New";
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
    
        $this->Database->array_query( $phone_type_array, "SELECT ID FROM eZContact_PhoneType ORDER BY ListOrder" );

        foreach( $phone_type_array as $phoneTypeItem )
        {
            $return_array[] = new eZPhoneType( $phoneTypeItem["ID"] );
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
            $this->Database->query( "UPDATE eZContact_PhoneType set Name='$this->Name', ListOrder='$this->ListOrder' WHERE ID='$this->ID'" );
            
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
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Name = $value;
    }

    function name(  )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Name;
    }  

    function id(  )
    {
        return $this->ID;
    }  
    
    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */

    function moveUp()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_PhoneType
                                  WHERE ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZContact_PhoneType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZContact_PhoneType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */

    function moveDown()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_PhoneType
                                  WHERE ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZContact_PhoneType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZContact_PhoneType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
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
