<?
//!! eZAddress
//!
/*!

*/

//  include_once( "ezaddress/classes/ezperson.php" );
//  include_once( "ezaddress/classes/ezcompany.php" );

class eZAddressType
{
    /*!
      Constructor.
    */
    function eZAddressType( $id="-1", $fetch=true)
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                
                $this->get( $this->ID );
            }
        }
    }
    
    /*!
      Lagrer en addressetyperow til databasen.      
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        $name = addslashes( $this->Name );
        if ( !isSet( $this->ID ) )
        {
            $db->query_single( $qry, "SELECT ListOrder from eZAddress_AddressType ORDER BY ListOrder DESC LIMIT 1" );
            $listorder = $qry["ListOrder"] + 1;
            $this->ListOrder = $listorder;

            $db->query( "INSERT INTO eZAddress_AddressType set Name='$name', ListOrder='$this->ListOrder'" );
			$this->ID = $db->insertID();

            $ret = true;
        }
        else
        {
            $db->query( "UPDATE eZAddress_AddressType set Name='$name', ListOrder='$this->ListOrder' WHERE ID='$this->ID'" );

            $ret = true;
        }
        return $ret;
    }

    /*
      Sletter adressetypen fra databasen,
      if $relations is true all relations to this item is deleted too,
      if $relations is "full" all persons and companies are deleted too.
     */

    function delete( $relations = false, $id = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;
//          if ( $relations == "full" )
//          {
//              $db->array_query( $person_qry, "SELECT Pe.ID
//                                              FROM eZContact_Person AS Pe, eZContact_PersonAddressDict AS PAD,
//                                                   eZAddress_Address AS Ad
//                                              WHERE Pe.ID = PAD.PersonID AND PAD.AddressID = Ad.ID AND AddressTypeID='$id'" );
//              foreach( $person_qry as $person )
//                  {
//                      eZPerson::delete( $person["ID"] );
//                  }
//              $db->array_query( $company_qry, "SELECT Co.ID
//                                               FROM eZContact_Company AS Co, eZContact_CompanyAddressDict AS CAD,
//                                                    eZAddress_Address AS Ad
//                                               WHERE Co.ID = CAD.CompanyID AND CAD.AddressID = Ad.ID AND AddressTypeID='$id'" );
//              foreach( $company_qry as $company )
//                  {
//                      eZCompany::delete( $company["ID"] );
//                  }
//          }
//          else if ( $relations )
//          {
//              $db->array_query( $person_qry, "SELECT A.PersonID, A.AddressID
//                                              FROM eZContact_PersonAddressDict AS A, eZContact_Address AS B
//                                              WHERE A.AddressID = B.ID AND B.AddressTypeID='$id'" );
//              foreach( $person_qry as $person )
//                  {
//                      $person_id = $person["PersonID"];
//                      $address_id = $person["AddressID"];
//                      $db->query( "DELETE FROM eZContact_PersonAddressDict WHERE PersonID='$person_id' AND AddressID='$address_id'" );
//                      $db->query( "DELETE FROM eZAddress_Address WHERE ID='$address_id'" );
//                  }
//              $db->array_query( $company_qry, "SELECT A.CompanyID, A.AddressID
//                                               FROM eZContact_CompanyAddressDict AS A, eZAddress_Address AS B
//                                               WHERE A.AddressID = B.ID AND B.AddressTypeID='$id'" );
//              foreach( $company_qry as $company )
//                  {
//                      $company_id = $company["CompanyID"];
//                      $address_id = $company["AddressID"];
//                      $db->query( "DELETE FROM eZContact_CompanyAddressDict WHERE CompanyID='$company_id' AND AddressID='$address_id'" );
//                      $db->query( "DELETE FROM eZAddress_Address WHERE ID='$address_id'" );
//                  }
//          }
//          $db->query( "DELETE FROM eZAddress_AddressType WHERE ID='$id'" );
        $db->query( "UPDATE eZAddress_AddressType SET Removed=1 WHERE ID='$id'" );
    }
    
    /*
      Henter ut en adressetype med ID == $id
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $address_type_array, "SELECT * FROM eZAddress_AddressType
                                                    WHERE ID='$id'",
                              0, 1 );
            if ( count( $address_type_array ) == 1 )
            {
                $this->fill( $address_type_array[0] );
            }
            else
            {
                $this->ID = "";
            }
        }
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$address_type_array )
    {
        $this->ID = $address_type_array[ "ID" ];
        $this->Name = $address_type_array[ "Name" ];
        $this->ListOrder = $address_type_array[ "ListOrder" ];
    }

    /*
    Henter ut alle adresstypene lagret i databasen.
  */
    function &getAll( $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $online_type_array = 0;

        $address_type_array = array();
        $return_array = array();

        if ( $as_object )
            $select = "*";
        else
            $select = "ID";
    
        $db->array_query( $address_type_array, "SELECT $select FROM eZAddress_AddressType
                                                WHERE Removed=0
                                                ORDER BY ListOrder" );

        if ( $as_object )
        {
            foreach( $address_type_array as $addressTypeItem )
            {
                $return_array[] = new eZAddressType( $addressTypeItem );
            }
        }
        else
        {
            foreach( $address_type_array as $addressTypeItem )
            {
                $return_array[] = $addressTypeItem["ID"];
            }
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

    function &count()
    {
        $db =& eZDB::globalDatabase();
//          $db->array_query( $person_qry,  "SELECT count( Pe.ID ) as Count
//                                           FROM eZContact_Person AS Pe, eZContact_PersonAddressDict AS PAD,
//                                                eZAddress_Address AS Ad, eZAddress_AddressType AS AT
//                                           WHERE Pe.ID = PAD.PersonID AND PAD.AddressID = Ad.ID AND Ad.AddressTypeID = AT.ID AND AddressTypeID='$this->ID'" );
//          $db->array_query( $company_qry, "SELECT count( Co.ID ) as Count
//                                           FROM eZContact_Company AS Co, eZContact_CompanyAddressDict AS CAD,
//                                                eZAddress_Address AS Ad, eZAddress_AddressType AS AT
//                                           WHERE Co.ID = CAD.CompanyID AND CAD.AddressID = Ad.ID AND Ad.AddressTypeID = AT.ID AND AddressTypeID='$this->ID'" );
        $db->array_query( $qry, "SELECT count( Ad.ID ) as Count
                                 FROM  eZAddress_Address AS Ad, eZAddress_AddressType AS AT
                                 WHERE Ad.AddressTypeID = AT.ID AND AddressTypeID='$this->ID'" );
        $cnt = 0;
//          if ( count( $company_qry ) > 0 )
//              $cnt += $company_qry[0]["Count"];
//          if ( count( $person_qry ) > 0 )
//              $cnt += $person_qry[0]["Count"];
        if ( count( $qry ) > 0 )
            $cnt += $qry[0]["Count"];
        return $cnt;
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */

    function moveUp()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZAddress_AddressType
                                  WHERE Removed=0 AND ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZAddress_AddressType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZAddress_AddressType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */

    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZAddress_AddressType
                                  WHERE Removed=0 AND ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZAddress_AddressType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZAddress_AddressType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    var $ID;
    var $Name;
    var $ListOrder;
}

?>
 