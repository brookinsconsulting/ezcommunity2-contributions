<?
// 
// $Id: ezforummessage.php,v 1.69 2001/02/20 09:52:35 pkej Exp $
//
// Definition of eZCompany class
//
// Lars Wilhelmsen <lw@ez.no>
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
//! The eZForumMessage handles a forum message in the database.
/*!
  Handles messages in forums..
*/

/*!TODO
  EmailNotice to use enum( true, false) and bool in the class.
*/

include_once( "classes/ezdatetime.php" );
include_once( "ezuser/classes/ezuser.php" );

class eZForumMessage
{
    /*!
      Constructs a new eZForumMessage object.
    */
    function eZForumMessage( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        $this->IsApproved = true;
        $this->IsTemporary = false;
        
        $this->ParentID = 0;
        
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
      Stores a eZForumMessage object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {

            if ( $this->ParentID == 0 )
            { // new node

                // find the biggest treeID
                $this->Database->array_query( $result, "SELECT TreeID FROM eZForum_Message ORDER BY TreeID DESC LIMIT 1" );

                $this->Depth = 0;
                if ( count( $result ) > 0 )
                {
                    $this->TreeID = $result[0]["TreeID"] + 1;
                }
                else
                {
                    $this->TreeID = 0;
                }

                // get the biggest thread ID
                $this->Database->array_query( $result, "SELECT ThreadID FROM eZForum_Message WHERE Parent='0' ORDER BY TreeID DESC LIMIT 1" );

                if ( count( $result ) > 0 )
                {
                    $this->ThreadID = $result[0]["ThreadID"] + 1;
                }
                else
                {
                    $this->ThreadID = 0;
                }                
                     
                $this->Database->query( "INSERT INTO eZForum_Message SET
		                         ForumID='$this->ForumID',
		                         Topic='$this->Topic',
		                         Body='$this->Body',
		                         UserID='$this->UserID',
		                         Parent='$this->ParentID',
		                         TreeID='$this->TreeID',
		                         ThreadID='$this->ThreadID',
		                         Depth='$this->Depth',
		                         EmailNotice='$this->EmailNotice',
		                         IsApproved='$this->IsApproved',
		                         IsTemporary='$this->IsTemporary',
                                 PostingTime=now()
       
                                 " );

                $this->ID = mysql_insert_id();
            }
            else
            { // child node

                // find the TreeID, ThreadID and Depth of the parent
                $this->Database->array_query( $result, "SELECT TreeID, ThreadID, Depth FROM eZForum_Message
                                                        WHERE ID='$this->ParentID'
                                                        ORDER BY TreeID DESC LIMIT 1" );

                if ( count( $result ) == 1 )
                {
                    $parentID = $result[0]["TreeID"];
                    $this->TreeID =  $parentID;

                    $this->ThreadID = $result[0]["ThreadID"];

                    $d = $result[0]["Depth"];
                    setType( $d, "integer" );
                    
                    $this->Depth = $d + 1;
                    
                    // update the whole tree''s ThreeID.
                    $this->Database->query( "UPDATE eZForum_Message SET TreeID=(TreeID +1 ), PostingTime=PostingTime WHERE TreeID >= $parentID" );

                    $this->Database->query( "INSERT INTO eZForum_Message SET
		                         ForumID='$this->ForumID',
		                         Topic='$this->Topic',
		                         Body='$this->Body',
		                         UserID='$this->UserID',
		                         Parent='$this->ParentID',
		                         TreeID='$this->TreeID',
		                         ThreadID='$this->ThreadID',
		                         Depth='$this->Depth',
		                         EmailNotice='$this->EmailNotice',
		                         IsApproved='$this->IsApproved',
		                         IsTemporary='$this->IsTemporary',
                                 PostingTime=now()
                                 " );

                    $this->ID = mysql_insert_id();
                }
                else
                {
                    print( "<b>ERROR:</b> eZForumMessage::store() parent not found in database.<br /> \n" );
                }                
            }
            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZForum_Message SET
		                         ForumID='$this->ForumID',
		                         Topic='$this->Topic',
		                         Body='$this->Body',
		                         UserID='$this->UserID',
		                         Parent='$this->ParentID',
		                         EmailNotice='$this->EmailNotice',
		                         IsApproved='$this->IsApproved',
		                         IsTemporary='$this->IsTemporary',
                                 PostingTime=PostingTime
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
        $this->dbInit();

        $this->Database->query( "DELETE FROM eZForum_Message WHERE ID='$this->ID'" );
        
        return true;
    }
    

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $id != "" )
        {
            $this->Database->array_query( $message_array, "SELECT * FROM eZForum_Message WHERE ID='$id'" );
            if ( count( $message_array ) > 1 )
            {
                die( "Error: Message's with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $message_array ) == 1 )
            {
                $this->ID =& $message_array[0][ "ID" ];
                $this->ForumID =& $message_array[0][ "ForumID" ];
                $this->Topic =& $message_array[0][ "Topic" ];
                $this->Body =& $message_array[0][ "Body" ];
                $this->UserID =& $message_array[0][ "UserID" ];
                $this->ParentID =& $message_array[0][ "Parent" ];
                $this->PostingTime =& $message_array[0][ "PostingTime" ];
                $this->EmailNotice =& $message_array[0][ "EmailNotice" ];

                $this->IsApproved =& $message_array[0][ "IsApproved" ];
                $this->IsTemporary =& $message_array[0][ "IsTemporary" ];

                $this->ThreadID =& $message_array[0][ "ThreadID" ];
                $this->TreeID =& $message_array[0][ "TreeID" ];                
                $this->Depth =& $message_array[0][ "Depth" ];
                
                
                $this->State_ = "Coherent";
                $ret = true;
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }

    /*!
      Returns every message as an array of eZForumCategory objects.
    */
    function getAll( )
    {
        $this->dbInit();

        $ret = array();

        $this->dbInit();

        $this->Database->array_query( $message_array, "SELECT ID FROM
                                                       eZForum_Message" );
                                                     
        $ret = array();

        foreach ( $message_array as $message )
        {
            $ret[] =& new eZForumMessage( $message["ID"] );
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
      
    */      
    function forumID()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ForumID;
    }
    
    /*!
      
    */      
    function setForumID( $newForumID )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->ForumID = $newForumID;
    }

    /*!
      Sets the message to be approved or not.
    */      
    function setIsApproved( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
           $this->IsApproved = 1;
       else
           $this->IsApproved = 0;           
    }


    /*!
      Sets the message to be temporary or not.
    */      
    function setIsTemporary( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
           $this->IsTemporary = 1;
       else
           $this->IsTemporary = 0;           
    }


    /*!
      Returns true if the message is approved.
    */
    function isApproved()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $this->IsApproved == 1 )
           return true;
       else
           return false;
           
    }
    
    /*!
      Returns true if the message is a temporary item.
    */
    function isTemporary()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $this->IsTemporary == 1 )
           return true;
       else
           return false;
           
    }
    
    /*!
      Returns the parent message.

      If the message is a top level message false is returned.
    */      
    function &parent()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;

       if ( $this->ParentID != 0 )
           $ret = new eZForumMessage( $this->ParentID );
        
       return $ret;
    }
    
    /*!
      
    */      
    function setParent( $newParent )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->ParentID = $newParent;    
    }

    /*!
      Returns the topic of the message.
    */      
    function &topic()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Topic;
    }
        
