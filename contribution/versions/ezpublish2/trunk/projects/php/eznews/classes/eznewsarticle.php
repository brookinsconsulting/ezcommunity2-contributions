<?
// 
// $Id: eznewsarticle.php,v 1.10 2000/10/13 12:09:34 pkej-cvs Exp $
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
        #echo "eZNewsArticle::eZNewsArticle( \$inData = $inData, \$fetch = $fetch )<br />\n";
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
        #echo "eZNewsArticle::storeThis( \$outID = $outID )<br />\n";
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
        #echo "eZNewsArticle::updateThis( \$outID=$outID )<br />\n";
    
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
        #echo "eZNewsArticle::getThis( \$outID=$outID, \$inData=$inData )<br />\n";
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
        
        Will consider the work for done if the incoming
        value equals the existing. But no change (and most
        importantly) no logging will be performed.
        
        \$in
            \$inAuthorText
        \return
            Returns true if successful.
        
     */
    function setAuthorText( $inAuthorText )
    {
        echo "eZNewsArticle::setAuthorText( \$inAuthorText = $inAuthorText )<br />\n";
        echo "\$this->AuthorText = " . $this->AuthorText . ", \$inAuthorText = $inAuthorText<br />\n";
        $value = false;
        
        $this->dirtyUpdate();
        
        $oldAuthorText = $this->AuthorText;

        if( strcmp( $inAuthorText, $oldAuthorText ) )
        {
            $this->AuthorText = $inAuthorText;

            $this->alterState();
            $value = true;

            $this->createLogItem( $this->ID . ": Author text has changed from $oldAuthorText to $inAuthorText", $this->Status );
        }
        
        return $value;
    }


    
    /*!
        Gets the author text field. 
     */
    function authorText()
    {
        #echo "eZNewsArticle::authorText()<br />\n";
        $this->dirtyUpdate();
        
        return $this->AuthorText;
    }



    /*!
        \static
        
        Returns an author text.
     */
    function createAuthorText()
    {
        #echo "eZNewsArticle::createAuthorText()<br />\n";
        $user = eZUser::currentUser();
        
        if( $user )
        {
            $name = $user->firstName() . " " . $user->lastName();
        }
        else
        {
            $name = "";
        }
        
        return $name;
    }



    /*!
        Sets the meta field.
        
        Will consider the work for done if the incoming
        value equals the existing. But no change (and most
        importantly) no logging will be performed.
        
        \in
            \$inMeta    The new meta information.
        \return
            Returns true if successful.
     */
    function setMeta( $inMeta )
    {
        #echo "eZNewsArticle::setMeta( \$inMeta = $inMeta )<br />\n";
        $value = false;
        
        $this->dirtyUpdate();
        
        $oldMeta = $this->Meta;
        
        if( strcmp( $oldMeta, $inMeta ) )
        {
            $this->Meta = $inMeta;

            $this->alterState();
            $value = true;

            $this->createLogItem( $this->ID . ": Meta has changed from $oldMeta to $inMeta", $this->Status );
        }
        
        return $value;
    }



    /*!
        Gets the meta field.
        
        \return
            Returns the meta info.
     */
    function meta()
    {
        #echo "eZNewsArticle::meta()<br />\n";
        $this->dirtyUpdate();
        
        return $this->Meta;
    }


    
    /*!
        Sets the story field.
        
        Will consider the work for done if the incoming
        value equals the existing. But no change (and most
        importantly) no logging will be performed.
        
        \in
            \$inStory    The new story.
        \return
            Returns true if successful.
     */
    function setStory( $inStory )
    {
        #echo "eZNewsArticle::setStory( \$inStory = $inStory )<br />\n";
        $value = false;
        
        $this->dirtyUpdate();
        
        $oldStory = $this->Story;
        
        if( strcmp( $oldStory, $inStory ) ) 
        {
            $this->Story = $inStory;

            $this->createLogItem( $this->ID . ": Story has changed from $oldStory to $inStory", $this->Status );
            $this->alterState();
            
            $value = true;
        }
        
        return $value;
    }


    
    /*!
        Gets the story.
        
        \return
            Returns the story.
     */
    function story()
    {
        #echo "eZNewsArticle::story()<br />\n";
        $this->dirtyUpdate();
        
        return $this->Story;
    }



    /*!
        Sets the link text.
        
        Will consider the work for done if the incoming
        value equals the existing. But no change (and most
        importantly) no logging will be performed.
        
        \in
            \$inLinkText    The new story.
        \return
            Returns true if successful.
     */
    function setLinkText( $inLinkText )
    {
        #echo "eZNewsArticle::setLinkText( \$inLinkText = $inLinkText )<br />\n";
        $value = false;
        
        $this->dirtyUpdate();
        
        $oldLinkText = $this->LinkText;
        
        if( strcmp( $oldLinkText, $inLinkText ) )
        {
            $this->LinkText = $inLinkText;

            $this->createLogItem( $this->ID . ": Link text has changed from $oldLinkText to $inLinkText", $this->Status );
            $this->alterState();
            
            $value = true;
        }
        
        return $value;
    }
    
    
    
    /*!
        Gets the link text.
        
        \return
            Returns the link text.
     */
    function linkText()
    {
        #echo "eZNewsArticle::linkText()<br />\n";
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
        #echo "eZNewsArticle::invariantCheck()<br />\n";
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
    
    
    
    /*!
        Print all the info in the object.
     */
    function printObject()
    {
        echo "eZNewsArticle::printObject()<br />\n";
        eZNewsItem::printObject();
        echo "AuthorText = " . $this->AuthorText . " \n";       
        echo "Meta = " . $this->Meta . " \n";       
        echo "Story = " . $this->Story . " \n";       
        echo "LinkText = " . $this->LinkText . " \n";       
        echo "<br />\n";
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
