<?
// 
// $Id: eznewschangetype.php,v 1.5 2000/10/01 13:39:28 pkej-cvs Exp $
//
// Definition of eZNewsChangeType class
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
//! eZNewsChangeType handles change types for the eZNews log.
/*!
    The eZNewsChangeType class is used to identify what kind of
    changes one can do to a news item. The change type info can
    be used to find certain types of changes (for example who
    deleted what) to a news item.
    
    The change type is stored in a change ticket.
    
    Example of usage:
    \code
    // Example of how to include this file.
    include_once( "eznews/classes/eznewschangetype.php" );       

    // Example on how to create an empty object:    
    $ct = new eZNewsChangeType();
    
    // Example on how to check if a change type exists
    
    $changeName = "create";
    
    $ct = new eZNewsChangeType( $changeName, true );
    
    if( $ct->isCoherent() )
    {
        echo "The object " . $ct->ID() . " represents the change type: " . $ct->Name();
        echo " which is equal to $changeName";
    }
    
    // Example on how to create a change type.
    
    $changeName = "submit";
    
    $ct = new eZNewsChangeType( $changeName, true );
    
    if( !$ct->isCoherent() )
    {
        $ct->setName( $changeName );
        $ct->setDescription( "The item has been submitted" );
        $outID = 0;
        $ct->store( $outID );
        
        if( $outID != 0 )
        {
            echo "The new change type: " .  $changeName . " was stored with id $outID<br>";
        }
        else
        {
            echo "Hmm shouldn't see this...<br>";
        }
    }
    else
    {
        echo "The change type: " $changeName . " exists with id $outID<br>";
    }
    
    \endcode
    
    \sa eZNewsItem
*/
/*!TODO
    Add all examples needed.
 */

include_once( "eznews/classes/eznewsutility.php" );       

class eZNewsChangeType extends eZNewsUtility
{
    /*!
        Constructs a new eZNewsChangeType object.

        If $inData is set the object's values are fetched from the
        database. $inData might be a name or an ID. If it is an object name
        then the first object with that name will be fetched. In other words
        there is no guarantee that you will get what you want using a name.
      
        \in
            \$inData    Either the ID or the Name of the row we want
            \$fetch     Should we fetch the row now, or later
    */
    function eZNewsChangeType( $inData = "", $fetch = true )
    {
        eZNewsUtility::eZNewsUtility( $inData, $fetch );
    }
    
    
    /*!
        Update this eZNewsChangeType object and related items.
        
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
                eZNews_ChangeType
            SET
                Name = '%s',
                Description = '%s'
            WHERE
                ID = '%s'
        ";
        
        $query = sprintf
        (
            $query,
            $this->Name,
            $this->Description,
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
        Store this eZNewsChangeType object and related items.
        
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
                eZNews_ChangeType
            SET
                Name = '%s',
                Description = '%s'
        ";
        
        $query = sprintf
        (
            $query,
            $this->Name,
            $this->Description
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
        $changeTypeArray = array();
        $outID = array();
        
        if( is_numeric( $inData ) )
        {
            $query = "
                SELECT
                    *
                FROM
                    eZNews_ChangeType
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
                    eZNews_ChangeType
                WHERE Name = '%s'
            ";
            
            $query = sprintf( $query2, $inData );
        }

        $this->Database->array_query( $changeTypeArray, $query );
        
        $count = count( $changeTypeArray );
        
        #echo "count=: " . $count . "<br>";
        #echo "item 0 id: " . $changeTypeArray[0][ "ID" ] . "<br>";
        #echo "item 0 description: " . $changeTypeArray[0][ "Description" ] . "<br>";
        switch( $count )
        {
            case 0:
                $this->Error[] = "intl-eznews-eznewschangetype-no-object-found";
                break;
            case 1:
                $outID[] = $changeTypeArray[0][ "ID" ];
                $this->Name = $changeTypeArray[0][ "Name" ];
                $this->Description = $changeTypeArray[0][ "Description" ];
                $value = true;
                break;
            default:
                $this->Error[] = "intl-eznews-eznewschangetype-more-than-one-object-found";

                foreach( $changeTypeArray as $changeType )
                {
                    $outID[] = $changeType[ "ID" ];
                }
                break;
        }
        
        return $value;
    }



    /*!
      Returns all the change types found in the database.

      The change types are returned as an array of eZNewsChangeType objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $changetype_array = array();
        
        $query="
        SELECT ID FROM eZNews_ChangeType ORDER BY Name
        ";
        
        $this->Database->array_query( $changetype_array, $query );
        
        for ( $i=0; $i<count($changetype_array); $i++ )
        {
            $return_array[$i] = new eZNewsChangeType( $changetype_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }



    /*!
        Sets the descritption of the object.
        
        \in
            \$inDescription    The new name of this object
        \return
            Will always return true.
    */
    function setDescription( $inDescription )
    {
        $this->dirtyUpdate();
        
        $this->Description = $inDescription;
        
        $this->alterState();
        
        return true;
    }
    


    /*!
        Returns the object description.
        
        \return
            Returns the description of the object.
    */
    function description()
    {
        $this->dirtyUpdate();
        
        return $this->Description;
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

    var $Description;
};

?>
