<?

// 
// $Id: ezcategory.php,v 1.1 2000/11/28 14:30:11 ce-cvs Exp $
//
// Definition of eZCategory class
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
//! eZCategory handles the category.
/*!
    This class handles the category types in the database. A category type can be used to
    describe different hiearchical information about a category. For example geographical
    area, business area, etc.
*/

include_once( "classes/ezdb.php" );

class eZCategory
{
    /*!
      Constructor of the eZCategory.
    */
    function eZCategory( $id="-1", $fetch=true )
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
            $this->Database->query( "INSERT INTO eZClassified_Category set Name='$this->Name', Description='$this->Description', ParentID='$this->ParentID'" );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $this->Database->query( "UPDATE eZClassified_Category set Name='$this->Name', Description='$this->Description', ParentID='$this->ParentID' WHERE ID='$this->ID'" );

            $this->State_ = "Coherent";
            $ret = true;
        }
        return $ret;
    }


    /*
      Delets the category from the database.
     */
    function delete()
    {
        $this->dbInit();
        $this->Database->query( "DELETE FROM eZClassified_Category WHERE ID='$this->ID'" );
    }

    /*
        Fetches a category with the  ID == $id
    */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            $this->Database->array_query( $category_array, "SELECT * FROM eZClassified_Category WHERE ID='$id'" );
            
            if ( count( $category_array ) > 1 )
            {
                die( "Error: More than one category type with the same ID found. Major problem, clean up the table eZClassified_Category. " );
            }
            elseif( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[ 0 ][ "ID" ];
                $this->Name = $category_array[ 0 ][ "Name" ];
                $this->Description = $category_array[ 0 ][ "Description" ];
                $this->ParentID = $category_array[ 0 ][ "ParentID" ];
           }
        }
    }
    
    /*!
        Fetches all the category in the db and return them as an array of objects.
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
        
        $category_array = array();
        $return_array = array();

        
        $this->Database->array_query( $category_array, "SELECT ID FROM eZClassified_Category $OrderBy $LimitClause" );

        foreach( $category_array as $categoryItem )
        {
            $return_array[] = new eZCategory( $categoryItem["ID"] );
        }
        return $return_array;
    }
 
    /*!
        Fetches all the category in the db and return them as an array of objects.
     */
    function getByParentID( $id = 0, $OrderBy = "ID", $LimitStart = "None", $LimitBy = "None" )
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
        
        $category_array = array();
        $return_array = array();
        
        $this->Database->array_query( $category_array, "SELECT ID FROM eZClassified_Category WHERE ParentID='$id' $OrderBy $LimitClause" );

        foreach( $category_array as $categoryItem )
        {
            $return_array[] = new eZCategory( $categoryItem["ID"] );
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
        
        $category = new eZCategory( $categoryID );
        
        $path = array();
        
        $parent = $category->parentID();
        
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
      Adds a classified to the current user category.

      Returns true if successful, false if not.
    */
    function addClassified( $classified )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $ret = false;

       if ( get_class( $classified ) == "ezclassified" )
       {
           $this->dbInit();

           $classifiedID = $classified->id();

//             if ( $this->ID > 1 )
           {
               $this->Database->query( "INSERT INTO eZClassified_ClassifiedCategoryLink
                                    SET
                                    ClassifiedID='$classifiedID',
                                    CategoryID='$this->ID'" );
               $ret = true;
           }
       }
       return $ret;
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
    function setParentID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ParentID = $value;
    }

  
    /*!
      Returns the ID of the category.
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
    function parentID( )
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
