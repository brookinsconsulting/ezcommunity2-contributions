<?
// 
// $Id: ezarticlecategory.php,v 1.68 2001/06/22 14:47:59 pkej Exp $
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
include_once( "ezuser/classes/ezuser.php" );
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

        $name = addslashes( $this->Name );
        $description = addslashes( $this->Description );
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZArticle_Category SET
		                         Name='$name',
                                 Description='$description',
                                 ExcludeFromSearch='$this->ExcludeFromSearch',
                                 SortMode='$this->SortMode',
                                 Placement='$this->Placement',  
                                 OwnerID='$this->OwnerID',
                                 SectionID='$this->SectionID',
                                 ImageID='$this->ImageID',
                                 ParentID='$this->ParentID'" );
			$this->ID = $this->Database->insertID();

            $this->Database->query( "UPDATE eZArticle_Category SET Placement=ID WHERE ID='$this->ID'" );
        }
        else
        {
            $this->Database->query( "UPDATE eZArticle_Category SET
		                         Name='$name',
                                 Description='$description',
                                 ExcludeFromSearch='$this->ExcludeFromSearch',
                                 SortMode='$this->SortMode',
                                 Placement='$this->Placement',  
                                 OwnerID='$this->OwnerID',
                                 SectionID='$this->SectionID',
                                 ImageID='$this->ImageID',
                                 ParentID='$this->ParentID' WHERE ID='$this->ID'" );
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
                $this->OwnerID = $category_array[0][ "OwnerID" ];
                $this->Placement = $category_array[0][ "Placement" ];
                $this->SectionID = $category_array[0][ "SectionID" ];
                $this->ImageID = $category_array[0][ "ImageID" ];
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
        \static
        Returns the one, and only if one exists, category with the name
        
        Returns an object of eZArticleCategory.
     */
    function &getByName( $name )
    {
        $db =& eZDB::globalDatabase();
        
        $topic =& new eZArticleCategory();
        
        $name = addslashes( $name );

        if( $name != "" )
        {
            $db->array_query( $author_array, "SELECT * FROM eZArticle_Category WHERE Name='$name'" );

            if( count( $author_array ) == 1 )
            {
                $topic =& new eZArticleCategory( $author_array[0][ "ID" ] );
            }
        }
        
        return $topic;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      If $showAll is set to true every category is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZArticleCategory objects.      
    */
    function getByParent( $parent, $showAll=false, $sortby=placement, $offset = 0, $max = -1 )
    {
        if ( get_class( $parent ) == "ezarticlecategory" )
        {
            $this->dbInit();

            $sortbySQL = "Name";
            switch( $sortby )
            {
                case "name" : $sortbySQL = "Name"; break;
                case "placement" : $sortbySQL = "Placement"; break;
            }

            $limit_str = "";
            if ( $max > -1 )
            {
                $limit_str = "LIMIT $offset, $max";
            }

            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            $show_str = "";
            if ( !$showAll )
                $show_str = "AND ExcludeFromSearch='false'";

            $this->Database->array_query( $category_array, "SELECT ID, Name FROM eZArticle_Category
                                          WHERE ParentID='$parentID' $show_str
                                          ORDER BY $sortbySQL $limit_str" );

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
      Returns the categories with the category given as parameter as parent.

      If $showAll is set to true every category is shown. By default the categories
      set as exclude from search is excluded from this query.

      The categories are returned as an array of eZArticleCategory objects.      
    */
    function countByParent( $parent, $showAll=false )
    {
        if ( get_class( $parent ) == "ezarticlecategory" )
        {
            $this->dbInit();

            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();

            $show_str = "";
            if ( !$showAll )
                $show_str = "AND ExcludeFromSearch='false'";

            $this->Database->query_single( $category_array, "SELECT count( ID ) AS Count
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
      \static
      Returns the Section ID. Returns false if the Category was not found.
    */
    function sectionIDStatic($categoryID )
    {
        $database =& eZDB::globalDatabase();
        $database->query_single( $res, "SELECT SectionID from eZArticle_Category WHERE ID='$categoryID'");
        
        $sectionID = $res[ "SectionID" ];

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
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if( $asHTML )
           return htmlspecialchars( $this->Name );

       return $this->Name;
    }
    
    /*!
      Returns the group description.
    */
    function description( $asHTML = true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if( $asHTML )
           return htmlspecialchars( $this->Description );

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
      Returns the creator of this category. Returns only the ID if given parameter is false.
     */
    function owner( $as_object = true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
        
        $database =& eZDB::globalDatabase();
        $database->query_single( $res, "SELECT OwnerID from eZArticle_Category WHERE ID='$categoryID'");
        $ownerID = $res[ "OwnerID" ];
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
      Sets the section of the category.
    */
    function setSectionID( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->SectionID = $value;
    }

    /*!
      Sets the image of the category.
    */
    function setImage( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $value ) == "ezimage" )
           $value = $value->id();
       
       $this->ImageID = $value;
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
       else
       {
           $this->ParentID = $value;
       }
    }


    /*!
      Sets the owner of this category.
    */
    function setOwner( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
      non-published articles will be returned. 
    */
    function &articles( $sortMode="time",
                        $fetchAll=true,
                        $fetchPublished=true,
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

       if ( $fetchAll  == true )             // fetch all articles
       {
           $publishedCode = "";
       }
       else if ( $fetchPublished  == true )  // fetch only published articles
       {
           $publishedCode = " AND Article.IsPublished = 'true' ";
       }
       else                                  // fetch only non-published articles
       {
           $publishedCode = " AND Article.IsPublished = 'false' ";
       }

       /* not needed
       if ( $getExcludedArticles == false )
       {
           $excludedCode = " AND Category.ExcludeFromSearch = 'false' ";
       }
       else
       {
           $excludedCode = "";           
       }
       */


       // this code works. do not EDIT !! :)
       
       $user =& eZUser::currentUser();

       $loggedInSQL = "";
       if ( $user )
       {
           $groups =& $user->groups( true );

           $groupSQL = "";
           
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
       }


       $query = "SELECT DISTINCT Article.ID as ArticleID
                  FROM eZArticle_Article AS Article,
                       eZArticle_ArticleCategoryLink as Link,
                       eZArticle_ArticlePermission AS Permission
                  WHERE (
                        ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                        )
                        $publishedCode
                        AND Link.CategoryID='$this->ID'
                        AND Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID
                 ORDER BY $OrderBy
                 LIMIT $offset,$limit";
       
       /* SQL before optimizing
       $query = "SELECT Article.ID as ArticleID
                 FROM eZArticle_Article AS Article
                 LEFT JOIN eZArticle_ArticleCategoryLink as Link ON Article.ID=Link.ArticleID
                 LEFT JOIN eZArticle_ArticlePermission AS Permission ON Article.ID=Permission.ObjectID,
                 eZArticle_Category AS Category
                 WHERE(
                      ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                      )
                 $publishedCode
                 AND Link.CategoryID='$this->ID'
                 AND Category.ID=Link.CategoryID
                 $excludedCode
                 GROUP BY Article.ID
                 ORDER BY $OrderBy
                 LIMIT $offset,$limit;";
       */
       
       
       $this->Database->array_query( $article_array, $query );
       
 
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
      non-published articles will be counted.       
    */
    function articleCount( $fetchAll=true, $fetchPublished=true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $return_array = array();
       $article_array = array();

       if ( $fetchAll  == true )             // fetch all articles
       {
           $publishedCode = "";
       }
       else if ( $fetchPublished  == true )  // fetch only published articles
       {
           $publishedCode = " AND Article.IsPublished = 'true' ";
       }
       else                                  // fetch only non-published articles
       {
           $publishedCode = " AND Article.IsPublished = 'false' ";
       }

       /* Not needed on this list
       if ( $getExcludedArticles == false )
       {
           $excludedCode = " AND Category.ExcludeFromSearch = 'false' ";
       }
       else
       {
           $excludedCode = "";           
       }
       */


       $user =& eZUser::currentUser();

       $loggedInSQL = "";
       if ( $user )
       {
           $groups =& $user->groups( true );

           $groupSQL = "";
           
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

       }
    
       $query = "SELECT count( DISTINCT Article.ID ) AS Count 
                  FROM eZArticle_Article AS Article,
                       eZArticle_ArticleCategoryLink as Link,
                       eZArticle_ArticlePermission AS Permission
                  WHERE (
                        ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                        )
                        $publishedCode
                        AND Link.CategoryID='$this->ID'
                        AND Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID";

       /* SQL before optimizing
       $query = "SELECT count( Article.ID ) as Count
                 FROM eZArticle_Article AS Article
                 LEFT JOIN eZArticle_ArticleCategoryLink as Link ON Article.ID=Link.ArticleID
                 LEFT JOIN eZArticle_ArticlePermission AS Permission ON Article.ID=Permission.ObjectID,
                 eZArticle_Category AS Category
                 WHERE(
                      ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                      )
                 $publishedCode
                 AND Link.CategoryID='$this->ID'
                 AND Category.ID=Link.CategoryID
                 $excludedCode;";
       */

       $this->Database->array_query( $article_array, $query );

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
      Moves the article category with the given ID down.
     */
    function moveCategoryUp(  )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $db =& eZDB::globalDatabase();

        $query = "SELECT ID, Placement FROM eZArticle_Category
                 WHERE Placement<'$this->Placement' AND ParentID='$this->ParentID' ORDER BY Placement DESC LIMIT 1";

        $db->query_single( $qry, $query );
        if ( is_numeric( $qry["ID"] ) )
        {
           $swapCatPlacement = $qry["Placement"];
           $swapCatID = $qry["ID"];

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
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $db =& eZDB::globalDatabase();
        $query = "SELECT ID, Placement FROM eZArticle_Category
                 WHERE Placement>'$this->Placement' AND ParentID='$this->ParentID' ORDER BY Placement ASC LIMIT 1";

        $db->query_single( $qry, $query );
        if ( is_numeric( $qry["ID"] ) )
        {
           $swapCatPlacement = $qry["Placement"];
           $swapCatID = $qry["ID"];

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
            $result = ( $asObject == true ) ? new eZBulkMailCategory( $result_array[0][ "BulkMailCategoryID" ] ) :  $result_array[0][ "BulkMailCategoryID" ];

        return $result;
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
    var $OwnerID;
    var $Placement;
    var $SectionID;
    var $ImageID;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
