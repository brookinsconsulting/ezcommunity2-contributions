<?php
// 
// $Id: ezarticlecategory.php,v 1.89 2001/09/03 16:05:31 bf Exp $
//
// Definition of eZArticleCategory class
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

//!! eZArticle
//! eZArticleCategory handles article categories.
/*!
  
*/

/*!TODO
  Implement activeArticles();
*/

include_once( "classes/ezdb.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezarticle/classes/ezarticle.php" );

class eZArticleCategory
{
    /*!
      Constructs a new eZArticleCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZArticleCategory( $id=-1 )
    {
        $this->SortMode = 1;
        $this->ImageID = 0;
        $this->ParentID = 0;
        $this->ExcludeFromSearch = "0";
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZArticleCategory object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZArticle_Category" );

            $nextID = $db->nextID( "eZArticle_Category", "ID" );            
            
            $res = $db->query( "INSERT INTO eZArticle_Category
            ( ID, Name, Description, ExcludeFromSearch,
              SortMode, Placement, OwnerID, SectionID,
              ImageID, ParentID, EditorGroupID )
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
              '$this->EditorGroupID')" );
            
			$this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZArticle_Category SET
		                         Name='$name',
                                 Description='$description',
                                 ExcludeFromSearch='$this->ExcludeFromSearch',
                                 SortMode='$this->SortMode',
                                 Placement='$this->Placement',  
                                 OwnerID='$this->OwnerID',
                                 SectionID='$this->SectionID',
                                 ImageID='$this->ImageID',
                                 EditorGroupID='$this->EditorGroupID',
                                 ParentID='$this->ParentID' WHERE ID='$this->ID'" );
        }

        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
        
        return true;
    }

    /*!
      Deletes a eZArticleGroup object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();


        $category = new eZArticleCategory( $catID );
        $categoryList = $category->getByParent( $category );
        foreach( $categoryList as $categoryItem )
        {
            eZArticleCategory::delete( $categoryItem->id() );
        }



        $categoryID = $category->id();
        foreach( $category->articles() as $article )
        {
            $categoryDefinition = $article->categoryDefinition();
            if ( $categoryDefinition->id() == $category->id() )
            {
                $article->delete();
            }
            else
            {
                $articleID = $article->id();
                $db->query( "DELETE FROM eZArticle_ArticleCategoryLink
                             WHERE CategoryID='$categoryID' AND ArticleID='$articledID'" );
            }
        }

        $db->query( "DELETE FROM eZArticle_CategoryPermission WHERE ObjectID='$categoryID'" );
        $db->query( "DELETE FROM eZArticle_Category WHERE ID='$categoryID'" );
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
            $db->array_query( $category_array, "SELECT * FROM eZArticle_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][$db->fieldName("ID")];
                $this->Name = $category_array[0][$db->fieldName("Name")];
                $this->Description = $category_array[0][$db->fieldName("Description")];
                $this->ParentID = $category_array[0][$db->fieldName("ParentID")];
                $this->ExcludeFromSearch = $category_array[0][$db->fieldName("ExcludeFromSearch")];
                $this->SortMode = $category_array[0][$db->fieldName("SortMode")];
                $this->OwnerID = $category_array[0][$db->fieldName("OwnerID")];
                $this->Placement = $category_array[0][$db->fieldName("Placement")];
                $this->SectionID = $category_array[0][$db->fieldName("SectionID")];
                $this->ImageID = $category_array[0][$db->fieldName("ImageID")];
                $this->EditorGroupID = $category_array[0][$db->fieldName("EditorGroupID")];
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZArticleCategory objects.
    */
    function getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $category_array = array();
        
        $db->array_query( $category_array, "SELECT ID,Name FROM eZArticle_Category ORDER BY Name" );

        
        for ( $i=0; $i < count($category_array); $i++ )
        {
            $return_array[$i] = new eZArticleCategory( $category_array[$i][$db->fieldName("ID")], 0 );
        }
        
