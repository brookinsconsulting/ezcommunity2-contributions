<?php
// 
// $Id: ezimapmailfolder.php,v 1.11 2002/04/17 10:45:53 fh Exp $
//
// eZIMAPMailFolder class
//
// Created on: <20-Mar-2001 18:29:11 fh>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

//!! eZMail
//! eZMailFolder documentation.
/*!

  Example code:
  \code
  \endcode

*/
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/INIFile.php" );
include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezmail/classes/ezimapmail.php" );
include_once( "ezmail/classes/imapfunctions.php" );
include_once( "ezmail/classes/ezmaildefines.php" );

class eZIMAPMailFolder
{
    /*!
      constructor
    */
    function eZIMAPMailFolder( $id=-1 )
    {
        if( $id != -1 )
        {
            $elements = $this->decodeFolderID( $id );
            $this->Account = $elements["AccountID"]; //new eZMailAccount( $elements[0] );
            $this->Name = ereg_replace( "#", "/", $elements["FolderName"] );
        }
    }

    /*!
      \static
      Functions to encode more information into one url position. This allows us to use the same
      templates for remote and local mail. If encode is set, the folder ID is encoded as an URL.
    */
    function encodeFolderID( $accountID = -1 , $folderName = -1, $encode = true )
    {
        if( $accountID == -1 || $folderName == -1 )
        {
            $accountID = $this->Account;
            $folderName = $this->Name;
        }
//        echo "Was here: $accountID, $folderName";
        $folderName = ereg_replace( "/", "#", $folderName );
        if( $encode )
        {
            return rawurlencode( $accountID . "-" . $folderName );
        }

        return $accountID . "-" . $folderName;
    }
    
    /*!
      \static
      Returns an array with the 
    */
    function decodeFolderID( $codedString )
    {
        $elements = explode( "-", $codedString, 2 ); // max 1 split rest is foldername.
        $elements["AccountID"] = $elements[0];
        $elements["FolderName"] = ereg_replace( "#", "/", $elements[1] );
        return $elements;
    }


    /*!
      Deletes a eZImapMailFolder object from the imap server.
    */
    function delete( $id )
    {
        $info = eZIMAPMailFolder::decodeFolderID( $id );
        $account = new eZMailAccount( $info["AccountID"] ); 
        eZIMAPMailFolder::deleteMailBox( $account, $info["FolderName"] );
    }

    /*!
      Stores a mail on the imap server.
    */
    function store()
    {
        return true;
    }    

    /*!
      \static
      IMAPMailFolder spesific. Creates a mailbox on a server.
      Foldername must be the full path to the mailbox you want to create.
     */
    function createMailBox( $account, $folderName )
    {
        if ( get_class( $account ) != "ezmailaccount" ) 
            $account = new eZMailAccount( $account ); 

        $mbox = imapConnect( $account );
        $server = $account->server();
//        $mailBoxes = imap_getmailboxes( $mbox, "{" . $server . "}", "*" );
        $ok = imap_createmailbox( $mbox, imap_utf7_encode( "{" . $server ."}" . $folderName ) );
//
        if( !$ok )
        {
            echo "imap_createmailbox failed: " . imap_last_error() . "\n";
            exit();
        }
        
        imapDisconnect( $mbox );

        return $ok;
    }

    /*!
      \static
      IMAPMailFolder spesific. Deletes a mailbox on a server.
      Foldername must be the full path to the mailbox you want to delete.
     */
    function deleteMailBox( $account, $folderName )
    {
        $mbox = imapConnect( $account );
        $server = $account->server();

        $ok = imap_deletemailbox( $mbox, imap_utf7_encode( "{" . $server ."}" . $folderName ) );
        if( !$ok )
            echo "imap_deletemailbox failed: " . imap_last_error() . "\n";
        
        imapDisconnect( $mbox );

        return $ok;
    }

    /*!
      \static
      IMAPMailFolder spesific. Renames a mailbox on a server.
      Foldername must be the full path to the mailbox you want to create.
     */
    function renameMailBox( $account, $oldFolder, $newFolder )
    {
        $mbox = imapConnect( $account );
        $server = $account->server();
        $ok = imap_renamemailbox( $mbox,
              imap_utf7_encode( "{" . $server ."}" . $oldFolder ),
              imap_utf7_encode( "{" . $server ."}" . $newFolder ) );
        if( !$ok )
            echo "imap_renamemailbox failed: " . imap_last_error() . "\n";
        
        imapDisconnect( $mbox );

        return $ok;
    }

