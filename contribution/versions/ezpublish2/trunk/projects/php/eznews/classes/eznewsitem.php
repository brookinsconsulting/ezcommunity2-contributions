<?php
// 
// $Id: eznewsitem.php,v 1.23 2000/10/10 15:01:35 pkej-cvs Exp $
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
    store and get.ddd
    
    In subclasses you must call the base constructor from the sub-class, etc.
    See eZNewsArticle and eZNewsCategory for examples of this usage.

    \code
    // Example - including this class
    
    include_once( "eznews/classes/eznewsitem.php" );
    
    // Example - creating an item
    
    $object = new eZNewsItem();
    
    // Example - adding a front image to the object.
    
    $object->addImage( $ImageID, true );
    
    // Example - adding an image to the object.
    
    $object->addImage( $ImageID );
    
    // Example - adding a file to the object.
    
    $object->addFile( $FileID );
    
    // Example - adding a parent to the object.
    
    $object->addParent( $ParentID );
   
    // Example - adding a canonical parent to the object.
    
    $object->addParent( $ParentID, true );

    // Example - Storing an object.
    
    $errors = $object->errors();
    
    if( !$errors )
    {
        $object->store();
    }
    else
    {
        $i = 0;
        foreach( $errors as $error )
        {
            echo sprintf( "<p><b>Error %s:</b><br><br>%s</p>", $i, $error );
            $i++;
        }
    }
       
    \endcode

    \sa eZNewsUtility eZNewsArticle eZNewsCategory eZNewsItemType eZNewsChangeType
 */
/*!TODO
    delete must do its job.
    
    createLogItem() must do its job ;)
    
    makeCoherent() mus do its job.
    
    updateThis(), needs to be implemented, togheter with cascading updates
    of dependant tables.

    Add more configuration.

    Better documentation.
    
    New examples.
    
    Add logging
    
 */
 
include_once( "eznews/classes/eznewsutility.php" );       

class eZNewsItem extends eZNewsUtility
{
    /*!
      Constructs a new eZNewsItem object.
      
      If $inData is set the object's values are fetched from the
      database. $inData might be a name or an ID. If it is an object name
      then the first object with that name will be fetched. In other words
      there is no guarantee that you will get what you want using a name.
      
        \in
            \$inData    Either the ID or the Name of the row we want this object
                    to be created from.
            \$fetch     Should we fetch the row now, or later
    */
    function eZNewsItem( $inData = "", $fetch = true )
    {
        $this->CreatedAt = $this->createTimeStamp();
        $this->CreationIP = $this->createIP();
        $this->CreatedBy = $this->createCreatedBy();
        
        eZNewsUtility::eZNewsUtility( $inData, $fetch );
    }


    
    /*!
        \private
        
        This function will get all info about an object from the database.
        
        If get will automatically change the class to be of the same type
        as the data in the database tells it to use. (eZNews_ItemType)    
      
        \in
            \$inData    Either the ID or the Name of the row we want this object
                    to get data from.
        \out
            \$outID     An array of all IDs from the result of the query.
        \return
            Returns true if only one data item was returned and all it's related
            data.
    */
    function getThis( &$outID, &$inData )
    {
        $value = false;
        $itemArray = array();
        $outID = array();
        
        $query = "";

        if( is_numeric( $inData ) )
        {
            $query = "
                SELECT
                    *
                FROM
                    eZNews_Item
                WHERE ID = %s
            ";
            
            $query = sprintf( $query, $inData );
        }
        else
        {
            $query = "
                SELECT
                    *
                FROM
                    eZNews_Item
                WHERE Name = '%s'
            ";
            
            $query = sprintf( $query, $inData );
        }

        $this->Database->array_query( $itemArray, $query );
        $count = count( $itemArray );
        
        switch( $count )
        {
            case 0:
                $this->Error[] = "intl-eznews-eznewschangetype-no-object-found";
                break;
            case 1:
                $outID[] = $itemArray[0][ "ID" ];
                $this->ID = $itemArray[0][ "ID" ];
                $this->Name = $itemArray[0][ "Name" ];
                $this->ItemTypeID = $itemArray[0][ "ItemTypeID" ];
                $this->Status = $itemArray[0][ "Status" ];
                $this->CreatedAt = $itemArray[0][ "CreatedAt" ];
                $this->CreatedBy = $itemArray[0][ "CreatedBy" ];
                $this->CreastionIP = $itemArray[0][ "CreastionIP" ];

                // fetch more info.
                $this->getImages( $returnArray, $maxCount, $inOrderBy, $direction, $startAt, $noOfResults );
                #$this->getFiles( $returnArray, $maxCount, $inOrderBy, $direction, $startAt, $noOfResults );
                $this->getParents( $returnArray, $maxCount, $inOrderBy, $direction, $startAt, $noOfResults );
                $this->getChildren( $returnArray, $maxCount, $inOrderBy, $direction, $startAt, $noOfResults );
                $value = true;
                break;
            default:
                $this->Error[] = "intl-eznews-eznewsitem-more-than-one-object-found";

                foreach( $itemArray as $item )
                {
                    $outID[] = $item[ "ID" ];
                }
                break;
        }
        
        return $value;
    }



