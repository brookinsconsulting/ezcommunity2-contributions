<?
// 
// $Id: ezarticlecategory.php,v 1.35 2001/02/23 10:25:27 fh Exp $
//
// Definition of eZArticleCategory class
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 14:05:56 bf>
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

//!! eZArticle
//! eZArticleCategory handles article categories.
/*!
  
*/

/*!TODO
  Implement activeArticles();
*/

include_once( "classes/ezdb.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezarticle/classes/ezarticle.php" );

class eZArticleCategory
{
    /*!
      Constructs a new eZArticleCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZArticleCategory( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;
        $this->ExcludeFromSearch = "false";
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
      Stores a eZArticleCategory object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZArticle_Category SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 ExcludeFromSearch='$this->ExcludeFromSearch',
                                 SortMode='$this->SortMode',
                                 ParentID='$this->ParentID',
                                 OwnerGroupID='$this->OwnerGroupID',
                                 ReadPermission='$this->ReadPermission'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZArticle_Category SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 ExcludeFromSearch='$this->ExcludeFromSearch',
                                 SortMode='$this->SortMode',
                                 ParentID='$this->ParentID',
                                 OwnerGroupID='$this->OwnerGroupID',
                                 ReadPermission='$this->ReadPermission' WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZArticleGroup object from the database.

    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $category = new eZArticleCategory( $catID );

        $categoryList = $category->getByParent( $category );

        foreach( $categoryList as $categoryItem )
        {
            $this->delete( $categoryItem->id() );
        }

        $categoryID = $category->id();
        
        foreach( $this->articles() as $article )
        {
            $categoryDefinition = $article->categoryDefinition();

            if ( $categoryDefinition->id() == $category->id() )
            {
                $this->Database->query( "DELETE FROM eZArticle_ArticleCategoryDefinition WHERE CategoryID='$categoryID'" );
                $this->Database->query( "DELETE FROM eZArticle_ArticleCategoryLink WHERE CategoryID='$categoryID'" );
               
                $article->delete();
            }
            else
            {
                $this->Database->query( "DELETE FROM eZArticle_ArticleCategoryLink WHERE CategoryID='$categoryID'" );
            }
        }
        $this->Database->query( "DELETE FROM eZArticle_CategoryReaderLink WHERE CategoryID='$categoryID'" );
        
        $this->Database->query( "DELETE FROM eZArticle_Category WHERE ID='$categoryID'" );
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $category_array, "SELECT * FROM eZArticle_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][ "ID" ];
                $this->Name = $category_array[0][ "Name" ];
                $this->Description = $category_array[0][ "Description" ];
                $this->ParentID = $category_array[0][ "ParentID" ];
                $this->ExcludeFromSearch = $category_array[0][ "ExcludeFromSearch" ];
                $this->SortMode = $category_array[0][ "SortMode" ];
                $this->OwnerGroupID = $category_array[0][ "OwnerGroupID" ];
                $this->ReadPermission = $category_array[0][ "ReadPermission" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZArticleCategory objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $category_array = array();
        
        $this->Database->array_query( $category_array, "SELECT ID FROM eZArticle_Category ORDER BY Name" );
        
        for ( $i=0; $i < count($category_array); $i++ )
        {
            $return_array[$i] = new eZArticleCategory( $category_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      If $showAll is set to true every category is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZArticleCategory objects.      
    */
    function getByParent( $parent, $showAll=false, $sortby=name )
    {
        if ( get_class( $parent ) == "ezarticlecategory" )
        {
            $this->dbInit();
        
            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            if ( $showAll == true )
            {
                $this->Database->array_query( $category_array, "SELECT ID, Name FROM eZArticle_Category
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );
            }
            else
            {
                $this->Database->array_query( $category_array, "SELECT ID, Name FROM eZArticle_Category
                                          WHERE ParentID='$parentID' AND ExcludeFromSearch='false'
                                          ORDER BY Name" );
            }

            for ( $i=0; $i < count($category_array); $i++ )
            {
                $return_array[$i] = new eZArticleCategory( $category_array[$i]["ID"], 0 );
            }

            return $return_array;
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
      Returns the object ID to the category. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    
    /*!
      Returns the name of the category.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Name;
    }
    
    /*!
      Returns the group description.
    */
    function description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Description;
    }

    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent( $as_object = true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
      Returns the owner group of this module as an eZOwnerGroup object.
      If the object doesn't have an owner it returns 0.
    */
    function ownerGroup()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $group = new eZUserGroup( $this->OwnerGroupID );
        return $group;
    }

    /*!
      Sets the read permission.

      Note: If you set this to 0 or 2 it automaticly clears the permission link table.
      
      0 means that only the user has permission to read the article
      1 means that only users in selected groups can read the article
      2 means that evryone can read the article
     */
    function readPermission()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ReadPermission;
    }


    
    /*!
      Removes every group that can read this article.
    */
    function removeReadGroups()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->query( "DELETE FROM eZArticle_CategoryReaderLink WHERE CategoryID='$this->ID'" );       
    }

    
    /*!
      Adds a group that can read this article.

      Note: calling this function will set the ReadPermission variable to 1
     */
    function addReadGroup( $newGroup )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( get_class( $newGroup ) == "ezusergroup" )
        {
            $this->dbInit();
            $groupID = $newGroup->id();
            $this->Database->query( "INSERT INTO  eZArticle_CategoryReaderLink SET
                                     CategoryID='$this->ID',
                                     GroupID='$groupID',
                                     Created=now()" );       
        }
        
    }

