<?
// 
// $Id: eznewschangeticket.php,v 1.3 2000/10/11 15:34:08 pkej-cvs Exp $
//
// Definition of eZNewsChangeTicket class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <10-Oct-2000 18:39:01 pkej>
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
    Add more error checking (in get/set functions)
    Add error logging
    Add debug logging
    More examples
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
        #echo "eZNewsChangeTicket::eZNewsChangeTicket( \$inData = $inData, \$fetch = $fetch )<br>";
        eZNewsUtility::eZNewsUtility( $inData, $fetch );
    }
    
    
    /*!
        Update this eZNewsChangeTicket object and related items.
        
        \out
            \ID     The ID returned after the insert/update.
        \return
            Returns true if we are successful.
     */
    function updateThis( &$outID )
    {
        #echo "eZNewsChangeTicket::updateThis( \$outID = $outID )<br>";
        $value = false;
        
        $query =
        "
            UPDATE
                eZNews_ChangeTicket
            SET
                Name = '%s',
                ChangeInfo = %s,
                ChangeTypeID = %s,
                ChangedBy = %s,
                ChangedAt = '%s',
                ChangeIP = '%s'
            WHERE
                ID = '%s'
        ";
        
        $query = sprintf
        (
            $query,
            $this->Name,
            $this->ChangeInfo,
            $this->ChangeTypeID,
            $this->ChangedBy,
            $this->ChangeIP,
            $this->ChangeIP,
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
        #echo "eZNewsChangeTicket::storeThis( \$outID = $outID )<br>";
        $value = false;
        
        $query =
        "
            INSERT INTO
                eZNews_ChangeTicket
            SET
                Name = '%s',
                ChangeInfo = '%s',
                ChangeTypeID = '%s',
                ChangedBy = '%s',
                ChangedAt = '%s',
                ChangeIP = '%s'
        ";
        
        $query = sprintf
        (
            $query,
            $this->Name,
            $this->ChangeInfo,
            $this->ChangeTypeID,
            $this->ChangedBy,
            $this->ChangedAt,
            $this->ChangeIP
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
    function getThis( &$outID, $inData )
    {
        #echo "eZNewsChangeTicket::getThis( \$outID = $outID, \$inData = $inData)<br>";
        $value = false;
        $changeTicketArray = array();
        $outID = array();
        
        if( is_numeric( $inData ) )
        {
            $query = "
                SELECT
                    *
                FROM
                    eZNews_ChangeTicket
                WHERE ID = %s
            ";
            
            $query = sprintf( $query, $inData );
        }
        else
        {
            $query2 = "
                SELECT
                    *
                FROM
                    eZNews_ChangeTicket
                WHERE Name = '%s'
            ";
            
            $query = sprintf( $query2, $inData );
        }

        $this->Database->array_query( $changeTicketArray, $query );
        
        $count = count( $changeTicketArray );
        
        switch( $count )
        {
            case 0:
                $this->Error[] = "intl-eznews-eznewschangeticket-no-object-found";
                break;
            case 1:
                $outID[] = $changeTicketArray[0][ "ID" ];
                $this->Name = $changeTicketArray[0][ "Name" ];
                $this->ChangeInfo = $changeTicketArray[0][ "ChangeInfo" ];
                $this->ChangeTypeID = $changeTicketArray[0][ "ChangeTypeID" ];
                $this->ChangedBy = $changeTicketArray[0][ "ChangedBy" ];
                $this->ChangedAt = $changeTicketArray[0][ "ChangedAt" ];
                $this->ChangeIP = $changeTicketArray[0][ "ChangeIP" ];
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
                eZNews_ChangeTicket
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
        Sets the ChangedBy of the object.
        
        \in
            \$inDescription    The new ChangedBy of this object
        \return
            Will always return true.
    */
    function setChangedBy( $inChangedBy )
    {
        $this->dirtyUpdate();
        
        $this->ChangedBy = $inChangedBy;
        
        $this->alterState();
        
        return true;
    }
    


    /*!
        Returns the object ChangedBy.
        
        \return
            Returns the ChangedBy of the object.
    */
    function changedBy()
    {
        $this->dirtyUpdate();
        
        return $this->ChangedBy;
    }



    /*!
        Sets the ChangedAt of the object.
        
        \in
            \$inDescription    The new ChangedAt of this object
        \return
            Will always return true.
    */
    function setChangedAt( $inChangedAt )
    {
        $this->dirtyUpdate();
        
        $this->ChangedAt = $inChangedAt;
        
        $this->alterState();
        
        return true;
    }
    


    /*!
        Returns the object ChangedAt.
        
        \return
            Returns the ChangedAt of the object.
    */
    function changedAt()
    {
        $this->dirtyUpdate();
        
        return $this->ChangedAt;
    }



    /*!
        Sets the ChangeIP of the object.
        
        \in
            \$inDescription    The new ChangeIP of this object
        \return
            Will always return true.
    */
    function setChangeIP( $inChangeIP )
    {
        $this->dirtyUpdate();
        
        $this->ChangeIP = $inChangeIP;
        
        $this->alterState();
        
        return true;
    }
    


    /*!
        Returns the object ChangeIP.
        
        \return
            Returns the ChangeIP of the object.
    */
    function changeIP()
    {
        $this->dirtyUpdate();
        
        return $this->ChangeIP;
    }



    /*!
        Sets the ChangeTypeID of the object.
        
        \in
            \$inDescription    A valid ChangeType ID or name.
        \return
            Returns true if the item is changed.
    */
    function setChangeTypeID( $inChangeTypeID )
    {
        $value = false;
        
        include_once( "eznews/classes/eznewschangetype.php" );

        $ct = new eZNewsChangeType( $inChangeTypeID, true );

        if( $ct->isCoherent() )
        {
            $this->dirtyUpdate();
        
            $this->ChangeTypeID = $inChangeTypeID;
        
            $this->alterState();
        
            $value = true;
        }
        
        return $value;
    }
    


    /*!
        Returns the object ChangeTypeID.
        
        \return
            Returns the ChangeTypeID of the object.
    */
    function changeTypeID()
    {
        $this->dirtyUpdate();
        
        return $this->ChangeTypeID;
    }



    /*!
        Sets the ChangeInfo of the object.
        
        \in
            \$inDescription    A valid name or id of an article.
        \return
            Returns true if changed.
    */
    function setChangeInfo( $inChangeInfo )
    {
        $value = false;
        
        include_once( "eznews/classes/eznewsarticle.php" );
        
        $item = new eZNewsChangeType( $inChangeInfo, true );

        if( $item->isCoherent() )
        {
            $this->dirtyUpdate();

            $this->ChangeInfo = $item->ID();

            $this->alterState();
            
            $value = true;
        }
        
        return $value;
    }
    


    /*!
        Returns the object's ChangeInfo.
        
        \return
            Returns the ChangeInfo of the object.
    */
    function changeInfo()
    {
        $this->dirtyUpdate();
        
        return $this->ChangeInfo;
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
        Start or stop article requirement.
        
        \in
            \$check True enables article requirement, false disables it.
                    Default is true.
        \return
            Returns the new status.
     */
    function doLogging( $check = true )
    {
        if( $check == false )
        {
            $this->isArticleRequired = false;
        }
        else
        {
            $this->isArticleRequired = true;
        }
        
        return requireArticle();
    }

    /*!
        Check if this object is requiring an article detailing
        the resaons for the change.
       
        \return
            Returns true if an article is required.
     */
    function requireArticle()
    {
        $value = false;
        
        if( $this->isArticleRequired == true )
        {
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
        if( !isset( $this->ChangeInfo ) && $this->requireArticle() )
        {
            $this->Errors[] = "intl-eznews-eznewschangeticket-logitem-required";
        }

        if( empty( $this->ChangeTypeID ) )
        {
            $this->Errors[] = "intl-eznews-eznewschangeticket-itemtypeid-required";
        }

        if( $this->ChangedBy == 0 && $this->checkCreator() )
        {
            $this->Errors[] = "intl-eznews-eznewschangeticket-changedby-required";
        }

        return eZNewsUtility::invariantCheck();
    }



    // The data members

    /// ID of an article which details this change.
    var $ChangeInfo;
    
    /// The type of change performed.
    var $ChangeTypeID;
    
    /// The user id of the person which did the change.
    var $ChangedBy = 0;
    
    /// The time stamp of the change.
    var $ChangedAt = '';

    /// The ip address of the computer the user used for this change.
    var $ChangeIP = '';



    // Object preferences

    /// Is an detailed info about the change needed?
    var $isArticleRequired = false;
       
    /// Check creator id
    var $checkCreator = true;
};

?>
