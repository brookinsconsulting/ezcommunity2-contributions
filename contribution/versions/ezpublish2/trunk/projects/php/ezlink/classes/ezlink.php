<?
// 
// $Id: ezlink.php,v 1.54 2001/06/29 07:08:39 bf Exp $
//
// Definition of eZLink class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <15-Sep-2000 14:40:06 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!! eZLink
//! The eZLink class handles URL links.
/*!

  Example code:
  \code
  // Create a new link and set some values.
  $link = new eZLink();
  $link->setTitle( "ZEZ website" );
  $link->Description( "zez.org is a page dedicated to all kinds of computer programming." );
  $link->KeyWords( "code programing c++ php sql python" );
  $link->setAccepted( true );
  $link->setUrl( "zez.org" );

  // Store the link to the datavase.
  $link->store();

  // Check if the url exist in the database.
  $link->checkUrl( "zez.org" );

  // Get all the links in a group.
  $link->getByGroup( $linkGroupID );

  // Get all the not accepted links.
  $link->getNotAccepted();

  \endcode
  
  \sa eZLinkGroup eZHit eZQuery
*/

include_once( "classes/ezquery.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/ezdb.php" );

class eZLink
{
    /*!
      Constructor
    */
    function eZLink( $id=-1  )
    {
        $this->ImageID = 0;
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a link to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $description = $db->escapeString( $this->Description );
        $title = $db->escapeString( $this->Title );
        $url = $db->escapeString( $this->Url );
        $keywords = $db->escapeString( $this->KeyWords );

        $db->lock( "eZLink_Link" );

        $nextID = $db->nextID( "eZLink_Link", "ID" );

        $timeStamp =& eZDateTime::timeStamp( true );
                    
        $res = $db->query( "INSERT INTO eZLink_Link 
                ( ID, Title, Description, LinkGroup, KeyWords, Created, Modified, Url, ImageID, Accepted )
                VALUES
                ( '$nextID',
                  '$title',
                  '$description',
                  '$this->LinkGroupID',
                  '$keywords',
                  '$timeStamp',
                  '$timeStamp',
                  '$url',
                  '$this->ImageID',
                  '$this->Accepted' )" );

        $this->ID = $nextID;

        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
        
    }

    /*!
      Update to the database.
    */
    function update()
    {
        $db =& eZDB::globalDatabase();

        $db->begin( );

        $description = $db->escapeString( $this->Description );
        $title = $db->escapeString( $this->Title );
        $url = $db->escapeString( $this->Url );
        $keywords = $db->escapeString( $this->KeyWords );

        $timeStamp =& eZDateTime::timeStamp( true );


        
        $res = $db->query( "UPDATE eZLink_Link SET
                Title='$title',
                Description='$description',
                LinkGroup='$this->LinkGroupID',
                KeyWords='$keywords',
                Modified='$timeStamp',
                Url='$url',
                ImageID='$this->ImageID',
                Accepted='$this->Accepted'
                WHERE ID='$this->ID'" );

      
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
        
    }

    /*!
      Remove the link and the hits that belongs to the link.
    */
    function delete( )
    {
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZLink_Hit WHERE Link='$this->ID'" );        
        $db->query( "DELETE FROM eZLink_Link WHERE ID='$this->ID'" );
    }

    /*!
      Fetches out informasjon from the daatabase where ID=$id
    */
    function get ( $id )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != "" )
        {
            $db->array_query( $link_array, "SELECT * FROM eZLink_Link WHERE ID='$id'" );
            if ( count( $link_array ) > 1 )
            {
                die( "Error: more than one link with the same ID found" );
            }
            else if ( count( $link_array ) == 1 )
            {
                $this->ID =& $link_array[0][$db->fieldName("ID")];
                $this->Title =& $link_array[0][$db->fieldName("Title")];
                $this->Description =& $link_array[0][$db->fieldName("Description")];
                $this->LinkGroupID =& $link_array[0][$db->fieldName("LinkGroup")];
                $this->KeyWords =& $link_array[0][$db->fieldName("KeyWords")];
                $this->Created =& $link_array[0][$db->fieldName("Created")];
                $this->Modified =& $link_array[0][$db->fieldName("Modified")];
                $this->Accepted =& $link_array[0][$db->fieldName("Accepted")];
                $this->Url =& $link_array[0][$db->fieldName("Url")];
                $this->ImageID =& $link_array[0][$db->fieldName("ImageID")];
            }
        }
    }

