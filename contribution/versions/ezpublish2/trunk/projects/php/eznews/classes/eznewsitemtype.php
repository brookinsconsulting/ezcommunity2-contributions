<?
// 
// $Id: eznewsitemtype.php,v 1.6 2000/10/01 17:36:21 pkej-cvs Exp $
//
// Definition of eZNewsItemType class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <14-Sep-2000 11:40:37 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZNews
//! eZNewsItemType handles item types for the eZNews log.
/*!
    The eZNewsItemType identifies what different kind of news items that
    can be used in a eZNews system. In addition to the typing info it
    stores information about which tables and classes are used to handle
    that type.
    
    Example of usage:
    \code
    // Example of how to include this file.
    include_once( "eznews/classes/eznewsitemtype.php" );       

    // Example on how to create an empty object:    
    $it = new eZNewsItemType();
    
    // Example on how to check if a item type exists
    
    $itemName = "create";
    
    $it = new eZNewsItemType( $itemName, true );
    
    if( $it->isCoherent() )
    {
        echo "The object " . $it->ID() . " represents the item type: " . $it->Name();
        echo " which is equal to $itemName";
    }
    
    // Example on how to create a item type.
    
    $itemName = "submit";
    
    $it = new eZNewsItemType( $itemName, true );
    
    if( !$it->isCoherent() )
    {
        $it->setName( $itemName );
        $it->setDescription( "The item has been submitted" );
        $outID = 0;
        $it->store( $outID );
        
        if( $outID != 0 )
        {
            echo "The new item type: " .  $itemName . " was stored with id $outID<br>";
        }
        else
        {
            echo "Hmm shouldn't see this...<br>";
        }
    }
    else
    {
        echo "The item type: " $itemName . " exists with id $outID<br>";
    }
    
    \endcode
    
    \sa eZNewsUtility eZNewsItem
*/
/*!TODO
    Make getThis get parents class/table if missing from this.
 */

include_once( "eznews/classes/eznewsutility.php" );       

class eZNewsItemType extends eZNewsUtility
{
    /*!
        Constructs a new eZNewsItemType object.

        If $inData is set the object's values are fetched from the
        database. $inData might be a name or an ID. If it is an object name
        then the first object with that name will be fetched. In other words
        there is no guarantee that you will get what you want using a name.
      
        \in
            \$inData    Either the ID or the Name of the row we want
            \$fetch     Should we fetch the row now, or later
    */
    function eZNewsItemType( $inData = "", $fetch = true )
    {
        eZNewsUtility::eZNewsUtility( $inData, $fetch );
    }
    
    
    /*!
        Update this eZNewsItemType object and related items.
        
        \out
            \ID     The ID returned after the insert/update.
        \return
            Returns true if we are successful.
     */
    function updateThis( &$ID )
    {
        $value = false;
        
        $query =
        "
            UPDATE
                eZNews_ItemType
            SET
                Name = '%s',
                eZClass = '%s',
                eZTable = '%s'
            WHERE
                ID = '%s'
        ";
        
        $query = sprintf
        (
            $query,
            $this->Name,
            $this->eZClass,
            $this->eZTable,
            $this->ID
        );
        
        $this->Database->query( $query );
        $insertID = mysql_insert_id();

        if( $insertID )
        {
            $outID = $insertID;
            $this->ID = $insertID;
            $value = true;
        }
        
        return $value;
    }
    
    
    
    /*!
        Store this eZNewsItemType object and related items.
        
        \out
            \$outID     The ID returned after the insert/update.
        \return
            Returns true if we are successful.
     */
    function storeThis( &$outID )
    {
        $value = false;
        
        $query =
        "
            INSERT INTO
                eZNews_ItemType
            SET
                Name = '%s',
                eZClass = '%s',
                eZTable = '%s'
        ";
        
        $query = sprintf
        (
            $query,
            $this->Name,
            $this->eZClass,
            $this->eZTable
        );
        
        $this->Database->query( $query );
        $insertID = mysql_insert_id();

        if( $insertID )
        {
            $outID = $insertID;
            $this->ID = $insertID;
            $value = true;
        }
        
        return $value;
    }
    
    
    
    /*!
        Fetches the object information from the database.
      
        \in
            \$inData    Either the ID or the Name of the row we want this object
                    to get data from.
        \out
            \$outID     An array of all IDs from the result of the query.
        \return
            Returns true if only one data item was returned.
    */
    function getThis( &$outID, &$inData )
    {
        $value = false;
        $itemTypeArray = array();
        $outID = array();
        
        if( is_numeric( $inData ) )
        {
            $query = "
                SELECT
                    *
                FROM
                    eZNews_ItemType
                WHERE ID = %s
            ";
            
            $query = sprintf( $query2, $inData );
        }
        else
        {
            $query2 = "
                SELECT
                    *
                FROM
                    eZNews_ItemType
                WHERE Name = '%s'
            ";
            
            $query = sprintf( $query2, $inData );
        }

        $this->Database->array_query( $itemTypeArray, $query );
        
        $count = count( $itemTypeArray );
        
        #echo "count=: " . $count . "<br>";
        #echo "item 0 id: " . $itemTypeArray[0][ "ID" ] . "<br>";
        #echo "item 0 description: " . $itemTypeArray[0][ "Description" ] . "<br>";
        switch( $count )
        {
            case 0:
                $this->Error[] = "intl-eznews-eznewsitemtype-no-object-found";
                break;
            case 1:
                $outID[] = $itemTypeArray[0][ "ID" ];
                $this->Name = $itemTypeArray[0][ "Name" ];
                $this->ParentID = $itemTypeArray[0][ "ParentID" ];
                $this->eZClass = $itemTypeArray[0][ "eZClass" ];
                $this->eZTable = $itemTypeArray[0][ "eZTable" ];
                $value = true;
                break;
            default:
                $this->Error[] = "intl-eznews-eznewsitemtype-more-than-one-object-found";

                foreach( $itemTypeArray as $itemType )
                {
                    $outID[] = $itemType[ "ID" ];
                }
                break;
        }
        
        return $value;
    }



