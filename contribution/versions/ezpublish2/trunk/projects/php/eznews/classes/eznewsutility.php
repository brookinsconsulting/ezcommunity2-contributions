<?
// 
// $Id: eznewsutility.php,v 1.3 2000/10/01 16:37:53 pkej-cvs Exp $
//
// Definition of eZNewsUtility class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <30-Sep-2000 22:01:27 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZNews
//! eZNewsUtility is used for creating new eZNews database objects.
/*!
    This class has a collection of standard functions which we need in
    different eZNews classes.
    
    Instead of coding these in each hiearchy, we create them here.
    
    Each child of this class must do the following:
    
    <ol>
    <li>hava a use for both the ID and Name fields in this class...
    <li>call the constructor of eZNewsUtility
    <li>implement: invariantCheck() which calls this invariantCheck()
    <li>implement: getThis() a function for fetching all data of the object.
    <li>implement: storeThis() a function for storing all data of the object
    <li>implement: setVariable() and variable() for each memeber variable of the object.
    <li>implement: makeCoherent() which will create all data which can be inferred from other
        data in the system....
    <li>store ale error messages in $this->Errors
    <li>store ale error messages concerning the database/SQL in $this->SQLErrors
    <li>each setVariable() must have this form:
    <code>
        $this->dirtyUpdate();
        $this->variable = $inVariable;
        $this->alterState();
    </code>
    <li>each variable() must have this form:
    <code>
        $this->dirtyUpdate();
        return $this->variable;
    </code>
    </ol>
    
    \sa eZNewsChangeType eZNewsItemType eZNewsItem for classes implementing this
    interface.

 */

include_once( "classes/ezdb.php" );
include_once( "classes/ezsession.php" );

class eZNewsUtility
{
    /*!
        Constructs a new eZNewsUtility object.
     */
    function eZNewsUtility( $inData = -1, $fetch = true )
    {
        
        $this->dbInit();        
        $this->State_ = "New";
        $outID = array();
        if( $inData )
        {
            if( $fetch )
            {
                $this->get( $outID, $inData );
            }
            else
            {
                if( is_numeric( $inData ) )
                {
                    $this->ID = $inData;
                }
                else
                {
                    $this->Name = $inData;
                }
                $this->State_ = "Dirty";        
            }
        }
    }
    


