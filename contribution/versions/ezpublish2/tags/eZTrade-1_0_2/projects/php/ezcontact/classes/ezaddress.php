<?
// 
// $Id: ezaddress.php,v 1.19 2000/11/01 17:59:18 ce-cvs Exp $
//
// Definition of eZAddress class
//
// Bård Farstad <bf@ez.no>
// Created on: <07-Oct-2000 12:34:13 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//
//!! eZContact
//! eZAddress handles addresses.
/*!
  NOTE: this class defaults to Norwegian country is none is
  set.
*/


include_once( "classes/ezdb.php" );
include_once( "ezcontact/classes/ezcountry.php" );

class eZAddress
{
    /*!
      Constructs a new eZAddress object.
    */
    function eZAddress( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        // default to Norwegian country.
        $this->CountryID = 162;

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
      Stores a eZAddress
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
                    CountryID='$this->CountryID',
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
                    AddressType='$this->AddressType',
                    CountryID='$this->CountryID'
                    WHERE ID='$this->ID'" );            

            $this->State_ = "Coherent";
            $ret = true;            
        }        

        
        return $ret;
    }

    /*!
      Fetches an address with object id==$id;
    */  
    function get( $id="" )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            $this->Database->array_query( $address_array, "SELECT * FROM eZContact_Address WHERE ID='$id'" );
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
                $this->CountryID =& $address_array[ 0 ][ "CountryID" ];
                
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
      Sets the country, takes an eZCountry object as argument.
    */
    function setCountry( $country )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $country ) == "ezcountry" )
       {
           $this->CountryID = $country->id();
       }
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
      Returns the country as an eZCountry object.
    */
    function country()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return new eZCountry( $this->CountryID );
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
    var $CountryID;
    

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
