<?

// 
// $Id: ezcompanytype.php,v 1.13 2000/11/14 18:31:32 pkej-cvs Exp $
//
// Definition of eZCompanyType class
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

//!! eZContact
//! eZCompanyType handles the company types.
/*!
    This class handles the company types in the database. A company type can be used to
    describe different hiearchical information about a company. For example geographical
    area, business area, etc.
*/

include_once( "classes/ezdb.php" );

class eZCompanyType
{
    /*!
      Constructor of the eZCompanyType.
    */
    function eZCompanyType( $id="-1", $fetch=true )
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
      Stores the object in the database.
    */
    function store()
    {
        $this->dbInit();
        
        $ret = false;
        
        if ( !isSet( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZContact_CompanyType set Name='$this->Name', Description='$this->Description'" );

            $this->ID = mysql_insert_id();
            
            $this->Database->query( "INSERT INTO eZContact_CompanyTypeHiearchy set CompanyTypeID='$this->ID', ParentID='$this->ParentID'" );

            $this->ParentID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $this->Database->query( "UPDATE eZContact_CompanyType set Name='$this->Name', Description='$this->Description' WHERE ID='$this->ID'" );
            $this->Database->query( "UPDATE eZContact_CompanyTypeHiearchy set ParentID='$this->ParentID' WHERE CompanyTypeID='$this->ID'" );

            $this->State_ = "Coherent";
            $ret = true;
        }
        return $ret;
    }


    /*
      Delets the company type from the database.
     */
    function delete()
    {
        $this->dbInit();
        $this->Database->query( "DELETE FROM eZContact_CompanyType WHERE ID='$this->ID'" );
        $this->Database->query( "DELETE FROM eZContact_CompanyTypeHiearchy WHERE CompanyTypeID='$this->ID'" );
    }

    /*
        Fetches a company type with the  ID == $id
    */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            $this->Database->array_query( $company_type_array, "SELECT * FROM eZContact_CompanyType WHERE ID='$id'" );
            $this->Database->array_query( $company_type_hiearchy_array, "SELECT ParentID FROM eZContact_CompanyTypeHiearchy WHERE CompanyTypeID='$this->ID'" );
            
            
            if ( count( $company_type_array ) > 1 )
            {
                die( "Error: More than one company type with the same ID found. Major problem, clean up the table eZContact_CompanyType. " );
            }
            elseif( count( $company_type_array ) == 1 )
            {
                $this->ID = $company_type_array[ 0 ][ "ID" ];
                $this->Name = $company_type_array[ 0 ][ "Name" ];
                $this->Description = $company_type_array[ 0 ][ "Description" ];
            }
            
            if( count( $company_type_hiearchy_array ) > 1 )
            {
                die( "Error: A company type is only allowed one parent at the moment. " );
            }
            elseif( count( $company_type_hiearchy_array ) == 1 )
            {
                $this->ParentID = $company_type_hiearchy_array[ 0 ][ "ParentID" ];
            }
        }
    }
    
    /*!
        Fetches all the company types in the db and return them as an array of objects.
     */
    function getAll( )
    {
        $this->dbInit();
        $company_type_array = array();
        $return_array = array();

        
        $this->Database->array_query( $company_type_array, "SELECT ID FROM eZContact_CompanyType ORDER BY Name" );

        foreach( $company_type_array as $companyTypeItem )
        {
            $return_array[] = new eZCompanyType( $companyTypeItem["ID"] );
        }
    
        return $return_array;
    }
  
  
    /*!
      Set the name.
    */
    function setName( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Name = $value;
    }
    /*!
      Set the description.
    */
    function setDescription( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Description = $value;
    }
    /*!
      Set parent
    */
    function setParent( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ParentID = $value;
    }

  
    /*!
      Returns the ID of the company type.
    */
    function id()
    {
        return $this->ID;
    }
  
    /*!
      Returns the name.
    */
    function name( )
    {
        return $this->Name;
    }
  
    /*!
      Returns the description.
    */
    function description( )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Description;
    }
    
    /*!
      Returns the parent.
    */
    function parent( )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ParentID;
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
    var $ParentID;
    var $Name;
    var $Description;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

}

?>
