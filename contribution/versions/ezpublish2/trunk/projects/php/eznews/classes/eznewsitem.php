<?php
// 
// $Id: eznewsitem.php,v 1.11 2000/09/28 13:07:22 pkej-cvs Exp $
//
// Definition of eZNewsItem class
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
//! eZNewsItem handles eZNews items.
/*!
    An eZNewsItem object is a base class for all items which can be stored
    in the eZNews hiearchy. It is not an abstract class, you can create
    objects of this type and store them in the db without any problems.
    
    All parts of an eZNews hiearchy is an eZNewsItem, even the categories
    which are used for creating the hiearchy. This enables us to treat any
    object in the database uniformly, thus very few special cases are needed.
    
    This class will therefore provide most functions needed for storing an
    object, logging it's use, etc. All classes which inherit from this class
    will only need to add set/get functions for it's extra data, a constructor,
    store and get.
    
    In subclasses you must call the base constructor from the sub-class, etc.
    See eZNewsArticle and eZNewsCategory for examples of this usage.

    TODO:
    <ul>
        <li>Clean up code.
        <li>Change getSubItemCounts to work more properly.
        <li>Move more code into sub-functions.
        <li>Integrate more dynamic info into the system.
        <li>Add more checking.
        <li>Add more configuration.
        <li>Clean up error handling.
        <li>Better documentation.
        <li>New examples.
    </ul>
    \code
    \endcode

    \sa eZNewsArticle, eZNewsCategory
 */

    function printArray( &$array )
    {
        if( is_array( $array ) )
        {
            foreach( $array as $item )
            {
                if( is_array( $item )  )
                {
                    printArray( $item );
                }
                else
                {
                    echo htmlspecialchars( $item ) . "<br>";
                }
            }
        }
        else    
        {
            echo htmlspecialchars( $array ) . " a<br>";
        }
    }


include_once( "classes/ezdb.php" );
include_once( "classes/ezsession.php" );       
include_once( "eznews/classes/eznewschangetype.php" );       

class eZNewsItem
{
    /*!
      Constructs a new eZNewsItem object.

      If $inID is set the object's values are fetched from the
      database.
    */
    function eZNewsItem( $inData = "", $fetch = true )
    {
    
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem( $inID, $fetch ) <br>\n";
        }
        
        $inName = "";
        $inID = -1;
        
        if( is_numeric( $inData ) )
        {
            $inID = $inData;
        }
        else
        {
            $inName = $inData;
        }
        
        $this->dbInit();

