<?

//!! eZAddress
//!
/*!

*/

//  include_once( "ezaddress/classes/ezperson.php" );
//  include_once( "ezaddress/classes/ezcompany.php" );

class eZPhoneType
{
    /*
      Constructor.
    */
    function eZPhoneType( $id="-1", $fetch=true )
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


    /*
      Henter ut en adressetype med ID == $id
    */  
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $phone_type_array, "SELECT * FROM eZAddress_PhoneType WHERE ID='$id'",
                              0, 1 );
            if ( count( $phone_type_array ) == 1 )
            {
                $this->fill( $phone_type_array[0] );
            }
            else
            {
                $this->ID = "";
            }
        }
    }

    /*!
      Extracts the information from the array and puts it in the object.
    */
    function fill( &$phone_type_array )
    {
        $this->ID = $phone_type_array[ "ID" ];
        $this->Name = $phone_type_array[ "Name" ];
        $this->ListOrder = $phone_type_array[ "ListOrder" ];
    }

    /*
      \static
      Henter antall telefontyper som er lagret i databasen.
    */
    function getAllCount()
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $phone_type_array,
                          "SELECT Count( ID ) AS Count FROM eZAddress_PhoneType" );

        return $phone_type_array["Count"];
    }

    /*
      \static
      Henter ut alle telefontypene lagret i databasen.
    */
    function getAll( $as_object = true, $offset = 0, $max = -1 )
    {
        $db =& eZDB::globalDatabase();

        $phone_type_edit = array();
        $return_array = array();

        if ( $max >= 0 && is_numeric( $offset ) && is_numeric( $max ) )
        {
            $limit = "LIMIT $offset, $max";
        }

        if ( $as_object )
            $select = "*";
        else
            $select = "ID";

        $db->array_query( $phone_type_array,
                          "SELECT $select FROM eZAddress_PhoneType
                                          WHERE Removed=0
                                          ORDER BY ListOrder
                          $limit" );

        if ( $as_object )
        {
            foreach( $phone_type_array as $phoneTypeItem )
            {
                $return_array[] = new eZPhoneType( $phoneTypeItem );
            }
        }
        else
        {
            foreach( $phone_type_array as $phoneTypeItem )
            {
                $return_array[] = $phoneTypeItem["ID"];
            }
        }
        return $return_array;
    }


    /*!
      Lagrer en telefontyperow til databasen.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name = addslashes( $this->Name );
        $ret = false;
        if ( !isSet( $this->ID ) )
        {
            $db->query_single( $qry, "SELECT ListOrder from eZAddress_PhoneType ORDER BY ListOrder DESC LIMIT 1" );
            $listorder = $qry["ListOrder"] + 1;
            $this->ListOrder = $listorder;

            $db->query( "INSERT INTO eZAddress_PhoneType set Name='$name', ListOrder='$this->ListOrder'" );
            
            $this->ID = mysql_insert_id();

            $ret = true;
        }
        else
        {
            $db->query( "UPDATE eZAddress_PhoneType set Name='$name', ListOrder='$this->ListOrder' WHERE ID='$this->ID'" );
            
            $ret = true;
        }
        return $ret;
    }

    /*
      Deletes the addressetype for the database,
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
//                                              FROM eZContact_Person AS Pe, eZContact_PersonPhoneDict AS PPD,
//                                                   eZAddress_Phone AS Ph
//                                              WHERE Pe.ID = PPD.PersonID AND PPD.PhoneID = Ph.ID AND PhoneTypeID='$id'" );
//              foreach( $person_qry as $person )
//                  {
//                      eZPerson::delete( $person["ID"] );
//                  }
//              $db->array_query( $company_qry, "SELECT Co.ID
//                                               FROM eZContact_Company AS Co, eZContact_CompanyPhoneDict AS CPD,
//                                                    eZAddress_Phone AS Ph
//                                               WHERE Co.ID = CPD.CompanyID AND CPD.PhoneID = Ph.ID AND PhoneTypeID='$id'" );
//              foreach( $company_qry as $company )
//                  {
//                      eZCompany::delete( $company["ID"] );
//                  }
//          }
//          else if ( $relations )
//          {
//              $db->array_query( $person_qry, "SELECT A.PersonID, A.PhoneID
//                                              FROM eZContact_PersonPhoneDict AS A, eZAddress_Phone AS B
//                                              WHERE A.PhoneID = B.ID AND B.PhoneTypeID='$id'" );
//              foreach( $person_qry as $person )
//                  {
//                      $person_id = $person["PersonID"];
//                      $phone_id = $person["PhoneID"];
//                      $db->query( "DELETE FROM eZContact_PersonPhoneDict WHERE PersonID='$person_id' AND PhoneID='$phone_id'" );
//                      $db->query( "DELETE FROM eZAddress_Phone WHERE ID='$phone_id'" );
//                  }
//              $db->array_query( $company_qry, "SELECT A.CompanyID, A.PhoneID
//                                               FROM eZContact_CompanyPhoneDict AS A, eZAddress_Phone AS B
//                                               WHERE A.PhoneID = B.ID AND B.PhoneTypeID='$id'" );
//              foreach( $company_qry as $company )
//                  {
//                      $company_id = $company["CompanyID"];
//                      $phone_id = $company["PhoneID"];
//                      $db->query( "DELETE FROM eZContact_CompanyPhoneDict WHERE CompanyID='$company_id' AND PhoneID='$phone_id'" );
//                      $db->query( "DELETE FROM eZAddress_Phone WHERE ID='$phone_id'" );
//                  }
//          }
//          $db->query( "DELETE FROM eZAddress_PhoneType WHERE ID='$id'" );
        $db->query( "UPDATE eZAddress_PhoneType SET Removed=1 WHERE ID='$id'" );
    }

    function setName( $value )
    {
        $this->Name = $value;
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
      Returns the number of external items using this item.
    */

    function &count()
    {
        $db =& eZDB::globalDatabase();
//          $db->array_query( $person_qry,  "SELECT count( Pe.ID ) as Count
//                                           FROM eZContact_Person AS Pe, eZContact_PersonPhoneDict AS PPD,
//                                                eZAddress_Phone AS Ph, eZAddress_PhoneType AS PT
//                                           WHERE Pe.ID = PPD.PersonID AND PPD.PhoneID = Ph.ID AND Ph.PhoneTypeID = PT.ID AND PhoneTypeID='$this->ID'" );
//          $db->array_query( $company_qry, "SELECT count( Co.ID ) as Count
//                                           FROM eZContact_Company AS Co, eZContact_CompanyPhoneDict AS CPD,
//                                                eZAddress_Phone AS Ph, eZAddress_PhoneType AS PT
//                                           WHERE Co.ID = CPD.CompanyID AND CPD.PhoneID = Ph.ID AND Ph.PhoneTypeID = PT.ID AND PhoneTypeID='$this->ID'" );
        $db->array_query( $qry,  "SELECT count( Ph.ID ) as Count
                                         FROM eZAddress_Phone AS Ph, eZAddress_PhoneType AS PT
                                         WHERE Ph.PhoneTypeID = PT.ID AND PhoneTypeID='$this->ID'" );
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
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZAddress_PhoneType
                                  WHERE Removed=0 AND ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZAddress_PhoneType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZAddress_PhoneType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */

    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZAddress_PhoneType
                                  WHERE Removed=0 AND ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZAddress_PhoneType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZAddress_PhoneType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    var $ID;
    var $Name;
}

?>
