<?php
// 
// $Id: ezarticle.php,v 1.93 2001/06/08 12:05:02 bf Exp $
//
// Definition of eZArticle class
//
// Bård Farstad <bf@ez.no>
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
  $article->setAuthorText( "Bård Farstad" );
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
include_once( "ezuser/classes/ezauthor.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezfilemanager/classes/ezvirtualfile.php" );

include_once( "ezforum/classes/ezforum.php" );
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticleattribute.php" );
include_once( "ezarticle/classes/ezarticletype.php" );

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

        $name = addslashes( $this->Name );
        $contents = addslashes( $this->Contents );
        $authortext = addslashes( $this->AuthorText );
        $authoremail = addslashes( $this->AuthorEmail );
        $linktext = addslashes( $this->LinkText );
        $keywords = addslashes( $this->Keywords );

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZArticle_Article SET
		                         Name='$name',
                                 Contents='$contents',
                                 AuthorID='$this->AuthorID',
                                 LinkText='$linktext',
                                 PageCount='$this->PageCount',
                                 IsPublished='$this->IsPublished',
                                 Keywords='$keywords',
                                 Discuss='$this->Discuss',
                                 ContentsWriterID='$this->ContentsWriterID',
                                 TopicID='$this->TopicID',
                                 Modified=now(),
                                 Published=now(),
                                 Created=now()
                                 " );

			$this->ID = $this->Database->insertID();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->array_query( $res, "SELECT ID FROM eZArticle_Article WHERE IsPublished='false' AND ID='$this->ID'" );
            
            if ( ( count( $res ) > 0 ) && ( $this->IsPublished == "true" ) )
            {                
                $this->Database->query( "UPDATE eZArticle_Article SET
		                         Name='$name',
                                 Contents='$contents',
                                 LinkText='$linktext',
                                 PageCount='$this->PageCount',
                                 AuthorID='$this->AuthorID',
                                 IsPublished='$this->IsPublished',
                                 Keywords='$keywords',
                                 Discuss='$this->Discuss',
                                 ContentsWriterID='$this->ContentsWriterID',
                                 TopicID='$this->TopicID',
                                 Published=now(),
                                 Modified=now()
                                 WHERE ID='$this->ID'
                                 " );
            }
            else
            {
                $this->Database->query( "UPDATE eZArticle_Article SET
		                         Name='$name',
                                 Contents='$contents',
                                 LinkText='$linktext',
                                 PageCount='$this->PageCount',
                                 AuthorID='$this->AuthorID',
                                 IsPublished='$this->IsPublished',
                                 Keywords='$keywords',
                                 Discuss='$this->Discuss',
                                 ContentsWriterID='$this->ContentsWriterID',
                                 TopicID='$this->TopicID',
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
                $this->AuthorEmail =& $article_array[0][ "AuthorEmail" ];
                $this->AuthorID =& $article_array[0][ "AuthorID" ];
                $this->LinkText =& $article_array[0][ "LinkText" ];
                $this->Modified =& $article_array[0][ "Modified" ];
                $this->Created =& $article_array[0][ "Created" ];
                $this->Published =& $article_array[0][ "Published" ];
                $this->PageCount =& $article_array[0][ "PageCount" ];
                $this->IsPublished =& $article_array[0][ "IsPublished" ];
                $this->Keywords =& $article_array[0][ "Keywords" ];
                $this->Discuss =& $article_array[0][ "Discuss" ];
                $this->ContentsWriterID =& $article_array[0][ "ContentsWriterID" ];
                $this->TopicID =& $article_array[0][ "TopicID" ];
                
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
        $db =& eZDB::globalDatabase();
        if ( isset( $this->ID ) )
        {
            $imageList =& $this->images();
            $fileList =& $this->files();
            foreach( $imageList as $image )
            {
//                print_r( $image );

//                $image->delete();
            }
            foreach( $fileList as $file )
            {
//                $file->delete();
            }
            $db->query( "DELETE FROM eZArticle_ArticleCategoryLink WHERE ArticleID='$this->ID'" );
            $db->query( "DELETE FROM eZArticle_ArticleCategoryDefinition WHERE ArticleID='$this->ID'" );
            $db->query( "DELETE FROM eZArticle_ArticleImageLink WHERE ArticleID='$this->ID'" );
            $db->query( "DELETE FROM eZArticle_ArticleImageDefinition WHERE ArticleID='$this->ID'" );
            $db->query( "DELETE FROM eZArticle_ArticlePermission WHERE ObjectID='$this->ID'" );
            $db->query( "DELETE FROM eZArticle_Article WHERE ID='$this->ID'" );
            $db->query( "DELETE FROM eZArticle_AttributeValue WHERE ArticleID='$this->ID'" );
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
    function &name( $asHTML = true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( $asHTML == true )
            return htmlspecialchars( $this->Name );
        return $this->Name;
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
    function &authorText( $asHTML = true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $author = new eZAuthor( $this->ContentsWriterID );
        
        if( $asHTML == true )
            return htmlspecialchars( $author->name() );
        return $author->name();
    }

    /*!
      Returns the author text contents.
    */
    function &authorEmail( $asHTML = true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $author = new eZAuthor( $this->ContentsWriterID );
        
        if( $asHTML == true )
            return htmlspecialchars( $author->email() );
        return $author->email();
    }

    
    /*!
      Returns the link text.
    */
    function &linkText( $asHTML = true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        if(  $asHTML )
            return htmlspecialchars( $this->LinkText );
        return $this->LinkText;
    }

    /*!
      Returns the author as a eZUser object.
    */
    function &author( $as_object = true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( $as_object )
            $author = new eZUser( $this->AuthorID );
        else
            $author = $this->AuthorID;
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
      Returns the discuss value of an article.
    */
    function discuss( )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
        if ( $this->Discuss == 1 )
        {
            $ret = true;
        }
        return $ret;
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
      Sets the author email.
    */
    function setAuthorEmail( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->AuthorEmail = $value;
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
      Sets the discuss value to an article.
    */
    function setDiscuss( $discuss )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $discuss == true )
            $this->Discuss = 1;
        else
            $this->Discuss = 0;
    }

    /*!
      Sets the manual keywords to an article. Theese words are used in the search.
    */
    function setManualKeywords( $keywords )
    {
        if ( !is_array( $keywords ) )
        {
            $words = explode( ",", $keywords );
            $keywords = array();
            foreach( $words as $word )
            {
                $keyword = strtolower( trim( $word ) );
                if ( $keyword != "" )
                    $keywords[] = $keyword;
            }
        }
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZArticle_ArticleKeyword WHERE ArticleID='$this->ID' AND Automatic='0'" );
        foreach( $keywords as $keyword )
        {
            $db->query( "INSERT INTO eZArticle_ArticleKeyword SET
                         ArticleID='$this->ID',
                         Keyword='$keyword',
                         Automatic='0'" );
        }
    }

    /*!
      Returns the manual keywords for an article.
      It is either returned as an array or as a comma separated string.
    */
    function &manualKeywords( $as_array = false )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $keywords, "SELECT Keyword FROM eZArticle_ArticleKeyword
                                      WHERE ArticleID='$this->ID' AND Automatic='0'" );
        $ret = array();
        foreach( $keywords as $keyword )
        {
            $ret[] = $keyword["Keyword"];
        }
        if ( !$as_array )
            $ret = implode( ", ", $ret );
        return $ret;
    }

    /*!
      \static
      Returns an index of keywords found in all articles.
      It returns an array of unique keywords.
    */
    function &manualKeywordIndex()
    {
        $user = eZUser::currentUser();
        $currentUserSQL = "";
        if ( $user )
        {
            $groups = $user->groups( true );

            $groupSQL = "";
           
            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= "Permission.GroupID=$group OR";
                else
                    $groupSQL .= " Permission.GroupID=$group OR";
               
                $i++;
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Article.AuthorID=$currentUserID OR";
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' ) AND Permission.ReadPermission='1') ) AND";

        $db =& eZDB::globalDatabase();
        $db->array_query( $keywords, "SELECT ArtKey.Keyword AS Keyword
                  FROM eZArticle_ArticleCategoryLink as Link,
                       eZArticle_ArticlePermission AS Permission,
                       eZArticle_Category AS Category,
                       eZArticle_Article AS Article LEFT JOIN eZArticle_ArticleKeyword AS ArtKey ON
                       Article.ID=ArtKey.ArticleID
                  WHERE (
                        ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                        )
                        AND Article.IsPublished = 'true'
                        AND Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID
                        AND Category.ID=Link.CategoryID
                        AND Category.ExcludeFromSearch = 'false'
                        AND ArtKey.Keyword is not NULL
                                      GROUP BY Keyword ORDER BY Keyword", 0, -1, "Keyword" );
        return $keywords;
    }

    /*!
      Sets the contents author.
    */
    function setContentsWriter( $author )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ContentsWriterID = $author->id();
    }

    /*!
      Returns the contentswriter of the article.
    */
    function contentsWriter( )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return new eZAuthor( $this->ContentsWriterID );
    }
    

    /*!
      Sets the topic.
    */
    function setTopic( $topic )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->TopicID = $topic->id();
    }

    /*!
      Returns the topic.

      If there is no topic selected for the article false is returned.
    */
    function topic()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return new eZTopic( $this->TopicID );
    }
    
    /*!
      \static
      Returns an array of articles which match short contents and the keywords.
    */
    function &searchByShortContent( $short_content, $keywords, $offset = 0, $max = -1, $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $content_sql = "";
        $keyword_sql = "";
        if ( count( $keywords ) > 0 )
        {
            foreach( $keywords as $keyword )
            {
                if ( $keyword_sql != "" )
                    $keyword_sql .= " OR ";
                $keyword_sql .= "ArtKey.Keyword='$keyword'";
            }
            if ( $content_sql != "" )
                $keyword_sql = "AND ( $keyword_sql )";
        }
        $conditions = "";
        if ( $content_sql != "" or $keyword_sql != "" )
        {
            $conditions = "AND $content_sql $keyword_sql";
        }
        $limit_sql = "";
        if ( !is_bool( $offset ) and $max > 0 )
        {
            $limit_sql = "LIMIT $offset, $max";
        }

        $user = eZUser::currentUser();
        $currentUserSQL = "";
        if ( $user )
        {
            $groups = $user->groups( true );

            $groupSQL = "";
           
            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= "Permission.GroupID=$group OR";
                else
                    $groupSQL .= " Permission.GroupID=$group OR";
               
                $i++;
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Article.AuthorID=$currentUserID OR";
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' ) AND Permission.ReadPermission='1') ) AND";

        $select_sql = "";
        if ( is_bool( $offset ) )
        {
            $select_sql = "count( DISTINCT Article.ID ) as ArticleCount";
        }
        else
        {
            $select_sql = "DISTINCT Article.ID as ArticleID";
        }
        $sql = "SELECT $select_sql
                  FROM eZArticle_ArticleCategoryLink as Link,
                       eZArticle_ArticlePermission AS Permission,
                       eZArticle_Category AS Category,
                       eZArticle_Article AS Article LEFT JOIN eZArticle_ArticleKeyword AS ArtKey ON
                       Article.ID=ArtKey.ArticleID
                  WHERE (
                        ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                        )
                        AND Article.IsPublished = 'true'
                        AND Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID
                        AND Category.ID=Link.CategoryID
                        AND Category.ExcludeFromSearch = 'false'
                        $conditions
                 ORDER BY Article.Name
                 $limit_sql";
        $db->array_query( $contents, $sql );
        if ( !is_bool( $offset ) )
        {
            $ret = array();
            foreach( $contents as $content )
            {
                $ret[] = $as_object ? new eZArticle( $content["ArticleID"] ) : $content["ArticleID"];
            }
        }
        else
            $ret = $contents[0]["ArticleCount"];
        return $ret;
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
      Returns the categories an article is assigned to.

      The categories are returned as an array of eZArticleCategory objects.
    */
    function categories( $as_object = true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->dbInit();

        $ret = array();
        $this->Database->array_query( $category_array, "SELECT * FROM eZArticle_ArticleCategoryLink WHERE ArticleID='$this->ID'" );

        if ( $as_object )
        {
            foreach ( $category_array as $category )
            {
                $ret[] = new eZArticleCategory( $category["CategoryID"] );
            }
        }
        else
        {
            foreach ( $category_array as $category )
            {
                $ret[] = $category["CategoryID"];
            }
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
      Adds an image to the article, unless the image is allready added for this article.
    */
    function addImage( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
            $this->dbInit();
        
        if( get_class( $value ) == "ezimage" )
            $value = $value->id();

        $this->Database->query_single( $res, "SELECT count( * ) as Count FROM eZArticle_ArticleImageLink WHERE ArticleID='$this->ID' AND ImageID='$value'" );
        if( $res["Count"] == 0 )
        {
            $this->Database->query( "INSERT INTO eZArticle_ArticleImageLink SET ArticleID='$this->ID', ImageID='$value'" );
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
    function images( $asObject = true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->dbInit();
       
        $return_array = array();
        $image_array = array();
       
        $this->Database->array_query( $image_array, "SELECT ImageID FROM eZArticle_ArticleImageLink WHERE ArticleID='$this->ID' ORDER BY Created" );
       
        for ( $i=0; $i < count($image_array); $i++ )
        {
            $return_array[$i] = $asObject ? new eZImage( $image_array[$i]["ImageID"], false ) : $image_array[$i]["ImageID"];
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
       
        for ( $i=0; $i < count($file_array); $i++ )
        {
            $return_array[$i] = new eZVirtualFile( $file_array[$i]["FileID"], false );
        }
       
        return $return_array;
    }
    
    
    
    /*!
      Deletes an attribute from an article.
    */
    function deleteAttribute( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $value ) == "ezarticleattribute" )
        {
            $this->dbInit();

            $attributeID = $value->id();
            
            $this->Database->query( "DELETE FROM eZArticle_AttributeValue WHERE ArticleID='$this->ID' AND AttributeID='$attributeID'" );
        }
    }

    /*!
      Returns every attribute belonging to an article as an array of eZArticleAttribute objects.
    */
    function attributes()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->dbInit();
       
        $return_array = array();
        $attribute_array = array();
       
        $this->Database->array_query( $attribute_array, "SELECT Value.AttributeID FROM eZArticle_AttributeValue as Value, eZArticle_Attribute as Attr WHERE Attr.ID = Value.AttributeID AND Value.ArticleID='$this->ID' ORDER BY Attr.TypeID, Attr.Placement" );
       
        for ( $i=0; $i < count( $attribute_array ); $i++ )
        {
            $return_array[$i] = new eZArticleAttribute( $attribute_array[$i]["AttributeID"], false );
        }
       
        return $return_array;
    }
    
    
    /*!
        Deletes all attributes defined for this article which belongs to a certain type.
     */
    function deleteAttributesByType( $type )
    {
        $ret = false;


        if ( get_class( $type ) == "ezarticletype" )
        {
            if ( $this->State_ == "Dirty" )
                $this->get( $this->ID );

            $this->dbInit();
            
            $typeID = $type->id();
            
            $return_array = array();
            $attribute_array = array();

            $this->Database->array_query( $attribute_array, "SELECT Value.ID FROM eZArticle_AttributeValue AS Value, eZArticle_Attribute AS Attr WHERE Value.ArticleID='$this->ID' AND Value.AttributeID=Attr.ID AND Attr.TypeID='$typeID'" );

            for ( $i=0; $i < count( $attribute_array ); $i++ )
            {
               $valueID =  $attribute_array[$i]["ID"];
               $this->Database->query( "DELETE FROM eZArticle_AttributeValue WHERE ID='$valueID'" );
            }
            
            $ret = true;
        }
        return $ret;
    }
    
    /*!
      Returns every attribute type belonging to an article as an array of eZArticleType objects.
    */
    function types()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->dbInit();
       
        $return_array = array();
        $type_array = array();
       
        $this->Database->array_query( $type_array, "SELECT Attr.TypeID AS TypeID FROM eZArticle_Attribute AS Attr, eZArticle_AttributeValue AS Value WHERE Value.ArticleID='$this->ID' AND Attr.ID = Value.AttributeID GROUP BY Attr.TypeID" );
       
        for ( $i=0; $i < count( $type_array ); $i++ )
        {
            $return_array[$i] = new eZArticleType( $type_array[$i]["TypeID"], false );
        }
       
        return $return_array;
    }
    
     /*!
      Returns true if the type given exists for this article.
    */
    function hasType( $type )
    {
        $ret = false;
        if ( get_class( $value ) == "ezarticletype" )
        {
            $typeID = $value->id();
            if ( $this->State_ == "Dirty" )
                $this->get( $this->ID );

            $this->dbInit();

            $return_array = array();
            $type_array = array();

            $this->Database->array_query( $type_array, "SELECT Attr.TypeID AS TypeID FROM eZArticle_Attribute AS Attr, eZArticle_AttributeValue AS Value WHERE Value.ArticleID='$this->ID' AND Attr.ID = Value.AttributeID AND Attr.TypeID='$typeID'" );

            if( count( $type_array ) > 0 )
            {
                $ret = true;
            }
        }
        return $ret;
    }
    
   /*!
      Returns true if the article is assigned to the category given
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
    function &search( &$queryText, $sortMode=time, $fetchNonPublished=true, $offset=0, $limit=10 )
    {
        $db =& eZDB::globalDatabase();

        // Build the ORDER BY
        $OrderBy = "Article.Published DESC";
        switch( $sortMode )
        {
            case "alpha" :
            {
                $OrderBy = "Article.Name DESC";
            }
            break;
        }
       
        if ( $fetchNonPublished == true )
        {
            $fetchText = "";
        }
        else
        {
            $fetchText = "AND Article.IsPublished = 'true'";
        }

        $user = eZUser::currentUser();

        // Build the permission
        $loggedInSQL = "";
        if ( $user )
        {
            $groups = $user->groups( true );

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

        // Build the search
        $query = new eZQuery( array( "Keywords", "Name" ), $queryText );
        $search = $query->buildQuery();

        $queryString = "SELECT DISTINCT Article.ID AS ArticleID
                 FROM eZArticle_Article AS Article,
                      eZArticle_ArticleCategoryLink AS Link,
                      eZArticle_ArticlePermission AS Permission
                 WHERE (
                       ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                       AND $search
                       )
                       $publishedCode
                       $fetchText
                       AND Permission.ObjectID=Article.ID
                       AND Link.ArticleID=ArticleID
                       ORDER BY $OrderBy
                       LIMIT $offset, $limit";

        $db->array_query( $article_array, $queryString );
        for ( $i=0; $i < count($article_array); $i++ )
        {
            $return_array[$i] = new eZArticle( $article_array[$i]["ArticleID"], false );
        }
       
        return $return_array;
    }


    /*!
      Does a search in the article archive.
    */
    function searchCount( $queryText,  $fetchNonPublished=true )
    {
        $db =& eZDB::globalDatabase();
       
        if ( $fetchNonPublished == true )
        {
            $fetchText = "";
        }
        else
        {
            $fetchText = "AND eZArticle_Article.IsPublished = 'true'";            
        }

        // this code works. do not EDIT !! :)
        $user = eZUser::currentUser();

        $loggedInSQL = "";
        if ( $user )
        {
            $groups = $user->groups( true );

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

        // Build the search
        $query = new eZQuery( array( "Keywords", "Name" ), $queryText );
        $search = $query->buildQuery();

        $queryString = "SELECT COUNT(DISTINCT Article.ID) AS Count
                 FROM eZArticle_Article AS Article,
                      eZArticle_ArticleCategoryLink AS Link,
                      eZArticle_ArticlePermission AS Permission
                 WHERE (
                       ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                       AND $search
                       )
                       $publishedCode
                       $fetchText
                       AND Permission.ObjectID=Article.ID
                       AND Link.ArticleID=ArticleID
                       GROUP BY ArticleID
                       LIMIT 0, 1";

        $db->query_single( $count, $queryString );

        return $count["Count"];
    }

    /*!
      Returns the number of articles available, for the current user.
    */
    function articleCount( $fetchNonPublished=true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->dbInit();

        $OrderBy = "Article.Published DESC";
        switch( $sortMode )
        {
            case "alpha" :
            {
                $OrderBy = "Article.Name DESC";
            }
            break;
        }

       
        $return_array = array();
        $article_array = array();

        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        if ( $user )
        {
            $groups = $user->groups( true );

            $groupSQL = "";
           
            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= "Permission.GroupID=$group OR";
                else
                    $groupSQL .= " Permission.GroupID=$group OR";
               
                $i++;
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Article.AuthorID=$currentUserID OR";
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' ) AND Permission.ReadPermission='1') ) AND";

        $publishedCode = "";
        if ( $fetchNonPublished == false )
        {
            $publishedCode = "AND Article.IsPublished = 'true'";
        }
        else if ( $fetchNonPublished == "only" )
        {
            $publishedCode = "AND Article.IsPublished = 'false'";
        }

        $query = "SELECT COUNT( DISTINCT Article.ID ) as Count
                  FROM eZArticle_Article AS Article,
                       eZArticle_ArticleCategoryLink as Link,
                       eZArticle_ArticlePermission AS Permission,
                       eZArticle_Category AS Category
                  WHERE (
                        ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                        )
                        $publishedCode
                        AND Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID
                        AND Category.ID=Link.CategoryID
                        AND Category.ID=Link.CategoryID
                        AND Category.ExcludeFromSearch = 'false'";
       
        $this->Database->array_query( $article_array, $query  );

        return  $article_array[0][ "Count" ];
    }

    /*!
      Returns every article in every category sorted by time.
    */
    function &articles( $sortMode=time,
    $fetchNonPublished=true,
    $offset=0,
    $limit=50 )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->dbInit();

        $OrderBy = "Article.Published DESC";
        switch( $sortMode )
        {
            case "alpha" :
            {
                $OrderBy = "Article.Name DESC";
            }
            break;
        }

       
        $return_array = array();
        $article_array = array();

        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        if ( $user )
        {
            $groups = $user->groups( true );

            $groupSQL = "";
           
            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= "Permission.GroupID=$group OR";
                else
                    $groupSQL .= " Permission.GroupID=$group OR";
               
                $i++;
            }
            $currentUserID = $user->id();
            $currentUserSQL = "Article.AuthorID=$currentUserID OR";
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL Permission.GroupID='-1' ) AND Permission.ReadPermission='1') ) AND";

        $publishedCode = "";
        if ( $fetchNonPublished == false )
        {
            $publishedCode = "AND Article.IsPublished = 'true'";
        }
        else if ( $fetchNonPublished == "only" )
        {
            $publishedCode = "AND Article.IsPublished = 'false'";
        }

        $query = "SELECT DISTINCT Article.ID as ArticleID
                  FROM eZArticle_Article AS Article,
                       eZArticle_ArticleCategoryLink as Link,
                       eZArticle_ArticlePermission AS Permission,
                       eZArticle_Category AS Category
                  WHERE (
                        ( $loggedInSQL ($groupSQL Permission.GroupID='-1') AND Permission.ReadPermission='1' )
                        )
                        $publishedCode
                        AND Permission.ObjectID=Article.ID
                        AND Link.ArticleID=Article.ID
                        AND Category.ID=Link.CategoryID
                        AND Category.ID=Link.CategoryID
                        AND Category.ExcludeFromSearch = 'false'
                 ORDER BY $OrderBy
                 LIMIT $offset,$limit";
       
        $this->Database->array_query( $article_array, $query  );

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
    function &forum()
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
            $forum->setName( addslashes( $this->Name ) );
            $forum->store();

            $forumID = $forum->id();

            $this->Database->query( "INSERT INTO eZArticle_ArticleForumLink
                                          SET ArticleID='$this->ID', ForumID='$forumID'" );

            $forum = new eZForum( $forumID );
        }


        return $forum;
    }


    /*!
      \Static
      Returns true if the given user is the author of the given object.
      $user is either a userID or an eZUser.
      $article is the articleID
     */
    function isAuthor( $user, $articleID )
    {
        if( get_class( $user ) != "ezuser" )
            return false;
        
        $database =& eZDB::globalDatabase();
        $database->query_single( $res, "SELECT AuthorID from eZArticle_Article WHERE ID='$articleID'");
        $authorID = $res[ "AuthorID" ];
        if(  $authorID == $user->id() )
            return true;

        return false;
    }
    
    /*!
      Returns the article which a forum is connected to.
     */
    function articleIDFromForum( $ForumID )
    {
        $db =& eZDB::globalDatabase();

        $ArticleID = 0;

        /* OLD
           $db->array_query( $result, "SELECT DISTINCT ArticleID FROM
           eZArticle_ArticleForumLink
           WHERE ForumID='$ForumID'" );
        */

        $db->array_query( $result, "SELECT ArticleID FROM
                                    eZArticle_ArticleForumLink
                                    WHERE ForumID='$ForumID' GROUP BY ArticleID" );

        if( count( $result ) > 0 )
        {
            $ArticleID = $result[0]["ArticleID"];
        }

        return $ArticleID;
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
                    $sort_text = "ORDER BY ContentsWriterID";
                    break;
                }
            }
        }

        if ( is_numeric( $limit ) and $limit > 0 )
        {
            $limit_text = "LIMIT $offset, $limit";
        }
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT count( eZArticle_Article.ID ) AS Count, eZUser_Author.Name AS ContentsWriter, ContentsWriterID
                                       FROM eZArticle_Article, eZArticle_ArticleCategoryLink, eZUser_Author
                                       WHERE IsPublished='true' AND eZArticle_Article.ID=ArticleID
                                       AND eZArticle_Article.ContentsWriterID=eZUser_Author.ID
                                       GROUP BY ContentsWriterID $sort_text $limit_text" );
        return $qry_array;
    }

    /*!
      Returns a all articles an author has written that currentuser is allowed to see.
    */
    function authorArticleList( $authorid, $offset = 0, $limit = -1, $sort = false )
    {
        if ( is_string( $sort ) )
        {
            switch( $sort )
            {
                case "author":
                {
                    $sort_text = "ORDER BY A.ContentsWriterID";
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

        $user =& eZUser::currentUser();
        $currentUserSQL = "";
        if ( $user )
        {
            $groups = $user->groups( true );

            $groupSQL = "";
           
            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= "P.GroupID=$group OR";
                else
                    $groupSQL .= " P.GroupID=$group OR";
               
                $i++;
            }
            $currentUserID = $user->id();
            $currentUserSQL = "A.AuthorID=$currentUserID OR";
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL P.GroupID='-1' ) AND P.ReadPermission='1') ) AND";
        

        $query = "SELECT A.ID , A.Name, Author.Name as AuthorName, A.Published, C.ID as CategoryID, C.Name as CategoryName
                     FROM eZArticle_Article AS A, eZArticle_Category as C, eZArticle_ArticleCategoryLink as ACL, eZArticle_ArticlePermission AS P, eZUser_Author as Author
                     WHERE A.ID=ACL.ArticleID AND C.ID=ACL.CategoryID AND A.ContentsWriterID=Author.ID AND
                     IsPublished='true' AND ContentsWriterID='$authorid' AND $loggedInSQL
                     A.ID=P.ObjectID GROUP BY A.ID $sort_text $limit_text";

        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, $query );
        return $qry_array;
    }
    
    /*!
      Returns the number of articles this author has written that the user is allowed to see.
    */
    function authorArticleCount( $authorid )
    {
        $user = eZUser::currentUser();
        $currentUserSQL = "";
        if ( $user )
        {
            $groups = $user->groups( true );

            $groupSQL = "";
           
            $i = 0;
            foreach ( $groups as $group )
            {
                if ( $i == 0 )
                    $groupSQL .= "P.GroupID=$group OR";
                else
                    $groupSQL .= " P.GroupID=$group OR";
               
                $i++;
            }
            $currentUserID = $user->id();
            $currentUserSQL = "A.AuthorID=$currentUserID OR";
        }
        $loggedInSQL = "( $currentUserSQL ( ( $groupSQL P.GroupID='-1' ) AND P.ReadPermission='1') ) AND";
       

        $query = "SELECT A.ID AS Count 
                     FROM eZArticle_Article AS A,
                     eZArticle_ArticlePermission AS P,
                     eZUser_Author as Author
                     WHERE A.ContentsWriterID=Author.ID AND IsPublished='true' AND ContentsWriterID='$authorid' AND $loggedInSQL
                     A.ID=P.ObjectID GROUP BY A.ID";

        
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array, $query );
        return count( $qry_array );
    }

    /*!
      Adds a log message to the article.
    */
    function addLog( $message )
    {
        $db =& eZDB::globalDatabase();

        $user = eZUser::currentUser();
        $userID = $user->id();
        
        $query = "INSERT INTO eZArticle_Log
                  SET ArticleID='$this->ID',
                  Created=now(),
                  Message='$message',
                  UserID='$userID'
                  ";
        
        $db->query( $query );        
    }

    /*!
      Returns an array of the logg messages for the current article.

      The messages are returned as : array( date, message ).
    */
    function logMessages( )
    {
        $db =& eZDB::globalDatabase();

        $query = "SELECT * FROM  eZArticle_Log
                  WHERE ArticleID='$this->ID'
                  ORDER BY Created
                  ";
        
        $db->array_query( $ret_array, $query );

        return $ret_array;
    }
        

    /*!
      \private
      
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
    var $AuthorID;
    var $ContentsWriterID;
    var $Name;
    var $Contents;
    var $LinkText;
    var $Modified;
    var $Created;
    var $Published;
    var $Keywords;
    var $Discuss;
    var $TopicID;
    
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
