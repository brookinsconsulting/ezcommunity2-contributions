<?

//!! eZContact
//!
/*!

*/

class eZPhone
{
    /*

    */
    function eZPhone( $id="", $fetch=true )
    {
        $this->IsConnected = false;
        
        if ( !empty( $id ) )
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
      Lagrer et telefonnummer link i databasen.      
    */
    function store()
    {
        $this->dbInit();
        
        $ret = false;
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZContact_Phone set Number='$this->Number', PhoneTypeID='$this->PhoneTypeID' " );
            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $this->Database->query( "UPDATE eZContact_Phone set Number='$this->Number', PhoneTypeID='$this->PhoneTypeID' WHERE ID='$this->ID' " );

            $this->State_ = "Coherent";
            $ret = true;            
        }        
        
        return $ret;
    }

    /*
      Sletter.
    */
    function delete()
    {
        $this->dbInit();
        
        $this->Database->query( "DELETE FROM eZContact_Phone WHERE ID='$this->ID' " );
    }
    
    /*
      Henter ut telefonnummer med ID == $id
    */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            $this->Database->array_query( $phone_array, "SELECT * FROM eZContact_Phone WHERE ID='$id'" );
            if ( count( $phone_array ) > 1 )
            {
                die( "Feil: Flere telefonnummer med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $phone_array ) == 1 )
            {
                $this->ID = $phone_array[ 0 ][ "ID" ];
                $this->Number = $phone_array[ 0 ][ "Number" ];
                $this->PhoneTypeID = $phone_array[ 0 ][ "PhoneTypeID" ];
            }
        }
    }

    function setNumber( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Number = $value;
    }

    function setPhoneTypeID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->PhoneTypeID = $value;
    }

    function setID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ID = $value;
    }
    
    function number( )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Number;
    }

    function phoneTypeID( )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->PhoneTypeID;
    }
    
    function id( )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
    var $Number;
    var $PhoneTypeID;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

}

?>
