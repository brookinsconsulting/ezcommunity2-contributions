<?php


//!! eZNews
//! eZNewsItem2 handles eZNews items.
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

class eZNewsItem2
{
/*
    \code
    // Example - adding a front image to the object.
    
    $object->referenceImage( $ImageID, true );
    $object->store();
    
    // Example - adding an image to the object.
    
    $object->referenceImage( $ImageID );
    $object->store();
    
    // Example - adding a file to the object.
    
    $object->referenceImage( $FileID );
    $object->store();

    // Example - adding a parent to the object.
    
    $object->referenceParent( $ParentID );
    $object->store();
   
    // Example - adding a canonical parent to the object.
    
    $object->referenceParent( $ParentID, true );
    $object->store();
   
    \endcode
 */
    /*
        Private
        This function returns the current ip and port of the computer
        accessing us.
     */
    function createIP ( )
    {
        return $GLOBALS[ "REMOTE_ADDR" ] . "/" .$GLOBALS[ "REMOTE_PORT" ];
    }


    
    /*
        Private
        This function returns the current time in gmt.
     */
    function createTimeStamp ( )
    {
        $time = gmdate( "YmdHis", time());
    }


    
    /*
        Creates a log entry when logging is on. This is
        used to create information about changes done to
        the object, epsecially automatic changes.
        
        Returns true if logging is on and  a log item
        was created.
     */
    function createLogItem( $changeText, $changeType,  )
    {
        $value = false;
        if( $this->isLogging )
        {
            if( $this->checkCreator() )
            {
                $creator = 0; // do check here.
            }
            else
            {
                $creator = 0;
            }
        }
        
        return $value;
    }



    /*!
        Create the relationship between this object and one image.
        
        Will fail if an image is marked as a front image, but an
        front image already exists.
        
        If the object is dirty it will not accept any new references.
        
        Needs store() afterwards.
        
        Only one image can be the front image.
        
        Returns true if an reference is made.
     */
    function referenceImage( $ImageID,  $isFrontImage = false )
    {
        $value = true;
        
        if( !is_numeric( $this->isFrontImage ) && !$this->isDirty() )
        {
            foreach( $this->ImageID as $existingImage )
            {
                if( $existingImage == $ImageID )
                {
                    $value = false;
                }
            }

            if( $value == true )
            {
                $this->ImageID[] = $ImageID;

                if( $isFrontImage == true )
                {
                    $this->isFrontImage = $ImageID;
                }

                $this->alterState();
            }
        }
        
        return $value;
    }
    
    
    
    /*!
        Create the relationship between this object and one file.
        
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
        Create the relationship between this object and one log entry.
        
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
        Create the relationship between this object and its parent.
        
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
                }
            }

            if( $value == true )
            {
                $this->ParentID[] = $ParentID;

                if( $isCanonical == true )
                {
                    $this->isCanonical = $ParentID;
                }

                $this->alterState();
            }
        }
        
        return $value;
    }
    
    
    
    /*!
        Private.
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
        Private.
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
        Private.
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
        Private.
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
        Store this object and related items.
        
        Returns true if the object is stored.
     */
    function store( $copy = false )
    {
        $value = false;
        $storeAllowed = false;
        
        
        if( $copy == true )
        {
            $storeAllowed = true;
            $this->ID = 0;
        }
        
        if( $this->isCoherent() )
        {
            $storeAllowed = true;
        }
        else
        {
            $storeAllowed = false;
        }
        
        if( $storeAllowed )
        {
            if( $this->hasChanged() && !$copy )
            {
                // alter the row in the database
                // What has changed
                
                
            }
            else
            {
                // create a new object
                
                // Set new ID before this.
                if( $this->checkCreator() )
                {
                    // look up parent id, use a function which
                    // will do this once and for all.
                    //
                }
                else
                {
                    $storeAllowed;
                }
                
                if( $storeAllowed )
                {
                    $this->storeParents();
                    $this->storeFiles();
                    $this->storeImages();

                    if( $this->isLogging() )
                    {
                        $this->storeLogs();
                    }
                }
                
            }
        }
        
        if( /* we stored the object successfully */ )
        {
            $this->hasChanged = false;
            $this->State_ = "coherent";
        }
    }



    /*!
        Private
        This function will change the state of the object based
        on the current state. Only functions which change the
        object may call this function.
     */
    function alterState()
    {
        switch( $this->State_ )
        {
            case "new":
                $this->State_ = "altered";
            case "coherent":
                $this->State_ = "altered";
                $this->hasChanged = true;
            case "dirty":
                $this->State_ = "dirty";
        }
    }



