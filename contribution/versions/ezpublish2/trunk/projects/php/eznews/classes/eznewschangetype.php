<?
// 
// $Id: eznewschangetype.php,v 1.4 2000/09/30 22:17:03 pkej-cvs Exp $
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
    \endcode
    
    \sa eZNewsItem eZNewsChangeTicket
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
      
        \variables
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
        
        \variables
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
        
        \variables
        \out
            \ID     The ID returned after the insert/update.
        \return
            Returns true if we are successful.
     */
    function storeThis( &$ID )
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
      
        \variables
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
                WHERE Name = %s
            ";
            
            $query = sprintf( $query2, $inData );
        }

        $this->Database->array_query( $changetype_array, $query );

        if ( count( $changetype_array ) > 1 )
        {
            $this->Error[] = "intl-eznews-eznewschangetype-more-than-one-object-found";
            
            foreach( $changeTypeArray as $changeType )
            {
                $outID[] = $changeType[ "ID" ];
            }
        }
        else if( count( $changeTypeArray ) == 1 )
        {
            $this->ID = $changeTypeArray[0][ "ID" ];
            $this->Name = $changeTypeArray[0][ "Name" ];
            $this->Description = $changeTypeArray[0][ "Description" ];
            $value = true;
        }
        else
        {
            $this->Error[] = "intl-eznews-eznewschangetype-no-object-found";
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
        
        \variables
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
        
        \variables
        \return
            Returns the description of the object.
    */
    function description()
    {
        $this->dirtyUpdate();
        
        return $this->Description;
    }

    // The data members

    var $Description;
};

?>
