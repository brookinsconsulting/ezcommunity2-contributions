<?
// 
// $Id: ezcompany.php,v 1.37 2000/12/06 12:48:35 ce-cvs Exp $
//
// Definition of eZProduct class
//
// <real-name><<email-name>>
// Created on: <09-Nov-2000 14:52:40 ce>
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

//!! eZCompany
//! eZCompany handles company information.
/*!

  Example code:
  \code
  $company = new eZCompany();
  $company->setName( "Company name" );
  $company->store();

  \endcode

  \sa eZPerson eZAddress
*/

//require "ezphputils.php";

include_once( "ezcontact/classes/ezaddress.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );
include_once( "ezcontact/classes/ezphone.php" );
include_once( "classes/ezimagefile.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

// include_once( "ezcontact/classes/ezonline.php" );

class eZCompany
{
    /*!
      Constructs a new eZCompany object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZCompany( $id="-1", $fetch=true )
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
      Stores a company to the database
    */
    function store( )
    {
        $this->dbInit();

        if ( !isSet( $this->ID ) )
        {
        
            $this->Database->query( "INSERT INTO eZContact_Company set Name='$this->Name',
	                                              Comment='$this->Comment',
                                                  CompanyNo='$this->CompanyNo',
	                                              CreatorID='$this->CreatorID'" );
            $this->ID = mysql_insert_id();
            
            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZContact_Company set Name='$this->Name',
                                            	 Comment='$this->Comment',
                                                 CompanyNo='$this->CompanyNo',
                                               	 CreatorID='$this->CreatorID' WHERE ID='$this->ID'" );
            $this->State_ = "Coherent";
        }

        return true;
    }

    /*
      Deletes a eZCompany object  from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isSet( $this->ID ) )
        {
            $this->Database->array_query( $address_array, "SELECT eZContact_Address.ID AS 'AID', eZContact_CompanyAddressDict.CompanyID AS 'DID'
                                               FROM eZContact_Address, eZContact_CompanyAddressDict
                                               WHERE eZContact_Address.ID=eZContact_CompanyAddressDict.AddressID AND eZContact_CompanyAddressDict.CompanyID='$this->ID' " );

            foreach( $address_array as $addressItem )
            {
                $addressID = $addressItem["AID"];
                $addressDictID = $addressItem["DID"];

                $this->Database->query( "DELETE FROM eZContact_Address WHERE ID='$addressID'" );
                $this->Database->query( "DELETE FROM eZContact_CompanyAddressDict WHERE CompanyID='$this->ID'" );
            }

            $this->Database->array_query( $phone_array, "SELECT eZContact_Phone.ID AS 'PID', eZContact_CompanyPhoneDict.CompanyID AS 'DID'
                                     FROM eZContact_Phone, eZContact_CompanyPhoneDict
                                     WHERE eZContact_Phone.ID=eZContact_CompanyPhoneDict.PhoneID AND eZContact_CompanyPhoneDict.CompanyID='$this->ID' " );

            foreach( $phone_array as $phoneItem )
                {
                    $phoneID = $phoneItem["PID"];
                    $phoneDictID = $phoneItem["DID"];
                    $this->Database->query( "DELETE FROM eZContact_Phone WHERE ID='$phoneID'" );
                    $this->Database->query( "DELETE FROM eZContact_CompanyPhoneDict WHERE CompanyID='$this->ID'" );
                }

            $this->Database->array_query( $online_array, "SELECT eZContact_Online.ID AS 'OID', eZContact_CompanyOnlineDict.CompanyID AS 'DID'
                                     FROM eZContact_Online, eZContact_CompanyOnlineDict
                                     WHERE eZContact_Online.ID=eZContact_CompanyOnlineDict.OnlineID AND eZContact_CompanyOnlineDict.CompanyID='$this->ID' " );

            foreach( $online_array as $onlineItem )
                {
                    $onlineID = $onlineItem["OID"];
                    $onlineDictID = $onlineItem["DID"];
                    $this->Database->query( "DELETE FROM eZContact_Online WHERE ID='$onlineID'" );
                    $this->Database->query( "DELETE FROM eZContact_CompanyOnlineDict WHERE CompanyID='$this->ID'" );
                }

            $this->Database->query( "DELETE FROM eZContact_CompanyTypeDict WHERE CompanyID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZContact_Company WHERE ID='$this->ID'" );
        }
        return true;
    }

  
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        $ret = false;

        if ( $id != "" )
        {
            $this->Database->array_query( $company_array, "SELECT * FROM eZContact_Company WHERE ID='$id'" );
            if ( count( $company_array ) > 1 )
            {
                die( "Error: More than one company with the same id was found. " );
            }
            else if ( count( $company_array ) == 1 )
            {
                $this->ID = $company_array[0]["ID"];
                $this->Name = $company_array[0]["Name"];
                $this->Comment = $company_array[0]["Comment"];
                $this->CreatorID = $company_array[0]["CreatorID" ];        
                $this->CompanyNo = $company_array[0]["CompanyNo"];
                     
                $ret = true;
            }
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }
    

    /*
      Returns all the company found in the database.
      
      The company are returned as an array of eZCompany objects.
    */
    function getAll( )
    {
        $this->dbInit();
        
        $company_array = array();
        $return_array = array();
    
        $this->Database->array_query( $company_array, "SELECT ID FROM eZContact_Company ORDER BY Name" );

        foreach( $company_array as $companyItem )
            {
                $return_array[] = new eZCompany( $companyItem["ID"] );
            }
        return $return_array;
    }

    /*
      Returns all the company found in the database.
      
      The company are returned as an array of eZCompany objects.
    */
    function getByCategory( $categoryID )
    {
        $this->dbInit();
        
        $company_array = array();
        $return_array = array();

        $this->Database->array_query( $company_array, "SELECT CompanyID FROM eZContact_CompanyTypeDict WHERE CompanyTypeID='$categoryID'" );

            foreach( $company_array as $companyItem )
            {
                $return_array[] = new eZCompany( $companyItem["CompanyID"] );
            }
       
        return $return_array;
    }
    
    /*
      Henter ut alle firma i databasen som inneholder søkestrengen.
    */
    function searchByCategory( $categoryID, $query )
    {
        $this->dbInit();
        
        $company_array = array();
        $return_array = array();
        if( !empty( $query ) )
        {
            $this->Database->array_query( $company_array, "
                SELECT 
                    Comp.ID 
                FROM
                    eZContact_CompanyTypeDict as Dict,
                    eZContact_Company as Comp
                WHERE
                    Comp.Name LIKE '%$query%'
                AND
                    Dict.CompanyTypeID = '$categoryID'
                AND
                    Comp.ID = Dict.CompanyID
                ORDER BY Name" );

            foreach( $company_array as $companyItem )
            {
                $return_array[] = new eZCompany( $companyItem["ID"] );
            }
        }
        
        return $return_array;
    }


    /*
      Returns all the company found in the database.
      
      The company are returned as an array of eZCompany objects.
    */
    function getByUser( $user )
    {
        $this->dbInit();

        if ( get_class( $user ) == "ezuser" )
        {
            $userID = $user->id();
            
            $company_array = array();
            $return_array = array();

            $this->Database->array_query( $company_array, "SELECT CompanyID FROM eZContact_UserCompanyDict WHERE UserID='$userID'" );
            
            foreach( $company_array as $companyItem )
            {
                $return_array[] = new eZCompany( $companyItem["CompanyID"] );
            }
            return $return_array;
        }
    }

    
    /*
      Henter ut alle firma i databasen som inneholder søkestrengen.
    */
    function search( $query )
    {
        $this->dbInit();    
        $company_array = array();
        $return_array = array();
    
        $this->Database->array_query( $company_array, "SELECT ID FROM eZContact_Company WHERE Name LIKE '%$query%' ORDER BY Name" );

        foreach( $company_array as $companyItem )
        {
            $return_array[] = new eZCompany( $companyItem["ID"] );
        }
        return $return_array;
    }

    /*
      Henter ut alle firma i databasen hvor en eller flere tilhørende personer    
      inneholder søkestrengen.
    */
    function searchByPerson( $query )
    {
        $this->dbInit();    
        $company_array = array();
        $return_array = array();
    
        $this->Database->array_query( $company_array, "SELECT eZContact_Company.ID as ID
                                      FROM eZContact_Company, eZContact_Person
                                      WHERE ((eZContact_Person.FirstName LIKE '%$query%' OR eZContact_Person.LastName LIKE '%$query%')
                                      AND eZContact_Company.ID=eZContact_Person.Company) GROUP BY eZContact_Company.ID ORDER BY eZContact_Company.ID" );

        foreach( $company_array as $companyItem )
            {
                $return_array[] = new eZCompany( $companyItem["ID"] );
            }
        return $return_array;
    }

    /*!
      Removes the company from every user category.
    */
    function removeCategoryies()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $this->Database->query( "DELETE FROM eZContact_CompanyTypeDict
                                WHERE CompanyID='$this->ID'" );
    }

    /*!
      Returns the categories that belong to this eZCompany object.
    */
    function categories( $companyID )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $this->dbInit();

        $this->Database->array_query( $categories_array, "SELECT CompanyTypeID
                                                 FROM eZContact_CompanyTypeDict
                                                 WHERE CompanyID='$companyID'" );

        foreach( $categories_array as $categoriesItem )
            {
                $return_array[] = new eZCompanyType( $categoriesItem["CompanyTypeID"] );
            }

        return $return_array;
    }
   

    /*!
      Returns the address that belong to this eZCompany object.
    */
    function addresses( $companyID )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $this->dbInit();

        $this->Database->array_query( $address_array, "SELECT AddressID
                                                 FROM eZContact_CompanyAddressDict
                                                 WHERE CompanyID='$companyID'" );

        foreach( $address_array as $addressItem )
            {
                $return_array[] = new eZAddress( $addressItem["AddressID"] );
            }

        return $return_array;
    }

    /*!
      Adds an address to the current Company.
    */
    function addAddress( $address )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();
        if ( get_class( $address ) == "ezaddress" )
        {
            $addressID = $address->id();

            $this->Database->query( "INSERT INTO eZContact_CompanyAddressDict
                                SET CompanyID='$this->ID', AddressID='$addressID'" );

            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns the phones that belong to this eZCompany object.
    */
    function phones( $companyID )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $this->dbInit();

        $this->Database->array_query( $phone_array, "SELECT PhoneID
                                                 FROM eZContact_CompanyPhoneDict
                                                 WHERE CompanyID='$companyID'" );

        foreach( $phone_array as $phoneItem )
            {
                $return_array[] = new eZPhone( $phoneItem["PhoneID"] );
            }

        return $return_array;
    }

    /*!
      Adds an phone to the current Company.
    */
    function addPhone( $phone )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();
        if ( get_class( $phone ) == "ezphone" )
        {
            $phoneID = $phone->id();

            $this->Database->query( "INSERT INTO eZContact_CompanyPhoneDict
                                SET CompanyID='$this->ID', PhoneID='$phoneID'" );

            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns the onlines that belong to this eZCompany object.
    */
    function onlines( $onlineID )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $this->dbInit();

        $this->Database->array_query( $online_array, "SELECT OnlineID
                                                 FROM eZContact_CompanyOnlineDict
                                                 WHERE CompanyID='$this->ID'" );

        foreach( $online_array as $onlineItem )
            {
                $return_array[] = new eZOnline( $onlineItem["OnlineID"] );
            }

        return $return_array;
    }

    /*!
      Adds an online to the current Company.
    */
    function addOnline( $online )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();

        if ( get_class( $online ) == "ezonline" )
        {
            $onlineID = $online->id();

            $this->Database->query( "INSERT INTO eZContact_CompanyOnlineDict
                                SET CompanyID='$this->ID', OnlineID='$onlineID'" );

            $ret = true;
        }
        return $ret;
    }

    /*!
      Adds a image to the current 
     */
    function addImage( $image )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();

        if ( get_class ( $image ) == "ezimage" )
        {
            $imageID = $image->id();

            $this->Database->query( "INSERT INTO eZContact_CompanyImageDict
                                     SET CompanyID='$this->ID', ImageID='$imageID'" );
        }

    }

    /*!
      Returns every image to a product as a array of eZImage objects.
    */
    function images()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $return_array = array();
       $image_array = array();
       
       $this->Database->array_query( $image_array, "SELECT ImageID FROM eZContact_CompanyImageDict WHERE CompanyID='$this->ID'" );
       
       for ( $i=0; $i<count($image_array); $i++ )
       {
           $return_array[$i] = new eZImage( $image_array[$i]["ImageID"], false );
       }
       
       return $return_array;
    }


    /*!
      Returns the logo image of the company as a eZImage object.
    */
    function logoImage( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       $this->dbInit();
       
       $this->Database->array_query( $res_array, "SELECT * FROM eZContact_CompanyImageDefinition
                                     WHERE
                                     CompanyID='$this->ID'
                                   " );

       if ( count( $res_array ) == 1 )
       {
           if ( $res_array[0]["LogoImageID"] != "NULL" )
           {
               $ret = new eZImage( $res_array[0]["LogoImageID"], false );
           }               
       }
       
       return $ret;
    }

    /*!
      Sets the logo image for the company.

      The argument must be a eZImage object.
    */
    function setLogoImage( $image )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $image ) == "ezimage" )
        {
            $this->dbInit();

            $imageID = $image->id();

            $this->Database->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZContact_CompanyImageDefinition
                                     WHERE
                                     CompanyID='$this->ID'" );

            if ( $res_array[0]["Number"] == "1" )
            {            
                $this->Database->query( "UPDATE eZContact_CompanyImageDefinition
                                     SET
                                     LogoImageID='$imageID'
                                     WHERE
                                     CompanyID='$this->ID'" );
            }
            else
            {
                $this->Database->query( "INSERT INTO eZContact_CompanyImageDefinition
                                     SET
                                     CompanyID='$this->ID',
                                     LogoImageID='$imageID'" );
            }
        }
    }

    function deleteImage( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
       $this->Database->query( "UPDATE eZContact_CompanyImageDefinition SET CompanyImageID='0' WHERE CompanyID='$this->ID'" );
    }

    function deleteLogo( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
       $this->dbInit();
       
       $this->Database->query( "UPDATE eZContact_CompanyImageDefinition SET LogoImageID='0' WHERE CompanyID='$this->ID'" );
    }

    /*!
      Adds a user to the current Person.
    */
    function addUser( $user )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
        
        $this->dbInit();

        if( get_class( $user ) == "ezuser" )
        {
            $userID = $user->id();
            
            $checkQuery = "SELECT PersonID FROM eZContact_UserPersonDict WHERE UserID=$userID";
            $this->Database->array_query( $user_array, $checkQuery );
            
            $count = count( $user_array );
            
            if( $count == 0 )
            {
                $this->Database->query( "INSERT INTO eZContact_UserPersonDict
                                SET PersonID='$this->ID', UserID='$userID'" );
            }
            $ret = true;
        }
        return $ret;
    }



    /*!
      Sets the company image for the company.

      The argument must be a eZImage object.
    */
    function setCompanyImage( $image )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $image ) == "ezimage" )
        {
            $this->dbInit();

            $imageID = $image->id();

            $this->Database->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZContact_CompanyImageDefinition
                                     WHERE
                                     CompanyID='$this->ID'" );

            if ( $res_array[0]["Number"] == "1" )
            {            
                $this->Database->query( "UPDATE eZContact_CompanyImageDefinition
                                     SET
                                     CompanyImageID='$imageID'
                                     WHERE
                                     CompanyID='$this->ID'" );
            }
            else
            {
                $this->Database->query( "INSERT INTO eZContact_CompanyImageDefinition
                                     SET
                                     CompanyID='$this->ID',
                                     CompanyImageID='$imageID'" );
            }
        }
    }


    /*!
      Returns the logo image of the company as a eZImage object.
    */
    function companyImage( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       $this->dbInit();
       
       $this->Database->array_query( $res_array, "SELECT * FROM eZContact_CompanyImageDefinition
                                     WHERE
                                     CompanyID='$this->ID'
                                   " );
       
       if ( count( $res_array ) == 1 )
       {
           if ( $res_array[0]["CompanyImageID"] != "NULL" )
           {
               $ret = new eZImage( $res_array[0]["CompanyImageID"], false );
           }               
       }
       
       return $ret;
    }
    
    
    /*!
      Sets the name of the company.
    */
    function setName( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Name = $value;
    }

    /*!
      Sets the comment of the company.
    */
    function setComment( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Comment = $value;
    }

    /*!
      Sets the creatorID of the company.
    */
    function setCreatorID( $user )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( get_class( $user ) == "ezuser" )
        {
            $userID = $user->id();

            $this->CreatorID = $userID;
        }
    }
    /*!
      Sets the contact type of the company.
    */
    function setCompanyNo( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->CompanyNo = $value;
    }


    /*!
      Returnerer ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returnerer firmanavn.
    */
    function name()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Name;
    }

    /*!
      Returnerer ID til eier av firma ( brukeren som opprettet det ).
    */
    function creatorID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->CreatorID;
    }
    
    /*!
      Returnerer kommentar.
    */
    function comment()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Comment;
    }  
    
    /*!
      Returns Company no.
    */
    function companyNo()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->CompanyNo;
    }  

    /*!
      \private
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit( )
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }


    var $ID;
    var $CreatorID;
    var $Name;
    var $Comment;
    var $Online;
    var $CompanyNo;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

}

?>
