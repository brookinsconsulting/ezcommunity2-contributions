<?

//!! eZAddress
//!
/*!

*/

include_once( "classes/ezdb.php" );
include_once( "ezaddress/classes/ezphonetype.php" );

class eZPhone
{
    /*

    */
    function eZPhone( $id="", $fetch=true )
    {
        if ( !empty( $id ) )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                
                $this->get( $this->ID );
            }
        }
    }

    /*
      Lagrer et telefonnummer link i databasen.      
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        
        $ret = false;
        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZAddress_Phone set Number='$this->Number', PhoneTypeID='$this->PhoneTypeID' " );
            $this->ID = mysql_insert_id();

            $ret = true;
        }
        else
        {
            $db->query( "UPDATE eZAddress_Phone set Number='$this->Number', PhoneTypeID='$this->PhoneTypeID' WHERE ID='$this->ID' " );

            $ret = true;            
        }        
        
        return $ret;
    }

    /*
      Sletter.
    */
    function delete( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZAddress_Phone WHERE ID='$id' " );
    }
    
    /*
      Henter ut telefonnummer med ID == $id
    */  
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $phone_array, "SELECT * FROM eZAddress_Phone WHERE ID='$id'" );
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
        $this->Number = $value;
    }

    function setPhoneTypeID( $value )
    {
        if( is_numeric( $value ) )
        {
            $this->PhoneTypeID = $value;
        }
        
        if( get_class( $value ) == "ezphonetype" )
        {
            $this->PhoneTypeID = $value->id();
        }
    }

    function setPhoneType( $value )
    {
        if( is_numeric( $value ) )
        {
            $this->PhoneTypeID = $value;
        }
        
        if( get_class( $value ) == "ezphonetype" )
        {
            $this->PhoneTypeID = $value->id();
        }
    }

    function setID( $value )
    {
        $this->ID = $value;
    }
    
    function number( )
    {
        return $this->Number;
    }

    function phoneTypeID( )
    {
        return $this->PhoneTypeID;
    }
    
    function phoneType( )
    {
        $phoneType = new eZPhoneType( $this->PhoneTypeID );
        return $phoneType;
    }
    
    function id( )
    {
        return $this->ID;
    }

    var $ID;
    var $Number;
    var $PhoneTypeID;
}

?>