        return $return_array;
    }

    /*!
        \static
        Returns the one, and only if one exists, category with the name
        
        Returns an object of eZArticleCategory.
     */
    function &getByName( $name )
    {
        $db =& eZDB::globalDatabase();
        
        $topic = false;
        
        $name = $db->escapeString( $name );

        if( $name != "" )
        {
            $db->array_query( $author_array, "SELECT * FROM eZArticle_Category WHERE Name='$name'" );

            if( count( $author_array ) == 1 )
            {
                $category =& new eZArticleCategory( $author_array[0][$db->fieldName("ID")] );
            }
        }
        
        return $category;
    }

    /*!
        \static
        Searches for a category called $name, if $name is empty all categories are returned,
        if $name is an array it will search for categories matching any of the names in
        the array.

        Returns an array of eZArticleCategory.
    */
    function &search( $name )
    {
        $db =& eZDB::globalDatabase();
        $topic = array();

        if ( is_array( $name ) )
        {
            $searches = "";
            foreach( $name as $n )
            {
                $n = $db->escapeString( $n );
                if ( $searches != "" )
                    $searches .= "OR ";
                $searches .= "Name='$n' OR Description='$n'";
            }
            $search = "WHERE $searches";
        }
        else if( $name == "" )
        {
            $search = "";
        }
        else
        {
            $name = $db->escapeString( $name );
            $search = "WHERE Name='$name'";
        }

        $db->array_query( $author_array,
                          "SELECT ID FROM eZArticle_Category $search" );

        foreach( $author_array as $author )
        {
            $topic[] =& new eZArticleCategory( $author[$db->fieldName("ID")] );
        }
        return $topic;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      If $showAll is set to true every category is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZArticleCategory objects.      
    */
    function getByParent( $parent, $showAll=false, $sortby='placement', $offset = 0, $max = -1 )
    {
        if ( get_class( $parent ) == "ezarticlecategory" )
        {
            $db =& eZDB::globalDatabase();
            $user =& eZUser::currentUser();
            
            $sortbySQL = "Name";
            switch( $sortby )
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

            if ( $user )
            {
                $groups =& $user->groups( true );
                
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

            $query = "SELECT Category.ID 
                      FROM eZArticle_Category as Category,
                           eZArticle_CategoryPermission as Permission
                      WHERE $permissionSQL
                            ParentID='$parentID'
                            AND Permission.ObjectID=Category.ID
                            $show_str
                      GROUP BY Category.ID, Category.Placement
                      ORDER BY $sortbySQL";

            $db->array_query( $category_array, $query, array( "Limit" => $max, "Offset" => $offset ) );

            for ( $i=0; $i < count($category_array); $i++ )
            {
                $return_array[$i] = new eZArticleCategory( $category_array[$i][$db->fieldName("ID")] );
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

      The categories are returned as an array of eZArticleCategory objects.      
    */
    function countByParent( $parent, $showAll=false )
    {
        if ( get_class( $parent ) == "ezarticlecategory" )
        {
            $db =& eZDB::globalDatabase();
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
                $groups =& $user->groups( true );
                
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
                $loggedInSQL = "Article.AuthorID=$currentUserID OR";

                if ( $user->hasRootAccess() )
                    $usePermission = false;
            }

            if ( $usePermission )
                $permissionSQL = "( ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' AND CategoryPermission.GroupID='-1' AND CategoryPermission.ReadPermission='1') )";
            else
                $permissionSQL = "";


            $db->query_single( $category_array, "SELECT count( ID ) AS Count
                                           FROM eZArticle_Category
                                           WHERE ParentID='$parentID' $show_str",
                                           "Count" );

            return $category_array;
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
            
        $category = new eZArticleCategory( $categoryID );

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
        $category = new eZArticleCategory( $parentID );

        $categoryList = $category->getByParent( $category, true );
        
        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $return_array[] = new eZArticleCategory( $category->id() ), $level ) );
            
            if ( $category != 0 )
            {
                $tree = array_merge( $tree, $this->getTree( $category->id(), $level ) );
            }
            
        }

        return $tree;
    }

    /*!
      Copies the categories recursively    
     */
    function copyTree( $parentID, $parentCategory )
    {
        $category = new eZArticleCategory( $parentID );

        $categoryList = $category->getByParent( $category, true );
        
        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $return_array[] = new eZArticleCategory( $category->id() ) ) );

            $newCategory = new eZArticleCategory( );
            $newCategory->setName( $category->name() );
            $newCategory->setDescription( $category->description() );
            $newCategory->setParent( $parentCategory );
            $newCategory->setOwner( eZUser::currentUser() );
            $newCategory->store();
            
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
        $db->query_single( $res, "SELECT SectionID from eZArticle_Category WHERE ID='$categoryID'");
        
        $sectionID = $res[$db->fieldName("SectionID")];

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
           if( $asHTML )
               return htmlspecialchars( $this->Name );

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
               return htmlspecialchars( $this->Description );

           return $this->Description;
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
           return new eZArticleCategory( $this->ParentID );
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
        if( get_class( $user ) != "ezuser" )
            return false;
        
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT OwnerID from eZArticle_Category WHERE ID='$categoryID'");
        $ownerID = $res[$db->fieldName("OwnerID")];
        if( $ownerID == $user->id() )
            return true;

        return false;
    }

    
    /*!
      Returns the sort mode.

      1 - publishing date
      2 - alphabetic
      3 - alphabetic desc
      3 - absolute placement      
    */
    function sortMode( $return_id = false )
    {
       if ( isset( $this->State_ ) and $this->State_ == "Dirty" )
            $this->get( $this->ID );

       switch( $this->SortMode )
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
       if ( get_class( $value ) == "ezarticlecategory" )
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
      Removes an article from the category.
      Can be used as a static function if $categoryid is supplied
    */
    function removeArticle( $value, $categoryid = false )
    {
        if ( get_class( $value ) == "ezarticle" )
            $articleID = $value->id();
        else if ( is_numeric( $value ) )
            $articleID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;

        $db =& eZDB::globalDatabase();
        $query = "DELETE FROM eZArticle_ArticleCategoryLink
                  WHERE CategoryID='$categoryid' AND
                        ArticleID='$articleID'";

        $db->query( $query );
    }

    /*!
      \static
      Adds an article to the category.
      Can be used as a static function if $categoryid is supplied
    */
    function addArticle( $value, $categoryid = false )
    {
        $db =& eZDB::globalDatabase();

        if ( get_class( $value ) == "ezarticle" )
            $articleID = $value->id();
        else if ( is_numeric( $value ) )
            $articleID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;

        // check if article already exists in category.
        $db->array_query( $qry, "SELECT ID FROM eZArticle_ArticleCategoryLink
                                 WHERE CategoryID='$categoryid' AND ArticleID='$articleID'" );

        if ( count( $qry ) > 0 )
            return false;
        
        
        $db->array_query( $qry, "SELECT ID, Placement FROM eZArticle_ArticleCategoryLink
                                 WHERE CategoryID='$categoryid'
                                 ORDER BY Placement DESC", array( "Limit" => 1, "Offset" => 0 ) );

        $place = count( $qry ) == 1 ? $qry[0][$db->fieldName("Placement")] + 1 : 1;

        $db->begin( );
    
        $db->lock( "eZArticle_ArticleCategoryLink" );

        $nextID = $db->nextID( "eZArticle_ArticleCategoryLink", "ID" );
        
        $query = "INSERT INTO eZArticle_ArticleCategoryLink
                  ( ID,  CategoryID, ArticleID, Placement  )
                  VALUES
                  ( '$nextID', '$categoryid', '$articleID', '$place' )";

        $res = $db->query( $query );

        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
        
    }

    /*!
      Returns every article in a category as a array of eZArticle objects.

      If $fetchAll is set to true, both published and unpublished articles will be returned.
      If it is set to false, then $fetchPublished will determine: If $fetchPublished is
      set to true then only published articles will be returned. If it is false, then only
      non-published articles will be returned. 
    */
    function &articles( $sortMode="time",
                        $fetchAll=true,
                        $fetchPublished=true,
                        $offset=0,
                        $limit=50 )
    {
        $db =& eZDB::globalDatabase();

       switch( $sortMode )
       {
           case "time" :
           {
               $OrderBy = "Article.Published DESC";
           }
           break;

           case "alpha" :
           {
               $OrderBy = "Article.Name ASC";
           }
           break;

           case "alphadesc" :
           {
               $OrderBy = "Article.Name DESC";
           }
           break;

           case "absolute_placement" :
           {
               $OrderBy = "Link.Placement ASC";
           }
           break;
           
           default :
           {
               $OrderBy = "Article.Published DESC";
           }
       }
       $return_array = array();
       $article_array = array();

       $user =& eZUser::currentUser();

       $loggedInSQL = "";
       $groupSQL = "";
       $categoryGroupSQL = "AND";
       $usePermission = true;
       if ( $user )
       {
           $groups =& $user->groups( true );
           
           $i = 0;
           foreach ( $groups as $group )
           {
               if ( $i == 0 )
                   $groupSQL .= "( Permission.GroupID=$group AND CategoryPermission.GroupID=$group ) OR";
               else
                   $groupSQL .= " ( Permission.GroupID=$group AND CategoryPermission.GroupID=$group ) OR";
               
               $i++;
           }
           $currentUserID = $user->id();
           $loggedInSQL = "Article.AuthorID=$currentUserID OR";

           if ( $user->hasRootAccess() )
               $usePermission = false;
       }

       if ( $usePermission )
           $permissionSQL = "( ( $loggedInSQL ($groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) AND Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1') ) ";
       else
           $permissionSQL = "";
       
       // fetch all articles
       if ( $fetchAll  == true )             
       {
           if ( $permissionSQL == "" )
               $publishedSQL = "";
           else
               $publishedSQL = " AND";
       }
       
       // fetch only published articles
       else if ( $fetchPublished  == true )  
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Article.IsPublished = '1' AND ";
           else
               $publishedSQL = " AND Article.IsPublished = '1' AND ";
       }

       // fetch only non-published articles
       else                                  
       {
           if ( $permissionSQL == "" )
               $publishedSQL = " Article.IsPublished = '0' AND ";
           else
               $publishedSQL = " AND Article.IsPublished = '0' AND ";
       }

       $query = "SELECT Article.ID as ArticleID
                  FROM eZArticle_ArticleCategoryDefinition as Definition,
                       eZArticle_Article as Article,
                       eZArticle_ArticleCategoryLink as Link,
                       eZArticle_CategoryPermission as CategoryPermission,
                       eZArticle_ArticlePermission AS Permission
                  WHERE 
                        $permissionSQL
                        $publishedSQL
                        Link.CategoryID='$this->ID'
                        AND Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID
                        AND Definition.ArticleID=Article.ID
                        AND CategoryPermission.ObjectID=Definition.CategoryID
                 GROUP BY Article.ID, Article.Published
                 ORDER BY $OrderBy";


       if ( $limit == -1 )
       {
           $db->array_query( $article_array, $query );
       }
       else
       {
           $db->array_query( $article_array, $query, array ( "Limit" => $limit, "Offset" => $offset ) );
       }
 
       for ( $i=0; $i < count($article_array); $i++ )
       {
           $return_array[$i] = new eZArticle( $article_array[$i][$db->fieldName("ArticleID")] );
       }

       return $return_array;
    }

    /*!
      Returns the total number of articles in the current category.

      If $fetchAll is set to true, both published and unpublished articles will be counted.
      If it is set to false, then $fetchPublished will determine: If $fetchPublished is
      set to true then only published articles will be counted. If it is false, then only
      non-published articles will be counted.       
    */
    function articleCount( $fetchAll=true, $fetchPublished=true )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $article_array = array();

        $user =& eZUser::currentUser();

        $loggedInSQL = "";
        $groupSQL = "";
        $categoryGroupSQL = "AND";
        $usePermission = true;
        if ( $user )
        {
            $groups =& $user->groups( true );
           
            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= "( Permission.GroupID=$group AND CategoryPermission.GroupID=$group ) OR";
                else
                    $groupSQL .= " ( Permission.GroupID=$group AND CategoryPermission.GroupID=$group ) OR";
                $i++;
            }
            $currentUserID = $user->id();
            $loggedInSQL = "Article.AuthorID=$currentUserID OR";

            if ( $user->hasRootAccess() )
                $usePermission = false;
        }

        if ( $usePermission )
            $permissionSQL = "( ( $loggedInSQL ($groupSQL Permission.GroupID='-1' AND CategoryPermission.GroupID='-1' ) AND Permission.ReadPermission='1' AND CategoryPermission.ReadPermission='1') ) ";
        else
            $permissionSQL = "";
       
        // fetch all articles
        if ( $fetchAll  == true )             
        {
            if ( $permissionSQL == "" )
                $publishedSQL = "";
            else
                $publishedSQL = " AND";
        }
       
        // fetch only published articles
        else if ( $fetchPublished  == true )  
        {
            if ( $permissionSQL == "" )
                $publishedSQL = " Article.IsPublished = '1' AND ";
            else
                $publishedSQL = " AND Article.IsPublished = '1' AND ";
        }

        // fetch only non-published articles
        else                                  
        {
            if ( $permissionSQL == "" )
                $publishedSQL = " Article.IsPublished = '0' AND ";
            else
                $publishedSQL = " AND Article.IsPublished = '0' AND ";
        }

        $query = "SELECT COUNT( DISTINCT Article.ID ) as Count
                  FROM eZArticle_ArticleCategoryDefinition as Definition,
                       eZArticle_Article as Article,
                       eZArticle_ArticleCategoryLink as Link,
                       eZArticle_CategoryPermission as CategoryPermission,
                       eZArticle_ArticlePermission AS Permission
                  WHERE 
                        $permissionSQL
                        $publishedSQL
                        Link.CategoryID='$this->ID'
                        AND Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID
                        AND Definition.ArticleID=Article.ID
                        AND CategoryPermission.ObjectID=Definition.CategoryID
                        ";

        $db->array_query( $article_array, $query );

        return $article_array[0][$db->fieldName("Count")];
    }

    /*!
      Moves the article placement with the given ID up.
    */
    function moveUp( $id )
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $qry, "SELECT * FROM eZArticle_ArticleCategoryLink
                                  WHERE ArticleID='$id' AND CategoryID='$this->ID'" );
       
       if ( is_numeric( $qry[$db->fieldName("ID")] ) )
       {
           $linkID = $qry[$db->fieldName("ID")];
           
           $placement = $qry[$db->fieldName("Placement")];
           
           $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_ArticleCategoryLink
                                    WHERE Placement<'$placement' AND eZArticle_ArticleCategoryLink.CategoryID='$this->ID'
                                    ORDER BY Placement DESC" );

           $newPlacement = $qry[$db->fieldName("Placement")];
           $listid = $qry[$db->fieldName("ID")];

           if ( $newPlacement == $placement )
           {
               $placement += 1;
           }
               

           if ( is_numeric( $listid ) )
           {           
               $db->query( "UPDATE eZArticle_ArticleCategoryLink SET Placement='$newPlacement' WHERE ID='$linkID'" );
               $db->query( "UPDATE eZArticle_ArticleCategoryLink SET Placement='$placement' WHERE ID='$listid'" );
           }           
       }       
    }

    /*!
      Moves the article placement with the given ID down.
    */
    function moveDown( $id )
    {
       $db =& eZDB::globalDatabase();

       $db->query_single( $qry, "SELECT * FROM eZArticle_ArticleCategoryLink
                                  WHERE ArticleID='$id' AND CategoryID='$this->ID'" );

       if ( is_numeric( $qry[$db->fieldName("ID")] ) )
       {
           $linkID = $qry[$db->fieldName("ID")];
           
           $placement = $qry[$db->fieldName("Placement")];
           
           $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_ArticleCategoryLink
                                    WHERE Placement>'$placement' AND eZArticle_ArticleCategoryLink.CategoryID='$this->ID' ORDER BY Placement ASC" );

           $newPlacement = $qry[$db->fieldName("Placement")];
           $listid = $qry[$db->fieldName("ID")];

           if ( $newPlacement == $placement )
           {
               $newPlacement += 1;
           }           

           if ( is_numeric( $listid ) )
           {
               $db->query( "UPDATE eZArticle_ArticleCategoryLink SET Placement='$newPlacement' WHERE ID='$linkID'" );
               $db->query( "UPDATE eZArticle_ArticleCategoryLink SET Placement='$placement' WHERE ID='$listid'" );
           }
       }       
    }


    /*!
      Moves the article category with the given ID down.
     */
    function moveCategoryUp(  )
    {
        $db =& eZDB::globalDatabase();

        $query = "SELECT ID, Placement FROM eZArticle_Category
                 WHERE Placement<'$this->Placement' AND ParentID='$this->ParentID' ORDER BY Placement DESC";

        $db->query_single( $qry, $query );
        if ( is_numeric( $qry[$db->fieldName("ID")] ) )
        {
           $swapCatPlacement = $qry[$db->fieldName("Placement")];
           $swapCatID = $qry[$db->fieldName("ID")];

           if ( is_numeric( $swapCatPlacement ) )
           {           
               $db->query( "UPDATE eZArticle_Category SET Placement='$swapCatPlacement' WHERE ID='$this->ID'" );
               $db->query( "UPDATE eZArticle_Category SET Placement='$this->Placement' WHERE ID='$swapCatID'" );
           }
        }       
    }

     /*!
      Moves the article category with the given ID down.
     */
    function moveCategoryDown( )
    {
        $db =& eZDB::globalDatabase();
        $query = "SELECT ID, Placement FROM eZArticle_Category
                 WHERE Placement>'$this->Placement' AND ParentID='$this->ParentID' ORDER BY Placement ASC";

        $db->query_single( $qry, $query );
        if ( is_numeric( $qry[$db->fieldName("ID")] ) )
        {
           $swapCatPlacement = $qry[$db->fieldName("Placement")];
           $swapCatID = $qry[$db->fieldName("ID")];

           if ( is_numeric( $swapCatPlacement ) )
           {           
               $db->query( "UPDATE eZArticle_Category SET Placement='$swapCatPlacement' WHERE ID='$this->ID'" );
               $db->query( "UPDATE eZArticle_Category SET Placement='$this->Placement' WHERE ID='$swapCatID'" );
           }
        }

    }

    /*!
      Connects this category to the bulkmail category specified.
     */
    function setBulkMailCategory( $value )
    {
        if( get_class( $value ) == "ezbulkmailcategory" )
            $value = $value->id();

        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZArticle_BulkMailCategoryLink WHERE ArticleCategoryID='$this->ID'" );

        if( $value != false )
            $db->query( "INSERT INTO eZArticle_BulkMailCategoryLink SET ArticleCategoryID='$this->ID', BulkMailCategoryID='$value'" );
    }

    /*!
      Returns the bulkMailCategory this category is connected to.
     */
    function bulkMailCategory( $asObject = true )
    {
        $db =& eZDB::globalDatabase();
        $result_array = array();
        $result = false;
        $db->array_query( $result_array, "SELECT BulkMailCategoryID FROM eZArticle_BulkMailCategoryLink WHERE ArticleCategoryID='$this->ID'" );

        if( count( $result_array ) > 0 )
            $result = ( $asObject == true ) ? new eZBulkMailCategory( $result_array[0][$db->fieldName("BulkMailCategoryID")] ) :  $result_array[0][$db->fieldName("BulkMailCategoryID")];

        return $result;
    }
  
    var $ID;
    var $Name;
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
