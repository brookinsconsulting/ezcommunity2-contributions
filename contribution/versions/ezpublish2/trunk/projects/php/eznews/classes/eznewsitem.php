<?php
// 
// $Id: eznewsitem.php,v 1.14 2000/10/01 13:39:28 pkej-cvs Exp $
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
    // Example - creating an item
    
    $object = new eZNewsItem();
    
    // Example - adding a front image to the object.
    
    $object->referenceImage( $ImageID, true );
    
    // Example - adding an image to the object.
    
    $object->referenceImage( $ImageID );
    
    // Example - adding a file to the object.
    
    $object->referenceFile( $FileID );
    
    // Example - adding a parent to the object.
    
    $object->referenceParent( $ParentID );
   
    // Example - adding a canonical parent to the object.
    
    $object->referenceParent( $ParentID, true );

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

    \sa eZNewsArticle, eZNewsCategory
 */
/*!TODO
    getThis() must also  fetch info about referenced objects.
    
    createLogItem() must do its job ;)
    
    updateThis(), needs to be implemented, togheter with cascading updates
    of dependant tables.

    Change getSubItemCounts to work more properly.
    
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
      
        \variables
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
        Private function
        
        Fetches the object information from the database.
      
        \variables
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
        
        if( is_numeric( $inData ) )
        {
            $query = "
                SELECT
                    *
                FROM
                    eZNews_NewsItem
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
                    eZNews_NewsItem
                WHERE Name = %s
            ";
            
            $query = sprintf( $query2, $inData );
        }

        $this->Database->array_query( $itemArray, $query );

        if ( count( $itemArray ) > 1 )
        {
            $this->Error[] = "intl-eznews-eznewsitem-more-than-one-object-found";
            
            foreach( $itemArray as $item )
            {
                $outID[] = $item[ "ID" ];
            }
        }
        else if( count( $itemArray ) == 1 )
        {
            $this->ID = $itemArray[0][ "ID" ];
            $this->Name = $itemArray[0][ "Name" ];
            $this->ItemTypeID = $itemArray[0][ "ItemTypeID" ];
            $this->Status = $itemArray[0][ "Status" ];
            $this->CreatedAt = $changeTypeArray[0][ "CreatedAt" ];
            $this->CreatedBy = $changeTypeArray[0][ "CreatedBy" ];
            $this->CreastionIP = $changeTypeArray[0][ "CreastionIP" ];
            
            // fetch more info.
            
            $value = true;
        }
        else
        {
            $this->Error[] = "intl-eznews-eznewsitem-no-object-found";
        }
        
        return $value;
    }



    /*!
        Creates a log entry.
        
        Creates a log entry when logging is on. This is
        used to create information about changes done to
        the object, epsecially automatic changes.
        
        \variables
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
        
        \varaiables
        \in
            \$ImageID   The id of the image that shall be added
            \$isFrontImage  If the incoming image is a front image
                            set this to true. Default is false.
        \return
            Returns true if an reference is made.
     */
    function referenceImage( $ImageID,  $isFrontImage = false )
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
    
    
    
    /*!
        Creates the relationship between this object and one file.
        
        If the object is dirty it will not accept any new references.
        
        Needs store() afterwards.
        
        Returns true if an reference is made.
     */
    function referenceFile( $FileID )
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
        
        Returns true if an reference is made.
     */
    function referenceLog( $ChangeTicketID )
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
        
        Returns true if an reference is made.
     */
    function referenceParent( $ParentID, $isCanonical = false )
    {
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
        Private function
        
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
                eZNews_ItemImagePreferences
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
        Private function
        
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
        Private function
        
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
        Private function
        
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
        Private funciton
        
        Store this eZNewsItem object and related items.
        
        \variables
        \out
            \$outID     The ID returned after the insert/update.
        \return
            Returns true if we are successful.
     */
    function storeThis( &$outID )
    {
        #echo "eZNewsItem::storeThis( \$outID, \$copy = $copy )<br>";
        
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
        This function will get all info about an object from the database.
        
        If get will automatically change the class to be of the same type
        as the data in the database tells it to use. (eZNews_ItemType)
     */
    
    /*!
        This function will return all IDs of the children of this class.
        
        \variables
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
        \return
            Returns false if it fails, the error message from SQL is
            retained in $this->SQLErrors. Use getSQLErrors() to read
            the error message.
                      
     */
    function getChildren( &$returnArray, $inOrderBy, $direction , $startAt = 0, $noOfResults = ""  )
    {
        $this->dbInit();
        $value = false;
        
        $returnArray = array();
        $itemArray = array();
        
        $query =
        "
            SELECT
                Item.ItemID AS ID,
                Item.Name AS Name,
                Item.CreatedAt AS CreatedAt,
                Item.CreatedBy AS CreatedBy,
                Item.CreationIP AS CreationIP,
                Item.Status AS Status
            FROM
                eZNews_Item AS Item,
                eZNews_Hiearchy AS Hier
            WHERE
                Hier.ParentID = '%s'
            AND
                Hier.ItemID = Item.ID
            %s
            %s
        ";

        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        $limits = $this->createLimit( $startAt, $noOfResults );
        
        $query = sprintf( $query, $this->ID, $orderBy, $limits );
        
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
        This function will return all IDs of the parents of this class.
        
        \variables
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
        \return
            Returns false if it fails, the error message from SQL is
            retained in $this->SQLErrors. Use getSQLErrors() to read
            the error message.
                      
     */
    function getParents( &$returnArray, $inOrderBy, $direction , $startAt = 0, $noOfResults = ""  )
    {
        $this->dbInit();
        $value = false;
        
        $returnArray = array();
        $itemArray = array();
        
        $query =
        "
            SELECT
                Item.ID AS ID,
                Item.ItemTypeID AS ItemTypeID,
                Item.Name AS Name,
                Item.CreatedAt AS CreatedAt,
                Item.CreatedBy AS CreatedBy,
                Item.CreationIP AS CreationIP,
                Item.Status AS Status
            FROM
                eZNews_Item AS Item,
                eZNews_Hiearchy AS Hier
            WHERE
                Hier.ItemID = '%s'
            AND
                Hier.ParentID = Item.ID
            %s
            %s
        ";

        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        $limits = $this->createLimit( $startAt, $noOfResults );

        $query = sprintf( $query, $this->ID, $orderBy, $limits );
        
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
        Start or stop object logging.
        
        \variables
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
        
        \variables
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
       
        \variables
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
       
        \variables
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
        
        \variables
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
        
        \variables
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
