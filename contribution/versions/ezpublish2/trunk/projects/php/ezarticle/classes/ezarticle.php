<?php
// 
// $Id: ezarticle.php,v 1.37 2001/02/21 17:15:33 fh Exp $
//
// Definition of eZArticle class
//
// B�rd Farstad <bf@ez.no>
// Created on: <18-Oct-2000 13:50:24 bf>
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
  $article->setAuthorText( "B�rd Farstad" );
  $article->setLinkText( "Read the article" );
  
  $article->store();
    
  $category->addArticle( $article );
  \endcode

  \sa eZArticleCategory

*/

/*!TODO
  Add delayed fetching of the article contents.

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezfilemanager/classes/ezvirtualfile.php" );

include_once( "ezforum/classes/ezforum.php" );


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
                                 Keywords='$this->Keywords',
                                 Modified=now(),
                                 Published=now(),
                                 Created=now(),
                                 OwnerGroupID='$this->OwnerGroupID',
                                 ReadPermission='$this->ReadPermission'
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
                                 Keywords='$this->Keywords',
                                 Published=now(),
                                 Modified=now(),
                                 OwnerGroupID='$this->OwnerGroupID',
                                 ReadPermission='$this->ReadPermission'
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
                                 Keywords='$this->Keywords',
                                 Modified=now(),
                                 OwnerGroupID='$this->OwnerGroupID',
                                 ReadPermission='$this->ReadPermission'
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
                $this->Keywords =& $article_array[0][ "Keywords" ];
                $this->OwnerGroupID =& $article_array[0][ "OwnerGroupID" ];
                $this->ReadPermission =& $article_array[0][ "ReadPermission" ];

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
            $this->Database->query( "DELETE FROM eZArticle_ArticleCategoryDefinition WHERE ArticleID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZArticle_ArticleImageLink WHERE ArticleID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZArticle_ArticleImageDefinition WHERE ArticleID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZArticle_ArticleReaderLink WHERE ArticleID='$this->ID'" );

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
      Returns the keywords of an article.
    */
    function keywords( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Keywords;
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
      Sets the keywords to an article. Theese words are used in the search.
    */
    function setKeywords( $keywords )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Keywords = $keywords;
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
      Sets the owner group of this article.
      Parameter $newOwner must be an eZUserGroup object.
     */
    function setOwnerGroup( $newOwner )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if( get_class( $newOwner ) == "ezusergroup" )
        {
            $this->$OwnerGroupID = $newOwner->id();
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

        if( is_digit( $value ) && $value >=0 && $value <=2)
        {
            if( $value != 1 )
                removeReadGroups();

            $this->ReadPermission = $value;
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
      Returns the read permission for this article.
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
      Removes every group that can read this article.
    */
    function removeReadGroups()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->query( "DELETE FROM eZArticle_ArticleReaderLink WHERE ArticleID='$this->ID'" );       
    }

    
    /*!
      Adds a group that can read this article.

      Note: calling this function will set the ReadPermission variable to 1 if not allready set.
     */
    function addReadGroup( $newGroup )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( get_class( $newGroup ) == "ezusergroup" )
        {
            $this->dbInit();
            $groupID = $newGroup->id();
            $this->Database->query( "INSERT INTO  eZArticle_ArticleReaderLink SET
                                     ArticleID='$this->ID',
                                     GroupID='$groupID',
                                     Created=now()" );       
        }
        
    }

    /*!
      Returns all the groups that have readpermission for this article as an array of eZUserGroup objects.
     */
    function readGroups()
    {
        $ret = array();
        $this->Database->array_query( $res, "SELECT GroupID FROM eZArticle_ArticleReaderLink
                                       WHERE ArticleID='$this->ID'" );
        if( count( $res ) > 0 )
        {
            $i = 0;
            foreach( $res as $groupID )
            {
                $ret[i] = new eZUserGroup( $groupID );
                $i++;
            }
        }
        return $ret;
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

      The argument must be an eZImage object, or false to unset the thumbnail image.
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
                                                       ArticleID='$this->ID'" );

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
        else if ( $image == false )
        {
            $this->dbInit();

            $this->Database->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZArticle_ArticleImageDefinition
                                                       WHERE
                                                       ArticleID='$this->ID'" );

            if ( $res_array[0]["Number"] == "1" )
            {
                $this->Database->query( "DELETE FROM eZArticle_ArticleImageDefinition
                                         WHERE
                                         ArticleID='$this->ID'" );
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
      Adds an file to the article.
    */
    function addFile( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $value ) == "ezvirtualfile" )
        {
            $this->dbInit();

            $fileID = $value->id();


            $this->Database->query( "INSERT INTO eZArticle_ArticleFileLink SET ArticleID='$this->ID', FileID='$fileID'" );
        }
    }

    /*!
      Deletes an file from the article.

      NOTE: the file does not get deleted from the file catalogue.
    */
    function deleteFile( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $value ) == "ezvirtualfile" )
        {
            $this->dbInit();

            $fileID = $value->id();
            
            $this->Database->query( "DELETE FROM eZArticle_ArticleFileLink WHERE ArticleID='$this->ID' AND FileID='$fileID'" );
        }
    }
    
    /*!
      Returns every file to a article as a array of eZFile objects.
    */
    function files()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $return_array = array();
       $file_array = array();
       
       $this->Database->array_query( $file_array, "SELECT FileID FROM eZArticle_ArticleFileLink WHERE ArticleID='$this->ID' ORDER BY Created" );
       
       for ( $i=0; $i<count($file_array); $i++ )
       {
           $return_array[$i] = new eZVirtualFile( $file_array[$i]["FileID"], false );
       }
       
       return $return_array;
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
      Does a search in the article archive.
    */
    function search( $queryText, $sortMode=time, $fetchNonPublished=true )
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

       if ( $fetchNonPublished == true )
       {
           $fetchText = "eZArticle_Article.IsPublished = 'true'
                    AND";           
       }
       else
       {           
           $fetchText = "";
       }

       $return_array = array();
       $article_array = array();

       $this->Database->array_query( $article_array,
                    "SELECT eZArticle_Article.ID AS ArticleID, eZArticle_Article.Name, eZArticle_Category.ID, eZArticle_Category.Name
                    FROM eZArticle_Article, eZArticle_Category, eZArticle_ArticleCategoryLink 
                    WHERE 
                    ( 
                    eZArticle_Article.Name LIKE '%$queryText%' OR
                    eZArticle_Article.Keywords LIKE '%$queryText%'
                    )
                    AND
                    eZArticle_ArticleCategoryLink.ArticleID = eZArticle_Article.ID
                    AND
                    eZArticle_Category.ID = eZArticle_ArticleCategoryLink.CategoryID
                    AND
                    eZArticle_Category.ExcludeFromSearch = 'false'
                    GROUP BY eZArticle_Article.ID ORDER BY $OrderBy" );
 
       for ( $i=0; $i<count($article_array); $i++ )
       {
           $return_array[$i] = new eZArticle( $article_array[$i]["ArticleID"], false );
       }
       
       return $return_array;
    }
    
    /*!
      Returns every article in every category sorted by time.
    */
    function articles( $sortMode=time,
                       $fetchNonPublished=true,
                       $offset=0,
                       $limit=50 )
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
                    GROUP BY eZArticle_Article.ID ORDER BY $OrderBy
                    LIMIT $offset,$limit" );
           
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
                    GROUP BY eZArticle_Article.ID ORDER BY $OrderBy
                    LIMIT $offset,$limit" );
       }

       for ( $i=0; $i < count($article_array); $i++ )
       {
           $return_array[$i] = new eZArticle( $article_array[$i]["ArticleID"], false );
       }
       
       return $return_array;
    }

    /*!
      Set's the articles defined category. This is the main category for the article.
      Additional categories can be added with eZArticleCategory::addArticle();
    */
    function setCategoryDefinition( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $value ) == "ezarticlecategory" )
       {
            $this->dbInit();

            $categoryID = $value->id();

            $this->Database->query( "DELETE FROM eZArticle_ArticleCategoryDefinition
                                     WHERE ArticleID='$this->ID'" );
            
            $query = "INSERT INTO
                           eZArticle_ArticleCategoryDefinition
                      SET
                           CategoryID='$categoryID',
                           ArticleID='$this->ID'";
            
            $this->Database->query( $query );
       }       
    }

    /*!
      Returns the article's definition category.
    */
    function categoryDefinition( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $this->Database->array_query( $res, "SELECT CategoryID FROM
                                            eZArticle_ArticleCategoryDefinition
                                            WHERE ArticleID='$this->ID'" );

       $category = false;
       if ( count( $res ) == 1 )
       {
           $category = new eZArticleCategory( $res[0]["CategoryID"] );
       }
       else
       {
           print( "<br><b>Failed to get article category definition for ID $this->ID</b></br>" );
       }

       return $category;
    }

    /*!
      Creates a discussion forum for the article.
    */
    function createForum()
    {

    }

    /*!
      Returns the forum for the article.
    */
    function forum()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $this->Database->array_query( $res, "SELECT ForumID FROM
                                            eZArticle_ArticleForumLink
                                            WHERE ArticleID='$this->ID'" );
       
       $forum = false;
       if ( count( $res ) == 1 )
       {
           $forum = new eZForum( $res[0]["ForumID"] );
       }
       else
       {
           $forum = new eZForum();
           $forum->setName( $this->Name );
           $forum->store();

           $forumID = $forum->id();

           $this->Database->query( "INSERT INTO eZArticle_ArticleForumLink
                                          SET ArticleID='$this->ID', ForumID='$forumID'" );

           $forum = new eZForum( $forumID );
       }


       return $forum;
    }

    /*!
      Returns a list of authors and their article count.
    */
    function authorList( $offset = 0, $limit = -1, $sort = false )
    {
        if ( is_string( $sort ) )
        {
            switch( $sort )
            {
                case "count":
                {
                    $sort_text = "ORDER BY Count";
                    break;
                }
                default:
                case "name":
                {
                    $sort_text = "ORDER BY AuthorText";
                    break;
                }
            }
        }
        if ( is_numeric( $limit ) and $limit > 0 )
        {
            $limit_text = "LIMIT $offset, $limit";
        }
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT count( eZArticle_Article.ID ) AS Count, AuthorID
                                       FROM eZArticle_Article, eZArticle_ArticleCategoryLink
                                       WHERE IsPublished='true' AND eZArticle_Article.ID=ArticleID
                                       GROUP BY AuthorID $sort_text $limit_text" );
        return $qry_array;
    }

    /*!
      Returns a list of authors and their article count.
    */
    function authorArticleList( $authorid, $offset = 0, $limit = -1, $sort = false )
    {
        if ( is_string( $sort ) )
        {
            switch( $sort )
            {
                case "author":
                {
                    $sort_text = "ORDER BY A.AuthorText";
                    break;
                }
                case "name":
                {
                    $sort_text = "ORDER BY A.Name";
                    break;
                }
                case "category":
                {
                    $sort_text = "ORDER BY C.Name";
                    break;
                }
                default:
                case "published":
                {
                    $sort_text = "ORDER BY A.Published DESC";
                    break;
                }
            }
        }
        if ( is_numeric( $limit ) and $limit > 0 )
        {
            $limit_text = "LIMIT $offset, $limit";
        }
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT A.ID, A.Name, A.AuthorText AS AuthorName, A.Published,
                                              C.ID AS CategoryID, C.Name AS CategoryName
                                       FROM eZArticle_Article AS A, eZArticle_Category AS C, eZArticle_ArticleCategoryLink AS ACL
                                       WHERE IsPublished='true' AND AuthorID='$authorid' AND
                                             A.ID=ACL.ArticleID AND C.ID=ACL.CategoryID
                                       GROUP BY A.ID $sort_text $limit_text" );
        return $qry_array;
    }
    
    /*!
      Returns a list of authors and their article count.
    */
    function authorArticleCount( $authorid )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry_array, "SELECT count( eZArticle_Article.ID ) AS Count
                                        FROM eZArticle_Article, eZArticle_ArticleCategoryLink
                                        WHERE IsPublished='true' AND eZArticle_Article.ID=ArticleID
                                        AND AuthorID='$authorid'" );
        return $qry_array["Count"];
    }

    /*!
      \private
      
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
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
    var $Keywords;
    var $OwnerGroupID;
    var $ReadPermission=0;
    
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
