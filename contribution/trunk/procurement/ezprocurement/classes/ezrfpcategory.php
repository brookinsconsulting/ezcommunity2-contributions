<?php
//
// $Id: ezrfpcategory.php,v 1.103.2.9 2003/04/10 13:26:14 br Exp $
//
// Definition of eZRfpCategory class
//
// Created on: <18-Oct-2000 14:05:56 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

//!! eZRfp
//! eZRfpCategory handles rfp categories.
/*!

*/

/*!TODO
  Implement activeRfps();
*/

include_once( "classes/ezdb.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezrfp/classes/ezrfp.php" );

class eZRfpCategory
{
    /*!
      Constructs a new eZRfpCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZRfpCategory( $id=-1 )
    {
        $this->SortMode = 1;
        $this->ImageID = 0;
        $this->ParentID = 0;
        $this->ExcludeFromSearch = "0";
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZRfpCategory object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZRfp_Category" );

            $nextID = $db->nextID( "eZRfp_Category", "ID" );            
            
            $res = $db->query( "INSERT INTO eZRfp_Category
            ( ID, Name, Description, ExcludeFromSearch,
              SortMode, Placement, OwnerID, SectionID,
              ImageID, ParentID, EditorGroupID, ListLimit )
            VALUES
            ( '$nextID',
              '$name',
              '$description',
              '$this->ExcludeFromSearch',
              '$this->SortMode',
              '$nextID',  
              '$this->OwnerID',
              '$this->SectionID',
              '$this->ImageID',
              '$this->ParentID',
              '$this->EditorGroupID',
              '$this->ListLimit')" );
            
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZRfp_Category SET
		                         Name='$name',
                                 Description='$description',
                                 ExcludeFromSearch='$this->ExcludeFromSearch',
                                 SortMode='$this->SortMode',
                                 Placement='$this->Placement',  
                                 OwnerID='$this->OwnerID',
                                 SectionID='$this->SectionID',
                                 ImageID='$this->ImageID',
                                 EditorGroupID='$this->EditorGroupID',
                                 ParentID='$this->ParentID',
                                 ListLimit='$this->ListLimit'
                                 WHERE ID='$this->ID'" );
        }

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
        
        return true;
    }

    /*!
      Deletes a eZRfpGroup object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();


        $category = new eZRfpCategory( $catID );
        $categoryList = $category->getByParent( $category );
        foreach ( $categoryList as $categoryItem )
        {
            eZRfpCategory::delete( $categoryItem->id() );
        }

        $categoryID = $category->id();
        foreach ( $category->rfps() as $rfp )
        {
            $categoryDefinition = $rfp->categoryDefinition();
            if ( $categoryDefinition->id() == $category->id() )
            {
                $rfp->delete();
            }
            else
            {
                $rfpID = $rfp->id();
                $db->query( "DELETE FROM eZRfp_RfpCategoryLink
                             WHERE CategoryID='$categoryID' AND RfpID='$rfpdID'" );
            }
        }

        $db->query( "DELETE FROM eZRfp_CategoryPermission WHERE ObjectID='$categoryID'" );
        $db->query( "DELETE FROM eZRfp_Category WHERE ID='$categoryID'" );
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        
        if ( $id != "" )
        {
            $db->array_query( $category_array, "SELECT * FROM eZRfp_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $category_array ) == 1 )
            {
                $this->fill( $category_array[0] );
                $ret = true;
            }
        }
        return $ret;
    }

    function fill( $category_array )
    {
        $db =& eZDB::globalDatabase();
        $this->ID = $category_array[$db->fieldName( "ID" )];
        $this->Name = $category_array[$db->fieldName( "Name" )];
        $this->Description = $category_array[$db->fieldName( "Description" )];
        $this->ParentID = $category_array[$db->fieldName( "ParentID" )];
        $this->ExcludeFromSearch = $category_array[$db->fieldName( "ExcludeFromSearch" )];
        $this->SortMode = $category_array[$db->fieldName( "SortMode" )];
        $this->OwnerID = $category_array[$db->fieldName( "OwnerID" )];
        $this->Placement = $category_array[$db->fieldName( "Placement" )];
        $this->SectionID = $category_array[$db->fieldName( "SectionID" )];
        $this->ImageID = $category_array[$db->fieldName( "ImageID" )];
        $this->EditorGroupID = $category_array[$db->fieldName( "EditorGroupID" )];
        $this->ListLimit = $category_array[$db->fieldName( "ListLimit" )];
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZRfpCategory objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();
        $return_array = array();
        $category_array = array();

        $db->array_query( $category_array, "SELECT * FROM eZRfp_Category ORDER BY Name" );

        for ( $i=0; $i < count($category_array); $i++ )
        {
            $return_array[$i] = new eZRfpCategory( $category_array[$i] );
        }

        return $return_array;
    }

    /*!
        \static
        Returns the one, and only if one exists, category with the name

        Returns an object of eZRfpCategory.
     */
    function &getByName( $name )
    {
        $db =& eZDB::globalDatabase();
        $category = false;
        $name = $db->escapeString( $name );

        if ( $name != "" )
        {
            $db->array_query( $author_array, "SELECT * FROM eZRfp_Category WHERE Name='$name'" );

            if ( count( $author_array ) == 1 )
            {
                $category =& new eZRfpCategory( $author_array[0] );
            }
        }

        return $category;
    }

