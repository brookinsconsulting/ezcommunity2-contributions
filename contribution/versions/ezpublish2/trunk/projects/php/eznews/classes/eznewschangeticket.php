<?
// 
// $Id: eznewschangeticket.php,v 1.1 2000/10/01 16:37:53 pkej-cvs Exp $
//
// Definition of eZNewsChangeTicket class
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
//! eZNewsChangeTicket handles change tickets for the eZNews log.
/*!
    The eZNewsChangeTicket identifies which changes has been applied to
    a news item.
    
    Example of usage:
    \code
    // Example of how to include this file.
    include_once( "eznews/classes/eznewschangeticket.php" );       

    // Example on how to create an empty object:    
    $ct = new eZNewsChangeTicket();
    
    // Example on how to check if a change ticket exists
    
    $changeTicketName = "create";
    
    $ct = new eZNewsChangeTicket( $changeTicketName, true );
    
    if( $ct->isCoherent() )
    {
        echo "The object " . $ct->ID() . " represents the change ticket: " . $ct->Name();
        echo " which is equal to $changeTicketName";
    }
    
    // Example on how to create a change ticket.
    
    $changeTicketName = "submit";
    
    $ct = new eZNewsChangeTicket( $changeTicketName, true );
    
    if( !$ct->isCoherent() )
    {
        $ct->setName( $changeTicketName );
        $ct->setDescription( "The change ticket has been submitted" );
        $outID = 0;
        $ct->store( $outID );
        
        if( $outID != 0 )
        {
            echo "The new change ticket: " .  $changeTicketName . " was stored with id $outID<br>";
        }
        else
        {
            echo "Hmm shouldn't see this...<br>";
        }
    }
    else
    {
        echo "The change ticket: " $changeTicketName . " exists with id $outID<br>";
    }
    
    \endcode
    
    \sa eZNewsUtility eZNewsItem
*/
/*!TODO
    Make getThis get parents class/table if missing from this.
 */

include_once( "eznews/classes/eznewsutility.php" );       

class eZNewsChangeTicket extends eZNewsUtility
{
    /*!
        Constructs a new eZNewsChangeTicket object.

        If $inData is set the object's values are fetched from the
        database. $inData might be a name or an ID. If it is an object name
        then the first object with that name will be fetched. In other words
        there is no guarantee that you will get what you want using a name.
      
        \in
            \$inData    Either the ID or the Name of the row we want
            \$fetch     Should we fetch the row now, or later
    */
    function eZNewsChangeTicket( $inData = "", $fetch = true )
    {
        eZNewsUtility::eZNewsUtility( $inData, $fetch );
    }
    
    
    /*!
        Update this eZNewsChangeTicket object and related items.
        
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
        Store this eZNewsChangeTicket object and related change tickets.
        
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
            Returns true if only one data change ticket was returned.
    */
    function getThis( &$outID, &$inData )
    {
        $value = false;
        $changeTicketArray = array();
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

        $this->Database->array_query( $changeTicketArray, $query );
        
        $count = count( $changeTicketArray );
        
        #echo "count=: " . $count . "<br>";
        #echo "change ticket 0 id: " . $changeTicketArray[0][ "ID" ] . "<br>";
        #echo "change ticket 0 description: " . $changeTicketArray[0][ "Description" ] . "<br>";
        switch( $count )
        {
            case 0:
                $this->Error[] = "intl-eznews-eznewschangeticket-no-object-found";
                break;
            case 1:
                $outID[] = $changeTicketArray[0][ "ID" ];
                $this->Name = $changeTicketArray[0][ "Name" ];
                $this->ParentID = $changeTicketArray[0][ "ParentID" ];
                $this->eZClass = $changeTicketArray[0][ "eZClass" ];
                $this->eZTable = $changeTicketArray[0][ "eZTable" ];
                $value = true;
                break;
            default:
                $this->Error[] = "intl-eznews-eznewschangeticket-more-than-one-object-found";

                foreach( $changeTicketArray as $changeTicket )
                {
                    $outID[] = $changeTicket[ "ID" ];
                }
                break;
        }
        
        return $value;
    }



        /*!
            Returns all the change tickets found in the database.
        
        \in
            \$inOrderBy  This is the columnname to order the returned array
                        by.
            \accepts
                ID - The id of the row in the table
                Name - Name of change ticket
                eZClass - The eZClass of this change ticket.
                eZTable - The eZTable of this change ticket.
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
        $changeTicketArray = array();
        
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
        
        $this->Database->array_query( $changeTicketArray, $query );
        
        for ( $i=0; $i < count( $changeTicketArray ); $i++ )
        {
            $returnArray[$i] = new eZNewsChangeTicket( $changeTicketArray[$i][ "ID" ], 0 );
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
            Will always return true.
    */
    function seteZClass( $ineZClass )
    {
        $this->dirtyUpdate();
        
        $this->eZClass = $ineZClass;
        
        $this->alterState();
        
        return true;
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
            Will always return true.
    */
    function seteZTable( $ineZTable )
    {
        $this->dirtyUpdate();
        
        $this->eZTable = $ineZTable;
        
        $this->alterState();
        
        return true;
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
            \$inDescription    The new ParentID of this object
        \return
            Will always return true.
    */
    function setParentID( $inParentID )
    {
        $this->dirtyUpdate();
        
        $this->ParentID = $inParentID;
        
        $this->alterState();
        
        return true;
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

    /// The class name of this change ticket, empty means use parent or n/a.
    var $eZClass;
    
    /// The table where this class is stored, empty means use parent or n/a.
    var $eZTable;
    
    /// The ID of the parent of this class.
    var $ParentID;
};

?>
