<?

// 
// $Id: ezcompanytype.php,v 1.30 2001/05/03 14:23:35 jb Exp $
//
// Definition of eZCompanyType class
//
// <real-name><<email-name>>
// Created on: <09-Nov-2000 14:52:40 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
include_once( "ezcontact/classes/ezcompany.php" );

class eZCompanyType
{
    /*!
      Constructor of the eZCompanyType.
    */
    function eZCompanyType( $id="-1", $fetch=true )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                
                $this->get( $this->ID );
            }
        }
    }
    
    /*!
      Stores the object in the database.
    */
    function store()
    {
        $db = eZDB::globalDatabase();
        
        $ret = false;
        $name = addslashes( $this->Name );
        $description = addslashes( $this->Description );
        if ( !isSet( $this->ID ) )
        {
            $db->query( "INSERT INTO eZContact_CompanyType set Name='$name', Description='$description',  ImageID='$this->ImageID', ParentID='$this->ParentID'" );

            $this->ID = mysql_insert_id();

            $ret = true;
        }
        else
        {
            $db->query( "UPDATE eZContact_CompanyType set Name='$name', Description='$description', ImageID='$this->ImageID', ParentID='$this->ParentID' WHERE ID='$this->ID'" );

            $ret = true;
        }
        return $ret;
    }


    /*
      Delets the company type from the database.
     */
    function delete()
    {
        $sub_categories =& eZCompanyType::getByParentID( $this->ID );
        foreach( $sub_categories as $category )
        {
            $category->delete();
        }
        $top_category = new eZCompanyType( 0 );
        $companies =& eZCompany::getByCategory( $this->ID );
        foreach( $companies as $company )
        {
            $company->removeCategories();
            $top_category->addCompany( $company );
        }
        $db = eZDB::globalDatabase();
        $db->query( "DELETE FROM eZContact_CompanyType WHERE ID='$this->ID'" );
    }

    /*
        Fetches a company type with the  ID == $id
    */  
    function get( $id )
    {
        $db = eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $company_type_array, "SELECT * FROM eZContact_CompanyType WHERE ID='$id'" );
            
            if ( count( $company_type_array ) > 1 )
            {
                die( "Error: More than one company type with the same ID found. Major problem, clean up the table eZContact_CompanyType. " );
            }
            elseif( count( $company_type_array ) == 1 )
            {
                $this->ID = $company_type_array[ 0 ][ "ID" ];
                $this->Name = $company_type_array[ 0 ][ "Name" ];
                $this->Description = $company_type_array[ 0 ][ "Description" ];
                $this->ParentID = $company_type_array[ 0 ][ "ParentID" ];
                $this->ImageID = $company_type_array[ 0 ][ "ImageID" ];
           }
        }
    }
    
    /*!
        Fetches all the company types in the db and return them as an array of objects.
     */
    function getAll( $OrderBy = "ID", $LimitStart = "None", $LimitBy = "None" )
    {
        $db = eZDB::globalDatabase();
        
        switch( strtolower( $OrderBy ) )
        {
            case "description":
            case "desc":
                $OrderBy = "ORDER BY Description";
                break;
            case "name":
                $OrderBy = "ORDER BY Name";
                break;
            case "parentid":
            case "pid":
                $OrderBy = "ORDER BY ParentID";
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

        
        $db->array_query( $company_type_array, "SELECT ID FROM eZContact_CompanyType $OrderBy $LimitClause" );

        foreach( $company_type_array as $companyTypeItem )
        {
            $return_array[] = new eZCompanyType( $companyTypeItem["ID"] );
        }
        return $return_array;
    }
 
    /*!
        Fetches all the company types in the db and return them as an array of objects.
     */
    function &getByParentID( $parent = 0, $OrderBy = "ID", $LimitStart = "None", $LimitBy = "None" )
    {
        $db = eZDB::globalDatabase();

        if ( get_class( $parent ) == "ezcompanytype" )
        {
            $id = $parent->id();
        }
        else
        {
            $id = $parent;
        }
        
        switch( strtolower( $OrderBy ) )
        {
            case "description":
            case "desc":
                $OrderBy = "ORDER BY Description";
                break;
            case "name":
                $OrderBy = "ORDER BY Name";
                break;
            case "parentid":
            case "pid":
                $OrderBy = "ORDER BY ParentID";
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
            else
                $LimitClause = "$LimitClause, -1";
        }
        else
        {
            $LimitClause = "";
        }
        
        $company_type_array = array();
        $return_array = array();
        
        $db->array_query( $company_type_array, "SELECT ID FROM eZContact_CompanyType WHERE ParentID='$id' $OrderBy $LimitClause" );

        foreach( $company_type_array as $companyTypeItem )
        {
            $return_array[] =& new eZCompanyType( $companyTypeItem["ID"] );
        }
        return $return_array;
    }

    /*!
        Check if this item has children
     */
    function hasChildren( &$childrenCount, $id = "this" )
    {
        $ret = false;
        
        if( $id == "this" )
        {
            $id = $this->ID;
        }
        
        if( is_numeric( $id ) )
        {
            $db = eZDB::globalDatabase();
            
            $company_type_array = array();
            $db->array_query( $company_type_array, "SELECT ParentID FROM eZContact_CompanyType WHERE ParentID='$id'" );
            $childrenCount = count( $company_type_array );
            
            if( $childrenCount != 0 )
            {
                $ret = true;
            }
        }
        
        return $ret;
    }

    /*!
      Print out the group path.
    */
    function path( $categoryID = 0 )
    {
        $db = eZDB::globalDatabase();
        
        if( $categoryID == 0 )
        {
            $categoryID = $this->ID;
        }
        
        $category = new eZCompanyType( $categoryID );
        
        $path = array();
        
        $parent = $category->parentID();
        
        if ( $parent != 0 )
        {
            $path = array_merge( $path, eZCompanyType::path( $parent ) );
        }
        else
        {
//              array_push( $path, $category->name() );
        }

        if ( $categoryID != 0 )
            array_push( $path, array( $category->id(), $category->name() ) );
        
        return $path;
    }

    function &getTree( $parentID=0, $level=0, $add_top = false, $name = false )
    {
        if ( $add_top )
        {
            $tree = array();
            $level++;
            $category = new eZCompanyType();
            $category->ID = 0;
            $category->setName( $name );
            array_push( $tree, array( $category, $level ) );
            $tree = array_merge( $tree, eZCompanyType::getTree( 0, $level ) );
        }
        else
        {
            $categoryList =& eZCompanyType::getByParentID( $parentID );

            $tree = array();
            $level++;
            foreach ( $categoryList as $category )
            {
                array_push( $tree, array( $category , $level ) );

                if ( $category != 0 )
                {
                    $tree = array_merge( $tree, eZCompanyType::getTree( $category->id(), $level ) );
                }
            }

        }
        return $tree;
    }

    /*!
      Adds a company to the current user category.

      Returns true if successful, false if not.
    */
    function addCompany( $company )
    {
       $ret = false;

       if ( get_class( $company ) )
       {
           $db = eZDB::globalDatabase();

           $companyID = $company->id();

//             if ( $this->ID > 1 )
           {
               $db->query( "INSERT INTO eZContact_CompanyTypeDict
                                    SET
                                    CompanyID='$companyID',
                                    CompanyTypeID='$this->ID'" );
               $ret = true;
           }
       }
       return $ret;
    }

    /*!
        Set an image for this category.
     */
    function setImageID( $value )
    {
        if ( get_class( $value ) == "ezimage" )
        {
            $this->ImageID = $value->id();
        }
        elseif( is_numeric( $value ) )
        {
            $this->ImageID = $value;
        }
        
    }
    
    
    /*!
      Set the name.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }
    /*!
      Set the description.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Set parent
    */
    function setParentID( $value )
    {
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
        return $this->Description;
    }
    
    /*!
      Returns the parent.
    */
    function parentID( )
    {
        return $this->ParentID;
    }
    
    
    /*!
      Returns the image id.
    */
    function imageID( )
    {
        return $this->ImageID;
    }

    var $ID;
    var $ParentID;
    var $Name;
    var $Description;
    var $ImageID;
}

?>