    /*!
        \static
        Searches for a category called $name, if $name is empty all categories are returned,
        if $name is an array it will search for categories matching any of the names in
        the array.

        Returns an array of eZRfpCategory.
    */
    function &search( $name, $showAll = false, $sortby='placement', $user = false )
    {
        $db =& eZDB::globalDatabase();
        $topic = array();

        if ( is_array( $name ) )
        {
            $searches = "";
            foreach ( $name as $n )
            {
                $n = $db->escapeString( $n );
                if ( $searches != "" )
                    $searches .= "OR ";
                $searches .= "Category.Name='$n' OR Category.Description='$n'";
            }
            $search = "$searches";
        }
        else if ( $name == "" )
        {
            $search = "";
        }
        else
        {
            $name = $db->escapeString( $name );
            $search = "Category.Name='$name' OR Category.Description='$name'";
        }

        $sortbySQL = "Name";
        switch ( $sortby )
        {
            case "name" : $sortbySQL = "Name"; break;
            case "placement" : $sortbySQL = "Placement"; break;
        }

        if ( get_class( $user ) != "ezuser" )
            $user =& eZUser::currentUser();

        $show_str = "";
        $usePermission = true;
        if ( !$showAll )
            $show_str = "AND Category.ExcludeFromSearch='0'";

        if ( $user )
        {
            $groups =& $user->groups( false );

            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= " Permission.GroupID=$group OR";
                else
                    $groupSQL .= " Permission.GroupID=$group OR";
                $i++;
            }
            $currentUserID = $user->id();

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }

        if ( $usePermission )
            $permissionSQL = "( ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' ) AND ";
        else
            $permissionSQL = "";

