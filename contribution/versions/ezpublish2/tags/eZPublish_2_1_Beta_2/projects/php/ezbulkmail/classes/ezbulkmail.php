<?
// 
// $Id: ezbulkmail.php,v 1.11 2001/04/30 15:17:34 fh Exp $
//
// eZBulkMail class
//
//  Frederik Holljen<fh@ez.no>
// Created on: <17-Apr-2001 11:53:30 fh>
//
// Copyright (C) .  All rights reserved.
//
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

//!! eZBulkMail
//! eZBulkMail documentation.
/*!

  Example code:
  \code
  \endcode

*/
include_once( "classes/ezdatetime.php" );
include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
include_once( "ezbulkmail/classes/ezbulkmailtemplate.php" );
include_once( "ezmail/classes/ezmail.php" );

class eZBulkMail
{
    /*!
    */
    function eZBulkMail( $id=-1 )
    {
        $this->IsConnected = false;
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Stores a eZBulkMail object to the database.
    */
    function store()
    {
        $this->dbInit();
        $bodytext = addslashes( $this->BodyText );
        $subject = addslashes( $this->Subject );
        $replyto = addslashes( $this->ReplyTo );
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBulkMail_Mail SET
		                         UserID='$this->UserID',
                                 FromField='$this->From',
                                 ReplyTo='$replyto',
                                 Subject='$subject',
                                 BodyText='$bodytext',
                                 IsDraft='$this->IsDraft'
                                 " );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZBulkMail_Mail SET
		                         UserID='$this->UserID',
                                 FromField='$this->From',
                                 ReplyTo='$replyto',
                                 Subject='$subject',
                                 BodyText='$bodytext',
                                 IsDraft='$this->IsDraft'
                                 WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZBulkMail object from the database.

    */
    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id==-1 )
            $id = $this->ID;
            
        $db->query( "DELETE FROM eZBulkMail_MailCategoryLink WHERE MailID='$id'" );
        $db->query( "DELETE FROM eZBulkMail_Mail WHERE ID='$id'" );
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        if ( $id != "" )
        {
            $this->Database->array_query( $mail_array, "SELECT * FROM eZBulkMail_Mail WHERE ID='$id'" );
            if ( count( $mail_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $mail_array ) == 1 )
            {
                $this->ID = $mail_array[0][ "ID" ];
                $this->UserID = $mail_array[0][ "UserID" ];
                $this->From = $mail_array[0][ "FromField" ];
                $this->ReplyTo = $mail_array[0][ "ReplyTo" ];
                $this->Subject = $mail_array[0][ "Subject" ];
                $this->BodyText = $mail_array[0][ "BodyText" ];
                $this->SentDate = $mail_array[0][ "SentDate" ];
                $this->IsDraft = $mail_array[0][ "IsDraft" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZBulkMail objects.
    */
    function getAll( $draftsOnly = false )
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $mail_array = array();

        $draftsSQL = "";
        if( $draftsOnly == true )
            $draftsSQL = "WHERE IsDraft='1'";
        
        $db->array_query( $mail_array, "SELECT ID FROM eZBulkMail_Mail $draftsSQL  ORDER BY SentDate" );
        
        for ( $i=0; $i<count($mail_array); $i++ )
        {
            $return_array[$i] = new eZBulkMail( $mail_array[$i]["ID"] );
        }
        
        return $return_array;
    }
    
    /*!
      Returns the object ID to the category. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the sender address.
    */
    function sender()
    {
        return $this->From;
    }

    /*!
      Sets the sender address.      
    */
    function setSender( $newSender )
    {
        $this->From = $newSender;
    }

    /*!
      Returns the subject.
    */
    function subject( $html = true )
    {
        if( $html )
            return htmlspecialchars( $this->Subject );
        return $this->Subject;
    }

    /*!
      Sets the subject of the mail.
    */
    function setSubject( $newSubject )
    {
        $this->Subject = trim( $newSubject );
    }

    /*!
      returns the body.
    */
    function body( $html = true )
    {
        if( $html )
            return htmlspecialchars( $this->BodyText );
        return $this->BodyText;
    }

    /*!
      Sets the body.
    */
    function setBodyText( $newBody )
    {
        $this->BodyText = $newBody;
    }

    /*!
      Returns the userID of the user that owns this object
    */
    function owner()
    {
        return $this->UserID;;
    }

    /*!
      Sets the owner of this mail
    */
    function setOwner( $newOwner )
    {
        if( get_class( $newOwner ) == "ezuser" )
            $this->UserID = $newOwner->id();
        else
            $this->UserID = $newOwner;
    }

    /*!
      Returns the date that this mail was distributed
     */
    function date(  )
    {
        $dateTime = new eZDateTime( );
        $dateTime->setMySQLTimeStamp( $this->SentDate );        
        
        return $dateTime;
    }

    /*!
      Sets the date when this mail was sent.
     */
    function setDate( $value )
    {
        $this->SentDate = $value;
    }

    /*!
      Returns true if this mail is a draft.
     */
    function isDraft()
    {
        return $this->IsDraft;
    }

    /*!
      Set if this mail is a draft or not.
     */
    function setIsDraft( $value )
    {
        $this->IsDraft = $value;
    }

    /*!
      Adds this bulkmail to a category. 
     */
    function addToCategory( $value )
    {
       if( get_class( $value ) == "ezbulkmailcategory" )
           $value = $value->id();

       $db =& eZDB::globalDatabase();

       if( $value == false )
           $db->query( "DELETE FROM eZBulkMail_MailCategoryLink WHERE MailID='$this->ID'");
       else
       {
           $db->query_single( $result, "SELECT count( * ) AS Count FROM eZBulkMail_MailCategoryLink WHERE CategoryID='$value' AND MailID='$this->ID'" );
           if( $result["Count"] == 0 )
               $db->query( "INSERT INTO eZBulkMail_MailCategoryLink SET CategoryID='$value', MailID='$this->ID'" );
       }
    }
    
    
    /*!
      Returns the categories a mail is member of.
      False if none..
     */
    function categories( $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $category_array = array();
        $result_array = array();
        
        $db->array_query( $category_array, "SELECT CategoryID FROM eZBulkMail_MailCategoryLink WHERE MailID='$this->ID'" );

        foreach( $category_array as $arrayItem )
        {
            $result_array[] = ( $as_object == true ) ? new eZBulkMailCategory( $arrayItem["CategoryID"] ) : $arrayItem["CategoryID"];
        }
        
        return $result_array;
    }

    /*!
      Associates a template with a bulkmail. If the parameter is false, the mail is dissassociated with all templates.
      NOTE: A bulkmail is only associated with a template before it is sent. After it is sent the template is stored in the mail. This is because
      templates my change, but you want to know exactly what you have sent to your custemors.
     */
    function useTemplate( $templateID )
    {
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZBulkMail_MailTemplateLink WHERE MailID='$this->ID'" );
        if( $templateID != false )
            $db->query( "INSERT INTO eZBulkMail_MailTemplateLink SET MailID='$this->ID', TemplateID='$templateID'" );
    }

    /*!
      Returns the template associated with this mail or false if none.
     */
    function template( $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $template_array = array();
        
        $db->array_query( $template_array, "SELECT TemplateID FROM eZBulkMail_MailTemplateLink WHERE MailID='$this->ID'" );

        if( count( $template_array ) > 0 && $as_object == true )
            return new eZBulkMailTemplate( $template_array[0]["TemplateID"] );
        if( count( $template_array ) > 0 && $as_object == false )
            return $template_array[0]["TemplateID"];
        
        return false;

    }

    /*!
      This function handles sending of the mail. Before sending the mail it merges the associated template (if any) with the body contents.
      The mail is pulled out of the drafts, and the template is disconnected.
      TODO: Current implementation sends all the mail in one big bulk. For larger systems we want to spread it out over time.
     */
    function send()
    {
        $this->IsDraft = false;
        $template = $this->template();
        if( is_object( $template ) )
            $this->BodyText = $template->header( false ) . $this->BodyText . $template->footer( false );

        $this->useTemplate( false );

        $categories = $this->categories();
        if( count( $categories ) > 0 ) // category does exist...
        {
            $mail = new eZMail();
            $mail->setBodyText( $this->BodyText );
            $mail->setSubject( $this->Subject );
            $mail->setSender( $this->From );
            // get subscribers from groups
            $subscribers = array();

            // normal subscribers...
            foreach( $categories as $categoryItem )
            {
                $subscribers = array_merge( $subscribers,  $categoryItem->subscribers() );

                $groups = $categoryItem->groupSubscriptions();
                foreach( $groups as $group )
                    $subscribers = array_merge( $subscribers, $group->userEmails() );
            }
            
            foreach( $subscribers as $subscriber )
            {
                if( !$this->isSent( $subscriber ) )
                {
                    $mail->setTo( $subscriber );
                    $mail->send();
                    $this->addLogEntry( $subscriber );
                }
            }
            $this->store();
            /** The mail was sent.. now lets set the timestamp **/
            $this->Database->query( "UPDATE eZBulkMail_Mail SET SentDate=now() WHERE ID='$this->ID'");
        }
    }

    /*** FUNCTIONS THAT HANDLE THE LOGGING **/
    /*!
      \private
      Sets this mail as sent for the address given which is the email address as a text.
    */
    function addLogEntry( $mail )
    {
        $db = eZDB::globalDatabase();
        $db->query( "INSERT INTO eZBulkMail_SentLog SET SentDate=now(), Mail='$mail', MailID='$this->ID'" );
    }

    /*!
      \private
      Checks if this mail is sent for the given address.
     */
    function isSent( $mail )
    {
        $db = eZDB::globalDatabase();
        $db->query_single( $result, "SELECT Count( ID ) as Count FROM eZBulkMail_SentLog WHERE Mail='$mail' AND MailID='$this->ID'" );
        if( $result[ "Count" ] > 0 )
            return true;

        return false;
    }
    
    /*!
      \private
      
      Open the database for read and write. Gets all the database information from site.ini.
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
    var $UserID;
    var $From;
    var $ReplyTo;
    var $Subject;
    var $BodyText;
    var $SentDate;
    var $IsDraft;
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
    
}

?>
