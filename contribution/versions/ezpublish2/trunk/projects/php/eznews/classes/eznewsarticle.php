<?
// 
// $Id: eznewsarticle.php,v 1.1 2000/09/28 08:27:14 pkej-cvs Exp $
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

    function eZNewsArticle( $inID = -1, $fetch = true )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsArticle( $inID, $fetch ) <br>\n";
        }
        
        $this->eZNewsItem( $inID, $fetch );
        
        $query =
        "
            SELECT
                *
            FROM
               eZNews_ItemType
            WHERE
                eZClass = '%s'
        ";
        
        $query = sprintf( $query, "eZNewsArticle" );
        $this->Database->query( $query );
        $rowsFound = count( $newscategory_array );
        
        $this->ItemTypeID = $rowsFound[0][ "ID" ];
    }
    
    /*!
      Stores a eZNewsArticle object into the database.

      Returns the ID of the stored Article item.
      
      $update can be any of the command names of the items in the
      eZNews_ChangeType;
    */
    
    function store( $update = 'create' )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsArticle->store( $update ) <br>\n";
        }
        $errorMessage = eZNewsItem::store( $update );
        
        if( is_array( $errorMessage ) )
        {
            if( $errorMessage[0] != "We didn't find an authenticated session. Value stored is default." && count( $errorMessage ) != 1 )
            {
                #echo "errors errors.";
            }
        }
        
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
        
        $query = sprintf( $query, $this->ID, $this->AuthorText, $this->Meta, $this->Story, $this->LinkText );
        
        $this->Database->query( $query );
    }
    
    function getParent( $inID = -1 )
    {
        return eZNewsItem::get( $inID );
    }
    
    function get( $inID = -1 )
    {
        $returnError[] = $this->getThis( $inID );
        $returnError[] = $this->getParent( $inID );
        
        return $returnError;
    }
    
    function getThis( $inID = -1 )
    {
        unset( $returnError );
        $returnError = array();
        unset( $articleArray );
        $articleArray = array();
        
        if( $this->State_ != "Coherent" && $inID != -1)
        {
            $this->dbInit();
            
            $query =
            "
                SELECT
                    *
                FROM
                    eZNews_Article
                WHERE
                    ID = '%s'
            ";
            
            $query=sprintf( $query, $inID );
            $this->Database->array_query( $articleArray, $query );
            $rowsFound = count( $articleArray );

            switch ( $rowsFound )
            {
                case (0):
                    $this->State_ = "Don't Exist";
                    break;
                case (1):
                    $this->ID           = $articleArray[0][ "ID" ];
                    $this->AuthorText   = $articleArray[0][ "AuthorText" ];
                    $this->Meta         = $articleArray[0][ "Meta" ];
                    $this->Story        = $articleArray[0][ "Story" ];
                    $this->LinkText     = $articleArray[0][ "LinkText" ];
                    break;
                default:
                    die( "Error: Article item's with the same ID was found in the database. This shouldent happen." );
                    break;
            }
            
        
        }
        else if( $this->State_ = -1 )
        {
            $this->State_ = "Dirty";
            $returnError[] = "State changed";
        }

        return $returnError;
    }



    /* Quick and dirty set and gets for this class */
    
    

    /*!
        Sets the author text field.
     */
    function setAuthorText( $value )
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->AuthorText = $value;
        
        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }


    
    /*!
        Gets the author text field. 
     */
    function authorText()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->AuthorText;
    }



    /*!
        Sets the meta field. 
     */
    function setMeta( $value )
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->Meta = $value;
        
        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }



    /*!
        Gets the meta field. 
     */
    function meta()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->Meta;
    }


    
    /*!
        Sets the story. 
     */
    function setStory( $value )
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->Story = $value;
        
        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }


    
    /*!
        Gets the story. 
     */
    function story()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->Story;
    }
    
    function setLinkText( $value )
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->LinkText = $value;
        
        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }
    
    function linkText()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->LinkText;
    }
    
    function checkInvariant()
    {
        $returnValue=false;
        unset( $this->InvariantError ); 
               
        if( !isset( $this->Story ) )
        {
            $this->InvariantError[]="Object is missing: Story";
        }
        
        if( !isset( $this->AuthorTex ) )
        {
            $this->InvariantError[]="Object is missing: AuthorTex";
        }

        eZNewsItem::checkInvariant();
        
        if( !isset( $this->InvariantError ) )
        {
            $returnValue = true;
        }
        
        return $returnValue;        
    }
    
    function editVariables( $template )
    {
        if( $this->State_ == "Coherent" )
        {
            $template->set_var( "eZNewsArticle_ID", $this->ID );
            $template->set_var( "eZNewsArticle_ItemTypeID", $this->ItemTypeID );
            $template->set_var( "eZNewsArticle_Status", $this->Status );
            $template->set_var( "eZNewsArticle_CreatedBy", $this->CreatedBy );
            $template->set_var( "eZNewsArticle_CreatedAt", $this->CreatedAt );
            $template->set_var( "eZNewsArticle_CreationIP", $this->CreationIP );
            $template->set_var( "eZNewsArticle_Name", htmlspecialchars( $this->Name ) );
            $template->set_var( "eZNewsArticle_AuthorText", htmlspecialchars( $this->AuthorText ) );
            $template->set_var( "eZNewsArticle_Meta", htmlspecialchars( $this->AuthorText ) );
            $template->set_var( "eZNewsArticle_Story", htmlspecialchars( $this->AuthorText ) );
            $template->set_var( "eZNewsArticle_LinkText", htmlspecialchars( $this->AuthorText ) );
        }
    }
    
    /*  This is the plain text version of the authors name,
        it is used in order to facilitate multiple authors etc. */
    var $AuthorText;
    var $Meta = '';
    var $Story;
    var $LinkText = '';
}

?>