    /*!
        Creates a log entry.
        
        Creates a log entry when logging is on. This is
        used to create information about changes done to
        the object, epsecially automatic changes.
        
        \in
            \$changeText    The information about this type in
                            plaintext.
            \$changeType    An eZNews_ChangeType.ID which is
                            used for categorization of the
                            change.
        \return
            Returns true if logging is on and  a log item
            was created.
     */
    function createLogItem( $changeText, $changeType  )
    {
        $value = false;
        $doIt = false;
        
        if( $this->isLogging )
        {
            if( ereg( "^intl-", $changeText ) )
            {
                // do some language stuff...
            }
            
            if( $changeText && $this->isChangeType( $changeType ) )
            {
                $doIt = true;
            }

            $creator = $this->createCreatedBy();
            
            if( $doIt )
            {
                // do or stuff...
                $value = true;
            }
        }
        
        return $value;
    }



    /*!
        Creates the relationship between this object and one image.
        
        Will fail if an image is marked as a front image, but an
        front image already exists.
        
        If the object is dirty it will not accept any new references.
        
        This funciton needs store() afterwards if you want to apply
        the change to the database.
        
        Only one image can be the front image.
        
        \in
            \$ImageID   The id of the image that shall be added

            \$isFrontImage  If the incoming image is a front image
                            set this to true. Default is false.
        
        \return
            Returns true if an reference is made.
     */
    function setImage( $ImageID,  $isFrontImage = false )
    {
        $value = true;
        
        if( !$this->isDirty() )
        {
            foreach( $this->ImageID as $existingImage )
            {
                if( $existingImage == $ImageID )
                {
                    $value = false;
                    $this->Errors[] = "intl-eznews-eznewsitem-image-reference-exists";
                }
            }
            
            // Check if the image actually exists in the db...
            
            include_once( "ezimagecatalogue/classes/ezimage.php" );
            
            $image = new eZImage( $ImageID );
            
            if( $image->isCoherent() == false )
            {
                $value = false;
                $this->Errors[] = "intl-eznews-eznewsitem-image-doesnt-exist";
            }
            
            if( $value == true )
            {
                if( $isFrontImage == true )
                {
                    if( $this->isFrontImage )
                    {
                        $value = false;
                        $this->Errors[] = "intl-eznews-eznewsitem-another-fron-image-set";
                    }
                    else
                    {
                        $this->isFrontImage = $ImageID;
                    }
                }
            }
            
            if( $value == true )
            {
                $this->ImageID[] = $ImageID;
                $this->alterState();
            }
        }
        
        return $value;
    }
    
    function removeImage( $ImageID )
    {
        $value = false;
        return $value;
    }
    
    
    /*!
        Creates the relationship between this object and one file.
        
        If the object is dirty it will not accept any new references.
        
        Needs store() afterwards.
        
        \in
            \$FileID A legal ID of a file stored in the database.
        
        \return
            Returns true if an reference is made.
     */
    function setFile( $FileID )
    {
        $value = true;
        if( !$this->isDirty() )
        {
            foreach( $this->FileID as $existingFile )
            {
                if( $existingFile == $FileID )
                {
                    $value = false;
                    $this->Errors[] = "intl-eznews-eznewsitem-file-reference-exists";
                }
            }

            if( $value == true )
            {
                $this->FileID[] = $FileID;            
                $this->alterState();
            }
        }
        return $value;
    }



    /*!
        Creates the relationship between this object and one log entry.
        
        If the object is dirty it will not accept any new references.
        
        Needs store() afterwards.
        
        \in
            \$ChangeTicketID A legal ID or name of a change ticket.
        
        \return
            Returns true if an reference is made.
     */
    function setLog( $ChangeTicketID )
    {
        $value = true;
        if( !$this->isDirty() )
        {
            foreach( $this->ChangeTicketID as $existingLog )
            {
                if( $existingLog == $ChangeTicketID )
                {
                    $value = false;
                    $this->Errors[] = "intl-eznews-eznewsitem-log-reference-exists";
                }
            }
            $include_once( "eznews/classes/eznewschangeticket.php" );

            $ct = new eZNewsChangeTicket( $ChangeTicketID, true );
    
            if( $ct->isCoherent() == false )
            {
                $value = false;
                $this->Errors[] = "intl-eznews-eznewsitem-changeticket-doesnt-exist";
            }

            if( $value == true )
            {
                $this->LogID[] = $ChangeTicketID;            
                $this->alterState();
            }
        }
        return $value;
    }


    
    /*!
        Creates the relationship between this object and its parent.
        
        Will fail if an parent is marked as canonical, but a
        canonical parent already exists.
        
        If the object is dirty it will not accept any new references.
        
        Needs store() afterwards.
        
        Only one parent can be the canonical parent.
        
        \in
            \$ParentID A legal name or ID of a eznews_item entry.
            \$isCanonical This new parent should be the new canonical.
        
        \return
            Returns true if an reference is made.
     */
    function setParent( $ParentID, $isCanonical = false )
    {
        #echo "eZNewsItem::setParent( \$ParentID = $ParentID,\$isCanonical = $isCanonical )<br>";
        $value = true;
        
        if( !is_numeric( $this->isCanonical ) && !$this->isDirty() )
        {
            foreach( $this->ParentID as $existingParent )
            {
                if( $existingParent == $ParentID )
                {
                    $value = false;
                    $this->Errors[] = "intl-eznews-eznewsitem-parent-reference-exists";
                }
            }

            $item = new eZNewsItem( $ParentID );
            
            if( $item->isCoherent() == false )
            {
                $value = false;
                $this->Errors[] = "intl-eznews-eznewsitem-parent-doesnt-exist";
            }

            if( $value == true )
            {

                if( $isCanonical == true )
                {
                    if( $this->isCanonical )
                    {
                        $value = false;
                        $this->Errors[] = "intl-eznews-eznewsitem-canonical-parent-reference-exists";
                    }
                    else
                    {
                        $this->isCanonical = $ParentID;
                    }
                }
            }
            
            if( $value == true )
            {
                $this->ParentID[] = $ParentID;
                $this->alterState();
            }
        }
        
        return $value;
    }
    
    
    
