<?
// 
// $Id: ezlinkcategory.php,v 1.1 2001/06/29 07:54:49 br Exp $
//
// Definition of eZLinkCategory class
//
// Bjørn Reiten <br@ez.no>
// Created on: <27-Jun-2001 12:41:48 br>
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
//! The eZLinkCategory class handles URL links.


include_once( "classes/ezdb.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

class eZLinkCategory
{
    /*!
      Constructor
    */
    function eZLinkCategory( $id=-1 )
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

        $db->lock( "eZLink_Category" );

        $nextID = $db->nextID( "eZLink_Category", "ID" );        
        $res = $db->query( "INSERT INTO eZLink_Category
                ( ID, Parent, Title, Name, ImageID, Description )
                VALUES
                ( '$nextID',
                  '$this->Parent',
                  '$title',
                  '$this->ImageID',
                  '$description')" );

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

        $res = $db->query( "UPDATE eZLinkCategory SET 
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
      Remove links from the database.
    */
    function removeLink( $value, $categoryid = false )
    {
        if( get_class( $value ) == "ezlink" )
            $linkID = $value->id();
        else if (is_nummeric( $value ) )
            $linkID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;

        $db =& eZDB::globaldatabase();
        $db->query( "DELETE FROM eZLink_LinkCategoryLink
                     WHERE CategoryID='$categoryid' AND
                           LinkID='$linkID'" );
    }

    
    /*!
      Add a link to the database.
    */
    function addLink( $value, $categoryid = false )
    {
        if ( get_class( $value ) == "ezlink" )
            $linkID = $value->id();
        else if ( is_nummeric( $value ) )
            $linkID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;
            
        $db =& eZDB::globalDatabase();
        
        $nextID = $db->nextID( "eZLink_LinkCategoryLink", "ID" );
        $db->query( "INSERT INTO eZLink_LinkCategoryLink
                     ( ID, LinkID, CategoryID )
                     VALUES
                     ( '$nextID',
                       '$linkID',
                       '$categoryid')" );
    }
    
    /*!
      Delete from database.
    */
    function delete( )
    {
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZLink_Link WHERE LinkCategory='$this->ID'" );
        $db->query( "DELETE FROM eZLink_Category WHERE ID='$this->ID'" );
    }

    /*!
      Fetch out a group from the database.
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $linkcategory_array,  "SELECT * FROM eZLink_Category WHERE ID='$id'" );
        if ( count( $linkcategory_array ) > 1 )
        {
            die( "error, more groups with the same id" );
        }
        else if ( count( $linkcategory_array ) == 1 )
        {
            $this->ID =& $linkcategory_array[0][$db->fieldName("ID")];
            $this->Title =& $linkcategory_array[0][$db->fieldName("Title")];
            $this->Description =& $linkcategory_array[0][$db->fieldName("Description")];
            $this->Parent =& $linkcategory_array[0][$db->fieldName("Parent")];
            $this->ImageID = $linkcategory_array[0][$db->fieldName("ImageID")];
        }
    }

    /*!
      Print out the group path.
    */
    function &path( $groupID=0 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $categoryID == 0 )
        {
            $categoryID = $this->ID;
        }
        
        $category = new eZLinkCategory( $categoryID );
        
        $path = array();
        
        $parent = $category->parent();
        
        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent ) );
        }
        else
        {
//              array_push( $path, $category->title() );
        }

        if ( $categoryID != 0 )
            array_push( $path, array( $category->id(), $category->title() ) );                                
        
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

        $db->array_query( $parent_array, "SELECT ID FROM eZLink_LinkCategory
                                                 WHERE Parent='$id' ORDER BY Title" );

        for( $i=0; $i<count( $parent_array ); $i++ )
        {
            $return_array[] = new eZLinkCategory( $parent_array[$i][$db->fieldName("ID")] );
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
            $db->array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE LinkCategory='$id' AND Accepted='1'" );
            $count += $link_count[0][$db->fieldName("LinkCount")];
        }
        
        for ( $i=0; $i<count( $sibling_array ); $i++ )
        {
            $category_id =  $sibling_array[ $i][$db->fieldName("ID")];
            $count += $this->getTotalSubLinks( $category_id, $start_id );
            $db->array_query( $link_count, "SELECT COUNT(ID) AS LinkCount FROM eZLink_Link WHERE LinkCategory='$category_id' AND Accepted='1'" );
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

            $db->array_query( $link_count, "SELECT COUNT( ID ) AS LinkCount from eZLink_Link WHERE LinkCategory='$id'
                                                        AND Accepted='1' AND ( ( $timeStamp - Created ) <= $new_limit  ) ORDER BY Title" );
            $count += $link_count[0][$db->fieldName("LinkCount")];
        }
        
        for ( $i=0; $i<count( $sibling_array ); $i++ )
        {
            $category_id =  $sibling_array[ $i][$db->fieldName("ID")];
            $count += $this->getNewSubLinks( $category_id, $start_id, $new_limit );
            $db->array_query( $link_count, "SELECT COUNT( ID ) AS LinkCount  from eZLink_Link WHERE LinkCategory='$category_id'
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

        $db->array_query( $parent_array, "SELECT ID FROM eZLink_Category ORDER BY Title" );

        for( $i=0; $i<count( $parent_array ); $i++ )
        {
            $return_array[$i] = new eZLinkCategory( $parent_array[$i][$db->fieldName("ID")] );
        }

        return $return_array;
    }

    /*!
      Fetch everything and return the result in a tree.
    */
    function &getTree( $parentID=0, $level=0 )
    {
        $category = new eZLinkCategory( $parentID );

        $categoryList = $category->getByParent( $category, true );
        
        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $return_array[] = new eZLinkCategory( $category->id() ), $level ) );
            
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
                          "SELECT ID
                           FROM eZLink_Link
                           WHERE LinkCategory='$this->ID'
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
                  WHERE LinkCategory='$this->ID'
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

        $db->array_query( $result, "SELECT ImageID FROM eZLink_Category WHERE ID='$this->ID'" );

        foreach ( $result as $item )
        {
            $image = new eZImage( $item[$db->fieldName("ImageID")] );
            $image->delete();
        }
        
        $db->query( "UPDATE eZLink_Category set ImageID='0' WHERE ID='$this->ID'" );
    }
    
    var $ID;
    var $Title;
    var $Description;
    var $Parent;
    var $ImageID;
}

?>
