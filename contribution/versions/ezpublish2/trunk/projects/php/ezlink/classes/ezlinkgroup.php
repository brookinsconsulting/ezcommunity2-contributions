<?
// 
// $Id: ezlinkgroup.php,v 1.55 2001/06/29 07:08:39 bf Exp $
//
// Definition of eZLinkGroup class
//
// Bård Farstad <bf@ez.no>
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
//! The eZLinkGroup class handles URL links.
/*!

  Example code:

  \code
  // Create a new group and set some values.
  $group = new eZLinkGroup();
  $group->setTitle( "PHP" );
  $group->setParent( "ParentID" );

  // Store the group in to the database.
  $group->store();

  \endcode
  
  \sa eZLink eZHit eZQuery
*/

/*!TODO
  Retitle title to title (also in the database).
  More effective caching.
*/


include_once( "classes/ezdb.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

class eZLinkGroup
{
    /*!
      Counstructor
    */
    function eZLinkGroup( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Saves a group to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $title = $db->escapeString( $this->Title );
        $description = $db->escapeString( $this->Description );

        $db->begin( );

        $db->lock( "eZLink_LinkGroup" );

        $nextID = $db->nextID( "eZLink_LinkGroup", "ID" );        

        $res = $db->query( "INSERT INTO eZLink_LinkGroup
                ( ID, Title, Description, ImageID, Parent )
                VALUES
                ( '$nextID',
                  '$title',
                  '$description',
                  '$this->ImageID',
                  '$this->Parent' )" );

        $this->ID = $nextID;

        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();        
    }

    /*!
      Update the database.
    */
    function update()
    {
        $db =& eZDB::globalDatabase();
        
        $title = $db->escapeString( $this->Title );
        $description = $db->escapeString( $this->Description );

        $db->begin( );

        $res = $db->query( "UPDATE eZLink_LinkGroup SET 
                Title='$title',
                Description='$description',
                Parent='$this->Parent',
                ImageID='$this->ImageID'
                WHERE ID='$this->ID'" );
         
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();    
    }

    /*!
      Delete from database.
    */
    function delete( )
    {
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZLink_Link WHERE LinkGroup='$this->ID'" );
        $db->query( "DELETE FROM eZLink_LinkGroup WHERE ID='$this->ID'" );
    }

    /*!
      Fetch out a group from the database.
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $linkgroup_array,  "SELECT * FROM eZLink_LinkGroup WHERE ID='$id'" );
        if ( count( $linkgroup_array ) > 1 )
        {
            die( "feil, flere grupper med samme id" );
        }
        else if ( count( $linkgroup_array ) == 1 )
        {
            $this->ID =& $linkgroup_array[0][$db->fieldName("ID")];
            $this->Title =& $linkgroup_array[0][$db->fieldName("Title")];
            $this->Description =& $linkgroup_array[0][$db->fieldName("Description")];
            $this->Parent =& $linkgroup_array[0][$db->fieldName("Parent")];
            $this->ImageID = $linkgroup_array[0][$db->fieldName("ImageID")];
        }
    }

    /*!
      Print out the group path.
    */
    function &path( $groupID=0 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $groupID == 0 )
        {
            $groupID = $this->ID;
        }
        
        $group = new eZLinkGroup( $groupID );
        
        $path = array();
        
        $parent = $group->parent();
        
        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent ) );
        }
        else
        {
//              array_push( $path, $category->title() );
        }

        if ( $groupID != 0 )
            array_push( $path, array( $group->id(), $group->title() ) );                                
        
        return $path;
    }


    /*!
      Fetch out parent.
    */
    function &getByParent( $value )
    {
        if ( get_class ( $value ) )
            $id = $value->id();
        else
            $id = $value;
        
        $db =& eZDB::globalDatabase();
        $parent_array = array();
        $return_array = array();

        $db->array_query( $parent_array, "SELECT ID, Title FROM eZLink_LinkGroup
                                                 WHERE Parent='$id' ORDER BY Title" );

        for( $i=0; $i<count( $parent_array ); $i++ )
        {
            $return_array[] = new eZLinkGroup( $parent_array[$i][$db->fieldName("ID")] );
        }

        return $return_array;                   
    }

    /*!
      Returns the count for subgroup in a group.
    */
    function &getTotalSubLinks( $id, $start_id )
    {
        $db =& eZDB::globalDatabase();
        
        $count = 0;
        $sibling_array = $this->getByParent( $id );

        if ( $id == $start_id )
        {
            $db->array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE LinkGroup='$id' AND Accepted='1'" );
            $count += $link_count[0][$db->fieldName("LinkCount")];
        }
        
        for ( $i=0; $i<count( $sibling_array ); $i++ )
        {
            $group_id =  $sibling_array[ $i][$db->fieldName("ID")];
            $count += $this->getTotalSubLinks( $group_id, $start_id );
            $db->array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE LinkGroup='$group_id' AND Accepted='1'" );
            $count += $link_count[0][$db->fieldName("LinkCount")];            
        }

        return $count;
    }

    /*!
      Returns the count for new links under the group.
      All the links that newer than $new_limit is marked as new.
     */
    function &getNewSubLinks( $id, $start_id, $new_limit )
    {
        $db =& eZDB::globalDatabase();
        
        $count = 0;
        $sibling_array = $this->getByParent( $id );

        $new_limit = $new_limit*60*60*24;
             
        if ( $id == $start_id )
        {
            $timeStamp =& eZDate::timeStamp( true );

            $db->array_query( $link_count, "SELECT COUNT( ID ) AS LinkCount, Title from eZLink_Link WHERE LinkGroup='$id'
                                                        AND Accepted='1' AND ( ( $timeStamp - Created ) <= $new_limit  ) ORDER BY Title" );
            $count += $link_count[0][$db->fieldName("LinkCount")];
        }
        
        for ( $i=0; $i<count( $sibling_array ); $i++ )
        {
            $group_id =  $sibling_array[ $i][$db->fieldName("ID")];
            $count += $this->getNewSubLinks( $group_id, $start_id, $new_limit );
            $db->array_query( $link_count, "SELECT COUNT( ID ) AS LinkCount, Title  from eZLink_Link WHERE LinkGroup='$group_id'
                                                        AND Accepted='1' AND ( ( $timeStamp - Created ) <= $new_limit  )  ORDER BY Title" );
            $count += $link_count[0][$db->fieldName("LinkCount")];
        }
        return $count;
    }

    /*!
      Return the count of links in incoming.
     */
    function &getTotalIncomingLinks()
    {
        $db =& eZDB::globalDatabase();
        
        $count = 0;
        $db->array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE Accepted='0'" );
        $count = $link_count[0][$db->fieldName("LinkCount")];

        return $count;
    }
    
    /*!
      Fetch everything.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        $parnet_array = array();
        $return_array = array();

        $db->array_query( $parent_array, "SELECT ID, Title FROM eZLink_LinkGroup ORDER BY Title" );

        for( $i=0; $i<count( $parent_array ); $i++ )
        {
            $return_array[$i] = new eZLinkGroup( $parent_array[$i][$db->fieldName("ID")] );
        }

        return $return_array;
    }

    /*!
      Fetch everything and return the result in a tree.
    */
    function &getTree( $parentID=0, $level=0 )
    {
        $category = new eZLinkGroup( $parentID );

        $categoryList = $category->getByParent( $category, true );
        
        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $return_array[] = new eZLinkGroup( $category->id() ), $level ) );
            
            if ( $category != 0 )
            {
                $tree = array_merge( $tree, $this->getTree( $category->id(), $level ) );
            }
            
        }

        return $tree;
    }

    /*!
      Returns all the links in the current category.

      Default limit is set to 30.
    */
    function links( $offset=0, $limit=30, $fetchUnAccepted=false )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        
        if ( $fetchUnAccepted )
            $fetchUnAccepted = "";
        else
            $fetchUnAccepted = " AND Accepted='1' ";
        
        $db->array_query( $linkArray,
                          "SELECT ID, Title
                           FROM eZLink_Link
                           WHERE LinkGroup='$this->ID'
                           $fetchUnAccepted
                           ORDER BY Title",
                           array( "Limit" => $limit, "Offset" => $offset ) );

        foreach( $linkArray as $link )
        {
            $returnArray[] = new eZLink( $link[$db->fieldName("ID")] );
        }
        return $returnArray;        
    }
    
    
    /*!
      Returns the total numbers of links in the current category.
    */
    function linkCount( $fetchUnAccepted=false )
    {
        $db =& eZDB::globalDatabase();

        if ( $fetchUnAccepted )
            $fetchUnAccepted = "";
        else
            $fetchUnAccepted = " AND Accepted='1' ";

        $query = "SELECT count( ID ) AS Count 
                  FROM eZLink_Link
                  WHERE LinkGroup='$this->ID'
                  $fetchUnAccepted";

        $db->array_query( $linkArray, $query );
        
        return $linkArray[0][$db->fieldName("Count")];
    }

    /*!
      Return the id of the group.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Sets the title of a group.
    */
    function setTitle( &$value )
    {
        $this->Title = ( $value );
    }

    /*!
      Sets the description of a group.
    */
    function setDescription( &$value )
    {
        $this->Description = ( $value );
    }

    /*!
      Sets the parentID of a group.
    */
    function setParent( $value )
    {
        $this->Parent = ( $value );
    }

    /*!
      Return the title of the link.
    */
    function &title()
    {
        return htmlspecialchars( $this->Title );
    }

    /*!
      Return the title of the link.
    */
    function &description()
    {
        return htmlspecialchars( $this->Description );
    }

    /*!
      Return the parent id of the group.
    */
    function parent()
    {
        return $this->Parent;
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
      Returns the image id.
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
      Delete the current image that belong to this eZLinkGroup object.
    */
    function deleteImage()
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $result, "SELECT ImageID FROM eZLink_LinkGroup WHERE ID='$this->ID'" );

        foreach ( $result as $item )
        {
            $image = new eZImage( $item[$db->fieldName("ImageID")] );
            $image->delete();
        }
        
        $db->query( "UPDATE eZLink_LinkGroup set ImageID='0' WHERE ID='$this->ID'" );
    }
    
    var $ID;
    var $Title;
    var $Description;
    var $Parent;
    var $ImageID;
}

?>