    /*!
        \private
        
        Stores the image data.
        
        Store the relationship between this object and it's images.
     */
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

        $query2 =
        "
            INSERT INTO
                eZNews_ItemImagePreference
            SET
                ItemID   = '%s',
                isFrontImage  = 'Y'
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
            
            if( $this->isFrontImage == $ImageID )
            {
                $query = sprintf
                (
                    $query2,
                    $this->ID
                );
                
                $this->Database->query( $query );
            }
            
        }
    }
    

    
    /*!
        \private
        
        Updates the image data.
        
        Updates the relationship between this object and it's images.
        
        Note: This function will not DELETE the images, merely remove
        the relationship between this item and the image.
     */
    function updateImages()
    {
        $query =
        "
            DELETE FROM
                eZNews_ItemImage
            WHERE
                ItemID   = '%s'
        ";

        $query = sprintf( $query, $this->ID );
        
        $this->Database->query( $query );
        
        $this->storeImages();
    }
    

    
    /*!
        \private
        
        Stores the log
         
        Store the relationship between this object and it's logs.
     */
    function storeLogs()
    {
        $query =
        "
            INSERT INTO
                eZNews_ItemLog
            SET
                ItemID   = '%s',
                ChangeTicketID  = '%s'
        ";
        
        foreach( $this->ChangeTicketID as $ChangeTicketID )
        {
            $query = sprintf
            (
                $query,
                $this->ID,
                $this->$ChangeTicketID
            );

            $this->Database->query( $query );
        }
    }



    /*!
        \private
        
        Updates the log
         
        Update the relationship between this object and it's logs.
     */
    function updateLogs()
    {
        $query =
        "
            DELETE FROM
                eZNews_ItemLog
            WHERE
                ItemID   = '%s'
        ";
        
        $query = sprintf( $query, $this->ID );
        
        $this->Database->query( $query );
        
        $this->storeLogs();
    }



    /*!
        \private
        
        Store the relationship between this object and its files.
     */
    function storeFiles()
    {
        $query =
        "
            INSERT INTO
                eZNews_ItemFile
            SET
                ItemID   = '%s',
                FileID  = '%s'
        ";
        
        foreach( $this->FileID as $FileID )
        {
            $query = sprintf
            (
                $query,
                $this->ID,
                $FileID
            );

            $this->Database->query( $query );
        }
    }



    /*!
        \private
        
        Update the relationship between this object and its files.
        
        Note: This function will not DELETE the files, just remove
        the relationship between this item and the files.
     */
    function updateFiles()
    {
        $query =
        "
            DELETE FROM
                eZNews_ItemFile
            WHERE
                ItemID   = '%s'
        ";
        
        $query = sprintf( $query, $this->ID );

        $this->Database->query( $query );
        
        $this->storeFiles();
    }



    /*!
        \private
        
        Store the relationship between this object and its parents.
     */
    function storeParents()
    {
        $this->dbInit();

        $query =
        "
            INSERT INTO
                eZNews_Hiearchy
            SET
                ItemID   = '%s',
                ParentID  = '%s',
                isCanonical = 'N'
        ";

        $query2 =
        "
            INSERT INTO
                eZNews_Hiearchy
            SET
                ItemID   = '%s',
                ParentID  = '%s',
                isCanonical = 'Y'
        ";

        foreach( $this->ParentID as $ParentID )
        {

            if( $this->isCanonical == $ParentID )
            {
                $query = sprintf
                (
                    $query2,
                    $this->ID,
                    $ParentID
                );
            }
            else
            {
                $query = sprintf
                (
                    $query,
                    $this->ID,
                    $ParentID
                );
            }
            
            $this->Database->query( $query );
        }
    }



    /*!
        \private
        
        Update the relationship between this object and its parents.
     */
    function updateParents()
    {
        $this->dbInit();

        $query =
        "
            DELETE FROM
                eZNews_Hierachy
            WHERE
                ItemID = %s
        ";
       
        $query = sprintf ( $query, $this->ID );
        
        $this->Database->query( $query );
        
        $this->storeParents();

    }



    /*!
        Private funciton
        
        Store this eZNewsItem object and related items.
        
        \out
            \$outID     The ID returned after the insert/update.
            
        \return
            Returns true if we are successful.
     */
    function storeThis( &$outID )
    {
        #echo "eZNewsItem::storeThis( \$outID )<br>";
        
        $value = false;
        
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
        $insertID = mysql_insert_id();
        
        if( $insertID )
        {
            $outID = $insertID;
            $this->ID = $insertID;
            $stored = true;
        }
        
        if( $stored )
        {
            $this->storeParents();
            $this->storeFiles();
            $this->storeImages();
            $this->createLogItem( "intl-eznews-eznewsitem-store-created", "create" );
            $this->storeLogs();
        }
        
        if( $stored )
        {
            $this->hasChanged = false;
            $this->makeCoherent();
            $value = true;
        }
        
        return $value;
    }
    



    /*!
        Private funciton
        
        Update this eZNewsItem object and related items.
        
        \out
            \$outID     The ID returned after the insert/update.
            
        \return
            Returns true if we are successful.
     */
    function updateThis( &$outID )
    {
        #echo "eZNewsItem::updateThis( \$outID )<br>";
        
        $value = false;
        
        $query =
        "
            UPDATE
                eZNews_Item
            SET
                ItemTypeID = '%s',
                Name       = '%s',
                Status     = '%s',
                CreatedBy  = '%s',
                CreatedAt  = '%s',
                CreationIP = '%s'
            WHERE
                ID = '%s'
        ";
        
        $query = sprintf
        (
            $query,
            $this->ItemTypeID,
            $this->Name,
            $this->Status,
            $this->CreatedBy,
            $this->CreatedAt,
            $this->CreationIP,
            $this->ID
        );

        $this->Database->query( $query );
        $insertID = mysql_insert_id();
        #echo "insertid " . $insertID . "<br>";
        if( $insertID )
        {
            $outID = $insertID;
            $this->ID = $insertID;
            $stored = true;
        }
        
        if( $stored )
        {
            $this->updateParents();
            $this->updateFiles();
            $this->updateImages();
            $this->createLogItem( "intl-eznews-eznewsitem-store-created", "change" );
            $this->updateLogs();
        }
        
        if( $stored )
        {
            $this->hasChanged = false;
            $this->makeCoherent();
            $value = true;
        }
        
        return $value;
    }
    
    
    
    /*!
        Deletes an item from the database.
        
        \return
            Returns true if the item exists and has been deleted.
     */
    function delete()
    {
        $value = false;
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            
        }
        
        return $value;
    }
    
    /*!
        This function will return all IDs of the items in the db which doesn't have a parent.
        
        \in
            \$inOrderBy  This is the columnname to order the returned array
                        by.
            \accepts
                ID - The id of the row in the table
                Name - Name of item
                CreatedAt - SQL timestamp
                CreatedBy - eZUser.ID
                CreationIP - ip / port
                Status  - eZNews_ChangeType.ID
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
            \$maxCount      The maximum number of items by this query. Needed to know max if limit is used.

        \return
            Returns false if it fails, the error message from SQL is
            retained in $this->SQLErrors. Use getSQLErrors() to read
            the error message.
                      
     */
    function getOrphans( &$returnArray, $inOrderBy = "ID", $direction = "asc" , $startAt = 0, $noOfResults = ""  )
    {
        $this->dbInit();
        $value = false;
        
        $returnArray = array();
        $itemArray = array();
        
        $query =
        "
            SELECT
                count(*)
            FROM
                eZNews_Item
            LEFT JOIN
                eZNews_Hiearchy
            ON
                eZNews_Item.ID = eZNews_Hiearchy.ItemID
            WHERE
                eZNews_Hiearchy.ItemID IS NULL
        ";
        
        $this->Database->array_query( $itemArray, $query );        
        $maxCount = $itemArray[0][0];


        $query =
        "
            SELECT
                eZNews_Item.*
            FROM
                eZNews_Item
            LEFT JOIN
                eZNews_Hiearchy
            ON
                eZNews_Item.ID = eZNews_Hiearchy.ItemID
            WHERE
                eZNews_Hiearchy.ItemID IS NULL
            %s
            %s
        ";
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        $limits = $this->createLimit( $startAt, $noOfResults );
        
        $query = sprintf( $query, $orderBy, $limits );
        
        $this->Database->array_query( $itemArray, $query );
        
        for( $i = 0; $i < count( $itemArray ); $i++ )
        {   
            $returnArray[$i] = new eZNewsItem( $itemArray[$i][ "ID" ], 0 );
        }
        
        if( $returnArray )
        {
            $value = true;
        }
        
        return $value;
    }



    /*!
        This function will return all IDs of the items in the db which doesn't have any children.
        
        \in
            \$inOrderBy  This is the columnname to order the returned array
                        by.
            \accepts
                ID - The id of the row in the table
                Name - Name of item
                CreatedAt - SQL timestamp
                CreatedBy - eZUser.ID
                CreationIP - ip / port
                Status  - eZNews_ChangeType.ID
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
            \$maxCount      The maximum number of items by this query. Needed to know max if limit is used.
        \return
            Returns false if it fails, the error message from SQL is
            retained in $this->SQLErrors. Use getSQLErrors() to read
            the error message.
                      
     */
    function getWidows( &$returnArray, &$maxCount, $inOrderBy = "ID", $direction = "asc" , $startAt = 0, $noOfResults = "" )
    {
        #echo "getWidows( \&\$returnArray, \$inOrderBy = \"$inOrderBy\", 
        #\$direction = \"$direction\" , \$startAt = \"$startAt\", \$noOfResults = \"$noOfResults\" )
        #<br>";
        $this->dbInit();
        $value = false;
        
        $returnArray = array();
        $itemArray = array();

        $query =
        "
            SELECT
                count(*)
            FROM
                eZNews_Item
            LEFT JOIN
                eZNews_Hiearchy
            ON
                eZNews_Item.ID = eZNews_Hiearchy.ParentID
            WHERE
                eZNews_Hiearchy.ParentID IS NULL
        ";
        
        $this->Database->array_query( $itemArray, $query );        
        $maxCount = $itemArray[0][0];
        

        $query =
        "
            SELECT
                eZNews_Item.*
            FROM
                eZNews_Item
            LEFT JOIN
                eZNews_Hiearchy
            ON
                eZNews_Item.ID = eZNews_Hiearchy.ParentID
            WHERE
                eZNews_Hiearchy.ParentID IS NULL
            %s
            %s
        ";
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        $limits = $this->createLimit( $startAt, $noOfResults );
        
        $query = sprintf( $query, $orderBy, $limits );

        $this->Database->array_query( $itemArray, $query );
        
        for( $i = 0; $i < count( $itemArray ); $i++ )
        {   
            $returnArray[$i] = new eZNewsItem( $itemArray[$i][ "ID" ], 0 );
        }
        
        if( $returnArray )
        {
            $value = true;
        }
        
        return $value;
    }



    /*!
        This function will return all IDs of the children of this class.
        
        \in
            \$inOrderBy  This is the columnname to order the returned array
                        by.
            \accepts
                ID - The id of the row in the table
                Name - Name of item
                CreatedAt - SQL timestamp
                CreatedBy - eZUser.ID
                CreationIP - ip / port
                Status  - eZNews_ChangeType.ID
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
            \$maxCount      The maximum number of items by this query. Needed to know max if limit is used.
        \return
            Returns false if it fails, the error message from SQL is
            retained in $this->SQLErrors. Use getSQLErrors() to read
            the error message.
                      
     */
    function getChildren( &$returnArray, &$maxCount, $inOrderBy = "ID", $direction = "asc" , $startAt = 0, $noOfResults = ""  )
    {
        global $childrenMax;
        
        $this->dbInit();
        $value = false;
        
        $returnArray = array();
        $itemArray = array();
        
        $query =
        "
            SELECT
                count(*)
            FROM
                eZNews_Item AS Item,
                eZNews_Hiearchy AS Hier
            WHERE
                Hier.ParentID = '%s'
            AND
                Hier.ItemID = Item.ID
            AND
                Item.Status != '%s'
            AND
                Item.Status != '%s'
        ";
        
        include_once( "eznews/classes/eznewschangetype.php" );
        $temporaryID = new eZNewsChangeType( "temporary", true );
        $deleteID = new eZNewsChangeType( "delete", true );
        
        $query = sprintf( $query, $this->ID, $temporaryID->ID(), $deleteID->ID() );        
        $this->Database->array_query( $itemArray, $query );        
        $maxCount = $itemArray[0][0];
        
        $query =
        "
            SELECT
                Item.*,
                Hier.isCanonical
            FROM
                eZNews_Item AS Item,
                eZNews_Hiearchy AS Hier
            WHERE
                Hier.ParentID = '%s'
            AND
                Hier.ItemID = Item.ID
            AND
                Item.Status != '%s'
            AND
                Item.Status != '%s'
            %s
            %s
        ";

        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        $limits = $this->createLimit( $startAt, $noOfResults );
        
        $query = sprintf( $query, $this->ID, $temporaryID->ID(), $deleteID->ID(), $orderBy, $limits );
        
        $this->Database->array_query( $itemArray, $query );
        
        for( $i = 0; $i != count( $itemArray ); $i++ )
        {   
            $returnArray[$i] = new eZNewsItem( $itemArray[$i][ "ID" ], 0 );
            $this->ParentID[$i] = $itemArray[$i][ "ID" ];
            
            if( $itemArray[$i][ "isCanonical" ] == 'Y' )
            {
                $this->isCanonical = $itemArray[$i][ "isCanonical" ];
            }
        }
        
        if( $returnArray )
        {
            $value = true;
        }
        
        return $value;
    }
    
    
    
    /*!
        This function will return an array of items with an array of children of that type.
        
        \in
            \$inOrderBy  This is the columnname to order the returned array
                        by.
            \accepts
                ID - The id of the row in the table
                Name - Name of item
                CreatedAt - SQL timestamp
                CreatedBy - eZUser.ID
                CreationIP - ip / port
                Status  - eZNews_ChangeType.ID
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
            \$returnArray    This is the array of arrays of found elements. The first level is keyed
                             by the item types of it's children. The item "Types" in the first level
                             is an array of all the types returned. For each type there is a count
                             column.
            \$maxCount      The maximum number of items by this query. Needed to know max if limit is used.
        \return
            Returns false if it fails, the error message from SQL is
            retained in $this->SQLErrors. Use getSQLErrors() to read
            the error message.
                      
     */
    function getChildrenGroups( &$returnArray, &$maxCount, $inOrderBy = "ID", $direction = "asc" , $startAt = 0, $noOfResults = ""  )
    {
        $this->dbInit();
        $value = false;
        $continue = false;
        
        $returnArray = array();
        $categoryArray = array();
        $itemArray = array();
        
        $query =
        "
            SELECT
                count(*)
            FROM
                eZNews_Item AS Item,
                eZNews_Hiearchy AS Hier,
                eZNews_ItemType AS Type
            WHERE
                Type.eZClass LIKE 'eZNews%%' 
            AND
                Type.ID = Item.ItemTypeID
            AND
                Hier.ParentID = %s
            AND
                Item.ID = Hier.ItemID 
            AND
                Item.Status != '%s'
            AND
                Item.Status != '%s'
            GROUP BY Type.Name
        ";
        
        include_once( "eznews/classes/eznewschangetype.php" );
        $temporaryID = new eZNewsChangeType( "temporary", true );
        $deleteID = new eZNewsChangeType( "delete", true );
        
        $query = sprintf( $query, $this->ID, $temporaryID->ID(), $deleteID->ID() );        
        $this->Database->array_query( $itemArray, $query );        
        $maxCount = $itemArray[0][0];

        $query =
        "
            SELECT
                Type.Name AS Name
            FROM
                eZNews_Item AS Item,
                eZNews_Hiearchy AS Hier,
                eZNews_ItemType AS Type
            WHERE
                Type.eZClass LIKE 'eZNews%%' 
            AND
                Type.ID = Item.ItemTypeID
            AND
                Hier.ParentID = %s
            AND
                Item.ID = Hier.ItemID 
            AND
                Item.Status != '%s'
            AND
                Item.Status != '%s'
            GROUP BY Type.Name
        ";

        $query = sprintf( $query, $this->ID, $temporaryID->ID(), $deleteID->ID() );

        $this->Database->array_query( $categoryArray, $query );
         
        $count = count( $categoryArray );
        for( $i = 0; $i != $count; $i++ )
        {
            $typeName = $categoryArray[$i][ "Name" ];
            $returnArray[ "Types" ][] = $typeName;
            $returnArray[ $typeName ] = array();

            $query =
            "
                SELECT
                    count(*)
                FROM
                    eZNews_Item AS Item,
                    eZNews_Hiearchy AS Hier,
                    eZNews_ItemType AS Type
                WHERE
                    Type.Name LIKE '%s'
                AND
                    Type.eZClass LIKE 'eZNews%%' 
                AND
                    Type.ID = Item.ItemTypeID
                AND
                    Hier.ParentID = %s
                AND
                    Item.ID = Hier.ItemID
                AND
                    Item.Status != '%s'
                AND
                    Item.Status != '%s'
            ";

            include_once( "eznews/classes/eznewschangetype.php" );
            $temporaryID = new eZNewsChangeType( "temporary", true );
            $deleteID = new eZNewsChangeType( "delete", true );
        
            $query = sprintf( $query, $typeName, $this->ID, $temporaryID->ID(), $deleteID->ID() );        
            $this->Database->array_query( $itemArray, $query );        
            $returnArray[ $typeName ][ "count" ] = $itemArray[0][ "count" ];

            $query =
            "
                SELECT
                    Item.*
                FROM
                    eZNews_Item AS Item,
                    eZNews_Hiearchy AS Hier,
                    eZNews_ItemType AS Type
                WHERE
                    Type.Name LIKE '%s'
                AND
                    Type.eZClass LIKE 'eZNews%%' 
                AND
                    Type.ID = Item.ItemTypeID
                AND
                    Hier.ParentID = %s
                AND
                    Item.ID = Hier.ItemID
                AND
                    Item.Status != '%s'
                AND
                    Item.Status != '%s'
                %s
                %s
            ";
            $orderBy = $this->createOrderBy( $inOrderBy, $direction );
            $limits = $this->createLimit( $startAt, $noOfResults );
        
            include_once( "eznews/classes/eznewschangetype.php" );
            $temporaryID = new eZNewsChangeType( "temporary", true );
            $deleteID = new eZNewsChangeType( "delete", true );
        
            $query = sprintf( $query, $typeName, $this->ID, $temporaryID->ID(), $deleteID->ID(), $orderBy, $limits );
            
            $this->Database->array_query( $itemArray, $query );   
            
            $count2 = count( $itemArray );
            
            for( $j = 0; $j != $count2; $j++ )
            {   
                $returnArray[ $typeName ][$j] = new eZNewsItem( $itemArray[$j][ "ID" ], false );
            }        
        }
        
        
        
        if( $returnArray )
        {
            $value = true;
        }
        
        return $value;
    }
    
    
    
    /*!
        This function will return all IDs of the parents of this class.
        
        \in
            \$inOrderBy  This is the columnname to order the returned array
                        by.
            \accepts
                ID - The id of the row in the table
                Name - Name of item
                CreatedAt - SQL timestamp
                CreatedBy - eZUser.ID
                CreationIP - ip / port
                Status  - eZNews_ChangeType.ID
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
            \$maxCount      The maximum number of items by this query. Needed to know max if limit is used.
        \return
            Returns false if it fails, the error message from SQL is
            retained in $this->SQLErrors. Use getSQLErrors() to read
            the error message.
                      
     */
    function getParents( &$returnArray, &$maxCount, $inOrderBy = "ID", $direction = "asc" , $startAt = 0, $noOfResults = ""  )
    {
        $this->dbInit();
        $value = false;

        $returnArray = array();
        $itemArray = array();

        $query =
        "
            SELECT
                count(*)
            FROM
                eZNews_Item AS Item,
                eZNews_Hiearchy AS Hier
            WHERE
                Hier.ItemID = '%s'
            AND
                Hier.ParentID = Item.ID
            AND
                Item.Status != '%s'
            AND
                Item.Status != '%s'
        ";
        
        include_once( "eznews/classes/eznewschangetype.php" );
        $temporaryID = new eZNewsChangeType( "temporary", true );
        $deleteID = new eZNewsChangeType( "delete", true );
        
        $query = sprintf( $query, $this->ID, $temporaryID->ID(), $deleteID->ID() );

        $this->Database->array_query( $itemArray, $query );        
        $maxCount = $itemArray[0][0];
        
        $query =
        "
            SELECT
                Item.*,
                Hier.isCanonical
            FROM
                eZNews_Item AS Item,
                eZNews_Hiearchy AS Hier
            WHERE
                Hier.ItemID = '%s'
            AND
                Hier.ParentID = Item.ID
            AND
                Item.Status != '%s'
            AND
                Item.Status != '%s'
            %s
            %s
        ";
        
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        $limits = $this->createLimit( $startAt, $noOfResults );

        $query = sprintf( $query, $this->ID, $temporaryID->ID(), $deleteID->ID(), $orderBy, $limits );
        #echo "$query<br>id: " . $this->ID . "<br>name: " . $this->Name . "<br>";

        $this->Database->array_query( $itemArray, $query );
        
        for( $i = 0; $i < count( $itemArray ); $i++ )
        {   
            $returnArray[$i] = new eZNewsItem( $itemArray[$i][ "ID" ], 0 );
            $this->ParentID[$i] = $itemArray[$i][ "ID" ];
            
            if( $itemArray[$i][ "isCanonical" ] == 'Y' )
            {
                $this->isCanonical = $itemArray[$i][ "isCanonical" ];
            }
        }
        
        if( $returnArray )
        {
            $value = true;
        }
        
        return $value;
    }



    /*!
        This function will return all IDs of the images associated with his item.
        
        \in
            \$inOrderBy  This is the columnname to order the returned array
                        by.
            \accepts
                ID - The id of the row in the table
                Name - Name of item
                CreatedAt - SQL timestamp
                CreatedBy - eZUser.ID
                CreationIP - ip / port
                Status  - eZNews_ChangeType.ID
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
            \$maxCount      The maximum number of items by this query. Needed to know max if limit is used.
        \return
            Returns false if it fails, the error message from SQL is
            retained in $this->SQLErrors. Use getSQLErrors() to read
            the error message.
                      
     */
    function getImages( &$returnArray, &$maxCount, $inOrderBy = "ID", $direction = "asc" , $startAt = 0, $noOfResults = ""  )
    {
        $this->dbInit();
        $value = false;

        $returnArray = array();
        $itemArray = array();

        $query =
        "
            SELECT
                count(*)
            FROM
                eZNews_ItemImage
            WHERE
                ItemID = %s
        ";
        
        $query = sprintf( $query, $this->ID );        
        $this->Database->array_query( $itemArray, $query );        
        $maxCount = $itemArray[0][0];
        
        $query =
        "
            SELECT
                ImageID
            FROM
                eZNews_ItemImage
            WHERE
                ItemID = %s
            %s
            %s
        ";
        
        $query2 =
        "
            SELECT
                *
            FROM
                eZNews_ItemImagePreference
            WHERE
                ID   = '%s'
        ";

        #$orderBy = $this->createOrderBy( $inOrderBy, $direction );
        $limits = $this->createLimit( $startAt, $noOfResults );

        $query = sprintf( $query, $this->ID, $orderBy, $limits );
        $this->Database->array_query( $itemArray, $query );
        
        for( $i = 0; $i < count( $itemArray ); $i++ )
        {
            include_once( "ezimagecatalogue/classes/ezimage.php" );
            $returnArray[$i] = new eZImage( $itemArray[$i][ "ImageID" ], 0 );
            $this->ImageID[] = $returnArray[$i];

            $query2 = sprintf( $query2, $returnAray[$i] );
            $this->Database->array_query( $itemArray, $query2 );
            
            if( $preferenceArray[0][ "isFrontImage" ] == 'Y' )
            {
                $this->isFrontImage = $returnAray[$i];
            }
        }
        
        if( $returnArray )
        {
            $value = true;
        }
        
        return $value;
    }



    /*!
        Start or stop object logging.
        
        \in
            \$check True enables logging, false disables it.
                    Default is true.
        \return
            Returns the new status.
     */
    function doLogging( $check = true )
    {
        if( $check == false )
        {
            $this->isLogging = false;
        }
        else
        {
            $this->isLogging = true;
        }
        
        return isLoggin();
    }
    


    /*!
        Start or stop creator check.
        
        \in
            \$check True enables creator checking, false disables it.
                    Default is true.
        \return
            Returns the new status.
     */
    function doCreatorCheck( $check = true )
    {
        if( $check == false )
        {
            $this->checkCreator = false;
        }
        else
        {
            $this->checkCreator = true;
        }
        
        return $this->checkCreator();
    }

    

    /*!
        Should we check out who the creator is?
       
        \return
            Returns true if we should check the
            creator.
     */
    function checkCreator()
    {
        $value = false;
        
        if( $this->checkCreator == true )
        {
            $value = true;
        }
        
        return $value;
    }



    /*!
        Check if this object is logging it's changes,
       
        \return
            Returns true if we are logging.
     */
    function isLogging()
    {
        $value = false;
        
        if( $this->isLogging == true )
        {
            $value = true;
        }
        
        return $value;
    }
    
    
    
    /*!
        Check if the string is a valid change type string.
        
        \in
            $changeType   A change type name.
        \return
            Returns true if this is a change type.
     */
    function isChangeType( &$changeType )
    {
        include_once( "eznews/classes/eznewschangetype.php" );       

        $value = false;
        
        $changeType = new eZNewsChangeType( $changeType, $fetch );
        
        $value = $changeType->isCoherent();
        
        return $value;
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
        
        if( is_numeric( $this->isCanonical ) )
        {
            $count = count( $this->ParentID );
            if( $count == 0 )
            {
                $this->Errors[] = "intl-canonical-exists-parents-dont";
            }            
        }
        
        if( is_numeric( $this->isFrontImage ) )
        {
            $count = count( $this->ImageID );
            
            if( $count == 0 )
            {
                $this->Errors[] = "intl-frontimage-exists-images-dont";
            }
        }

        if( empty( $this->ItemTypeID ) )
        {
            $this->Errors[] = "intl-itemtypeid-required";
        }

        if( $this->CreatedBy == 0 && $this->checkCreator() )
        {
            $this->Errors[] = "intl-createdby-required";
        }

        if( !isset( $this->ChangeTicketID ) && $this->isLogging() )
        {
            $this->Errors[] = "intl-logitem-required";
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
    
    
    
    /*!
        This function will try to make this object coherent.
        It will discard any ids of logs, images, files and
        parents which doesn't exist.
        
        It will create one id for an image and/lr parent if
        the isFrontImage or the isCanonical variables are set.
        
        \return
            Returns true if an invariantCheck passes at the end
            of the function.
     */
    function makeCoherent()
    {
        if( eZNewsUtility::makeCoherent() )
        {
        
        // do as invariant check
        // is isCanonical is set and no ParentIDs exists
        // then check if iscanonical id exists adn set
        // a parentID as that id.
        
        // the same for isfrontimage.
        
        //
        }
        return $this->invariantCheck();
    }



    /*!
        Sets the CreatedAt of the object.
        
        \in
            \$inDescription    The new CreatedAt of this object
        \return
            Will always return true.
    */
    function setCreatedAt( $inCreatedAt )
    {
        $this->dirtyUpdate();
        
        $this->CreatedAt = $inCreatedAt;
        
        $this->alterState();
        
        return true;
    }
    


    /*!
        Returns the object CreatedAt.
        
        \return
            Returns the CreatedAt of the object.
    */
    function createdAt()
    {
        $this->dirtyUpdate();
        
        return $this->CreatedAt;
    }



    /*!
        Sets the item type id of the object.
        
        \in
            \$inDescription    A valid item type id or name.
        \return
            Will return true if the item type id was changed.
    */
    function setItemTypeID( $inItemTypeID )
    {
        $value = false;
        
        include_once( "eznews/classes/eznewsitemtype.php" );
        $it = new eZNewsItemType( $inItemTypeID, true );

        if( $it->isCoherent() )
        {
            $this->dirtyUpdate();
        
            $this->ItemTypeID = $it->ID();
        
            $this->alterState();
        
            $value = true;
        }
        
        return $value;
    }
    


    /*!
        Returns the object ItemTypeID.
        
        \return
            Returns the ItemTypeID of the object.
    */
    function getItemTypeID()
    {
        $this->dirtyUpdate();
        echo $this->ItemTypeID . "        buahdklfjaøl<br>";
        return $this->ItemTypeID;
    }



    /*!
        Returns the object CreatedAt.
        
        \return
            Returns the CreatedAt of the object.
    */
    function getCreatedAt()
    {
        $this->dirtyUpdate();
        
        return $this->CreatedAt;
    }



    /*!
        Sets the Status of the object.
        
        \in
            \$inDescription    A valid change type id or name.
        \return
            Will return true if the Status was changed.
    */
    function setStatus( $inItemTypeID )
    {
        $value = false;
        
        #echo "set status: $inStatus <br>";
        include_once( "eznews/classes/eznewschangetype.php" );
        $ct = new eZNewsChangeType( $inItemTypeID, true );

        if( $ct->isCoherent() )
        {
            $this->dirtyUpdate();
        
            $this->Status = $ct->ID();
        
            $this->alterState();
        
            $value = true;
        }
        
        return $value;
    }
    


    /*!
        Returns the object Status.
        
        \return
            Returns the Status of the object.
    */
    function changeStatus()
    {
        $this->dirtyUpdate();
        
        return $this->Status;
    }



    // The data members
    
    /// The object''s ItemTypeID
    var $ItemTypeID = 0;
    
    /// The object''s Status
    var $Status = 0;
    
    /// The object''s CreatedBy
    var $CreatedBy = 0;
    
    /// The object''s CreatedAt
    var $CreatedAt = 0;
    
    /// The object''s CreationIP
    var $CreationIP = 0;
    
    /// The ID of the canonical parent.
    var $isCanonical;
    
    /// The ID of the front image.
    var $isFrontImage;
    
    /// All Image IDs
    var $ImageID = array();
    
    /// All Parent IDs
    var $ParentID = array();
    
    /// All file IDs
    var $FileID = array();

    /// All Log IDs
    var $ChangeTicketID = array();    



    // Object preferences

    /// Logging
    var $isLogging = true;
    
    /// Check creator id
    var $checkCreator = true;
    
    

};
?> 