    /*!
      \static
      Move a mail from one mailbox folder to another mailbox folder on the same account.
     */
    function moveMail( $mailID, $newFolderID )
    {
        // there are lots of different cases for this one..
        if( is_numeric( $mailID ) )// local
        {
            // 1. local to local
            if( is_numeric( $newFolderID ) )
            {
                include_once( "ezmail/classes/ezmailfolder.php" );
                $folder = new eZMailFolder( $newFolderID );
                $folder->addMail( $mailID );
            }
            else        // 2. local to imap
            {
                $folderIDData = eZImapMailFolder::decodeFolderID( $newFolderID );
                $mail = new eZMail( $mailID );
                $mimeMail = createMimeMail( $mail, true );
//                echo "Built mail $mimeMail <BR>";
                
                $account = new eZMailAccount( $folderIDData["AccountID"] );
                $mailboxString = createServerString( $account->server(), $account->serverPort(), $folderIDData["FolderName"] );

                $mbox = imapConnect( $account, $folderIDData["FolderName"] );
                imap_append( $mbox, $mailboxString, $mimeMail );
                if( !$ok )
                {
                    echo "imap_append failed: " . imap_last_error() . "\n";
                }
                else // delete the message
                {
                    $mail->delete();
                }
                
                imap_close($mbox);
            }
        }
        else // remote
        {
            // 3. imap to local
            if( is_int( $newFolderID ) )
            {
                // not supported yet..
            }
            else
            {
                $mailIDData = eZImapMail::decodeMailID( $mailID );
                $folderIDData = eZImapMailFolder::decodeFolderID( $newFolderID );
                // 4. imap to imap same server
                if( $mailIDData["AccountID"] == $folderIDData["AccountID"] )
                {
                    if( $mailIDData["FolderName"] != $folderIDData["FolderName"] ) // not same mailbox
                    {
                        $mbox = imapConnect( $mailIDData["AccountID"], $mailIDData["FolderName"] );

                        $ok = imap_mail_move( $mbox, $mailIDData["MailID"], $folderIDData["FolderName"] );
                        if( !$ok )
                            echo "imap_mail_move failed: " . imap_last_error() . "\n";

                        imap_expunge( $mbox ); // really delete the mail.
                        imapDisconnect( $mbox );
                    }
                }
                else  // 5. imap to imap not same server
                {
                    // not supported yet
                }
            }
        }
            
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->encodeFolderID( -1, -1, false );
    }

   /*!
     Returns the ID of the owner user
    */
    function userID()
    {
        return $this->UserID;
    }

    /*!
      Sets the user that owns this object
    */
    function setUser( $value )
    {
    }

  /*!
    Returns the name of the folder
  */
    function name( $asHTML = true )
    {
        if( $asHTML )
            return htmlspecialchars( $this->Name );

        return $this->Name;
    }

    /*!
      Sets the name of the folder.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Returns the ID of the parent folder.
    */
    function parentID()
    {
        return $this->ParentID;
    }

    /*!
      Sets the parent folder.
    */
    function setParent( $value )
    {
        $this->ParentID = $value;
    }

    /*!
      Returns the type of this folder. Valid types are:
      0 - Normal user created folder
      1 - Inbox
      2 - Outbox
      3 - Sent mail
      4 - Drafts
      5 - Trash
    */
    function folderType()
    {
        return $this->FolderType;
    }
    
    /*!
      Sets the type of this folder. Valid types are:
      0 - Normal user created folder
      1 - Inbox
      2 - Outbox
      3 - Sent mail
      4 - Drafts
      5 - Trash
    */
    function setFolderType( $value )
    {
        $this->FolderType = $value;
    }

    /*!
      Adds a mail to this folder. Returns true if successfull.
    */
    function addMail( $mail, $removeFromOld = true )
    {
       if ( get_class( $mail ) == "ezmail" )
           $mail = $mail->id();

       $db =& eZDB::globalDatabase();
       $db->begin();
       if ( $removeFromOld == true )
           $res = $db->query( "DELETE FROM eZMail_MailFolderLink WHERE MailID='$mail'" );
       
      // code to insert into a imap server here!
       
        if ( $res == false )
        {
            $db->rollback( );
            return false;
        }
        else
            $db->commit();

        return true;
    }

    /*!
      Removes a mail from this folder
     */
    function removeMail( $mail )
    {
    }

    /*!
      Returns all folders with the folder given as parent.

      The folder are returned as an array of eZMailFolder objects.
    */
    function getByParent( $parent )
    {
    }                                

    /*!
      \static
      Returns all folders that belongs to this
      user as an array of eZIMAPMailFolders.
     */
    function getByUser( $user = false, $withSpecialFolders=false, $parentFolder = -1 )
    {
        return $return_array;
    }


    /*
      \static
      Creates a tree of the folders for the current user.
     */
    function getTree( $parentID = 0, $level = 0 )
    {
    }

    
    /*!
      Imap spesific. Returns all folders in this account as eZIMAPMailFolder objects.
      Returns false if the function did not succeed.
     */
    function &getImapTree( $account )
    {
        $mbox = imapConnect( $account );
        $resultArray = array();
        if( !$mbox )
        {
            return false;
        }
            
        $server = $account->server();
        $mailBoxes = imap_getmailboxes( $mbox, "{" . $server . "}", "*" );
//    echo "<pre>"; print_r( $mailBoxes ); echo "</pre>";
        
        if( $mailBoxes  )
        {
            $i = 0;
            foreach( $mailBoxes as $mailBox )
            {
                $key = explode( "}", $mailBox->name );
                $resultArray[$i] = new eZImapMailFolder(
                    eZImapMailFolder::encodeFolderID( $account->id(), $key[1], false ) );
//                $resultArray[$i]->Name = $key[1];
//                $resultArray[$i]->FullName = $mailBox->name;
//            echo "<pre>"; print_r( $resultArray ); echo "</pre>";
            $i++;
            }
        }
        else
        {
//            echo "imap_getmailboxes failed: ".imap_last_error()."\n";
            return $resultArray = false;;
        }

        imapDisconnect( $mbox );
        return $resultArray;
    }

