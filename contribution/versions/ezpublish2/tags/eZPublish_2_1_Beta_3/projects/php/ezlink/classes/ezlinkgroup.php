<?
// 
// $Id: ezlinkgroup.php,v 1.52 2001/05/09 16:41:25 ce Exp $
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
    function eZLinkGroup( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;
        if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
            }
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Saves a group to the database.
    */
    function store()
    {
        $title = addslashes( $this->Title );
        $description = addslashes( $this->Description );

        $this->dbInit();
        $this->Database->query( "INSERT INTO eZLink_LinkGroup SET
                ID='$this->ID',
                Title='$title',
                Description='$description',
                ImageID='$this->ImageID',
                Parent='$this->Parent'" );
    }

    /*!
      Update the database.
    */
    function update()
    {
        $title = addslashes( $this->Title );
        $description = addslashes( $this->Description );

        $this->dbInit();
        $this->Database->query( "UPDATE eZLink_LinkGroup SET 
                Title='$title',
                Description='$description',
                Parent='$this->Parent',
                ImageID='$this->ImageID'
                WHERE ID='$this->ID'" );
    }

    /*!
      Delete from database.
    */
    function delete( )
    {
        $this->dbInit();
        $this->Database->query( "DELETE FROM eZLink_Link WHERE LinkGroup='$this->ID'" );
        $this->Database->query( "DELETE FROM eZLink_LinkGroup WHERE ID='$this->ID'" );
    }

    /*!
      Fetch out a group from the database.
    */
    function get( $id )
    {
        $this->dbInit();
        $this->Database->array_query( $linkgroup_array,  "SELECT * FROM eZLink_LinkGroup WHERE ID='$id'" );
        if ( count( $linkgroup_array ) > 1 )
        {
            die( "feil, flere grupper med samme id" );
        }
        else if ( count( $linkgroup_array ) == 1 )
        {
            $this->ID =& $linkgroup_array[ 0 ][ "ID" ];
            $this->Title =& $linkgroup_array[ 0 ][ "Title" ];
            $this->Description =& $linkgroup_array[ 0 ][ "Description" ];
            $this->Parent =& $linkgroup_array[ 0 ][ "Parent" ];
            $this->ImageID = $linkgroup_array[ 0 ][ "ImageID" ];
        }
    }

    /*!
      Print out the group path.
    */
    function &path( $groupID=0 )
    {
        $this->dbInit();
        
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
        
        $this->dbInit();
        $parent_array = array();
        $return_array = array();

        $this->Database->array_query( $parent_array, "SELECT ID FROM eZLink_LinkGroup
                                                      WHERE Parent='$id' ORDER BY Title" );

        for( $i=0; $i<count( $parent_array ); $i++ )
        {
            $return_array[] = new eZLinkGroup( $parent_array[$i][ "ID" ] );
        }

        return $return_array;
                   
    }

    /*!
      Returns the count for subgroup in a group.
     */
    function &getTotalSubLinks( $id, $start_id )
    {
        $this->dbInit();
        
        $count = 0;
        $sibling_array = $this->getByParent( $id );

        if ( $id == $start_id )
        {
            $this->Database->array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE LinkGroup='$id' AND Accepted='Y'" );
            $count += $link_count[0][ "LinkCount" ];
        }
        
        for ( $i=0; $i<count( $sibling_array ); $i++ )
        {
            $group_id =  $sibling_array[ $i][ "ID" ];
            $count += $this->getTotalSubLinks( $group_id, $start_id );
            $this->Database->array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE LinkGroup='$group_id' AND Accepted='Y'" );
            $count += $link_count[0][ "LinkCount" ];            
        }

        return $count;
    }

    /*!
      Returns the count for new links under the group.
      All the links that newer than $new_limit is marked as new.
     */
    function &getNewSubLinks( $id, $start_id, $new_limit )
    {
        $this->dbInit();
        
        $count = 0;
        $sibling_array = $this->getByParent( $id );

        if ( $id == $start_id )
        {
            $this->Database->array_query( $link_count, "SELECT COUNT( ID ) AS LinkCount from eZLink_Link WHERE LinkGroup='$id' AND Accepted='Y' AND ( TO_DAYS( Now() ) - TO_DAYS( Created ) ) <= $new_limit  ORDER BY Title" );
            $count += $link_count[0][ "LinkCount" ];
        }
        
        for ( $i=0; $i<count( $sibling_array ); $i++ )
        {
            $group_id =  $sibling_array[ $i][ "ID" ];
            $count += $this->getNewSubLinks( $group_id, $start_id, $new_limit );
            $this->Database->array_query( $link_count, "SELECT COUNT( ID ) AS LinkCount  from eZLink_Link WHERE LinkGroup='$group_id' AND Accepted='Y' AND ( To_DAYS( Now() ) - TO_DAYS( Created ) ) <= $new_limit  ORDER BY Title" );
            $count += $link_count[0][ "LinkCount" ];            
        }
        return $count;
    }

    /*!
      Return the count of links in incoming.
     */
    function &getTotalIncomingLinks()
    {
        $this->dbInit();
        
        $count = 0;
        $this->Database->array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE Accepted='N'" );
        $count = $link_count[0][ "LinkCount" ];

        return $count;
    }
    
    /*!
      Fetch everything.
    */
    function &getAll()
    {
        $this->dbInit();
        $parnet_array = array();
        $return_array = array();

        $this->Database->array_query( $parent_array, "SELECT ID FROM eZLink_LinkGroup ORDER BY Title" );

        for( $i=0; $i<count( $parent_array ); $i++ )
        {
            $return_array[$i] = new eZLinkGroup( $parent_array[$i]["ID"] );
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
        $this->dbInit();

        $returnArray = array();
        
        if ( $fetchUnAccepted )
            $fetchUnAccepted = "";
        else
            $fetchUnAccepted = " AND Accepted='Y' ";
        
        $this->Database->array_query( $linkArray, "SELECT ID
                                                   FROM eZLink_Link
                                                   WHERE LinkGroup='$this->ID'
                                                   $fetchUnAccepted
                                                   ORDER BY Title
                                                   LIMIT $offset, $limit" );
        foreach( $linkArray as $link )
        {
            $returnArray[] = new eZLink( $link["ID"] );
        }
        return $returnArray;
        
    }
    
    
    /*!
      Returns the total numbers of links in the current category.
    */
    function linkCount( $fetchUnAccepted=false )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->dbInit();

        if ( $fetchUnAccepted )
            $fetchUnAccepted = "";
        else
            $fetchUnAccepted = " AND Accepted='Y' ";

        $query = "SELECT count( ID ) AS Count 
                  FROM eZLink_Link
                  WHERE LinkGroup='$this->ID'
                  $fetchUnAccepted";

        $this->Database->array_query( $linkArray, $query );
        
        return $linkArray[0]["Count"];
    }

    /*!
      Return the id of the group.
    */
    function id()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ID;
    }

    /*!
      Sets the title of a group.
    */
    function setTitle( &$value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Title = ( $value );
    }

    /*!
      Sets the description of a group.
    */
    function setDescription( &$value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = ( $value );
    }

    /*!
      Sets the parentID of a group.
    */
    function setParent( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Parent = ( $value );
    }

    /*!
      Return the title of the link.
    */
    function &title()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return htmlspecialchars( $this->Title );
    }

    /*!
      Return the title of the link.
    */
    function &description()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return htmlspecialchars( $this->Description );
    }

    /*!
      Return the parent id of the group.
    */
    function parent()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Parent;

    }

    /*!
        Set an image for this category.
     */
    function setImage( &$value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
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
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
        $this->dbInit();

        $this->Database->array_query( $result, "SELECT ImageID FROM eZLink_LinkGroup WHERE ID='$this->ID'" );

        foreach ( $result as $item )
        {
            $image = new eZImage( $item["ImageID"] );
            $image->delete();
        }
        
        $this->Database->query( "UPDATE eZLink_LinkGroup set ImageID='0' WHERE ID='$this->ID'" );
    }
    
	/*!
      Initializing the database.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database =& eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Title;
    var $Description;
    var $Parent;
    var $ImageID;

    /// Is true if the object has database connection, false if not.
    var $IsConnected;

    /// database connection indicator
    var $Database;

    /// internal object state
    var $State_;
}

?>