    /*!
      Fetchs out the links where the linkgroup=$id. Fetchs only accepted links.
    */
    function &getByGroup( $id )
    {
        $db =& eZDB::globalDatabase();
        
        $link_array = array();
        $return_array = array();
        
        $db->array_query( $link_array, "SELECT ID, Title FROM eZLink_Link WHERE LinkGroup='$id' AND Accepted='1' ORDER BY Title" );

        for( $i=0; $i < count( $link_array ); $i++ )
        {
            $return_array[] = new eZLink( $link_array[$i][$db->fieldName("ID")] );
        }


        return $return_array;
    }

    /*!
      Fetches out the links that is not accepted.

      Default limit is set to 30.
    */
    function &getNotAccepted( $offset=0, $limit=30 )
    {
        $db =& eZDB::globalDatabase();
        
        $link_array = array();
        $return_array = array();
        
        $db->array_query( $link_array, "SELECT ID, Title
                                        FROM eZLink_Link
                                        WHERE Accepted='0'
                                        ORDER BY Title",
                          array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i=0; $i < count( $link_array ); $i++ )
        {
            $return_array[] = new eZLink( $link_array[$i][$db->fieldName("ID")] );
        }

        return $return_array;
    }

    /*!
      Returns the total numbers of links that is not accepted.
    */
    function unAcceptedCount(  )
    {
        $db =& eZDB::globalDatabase();

        $query = "SELECT count( ID ) AS Count 
                  FROM eZLink_Link
                  WHERE Accepted='0'";

        $db->array_query( $linkArray, $query );
        
        return $linkArray[0][$db->fieldName("Count")];
    }


    /*!
      Fetches out the last teen accpeted links.
    */
    function &getLastTenDate( $limit, $offset )
    {
        $db =& eZDB::globalDatabase();
        
        $link_array = array();
        $return_array = array();
        
        $db->array_query( $link_array,
        "SELECT * FROM eZLink_Link WHERE Accepted='1' ORDER BY Created DESC",
        array( "Limit" => $limit, "Offset" => $offset ) );

        for( $i=0; $i < count( $link_array ); $i++ )
        {
            $return_array[] = new eZLink( $link_array[$i][$db->fieldName("ID")] );
        }
        return $return_array;
    }

    /*!
      Fetches out the last teen accpeted links.
    */
    function &getLastTen( $limit, $offset )
    {
        $db =& eZDB::globalDatabase();
        $link_array = 0;
        
        $db->array_query( $link_array,
        "SELECT * FROM eZLink_Link WHERE Accepted='1' ORDER BY Title DESC",
        array( "Limit" => $limit, "Offset" => $offset ) );

        return $link_array;
    }

    /*!
      Fetches the links that matches the $query.

      Default limit is set to 25.
    */
    function &getQuery( $query, $limit, $offset )
    {
        $db =& eZDB::globalDatabase();
        $link_array = array();
        $return_array = array();

        $query = new eZQuery( array( "KeyWords", "Title", "Description" ), $query );
        
        $query_str =  "SELECT ID, Title FROM eZLink_Link WHERE (" .
             $query->buildQuery()  .
             ") AND Accepted='1' GROUP BY Title, ID ORDER BY Title";

        $db->array_query( $link_array,
        $query_str,  array( "Limit" => $limit, "Offset" => $offset ) );
    
        $ret = array();

        foreach( $link_array as $linkItem )
        {
            $ret[] = new eZLink( $linkItem[$db->fieldName("ID")] );
        }
        return $ret;
    }


