<?
// 
// $Id: ezarticlecategory.php,v 1.5 2000/10/25 13:36:59 bf-cvs Exp $
//
// Definition of eZArticleCategory class
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 14:05:56 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZArticle
//! eZArticleCategory handles article categories.
/*!
  
*/

/*!TODO
  Implement activeArticles();
*/

include_once( "classes/ezdb.php" );


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
                                 ParentID='$this->ParentID'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZArticle_Category SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 ParentID='$this->ParentID' WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZArticleGroup object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZArticle_ArticleCategoryLink WHERE CategoryID='$this->ID'" );
            
            $this->Database->query( "DELETE FROM eZArticle_Category WHERE ID='$this->ID'" );            
        }
        
        return true;
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
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZArticleCategory( $category_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      The categories are returned as an array of eZArticleCategory objects.
    */
    function getByParent( $parent, $sortby=name )
    {
        if ( get_class( $parent ) == "ezarticlecategory" )
        {
            $this->dbInit();
        
            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();
                 
            $this->Database->array_query( $category_array, "SELECT ID, Name FROM eZArticle_Category WHERE ParentID='$parentID' ORDER BY Name" );
        
            for ( $i=0; $i<count($category_array); $i++ )
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
    function parent()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $this->ParentID != 0 )
       {
           return new eZArticleCategory( $this->ParentID );
       }
       else
       {
           return 0;           
       }
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
      Adds a article to the category.
    */
    function addArticle( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $value ) == "ezarticle" )
       {
            $this->dbInit();

            $articleID = $value->id();
            
            $query = "INSERT INTO
                           eZArticle_ArticleCategoryLink
                      SET
                           CategoryID='$this->ID',
                           ArticleID='$articleID'";
            
            $this->Database->query( $query );
       }       
    }

    /*!
      Returns every article in a category as a array of eZArticle objects.
    */
    function articles( $sortMode=time )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $OrderBy = "eZArticle_Article.Created DESC";
       switch( $sortMode )
       {
           case "alpha" :
           {
               $OrderBy = "eZArticle_Article.Name ASC";
           }
           break;
       }
       
       
       $return_array = array();
       $article_array = array();

       $this->Database->array_query( $article_array, "SELECT eZArticle_Article.ID AS ArticleID, eZArticle_ArticleCategoryLink.ArticleID 
                                                      FROM eZArticle_Article, eZArticle_ArticleCategoryLink
                                                      WHERE CategoryID='$this->ID' AND eZArticle_Article.ID = eZArticle_ArticleCategoryLink.ArticleID
                                                      ORDER BY $OrderBy" );
 
       for ( $i=0; $i<count($article_array); $i++ )
       {
           $return_array[$i] = new eZArticle( $article_array[$i]["ArticleID"], false );
       }
       
       return $return_array;
    }
    
    /*!
      Returns every active article in a category as a array of eZArticle objects.
    */
    function activeArticles()
    {
//         if ( $this->State_ == "Dirty" )
//              $this->get( $this->ID );

//         $this->dbInit();
       
//         $return_array = array();
//         $product_array = array();
       
//         $this->Database->array_query( $product_array, "SELECT eZTrade_Product.ID AS ProductID from eZTrade_ProductCategoryLink,
//                                                               eZTrade_Product WHERE eZTrade_ProductCategoryLink.CategoryID='$this->ID' AND
//                                                               eZTrade_Product.ShowProduct='true'
//                                                               AND eZTrade_ProductCategoryLink.ProductID=eZTrade_Product.ID
//                                                               GROUP BY eZTrade_Product.ID" );
//         for ( $i=0; $i<count($product_array); $i++ )
//         {
//             $return_array[$i] = new eZProduct( $product_array[$i]["ProductID"], false );
//         }
       
//         return $return_array;
    }
    


    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
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
    var $Name;
    var $ParentID;
    var $Description;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>

