<?
//
// $Id: ezforum.php,v 1.38 2001/07/03 13:20:56 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
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

//!! eZForum
//! The eZForum class handles forum''s in the database.
/*!

  \sa eZForumMessage \eZForumCategory
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezquery.php" );
include_once( "ezforum/classes/ezforummessage.php" );

include_once( "ezuser/classes/ezusergroup.php" );

class eZForum
{
    /*!
      Constructs a new eZForum object.
    */
    function eZForum( $id="" )
    {
        $this->IsModerated = 0;
        $this->IsAnonymous = 0;
        $this->IsPrivate = 0;
        $this->ModeratorID = 0;
        
        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZForum object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $name = $db->escapeString( $this->Name );
        $description = $db->escapeString( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZForum_Forum" );
            $nextID = $db->nextID( "eZForum_Forum", "ID" );
            
            $res = $db->query( "INSERT INTO eZForum_Forum
                         ( ID, 
                           Name,
                           Description,
                           IsModerated,
                           IsAnonymous,
                           ModeratorID,
                           GroupID,
                           IsPrivate )
                         VALUES
                         ( '$nextID',
                           '$name',
                           '$description',
                           '$this->IsModerated',
                           '$this->IsAnonymous',
                           '$this->ModeratorID',
                           '$this->GroupID',
                           '$this->IsPrivate' )
                                 " );

                        $this->ID = $nextID;
        }
        else
        {
            $res = $db->query( "UPDATE eZForum_Forum SET
                                         Name='$name',
                                         Description='$description',
                                         IsModerated='$this->IsModerated',
                                         IsAnonymous='$this->IsAnonymous',
                                         ModeratorID='$this->ModeratorID',
                                         GroupID='$this->GroupID',
                                         IsPrivate='$this->IsPrivate'
                                 WHERE ID='$this->ID'
                                 " );
        }


        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();
        
        return true;
    }

    /*!
      Deletes a eZForumCategory object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        // delete messages
        $db->query( "DELETE FROM eZForum_Message WHERE ForumID='$this->ID'" );

        // delete category assignments
        $db->query( "DELETE FROM eZForum_ForumCategoryLink WHERE ForumID='$this->ID'" );

        // delete the forum
        $db->query( "DELETE FROM eZForum_Forum WHERE ID='$this->ID'" );

        return true;
    }

    /*!
      Removes all assignments from forum to categories.
    */
    function removeFromForums()
    {
        $db =& eZDB::globalDatabase();

        // delete category assignments
        $db->query( "DELETE FROM eZForum_ForumCategoryLink WHERE ForumID='$this->ID'" );
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        if ( $id != "" )
        {
            $db->array_query( $forum_array, "SELECT * FROM eZForum_Forum WHERE ID='$id'" );
            if ( count( $forum_array ) > 1 )
            {
                die( "Error: Forum's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $forum_array ) == 1 )
            {
                $this->ID =& $forum_array[0][$db->fieldName("ID")];
                $this->Name =& $forum_array[0][$db->fieldName("Name")];
                $this->Description =& $forum_array[0][$db->fieldName("Description")];
                $this->IsModerated =& $forum_array[0][$db->fieldName("IsModerated")];
                $this->IsAnonymous =& $forum_array[0][$db->fieldName("IsAnonymous")];
                $this->ModeratorID =& $forum_array[0][$db->fieldName("ModeratorID")];
                $this->GroupID =& $forum_array[0][$db->fieldName("GroupID")];
                $this->IsPrivate =& $forum_array[0][$db->fieldName("IsPrivate")];

                $ret = true;
            }
            else if( count( $category_array ) == 0 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }


    /*!
      Returns every forum.
    */
    function getAll( )
    {

    }

    /*!
      Returns the messages in a forum.
    */
    function &messages( )
    {
       $db =& eZDB::globalDatabase();

       $db->array_query( $message_array, "SELECT ID, PostingTime FROM
                                          eZForum_Message
                                          WHERE ForumID='$this->ID' AND IsTemporary='0' ORDER BY PostingTime DESC" );

       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] = new eZForumMessage( $message[$db->fieldName("ID")] );
       }

       return $ret;
    }

    /*!
      Returns the messages in every forum matching the query string.
    */
    function &search( $query, $offset, $limit )
    {
       $db =& eZDB::globalDatabase();

       $query = new eZQuery( array( "Topic", "Body" ), $query );

       $query_str = "SELECT ID, PostingTime FROM eZForum_Message WHERE (" .
             $query->buildQuery()  .
             ") GROUP BY ID, PostingTime ORDER BY PostingTime";

       $db->array_query( $message_array, $query_str, array( "Limit" => $limit, "Offset" => $offset ) );
       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] = new eZForumMessage( $message[$db->fieldName("ID")] );
       }

       return $ret;
    }


    /*!
      Returns the total count of a query.
    */
    function &getQueryCount( $queryString )
    {
        $db =& eZDB::globalDatabase();
        $message_array = 0;

        $query = new eZQuery( array( "Topic", "Body" ), $queryString );

        $query_str = "SELECT count(*) AS Count FROM eZForum_Message WHERE (" . $query->buildQuery() . ")";

        $db->array_query( $message_array, $query_str );

        $ret = 0;
        if ( count( $message_array ) == 1 )
            $ret = $message_array[0][$db->fieldName("Count")];
        settype( $ret, "integer" );
        return $ret;
    }


    /*!
      Returns all the messages and submessages as a tree as an array.

      Default limit is set to 30.
    */
    function &messageTree( $offset=0, $limit=30, $showUnApproved=false )
    {
       $db =& eZDB::globalDatabase();

       $approvedCode = "";
       if ( $showUnApproved == false )
       {
           $approvedCode = " AND IsApproved=1 ";
       }

       $db->array_query( $message_array, "SELECT ID, TreeID FROM
                                          eZForum_Message
                                          WHERE ForumID='$this->ID' $approvedCode
                                          AND IsTemporary='0' ORDER BY TreeID
                                          DESC",
                         array( "Limit" => $limit, "Offset" => $offset ) );

       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] =& new eZForumMessage( $message[$db->fieldName("ID")] );
       }

       return $ret;
    }

    /*!
      Returns all the messages and submessages as a tree as an array.

      Default limit is set to 30.
    */
    function &messageTreeArray( $offset=0, $limit=30, $showUnApproved=false, $showReplies=true )
    {
       $db =& eZDB::globalDatabase();

       $approvedCode = "";
       if ( $showUnApproved == false )
       {
           $approvedCode = " AND IsApproved=1 ";
       }

       if ( $showReplies )
       {
            $timeStamp =& eZDateTime::timeStamp( true );            
           
            $db->array_query( $message_array, "SELECT ID, Topic, UserID, PostingTime, Depth,
                                          ( $timeStamp  - PostingTime ) AS Age, TreeID, Body
                                          FROM
                                          eZForum_Message
                                          WHERE ForumID='$this->ID'
                                          AND IsTemporary='0'
                                          $approvedCode
                                          ORDER BY TreeID
                                          DESC",
            array( "Limit" => $limit, "Offset" => $offset ) );
       }
       else
       {
           $db->array_query( $message_array, "SELECT ID, Topic, UserID, PostingTime, Depth,
                                          ( $timeStamp  -  PostingTime ) AS Age, TreeID, ThreadID, Body
                                          FROM eZForum_Message
                                          WHERE ForumID='$this->ID' AND Depth='0'
                                          AND IsTemporary='0'
                                          $approvedCode
                                          ORDER BY TreeID DESC ",
                       array( "Limit" => $limit, "Offset" => $offset ) );
       }
       return $message_array;
    }

    /*!
      Returns all the messages and submessages of a thread as a tree.

      Default limit is set to 100
    */
    function &messageThreadTree( $threadID, $showUnApprived=false, $offset=0, $limit=100 )
    {
       $db =& eZDB::globalDatabase();

       if ( !$showUnApproved )
           $showUnApproved = " AND IsApproved='1' ";
       else
           $showUnApproved = " AND IsApproved='0' ";

       $db->array_query( $message_array, "SELECT ID, TreeID FROM eZForum_Message
                                          WHERE ForumID='$this->ID'
                                          AND ThreadID='$threadID'
                                          AND IsTemporary='0'
                                          $showUnApproved
                                          ORDER BY TreeID DESC",
       array( "Limit" => $limit, "Offset" => $offset ) );
       
       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] = new eZForumMessage( $message[$db->fieldName("ID")] );
       }

       return $ret;
    }


    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns every category which the forum is a part of.
    */
    function categories()
    {
       $db =& eZDB::globalDatabase();

       $db->array_query( $forum_array, "SELECT CategoryID FROM
                                        eZForum_ForumCategoryLink
                                        WHERE ForumID='$this->ID'" );

       $ret = array();

       foreach ( $forum_array as $forum )
       {
           $ret[] = new eZForumCategory( $forum[$db->fieldName("CategoryID")] );
       }

       return $ret;
    }


    /*!
      Returns the name of the forum.
    */
    function &name( $html = true )
    {
       return htmlspecialchars( $this->Name );
    }

    /*!

    */
    function setName($newName)
    {
        $this->Name = $newName;
    }

    /*!

    */
    function &description()
    {
        return htmlspecialchars( $this->Description );
    }

    /*!

    */
    function setDescription($newDescription)
    {
        $this->Description = $newDescription;
    }

    /*!
      Returns true if the forum is moderated, false if not.
    */
    function isModerated()
    {
       $ret = false;
       if ( $this->IsModerated == 1 )
           $ret = true;

        return $ret;
    }

    /*!
      Returns true if the forum allows anonymous posting.
    */
    function isAnonymous()
    {
       $ret = false;
       if ( $this->IsAnonymous == 1 )
           $ret = true;

        return $ret;
    }

    /*!
      Returns the forum moderator as a eZUser object.
    */
    function &moderator()
    {
       $group = false;

       if ( $this->ModeratorID > 0 )
       {
           $group = new eZUserGroup( $this->ModeratorID );
       }

      return $group;
    }


    /*!
      Returns the forum moderator as a eZUser object.
    */
    function &group()
    {
       $group = false;

       if ( $this->GroupID > 0 )
       {
           $group = new eZUserGroup( $this->GroupID );
       }

       return $group;
    }

    /*!
      Sets the forum to be moderated or not.
    */
    function setIsModerated( $value )
    {
       if ( $value == true )
       {
           $this->IsModerated = 1;
       }
       else
       {
           $this->IsModerated = 0;
       }
    }

    /*!
      Sets the forum to allow anonymous posting, or not.
    */
    function setIsAnonymous( $value )
    {
       if ( $value == true )
           $this->IsAnonymous = 1;
       else
           $this->IsAnonymous = 0;
    }

    /*!
      Sets the forum moderator.
    */
   function setModerator( &$group )
    {
       if ( get_class( $group  ) == "ezusergroup" )
       {
          $this->ModeratorID = $group->id();
       }
    }

    /*!
      Sets the forum moderator.
    */
    function setGroup( &$group )
    {
       if ( get_class( $group  ) == "ezusergroup" )
       {
           $this->GroupID = $group->id();
       }
    }


    /*!

    */
    function private()
    {
        return $this->IsPrivate;
    }

    /*!

    */
    function setPrivate($newPrivate)
    {

        $this->IsPrivate = $newPrivate;
    }

    /*!
      Returns the number of threads in the forum.
    */
    function threadCount( $countUnapproved = false )
    {
       $db =& eZDB::globalDatabase();

       $unapprovedSQL = "";
       if( $countUnapproved == false )
           $unapprovedSQL = "AND IsApproved='1'";

       $db->array_query( $message_array, "SELECT Count(ID) AS Count FROM eZForum_Message
                                          WHERE ForumID='$this->ID' AND Depth='0'
                                          AND IsTemporary='0' $unapprovedSQL " );

       $ret = $message_array[0][$db->fieldName( "Count" )];

       setType( $ret, "integer" );

       return $ret;
    }

    /*!
      Returns the number of messages in the forum.
    */
    function messageCount( $countUnapproved = false, $showReplies = false)
    {
       $db =& eZDB::globalDatabase();

       $unapprovedSQL = "";
       if( $countUnapproved == false )
           $unapprovedSQL = "AND IsApproved='1'";

       if ( $showReplies )
       {
           $db->array_query( $message_array, "SELECT ID FROM eZForum_Message
                                          WHERE ForumID='$this->ID'
                                          AND IsTemporary='0'
                                          $unapprovedSQL
                                          GROUP BY ID, ThreadID" );
       }
       else
       {
           $db->array_query( $message_array, "SELECT ID FROM eZForum_Message
                                          WHERE ForumID='$this->ID'
                                          AND IsTemporary='0' $unapprovedSQL" );
       }

       $ret = count( $message_array );

       return $ret;
    }


    var $ID;
    var $Name;
    var $Description;
    var $IsModerated;
    var $IsAnonymous;
    var $IsPrivate;
    var $ModeratorID;
    var $GroupID;
}

?>