    /*!
      
    */      
    function setTopic( &$newTopic )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        $this->Topic = $newTopic;
    }
        
    /*!
      Returns the body of the forum message.
    */      
    function &body( $htmlchars=true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( $htmlchars == true )
       {           
           return htmlspecialchars( $this->Body );
       }
       else
       {
           return $this->Body;
       }
    }

    /*!
      
    */      
    function setBody( &$newBody )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        $this->Body = $newBody;
    }
    
    /*!
      
    */      
    function userID()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        return $this->UserID;
    }

    /*!
      Returns the user as a eZUser object.
    */      
    function &user()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $user =& new eZUser( $this->UserID );
        
       return $user;
    }
    
    /*!
      
    */      
    function setUserID( $newUserID )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->UserID = $newUserID;
    }

    /*!
      
    */      
    function emailNotice()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( $this->EmailNotice == "Y" )
           $ret = true;    
        
       return $ret;
    }

    /*!
      
    */      
    function setEmailNotice( $newEmailNotice )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        $this->EmailNotice = $newEmailNotice;
    }

    /*!
      Returns the depth of the message.
    */
    function depth()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Depth;
    }

    /*!
      
    */      
    function enableEmailNotice()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        $this->setEmailNotice( "Y" );
    }

    /*!
      
    */      
    function disableEmailNotice()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        $this->setEmailNotice( "N" );
    }


    /*!
      Returns the postimg time as a eZTimeDate object.
    */
    function &postingTime()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();

       $dateTime->setMySQLTimeStamp( $this->PostingTime );
       
       return $dateTime;
    }

    /*!
      Returns the threadID. Each new posting to a forum creates
      a new thread. Every reply to that message belongs to the
      same thread.
    */
    function threadID()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->ThreadID;
    }

    /*!
      Returns the treeID. The tree id is an integer which
      indicates the position of the message in the forum.
      Higher number is newer/higher up in the tree. 0 is the
      first message.
    */
    function treeID()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->TreeID;
    }

    
    /*!
      Returns the number of messages.
    */      
    function countMessages( $ID )
    {
        $this->dbInit();
        
        $query_id = mysql_query("SELECT COUNT(ID) AS Messages
                             FROM eZForum_Message
                             WHERE ForumID='$ID'
                             AND Parent IS NULL")
             or die("eZForumMessage::countMessages($ID) failed, dying...");
        
        return mysql_result($query_id,0,"Messages");
    }
    
    /*!
      
    */      
    function countReplies( $ID )
    {
        $this->dbInit();
         
        $query_id = mysql_query("SELECT COUNT(ID) AS replies FROM eZForum_Message WHERE Parent='$ID'")
             or die("could not count replies, dying");
         
        return mysql_result($query_id,0,"replies");
    }

    /*!
      Returns the first message in a thread as a eZForumMessage object.
      
      *warning* This function is recursive!
     */
    function threadTop( &$msg )
    {
        $this->dbInit();

        $ret = 0;
        
        if ( $msg->parent() != 0 )
        {
            $parent =& $msg->parent();
            $ret = $this->threadTop( $parent  );
        }
        else
        {
            $ret = $msg;
        }
        
        return $ret;
    }

    /*!
      Get all the messages which is not approved
    */
    function getAllNotApproved( )
    {
        $this->dbInit();

        $ret = array();

        $this->dbInit();

        $this->Database->array_query( $message_array, "SELECT ID FROM eZForum_Message WHERE IsApproved='0'" );
        $ret = array();

        foreach ( $message_array as $message )
        {
            $ret[] =& new eZForumMessage( $message["ID"] );
        }
        
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
            $this->Database =& eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }
    
    var $ID;
    var $ForumID;
    var $ParentID;
    var $Topic;
    var $Body;
    var $UserID;
    var $PostingTime;
    var $EmailNotice;
    var $IsApproved;
    var $IsTemporary;

    /// indicates the position in the tree.
    var $TreeID;

    // contains the thread id
    var $ThreadID;

    // indicates the depth of the message in the tree
    var $Depth;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}
?>
