<?
// 
// $Id: ezforummessage.php,v 1.48 2000/10/12 16:29:29 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Lars Wilhelmsen <lw@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

// REQUIRES class eZUser
//  include( "ezforum/dbsettings.php" );
//  include_once( "$DOCROOT/classes/ezmail.php" );

//!! eZForum
//! The eZForumMessage handles a forum message in the database.
/*!
  
*/

/*!TODO
  Rename tables and sql row names Id -> ID.

  EmailNotice to use enum( true, false) and bool in the class.
*/

include_once( "classes/ezdatetime.php" );
include_once( "ezuser/classes/ezuser.php" );

class eZForumMessage
{
    /*!
      Constructs a new eZForumForum object.
    */
    function eZForumMessage( $id="", $fetch=true )
    {
        $this->IsConnected = false;

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
      Stores a eZForumForum object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {

            if ( $this->ParentID == 0 )
            { // new node

                // find the biggest treeID
                $this->Database->array_query( $result, "SELECT TreeID FROM ezforum_MessageTable ORDER BY TreeID DESC LIMIT 1" );

                if ( count( $result ) > 0 )
                {
                    $this->TreeID = $result[0]["TreeID"] + 1;
                }
                else
                {
                    $this->TreeID = 0;
                }

                // get the biggest thread ID
                $this->Database->array_query( $result, "SELECT ThreadID FROM ezforum_MessageTable WHERE Parent='0' ORDER BY TreeID DESC LIMIT 1" );

                if ( count( $result ) > 0 )
                {
                    $this->ThreadID = $result[0]["ThreadID"] + 1;
                }
                else
                {
                    $this->ThreadID = 0;
                }
                
                     
                $this->Database->query( "INSERT INTO ezforum_MessageTable SET
		                         ForumId='$this->ForumID',
		                         Topic='$this->Topic',
		                         Body='$this->Body',
		                         UserId='$this->UserID',
		                         Parent='$this->ParentID',
		                         TreeID='$this->TreeID',
		                         ThreadID='$this->ThreadID',
		                         EmailNotice='$this->EmailNotice'
                                 " );

                $this->ID = mysql_insert_id();
            }
            else
            { // child node

                // find the treeID and ThreadID of the parent
                $this->Database->array_query( $result, "SELECT TreeID, ThreadID FROM ezforum_MessageTable
                                                        WHERE ID='$this->ParentID'
                                                        ORDER BY TreeID DESC LIMIT 1" );

                if ( count( $result ) == 1 )
                {
                    $parentID = $result[0]["TreeID"];
                    $this->TreeID =  $parentID;

                    $this->ThreadID = $result[0]["ThreadID"];

                    $parentID += 1;
                    
                    // update the whole tree
                    $this->Database->query( "UPDATE ezforum_MessageTable SET TreeID=(TreeID +1 ) WHERE TreeID > $parentID" );

                    
                }
                else
                {
                    print( "<b>ERROR:</b> eZForumMessage::store() parent not found in database.<br /> \n" );
                }
                
                
                $this->Database->query( "INSERT INTO ezforum_MessageTable SET
		                         ForumId='$this->ForumID',
		                         Topic='$this->Topic',
		                         Body='$this->Body',
		                         UserId='$this->UserID',
		                         Parent='$this->ParentID',
		                         TreeID='$this->TreeID',
		                         ThreadID='$this->ThreadID',
		                         EmailNotice='$this->EmailNotice'
                                 " );

                $this->ID = mysql_insert_id();
                
            }
            

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE ezforum_MessageTable SET
		                         ForumId='$this->ForumID',
		                         Topic='$this->Topic',
		                         Body='$this->Body',
		                         UserId='$this->UserID',
		                         Parent='$this->ParentID',
		                         EmailNotice='$this->EmailNotice'
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

        $this->Database->query( "DELETE FROM ezforumMessageTable WHERE ID='$this->ID'" );
        
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
            $this->Database->array_query( $message_array, "SELECT * FROM ezforum_MessageTable WHERE ID='$id'" );
            if ( count( $message_array ) > 1 )
            {
                die( "Error: Message's with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $message_array ) == 1 )
            {
                $this->ID = $message_array[0][ "Id" ];
                $this->ForumID = $message_array[0][ "ForumId" ];
                $this->Topic = $message_array[0][ "Topic" ];
                $this->Body = $message_array[0][ "Body" ];
                $this->UserID = $message_array[0][ "UserId" ];
                $this->ParentID = $message_array[0][ "Parent" ];
                $this->PostingTime = $message_array[0][ "PostingTime" ];
                $this->EmailNotice = $message_array[0][ "EmailNotice" ];

                $this->ThreadID = $message_array[0][ "ThreadID" ];
                $this->TreeID = $message_array[0][ "TreeID" ];
                
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
      Searches the forum and returnes the result.
    */
    function search( $criteria )
    {
        $this->dbInit();
        $query_id = mysql_query( "SELECT Id, Topic, UserId, Parent, PostingTime FROM ezforum_MessageTable
                      WHERE Topic LIKE '%$criteria%' OR Body LIKE '%$criteria%'" )
            or die("Could not execute search, dying...");

        for ( $i = 0; $i < mysql_num_rows( $query_id ); $i++ )
            $resultArray[$i] = mysql_fetch_array( $query_id );

        return $resultArray;
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
    function setForumId( $newForumId )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->ForumID = $newForumId;
    }
    
    /*!
      Returns the parent message.

      If the message is a top level message false is returned.
    */      
    function parent()
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
      
    */      
    function topic()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        return $this->Topic;
    }
        
    /*!
      
    */      
    function setTopic( $newTopic )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        $this->Topic = $newTopic;
    }
        
    /*!
      
    */      
    function body()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        return $this->Body;
    }

    /*!
      
    */      
    function setBody( $newBody )
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
        
        
        return $this->UserId;
    }

    /*!
      Returns the user as a eZUser object.
    */      
    function &user()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $user = new eZUser( $this->UserID );
        
       return $user;
    }
    
    /*!
      
    */      
    function setUserId( $newUserId )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->UserID = $newUserId;
    }

    /*!
      
    */      
    function emailNotice()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        
        return $this->EmailNotice;
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
      Returns the threadID
    */
    function threadID()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->ThreadID;
    }
    

    /*!
      recursiveEmailNotice() : Send a notice by email to users who have requested it.

      $msgId : $message to send a notice about
     */
    function recursiveEmailNotice( $startId, $msgId, &$liste )
    {
        $this->get( $msgId );
        if ( $this->Id != $startId) // root of search - do not check current message
        {
            if ($this->emailNotice() == 'Y')
            {
                if( !in_array( $this->UserId, $liste ) )
                {
                    array_push( $liste, $this->UserId );
                    $email = new eZMail();
                    $usr = new eZUser();
                    $msg = new eZForumMessage;
                    $msg->get( $startId );
                    $usr->get( $this->UserId );
                    $email->setTo( $usr->email() );
                    $email->setFrom( "webmaster@" . $SERVER_NAME );
                    $email->setSubject( $msg->topic() );
                    $email->setBody( $msg->body() );
                    $email->send();
                }    
            }
        }
        else
        {
            array_push( $liste, $this->UserId );
        }
        if( $this->Parent != "" ) $this->recursiveEmailNotice( $startId, $this->Parent, $liste );
    }

    /*!
      
    */      
    function countMessages( $Id )
    {
        $this->dbInit();
        
        $query_id = mysql_query("SELECT COUNT(Id) AS Messages
                             FROM ezforum_MessageTable
                             WHERE ForumId='$Id'
                             AND Parent IS NULL")
             or die("eZForumMessage::countMessages($Id) failed, dying...");
        
        return mysql_result($query_id,0,"Messages");
    }
    
    /*!
      
    */      
    function countReplies( $Id )
    {
        $this->dbInit();
         
        $query_id = mysql_query("SELECT COUNT(Id) AS replies FROM ezforum_MessageTable WHERE Parent='$Id'")
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
            $ret = $this->threadTop( $msg->parent() );
        }
        else
        {
            $ret = $msg;
        }
        
        return $ret;
    }


    /*!
      Returns the recursive level of the message.

      0 is top node, 1 is a child in the first level etc.
    */
    function level( $message=0, $level=0 )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $message == 0 )
           $message = $this;
       
       if ( $message->parent() )
       {
           $level += 1;
           $level += $this->level(  $message->parent(), $level );
       }

       return $level;
    }

    /*!
      \private
      Opens the database for read and write.
    */
    function dbInit( )
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
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

    /// indicates the position in the tree.
    var $TreeID;

    // contains the thread id
    var $ThreadID;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}
?>
