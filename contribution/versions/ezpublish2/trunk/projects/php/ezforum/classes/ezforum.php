<?
//
// $Id: ezforum.php,v 1.33 2001/05/16 09:16:37 wojciechp Exp $
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
    function eZForum( $id="", $fetch=true )
    {
        if ( $id != "" )
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
      Stores a eZForum object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name = addslashes( $this->Name );
        $description = addslashes( $this->Description );

        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZForum_Forum SET
                                         Name='$name',
                                         Description='$description',
                                         IsModerated='$this->IsModerated',
                                         IsAnonymous='$this->IsAnonymous',
                                         ModeratorID='$this->ModeratorID',
                                         GroupID='$this->GroupID',
                                         IsPrivate='$this->IsPrivate'
                                 " );

                        $this->ID = $db->insertID();

            $this->State_ = "Coherent";
        }
        else
        {
            $db->query( "UPDATE eZForum_Forum SET
                                         Name='$name',
                                         Description='$description',
                                         IsModerated='$this->IsModerated',
                                         IsAnonymous='$this->IsAnonymous',
                                         ModeratorID='$this->ModeratorID',
                                         GroupID='$this->GroupID',
                                         IsPrivate='$this->IsPrivate'
                                 WHERE ID='$this->ID'
                                 " );

            $this->State_ = "Coherent";
        }

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
                $this->ID =& $forum_array[0][ "ID" ];
                $this->Name =& $forum_array[0][ "Name" ];
                $this->Description =& $forum_array[0][ "Description" ];
                $this->IsModerated =& $forum_array[0][ "IsModerated" ];
                $this->IsAnonymous =& $forum_array[0][ "IsAnonymous" ];
                $this->ModeratorID =& $forum_array[0][ "ModeratorID" ];
                $this->GroupID =& $forum_array[0][ "GroupID" ];
                $this->IsPrivate =& $forum_array[0][ "IsPrivate" ];

                $this->State_ = "Coherent";
                $ret = true;
            }
            else if( count( $category_array ) == 0 )
            {
                $this->ID = 0;
                $this->State_ = "New";
            }
        }
        else
        {
            $this->State_ = "Dirty";
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $db =& eZDB::globalDatabase();

       $db->array_query( $message_array, "SELECT ID FROM
                                                       eZForum_Message
                                                       WHERE ForumID='$this->ID' AND IsTemporary='0' ORDER BY PostingTime DESC" );

       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] = new eZForumMessage( $message["ID"] );
       }

       return $ret;
    }

    /*!
      Returns the messages in every forum matching the query string.
    */
    function &search( &$query, $offset, $limit )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $db =& eZDB::globalDatabase();

       $query = new eZQuery( array( "Topic", "Body" ), $query );

       $query_str = "SELECT ID FROM eZForum_Message WHERE (" .
             $query->buildQuery()  .
             ") ORDER BY PostingTime LIMIT $offset, $limit";

       $db->array_query( $message_array, $query_str );
       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] = new eZForumMessage( $message["ID"] );
       }

       return $ret;
    }


    /*!
      Returns the total count of a query.
    */
    function &getQueryCount( $query  )
    {
        $db =& eZDB::globalDatabase();
        $message_array = 0;

        $query = new eZQuery( array( "Topic", "Body" ), $query );

        $query_str = "SELECT count(ID) AS Count FROM eZForum_Message WHERE (" . $query->buildQuery() . ") ORDER BY PostingTime";

        $db->array_query( $message_array, $query_str );

        $ret = 0;
        if ( count( $message_array ) == 1 )
            $ret = $message_array[0]["Count"];

        return $ret;
    }


    /*!
      Returns all the messages and submessages as a tree as an array.

      Default limit is set to 30.
    */
    function &messageTree( $offset=0, $limit=30, $showUnApproved=false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $db =& eZDB::globalDatabase();

       $approvedCode = "";
       if ( $showUnApproved == false )
       {
           $approvedCode = " AND IsApproved=1 ";
       }

       $db->array_query( $message_array, "SELECT ID FROM
                                          eZForum_Message
                                          WHERE ForumID='$this->ID' $approvedCode
                                          AND IsTemporary='0' ORDER BY TreeID
                                          DESC LIMIT $offset,$limit" );

       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] =& new eZForumMessage( $message["ID"] );
       }

       return $ret;
    }

    /*!
      Returns all the messages and submessages as a tree as an array.

      Default limit is set to 30.
    */
    function &messageTreeArray( $offset=0, $limit=30, $showUnApproved=false, $showReplies=true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $db =& eZDB::globalDatabase();

       $approvedCode = "";
       if ( $showUnApproved == false )
       {
           $approvedCode = " AND IsApproved=1 ";
       }

       if ( $showReplies )
       {
           $db->array_query( $message_array, "SELECT ID, Topic, UserID, PostingTime, Depth,
                                          ( UNIX_TIMESTAMP( now() + 0 )  - UNIX_TIMESTAMP( PostingTime )) AS Age
                                          FROM
                                          eZForum_Message
                                          WHERE ForumID='$this->ID'
                                          AND IsTemporary='0'
                                          $approvedCode
                                          ORDER BY TreeID
                                          DESC LIMIT $offset,$limit" );
       }
       else
       {
           $db->array_query( $message_array, "SELECT COUNT(ThreadID) as Count, ID, Topic, UserID, PostingTime, Depth,
                                          ( UNIX_TIMESTAMP( now() + 0 )  - UNIX_TIMESTAMP( PostingTime )) AS Age
                                          FROM eZForum_Message
                                          WHERE ForumID='$this->ID'
                                          AND IsTemporary='0'
                                          $approvedCode
                                          GROUP BY ThreadID
                                          ORDER BY TreeID
                                          DESC LIMIT $offset,$limit" );
       }
       return $message_array;
    }

    /*!
      Returns all the messages and submessages of a thread as a tree.

      Default limit is set to 100
    */
    function &messageThreadTree( $threadID, $showUnApprived=false, $offset=0, $limit=100 )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $db =& eZDB::globalDatabase();

       if ( !$showUnApproved )
           $showUnApproved = " AND IsApproved='1' ";
       else
           $showUnApproved = " AND IsApproved='0' ";

       $db->array_query( $message_array, "SELECT ID FROM eZForum_Message
                                          WHERE ForumID='$this->ID'
                                          AND ThreadID='$threadID'
                                          AND IsTemporary='0'
                                          $showUnApproved
                                          ORDER BY TreeID DESC
                                          LIMIT $offset,$limit" );
       $ret = array();

       foreach ( $message_array as $message )
       {
           $ret[] = new eZForumMessage( $message["ID"] );
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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $db =& eZDB::globalDatabase();

       $db->array_query( $forum_array, "SELECT CategoryID FROM
                                                       eZForum_ForumCategoryLink
                                                       WHERE ForumID='$this->ID'" );

       $ret = array();

       foreach ( $forum_array as $forum )
       {
           $ret[] = new eZForumCategory( $forum["CategoryID"] );
       }

       return $ret;
    }


    /*!
      Returns the name of the forum.
    */
    function &name( $html = true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->Name );
    }

    /*!

    */
    function setName($newName)
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );


        $this->Name = $newName;
    }

    /*!

    */
    function &description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );


        return htmlspecialchars( $this->Description );
    }

    /*!

    */
    function setDescription($newDescription)
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );


        $this->Description = $newDescription;
    }

    /*!
      Returns true if the forum is moderated, false if not.
    */
    function isModerated()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );


        $this->IsPrivate = $newPrivate;
    }

    /*!
      Returns the number of threads in the forum.
    */
    function threadCount( $countUnapproved = false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $db =& eZDB::globalDatabase();

       $unapprovedSQL = "";
       if( $countUnapproved == false )
           $unapprovedSQL = "AND IsApproved='1'";

       $db->array_query( $message_array, "SELECT ID FROM eZForum_Message
                                          WHERE ForumID='$this->ID'
                                          AND IsTemporary='0' $unapprovedSQL GROUP BY ThreadID" );

       $ret = count( $message_array );

       return $ret;
    }

    /*!
      Returns the number of messages in the forum.
    */
    function messageCount( $countUnapproved = false, $showReplies = false)
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
                                          GROUP BY ThreadID" );
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



    /*!
      \private
      Opens the database for read and write.
    */
    function dbInit( )
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Name;
    var $Description;
    var $IsModerated;
    var $IsAnonymous;
    var $IsPrivate;
    var $ModeratorID;
    var $GroupID;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
}

?>
