<?php
// 
// $Id: eznewsitem.php,v 1.39 2000/10/13 12:25:02 pkej-cvs Exp $
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
    
    removeImage must do its job.
    
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
include_once( "eznews/classes/eznewschangetype.php" );       
include_once( "eznews/classes/eznewschangeticket.php" );

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
        #echo "eZNewsItem::eZNewsItem( \$inData = $inData \$fetch = $fetch )<br />\n";
        
        $this->CreatedAt = $this->createTimeStamp();
        $this->CreationIP = $this->createIP();
        $this->createCreatedBy();
        $this->Name = $this->CreatedAt;
        
        eZNewsUtility::eZNewsUtility( $inData, $fetch );
        
        if( !strcmp( $this->State_, "new" ) )
        {
            $this->createLogItem( $this->ID . ": " . $this->Name . " created", "create" );
        }
    }


    
    /*!
        \private
        
        Creates an user id.
        
        This function returns the current user.
        
        NOTE: Unimplemented at the moment, pending session
        management.
        
        We need to store session data indefinetly
        if we have to save session id for the user.
        Ie. never loose contact with session.
        
        \return
            Returns zero if there is no authenticated user or
            it will return the User ID of an authenticated user.
     */
    function createCreatedBy( )
    {
        $value = false;

        if( $this->checkCreator() )
        {
            include_once( "ezsession/classes/ezsession.php" );
            $user = eZUser::currentUser();
            
            if( $user )
            {
                $this->CreatedBy = $user->ID();
            }
            else
            {
                $this->CreatedBy = 0;
                $value = true;
            }
        }
        
        return $value;
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
        #echo "eZNewsItem::getThis( \$outID=$outID, \$inData=$inData )<br />\n";
        $value = false;
        $itemArray = array();
        $outID = array();
        
        if( is_numeric( $inData ) )
        {
            $itemByIDQuery = "
                SELECT
                    *
                FROM
                    eZNews_Item
                WHERE ID = %s
            ";
            
            $query = sprintf( $itemByIDQuery, $inData );
        }
        else
        {
            $itemByNameQuery = "
                SELECT
                    *
                FROM
                    eZNews_Item
                WHERE Name = '%s'
            ";
            
            $query = sprintf( $itemByNameQuery, $inData );
        }


        $this->Database->array_query( $itemArray, $query );
        $count = count( $itemArray );
        
    #echo $query . "<br />\n";
    #echo $count . "<br />\n";
        switch( $count )
        {
            case 0:
                $this->Error[] = "intl-eznews-eznewschangetype-no-object-found";
                $this->State_ = "unexisting";
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
        #echo "eZNewsItem::createLogItem( \$changeText = $changeText \$changeType = $changeType )<br />\n";
        
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
            
            if( $doIt )
            {
                $user = eZUser::currentUser();
                if( $user )
                {
                    $creator = $user->ID();
                }
                else
                {
                    $creator = 0;
                }
                
                $ticket = new eZNewsChangeTicket();
                $type = new eZNewsChangeType( $changeType );
                
                $ticket->setChangeTypeID( $type->ID() );
                $ticket->setChangeInfo( "0" );

                $ticket->setName( $changeText );
                $ticket->setChangedBy( $creator );
                $ticket->setChangedAt( $this->createTimeStamp() );
                $ticket->setChangeIP( $this->createIP() );
                $ticket->store( $outID );

                $this->setLog( $outID );

                $this->Status = $type->ID();
                $value = true;
            }
        }
        return $value;
    }



    /*!
        Creates the relationship between this object and one image.
        
        If the object is dirty it will not accept any new references.
        
        This funciton needs store() afterwards if you want to apply
        the change to the database.
        
        Only one image can be the front image. Only the latest added
        front image will be set as the front image.
        
        \in
            \$ImageID   The id of the image that shall be added

            \$isFrontImage  If the incoming image is a front image
                            set this to true. Default is false.
        
        \return
            Returns true if an reference is made.
     */
    function setImage( $ImageID,  $isFrontImage = false )
    {
        echo "eZNewsItem::createLogItem( \$ImageID = $ImageID \$isFrontImage = $isFrontImage )<br />\n";
        $value = false;
        
        if( !$this->isDirty() )
        {
            foreach( $this->ImageID as $existingImage )
            {
                if( $existingImage == $ImageID )
                {
                    $oldImageID = $existingImage;
                }
            }
            
            if( $oldImageID != $ImageID )
            {
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
                        $oldFrontImage = $this->isFrontImage;
                        $this->isFrontImage = $ImageID;
                        
                        if( $oldFrontImage != $ImageID )
                        {
                            $oldImage = new eZImage( $oldFrontImage );
                            $this->createLogItem( $this->ID . ": Front Image changed from " . $oldImage->Name() . "(" . $oldImage->ID()  .")" . " to " . $image->Name() . "(" . $image->ID()  .")", $this->Status );
                        }
                    }
                    else
                    {
                        $this->createLogItem( $this->ID . ": Image " . $image->Name() . "(" . $image->ID()  .")" . " added.", $this->Status );
                    }
                }

                if( $value == true )
                {
                    $this->ImageID[] = $ImageID;
                    $this->alterState();
                }
            }
        }
        
        return $value;
    }
    
    
    /*!
        This one needs some code...
     */
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
        #echo "eZNewsItem::setLog( \$ChangeTicketID = $ChangeTicketID )<br />\n";
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
            
            $ct = new eZNewsChangeTicket( $ChangeTicketID, true );
    
            if( $ct->isCoherent() == false )
            {
                $value = false;
                $this->Errors[] = "intl-eznews-eznewsitem-changeticket-doesnt-exist";
            }

            if( $value == true )
            {
                $this->ChangeTicketID[] = $ChangeTicketID;
                $this->alterState();
            }
        }
        
        #$this->printLogs();
        
        return $value;
    }



    /*!
        Prints all the log item ids of this item.
     */
    function printLogs()
    {
        #echo "eZNewsItem::printLogs()<br />\n";
        if( $this->ChangeTicketID )
        {
            echo "Log items belonging to: " . $this->ID . " " . $this->Name . "<br />\n";
            foreach( $this->ChangeTicketID as $id )
            {
                echo "$id <br />\n";
            }
        }
    }



    /*!
        Prints all the parent ids of this item.
     */
    function printParents()
    {
        #echo "eZNewsItem::printParents()<br />\n";
        if( $this->ParentID )
        {
            echo "Parent items belonging to: " . $this->ID . " " . $this->Name . "<br />\n";
            foreach( $this->ParentID as $id )
            {
                echo "$id <br />\n";
            }
        }
    }



    /*!
        Prints all the file ids of this item.
     */
    function printFiles()
    {
        #echo "eZNewsItem::printFiles()<br />\n";
        if( $this->FileID )
        {
            echo "File items belonging to: " . $this->ID . " " . $this->Name . "<br />\n";
            foreach( $this->FileID as $id )
            {
                echo "$id <br />\n";
            }
        }
    }
    


    /*!
        Prints all the image ids of this item.
     */
    function printImages()
    {
        #echo "eZNewsItem::printImages()<br />\n";
        if( $this->ImageID )
        {
            echo "Image items belonging to: " . $this->ID . " " . $this->Name . "<br />\n";
            foreach( $this->ImageID as $id )
            {
                echo "$id <br />\n";
            }
        }
    }
    


    /*!
        Creates the relationship between this object and its parent.
        
        If the object is dirty it will not accept any new references.
        
        Needs store() afterwards.
        
        Only one parent can be the canonical parent. Only the latest
        added canonical parent will be retained.
        
        \in
            \$ParentID A legal name or ID of a eznews_item entry.
            \$isCanonical This new parent should be the new canonical.
        
        \return
            Returns true if an reference is made.
     */
    function setParent( $ParentID, $isCanonical = false )
    {
        #echo "eZNewsItem::setParent( \$ParentID = $ParentID, \$isCanonical = $isCanonical )<br />\n";
        $value = false;
        
        if( !$this->isDirty() )
        {
            foreach( $this->ParentID as $existingParent )
            {
                if( $existingParent == $ParentID )
                {
                    $oldParentID = $existingParent;
                }
            }

            if( $oldParentID != $ImageID )
            {
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
                        $oldCanonicalID = $this->isCanonical;
                        $this->isCanonical = $ParentID;
                        
                        if( $oldCanonicalID != $ParentID )
                        {
                            $oldCanonical = new eZNewsCategory( $oldCanonicalID );
                            $this->createLogItem( $this->ID . ": Canonical parent changed from " . $oldCanonical->Name() . "(" . $oldCanonical->ID()  .")" . " to " . $item->Name() . "(" . $item->ID()  .")", $this->Status );
                        }
                    }
                    else
                    {
                        $this->createLogItem( $this->ID . ": Parent " . $item->Name() . "(" . $item->ID()  .")" . " added.", $this->Status );
                    }
                }

                if( $value == true )
                {
                    $this->ParentID[] = $ParentID;
                    $this->alterState();
                }
            }
        }
        
        #$this->printParents();
        #$this->printErrors();
        
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

        $nonFrontImageQuery =
        "
            INSERT INTO
                eZNews_ItemImage
            SET
                ItemID   = '%s',
                ImageID  = '%s',
                isFrontImage = 'N'
        ";

        $frontImageQuery =
        "
            INSERT INTO
                eZNews_ItemImage
            SET
                ItemID   = '%s',
                ImageID  = '%s',
                isFrontImage  = 'Y'
        ";

        foreach( $this->ImageID as $ImageID )
        {
            if( $this->isFrontImage == $ImageID )
            {
                $query = sprintf
                (
                    $frontImageQuery,
                    $this->ID,
                    $ImageID
                );                
            }
            else
            {
                $query = sprintf
                (
                    $nonFrontImageQuery,
                    $this->ID,
                    $ImageID
                );
            }
            
            $this->Database->query( $query );
            
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
        #echo "eZNewsItem::storeLogs()<br />\n";
        
        #$this->printLogs();

        $changeTicketQuery =
        "
            INSERT INTO
                eZNews_ItemLog
            SET
                ItemID   = '%s',
                ChangeTicketID  = '%s'
        ";

        foreach( $this->ChangeTicketID as $CTID )
        {
            $query = sprintf
            (
                $changeTicketQuery,
                $this->ID,
                $CTID
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
        #echo "eZNewsItem::updateLogs()<br />\n";
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
        $fileQuery =
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
                $fileQuery,
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
        #echo "eZNewsItem::storeParents()<br />\n";
        $this->dbInit();

        $nonCanonicalQuery =
        "
            INSERT INTO
                eZNews_Hiearchy
            SET
                ItemID   = '%s',
                ParentID  = '%s',
                isCanonical = 'N'
        ";

        $canonicalQuery =
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
                    $canonicalQuery,
                    $this->ID,
                    $ParentID
                );
            }
            else
            {
                $query = sprintf
                (
                    $nonCanonicalQuery,
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
        #echo "eZNewsItem::updateParents()<br />\n";
        $this->dbInit();

        $query =
        "
            DELETE FROM
                eZNews_Hiearchy
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
        #echo "eZNewsItem::storeThis( \$outID )<br />\n";
        
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
            $this->createLogItem( $this->ID . ": Was stored", $this->Status );
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
        #echo "eZNewsItem::updateThis( \$outID )<br />\n";
        
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

        $outID = $this->ID;
        $stored = true;
        
        if( $stored )
        {
            $this->updateParents();
            $this->updateFiles();
            $this->updateImages();
            $this->createLogItem( $this->ID . ": Was updated", $this->Status );
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
        
        Hmm, how much should we delete, and what about a hard delete?
        
        Should we promote children?
        
        \return
            Returns true if the item exists and has been deleted.
     */
    function delete()
    {
        #echo "eZNewsItem::delete()<br />\n";
        $value = false;
        $this->dbInit();
        
        $type = new eZNewsChangeType( "delete" );

        if ( isset( $this->ID ) && $this->Status != $type->ID() )
        {
            $this->dirtyUpdate();

            $this->createLogItem( $this->ID . ": Item was deleted", "delete" );

            $this->Status = $type->ID();

            unset( $this->ParentID );
            $this->ParentID = array();
            
            unset( $this->FileID );
            $this->FileID = array();
            
            unset( $this->ImageID );
            $this->ImageID = array();
            
            $this->isCanonical = 0;
            $this->isFrontImage = 0;

            $this->alterState();
                        
            #$this->printObject();

            $this->store( $outID );
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
        #echo "eZnewsItem::getWidows( \&\$returnArray, \$inOrderBy = \"$inOrderBy\", \$direction = \"$direction\" , \$startAt = \"$startAt\", \$noOfResults = \"$noOfResults\" ) <br />\n";
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
        
        Note: Even deleted items will be returned. It is the client's job to sort
        out those items actually shown to the user.
        
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
        #echo "eZnewsItem::getChildren( \&\$returnArray, \$inOrderBy = \"$inOrderBy\", \$direction = \"$direction\" , \$startAt = \"$startAt\", \$noOfResults = \"$noOfResults\" ) <br />\n";
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
        ";
        
        $query = sprintf( $query, $this->ID );        
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
            %s
            %s
        ";

        
        $query = sprintf( $query, $this->ID, $orderBy, $limits );
        
        $this->Database->array_query( $itemArray, $query );
        
        $count = count( $itemArray );
        
        for( $i = 0; $i != $count; $i++ )
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
        This function will return an array of items with an array of children of that type.

        Note: Even deleted items will be returned. It is the client's job to sort
        out those items actually shown to the user.
                
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
        #echo "eZnewsItem::getChildrenGroups( \&\$returnArray, \$inOrderBy = \"$inOrderBy\", \$direction = \"$direction\" , \$startAt = \"$startAt\", \$noOfResults = \"$noOfResults\" ) <br />\n";
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
            GROUP BY Type.Name
        ";
        
        $query = sprintf( $query, $this->ID );        
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
            GROUP BY Type.Name
        ";

        $query = sprintf( $query, $this->ID );

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
            ";
       
            $query = sprintf( $query, $typeName, $this->ID );        
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
                %s
                %s
            ";
            $orderBy = $this->createOrderBy( $inOrderBy, $direction );
            $limits = $this->createLimit( $startAt, $noOfResults );
                
            $query = sprintf( $query, $typeName, $this->ID, $orderBy, $limits );
            
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
        
        Note: Even deleted items will be returned. It is the client's job to sort
        out those items actually shown to the user.
        
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
        #echo "eZnewsItem::getParents( \&\$returnArray, \$inOrderBy = \"$inOrderBy\", \$direction = \"$direction\" , \$startAt = \"$startAt\", \$noOfResults = \"$noOfResults\" ) <br />\n";
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
        ";
        
        $query = sprintf( $query, $this->ID );

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
            %s
            %s
        ";
        
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        $limits = $this->createLimit( $startAt, $noOfResults );

        $query = sprintf( $query, $this->ID, $orderBy, $limits );
        #echo "$query<br>id: " . $this->ID . "<br>name: " . $this->Name . "<br />\n";

        $this->Database->array_query( $itemArray, $query );
        
        $count = count( $itemArray );
        
        if( $count > 0 )
        {
            unset( $this->ParentID );
            $this->ParentID = array();
        }
        
        for( $i = 0; $i < $count; $i++ )
        {   
            $returnArray[$i] = new eZNewsItem( $itemArray[$i][ "ID" ], 0 );
            $this->ParentID[$i] = $itemArray[$i][ "ID" ];
            
            if( $itemArray[$i][ "isCanonical" ] == 'Y' )
            {
                $this->isCanonical = $itemArray[$i][ "ID" ];
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
        #echo "eZnewsItem::getImages( \&\$returnArray, \$inOrderBy = \"$inOrderBy\", \$direction = \"$direction\" , \$startAt = \"$startAt\", \$noOfResults = \"$noOfResults\" ) <br />\n";
        $this->dbInit();
        $value = false;

        $returnArray = array();
        $itemArray = array();

        $imageCountQuery =
        "
            SELECT
                count(*)
            FROM
                eZNews_ItemImage
            WHERE
                ItemID = %s
        ";
        
        $query = sprintf( $imageCountQuery, $this->ID );        
        $this->Database->array_query( $countArray, $query );        
        $maxCount = $countArray[0][0];
        
        $getImagesQuery =
        "
            SELECT
                *
            FROM
                eZNews_ItemImage
            WHERE
                ItemID = %s
            %s
            %s
        ";

        #$orderBy = $this->createOrderBy( $inOrderBy, $direction );
        $limits = $this->createLimit( $startAt, $noOfResults );

        $query = sprintf( $getImagesQuery, $this->ID, $orderBy, $limits );
        $this->Database->array_query( $imagesArray, $query );
        
        $count = count( $imagesArray );
        
        #echo "\$count = $count<br />\n";
        
        unset( $this->ImageID );
        $this->ImageID = array();
        
        for( $i = 0; $i < $count; $i++ )
        {
            include_once( "ezimagecatalogue/classes/ezimage.php" );

            $returnArray[$i] = new eZImage( $imagesArray[$i][ "ImageID" ], 0 );
            $this->ImageID[] = $returnArray[$i];

            if( $imagesArray[$i][ "isFrontImage" ] == 'Y' )
            {
                $this->isFrontImage = $imagesArray[$i][ "ImageID" ];
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
    function isChangeType( $changeType )
    {
        #echo "eZNewsItem::isChangeType( \$changeType = $changeType )<br />\n";

        $value = false;
        
        $changeType = new eZNewsChangeType( $changeType, true );

        $changeType->invariantCheck();
        
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
        if( $this->isCanonical != 0 )
        {
            $count = count( $this->ParentID );
            if( $count == 0 )
            {
                $this->Errors[] = "intl-eznews-eznewsitem-canonical-exists-parents-dont";
            }            
        }
        
        if( $this->isFrontImage != 0 )
        {
            $count = count( $this->ImageID );
            
            if( $count == 0 )
            {
                $this->Errors[] = "intl-eznews-eznewsitem-frontimage-exists-images-dont";
            }
        }

        if( empty( $this->ItemTypeID ) )
        {
            $this->Errors[] = "intl-eznews-eznewsitem-itemtypeid-required";
        }

        if( $this->CreatedBy == 0 && $this->checkCreator() )
        {
            $this->Errors[] = "intl-eznews-eznewsitem-createdby-required";
        }

        if( !isset( $this->ChangeTicketID ) && $this->isLogging() )
        {
            $this->Errors[] = "intl-eznews-eznewsitem-logitem-required";
        }

        return eZNewsUtility::invariantCheck();
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
        
        Will consider the work for done if the incoming
        value equals the existing. But no change (and most
        importantly) no logging will be performed.
        
        \in
            \$inCreatedAt    The new CreatedAt of this object
        \return
            Will always return true.
    */
    function setCreatedAt( $inCreatedAt )
    {
        $this->dirtyUpdate();
        
        $oldCreatedAt = $this->CreatedAt;
        
        if( $oldCreatedAt != $inCreatedAt )
        {
            $this->CreatedAt = $inCreatedAt;

            $this->createLogItem( $this->ID . ": Creation date changed from $oldCreatedAt to $inCreatedAt", $this->Status );

            $this->alterState();
        }
        
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
        
        Will consider the work for done if the incoming
        value equals the existing. But no change (and most
        importantly) no logging will be performed.
        
        \in
            \$inItemTypeID    A valid item type id or name.
        \return
            Will return true if the item type id was changed.
    */
    function setItemTypeID( $inItemTypeID )
    {
        $value = false;
        
        include_once( "eznews/classes/eznewsitemtype.php" );
        $it = new eZNewsItemType( $inItemTypeID, true );
        $itold = new eZNewsItemType( $this->ItemTypeID, true );

        if( $itold->ID() != $it->ID() )
        {
            if( $it->isCoherent() )
            {
                $this->dirtyUpdate();

                $this->createLogItem( $this->ID . ": Item Type changed from " . $itold->name() . "(" . $itold->ID()  .")" . " to " . $it->name() . "(" . $it->ID()  .")", $this->Status );

                $this->ItemTypeID = $it->ID();

                $this->alterState();

                $value = true;
            }
        }
        else
        {
            $value = true;
        }
        
        return $value;
    }
    


    /*!
        Returns the object isCanonical.
        
        \return
            Returns the isCanonical of the object.
    */
    function getIsCanonical()
    {
        $this->dirtyUpdate();
        return $this->isCanonical;
    }



    /*!
        Returns the object ItemTypeID.
        
        \return
            Returns the ItemTypeID of the object.
    */
    function getItemTypeID()
    {
        $this->dirtyUpdate();
        return $this->ItemTypeID;
    }



    /*!
        Returns the object CreatedAt.
        
        \return
            Returns the CreatedAt of the object.
    */
    function getCreatedAt()
    {
        #echo "eZNewsItem::getCreatedAt()<br />\n";
        $this->dirtyUpdate();
        
        return $this->CreatedAt;
    }



    /*!
        Returns the object FrontImageID.
        
        \return
            Returns the isFrontImage of the object.
    */
    function getFrontImage()
    {
        #echo "eZNewsItem::getFrontImage()<br />\n";
        $this->dirtyUpdate();
        
        #echo "\$this->isFrontImage = " . $this->isFrontImage . "<br />\n";
        
        return $this->isFrontImage;
    }



    /*!
        Sets the Status of the object.
        
        Will consider the work for done if the incoming
        value equals the existing. But no change (and most
        importantly) no logging will be performed.

        \in
            \$inDescription    A valid change type id or name.
        \return
            Will return true if the Status was changed.
    */
    function setStatus( $inItemTypeID )
    {
        #echo "eZNewsItem::setStatus( \$inItemTypeID = $inItemTypeID )<br />\n";
        $value = false;
        

        $ct = new eZNewsChangeType( $inItemTypeID, true );
        $ctold = new eZNewsChangeType( $this->Status, true );
        
        if( $ct->ID() != $ctold->ID() )
        {
            if( $ct->isCoherent() )
            {
                $this->dirtyUpdate();

                if( $this->isLogging )
                {
                    $this->createLogItem( $this->ID . ": Status changed from " . $ctold->Name() . "(" . $ctold->ID()  .")" . " to " . $ct->Name() . "(" . $ct->ID()  .")", $inItemTypeID );
                }
                else
                {
                    $this->Status = $ct->ID();
                }
                $this->alterState();

                $value = true;
            }
        }
        else
        {
            $value = true;
        }
        return $value;
    }
    
    /*!
        Enables logging on name changes.
        
        Will consider the work for done if the incoming
        value equals the existing. But no change (and most
        importantly) no logging will be performed.
        
        \in
            \$inName    The new name of this item.
        \return
            Returns true if the name was changed.
     */
    function setName( $inName )
    {
        #echo "eZNewsItem::setName( \$inName = $inName )<br />\n";
        $oldname = $this->name();
        
        #echo "\$oldname = $oldname, \$inName = $inName<br />\n";
        
        $value = eZNewsUtility::setName( $inName );
        
        if( $value )
        {
            $this->createLogItem( $this->ID . ": Name changed from $oldname to $inName", $this->Status );
        }

        return $value;
    }

    /*!
        Enables logging on id changes.
        
        Will consider the work for done if the incoming
        value equals the existing. But no change (and most
        importantly) no logging will be performed.
        
        \in
            \$inID    The new id of this item.
        \return
            Returns true if the id was changed.
     */
    function setID( $inID )
    {
        #echo "eZNewsItem::setID( \$inID = $inID )<br />\n";
        $oldid = $this->ID();
        
        $value = eZNewsUtility::setID( $inID );
        
        if( $value )
        {
            $this->createLogItem( $this->ID . "(was " . $oldid . "): ID changed from $oldid to $inID", $this->Status );
        }

        return $value;
    }



    /*!
        Returns the object Status.
        
        \return
            Returns the Status of the object.
    */
    function status()
    {
        #echo "eZNewsItem::status()<br />\n";
        $this->dirtyUpdate();
        
        return $this->Status;
    }



    /*!
        Returns the object ItemTypeID.
        
        \return
            Returns the ItemTypeID of the object.
    */
    function itemTypeID()
    {
        $this->dirtyUpdate();
        
        return $this->ItemTypeID;
    }



    /*!
        Print all the info in the object.
     */
    function printObject()
    {
        echo "eZNewsItem::printObject()<br />\n";
        echo "ID = " . $this->ID . " \n";
        echo "Name = " . $this->Name . " \n";
        echo "ItemTypeID = " . $this->ItemTypeID . " \n";
        echo "Status = " . $this->Status . " \n";
        echo "CreationIP = " . $this->CreationIP . " \n";
        echo "CreatedAt = " . $this->CreatedAt . " \n";
        echo "CreatedBy = " . $this->CreatedBy . " \n";
        
        echo "isCanonical = " . $this->isCanonical . " \n";
        echo "isFrontImage = " . $this->isFrontImage . " \n";
        echo "doStoreCheck = " . $this->doStoreCheck . " \n";
        echo "State_ = " . $this->State_ . " \n";
        echo "hasChanged = " . $this->hasChanged . " \n";       
        echo "checkCreator = " . $this->checkCreator . " \n";       
        echo "isLogging = " . $this->isLogging . " \n";       
        echo "<br />\n";
        
        $this->printParents();
        $this->printLogs();
        $this->printImages();
        $this->printFiles();
        $this->printErrors();
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
    var $isCanonical = 0;
    
    /// The ID of the front image.
    var $isFrontImage = 0;
    
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