    /*!
        Stores this object to the database.

        Does some checking which all objects needs done.
        
        Will call $this->updateThis() or $this->storeThis() which
        has to be implemented in any class which inherits from this
        class.
        
        \out
            \$outID The ID returned from the insert.
        \return
            Returns the true if successful.
    */
    function store( &$outID )
    {
        #echo "eZNewsUtility::store( \$copy = $copy )<br>";
        $this->dbInit();
        
        $value = false;
        $storeAllowed = false;
        $stored = false;
        

        
        if( $copy == true )
        {
            $this->ID = 0;
        }
        
        // An object must pass the invariant check to 
        // be stored.
        
        $this->invariantCheck();
         
        if( $this->isCoherent() )
        {
            $storeAllowed = true;
        }
        
        if( $storeAllowed )
        {
            if( $this->hasChanged() )
            {
                $stored = $this->updateThis( $outID );
            }
            else
            {
                $stored = $this->storeThis( $outID );
            }
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
        Gets the object data.
        
        This function does all the error checking before getting a new
        object thru the $this->getThis() function.
        
        \in
            \$inData    Either the ID or the Name of the row we want this object
                    to get data from.
        \out
            \$outID     An array of all IDs from the result of the query.
        \return
            Returns true if only one data item was returned.
     */
    function get( &$outID, &$inData )
    {
        #echo "eZUtility::get( \$outID, \$inData=$inData ) <br>";
        $value = false;
        $getting = false;
        
        
        // First we just make sure that we have some info on what
        // we should get.

        if( empty( $inData  ) )
        {
            if( is_numeric( $this->ID ) )
            {
                $inData = $this->ID;
                $getting = true;
            }
            else if( !empty( $this->Name ) )
            {
                $inData = $this->Name;
                $getting = true;
            }
        }
        else
        {
            $getting = true;
        }
        
        // Then we make sure that the object doesn''t need storing
        if( $this->storedCheck() == false )
        {
            $getting = false;
        }
        
        // Now fetch the data.
        if( $getting )
        {
            $value = $this->getThis( $outID, $inData );
            
            if( $value )
            {
                #echo "get out id " . $outID[0] . "<br>";
                $this->State_ = "coherent";
                $this->hasChanged = false;
                $this->ID = $outID[0];
                #echo "this id = " .  $this->ID . "<br>";
            }
        }
        
        return $value;
    }
    
    
    
    /*!
        Private function
        
        Creates an order by clause.
        
        This function creates an SQL order by clause.
        
        \in
            \$inOrderBy     The column to order the search by
            \$direction     The direction to order the search by
        \return
            This funciton returns an empty string if a column
            isn't provided.
     */
    function createOrderBy( $inOrderBy, $direction )
    {
        unset( $returnString );
        
        if( !empty( $inOrderBy ) )
        {
            $returnString = "ORDER BY " . $inOrderBy  . " " . $direction;
        }
        
        return $returnString;
    }



    /*!
        Private function
        
        Creates an limit clause.
        
        This function creates an SQL limit clause.
        
        \in
            \$startAt       The number of the first result we want
            \$noOfResults   The number of results we want
        \return
            Returns the limit string. String will be empty if no
            either of the input variables are empty.
            
     */
    function createLimit( $startAt, $noOfResults )
    {
        unset( $returnString );
        
        if( !empty( $startAt ) && !empty( $noOfResults ) )
        {
            $returnString = "LIMIT " . $startAt  . ", " . $noOfResults ;
        }
        
        return $returnString;
    }
    
    
    
    /*!
        Private function
        
        Creates IP and port number of accessing browser.
        
        This function returns the current ip and port of the computer
        accessing us.
        
        \return
            Will return a concatenated string of IP and Port, or just a
            slash if nothing exists.
     */
    function createIP()
    {
        #echo "eZNewsUtility::createIP()<br>";
        return $GLOBALS[ "REMOTE_ADDR" ] . "/" .$GLOBALS[ "REMOTE_PORT" ];
    }


    
    /*!
        Private function
        
        Creates a timestamp for use in a Mysql timestamp field.
        
        \return
            Returns an SQL timestamp on the form YYYYMMDDHHMMSS using the
            gmt time.
     */
    function createTimeStamp( )
    {
        return gmdate( "YmdHis", time());
    }


    
    /*!
        Private function
        
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
        $value = 0;
        
        if( $this->checkCreator() )
        {
            // find user id.
        }
        
        return $value;
    }


    /*!
        Private function
        
        This function will get the data of this object if the 
        state of the object is dirty.
     */
    function dirtyUpdate()
    {
        if( $this->State_ == "dirty" )
        {
            if( is_numeric( $this->ID ) )
            {
                $this->get( $this->ID );
            }
            else
            {
                $this->get( $this->Name );
            }
        }
    }


   
    /*!
        Private function
        
        This function will change the state of the object based
        on the current state. Only functions which change the
        object may call this function.
        
        \return
            Returns true if the state has been changed.
     */
    function alterState()
    {
        $value = false;
        
        switch( $this->State_ )
        {
            case "new":
                $this->State_ = "altered";
                $value = true;
                break;
            case "coherent":
                $this->State_ = "altered";
                $this->hasChanged = true;
                $value = true;
                break;
            case "dirty":
                $this->State_ = "dirty";
                $value = true;
                break;
            default:
                break;
        }
        
        return $value;
    }



    /*!
        Check if the object has been changed.
        
        An object in the altered state will report has changed if its
        previous state was coherent.
        
        \return
            Returns true if the object has changed.
     */
    function hasChanged()
    {
        $value = false;
        
        if( $this->hasChanged == true )
        {
            $value = true;
        }
        
        return $value;
    }



    /*!
        Check if this object is new, ie. just created.
        
        A new object is a blank object which can be used to create a new
        row in the table.
        
        \return
            Returns true if this is a new object.
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
        Check if this object is dirty, ie. no data has been loaded, or the
        object is in a state where changes to it doesn't make any sense.
        
        An object in a dirty state must be getted with get() to make sure
        that we know all facets of it before doing changes.
       
        \return
            Returns true if the object is dirty.
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
       
        A coherent object is one which is ready to be altered or stored.
       
        \return
            Returns true if the object is coherent.
        
     */
    function isCoherent()
    {
        $value = false;

        if( $this->State_ == "coherent" )
        {
            $value = true;
        }
        
        #echo "isCoherent returns: " . $value . "<br>";
        return $value;
    }


    
    /*!
        Check if this object is altered, ie. data has recently been changed.
        
        An object in the altered state has had some changes to the data.
       
        \return
            Returns true if the object has been altered since last get or store.
            
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
        Private function
        
        This function will just check that the object isn't dirty.
        
        \return
            Returns true if an invariantCheck passes at the end
            of the function.
     */
    function makeCoherent()
    {
        $value = false;
        
        if( $this->State_ == "Dirty" )
        {
            $this->get();
            $value = true;
        }
        
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
        $value = true;
        
        return $value;
    }



    /*!
        Return error strings and reset errors.
        
        This function will return the errors in $this->Errors. It
        assumes that you know what you're doing and resets the error
        string automatically.
        
        There are two methods of handling errors with this object:
        <ol>
        <li>Check each function for errors and if the function returns
        false, deal with the problem.
        <li>Check if there were errors at end of function, and deal
        with them there.
        </ol>
        
        See the examples for how you can use the second method.
        
        \return
            Returns the error messages since last call to the function.
     */
    function errors()
    {
        $errors = $this->Errors;
        unset( $this->Errors );
        return $errors;
    }

    
    /*!
        Do the store check.
        
        Will check the state of the object if doSaveCheck is true,
        and return true or false based on the state.
        
        If the object 
        
        \return
            Returns false if the object needs to be stored.
     */
    function storedCheck()
    {
        $value = false;
        
        if( $this->doStoreCheck = true )
        {
            if( $this->State_ != "altered" || $this->hasChanged == false )
            {
                $value = true;
            }
            else
            {
                $this->Errors[] = "intl-eznews-utility-object-needs-store";
            }
        }
        else
        {
            $value = true;
        }
        return $value;
    }
    
    /*!
        Start or stop object save checking.
        
        \in
            \$check True enables the save check, false disables it.
                    Default is true.
        \return
            Returns the new status.
     */
    function doStoreCheck( $check = true )
    {
        if( $check == true )
        {
            $this->doStoreCheck = true;
        }
        else
        {
            $this->doStoreCheck = false;
        }
        
        return $this->doStoreCheck;
    }
    
    
        
    /*!
        Return sql error strings and reset sql errors.
        
        This function will return the SQL errors in $this->SQLErrors. It
        assumes that you know what you're doing and resets the error
        string automatically.
        
        There are two methods of handling errors with this object:
        <ol>
        <li>Check each function for errors and if the function returns
        false, deal with the problem.
        <li>Check if there were errors at end of function, and deal
        with them there.
        </ol>
        
        See the examples for how you can use the second method (the same
        example as for errors()).
        
        \return
            Returns the error messages since last call to the function.
     */
    function SQLErrors()
    {
        $errors = $this->SQLErrors;
        unset( $this->SQLErrors );
        return $errors;
    }



    /*!
        Returns the object ID..
        
        Returns the unique ID of this object as stored in
        the database. If this object is new, then 0 is
        returned.
        
        \return
            Returns the ID of the object, or 0;
    */
    function id()
    {
        $value = 0;
        
        if ( $this->State_ != "new" )
        {
            $value = $this->ID;
        }
       
        return $value;
    }



    /*!
        Sets the object ID.
        
        This will set the object in a dirty state if successful.

        \in
            $inID   The new ID of this object.
        \return
            Returns true if successful;
    */
    function setID( $inID )
    {
        $value = 0;
        
        if( ( $this->State_ == "coherent" ) || ( $this->State_ == "new" ) )
        {
            $this->ID = $inID;
            $value = true;
            $this->State_ == "dirty";
            $this->hasChanged = false;
        }
       
        return $value;
    }



    /*!
        Returns the object name.
        
        \return
            Returns the name of the object.
    */
    function name()
    {
        $this->dirtyUpdate();
        
        return $this->Name;
    }



    /*!
        Sets the name of the object.
        
        \in
            \$inName    The new name of this object
        \return
            Will always return true.
    */
    function setName( $inName )
    {
        $this->dirtyUpdate();
        
        $this->Name = $inName;
        
        $this->alterState();
        
        return true;
    }



    /*!
        Private function
      
        Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        #if( $GLOBAL["NEWSDEBUG"] == true )
        #{
        #    echo "eZNewsItem::dbInit(  ) <br>\n";
        #    echo "isConnected is: " . $this->IsConnected . "<br>";
        #}
        
        if( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "eZNewsMain" );
            $this->IsConnected = true;
        }
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

        if( empty( $this->Name ) )
        {
            $this->Errors[] = "intl-name-required";
        }

        if( !count( $this->Errors ) )
        {
            #echo "errors " . $this->Errors[0] . "<br>";
            $value = true;
            $this->State_ = "coherent";
        }
        return $value;
    }


    // The data members
    
    /// The object''s ID
    var $ID = 0;
    
    /// The object''s Name
    var $Name = '';
    


    // Object preferences
    
    /// Check if there are unsaved changes
    var $doStoreCheck;

    
    // Object errors

    /// All invariant errors.
    var $Errors = array();
    
    /// All sql errors.
    var $SQLErrors = array();
    
    
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regards to database information.
    var $State_;
    
    /// Is true if the object has a database connection, false if not.
    var $isConnected = false;
    
    /// Is true if the object has changed data previously loaded
    var $hasChanged = false;
    
};

?>
