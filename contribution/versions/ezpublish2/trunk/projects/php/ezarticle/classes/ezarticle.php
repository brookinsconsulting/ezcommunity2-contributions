<?php
// 
// $Id: ezarticle.php,v 1.18 2000/10/28 12:29:01 bf-cvs Exp $
//
// Definition of eZArticle class
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 13:50:24 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZArticle
//! eZArticle handles articles.
/*!

  Example code:
  \code
  $category = new eZArticleCategory();
  $category->setName( "Programming" );
  $category->setDescription( "Lots of programming articles" );
  
  $category->store();
    
  $article = new eZArticle( );
  $article->setName( "C++" );
  $article->setContents( "An article about the fine art of C++ .... .... ... ... .... ... " );
  $article->setAuthorText( "Bård Farstad" );
  $article->setLinkText( "Read the article" );
  
  $article->store();
    
  $category->addArticle( $article );
  \endcode

  \sa eZArticleCategory

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );

class eZArticle
{
    /*!
      Constructs a new eZArticle object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZArticle( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        // default value
        $this->IsPublished = "false";
        
        if ( $id != "" )
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
      Stores a product to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZArticle_Article SET
		                         Name='$this->Name',
                                 Contents='$this->Contents',
                                 AuthorText='$this->AuthorText',
                                 AuthorID='$this->AuthorID',
                                 LinkText='$this->LinkText',
                                 PageCount='$this->PageCount',
                                 IsPublished='$this->IsPublished',
                                 Modified=now(),
                                 Published=now(),
                                 Created=now()
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->array_query( $res, "SELECT ID FROM eZArticle_Article WHERE IsPublished='false' AND ID='$this->ID'" );
            
            if ( ( count( $res ) > 0 ) && ( $this->IsPublished == "true" ) )
            {                
                $this->Database->query( "UPDATE eZArticle_Article SET
		                         Name='$this->Name',
                                 Contents='$this->Contents',
                                 AuthorText='$this->AuthorText',
                                 LinkText='$this->LinkText',
                                 PageCount='$this->PageCount',
                                 AuthorID='$this->AuthorID',
                                 IsPublished='$this->IsPublished',
                                 Published=now(),
                                 Modified=now()
                                 WHERE ID='$this->ID'
                                 " );
            }
            else
            {
                $this->Database->query( "UPDATE eZArticle_Article SET
		                         Name='$this->Name',
                                 Contents='$this->Contents',
                                 AuthorText='$this->AuthorText',
                                 LinkText='$this->LinkText',
                                 PageCount='$this->PageCount',
                                 AuthorID='$this->AuthorID',
                                 IsPublished='$this->IsPublished',
                                 Modified=now()
                                 WHERE ID='$this->ID'
                                 " );
            }

            $this->State_ = "Coherent";
        }
        
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $id != "" )
        {
            $this->Database->array_query( $article_array, "SELECT * FROM eZArticle_Article WHERE ID='$id'" );
            if ( count( $article_array ) > 1 )
            {
                die( "Error: Article's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $article_array ) == 1 )
            {
                $this->ID =& $article_array[0][ "ID" ];
                $this->Name =& $article_array[0][ "Name" ];
                $this->Contents =& $article_array[0][ "Contents" ];
                $this->AuthorText =& $article_array[0][ "AuthorText" ];
                $this->AuthorID =& $article_array[0][ "AuthorID" ];
                $this->LinkText =& $article_array[0][ "LinkText" ];
                $this->Modified =& $article_array[0][ "Modified" ];
                $this->Created =& $article_array[0][ "Created" ];
                $this->Published =& $article_array[0][ "Published" ];
                $this->PageCount =& $article_array[0][ "PageCount" ];
                $this->IsPublished =& $article_array[0][ "IsPublished" ];

                $this->State_ = "Coherent";
                $ret = true;
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }

    /*!
      Deletes a eZArticle object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZArticle_ArticleCategoryLink WHERE ArticleID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZArticle_ArticleImageLink WHERE ArticleID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZArticle_ArticleImageDefinition WHERE ArticleID='$this->ID'" );
            
            $this->Database->query( "DELETE FROM eZArticle_Article WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the article name / title.
    */
    function &name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the article contents.

      The contents is internally stored as XML.
    */
    function &contents()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Contents;
    }

    /*!
      Returns the author text contents.
    */
    function &authorText()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->AuthorText );
    }

    /*!
      Returns the link text.
    */
    function &linkText()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->LinkText );
    }

    /*!
      Returns the author as a eZUser object.
    */
    function &author()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $author = new eZUser( $this->AuthorID );
       return $author;
    }

    /*!
      Returns the number of pages in the article.
    */
    function &pageCount()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->PageCount;
    }

    /*!
      Returns the creation time of the article.

      The time is returned as a eZDateTime object.
    */
    function &created()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->Created );
       
       return $dateTime;
    }

    /*!
      Returns the last time the article was published.

      The time is returned as a eZDateTime object.
    */
    function &published()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->Published );
       
       return $dateTime;
    }
    
    /*!
      Returns true if the article is published false if not.
    */
    function isPublished()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( $this->IsPublished == "true" )
       {
           $ret = true;
       }
       return $ret;
    }
      
    
    /*!
      Sets the article name.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Name = $value;
    }

    /*!
      Sets the contents name.
    */
    function setContents( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Contents = $value;
    }

    /*!
      Sets the author text.
    */
    function setAuthorText( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->AuthorText = $value;
    }

    /*!
      Sets the link text.
    */
    function setLinkText( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->LinkText = $value;
    }

    /*!
      Sets the author of the article.
    */
    function setAuthor( $user )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $user ) == "ezuser" )
       {
           $this->AuthorID = $user->id();
       }
    }

    /*!
      Sets the number of pages in the article.
    */
    function setPageCount( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->PageCount = $value;
    }

    /*!
     Sets the article to published or not. 
    */
    function setIsPublished( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->IsPublished = "true";
       }
       else
       {
           $this->IsPublished = "false";           
       }
    }
    

    /*!
      Returns the categrories an article is assigned to.

      The categories are returned as an array of eZArticleCategory objects.
    */
    function categories()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $ret = array();
       $this->Database->array_query( $category_array, "SELECT * FROM eZArticle_ArticleCategoryLink WHERE ArticleID='$this->ID'" );

       foreach ( $category_array as $category )
       {
           $ret[] = new eZArticleCategory( $category["CategoryID"] );
       }

       return $ret;
    }
    
    /*!
      Removes every category assignments from the current article.
    */
    function removeFromCategories()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->query( "DELETE FROM eZArticle_ArticleCategoryLink WHERE ArticleID='$this->ID'" );       
        
    }

    /*!
      Adds an image to the article.
    */
    function addImage( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $value ) == "ezimage" )
        {
            $this->dbInit();

            $imageID = $value->id();
            
            $this->Database->query( "INSERT INTO eZArticle_ArticleImageLink SET ArticleID='$this->ID', ImageID='$imageID'" );
        }
    }

    /*!
      Deletes an image from the article.

      NOTE: the image does not get deleted from the image catalogue.
    */
    function deleteImage( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $value ) == "ezimage" )
        {
            $this->dbInit();

            $imageID = $value->id();
            
            $this->Database->query( "DELETE FROM eZArticle_ArticleImageDefinition WHERE ArticleID='$this->ID' AND ThumbnailImageID='$imageID'" );

            $this->Database->query( "DELETE FROM eZArticle_ArticleImageLink WHERE ArticleID='$this->ID' AND ImageID='$imageID'" );
        }
    }
    
    /*!
      Returns every image to a article as a array of eZImage objects.
    */
    function images()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $return_array = array();
       $image_array = array();
       
       $this->Database->array_query( $image_array, "SELECT ImageID FROM eZArticle_ArticleImageLink WHERE ArticleID='$this->ID' ORDER BY Created" );
       
       for ( $i=0; $i<count($image_array); $i++ )
       {
           $return_array[$i] = new eZImage( $image_array[$i]["ImageID"], false );
       }
       
       return $return_array;
    }

    /*!
      Sets the thumbnail image for the article.

      The argument must be a eZImage object.
    */
    function setThumbnailImage( $image )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $image ) == "ezimage" )
        {
            $this->dbInit();

            $imageID = $image->id();

            $this->Database->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZArticle_ArticleImageDefinition
                                     WHERE
                                     ArticleID='$this->ID'
                                   " );

            if ( $res_array[0]["Number"] == "1" )
            {            
                $this->Database->query( "UPDATE eZArticle_ArticleImageDefinition
                                     SET
                                     ThumbnailImageID='$imageID'
                                     WHERE
                                     ArticleID='$this->ID'" );
            }
            else
            {
                $this->Database->query( "INSERT INTO eZArticle_ArticleImageDefinition
                                     SET
                                     ArticleID='$this->ID',
                                     ThumbnailImageID='$imageID'" );
            }
        }
    }

    /*!
      Returns the thumbnail image of the article as a eZImage object.
    */
    function thumbnailImage( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       $this->dbInit();
       
       $this->Database->array_query( $res_array, "SELECT * FROM eZArticle_ArticleImageDefinition
                                     WHERE
                                     ArticleID='$this->ID'
                                   " );
       
       if ( count( $res_array ) == 1 )
       {
           if ( $res_array[0]["ThumbnailImageID"] != "NULL" )
           {
               $ret = new eZImage( $res_array[0]["ThumbnailImageID"], false );
           }               
       }
       
       return $ret;
       
    }
    
    /*!
      Returns true if the product is assigned to the category given
      as argument. False if not.
     */
    function existsInCategory( $category )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( get_class( $category ) == "ezarticlecategory" )
       {
           $this->dbInit();
           $catID = $category->id();

           $this->Database->array_query( $ret_array, "SELECT ID FROM eZArticle_ArticleCategoryLink
                                    WHERE ArticleID='$this->ID' AND CategoryID='$catID'" );

           if ( count( $ret_array ) == 1 )
           {
               $ret = true;
           }           
       }
       return $ret;
    }

    /*!
      Returns every article in every category sorted by time.
    */
    function articles( $sortMode=time, $fetchNonPublished=true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $OrderBy = "eZArticle_Article.Published DESC";
       switch( $sortMode )
       {
           case "alpha" :
           {
               $OrderBy = "eZArticle_Article.Name DESC";
           }
           break;
       }

       
       $return_array = array();
       $article_array = array();

       if ( $fetchNonPublished == true )
       {
          $this->Database->array_query( $article_array, "
                    SELECT eZArticle_Article.ID AS ArticleID, eZArticle_Article.Name, eZArticle_Category.ID, eZArticle_Category.Name
                    FROM eZArticle_Article, eZArticle_Category, eZArticle_ArticleCategoryLink 
                    WHERE 
                    eZArticle_ArticleCategoryLink.ArticleID = eZArticle_Article.ID
                    AND
                    eZArticle_Category.ID = eZArticle_ArticleCategoryLink.CategoryID
                    AND
                    eZArticle_Category.ExcludeFromSearch = 'false'
                    GROUP BY eZArticle_Article.ID ORDER BY $OrderBy" );
           
       }
       else
       {
           $this->Database->array_query( $article_array, "
                    SELECT eZArticle_Article.ID AS ArticleID, eZArticle_Article.Name, eZArticle_Category.ID, eZArticle_Category.Name
                    FROM eZArticle_Article, eZArticle_Category, eZArticle_ArticleCategoryLink 
                    WHERE 
                    eZArticle_ArticleCategoryLink.ArticleID = eZArticle_Article.ID
                    AND
                    eZArticle_Article.IsPublished = 'true'
                    AND
                    eZArticle_Category.ID = eZArticle_ArticleCategoryLink.CategoryID
                    AND
                    eZArticle_Category.ExcludeFromSearch = 'false'
                    GROUP BY eZArticle_Article.ID ORDER BY $OrderBy" );
       }
 
       for ( $i=0; $i<count($article_array); $i++ )
       {
           $return_array[$i] = new eZArticle( $article_array[$i]["ArticleID"], false );
       }
       
       return $return_array;
    }
    
    /*!
      \private
      
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
    var $AuthorID;
    var $Name;
    var $Contents;
    var $AuthorText;
    var $LinkText;
    var $Modified;
    var $Created;
    var $Published;

    // telll eZ publish to show the article to the public
    var $IsPublished;

    // variable for storing the number of pages in the article.
    var $PageCount;
    
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}


?>
