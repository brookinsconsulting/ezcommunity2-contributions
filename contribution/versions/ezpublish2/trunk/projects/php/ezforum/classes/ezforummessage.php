<?
/*!
    $Id: ezforummessage.php,v 1.8 2000/07/24 10:39:03 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:05:29 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
// REQUIRES class eZUser
include_once( "ezforum/dbsettings.php" );
include_once( "$DOCROOT/classes/ezmail.php" );

class eZforumMessage
{
    var $Id;
    var $ForumId;
    var $Parent;
    var $Topic;
    var $Body;
    var $UserId;
    var $PostingTime;
    var $EmailNotice;
    
    function eZforumMessage($ForumId = 0)
    {
        $this->ForumId = $ForumId;
    }

    function newMessage()
    {
        unset($Id);
    }

    function get($Id)
    {
        openDB();
            
        $query_id = mysql_query("SELECT ForumId, Parent, Topic,
                                                          Body,
                                                          UserId,
                                                          PostingTime,
                                                          EmailNotice
                         FROM MessageTable WHERE Id='$Id'")
             or die("get()");
            
        $results = mysql_fetch_array( $query_id );
            
        $this->Id = $Id;
        $this->Topic = $results["Topic"];
        $this->Body = $results["Body"];
        $this->Parent = $results["Parent"];
        $this->UserId = $results["UserId"];
        $this->PostingTime = $results["PostingTime"];
        $this->EmailNotice = $results["EmailNotice"];
    }
        
    function getHeaders($forum_id,$Parent = "NULL", $startMessage = "0",$maxMessages = "25")
    {
        global $eZUser;
        $usr = new eZUser;
        
        openDB();
                
        if ($Parent == "NULL")
        {
            $optstr = "Parent IS NULL";
        }
        else
        {
            $optstr = "Parent='$Parent'";
        }
            
        $query_string = "SELECT Id,Topic, Body, UserId,
                 DATE_FORMAT(PostingTime,'%k:%i:%s %e/%c/%y') AS PostingTime
                 FROM MessageTable WHERE ForumId='$forum_id' AND " . $optstr . " ORDER BY PostingTime";
            
        $query_id = mysql_query( $query_string )
             or die("eZforumMessage::getHeaders() failed, dying...");
            
        for ($i = 0;$i < mysql_num_rows($query_id); $i++)
        {
            $resultArray[$i] = mysql_fetch_array($query_id);
            $resultArray[$i]["UserId"] = $usr->resolveUser($resultArray[$i]["UserId"]);
        }
            
        return $resultArray;
    }

    function store()
    {
        openDB();
            
        $this->ForumId = addslashes( $this->ForumId );
        $this->Parent = addslashes( $this->Parent );
        $this->Topic = addslashes( $this->Topic );
        $this->Body = addslashes( $this->Body );
        $this->UserId = addslashes( $this->UserId );
        $this->PostingTime = addslashes( $this->PostingTime );
                    
        if ($this->Id)
        {
            mysql_query("UPDATE MessageTable SET ForumId = '$this->ForumId',
                                                     Parent = '$this->Parent',
                                                     Topic = '$this->Topic',
                                                     Body = '$this->Body',
                                                     UserId = '$this->UserId',
                                                     PostingTime = '$this->PostingTime',
                                                     EmailNotice = '$this->EmailNotice'
                             WHERE Id='$this->Id'")
                or die("store() near update");
                
            return $this->Id;            
        }
        else
        {
            if ( $this->Parent != "" )
            {
                $tmp = "'$this->Parent', ";
                $val = "Parent, ";
            }

            if ( $this->EmailNotice == "")
            {
                $this->EmailNotice = "N";
            }
            $query_str = "INSERT INTO MessageTable(ForumId, " . $val . "Topic, Body, UserId, EmailNotice)
                                         VALUES('$this->ForumId'," . $tmp . " '$this->Topic',
                                         '$this->Body', '$this->UserId', '$this->EmailNotice')";
            mysql_query($query_str)
                or die("store() near insert");
	    $temp = array();
            $msg_id = mysql_insert_id();
            $this->recursiveEmailNotice( $msg_id, $msg_id, $temp );
            return $msg_id; 
        }
    }
        
    function delete($Id)
    {
        openDB();
            
        mysql_query("DELETE FROM MessageTable WHERE Id='$Id'")
            or die("delete()");    
    }
        
    function search()
    {
        // not implemented
    }
    
    function id()
    {
        return $this->Id;
    }
        
    function forumId()
    {
        return $this->ForumId;
    }
    function setForumId($newForumId)
    {
        $this->ForumId = $newForumId;
    }
    
    function parent()
    {
        return $this->parent;
    }
    function setParent($newParent)
    {
        $this->Parent = $newParent;    
    }

    function topic()
    {   
        return $this->Topic;
    }
        
    function setTopic($newTopic)
    {
        $this->Topic = $newTopic;
    }
        
    function body()
    {
        return $this->Body;
    }

    function setBody($newBody)
    {
        $this->Body = $newBody;
    }
    
    function user()
    {
        return $this->UserId;
    }
        
    function setUserId($newUserId)
    {
        $this->UserId = $newUserId;
    }

    function emailNotice()
    {
        return $this->EmailNotice;
    }

    function setEmailNotice( $newEmailNotice )
    {
        $this->EmailNotice = $newEmailNotice;
    }

    function enableEmailNotice()
    {
        $this->setEmailNotice( "Y" );
    }

    function disableEmailNotice()
    {
        $this->setEmailNotice( "N" );
    }
    
    function postingTime()
    {
        $t = $this->PostingTime;
        $returnTime = $t[4] . $t[5] ."/". $t[2] . $t[3] ."/20". $t[0] . $t[1] . " ";
        $returnTime .= $t[6] . $t[7] . ":" . $t[8] . $t[9] . ":" . $t[10] . $t[11];
        
        return $returnTime;
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
                    $usr->get( $this->UserId );
                    $email->setTo( $usr->email() );
                    $email->setFrom( "webmaster@" . $SERVER_NAME );
                    $email->setSubject( $this->Topic );
                    $email->setBody("Du har fått svar på tiltale.");
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
}
?>