        /*!
            Returns all the item types found in the database.
        
        \in
            \$inOrderBy  This is the columnname to order the returned array
                        by.
            \accepts
                ID - The id of the row in the table
                Name - Name of item
                eZClass - The eZClass of this item type.
                eZTable - The eZTable of this item type.
                \default is ID
            \$direction  This is the direction to do the ordering in
            \accepts
                asc - ascending order
                desc - descending order
                \default is asc
            \$startAt   This is the result number we want to start at
                \default is 0
            \$noOfResults This is the number of results we want.
                \default is all
        \out
            \$returnArray    This is the array of found elements
        \return
            Returns false if it fails, the error message from SQL is
            retained in $this->SQLErrors. Use getSQLErrors() to read
            the error message.
                      
     */
    function getAll( &$returnArray, $inOrderBy = "ID", $direction = "asc", $startAt = 0, $noOfResults = "" )
    {
        $this->dbInit();
        
        $returnArray = array();
        $itemTypeArray = array();
        
        $query =
        "
            SELECT
                ID
            FROM
                eZNews_ItemType
            %s
            %s
        ";
        
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        $limits = $this->createLimit( $startAt, $noOfResults );
        
        $query = sprintf( $query, $orderBy, $limits );
        
        $this->Database->array_query( $itemTypeArray, $query );
        
        for ( $i=0; $i < count( $itemTypeArray ); $i++ )
        {
            $returnArray[$i] = new eZNewsItemType( $itemTypeArray[$i][ "ID" ], 0 );
        }
        
        if( $returnArray )
        {
            $value = true;
        }
        
        return $value;
    }



    /*!
        Sets the eZClass of the object.
        
        \in
            \$inDescription    The new eZClass of this object
        \return
            Will return true if a valid string is entered.
    */
    function seteZClass( $ineZClass )
    {
        $value = false;
        
        if( is_string( $ineZClass ) )
        {
            $this->dirtyUpdate();
        
            $this->eZClass = $ineZClass;
        
            $this->alterState();
             
            $value = true;
        }

        return $value;
    }
    


    /*!
        Returns the object eZClass.
        
        \return
            Returns the eZClass of the object.
    */
    function eZClass()
    {
        $this->dirtyUpdate();
        
        return $this->eZClass;
    }



    /*!
        Sets the eZTable of the object.
        
        \in
            \$inDescription    The new eZTable of this object
        \return
            Will return true if a valid string is entered.
    */
    function seteZTable( $ineZTable )
    {
        $value = false;
        
        if( is_string( $ineZTable ) )
        {
            $this->dirtyUpdate();
        
            $this->eZTable = $ineZTable;
        
            $this->alterState();
            
            $value = true;
        }
        return $value;
    }
    


    /*!
        Returns the object eZTable.
        
        \return
            Returns the eZTable of the object.
    */
    function eZTable()
    {
        $this->dirtyUpdate();
        
        return $this->eZTable;
    }



    /*!
        Sets the ParentID of the object.
        
        \in
            \$inDescription    A valid item type id or name.
        \return
            Will return true if the item type id was changed.
    */
    function setParentID( $inParentID )
    {
        $value = false;
        
        $it = new eZNewsItemType( $inParentID, true );

        if( $it->isCoherent() )
        {
            $this->dirtyUpdate();

            $this->ParentID = $it->ID();

            $this->alterState();
            
            $value = true;
        }
        
        return $value;
    }
    


    /*!
        Returns the object's ParentID.
        
        \return
            Returns the ParentID of the object.
    */
    function parentID()
    {
        $this->dirtyUpdate();
        
        return $this->ParentID;
    }



    /*!
        Make shure that the object is in a legal state.
        All errors are stored in $this->Errors.
        
        \return
            Returns true if the object passes the check.
     */
    function invariantCheck()
    {
        $value = false;
        
        eZNewsUtility::invariantCheck();

        if( empty( $this->Description ) )
        {
            $this->Errors[] = "intl-description-required";
        }

        if( !count( $this->Errors ) )
        {
            #echo "errors " . $this->Errors[0] . "<br>";
            $value = true;
            $this->State_ = "coherent";
        }
        #echo "invariantCheck returns: " . $value . "<br>";
        return $value;
    }



    // The data members

    /// The class name of this item, empty means use parent or n/a.
    var $eZClass;
    
    /// The table where this class is stored, empty means use parent or n/a.
    var $eZTable;
    
    /// The ID of the parent of this class.
    var $ParentID;
};

?>
