<?php
// 
// $Id: eznewscategory.php,v 1.11 2000/10/13 20:55:50 pkej-cvs Exp $
//
// Definition of eZNewsCategory class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <20-Sep-2000 16:44:53 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//



//!! eZNews
//! eZNewsCategory handles eZNews categories.
/*!
    This class is used for creating categories in the hiearchy of stories/articles.
 */

/*!TODO
    add delete to existing article etc when setting new descriptions.
 */
 
include_once( "classes/ezdb.php" );
include_once( "eznews/classes/eznewsitem.php" );

class eZNewsCategory extends eZNewsItem
{
    /*!
        Constructor. Nothing special here.
     */
    function eZNewsCategory( $inData = "", $fetch = true )
    {
        #echo "eZNewsCategory::eZNewsCategory( \$inData = $inData, \$fetch = $fetch )<br>\n";

        eZNewsItem::eZNewsItem( $inData, $fetch );
    }
    
    
    
    /*!
        \private
        
        Stores a eZNewsCategory object in the database.
      
        \out
            \$outID The ID of the stored object.
        \return
            Returns true if the object is stored.
    */
    function storeThis( &$outID )
    {
         #echo "eZNews "eZNewsCategory::storeThis( \$outID=$outID )<br>\n";
        $value = false;
        
        eZNewsItem::storeThis( $outID );
        
        if( $outID )
        {
            $query =
            "
                INSERT INTO
                    eZNews_Category
                SET
                    ID                   = '%s',
                    PublicDescriptionID  = '%s',
                    PrivateDescriptionID = '%s'
            ";

            $query = sprintf
            (
                $query,
                $this->ID,
                $this->PublicDescriptionID,
                $this->PrivateDescriptionID
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
         #echo "eZNews "eZNewsCategory::updateThis( \$outID=$outID )<br>\n";
    
        $value = false;
        
        eZNewsItem::updateThis( $outID );
        
        if( $outID )
        {
            $query =
            "
                UPDATE
                    eZNews_Category
                SET
                    PublicDescriptionID  = '%s',
                    PrivateDescriptionID = '%s'
                WHERE
                    ID = %s
            ";

            $query = sprintf
            (
                $query,
                $this->PublicDescriptionID,
                $this->PrivateDescriptionID,
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
         #echo "eZNews "eZNewsCategory::getThis( \$outID=$outID, \$inData=$inData )<br>\n";
        $value = false;
        
        eZNewsItem::getThis( $outID, $inData );
        
        $thisID = $outID[0];
        
        if( $thisID )
        {
            $categoryQuery =
            "
                SELECT
                    *
                FROM
                    eZNews_Category
                WHERE
                    ID = '%s'
            ";

            $query = sprintf( $categoryQuery, $thisID );            
            $this->Database->array_query( $articleArray, $query );
            $rowsFound = count( $articleArray );

            switch ( $rowsFound )
            {
                case (0):
                    die( "Error: Article item don't exist, the ID $thisID wasn't found in the database. This shouldn't happen." );
                    break;
                case (1):
                    $this->PublicDescriptionID  = $articleArray[0][ "PublicDescriptionID" ];
                    $this->PrivateDescriptionID = $articleArray[0][ "PrivateDescriptionID" ];
                    $value = true;
                    break;
                default:
                    die( "Error: Article items with the same ID, $thisID, was found in the database. This shouldn't happen." );
                    break;
            }
        }
        
        return $value;
    }
    
    
    
    /*!
        Sets the PublicDescriptionID text field.
        
        Will consider the work for done if the incoming
        value equals the existing. But no change (and most
        importantly) no logging will be performed.

        \in
            \$inPPID    A valid article type id or name.
        \return
            Will return true if the Status was changed.
     */
    function setPublicDescriptionID( $inPPID )
    {
        echo "eZNewsCategory::setPublicDescriptionID( \$inPPID=$inPPID )<br />\n";
        $oldPPID = $this->PublicDescriptionID;
        
        $value = false;
        
        
        if( $inPPID != $oldPPID )
        {
            if( $oldPPID != 0 )
            {
                $oldObject = new eZNewsArticle( $oldPPID );
                $newObject = new eZNewsArticle( $inPPID );

                $this->dirtyUpdate();

                $this->PublicDescriptionID = $inPPID;

                $this->alterState();
                $value = true;

                $this->createLogItem( $this->ID . ": PublicDescriptionID changed from " . $oldObject->ID() . " ( " . $oldObject->name()  ." )" . " to " . $newObject->ID() . " ( " . $newObject->name()  ." )", $this->Status );
            }
            else
            {
                $newObject = new eZNewsArticle( $inPPID );

                $this->dirtyUpdate();

                $this->PublicDescriptionID = $inPPID;

                $this->alterState();
                $value = true;

                $this->createLogItem( $this->ID . ": PublicDescriptionID changed from 0 (nothing) to " . $newObject->ID() . " ( " . $newObject->name()  ." )", $this->Status );
            }
        }
        
        return true;
    }


    
    /*!
        Gets the PublicDescriptionID field.
        
        \return
            Returns the public description id
     */
    function publicDescriptionID()
    {
        #echo "eZNews "eZNewsCategory::publicDescriptionID()<br />\n";
        $this->dirtyUpdate();
        
        return $this->PublicDescriptionID;
    }
    
    
    
    /*!
        Sets the PrivateDescriptionID text field.
        
        Will consider the work for done if the incoming
        value equals the existing. But no change (and most
        importantly) no logging will be performed.

        \in
            \$inPPID    A valid article type id or name.
        \return
            Will return true if the Status was changed.
     */
    function setPrivateDescriptionID( $inPPID )
    {
        #echo "eZNews "eZNewsCategory::setPrivateDescriptionID( \$inPPID=$inPPID )<br />\n";
        $oldPPID = $this->PrivateDescriptionID;
        
        $value = false;
        
        $oldObject = new eZNewsArticle( $oldPPID );
        $newObject = new eZNewsArticle( $inPPID );
        
        if( $inPPID != $oldPPID )
        {
            $this->dirtyUpdate();
        
            $this->PrivateDescriptionID = $inPPID;

            $this->alterState();
            $value = true;
            
            $this->createLogItem( $this->ID . ": PrivateDescriptionID changed from " . $oldObject->ID() . "(" . $oldObject->name()  .")" . " to " . $newObject->ID() . "(" . $newObject->name()  .")", $this->Status );
        }
        
        return true;
    }


    
    /*!
        Gets the PrivateDescriptionID field.
        
        \return
            Returns the private description id.
     */
    function privateDescriptionID()
    {
         #echo "eZNews "eZNewsCategory::privateDescriptionID()<br />\n";
        $this->dirtyUpdate();
        
        return $this->PrivateDescriptionID;
    }
    
    
    
    /*!
        Invariant check for this object. Makes sure that the object is
        in a legal state.
        
        \return
            Returns true if the check passed.
     */
    function invariantCheck()
    {
         #echo "eZNews "eZNewsCategory::invariantCheck()<br />\n";
        $value=false;
        
        eZNewsItem::invariantCheck();

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
        echo "eZNewsCategory::printObject()<br />\n";
        eZNewsItem::printObject();
        echo "PublicDescriptionID = " . $this->PublicDescriptionID . " \n";       
        echo "PrivateDescriptionID = " . $this->PrivateDescriptionID . " \n";       
        echo "<br />\n";
    }
    
    /*  This is the public information about this category. */
    var $PublicDescriptionID = 0;
    
    /*  This is the private information about this categoyr.
        Used to give instructions, etc. to the administrators. */
    var $PrivateDescriptionID = 0;
};
?>
