<?

//!! eZContact
//!
/*!

*/

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompany.php" );

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
                die( "Feil: Flere phonetype med samme ID funnet i database, dette skal ikke være mulig. " );
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
        $db = eZDB::globalDatabase();

        $ret = false;
        if ( !isSet( $this->ID ) )
        {
            $db->query_single( $qry, "SELECT ListOrder from eZContact_PhoneType ORDER BY ListOrder DESC LIMIT 1" );
            $listorder = $qry["ListOrder"] + 1;
            $this->ListOrder = $listorder;

            $db->query( "INSERT INTO eZContact_PhoneType set Name='$this->Name', ListOrder='$this->ListOrder'" );
            
            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $db->query( "UPDATE eZContact_PhoneType set Name='$this->Name', ListOrder='$this->ListOrder' WHERE ID='$this->ID'" );
            
            $this->State_ = "Coherent";
            $ret = true;
        }
        return $ret;
    }

    /*
      Deletes the addressetype for the database,
      if $relations is true all relations to this item is deleted too,
      if $relations is "full" all persons and companies are deleted too.
    */
    function delete( $relations = false )
    {
        $db = eZDB::globalDatabase();
        if ( $relations == "full" )
        {
            $db->array_query( $person_qry, "SELECT Pe.ID
                                            FROM eZContact_Person AS Pe, eZContact_PersonPhoneDict AS PPD,
                                                 eZContact_Phone AS Ph
                                            WHERE Pe.ID = PPD.PersonID AND PPD.PhoneID = Ph.ID AND PhoneTypeID='$this->ID'" );
            foreach( $person_qry as $person )
                {
                    eZPerson::delete( $person["ID"] );
                }
            $db->array_query( $company_qry, "SELECT Co.ID
                                             FROM eZContact_Company AS Co, eZContact_CompanyPhoneDict AS CPD,
                                                  eZContact_Phone AS Ph
                                             WHERE Co.ID = CPD.CompanyID AND CPD.PhoneID = Ph.ID AND PhoneTypeID='$this->ID'" );
            foreach( $company_qry as $company )
                {
                    eZCompany::delete( $company["ID"] );
                }
        }
        else if ( $relations )
        {
            $db->array_query( $person_qry, "SELECT A.PersonID, A.PhoneID
                                            FROM eZContact_PersonPhoneDict AS A, eZContact_Phone AS B
                                            WHERE A.PhoneID = B.ID AND B.PhoneTypeID='$this->ID'" );
            foreach( $person_qry as $person )
                {
                    $person_id = $person["PersonID"];
                    $phone_id = $person["PhoneID"];
                    $db->query( "DELETE FROM eZContact_PersonPhoneDict WHERE PersonID='$person_id' AND PhoneID='$phone_id'" );
                    $db->query( "DELETE FROM eZContact_Phone WHERE ID='$phone_id'" );
                }
            $db->array_query( $company_qry, "SELECT A.CompanyID, A.PhoneID
                                             FROM eZContact_CompanyPhoneDict AS A, eZContact_Phone AS B
                                             WHERE A.PhoneID = B.ID AND B.PhoneTypeID='$this->ID'" );
            foreach( $company_qry as $company )
                {
                    $company_id = $company["CompanyID"];
                    $phone_id = $company["PhoneID"];
                    $db->query( "DELETE FROM eZContact_CompanyPhoneDict WHERE CompanyID='$company_id' AND PhoneID='$phone_id'" );
                    $db->query( "DELETE FROM eZContact_Phone WHERE ID='$phone_id'" );
                }
        }
        $db->query( "DELETE FROM eZContact_PhoneType WHERE ID='$this->ID'" );
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
      Returns the number of external items using this item.
    */

    function count()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->array_query( $person_qry,  "SELECT count( Pe.ID ) as Count
                                         FROM eZContact_Person AS Pe, eZContact_PersonPhoneDict AS PPD,
                                              eZContact_Phone AS Ph, eZContact_PhoneType AS PT
                                         WHERE Pe.ID = PPD.PersonID AND PPD.PhoneID = Ph.ID AND Ph.PhoneTypeID = PT.ID AND PhoneTypeID='$this->ID'" );
        $db->array_query( $company_qry, "SELECT count( Co.ID ) as Count
                                         FROM eZContact_Company AS Co, eZContact_CompanyPhoneDict AS CPD,
                                              eZContact_Phone AS Ph, eZContact_PhoneType AS PT
                                         WHERE Co.ID = CPD.CompanyID AND CPD.PhoneID = Ph.ID AND Ph.PhoneTypeID = PT.ID AND PhoneTypeID='$this->ID'" );
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