        $this->IsConnected = false;
        
        
        if( $inID != -1  )
        {
            $this->ID = $inID;

            if( $fetch == true )
            {
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
            }
        }
        else if( $inName != "" )
        {
            $this->Name = $inName;

            if( $fetch == true )
            {
                $this->getByName( $inName );
            }
            else
            {
                $this->State_ = "Dirty";
            }            
        }
        else
        {
            $this->State_ = "New";
            $this->CreatedAt = time();
        }
    }

    function storeImages()
    {
        $this->dbInit();

        $query =
        "
            INSERT INTO
                eZNews_ItemImage
            SET
                ItemID   = '%s',
                ImageID  = '%s'
        ";

        foreach( $this->ImageID as $ImageID )
        {
            $query = sprintf
            (
                $query,
                $this->ID,
                $ImageID
            );

            $this->Database->query( $query );
        }
    }
    
    function storeImage( $ImageID,  $thumbnail )
    {
        $this->dbInit();

        $query =
        "
            INSERT INTO
                eZNews_ItemImage
            SET
                ItemID   = '%s',
                ImageID  = '%s'
        ";
        
        $query = sprintf
        (
            $query,
            $this->ID,
            $ImageID
        );

        $this->Database->query( $query );
    }
    
    function storeFile( $FileID )
    {
        $this->dbInit();

        $query =
        "
            INSERT INTO
                eZNews_ItemImage
            SET
                ItemID   = '%s',
                FileID  = '%s'
        ";
        
        $query = sprintf
        (
            $query,
            $this->ID,
            $FileID
        );

        $this->Database->query( $query );
    }
    
    function storeParent( $ParentID, $canonical = 'N' )
    {
        $this->dbInit();

        $query =
        "
            INSERT INTO
                eZNews_Hiearchy
            SET
                ItemID   = '%s',
                ParentID  = '%s',
                isCanonical = '%s'
        ";
        
        $query = sprintf
        (
            $query,
            $this->ID,
            $ParentID,
            $canonical
        );

        $this->Database->query( $query );
    }
    
    function storeFiles()
    {
    }
    
    function storeThis()
    {
        $this->dbInit();

        $query =
        "
            INSERT INTO
                eZNews_Item
            SET
                ItemTypeID = '%s',
                Name       = '%s',
                Status     = '%s',
                CreatedBy  = '%s',
                CreatedAt  = '%s',
                CreationIP = '%s'
        ";

        $query = sprintf
        (
            $query,
            $this->ItemTypeID,
            $this->Name,
            $this->Status,
            $this->CreatedBy,
            $this->CreatedAt,
            $this->CreationIP
        );

        $this->Database->query( $query );

        $this->ID = mysql_insert_id();
        $this->get( $this->ID );
        $this->storeFiles();
        $this->storeImages();
        #$this->createLogEntry();
    }

    /*!
      Stores a eZNewsItem object into the database.

      Returns the ID of the stored News item.
      
      $update can be any of the command names of the items in the
      eZNews_ChangeType;
    */
    
    function store( $update = 'create' )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->store( \$update = $update ) <br>\n";
        }
        unset( $errorMessage );
        $errorMessage = array();
        $changeType = array();
        
        $query =
        "
            SELECT
                *
            FROM
                eZNews_ChangeType
            WHERE
                CommandName = '%s'
        ";
        
        $query = sprintf( $query, $update );
        $this->Database->array_query( $changeType, $query );
        $count=count( $changeType );

        if( $count != 1 )
        {
            die( "Very bad error, we should have found something named $update in eZNews_ChangeType.CommandName" );
        }
       
        
        if( $this->checkInvariant() == true )
        {
            $reason = strtolower( $changeType[ "Name" ] );
            
            if( $this->OverrideCreator == false )
            {
                $GLOBALS[ "AuthenticatedSession" ];

                $this->CreatedBy = 0;
                $errorMessage[] = "We didn't find an authenticated session. Value stored is default.";

                $session = new eZSession();

                if( $session->get( $AuthenticatedSession ) == 0 )
                { 
                    $this->CreatedBy = $session->userID();
                    echo $this->CreatedBy;
                }
            }
            else
            {
                $this->CreatedBy = "0";
            }
            
            if( empty( $this->CreatedAt ) )
            {
                include_once( "classes/ezdatetime.php" );
                $time = gmdate( "YmdHis", time());
                $this->CreatedAt = $time;
            }

            $this->CreationIP = $GLOBALS[ "REMOTE_ADDR" ] . "/" .$GLOBALS[ "REMOTE_PORT" ];

            $this->storeThis();

            if($this->Log == true)
            {
                $query =
                "
                    SELECT
                        *
                    FROM
                        eZNews_ChangeType
                    WHERE
                        Name = '%s'
                ";

                $query = sprintf( $query, $reason );
                $result = $this->Database->query( $query );
                $row = mysql_fetch_row( $result );
                $this->ChangeTypeID = $row[0];

                $query =
                "
                    INSERT INTO
                        eZNews_ChangeTicket
                    SET
                        ChangeTypeID = '%s',
                        ChangeText   = 'Class eZNewsItem %s this item',
                        ChangedBy    = '%s',
                        ChangeIP     = '%s'
                ";


                $query = sprintf( $query, $this->ChangeTypeID, $reason, $this->CreatedBy, $this->CreationIP );

                $this->Database->query( $query );
                $this->ChangeTicketID = mysql_insert_id();

                $query = 
                "
                    INSERT INTO
                        eZNews_ItemLog
                    SET
                        ItemID         = '%s',
                        ChangeTicketID = '%s'
                ";


                $query = sprintf($query, $this->ID, $this->ChangeTicketID );
                $this->Database->query( $query );
            }
        }
        else
        {
            $errorMessage = "Perhaps the invariant check failed, or perhaps you supplied a wrong argument ($reason)?";
        }
        
        return $errorMessage;
    }



    function getImages()
    {
        $this->dbInit();

        $query =
        "
            SELECT
                *
            FROM
                eZNews_ItemImage
            WHERE
                ItemID   = '%s'
        ";

        $query = sprintf
        (
            $query,
            $this->ID
        );

        $this->Database->array_query( $imageArray, $query );
        $rowsFound = count( $imageArray );
        
        foreach( $imageArray as $Image )
        {
            $this->ImageID[] = $Image["ImageID"];
        }
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $inId = -1 )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem::get( inID = $inID )<br>";
        }
        $returnValue = false;
        
        $itemArray = array();
        
        $this->dbInit();
        
        if( $inId != -1 )
        {
            $query = 
            "
                SELECT
                    *
                FROM
                    eZNews_Item
                WHERE
                    ID='%s'
            ";

            $query = sprintf( $query, $inId );
            
            $this->Database->array_query( $itemArray, $query );
            $rowsFound = count( $itemArray );
            
            if( $GLOBAL["NEWSDEBUG"] == true )
            {
                printArray( $itemArray );
            }
            
            switch ( $rowsFound )
            {
                case ( 0 ):
                    $this->State_ = "Don't Exist";
                    break;
                case ( 1 ):
                    $this->ID         = $itemArray[0][ "ID" ];
                    $this->Name       = $itemArray[0][ "Name" ];
                    $this->ItemTypeID = $itemArray[0][ "ItemTypeID" ];
                    $this->Status     = $itemArray[0][ "Status" ];
                    $this->CreatedBy  = $itemArray[0][ "CreatedBy" ];
                    $this->CreatedAt  = $itemArray[0][ "CreatedAt" ];
                    $this->CreationIP = $itemArray[0][ "CreationIP" ];
                    $this->Views      = $itemArray[0][ "Views" ];
                    $this->Status     = $itemArray[0][ "Status" ];
                    $this->State_     = "Coherent";
                    $returnValue      = "true";
                    break;
                default:
                    die( "Error: News item's with the same ID was found in the database. This shouldent happen." );
                    break;
            }
            
            $this->getImages();
                 
        }
        else if( $this->Name != "" )
        {
            $this->getByName( $this->Name );
        }
        else
        {
            $this->State_ = "Dirty";
        }
        
        if( $this->ItemTypeID != "0" )
        {
            $query =
            "
                SELECT
                    Name
                FROM
                    eZNews_ItemType
                WHERE
                    ID='%s'
            ";
            $query = sprintf( $query, $this->ItemTypeID );
            
            $this->Database->array_query( $itemArray, $query );
            $rowsFound = count( $itemArray );

            $this->polymorphSelf( $itemArray[0]["Name"] );
        }
        
        return $returnValue;
    }
    
    function getClass()
    {
        $this->dbInit();
        
        $query =
        "
            SELECT
                Type.eZClass
            FROM
                eZNews_Item AS Item,
                eZNews_ItemType AS Type
            WHERE
                Item.ItemTypeID = Type.ID
            AND
                Item.ID = '%s'
        ";
        
        $query = sprintf( $query, $this->ID );
        $this->Database->array_query( $itemTypeArray, $query );
        $rowsFound = count( $itemTypeArray );
        return $itemTypeArray[0]["eZClass"];
    }
    
    function polymorphSelf( $newClass )
    {   
        $returnValue = false;
        
        $path = "eznews/classes/" . strtolower( $newClass ) . ".php";        
        
        include_once( "eznews/classes/eznewsitemtype.php" );
        $itemType = new eZNewsItemType( $this->itemTypeID() );

        if( $newClass == $itemType->eZClass() )
        {        
            include_once( $path );
            $this = new $newClass( $this->ID, true );
            $returnValue = true;
        }
        
        return $returnValue;
    }
    
    function getByName( $name )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->getByName( \$name = $name ) <br>\n";
        }
        $this->dbInit();

        $query = 
        "
            SELECT
                *
            FROM
                eZNews_Item
            WHERE
                Name='%s'
        ";
        
        $query = sprintf( $query, $name );

        $this->Database->array_query( $itemArray, $query );
        $rowsFound = count( $itemArray );

        return $this->get( $itemArray[0][ "ID" ] );
    }
    
    function getChangeLog()
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->getChangeLog( ) <br>\n";
        }
        
        $this->dbInit();
        
        $returnArray = array();
        
        $query = 
        "
            SELECT
                Ticket.ID
            FROM
                eZNews_ChangeTicket AS Ticket,
                eZNews_ItemLog AS Log
            WHERE
                Log.ItemID LIKE %s
            ORDER BY
                Ticket.ChangedAt
        ";

        $query = sprintf( $query, $this->ID);
        
        $this->Database->array_query( $categoryArray, $query );
        
        for( $i = 0; $i < count( $categoryArray ); $i++ )
        {
            $returnArray[$i] = new eZNewsChangeType( $categoryArray[$i]["ID"], 0 );
        }
        
        return $returnArray;
    }



    function getChangeTypes( )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->getChangeTypes( ) <br>\n";
        }
        
        $this->dbInit();
        
        $returnArray = array();
        
        $query =
        "
            SELECT
                *
            FROM
                eZNews_ChangeType
            ORDER BY
                Name
        ";

        $this->Database->array_query( $changeTypeArray, $query );
        
        for( $i = 0; $i<count($changeTypeArray); $i++ )
        {
            $returnArray[$i] = new eZNewsChangeType( $changeTypeArray[$i][ "ID" ], 0 );
        }
        
        return $returnArray;
    }



    /*!
        Utility function, creates an order by clause.
     */
    function createOrderBy( $inOrderBy, $direction )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->createOrderBy( \$inOrderBy = $inOrderBy, \$direction = $direction ) <br>\n";
        }
        unset( $returnString );
        
        if( !empty( $inOrderBy ) )
        {
            $returnString = "ORDER BY " . $this->OrderBy[ $inOrderBy ] . " " . $this->OrderBy[ $direction ];
        }
        
        return $returnString;
    }



    /*!
      Returns all the news items found in the database.

      The categories are returned as an array of eZNewsItem objects.
      
      $inOrderBy may be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>creatorID
      <li>date
      </ul>
      
      $direction may be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getAll( $inOrderBy = "name", $direction="forward" )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->createOrderBy( \$inOrderBy = $inOrderBy, \$direction = $direction ) <br>\n";
        }
        $this->dbInit();
        
        $returnArray = array();
        $itemArray = array();
        
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        
        $query = 
        "
            SELECT
                *
            FROM
                eZNews_Item
            WHERE
                ID='%s'
            %s
        ";

        $query = sprintf( $query, $orderBy );
        
        $this->Database->array_query( $itemArray, $query );
        
        for( $i = 0; $i < count( $itemArray ); $i++ )
        {
            $returnArray[$i] = new eZNewsItem( $itemArray[$i][ "ID" ], true );
        }
        
        return $returnArray;
    }



    /*!
      Returns all the news items found in the database which are visible.

      The news items are returned as an array of eZNewsItem objects.

      $OrderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>creatorID
      <li>date
      </ul>
      
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
      
      $status is any value as found in the eZNews_ChangeType table.
    */
    function getAllByStatus( $inOrderBy = "name", $direction = "forward", $status = "Published" )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->createOrderBy( \$inOrderBy = $inOrderBy, \$direction = $direction, \$status = $status ) <br>\n";
        }
        $this->dbInit();
        
        $returnArray = array();
        $itemArray = array();
        
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        
        $query =
        "
            SELECT
                Item.ID AS ID, 
                Item.Name AS Name,
                Item.CreatedAt AS CreatedAt,
                Item.CreatedBy AS CreatedBy,
                Item.CreationIP AS CreationIP,
                Item.Status AS Status
            FROM
                eZNews_Item AS Item,
                eZNews_ChangeType AS CT
            WHERE
                Item.Status = CT.ID
            AND
                CT.Name = '%s'
            %s
        ";
        
        $query = sprintf( $query, $orderBy );
        
        $this->Database->array_query( $itemArray, $query, $status );
        
        for( $i = 0; $i < count( $itemArray ); $i++ )
        {
            $returnArray[$i] = new eZNewsItem( $itemArray[$i][ "ID" ], 0 );
        }
        
        return $returnArray;
    }



    /*!
      Returns all the news items found in the database which aren't visible.

      The news items are returned as an array of eZNewsItem objects.

      $OrderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>creatorID
      <li>date
      </ul>
      
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getAllExceptByStatus( $inOrderBy = "name", $direction = "forward", $status = "Published" )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->getAllExceptByStatus( \$inOrderBy = $inOrderBy, \$direction = $direction, \$status = $status ) <br>\n";
        }
        $this->dbInit();
        
        $returnArray = array();
        $itemArray = array();
        
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        
        $query =
        "
            SELECT
                Item.ID AS ID, 
                Item.Name AS Name,
                Item.CreatedAt AS CreatedAt,
                Item.CreatedBy AS CreatedBy,
                Item.CreationIP AS CreationIP,
                Item.Status AS Status
            FROM
                eZNews_Item AS Item,
                eZNews_ChangeType AS CT
            WHERE
                Item.Status = CT.ID
            AND
                CT.Name != '%s'
            %s
        ";
        
        $query = sprintf( $query, $orderBy, $staus );
        
        $this->Database->array_query( $itemArray, $query );
        
        for( $i = 0; $i < count( $itemArray ); $i++ )
        {
            $returnArray[$i] = new eZNewsItem( $itemArray[$i][ "ID" ], 0 );
        }
        
        return $returnArray;
    }



    /*!
      Returns all the parent items to this item.

      The news items are returned as an array of eZNewsItem objects.
      
      $OrderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>creatorID
      <li>date
      </ul>
      
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getAllParents( $inOrderBy = "name", $direction = "forward" )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->getAllParents( \$inOrderBy = $inOrderBy, \$direction = $direction ) <br>\n";
        }
        $this->dbInit();
        
        $returnArray = array();
        $itemArray = array();
        
        $query =
        "
            SELECT
                Hier.ParentID AS ID,
                Item.Name AS Name,
                Item.CreatedAt AS CreatedAt,
                Item.CreatedBy AS CreatedBy,
                Item.CreationIP AS CreationIP,
                Item.Status AS Status
            FROM
                eZNews_Hiearchy AS Hier,
                eZNews_Item AS Item
            WHERE
                Hier.ItemID = '%s'
            AND
                Item.ID = Hier.ParentID
            %s
        ";
        
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        
        $query = sprintf( $query, $this->ID, $orderBy );
        
        $this->Database->array_query( $itemArray, $query );
        
        for( $i = 0; $i < count( $itemArray ); $i++ )
        {
            $returnArray[$i] = new eZNewsItem( $itemArray[$i][ "ID" ], 0 );
        }
        
        return $returnArray;
    }
    
    function getAllChildren( $inOrderBy = "name", $direction = "forward" )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->getAllChildren( \$inOrderBy = $inOrderBy, \$direction = $direction ) <br>\n";
        }
        $this->dbInit();

        $returnArray = array();
        $itemArray = array();
        
        $query =
        "
            SELECT
                Hier.ItemID AS ID,
                Item.Name AS Name,
                Item.CreatedAt AS CreatedAt,
                Item.CreatedBy AS CreatedBy,
                Item.CreationIP AS CreationIP,
                Item.Status AS Status
            FROM
                eZNews_Hiearchy AS Hier,
                eZNews_Item AS Item
            WHERE
                Hier.ParentID = '%s'
            AND
                Item.ID = Hier.ItemID
            %s
        ";

        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        
        $query = sprintf( $query, $this->ID, $orderBy );
        
        $this->Database->array_query( $itemArray, $query );
        
        for( $i = 0; $i < count( $itemArray ); $i++ )
        {   
            $returnArray[$i] = new eZNewsItem( $itemArray[$i][ "ID" ], 0 );
        }
        
        return $returnArray;
    }




    function getSubItemCounts( $inOrderBy = "name", $direction = "forward", $fetch = true )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->getSubItemCounts( \$inOrderBy = $inOrderBy, \$direction = $direction ) <br>\n";
        }
        $this->dbInit();
        
        $returnArray = array();
        $itemArray = array();
        
        $query =
        "
            SELECT
                *
            FROM 
                eZNews_ItemType
            WHERE
                eZClass LIKE 'eZNews%%'
            %s
        ";
        
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        
        $query = sprintf( $query, $orderBy );
        $this->Database->array_query( $itemArray, $query );

        for( $i = 0; $i < count( $itemArray ); $i++ )
        {
            if( $itemArray[$i][ "Name" ] != "Undefined" )
            {
                $returnArray[] = array( "TypeName" => $itemArray[$i][ "Name" ] );
            }
        }
        
        $query =
        "
            SELECT
                Item.ID AS ID,
                Item.Name AS Name,
                Item.CreatedAt AS CreatedAt,
                Item.CreatedBy AS CreatedBy,
                Item.CreationIP AS CreationIP,
                Item.Status AS Status,
                Type.Name AS Type
            FROM
                eZNews_Hiearchy AS Hier,
                eZNews_Item AS Item,
                eZNews_ItemType AS Type
            WHERE
                Type.ID = Item.ItemTypeID
            AND
                Hier.ParentID = '%s'
            AND
                Type.eZClass LIKE 'eZNews%%'
            AND
                Item.ID = Hier.ItemID
            %s
        ";
        
        $query = sprintf( $query, $this->ID, $orderBy );
        
        $this->Database->array_query( $itemArray, $query );
        
        for( $i = 0; $i < count( $itemArray ); $i++ )
        {
            $j = 0;
            while( $itemArray[$i][ "Type" ] != $returnArray[$j][ "TypeName" ] )
            {
                $j++;
            }
            
            $returnArray[$j][ "Item" . $i ] = new eZNewsItem( $itemArray[$i][ "ID" ], $fetch );
        }
        
        return $returnArray;
    }



    /*!
      Returns the canonical parent item to this item.

      The news items are returned as an array of eZNewsItem objects.
      
      $OrderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>creatorID
      <li>date
      </ul>
      
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getCanonicalParent( $inOrderBy = "name", $direction = "forward", $fetch = true )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->getCanonicalParent( \$inOrderBy = $inOrderBy, \$direction = $direction ) <br>\n";
        }
        $this->dbInit();
        
        $i = 0;
        $count = 1;
        $id = $this->ID;
        
        while( $count == 1 )
        {
            $query =
            "
                SELECT
                    Hier.ParentID AS ID,
                    Item.Name AS Name
                FROM
                    eZNews_Hiearchy AS Hier,
                    eZNews_Item AS Item
                WHERE
                    Item.ID = Hier.ParentID
                AND
                    Hier.ItemID = '%s'
                AND
                    Hier.isCanonical = 'Y'
                %s
            ";
            
            $orderBy = $this->createOrderBy( $inOrderBy, $direction );
            
            $query = sprintf( $query, $id, $orderBy );
            $this->Database->array_query( $itemArray, $query );
            $count=count( $itemArray );
            
            if( $count == 1 )
            {            
                $returnArray[$i] = new eZNewsItem( $itemArray[0][ "ID" ], $fetch );
                $id = $itemArray[0][ "ID" ];
            }
            $i++;
        }
        return $returnArray;
    }



    /*!
      Returns the object ID of the news item. This is the unique ID stored in the database.
    */
    function id()
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem->id(  ) <br>\n";
        }
        $returnValue = 0;
        
        if( $this->State_ != "New" )
        {
            $returnValue=$this->ID;
        }
       
        return $returnValue;
    }



    /*!
      Returns the name of the news item.
    */
    function name()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->Name;
    }

    /*!
      Sets the name of the news item.
    */
    function setName( $value )
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->Name = $value;
        
        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }


    /*!
      Sets the user id of creator.
    */
    function setCreatedBy( $value )
    {
        $returnValue = false;
        
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        if( $this->OverrideCreator == true )
        {
            $this->CreatedBy = $value;
            $returnValue = true;
        
            if( $this->State_ != "New" )
            {
                $this->State_ == "Altered";
            }
        }
        
        return $returnValue;
    }



    /*!
      Returns the name of the creator
    */
    function createdBy()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->CreatedBy;
    }
    /*!
      Returns the item type id of this item
    */
    function itemTypeID()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->ItemTypeID;
    }
    
    /*!
      Sets the type of the news item.
    */
    function setItemTypeID( $value )
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->ItemTypeID = $value;
        
        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }

    /*!
      Returns the image ids associated with this item.
    */
    function imageID()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->ImageID;
    }
    
    /*!
      Sets an image id.
    */
    function setImageID( $value )
    {
        $returnValue = false;
        
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->ImageID[] = $value;
        echo "storing image id " . $value . "<br>";
        $returnValue = true;

        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
        
        return $returnValue;
    }
 
    /*!
      Returns the status of  this item.
    */
    function status()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->Status;
    }
    
    /*!
      Sets the status of this item.
    */
    function setStatus( $value )
    {
        $returnValue = false;
        
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->Status= $value;
        $returnValue = true;

        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
        
        return $returnValue;
    }

    /*!
        Allow creator override.
     */
    function overrideCreator()
    {
        $this->OverrideCreator = true;
    }



    /*!
        Disable creator override.
     */
    function NoOverrideCreator()
    {
        $this->OverrideCreator = false;
    }

    /*!
        Start logging changes.
     */
    function startLog()
    {
        $this->Log = true;
    }

    /*!
        Stop logging changes.
     */
    function stopsLog()
    {
        $this->Log = false;
    }


    function checkInvariant()
    {
        $returnValue=false;
        unset( $this->InvariantError ); 
               
        if( !isset( $this->Name ) )
        {
            $this->InvariantError[]="Object is missing: Name";
        }
        
        if( !isset( $this->ItemTypeID ) )
        {
            $this->InvariantError[]="Object is missing: ItemTypeID";
        }

        if( !isset( $this->CreatedBy ) && $this->OverrideCreator == true)
        {
            $this->InvariantError[]="Object is missing: CreatedBy";
        }

        if( !isset( $this->InvariantError ) )
        {
            $returnValue = true;
        }
        return $returnValue;        
    }

    function objectInfo()
    {
        $output;
        if( ( $this->checkInvariant() == true ) || true )
        {
            $output = sprintf("<TR><TD WIDTH=5%%>%s</TD><TD>%s</TD><TD WIDTH=5%%>%s</TD><TD WIDTH=5%%>%s</TD><TD WIDTH=10%%>%s</TD><TD WIDTH=10%%>%s</TD><TD WIDTH=3%%>%s</TD></TR>",
                    $this->ID,
                    $this->Name,
                    $this->ItemTypeID,
                    $this->CreatedBy,
                    $this->CreatedAt,
                    $this->CreationIP,
                    $this->Status
                   );
            if( $this->Log == true )
            {
                // here comes a print statement for sub objects in the log.
            }
        }
        else
        {
            foreach ( $this->InvariantError as $something )
            {
                echo $something . "<br>";       
            }
        }
        
        return $output;
    }
    
    function objectHeader()
    {
        return sprintf("<TABLE WIDTH=100%%><TR><TD WIDTH=5%%>%s</TD><TD>%s</TD><TD WIDTH=5%%>%s</TD><TD WIDTH=5%%>%s</TD><TD WIDTH=10%%>%s</TD><TD WIDTH=10%%>%s</TD><TD WIDTH=3%%>%s</TD></TR></TABLE><hr><TABLE WIDTH=100%%>",
                "ID",
                "Name",
                "TypeID",
                "CreatedBy",
                "CreatedAt",
                "CreationIP", 
                "Vis"
               );
    }
    function objectFooter()
    {
        return sprintf("</TABLE>");
    }

    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsItem::dbInit(  ) <br>\n";
            echo "isConnected is: " . $this->IsConnected . "<br>";
        }
        if( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "eZNewsMain" );
            $this->IsConnected = true;
        }
    }
    
    function state()
    {
        return $this->State_;
    }
    
    function editVariables( $template )
    {
        if( $this->State_ == "Coherent" )
        {
            eZNewsItem::editVariables( $template );
            
            $template->set_var( "eZNewsItem_ID", $this->ID );
            $template->set_var( "eZNewsItem_ItemTypeID", $this->ItemTypeID );
            $template->set_var( "eZNewsItem_Status", $this->Status );
            $template->set_var( "eZNewsItem_CreatedBy", $this->CreatedBy );
            $template->set_var( "eZNewsItem_CreatedAt", $this->CreatedAt );
            $template->set_var( "eZNewsItem_CreationIP", $this->CreationIP );
            $template->set_var( "eZNewsItem_Name", htmlspecialchars( $this->Name ) );
        }
    }
    
    var $ID;
    var $ItemTypeID = 0;
    var $Name;
    var $Status = 3;
    var $CreatedBy;
    var $CreatedAt;
    var $CreationIP;
    var $Logs = array();
    var $ImageID = array();
    var $FileID = array();
    
    /// Error variables
    
    var $InvariantError = array();
    
    var $OrderBy = array(
        "none" => "",
        "name" => "Name",
        "id" => "ID",
        "visibility" => "Status",
        "type" => "ItemTypeID",
        "authorID" => "CreatedBy",
        "date" => "CreatedAt",
        "forward" => "ASC",
        "reverse" => "DESC",
        );
    
    /// Preferences
    
    /// Turn on/off logging of changes to articles. Default is on.
    var $Log = true;
    var $OverrideCreator = true;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has a database connection, false if not.
    var $IsConnected;
}

?>