        $db->array_query( $author_array,
                          "SELECT Category.ID
                           FROM eZRfp_Category AS Category,
                                eZRfp_CategoryPermission as Permission
                           WHERE $permissionSQL ($search)
                                 AND Permission.ObjectID=Category.ID
                           GROUP BY Category.ID, Category.Placement
                           ORDER BY $sortbySQL" );

        foreach ( $author_array as $author )
        {
            $topic[] =& new eZRfpCategory( $author[$db->fieldName("ID")] );
        }
        return $topic;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      If $showAll is set to true every category is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZRfpCategory objects.
      If $check_write is true then the result will only contain categories which has read AND write permissions.
    */
    function getByParent( $parent, $showAll=false, $sortby='placement', $offset = 0, $max = -1, $user = false,
                          $check_write = false )
    {
        if ( get_class( $parent ) == "ezrfpcategory" )
        {
            $db =& eZDB::globalDatabase();
            if ( get_class( $user ) != "ezuser" )
                $user =& eZUser::currentUser();

            $sortbySQL = "Name";
            switch ( $sortby )
            {
                case "name" : $sortbySQL = "Name"; break;
                case "placement" : $sortbySQL = "Placement"; break;
            }

            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            $show_str = "";
            $usePermission = true;
            if ( !$showAll )
                $show_str = "AND ExcludeFromSearch='0'";

			if ( !isset( $groupSQL ) )
				$groupSQL = "";
            if ( $user )
            {
                $groups =& $user->groups( false );

                $i = 0;
                foreach ( $groups as $group )
                {
                    if ( $i == 0 )
                        $groupSQL .= " Permission.GroupID=$group OR";
                    else
                        $groupSQL .= " Permission.GroupID=$group OR";
                    $i++;
                }
                $currentUserID = $user->id();

                if ( $user->hasRootAccess() )
                    $usePermission = false;
            }

            $having_str = "";
            if ( $usePermission )
            {
                if ( $check_write )
                {
                    $perm_str = ", MAX(Permission.WritePermission) AS MaxWritePerm, MAX(Permission.ReadPermission) AS MaxReadPerm";
                    $PermGroupBy = "Permission.ObjectID, ";

                    $permissionSQL = "( ($groupSQL Permission.GroupID='-1') ) AND ";
                    $having_str = "HAVING MaxWritePerm=1 AND MaxReadPerm=1 ";
                }
                else
                    $permissionSQL = "( ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' ) AND ";
            }
            else
                $permissionSQL = "";

            $query = "SELECT Category.ID $perm_str
                      FROM eZRfp_Category as Category,
                           eZRfp_CategoryPermission as Permission
                      WHERE $permissionSQL
                            ParentID='$parentID'
                            AND Permission.ObjectID=Category.ID
                            $show_str
                      GROUP BY $PermGroupBy Category.ID, Category.Placement, Category.Name
                      $having_str
                      ORDER BY $sortbySQL";

            $db->array_query( $category_array, $query, array( "Limit" => $max, "Offset" => $offset ) );

            for ( $i=0; $i < count($category_array); $i++ )
            {
                $return_array[$i] = new eZRfpCategory( $category_array[$i][$db->fieldName("ID")] );
            }
            return $return_array;
        }
        else
        {
            return 0;
        }
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      If $showAll is set to true every category is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZRfpCategory objects.
      If $user is not a eZUser object the current user is used.

      If $check_write is true then the result will only count categories which has read AND write permissions.
    */
    function countByParent( $parent, $showAll=false, $user = false, $check_write = false )
    {
        if ( get_class( $parent ) == "ezrfpcategory" )
        {
            $db =& eZDB::globalDatabase();
            if ( get_class( $user ) != "ezuser" )
                $user =& eZUser::currentUser();

            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            $show_str = "";
            $usePermission = true;

            if ( !$showAll )
                $show_str = "AND ExcludeFromSearch='0'";

            if ( $user )
            {
                $groups =& $user->groups( false );
                
                $i = 0;
                foreach ( $groups as $group )
                {
                    if ( $i == 0 )
                        $groupSQL .= " Permission.GroupID=$group OR";
                    else
                        $groupSQL .= " Permission.GroupID=$group OR";
                    $i++;
                }
                $currentUserID = $user->id();

                if ( $user->hasRootAccess() )
                    $usePermission = false;
            }

            $permissionTables = "";
            $having_str = "";

            $sel_str = "count( DISTINCT Category.ID ) AS Count";
            if ( $usePermission )
            {
                if ( $check_write )
                {
                    $permissionSQL = "( ($groupSQL Permission.GroupID='-1') ) AND ";
                    $sel_str = "max( Permission.ReadPermission ) AS MaxRead, max( Permission.WritePermission ) AS MaxWrite ";
                    $having_str = "GROUP BY Permission.ObjectID HAVING MaxRead='1' AND MaxWrite='1'";
                }
                else
                    $permissionSQL = "( ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' ) AND ";
            }
            else
                $permissionSQL = "";

            $query = "SELECT $sel_str
                                           FROM eZRfp_Category AS Category,
                                                eZRfp_CategoryPermission as Permission
                                           WHERE $permissionSQL
                                                 ParentID='$parentID'
                                                 AND Permission.ObjectID=Category.ID
                                                 $show_str $having_str";
            if ( $usePermission and $check_write )
            {
                $db->array_query( $category_array, $query );
                return count( $category_array );
            }
            else
            {
                $db->query_single( $category_array, $query,
                                   "Count" );
                return $category_array;
            }
        }
        else
        {
            return 0;
        }
    }

    /*!
      Returns the current path as an array of arrays.

      The array is built up like: array( array( id, name ), array( id, name ) );

      See detailed description for an example of usage.
    */
    function path( $categoryID=0 )
    {
        if ( $categoryID == 0 )
        {
            $categoryID = $this->ID;
        }
            
        $category = new eZRfpCategory( $categoryID );

        $path = array();

        $parent = $category->parent();

        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent->id() ) );
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
      FIXME: Look at the tree function in (productcategory??!??)
      Recursive function that returns an array containing an int (tree position) and an array ( all items on that level )
     */
    function getTree( $parentID=0, $level=0 )
    {
        if ( get_class( $parentID ) == "ezrfpcategory" )
            $category = $parentID;
        else
            $category = new eZRfpCategory( $parentID );

        $categoryList = $category->getByParent( $category, true );

        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $return_array[] = $category, $level ) );

