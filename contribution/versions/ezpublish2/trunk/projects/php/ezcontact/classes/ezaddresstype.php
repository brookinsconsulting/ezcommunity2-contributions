<?
//!! eZContact
//!
/*!

*/

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompany.php" );

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
        $db = eZDB::globalDatabase();

        $ret = false;
        if ( !isSet( $this->ID ) )
        {
            $db->query_single( $qry, "SELECT ListOrder from eZContact_AddressType ORDER BY ListOrder DESC LIMIT 1" );
            $listorder = $qry["ListOrder"] + 1;
            $this->ListOrder = $listorder;

            $db->query( "INSERT INTO eZContact_AddressType set Name='$this->Name', ListOrder='$this->ListOrder'" );
            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $db->query( "UPDATE eZContact_AddressType set Name='$this->Name', ListOrder='$this->ListOrder' WHERE ID='$this->ID'" );

            $this->State_ = "Coherent";
            $ret = true;
        }
        return $ret;
    }

    /*
      Sletter adressetypen fra databasen,
      if $relations is true all relations to this item is deleted too,
      if $relations is "full" all persons and companies are deleted too.
     */

    function delete( $relations = false )
    {
        $db = eZDB::globalDatabase();
        if ( $relations == "full" )
        {
            $db->array_query( $person_qry, "SELECT Pe.ID
                                            FROM eZContact_Person AS Pe, eZContact_PersonAddressDict AS PAD,
                                                 eZContact_Address AS Ad
                                            WHERE Pe.ID = PAD.PersonID AND PAD.AddressID = Ad.ID AND AddressTypeID='$this->ID'" );
            foreach( $person_qry as $person )
                {
                    eZPerson::delete( $person["ID"] );
                }
            $db->array_query( $company_qry, "SELECT Co.ID
                                             FROM eZContact_Company AS Co, eZContact_CompanyAddressDict AS CAD,
                                                  eZContact_Address AS Ad
                                             WHERE Co.ID = CAD.CompanyID AND CAD.AddressID = Ad.ID AND AddressTypeID='$this->ID'" );
            foreach( $company_qry as $company )
                {
                    eZCompany::delete( $company["ID"] );
                }
        }
        else if ( $relations )
        {
            $db->array_query( $person_qry, "SELECT A.PersonID, A.AddressID
                                            FROM eZContact_PersonAddressDict AS A, eZContact_Address AS B
                                            WHERE A.AddressID = B.ID AND B.AddressTypeID='$this->ID'" );
            foreach( $person_qry as $person )
                {
                    $person_id = $person["PersonID"];
                    $address_id = $person["AddressID"];
                    $db->query( "DELETE FROM eZContact_PersonAddressDict WHERE PersonID='$person_id' AND AddressID='$address_id'" );
                    $db->query( "DELETE FROM eZContact_Address WHERE ID='$address_id'" );
                }
            $db->array_query( $company_qry, "SELECT A.CompanyID, A.AddressID
                                             FROM eZContact_CompanyAddressDict AS A, eZContact_Address AS B
                                             WHERE A.AddressID = B.ID AND B.AddressTypeID='$this->ID'" );
            foreach( $company_qry as $company )
                {
                    $company_id = $company["CompanyID"];
                    $address_id = $company["AddressID"];
                    $db->query( "DELETE FROM eZContact_CompanyAddressDict WHERE CompanyID='$company_id' AND AddressID='$address_id'" );
                    $db->query( "DELETE FROM eZContact_Address WHERE ID='$address_id'" );
                }
        }
        $db->query( "DELETE FROM eZContact_AddressType WHERE ID='$this->ID'" );
    }
    
  /*
    Henter ut en adressetype med ID == $id
  */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            $this->Database->array_query( $address_type_array, "SELECT * FROM eZContact_AddressType WHERE ID='$id'" );
            if ( count( $address_type_array ) > 1 )
            {
                die( "Feil: Flere addresstype med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $address_type_array ) == 1 )
            {
                $this->ID = $address_type_array[ 0 ][ "ID" ];
                $this->Name = $address_type_array[ 0 ][ "Name" ];
                $this->ListOrder = $address_type_array[ 0 ][ "ListOrder" ];
            }
            else
            {
                $this->ID = "";
                $this->State_ = "New";
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
    
        $this->Database->array_query( $address_type_array, "SELECT ID FROM eZContact_AddressType ORDER BY ListOrder" );

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
      Returns the number of external items using this item.
    */

    function count()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->array_query( $person_qry,  "SELECT count( Pe.ID ) as Count
                                         FROM eZContact_Person AS Pe, eZContact_PersonAddressDict AS PAD,
                                              eZContact_Address AS Ad, eZContact_AddressType AS AT
                                         WHERE Pe.ID = PAD.PersonID AND PAD.AddressID = Ad.ID AND Ad.AddressTypeID = AT.ID AND AddressTypeID='$this->ID'" );
        $db->array_query( $company_qry, "SELECT count( Co.ID ) as Count
                                         FROM eZContact_Company AS Co, eZContact_CompanyAddressDict AS CAD,
                                              eZContact_Address AS Ad, eZContact_AddressType AS AT
                                         WHERE Co.ID = CAD.CompanyID AND CAD.AddressID = Ad.ID AND Ad.AddressTypeID = AT.ID AND AddressTypeID='$this->ID'" );
        $cnt = 0;
        if ( count( $company_qry ) > 0 )
            $cnt += $company_qry[0]["Count"];
        if ( count( $person_qry ) > 0 )
            $cnt += $person_qry[0]["Count"];
        return $cnt;
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */

    function moveUp()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_AddressType
                                  WHERE ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZContact_AddressType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZContact_AddressType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */

    function moveDown()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_AddressType
                                  WHERE ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZContact_AddressType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZContact_AddressType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
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
    var $ListOrder;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

}

?>