    /*!
      Returns all the groups that have readpermission for this article as an array of eZUserGroup objects if the parameter is false.
      If the parameter is true, it returns the groups as an array of ID's.
     */
    function readGroups( $IDOnly=false )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = array();
        $this->Database->array_query( $res, "SELECT GroupID FROM eZArticle_CategoryReaderLink
                                       WHERE CategoryID='$this->ID'" );
        
        if( count( $res ) > 0 )
        {
            $i = 0;
            foreach( $res as $groupItem )
            {
                if( $IDOnly == true )
                    $ret[$i] = $groupItem[0]["GroupID"];
                else
                    $ret[$i] = new eZUserGroup( $groupID[0]["GroupID"] );
                $i++;
            }
        }

        return $ret;
    }
    
    /*!
      \static
      Returns true if the user has permission to view this category.
     */
    function hasReadPermission( $user, $categoryID )
    {
       $database =& eZDB::globalDatabase();
       $database->array_query( $res, "SELECT ReadPermission FROM eZArticle_Category WHERE ID='$categoryID'" );
//       print("<br>Inside hasReadPermission<br>");
       $readPermission = $res[0][ "ReadPermission" ];
//       print( "This page has readpermission: $readPermission <br>" );

       if( $readPermission == 0 ) //none
           return false;
       else if( $readPermission == 2 )// all
           return true;
       else if( $readPermission == 1 && get_class( $user ) == "ezuser" )//some
       {
           $userGroups = $user->groups( true );
           $database->array_query( $res, "SELECT GroupID FROM eZArticle_CategoryReaderLink
                                   WHERE CategoryID='$categoryID'");

           $i = 0;
           $readGrpID = array();
           foreach( $res as $groupItem )
           {
               $readGrpID[$i] = $groupItem["GroupID"];
               $i++;
           }
           
           $commonGroups = array_intersect( $readGrpID, $userGroups );
           //         print_r( $readGrpID ); print( "<br>");
//           print_r( $userGroups );print( "<br>");
//           print_r( $commonGroups );print( "<br>");
//           $count = count( $commonGroups );
//           print( "$count");
           if( count( $commonGroups ) > 0  )
               return true;
       }
       return false; 
    }

    /*!
      \static
      Returns true if the user has write permission for this category
     */
    function hasWritePermission( $user, $categoryID )
    {
        if( get_class( $user ) != "ezuser" )
            return false;

        $database =& eZDB::globalDatabase();
        // check if group
        $database->query_single( $res, "SELECT OwnerGroupID from eZArticle_Category WHERE ID='$categoryID'");
        $ownerGroupID = $res[ "OwnerGroupID" ];
        $userGroups = $user->groups( true );
        if( in_array( $ownerGroupID, $userGroups ) || $ownerGroupID == 0 )
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
       if ( $this->State_ == "Dirty" )
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( $this->ExcludeFromSearch  == "true" )
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the description of the category.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    /*!
      Sets the parent category.
    */
    function setParent( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $value ) == "ezarticlecategory" )
       {
           $this->ParentID = $value->id();
       }
    }