    /*!
      Imap spesific. Returns all folders of all given accounts.
      TODO: caching..
     */
    function &getAllImapFolders( $accounts = 0 )
    {
        if( $accounts == 0 )
            $accounts = eZMailAccount::getByUser( eZUser::currentUser(), IMAP );
        
        $folders = array();
        if( count( $accounts ) > 0 )
        {
            foreach( $accounts as $account )
            {
                $folders = array_merge( $folders, eZImapMailFolder::getImapTree( $account ) );
            }
        }
//        echo "<PRE>";
//        print_r( $folders );
//        echo "</PRE>";
        return $folders;
    }
    
    /*!
      Returns all the mail in the folder. $sortmode can be one of the following:
      subject, sender, date, subjectdec, senderdesc, datedesc.
      $offset and $limit sets how many mail to return in one bunch and where in the list to start.
      Static if the folderID is supplied.

      This one only fetches the mail header for now. You need to use get() on the mail to get
      the complete message.
      Fetches all mail headers for an imap mailbox.
      Fetches data from INBOX for now.
      
      TODO:
      - fetch email address correctly. (not just name)
      - fetch email date.
      - offset, range
      
     */
    function &mail( $sortmode="subject_asc", $offset=0, $limit=50, $folderID = -1 )
    {
//        switch( $sortmode )
//        {
//            case "subject_asc" : $orderBySQL = "Mail.Subject ASC"; break;
//            case "subject_desc" : $orderBySQL = "Mail.Subject DESC"; break;
//            case "date_asc" : $orderBySQL = "Mail.UDate ASC"; break;
//            case "date_desc" : $orderBySQL = "Mail.UDate DESC"; break;
//            case "from_asc" : $orderBySQL = "Mail.FromField ASC"; break;
//            case "from_desc" : $orderBySQL = "Mail.FromField DESC"; break;
//            case "size_asc" : $orderBySQL = "Mail.Size ASC"; break;
//            case "size_desc" : $orderBySQL = "Mail.Size DESC"; break;
//        }
        $account = new eZMailAccount( $this->Account );
        $mbox = imapConnect( $account, $this->Name );
        
        $MC = imap_check( $mbox ); 
        $MN = $MC->Nmsgs; 
        $overview = imap_fetch_overview( $mbox, "1:$MN", 0 );
//        $overview = imap_headers( $mbox ); //<--- crap function, returns no useful info.
        foreach( $overview as $mailHeader )
        {
            $mailItem = new eZIMAPMail();
            $mailItem->setAccount( $this->Account );
            $mailItem->setMailNr( $mailHeader->msgno );
            $mailItem->setPath( $this->Name );
            $mailItem->setSize( $mailHeader->size );
            $mailItem->setSubject( $mailHeader->subject );
            $mailItem->setFrom( $mailHeader->from );
            $mailItem->setTo( $mailHeader->to );
            if( $mailHeader->answered )
                $mailItem->setStatus( REPLIED );
            else if( $mailHeader->seen )
                $mailItem->setStatus( READ );
            else
                $mailItem->setStatus( UNREAD );

            $mail[] = $mailItem;
//        echo "<pre>";print_r( $mailHeader ); echo "</pre>";
        }

        imapDisconnect( $mbox );
        return $mail;
    }

    /*!
      Returns the number of mail in this folder
     */
    function mailCount()
    {
    }
    
    /*!
      Deletes all mail in the folder
     */
    function deleteAll()
    {
    }
    
    /*!
      Returns the number for mail in the folder. If $unreadOnly is set to true the function returns the number of unread mails.
      If you specify the folderID this function can be used as an static function.
     */
    function count( $unreadOnly = false, $folderID =-1 )
    {
    }
    
    /*
      \static
      
      Returns the requested special folder of the current user or the user specified. Valid folders are:
      INBOX
      SENT
      DRAFTS
      TRASH
      If the folder does not exist it will be created. If the creation should fail the function returns false.
     */
    function getSpecialFolder( $specialType, $user=false ) 
    {
        return false;
    }

    /*!
      Returns true if the given folder is a child (doesn't have to be first level) of this folder.
      If the second parameter is set to true, the function also checks if the folder given is itself.
     */
    function isChild( $folderID, $check_for_self = false )
    {
        $return_value = false;
        return $return_value;
    }

    /*!
      \static  
      
      Returns true if the given mail belongs to the given user.
     */
    function isOwner( $user, $folderID )
    {
        return false;
    }

    var $Account;
    
    var $ParentID;
    var $Name;
    var $FolderType=0;
}

?>