    /*!
        Returns true if the object has changed data which
        were fetched from a database.
     */
    function hasChanged()
    {
        $value = false;
        
        if( $this->hasChanged = true; )
        {
            $value = true;
        }
        
        return $value;
    }



    /*!
       Check if this object is new, ie. just created.
     */
    function isNew()
    {
        $value = false;
        
        if( $this->State_ == "new" )
        {
            $value = true;
        }
        
        return $value;
    }


    
    /*!
       Check if this object is dirty, ie. no data has been loaded. 
     */
    function isDirty()
    {
        $value = false;
        
        if( $this->State_ == "dirty" )
        {
            $value = true;
        }
        
        return $value;
    }


    
    /*!
       Check if this object is coherent, ie. data has recently been loaded,
       or an invariant check has worked. 
     */
    function isCoherent()
    {
        $value = false;
        
        if( $this->State_ == "coherent" )
        {
            $value = true;
        }
        
        return $value;
    }


    
    /*!
       Check if this object is altered, ie. data has recently been changed.
     */
    function isAltered()
    {
        $value = false;
        
        if( $this->State_ == "Altrered" )
        {
            $value = true;
        }
        
        return $value;
    }



    /*!
        Start or stop creator check.
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
        
        return checkCreator();
    }



    /*!
       Should we check out who the creator is?
       
       Returns true if we should.
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
        Start or stop object logging.
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
       Check if this object is logging it's changes,
       
       Returns true if it is logging.
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
        This function will try to make this object coherent.
        It will discard any ids of logs, images, files and
        parents which doesn't exist.
        
        It will create one id for an image and/lr parent if
        the isFrontImage or the isCanonical variables are set.
        
        Returns true if an invariantCheck passes at the end
        of the function.
     */
    function makeCoherent()
    {
        if( $this->State_ == "Dirty" )
        {
            //check if possible $this->get( $this->ID );
        }
        
        // do as invariant check
        // is isCanonical is set and no ParentIDs exists
        // then check if iscanonical id exists adn set
        // a parentID as that id.
        
        // the same for isfrontimage.
        
        //
        
        return $this->invariantCheck();
    }
    /*!
        Make shure that the object is in a legal state.
        All errors are stored in $this->invariantErrors.
        
        Returns true if the object passes the check.
     */
    function invariantCheck()
    {
        $value = false;
        
        if( is_numeric( $this->isCanonical ) )
        {
            $count = count( $this->ParentID );
            if( $count == 0 )
            {
                $this->invariantErrors[] = "intl-canonical-exists-parents-dont";
            }            
        }
        
        if( is_numeric( $this->isFrontImage ) )
        {
            $count = count( $this->ImageID )
            if( $count == 0 )
            {
                $this->InvariantErrors[] = "intl-fronimage-exists-images-dont";
            }
        }
        
        if( empty( $this->Name ) )
        {
            $this->InvariantErrors[] = "intl-name-required";
        }

        if( empty( $this->ItemTypeID ) )
        {
            $this->InvariantErrors[] = "intl-itemtypeid-required";
        }

        if( $this->CreatedBy == 0 && $this->checkCreator() )
        {
            $this->InvariantErrors[] = "intl-createdby-required";
        }

        if( !isset( $this->ChangeTicketID ) && $this->isLogging() )
        {
            $this->InvariantErrors[] = "intl-logitem-required";
        }

        if( !isset( $this->InvariantErrors ) )
        {
            $value = true;
            $State_ = "coherent";
        }
        
        return $value;
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
    
    
    
    // The data members
    
    /// The object''s ID
    var $ID = 0;
    
    /// The object''s ItemTypeID
    var $ItemTypeID = 0;
    
    /// The object''s Name
    var $Name = '';
    
    /// The object''s Status
    var $Status = 0;
    
    /// The object''s CreatedBy
    var $CreatedBy = 0;
    
    /// The object''s CreatedAt
    var $CreatedAt = 0;
    
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
    
    
    
    // Object errors

    /// All invariant errors.
    var $InvariantErrors = array();
    
    
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regards to database information.
    var $State_;
    
    /// Is true if the object has a database connection, false if not.
    var $isConnected;
    
    /// Is true if the object has changed data previously loaded
    var $hasChanged = false;
};
?> 