            if ( $category != 0 )
            {
                $tree = array_merge( $tree, $this->getTree( $category, $level ) );
            }
        }
        return $tree;
    }

    /*!
      Copies the categories recursively
     */
    function copyTree( $parentID, $parentCategory )
    {
        $category = new eZRfpCategory( $parentID );

        $categoryList = $category->getByParent( $category, true );

        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $return_array[] = new eZRfpCategory( $category->id() ) ) );

            $newCategory = new eZRfpCategory( );
            $newCategory->setName( $category->name() );
            $newCategory->setDescription( $category->description() );
            $newCategory->setParent( $parentCategory );
            $newCategory->setOwner( eZUser::currentUser() );
            $newCategory->store();

            // write access
            eZObjectPermission::setPermission( -1, $newCategory->id(), "rfp_category", 'w' );

            // read access 
            eZObjectPermission::setPermission( -1, $newCategory->id(), "rfp_category", 'r' );

            
            if ( $category != 0 )
            {
                $tree = array_merge( $tree, $this->copyTree( $category->id(), $newCategory ) );
            }
            
        }

        return $tree;
    }
    
    /*!
      \static
      Returns the Section ID. Returns false if the Category was not found.
    */
    function sectionIDStatic($categoryID )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT SectionID from eZRfp_Category WHERE ID='$categoryID'");
        
		if ( isset( $res[$db->fieldName("SectionID")] ) )
	        $sectionID = $res[$db->fieldName("SectionID")];
		else
			$sectionID = "";

        if ( $sectionID > 0 )
            return $sectionID;
        else
            return false;
    }

    /*!
      Returns the Image ID.
    */
    function &image( $AsObject = true )
    {
        if ( $AsObject )
            $image = new eZImage( $this->ImageID );
        else
            $image = $this->ImageID;

        return $image;
    }

    /*!
      Returns the Section ID. Returns false if the Category was not found.
    */
    function sectionID( )
    {
        return $this->SectionID;
    }

    /*!
      Returns the object ID to the category. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the category.
    */
    function name( $asHTML = true )
    {
        if ( isset( $this->Name ) )
        {
            if ( $asHTML )
                return eZTextTool::fixhtmlentities( htmlspecialchars( $this->Name ) );
            return $this->Name;
        }
        else
            return;
    }

    /*!
      Returns the group description.
    */
    function description( $asHTML = true )
    {
        if ( isset( $this->Description ) )
        {
            if ( $asHTML )
                return eZTextTool::fixhtmlentities( htmlspecialchars( $this->Description ) );
            return $this->Description;
        }
        else
            return;
    }

    /*!
      Returns the limit of rfp in list in this category
    */
    function listLimit()
    {
        if ( isset( $this->ListLimit ) )
        {
            return $this->ListLimit;
        }
        else
            return;
    }
    
    /*!
      Returns the placement.
    */
    function placement()
    {
	   if ( isset( $this->Placement ) )
	   {
            return htmlspecialchars( $this->Placement );
	   }
	   else
	       return;
    }

    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent( $as_object = true )
    {
       if ( !$as_object )
           return $this->ParentID;
       else if ( $this->ParentID != 0 )
       {
           return new eZRfpCategory( $this->ParentID );
       }
       else
       {
           return 0;
       }
    }

    /*!
      Returns the editor group if one exist. If not 0 is returned.
    */
    function editorGroup( $as_object = true )
    {
       if ( !$as_object )
           return $this->EditorGroupID;
       else if ( $this->EditorGroupID != 0 )
       {
           return new eZUserGroup( $this->EditorGroupID );
       }
       else
       {
           return 0;
       }
    }

    /*!
      Returns the creator of this category. Returns only the ID if given parameter is false.
     */
    function owner( $as_object = true )
    {
       if ( !$as_object )
           return $this->OwnerID;
       else if ( $this->OwnerID != 0 )
       {
           return new eZUser( $this->OwnerID );
       }
       else
       {
           return 0;
       }

    }

    /*!
      \Static
      Returns true if the given user is the author of the given object.
      $user is of type eZUser.
      $categoryID is the categoryID.
     */
    function isOwner( $user, $categoryID )
    {
        if ( get_class( $user ) != "ezuser" )
            return false;
        
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT OwnerID from eZRfp_Category WHERE ID='$categoryID'");
        $ownerID = $res[$db->fieldName("OwnerID")];
        if ( $ownerID == $user->id() )
            return true;

        return false;
    }

    
    /*!
      Returns the sort mode.

      1 - publishing date
      2 - alphabetic
      3 - alphabetic desc
      4 - absolute placement     
      5 - modification date
    */
    function sortMode( $return_id = false )
    {
        switch ( $this->SortMode )
        {
            case 1 :
            {
                $SortMode = "time";
            }
            break;
            
            case 2 :
            {
                $SortMode = "alpha";
            }
            break;
            
            case 3 :
            {
                $SortMode = "alphadesc";
            }
            break;
            
            case 4 :
            {
                $SortMode = "absolute_placement";
            }
            break;
            
            case 5 :
            {
                $SortMode = "modification";
            }
            break;
            
            default :
            {
                $SortMode = "time";
            }           
        }
        
        if ( $return_id == true )       
            return $this->SortMode;
        else
            return $SortMode;
        
    }

    /*!
      Returns true if the category is to be excluded
      from search, false if not.
    */
    function excludeFromSearch( )
    {
        $ret = false;
        if ( $this->ExcludeFromSearch  == "1" )
        {
            $ret = true;
        }
        
        return $ret;
    }
    


    /*!
      Sets the name of the category.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the limit of rfp per page in this category
    */
    function setListLimit( $value )
    {
        $this->ListLimit = $value;
    }
    
    /*!
      Sets the placement of the category.
    */
    function setPlacement( $value )
    {
        $this->Placement = $value;
    }

    /*!
      Sets the section of the category.
    */
    function setSectionID( $value )
    {
        $this->SectionID = $value;
    }

    /*!
      Sets the image of the category.
    */
    function setImage( $value )
    {
        if ( get_class( $value ) == "ezimage" )
            $value = $value->id();
        
        $this->ImageID = $value;
    }

    /*!
      Sets the description of the category.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the parent category.
    */
    function setParent( $value )
    {
        if ( get_class( $value ) == "ezrfpcategory" )
        {
            $this->ParentID = $value->id();
        }
        else
        {
            $this->ParentID = $value;
            setType( $this->ParentID, "integer" );
            
        }
    }
    
    /*!
      Sets the editor group.
    */
    function setEditorGroup( $value )
    {
        if ( get_class( $value ) == "ezusergroup" )
        {
            $this->EditorGroupID = $value->id();
        }
        else
        {
            $this->EditorGroupID = $value;
            setType( $this->EditorGroupID, "integer" );
            
        }
    }
    
    /*!
      Sets the owner of this category.
    */
    function setOwner( $value )
    {
        if ( get_class( $value ) == "ezuser" )
        {
            $this->OwnerID = $value->id();
        }
        else
        {
            $this->OwnerID = $value;
        }
    }
    
    
    /*!
      Sets the sort mode.

      1 - publishing date
      2 - alphabetic
      3 - alphabetic desc
      3 - absolute placement      
    */
    function setSortMode( $value )
    {
        $this->SortMode = $value;
    }
    
    /*!
     Sets the exclude from search bit.
     The argumen can be true or false.
    */
    function setExcludeFromSearch( $value )
    {
        if ( $value == true )
        {
            $this->ExcludeFromSearch = "1";
        }
        else
        {
            $this->ExcludeFromSearch = "0";
        }
    }
   
    /*!
      \static
      Removes an rfp from the category.
      Can be used as a static function if $categoryid is supplied
    */
    function removeRfp( $value, $categoryid = false )
    {
        if ( get_class( $value ) == "ezrfp" )
            $rfpID = $value->id();
        else if ( is_numeric( $value ) )
            $rfpID = $value;
        else
            return false;
        
        if ( !$categoryid )
            $categoryid = $this->ID;

        $db =& eZDB::globalDatabase();
        $query = "DELETE FROM eZRfp_RfpCategoryLink
                  WHERE CategoryID='$categoryid' AND
                        RfpID='$rfpID'";

        $db->query( $query );
    }

    /*!
      \static
      Adds an rfp to the category.
      Can be used as a static function if $categoryid is supplied
    */
    function addRfp( $value, $categoryid = false )
    {
        $db =& eZDB::globalDatabase();

        if ( get_class( $value ) == "ezrfp" )
            $rfpID = $value->id();
        else if ( is_numeric( $value ) )
            $rfpID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;

        // check if rfp already exists in category.
        $db->array_query( $qry, "SELECT ID FROM eZRfp_RfpCategoryLink
                                 WHERE CategoryID='$categoryid' AND RfpID='$rfpID'" );

        if ( count( $qry ) > 0 )
            return false;

        $db->array_query( $qry, "SELECT ID, Placement FROM eZRfp_RfpCategoryLink
                                 WHERE CategoryID='$categoryid'
                                 ORDER BY Placement DESC", array( "Limit" => 1, "Offset" => 0 ) );

        $place = count( $qry ) == 1 ? $qry[0][$db->fieldName("Placement")] + 1 : 1;

//this must be important rfp list bug!

        $db->begin();
        $db->lock( "eZRfp_RfpCategoryLink" );
        $nextID = $db->nextID( "eZRfp_RfpCategoryLink", "ID" );

        $query = "INSERT INTO eZRfp_RfpCategoryLink
                  ( ID,  CategoryID, RfpID, Placement  )
                  VALUES
                  ( '$nextID', '$categoryid', '$rfpID', '$place' )";

        $res = $db->query( $query );

        $db->unlock();

        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
    }


    /*!
      Checks whether user has permission to this category
    */
    function userHasPermision( $categoryID = -1, $read = true, $write = true, $user = false )
    {
        if ( $categoryID == -1 )
        {
            if ( is_object( $this ) )
            {
                $categoryID = $this->id();
            }
            else
            {
                return false;
            }
        }
        $perm = '';
        if ( $read )
        {
            $perm .= 'r';
        }
        if ( $write )
        {
            $perm .= 'w';
        }

        return eZObjectPermission::hasPermission( $categoryID, 'rfp_category', $perm, $user );
    }


    /*!
      Returns every rfp in a category as a array of eZRfp objects.

      If $fetchAll is set to true, both published and unpublished rfps will be returned.
      If it is set to false, then $fetchPublished will determine: If $fetchPublished iss
      set to true then only published rfps will be returned. If it is false, then only
      non-published rfps will be returned. 

      If $check_write is true then the result will only contain rfps which has read AND write permissions.
    */
    function &rfps( $sortMode="time",
                        $fetchAll=true,
                        $fetchPublished=true,
                        $offset=0,
                        $limit=50,
                        $categoryID=0,
                        $check_write = false )
    {

        if ( $categoryID != 0 )
            $catID = $categoryID;
        else
            $catID = $this->ID;
        
        $db =& eZDB::globalDatabase();

        if ( $offset == false )
            $offset = 0;

       switch ( $sortMode )
       {
           case "time" :
           {
               $GroupBy = ", Rfp.Published";
               $OrderBy = "Rfp.Published DESC";
           }
           break;

           case "alpha" :
           {
               $GroupBy = ", Rfp.Name";
               $OrderBy = "Rfp.Name ASC";
           }
           break;

           case "alphadesc" :
           {
               $GroupBy = ", Rfp.Name";
               $OrderBy = "Rfp.Name DESC";
           }
           break;

           case "absolute_placement" :
           {
               $GroupBy = ", Link.Placement";
               $OrderBy = "Link.Placement ASC";
           }
           break;

           case "modification" :
           {
               $GroupBy = ", Rfp.Modified";
               $OrderBy = "Rfp.Modified DESC";
           }
           break;
           
           default :
           {
               $GroupBy = ", Rfp.Published";
               $OrderBy = "Rfp.Published DESC";
           }
       }

       $return_array = array();
       $rfp_array = array();

       $user =& eZUser::currentUser();

       $loggedInSQL = "";
       $groupSQL = "";
       $categoryGroupSQL = "AND";
       $usePermission = true;
       if ( $user )
       {
           $groups =& $user->groups( false );

           foreach ( $groups as $group )
           {
               $groupSQL .= " ( Permission.GroupID='$group' AND CategoryPermission.GroupID='$group' ) OR
                              ( Permission.GroupID='$group' AND CategoryPermission.GroupID='-1' ) OR
                              ( Permission.GroupID='-1' AND CategoryPermission.GroupID='$group' ) OR
                            ";
           }
           $currentUserID = $user->id();
           $loggedInSQL = "Rfp.AuthorID=$currentUserID OR";

           if ( $user->hasRootAccess() )
               $usePermission = false;
       }

       $perm_str = "";
       $PermGroupBy = "";
       $having_str;
       if ( $usePermission )
       {
           if ( $check_write )
           {
               $perm_str = ", MAX(Permission.WritePermission) AS MaxWritePerm, MAX(Permission.ReadPermission) AS MaxReadPerm,
 MAX(CategoryPermission.WritePermission) AS CatMaxWritePerm, MAX(CategoryPermission.ReadPermission) AS CatMaxReadPerm";
               $PermGroupBy = "Permission.ObjectID, ";

               $permissionSQL = "( $loggedInSQL ( $groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) ) ";
               $having_str = "HAVING MaxReadPerm='1' AND MaxWritePerm='1' AND CatMaxReadPerm='1' AND CatMaxWritePerm='1'";
           }
           else
               $permissionSQL = "( $loggedInSQL ( $groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' )
                                               AND Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1' ) ";
       }
       else
           $permissionSQL = "";
       
       // fetch all rfps
       if ( $fetchAll  == true )             
       {
           if ( $permissionSQL == "" )
               $publishedSQL = "";
           else
               $publishedSQL = " AND";
       }
       
       // fetch only published rfps
       else if ( $fetchPublished  == true )  
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Rfp.IsPublished = '1' AND ";
           else
               $publishedSQL = " AND Rfp.IsPublished = '1' AND ";
       }

       // fetch only non-published rfps
       else                                  
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Rfp.IsPublished = '0' AND ";
           else
               $publishedSQL = " AND Rfp.IsPublished = '0' AND ";
       }
  $query = "SELECT Rfp.* $perm_str
                  FROM eZRfp_RfpCategoryDefinition as Definition,
                       eZRfp_Rfp as Rfp,
                       eZRfp_RfpCategoryLink as Link,
                       eZRfp_CategoryPermission as CategoryPermission,
                       eZRfp_RfpPermission AS Permission
                  WHERE
                        $permissionSQL
                        $publishedSQL
                        Link.CategoryID='$catID'
                        AND Permission.ObjectID=Rfp.ID
                        AND Link.RfpID=Rfp.ID
                        AND Definition.RfpID=Rfp.ID
                        AND CategoryPermission.ObjectID=Definition.CategoryID
                 GROUP BY $PermGroupBy Rfp.ID, Rfp.Published, Rfp.Name, Rfp.Contents, Rfp.ContentsWriterID, Rfp.LinkText, Rfp.AuthorID, Rfp.Modified, Rfp.Created, Rfp.PageCount, Rfp.IsPublished, Rfp.Keywords, Rfp.Discuss, Rfp.TopicID, Rfp.StartDate, Rfp.StopDate, Rfp.ImportID $GroupBy
                 $having_str
                 ORDER BY $OrderBy";

// won't list rfp!
// print( $query);
       
       if ( $limit == -1 )
       {
           $db->array_query( $rfp_array, $query );
       }
       else
       {
           $db->array_query( $rfp_array, $query, array( "Limit" => $limit, "Offset" => $offset ) );
       }
       for ( $i=0; $i < count( $rfp_array ); $i++ )
       {
           $return_array[$i] = new eZRfp( $rfp_array[$i] );
       }

       return $return_array;
    }

    /*!
      Returns the total number of rfps in the current category.

      If $fetchAll is set to true, both published and unpublished rfps will be counted.
      If it is set to false, then $fetchPublished will determine: If $fetchPublished is
      set to true then only published rfps will be counted. If it is false, then only
      non-published rfps will be counted.       

      If $check_write is true then the result will only contain rfps which has read AND write permissions.
    */
    function rfpCount( $fetchAll=true, $fetchPublished=true, $check_write = false )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $rfp_array = array();

        $user =& eZUser::currentUser();

        $loggedInSQL = "";
        $groupSQL = "";
        $categoryGroupSQL = "AND";
        $usePermission = true;
        if ( $user )
        {
            $groups =& $user->groups( false );
           
            foreach ( $groups as $group )
            {
                $groupSQL .= " ( Permission.GroupID='$group' AND CategoryPermission.GroupID='$group' ) OR
                              ( Permission.GroupID='$group' AND CategoryPermission.GroupID='-1' ) OR
                              ( Permission.GroupID='-1' AND CategoryPermission.GroupID='$group' ) OR
                            ";
            }
            $currentUserID = $user->id();
            $loggedInSQL = "Rfp.AuthorID=$currentUserID OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }

        $sel_str = "COUNT( DISTINCT Rfp.ID ) as Count";
        $group_str = "";
        if ( $usePermission )
        {
            if ( $check_write )
            {
                $sel_str = "Rfp.ID, max( Permission.ReadPermission ) AS MaxRead, max( Permission.WritePermission ) AS MaxWrite,
 max( CategoryPermission.ReadPermission ) AS CatMaxRead, max( CategoryPermission.WritePermission ) AS CatMaxWrite ";
                $permissionSQL = "( ( $loggedInSQL ($groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) ) ) ";
                $group_str = "GROUP BY Rfp.ID";
                $having_str = "HAVING MaxRead='1' AND MaxWrite='1' AND CatMaxRead='1' AND CatMaxWrite='1' ";
            }
            else
                $permissionSQL = "( ( $loggedInSQL ($groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) AND Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1') ) ";
        }
        else
            $permissionSQL = "";

        // fetch all rfps
        if ( $fetchAll  == true )
        {
            if ( $permissionSQL == "" )
                $publishedSQL = "";
            else
                $publishedSQL = " AND";
        }

        // fetch only published rfps
        else if ( $fetchPublished  == true )
        {
            if ( $permissionSQL == "" )
                $publishedSQL = " Rfp.IsPublished = '1' AND ";
            else
                $publishedSQL = " AND Rfp.IsPublished = '1' AND ";
        }

        // fetch only non-published rfps
        else
        {
            if ( $permissionSQL == "" )
                $publishedSQL = " Rfp.IsPublished = '0' AND ";
            else
                $publishedSQL = " AND Rfp.IsPublished = '0' AND ";
        }

        $query = "SELECT $sel_str
                  FROM eZRfp_RfpCategoryDefinition as Definition,
                       eZRfp_Rfp as Rfp,
                       eZRfp_RfpCategoryLink as Link,
                       eZRfp_CategoryPermission as CategoryPermission,
                       eZRfp_RfpPermission AS Permission
                  WHERE
                        $permissionSQL
                        $publishedSQL
                        Link.CategoryID='$this->ID'
                        AND Permission.ObjectID=Rfp.ID
                        AND Link.RfpID=Rfp.ID
                        AND Definition.RfpID=Rfp.ID
                        AND CategoryPermission.ObjectID=Definition.CategoryID
                        $group_str
                        $having_str
                        ";

        $db->array_query( $rfp_array, $query );

        if ( $usePermission and $check_write )
            $cnt = count( $rfp_array );
        else
            $cnt = $rfp_array[0][$db->fieldName("Count")];
        return $cnt;
    }


    /*!
      Moves the rfp placement with the given ID up.
    */
    function moveUp( $id )
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $qry, "SELECT * FROM eZRfp_RfpCategoryLink
                                  WHERE RfpID='$id' AND CategoryID='$this->ID'" );

        if ( is_numeric( $qry[$db->fieldName("ID")] ) )
        {
            $linkID = $qry[$db->fieldName("ID")];
            $placement = $qry[$db->fieldName("Placement")];

            $db->query_single( $qry, "SELECT ID, Placement FROM eZRfp_RfpCategoryLink
                                    WHERE Placement<'$placement' AND eZRfp_RfpCategoryLink.CategoryID='$this->ID'
                                    ORDER BY Placement DESC" );

            $newPlacement = $qry[$db->fieldName("Placement")];
            $listid = $qry[$db->fieldName("ID")];

            if ( $newPlacement == $placement )
            {
                $placement += 1;
            }

            if ( is_numeric( $listid ) )
            {
                $db->query( "UPDATE eZRfp_RfpCategoryLink SET Placement='$newPlacement' WHERE ID='$linkID'" );
                $db->query( "UPDATE eZRfp_RfpCategoryLink SET Placement='$placement' WHERE ID='$listid'" );
            }
        }
    }

    /*!
      Moves the rfp placement with the given ID down.
    */
    function moveDown( $id )
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $qry, "SELECT * FROM eZRfp_RfpCategoryLink
                                  WHERE RfpID='$id' AND CategoryID='$this->ID'" );

        if ( is_numeric( $qry[$db->fieldName("ID")] ) )
        {
            $linkID = $qry[$db->fieldName("ID")];
            $placement = $qry[$db->fieldName("Placement")];
            $db->query_single( $qry, "SELECT ID, Placement FROM eZRfp_RfpCategoryLink
                                    WHERE Placement>'$placement' AND eZRfp_RfpCategoryLink.CategoryID='$this->ID' ORDER BY Placement ASC" );

            $newPlacement = $qry[$db->fieldName("Placement")];
            $listid = $qry[$db->fieldName("ID")];

            if ( $newPlacement == $placement )
            {
                $newPlacement += 1;
            }

            if ( is_numeric( $listid ) )
            {
                $db->query( "UPDATE eZRfp_RfpCategoryLink SET Placement='$newPlacement' WHERE ID='$linkID'" );
                $db->query( "UPDATE eZRfp_RfpCategoryLink SET Placement='$placement' WHERE ID='$listid'" );
            }
        }
    }


    /*!
      Moves the rfp category with the given ID down.
     */
    function moveCategoryUp()
    {
        $db =& eZDB::globalDatabase();

        $query = "SELECT ID, Placement FROM eZRfp_Category
                 WHERE Placement<'$this->Placement' AND ParentID='$this->ParentID' ORDER BY Placement DESC";

        $db->query_single( $qry, $query );
        if ( is_numeric( $qry[$db->fieldName("ID")] ) )
        {
            $swapCatPlacement = $qry[$db->fieldName("Placement")];
            $swapCatID = $qry[$db->fieldName("ID")];

            if ( is_numeric( $swapCatPlacement ) )
            {
                $db->query( "UPDATE eZRfp_Category SET Placement='$swapCatPlacement' WHERE ID='$this->ID'" );
                $db->query( "UPDATE eZRfp_Category SET Placement='$this->Placement' WHERE ID='$swapCatID'" );
            }
        }
        else
        {
            $query = "SELECT ID, Placement FROM eZRfp_Category
                 WHERE Placement>'$this->Placement' AND ParentID='$this->ParentID' ORDER BY Placement DESC";

            $db->query_single( $qry, $query );
            if ( is_numeric( $qry[$db->fieldName("ID")] ) )
            {
                $swapCatPlacement = $qry[$db->fieldName("Placement")];
                $swapCatID = $qry[$db->fieldName("ID")];

                if ( is_numeric( $swapCatPlacement ) )
                {
                    $db->query( "UPDATE eZRfp_Category SET Placement=Placement-1 WHERE ParentID='$this->ParentID'" );
                    $db->query( "UPDATE eZRfp_Category SET Placement='$swapCatPlacement' WHERE ID='$this->ID'" );
                }
            }
        }
    }

     /*!
      Moves the rfp category with the given ID down.
     */
    function moveCategoryDown( )
    {
        $db =& eZDB::globalDatabase();
        $query = "SELECT ID, Placement FROM eZRfp_Category
                 WHERE Placement>'$this->Placement' AND ParentID='$this->ParentID' ORDER BY Placement ASC";

        $db->query_single( $qry, $query );
        if ( is_numeric( $qry[$db->fieldName("ID")] ) )
        {
            $swapCatPlacement = $qry[$db->fieldName("Placement")];
            $swapCatID = $qry[$db->fieldName("ID")];

            if ( is_numeric( $swapCatPlacement ) )
            {
                $db->query( "UPDATE eZRfp_Category SET Placement='$swapCatPlacement' WHERE ID='$this->ID'" );
                $db->query( "UPDATE eZRfp_Category SET Placement='$this->Placement' WHERE ID='$swapCatID'" );
            }
        }
        else
        {
            $query = "SELECT ID, Placement FROM eZRfp_Category
                 WHERE Placement<'$this->Placement' AND ParentID='$this->ParentID' ORDER BY Placement ASC";

            $db->query_single( $qry, $query );
            if ( is_numeric( $qry[$db->fieldName("ID")] ) )
            {
                $swapCatPlacement = $qry[$db->fieldName("Placement")];
                $swapCatID = $qry[$db->fieldName("ID")];

                if ( is_numeric( $swapCatPlacement ) )
                {
                    $db->query( "UPDATE eZRfp_Category SET Placement=Placement+1 WHERE ParentID='$this->ParentID'" );
                    $db->query( "UPDATE eZRfp_Category SET Placement='$swapCatPlacement' WHERE ID='$this->ID'" );
                }
            }
        }
    }

    /*!
      Connects this category to the bulkmail category specified.
     */
    function setBulkMailCategory( $value )
    {
        if ( get_class( $value ) == "ezbulkmailcategory" )
            $value = $value->id();

        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZRfp_BulkMailCategoryLink WHERE RfpCategoryID='$this->ID'" );

        if ( $value != false )
            $db->query( "INSERT INTO eZRfp_BulkMailCategoryLink SET RfpCategoryID='$this->ID', BulkMailCategoryID='$value'" );
    }

    /*!
      Returns the bulkMailCategory this category is connected to.
     */
    function bulkMailCategory( $asObject = true )
    {
        $db =& eZDB::globalDatabase();
        $result_array = array();
        $result = false;
        $db->array_query( $result_array, "SELECT BulkMailCategoryID FROM eZRfp_BulkMailCategoryLink WHERE RfpCategoryID='$this->ID'" );

        if ( count( $result_array ) > 0 )
            $result = ( $asObject == true ) ? new eZBulkMailCategory( $result_array[0][$db->fieldName("BulkMailCategoryID")] ) :  $result_array[0][$db->fieldName("BulkMailCategoryID")];

        return $result;
    }

    var $ID;
    var $Name;
    var $ListLimit;
    var $ParentID;
    var $Description;
    var $ExcludeFromSearch;
    var $SortMode;
    var $OwnerID;
    var $Placement;
    var $SectionID;
    var $ImageID;
    var $EditorGroupID;
}

?>
