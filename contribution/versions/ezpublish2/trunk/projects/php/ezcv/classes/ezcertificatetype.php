<?

// 
// $Id: ezcertificatetype.php,v 1.1 2000/12/11 12:08:19 pkej Exp $
//
// Definition of eZCertificateType class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <09-Nov-2000 14:52:40 pkej>
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

//!! eZCV
//! eZCertificateType handles the certificate types.
/*!
*/

include_once( "classes/ezdb.php" );
include_once( "ezcv/classes/ezcertificatecategory.php" );

class eZCertificateType
{
    /*!
      Constructor of the eZCertificateType.
    */
    function eZCertificateType( $id="-1", $fetch=true )
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
            $this->Database->query( "INSERT INTO eZCV_CertificateType set Name='$this->Name', Description='$this->Description',  CertificateCategoryID='$this->CertificateCategoryID'" );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $this->Database->query( "UPDATE eZCV_CertificateType set Name='$this->Name', Description='$this->Description', CertificateCategoryID='$this->CertificateCategoryID' WHERE ID='$this->ID'" );

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
        $this->Database->query( "DELETE FROM eZCV_CertificateType WHERE ID='$this->ID'" );
    }

    /*
        Fetches a company type with the  ID == $id
    */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            $this->Database->array_query( $company_type_array, "SELECT * FROM eZCV_CertificateType WHERE ID='$id'" );
            
            if ( count( $company_type_array ) > 1 )
            {
                die( "Error: More than one company type with the same ID found. Major problem, clean up the table eZCV_CertificateType. " );
            }
            elseif( count( $company_type_array ) == 1 )
            {
                $this->ID = $company_type_array[ 0 ][ "ID" ];
                $this->Name = $company_type_array[ 0 ][ "Name" ];
                $this->Description = $company_type_array[ 0 ][ "Description" ];
                $this->CertificateCategoryID = $company_type_array[ 0 ][ "CertificateCategoryID" ];
           }
        }
    }
    
    /*!
        Fetches all the company types in the db and return them as an array of objects.
     */
    function getAll( $OrderBy = "ID", $LimitStart = "None", $LimitBy = "None" )
    {
        $this->dbInit();
        
        switch( strtolower( $OrderBy ) )
        {
            case "description":
            case "desc":
                $OrderBy = "ORDER BY Description";
                break;
            case "name":
                $OrderBy = "ORDER BY Name";
                break;
            case "CertificateCategoryID":
            case "pid":
                $OrderBy = "ORDER BY CertificateCategoryID";
                break;
            case "id":
            case "typeid":
                $OrderBy = "ORDER BY ID";
                break;
            default:
                $OrderBy = "ORDER BY ID";
                break;
        }
        
        if( is_numeric( $LimitStart ) )
        {
            $LimitClause = "LIMIT $LimitStart";
            
            if( is_numeric( $LimitBy ) )
            {
                $LimitClause = $LimitClause . ", $LimitBy";
            }
        }
        else
        {
            $LimitClause = "";
        }
        
        $company_type_array = array();
        $return_array = array();

        
        $this->Database->array_query( $company_type_array, "SELECT ID FROM eZCV_CertificateType $OrderBy $LimitClause" );

        foreach( $company_type_array as $companyTypeItem )
        {
            $return_array[] = new eZCertificateType( $companyTypeItem["ID"] );
        }
        return $return_array;
    }
 
    /*!
        Fetches all the company types in the db and return them as an array of objects.
     */
    function getByCertificateCategoryID( $id = 0, $OrderBy = "ID", $LimitStart = "None", $LimitBy = "None" )
    {
        $this->dbInit();

        switch( strtolower( $OrderBy ) )
        {
            case "description":
            case "desc":
                $OrderBy = "ORDER BY Description";
                break;
            case "name":
                $OrderBy = "ORDER BY Name";
                break;
            case "CertificateCategoryID":
            case "pid":
                $OrderBy = "ORDER BY CertificateCategoryID";
                break;
            case "id":
            case "typeid":
                $OrderBy = "ORDER BY ID";
                break;
            default:
                $OrderBy = "ORDER BY ID";
                break;
        }
        
        if( is_numeric( $LimitStart ) )
        {
            $LimitClause = "LIMIT $LimitStart";
            
            if( is_numeric( $LimitBy ) )
            {
                $LimitClause = $LimitClause . ", $LimitBy";
            }
        }
        else
        {
            $LimitClause = "";
        }
        
        $company_type_array = array();
        $return_array = array();
        
        $this->Database->array_query( $company_type_array, "SELECT ID FROM eZCV_CertificateType WHERE CertificateCategoryID='$id' $OrderBy $LimitClause" );

        foreach( $company_type_array as $companyTypeItem )
        {
            $return_array[] = new eZCertificateType( $companyTypeItem["ID"] );
        }
        return $return_array;
    }

    /*!
      Print out the group path.
    */
    function path( $categoryID = 0 )
    {
        $this->dbInit();
        
        if( $categoryID == 0 )
        {
            $categoryID = $this->ID;
        }
        
        $category = new eZCertificateCategory( $categoryID );
        
        $path = array();
        
        $parent = $category->ParentID();
        
        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent ) );
        }
        else
        {
//              array_push( $path, $category->name() );
        }

        if ( $categoryID != 0 )
            array_push( $path, array( $category->id(), $category->name() ) );
        
        return $path;
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
    function setCertificateCategoryID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        if( is_numeric( $value ) )
        {
            $this->CertificateCategoryID = $value;
        }
    }

  
    /*!
      Set parent
    */
    function setCertificateCategory( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( get_class( $value ) == "ezcertificatecategory" )
        {
            $this->CertificateCategoryID = $value->id();
        }
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
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
      Returns the certificateCategoryID.
    */
    function certificateCategoryID( )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->CertificateCategoryID;
    }
    
    /*!
      Returns the certificateCategory.
    */
    function certificateCategory( )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
            
        $return = new eZCertificateCategory( $this->CertificateCategoryID );

        return $return;
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
    var $CertificateCategoryID;
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
