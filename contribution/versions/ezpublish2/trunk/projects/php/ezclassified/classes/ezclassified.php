<?
// 
// $Id: ezclassified.php,v 1.3 2000/11/29 18:43:31 ce-cvs Exp $
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

//!! eZClassified
//! eZClassified handles company information.
/*!

  Example code:
  \code
  $company = new eZClassified();
  $company->setName( "Company name" );
  $company->store();

  \endcode

  \sa eZPerson eZAddress
*/

//require "ezphputils.php";

include_once( "ezcontact/classes/ezcompany.php" );
// include_once( "ezcontact/classes/ezonline.php" );

class eZClassified
{
    /*!
      Constructs a new eZClassified object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZClassified( $id="-1", $fetch=true )
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
        
            $this->Database->query( "INSERT INTO eZClassified_Classified SET
                                                  Name='$this->Name',
	                                              Description='$this->Description',
	                                              UserID='$this->UserID',
	                                              Price='$this->Price',
	                                              Created=now()
                                                  ");
            $this->ID = mysql_insert_id();
            
            $this->State_ = "Coherent";
            
            $this->Status_ = "Insert";
        }
        else
        {
            $this->Database->query( "UPDATE eZClassified_Classified SET
                                                  Name='$this->Name',
	                                              Description='$this->Description',
	                                              UserID='$this->UserID',
	                                              Price='$this->Price',
	                                              Created=Created
                                               	  WHERE ID='$this->ID'
                                               	  " );
            $this->State_ = "Coherent";

            $this->Status_ = "Update";
        }

        return true;
    }

    /*
      Deletes a eZClassified object  from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isSet( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZClassified_Classified WHERE ID='$this->ID'" );
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
            $this->Database->array_query( $classified_array, "SELECT * FROM eZClassified_Classified WHERE ID='$id'" );
            if ( count( $classified_array ) > 1 )
            {
                die( "Error: More than one company with the same id was found. " );
            }
            else if ( count( $classified_array ) == 1 )
            {
                $this->ID = $classified_array[0]["ID"];
                $this->Name = $classified_array[0]["Name"];
                $this->Description = $classified_array[0]["Description"];
                $this->UserID = $classified_array[0]["UserID"];
                $this->Price = $classified_array[0]["Price"];
                $this->Dato = $classified_array[0]["Dato"];
                $this->Created = $classified_array[0]["Created"];
                     
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
      
      The company are returned as an array of eZClassified objects.
    */
    function getAll( )
    {
        $this->dbInit();
        
        $classified_array = array();
        $return_array = array();
    
        $this->Database->array_query( $classified_array, "SELECT ID FROM eZClassified_Classified ORDER BY Name" );

        foreach( $classified_array as $classifiedItem )
        {
            $return_array[] = new eZClassified( $classifiedItem["ID"] );
        }
        return $return_array;
    }

    /*
      Returns all the company found in the database.
      
      The company are returned as an array of eZClassified objects.
    */
    function getByCategory( $categoryID )
    {
        $this->dbInit();
        
        $classified_array = array();
        $return_array = array();
    
        $this->Database->array_query( $classified_array, "SELECT ClassifiedID FROM eZClassified_ClassifiedCategoryLink WHERE CategoryID='$categoryID'" );

        foreach( $classified_array as $classifiedItem )
        {
            $return_array[] = new eZClassified( $classifiedItem["ClassifiedID"] );
        }
        return $return_array;
    }
    
    /*
      Henter ut alle firma i databasen som inneholder søkestrengen.
    */
    function search( $query )
    {
        $this->dbInit();    
        $classified_array = array();
        $return_array = array();
    
        $this->Datbase->query_array( $classified_array, "SELECT ID FROM eZClassified_Classified WHERE Name LIKE '%$query%' ORDER BY Name" );

        foreach( $classified_array as $classifiedItem )
        {
            $return_array[] = new eZClassified( $classifiedItem["ID"] );
        }
        return $return_array;
    }

    /*!
      Add a company to the eZClassified object.
    */
    function addCompany( $company )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
        
        $this->dbInit();
        
        if ( get_class( $company ) == "ezcompany" )
        {
            $companyID = $company->id();

            $this->Database->query( "INSERT INTO eZClassified_ClassifiedCompanyLink
                                     SET CompanyID='$companyID', ClassifiedID='$this->ID'" );
            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns the logo image of the company as a eZImage object.
    */
    function company( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       $this->dbInit();


       $this->Database->array_query( $res_array, "SELECT CompanyID FROM eZClassified_ClassifiedCompanyLink
                                     WHERE
                                     ClassifiedID='$this->ID'
                                   " );

       print( count( $res_array ) );
       if ( count( $res_array ) == 1 )
       {
           if ( $res_array[0]["CompanyID"] != "NULL" )
           {
               $ret = new eZCompany( $res_array[0]["CompanyID"], false );
               print( "ka" );
               exit();
           }               
       }
       
       return $ret;
    }


    /*!
      Removes the company from every user category.
    */
    function removeCategoryies()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $this->Database->query( "DELETE FROM eZClassified_ClassifiedCategoryLink
                                WHERE ClassifiedID='$this->ID'" );
    }

    /*!
      Returns the categories that belong to this eZClassified object.
    */
    function categories( $companyID )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $this->dbInit();

        $this->Database->array_query( $categories_array, "SELECT CategoryID
                                                 FROM eZClassified_ClassifiedCategoryLink
                                                 WHERE ClassifiedID='$companyID'" );

        foreach( $categories_array as $categoriesItem )
        {
            $return_array[] = new eZClassifiedType( $categoriesItem["CategoryID"] );
        }

        return $return_array;
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
      Sets the description of the company.
    */
    function setDescription( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Description = $value;
    }


    /*!
      Sets the description of the company.
    */
    function setPrice( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Price = $value;
    }

    function setUser( $user )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( get_class( $user ) == "ezuser" )
        {
            $userID = $user->id();
            $this->UserID = $userID;
        }
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
      Returnerer kommentar.
    */
    function description()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Description;
    }

    /*!
      Returnerer kommentar.
    */
    function price()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Price;
    }
    
    /*!
      Returns the postimg time as a eZTimeDate object.
    */
    function created()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();

       $dateTime->setMySQLTimeStamp( $this->Created );
       
       return $dateTime;
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
    var $Name;
    var $Description;
    var $UserID;
    var $Price;
    var $Date;
    var $Created;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

    /// Check for update or insert
    var $Status_;
}

?>
