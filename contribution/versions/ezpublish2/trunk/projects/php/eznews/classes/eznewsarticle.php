<?
// 
// $Id: eznewsarticle.php,v 1.5 2000/10/11 10:05:57 pkej-cvs Exp $
//
// Definition of eZNewsArticle class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <14-Sep-2000 10:46:38 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZNews
//! eZNewsArticle handles eZNews items.
/*!
  The eZProductCategory class handles product groups, the relation to products
  in the productgroup and the options connected to the product group.
  
  Example of usage:

  \code
  \endcode

  \sa 
*/

include_once( "eznews/classes/eznewsitem.php" );

class eZNewsArticle extends eZNewsItem
{

    /*!
        Constructor, nothing special here.
     */
    function eZNewsArticle( $inData = "", $fetch = true )
    {
        #echo "eZNewsArticle::eZNewsArticle( \$inData = $inData, \$fetch = $fetch )<br>";
        eZNewsItem::eZNewsItem( $inData, $fetch );
    }



    /*!
        \private
        
        Stores a eZNewsArticle object in the database.
      
        \out
            \$outID The ID of the stored object.
        \return
            Returns true if the object is stored.
    */
    
    function storeThis( &$outID )
    {
        #echo "eZNewsArticle::storeThis( \$outID = $outID )<br>";
        $value = false;
        
        eZNewsItem::storeThis( $outID );
        
        if( $outID )
        {
            $query =
            "
                INSERT INTO
                    eZNews_Article
                SET
                    ID          = '%s',
                    AuthorText  = '%s',
                    Meta        = '%s',
                    Story       = '%s',
                    LinkText    = '%s'
            ";

            $query = sprintf
            (
                $query,
                $this->ID,
                $this->AuthorText,
                $this->Meta,
                $this->Story,
                $this->LinkText
            );

            $this->Database->query( $query );
            
            $value = true;
        }
        
        return $value;
    }
    
    
    
    /*!
        \private
        Updates the data in the database with the objects current data.
        
        \out
            \$outID The ID of the updated row.
        \return
            Returns true when the object is stored.
     */
    function updateThis( &$outID )
    {
        #echo "eZNewsArticle::updateThis( \$outID=$outID )<br>";
    
        $value = false;
        
        eZNewsItem::updateThis( $outID );
        
        if( $outID )
        {
            $query =
            "
                UPDATE
                    eZNews_Article
                SET
                    AuthorText  = '%s',
                    Meta        = '%s',
                    Story       = '%s',
                    LinkText    = '%s'
                WHERE
                    ID = %s
            ";

            $query = sprintf
            (
                $query,
                $this->AuthorText,
                $this->Meta,
                $this->Story,
                $this->LinkText,
                $this->ID
            );

            $this->Database->query( $query );
            
            $value = true;
        }
        
        return $value;
    }


    
    /*!
        This function gets this objects data from the database.
        
        \in
            \$inData The name, or ID, of the object to fetch data about.
        \out
            \$outID The ID of the fetched object.
        \return
            Returns true if the data has been fetched.
     */
    function getThis( &$outID, $inData )
    {
        #echo "eZNewsArticle::getThis( \$outID=$outID, \$inData=$inData )<br>";
        $value = false;
        
        eZNewsItem::getThis( $outID, $inData );
        
        $thisID = $outID[0];
        
        if( $thisID )
        {
            $query =
            "
                SELECT
                    *
                FROM
                    eZNews_Article
                WHERE
                    ID = '%s'
            ";

            $query = sprintf( $query, $thisID );            
            $this->Database->array_query( $articleArray, $query );
            $rowsFound = count( $articleArray );

            switch ( $rowsFound )
            {
                case (0):
                    die( "Error: Article item don't exist, the ID $thisID wasn't found in the database. This shouldn't happen." );
                    break;
                case (1):
                    $this->AuthorText   = $articleArray[0][ "AuthorText" ];
                    $this->Meta         = $articleArray[0][ "Meta" ];
                    $this->Story        = $articleArray[0][ "Story" ];
                    $this->LinkText     = $articleArray[0][ "LinkText" ];
                    $value = true;
                    break;
                default:
                    die( "Error: Article items with the same ID, $thisID, was found in the database. This shouldn't happen." );
                    break;
            }
        }
        
        return $value;
    }



    /* Quick and dirty set and gets for this class */
    
    

    /*!
        Sets the author text field.
     */
    function setAuthorText( $value )
    {
        $this->dirtyUpdate();
        
        $this->AuthorText = $value;

        $this->alterState();
        
        return true;
    }


    
    /*!
        Gets the author text field. 
     */
    function authorText()
    {
        $this->dirtyUpdate();
        
        return $this->AuthorText;
    }



    /*!
        \static
        
        Returns an author text.
     */
    function createAuthorText()
    {
        $user = eZUser::currentUser();
    
        return $user->firstName() . " " . $user->lastName();
    }



    /*!
        Sets the meta field. 
     */
    function setMeta( $value )
    {
        $this->dirtyUpdate();
        
        $this->Meta = $value;

        $this->alterState();
        
        return true;
    }



    /*!
        Gets the meta field. 
     */
    function meta()
    {
        $this->dirtyUpdate();
        
        return $this->Meta;
    }


    
    /*!
        Sets the story. 
     */
    function setStory( $value )
    {
        $this->dirtyUpdate();
        
        $this->Story = $value;
        
        $this->alterState();
        
        return true;
    }


    
    /*!
        Gets the story. 
     */
    function story()
    {
        $this->dirtyUpdate();
        
        return $this->Story;
    }
    
    
    /*!
        Sets the link text.
     */
    function setLinkText( $value )
    {
        $this->dirtyUpdate();
        
        $this->LinkText = $value;

        $this->alterState();        
    }
    
    
    
    /*!
        Gets the link text.
     */
    function linkText()
    {
        $this->dirtyUpdate();
        
        return $this->LinkText;
    }
    
    
    
    /*!
        Invariant check for this object. Makes sure that the object is
        in a legal state.
        
        \return
            Returns true if the check passed.
     */
    function invariantCheck()
    {
        $value=false;
        
        eZNewsItem::invariantCheck();
               
        if( !isset( $this->Story ) )
        {
            $this->Errors[]="intl-eznews-eznewsarticle-missing-story";
        }
        
        if( !isset( $this->AuthorTex ) )
        {
            $this->Errors[]="intl-eznews-eznewsarticle-missing-author-text";
        }

        if( !count( $this->Errors ) )
        {
            $value = true;
        }
        #$this->printErrors();
        return $value;        
    }
    
    /*  This is the plain text version of the authors name,
        it is used in order to facilitate multiple authors etc. */
    var $AuthorText;
    
    /*  This is the meta information about this article. */
    var $Meta = '';
    
    /*  This is the story of the article. Will often be XML data. */
    var $Story;
    
    /*  This is the 'read more' text to go from the lead in to the story */
    var $LinkText = '';
}

?>