        /*!
      Sets the read permission.

      Note: If you set this to 0 or 2 it automaticly clears the permission link table.
      
      0 means that only the user has permission to read the article
      1 means that only users in selected groups can read the article
      2 means that evryone can read the article
     */
    function setReadPermission( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( $value >=0 && $value <=2)
        {
            if( $value != 1 )
                $this->removeReadGroups();

            $this->ReadPermission = $value;
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->SortMode = $value;
    }
    
    /*!
     Sets the exclude from search bit.
     The argumen can be true or false.
    */
    function setExcludeFromSearch( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->ExcludeFromSearch = "true";
       }
       else
       {
           $this->ExcludeFromSearch = "false";           
       }
    }

    /*!
      Sets the owner group of this category.
      Parameter $newOwner must be an eZUserGroup object.
      if $recursive is true the function will also set the owner group for all subcategories. These will be stored automaticly.
     */
    function setOwnerGroup( $newOwner, $recursive = false )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if( get_class( $newOwner ) == "ezusergroup" )
        {
            $this->OwnerGroupID = $newOwner->id();
            if( $recursive == true )
            {
                $categories = $this->getByParent( $this );
                foreach( $categories as $categoryItem )
                {
                    $categoryItem->setOwnerGroup( $newOwner, true );
                    $categoryItem->store();
                }
            }
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
        if ( get_class( $value ) == "ezarticle" )
            $articleID = $value->id();
        else if ( is_numeric( $value ) )
            $articleID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->array_query( $qry, "SELECT ID, Placement FROM eZArticle_ArticleCategoryLink
                                 WHERE CategoryID='$categoryid'
                                 ORDER BY Placement DESC LIMIT 1", 0, 1 );

        $place = count( $qry ) == 1 ? $qry[0]["Placement"] + 1 : 1;
        $query = "INSERT INTO eZArticle_ArticleCategoryLink
                  SET CategoryID='$categoryid',
                      ArticleID='$articleID',
                      Placement='$place'";

        $db->query( $query );
    }

    /*!
      Returns every article in a category as a array of eZArticle objects.

      If $fetchAll is set to true, both published and unpublished articles will be returned.
      If it is set to false, then $fetchPublished will determine: If $fetchPublished is
      set to true then only published articles will be returned. If it is false, then only
      non-published articles will be returned. If the $getExcludedArticles is set to true the
      articles which are excluded from search is also returned.
    */
    function &articles( $sortMode="time",
                        $fetchAll=true,
                        $fetchPublished=true,
                        $getExcludedArticles=false,
                        $offset=0,
                        $limit=50 )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       switch( $sortMode )
       {
           case "time" :
           {
               $OrderBy = "eZArticle_Article.Published DESC";
           }
           break;

           case "alpha" :
           {
               $OrderBy = "eZArticle_Article.Name ASC";
           }
           break;

           case "alphadesc" :
           {
               $OrderBy = "eZArticle_Article.Name DESC";
           }
           break;

           case "absolute_placement" :
           {
               $OrderBy = "eZArticle_ArticleCategoryLink.Placement ASC";
           }
           break;
           
           default :
           {
               $OrderBy = "eZArticle_Article.Published DESC";
           }
       }
       $return_array = array();
       $article_array = array();

       if ( $fetchAll  == true )             // fetch all articles
       {
           $publishedCode = "";
       }
       else if ( $fetchPublished  == true )  // fetch only published articles
       {
           $publishedCode = " AND eZArticle_Article.IsPublished = 'true' ";
       }
       else                                  // fetch only non-published articles
       {
           $publishedCode = " AND eZArticle_Article.IsPublished = 'false' ";
       }

       if ( $getExcludedArticles == false )
       {
           $excludedCode = " AND eZArticle_Category.ExcludeFromSearch = 'false' ";
       }
       else
       {
           $excludedCode = "";           
       }

       $this->Database->array_query( $article_array, "
                SELECT eZArticle_Article.ID AS ArticleID, eZArticle_Article.Name, eZArticle_Category.ID, eZArticle_Category.Name
                FROM eZArticle_Article, eZArticle_Category, eZArticle_ArticleCategoryLink
                WHERE 
                eZArticle_ArticleCategoryLink.ArticleID = eZArticle_Article.ID
                $publishedCode
                AND
                eZArticle_Category.ID = eZArticle_ArticleCategoryLink.CategoryID
                AND
                eZArticle_Category.ID='$this->ID'
                $excludedCode  
                GROUP BY eZArticle_Article.ID ORDER BY $OrderBy LIMIT $offset,$limit" );
 
       for ( $i=0; $i < count($article_array); $i++ )
       {
           $return_array[$i] = new eZArticle( $article_array[$i]["ArticleID"], false );
       }

       return $return_array;
    }

    /*!
      Returns the total number of articles in the current category.

      If $fetchAll is set to true, both published and unpublished articles will be counted.
      If it is set to false, then $fetchPublished will determine: If $fetchPublished is
      set to true then only published articles will be counted. If it is false, then only
      non-published articles will be counted. If the $getExcludedArticles is set to true the
      articles which are excluded from search is also counted.
    */
    function articleCount( $fetchAll=true, $fetchPublished=true, $getExcludedArticles=false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $return_array = array();
       $article_array = array();

       if ( $getExcludedArticles == false )
       {
           $excludedCode = " AND eZArticle_Category.ExcludeFromSearch = 'false' ";
       }
       else
       {
           $excludedCode = "";
       }

       if ( $fetchAll  == true )             // fetch all articles
       {
           $publishedCode = "";
       }
       else if ( $fetchPublished  == true )  // fetch only published articles
       {
           $publishedCode = " AND eZArticle_Article.IsPublished = 'true' ";
       }
       else                                  // fetch only non-published articles
       {
           $publishedCode = " AND eZArticle_Article.IsPublished = 'false' ";
       }
       
       $this->Database->array_query( $article_array, "
                SELECT count(*) AS Count
                FROM eZArticle_Article, eZArticle_Category, eZArticle_ArticleCategoryLink
                WHERE 
                eZArticle_ArticleCategoryLink.ArticleID = eZArticle_Article.ID
                $publishedCode
                AND
                eZArticle_Category.ID = eZArticle_ArticleCategoryLink.CategoryID
                AND
                eZArticle_Category.ID='$this->ID'
                $excludedCode " );

       return $article_array[0]["Count"];
    }

    /*!
      Moves the article placement with the given ID up.
    */
    function moveUp( $id )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $db =& eZDB::globalDatabase();

       $db->query_single( $qry, "SELECT * FROM eZArticle_ArticleCategoryLink
                                  WHERE ArticleID='$id' AND CategoryID='$this->ID'" );
       
       if ( is_numeric( $qry["ID"] ) )
       {
           $linkID = $qry["ID"];
           
           $placement = $qry["Placement"];
           
           $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_ArticleCategoryLink
                                    WHERE Placement<'$placement' AND eZArticle_ArticleCategoryLink.CategoryID='$this->ID'
                                    ORDER BY Placement DESC LIMIT 1" );

           $newPlacement = $qry["Placement"];
           $listid = $qry["ID"];

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $db =& eZDB::globalDatabase();

       $db->query_single( $qry, "SELECT * FROM eZArticle_ArticleCategoryLink
                                  WHERE ArticleID='$id' AND CategoryID='$this->ID'" );

       if ( is_numeric( $qry["ID"] ) )
       {
           $linkID = $qry["ID"];
           
           $placement = $qry["Placement"];
           
           $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_ArticleCategoryLink
                                    WHERE Placement>'$placement' AND eZArticle_ArticleCategoryLink.CategoryID='$this->ID' ORDER BY Placement ASC LIMIT 1" );

           $newPlacement = $qry["Placement"];
           $listid = $qry["ID"];

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
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database =& eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }
    
    var $ID;
    var $Name;
    var $ParentID;
    var $Description;
    var $ExcludeFromSearch;
    var $SortMode;
    var $OwnerGroupID;
    var $ReadPermission;
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>