    /*!
      Returns the total count of a query.
    */
    function &getQueryCount( $query  )
    {
        $db =& eZDB::globalDatabase();
        $link_array = 0;

        $query = new eZQuery( array( "KeyWords", "Title", "Description" ), $query );
        
        $query_str = "SELECT count(ID) AS Count, Title FROM eZLink_Link WHERE (" .
             $query->buildQuery()  .
             ") AND Accepted='1' GROUP BY Title ORDER BY Title";

        $db->array_query( $link_array, $query_str );

        $ret = 0;
        if ( count( $link_array ) == 1 )
            $ret = $link_array[0][$db->fieldName("Count")];

        return $ret;
    }
    

    /*!
      Fetches all the links.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        $group_array = 0;

        $db->array_query( $group_array, "SELECT * FROM eZLink_Link ORDER BY Title" );

        return $group_array;
    }

    /*!
      Check if the url exists.
    */
    function &checkUrl( $url )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $url_array, "SELECT url FROM eZLink_Link WHERE url='$url'" );

        return count( $url_array );
    }

    /*!
      Returns the id of the link.
    */
    function id()
    {
        return $this->ID;
    }


    /*!
      Sets the link title.
    */
    function setTitle( &$value )
    {
        $this->Title = $value;
    }

    /*!
      Sets the link description
    */
    function setDescription( &$value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the linkgroupID.
    */
    function setLinkGroupID( $value )
    {
        $this->LinkGroupID = $value;
    }

    /*!
      Sets the link keywords.
    */    
    function setKeyWords( &$value )
    {
        $this->KeyWords = ( $value );
    }

    /*!
      Sets if the link is accepted, true/false.
    */
    function setAccepted( $value )
    {
        if ( $value == true )            
            $this->Accepted = "1";
        else
            $this->Accepted = "0";
    }

    /*!
      Sets the link URL.
    */
    function setUrl( &$value )
    {
        $this->Url = ( $value );
    }

    /*!
      Returns the link title.
    */
    function &title()
    {
        return htmlspecialchars( $this->Title );
    }


    /*!
      Returns the link description.
    */
    function &description()
    {
        return htmlspecialchars( $this->Description );
    }

    /*!
      Returns the linkgroupID.
    */
    function linkGroupID()
    {
        return htmlspecialchars( $this->LinkGroupID );
    }

    /*!
      Returns the link keywords.
    */
    function &keyWords()
    {
        return htmlspecialchars( $this->KeyWords );
    }

    /*!
      Returns the date when the link was created.
    */
    function &created()
    {
        return $this->Created;
    }

    /*!
      Returns the date when the link was modified.
    */
    function &modified()
    {
        return $this->Modified;
    }

    /*!
      Returns true if the link is Accepted, false if not.
    */
    function accepted()
    {
        if ( $this->Accepted == 1 )
            return true;
        else
            return false;
    }

    /*!
      Retruns the url of the link.
    */
    function &url()
    {
        return htmlspecialchars( $this->Url );
    }

    /*!
      Returns the id of the link.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
        Set an image for this category.
     */
    function setImage( &$value )
    {
        if ( get_class( $value ) == "ezimage" )
        {
            $this->ImageID = $value->id();
        }
        elseif( is_numeric( $value ) )
        {
            $this->ImageID = $value;
        }
    }

    /*!
      Returns the image as a eZImage object.

      false is returned if no link is assigned to the link.
    */
    function &image( )
    {
        $ret = false;
        if ( $this->ImageID != 0 )
        {
            $ret = new eZImage( $this->ImageID );
        }

        return $ret;
    }

    /*!
      Delete the current image that belong to this eZLink object.
    */
    function deleteImage()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $result, "SELECT ImageID FROM eZLink_Link WHERE ID='$this->ID'" );

        foreach ( $result as $item )
        {
            $image = new eZImage( $item[$db->fieldName("ImageID")] );
            $image->delete();
        }
        
        $db->query( "UPDATE eZLink_Link set ImageID='0' WHERE ID='$this->ID'" );
    }


    var $ID;
    var $Title;
    var $Description;
    var $LinkGroupID;
    var $KeyWords;
    var $Created;
    var $Modified;
    var $Accepted;
    var $ImageID;
    var $Url;
    var $url_array;

}
?>
