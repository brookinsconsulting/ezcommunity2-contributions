<?
/*!
    $Id: ezforummessage.php,v 1.21 2000/07/26 09:15:29 bf-cvs Exp $

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

    function get( $Id )
    {
        openDB();
            
        $query_id = mysql_query("SELECT ForumId, Parent, Topic,
                                                          Body,
                                                          UserId,
                                                          PostingTime,
                                                          EmailNotice
                         FROM MessageTable WHERE Id='$Id'")
             or die("eZforumMessage::get($Id) failed, dying...");
            
        $results = mysql_fetch_array( $query_id );

        print( $results["Id"] );
        $this->Id = $Id;
        $this->Topic = $results["Topic"];
        $this->Body = $results["Body"];
        $this->Parent = $results["Parent"];
        $this->UserId = $results["UserId"];
        $this->PostingTime = $results["PostingTime"];
        $this->EmailNotice = $results["EmailNotice"];
    }
        
    function getAllHeaders( $forum_id )
    {
	openDB();

        $query_string = "SELECT Id,Topic, Body, UserId, Parent, EmailNotice, 
                 DATE_FORMAT(PostingTime,'%k:%i:%s %e/%c/%y') AS PostingTimeFormated
                 FROM MessageTable WHERE ForumId='$forum_id' ORDER BY PostingTime DESC";
            
        $query_id = mysql_query( $query_string )
             or die("eZforumMessage::getAllHeaders() failed, dying...");
            
        for ( $i = 0; $i < mysql_num_rows( $query_id ); $i++ )
            $resultArray[$i] = mysql_fetch_array( $query_id );

        return $resultArray;
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
            
        $query_string = "SELECT Id,Topic, Body, UserId, Parent, EmailNotice, 
                 DATE_FORMAT(PostingTime,'%k:%i:%s %e/%c/%y') AS PostingTimeFormated
                 FROM MessageTable WHERE ForumId='$forum_id' AND " . $optstr . " ORDER BY PostingTime DESC";
            
        $query_id = mysql_query( $query_string )
             or die("eZforumMessage::getHeaders() failed, dying...");
            
        for ($i = 0;$i < mysql_num_rows($query_id); $i++)
        {
            $resultArray[$i] = mysql_fetch_array($query_id);
            $resultArray[$i]["UserId"] = $usr->resolveUser( $resultArray[$i]["UserId"] );
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
        
    function search( $criteria )
    {
        openDB();
        $query_id = mysql_query( "SELECT Id, Topic, UserId, Parent, PostingTime FROM MessageTable
                      WHERE Topic LIKE '%$criteria%' OR Body LIKE '%$criteria%'" )
            or die("Could not execute search, dying...");

        for ( $i = 0; $i < mysql_num_rows( $query_id ); $i++ )
            $resultArray[$i] = mysql_fetch_array( $query_id );

        return $resultArray;
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
        return $this->Parent;
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
    
    function userId()
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
    
    function formatTime( $t )
    {
        $returnTime = $t[4] . $t[5] ."/". $t[2] . $t[3] ."/20". $t[0] . $t[1] . " ";
        $returnTime .= $t[6] . $t[7] . ":" . $t[8] . $t[9] . ":" . $t[10] . $t[11];
        
        return $returnTime;
    }

    function postingTime()
    {
        return $this->formatTime( $this->PostingTime );
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

    function countReplies( $Id )
    {
        openDB();
         
        $query_id = mysql_query("SELECT COUNT(Id) AS replies FROM MessageTable WHERE Parent='$Id'")
             or die("could not count replies, dying");
         
        return mysql_result($query_id,0,"replies");
    }

    /*
      Henter ut toppthreaden av gjeldende meldingstråd.
      WARNING: Denne funksjonen er rekursiv.
     */
    function getTopMessage( $id )
    {
        $ret_id = "";
        openDB();

        $msg = new eZForumMessage( );
        $msg->get( $id );
        
        if ( $msg->parent() != "" )
        {
            $ret_id = $this->getTopMessage( $msg->parent() );
        }
        else
        {
            $ret_id = $msg->id( );
        }
        
        return $ret_id;
    }

    /*
      Denne funksjonen printer ut alle headerene og viser dem som et tre.
      Den returnerer rader i en tabell, hva den printer ut er avhengig
      av templates.
      WARNING: denne funksjonen er rekursiv og kan bruke en del minne. Denne forutsetter
      også at databasekolingen er oppe.
    */
    function printHeaderTree( $forum_id, $parent_id, $level, $document_root )
    {
        $level = $level + 1;
    
        $t = new Template(".");
        $msg = new eZForumMessage();

        $t->set_file( "elements", $document_root . "/templates/forum-elements.tpl"   );

        $t->set_var( "docroot", $document_root );
        $t->set_var( "category_id", $category_id );
        $t->set_var( "forum_id", $forum_id );
    
    
        $headers = $msg->getHeaders( $forum_id, $parent_id );

        for ($i = 0; $i < count($headers); $i++)
        {
            $Id = $headers[$i]["Id"];
            $Topic  = $headers[$i]["Topic"];
            $User = $headers[$i]["UserId"];
            $PostingTime = $headers[$i]["PostingTimeFormated"];
        
            $nr = $i + 1;
         
            $t->set_var( "id", $Id );
            $t->set_var( "forum_id", $forum_id );


            $replies = eZforumMessage::countReplies( $Id );
            
            $t->set_var( "replies", $replies );

            // legger på kode for å vise "gren" ikon
            $spacer = "<img src=\"". $document_root ."/images/trans.gif\" border=\"0\" height=\"21\" width=\"5\" >";

            if ( ( $replies == 0 ) )
            {
                if ( $level == 1 )
                {            
                    $spacer .= "<img src=\"". $document_root ."/images/n.gif\" border=\"0\" height=\"21\" width=\"9\" >";
                }
                else
                {
                    // sjekker om vi er på siste element av en gren.
                    if ( $i == ( count($headers) -1 ) )
                    {
                        $imgtype = "l";
                    }
                    else
                    {
                        $imgtype = "t";                    
                    }

                    if ( $level > 2 )
                        $spacer .= "<img  src=\"". $document_root ."/images/trans.gif\" height=\"21\" width=\"" . ( ($level-2)*12 ) ."\" border=\"0\">";
                
                    $spacer .= "<img  src=\"". $document_root ."/images/" . $imgtype . ".gif\"  height=\"21\" width=\"12\" border=\"0\">";

                    $spacer .= "<img  src=\"". $document_root ."/images/c.gif\" border=\"0\" height=\"21\" width=\"9\" >";
                
                }            
            }
            else
            {
                if ( $level > 1 )
                {
                    if ( $level > 2 )
                        $spacer .= "<img   src=\"". $document_root ."/images/trans.gif\" width=\"" . ( ($level-2)*12 ) ."\" border=\"0\">";
                
                    $spacer .= "<img  height=\"21\" width=\"12\" src=\"". $document_root ."/images/l.gif\" border=\"0\">";
                    $spacer .= "<img  height=\"21\" width=\"9\" src=\"". $document_root ."/images/m.gif\" border=\"0\">";
                }
                else
                {
                    $spacer .= "<img  height=\"21\" width=\"9\" src=\"". $document_root ."/images/m.gif\" border=\"0\">";
                }
            }

            $t->set_var( "tree_icon", $spacer );                    
            $t->set_var( "topic", "&nbsp;" . $Topic );        
            $t->set_var( "user", $User );
            $t->set_var( "postingtime", $PostingTime );
            $t->set_var( "link",$link );

            if ( ($i % 2) != 0)
                $t->set_var( "color", "#eeeeee");
            else
                $t->set_var( "color", "#bbbbbb");
    
            $messages .= $t->parse( "messages", "elements", true );
            $messages .= $this->printHeaderTree( $forum_id, $Id, $level, $document_root );
        }
        return $messages;
    }
    
}
?>

