<?
// 
// $Id: ezaddress.php,v 1.15 2000/10/10 14:07:02 bf-cvs Exp $
//
// Definition of eZAddress class
//
// Bård Farstad <bf@ez.no>
// Created on: <07-Oct-2000 12:34:13 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZContact
//! eZAddress handles addresses.
/*!
  
*/

include_once( "classes/ezdb.php" );

class eZAddress
{
    /*!
      Constructs a new eZAddress object.
    */
    function eZAddress( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        if ( $id != "" )
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
      Lagrer en ny adresserad i databasen. 
    */  
    function store()
    {
        $this->dbInit();

        $ret = false;
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZContact_Address
                    SET Street1='$this->Street1',
                    Street2='$this->Street2',
                    Zip='$this->Zip',
                    Place='$this->Place',
                    AddressType='$this->AddressType'" );            

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $this->Database->query( "UPDATE eZContact_Address
                    SET Street1='$this->Street1',
                    Street2='$this->Street2',
                    Zip='$this->Zip',
                    Place='$this->Place',
                    AddressType='$this->AddressType'
                    WHERE ID='$this->ID'" );            

            $this->State_ = "Coherent";
            $ret = true;            
        }        

        
        return $ret;
    }

    /*!
      NOTE: this function is obsolete.
      
      Oppdaterer informasjonen som ligger i databasen.
    */  
    function update()
    {
        print( "Warning: eZAddress::update, this function is no longer valid. Please reimplement to use the eZAddress::store() function." );
//          $this->dbInit();
//          query( "UPDATE eZContact_Address set Street1='$this->Street1', Street2='$this->Street2', Zip='$this->Zip', AddressType='$this->AddressType' WHERE ID='$this->ID'" );
    }
  
    /*!
      Fetches an address with object id==$id;
    */  
    function get( $id="" )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $address_array, "SELECT * FROM eZContact_Address WHERE ID='$id'" );
            if ( count( $address_array ) > 1 )
            {
                die( "Feil: Flere addresser med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $address_array ) == 1 )
            {
                $this->ID =& $address_array[ 0 ][ "ID" ];
                $this->Street1 =& $address_array[ 0 ][ "Street1" ];
                $this->Street2 =& $address_array[ 0 ][ "Street2" ];
                $this->Zip =& $address_array[ 0 ][ "Zip" ];
                $this->Place =& $address_array[ 0 ][ "Place" ];
                
                $this->AddressType =& $address_array[ 0 ][ "AddressType" ];
            }
        }
    }

    /*!
      Henter ut alle adressene lagret i databasen.
    */
    function getAll( )
    {
        $this->dbInit();    
        $address_array = 0;
    
        $this->Database->array_query( $address_array, "SELECT * FROM eZContact_Address" );
    
        return $address_array;
    }

    /*!
      Sletter adressen med ID == $id;
     */
    function delete()
    {
        $this->dbInit();
        
        $this->Database->query( "DELETE FROM eZContact_Address WHERE ID='$this->ID'" );
    }    
    

    /*!
      Setter  street1.
    */
    function setStreet1( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Street1 = $value;
    }

    /*!
      Setter  street2.
    */
    function setStreet2( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Street2 = $value;
    }

    /*!
      Setter postkode.
    */
    function setZip( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Zip = $value;
    }

    /*!
      Setter adressetype.
    */
    function setAddressType( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->AddressType = $value;
    }

    /*!
      Returns the object ID.
    */
    function id( )
    {
        return $this->ID;
    }
    
    /*!
      Returnerer  street1.
    */
    function street1( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Street1;
    }

    /*!
      Returnerer  street2.
    */
    function street2( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        
        return $this->Street2;
    }

    /*!
      Returnerer postkode.
    */
    function zip( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        
        return $this->Zip;
    }

    /*!
      Returnerer adressetype.
    */
    function addressType(  )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        
        return $this->AddressType;
    }

    /*!
      Sets the place value.
    */
    function setPlace( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Place = $value;
    }

    /*!
     Returns the place.
    */
    function place()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Place;
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
    var $Street1;
    var $Street2;
    var $Zip;
    var $Place;

    /// Relation to an eZAddressType
    var $AddressType;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
