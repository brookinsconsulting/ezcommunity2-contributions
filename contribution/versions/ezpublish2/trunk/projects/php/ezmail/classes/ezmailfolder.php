<?
// 
// $Id: ezmailfolder.php,v 1.11 2001/03/28 16:31:27 fh Exp $
//
// eZMailFolder class
//
// Frederik Holljen <fh@ez.no>
// Created on: <20-Mar-2001 18:29:11 fh>
//
// Copyright (C) .  All rights reserved.
//
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

//!! eZMailFolder
//! eZMailFolder documentation.
/*!

  Example code:
  \code
  \endcode

*/
include_once( "ezuser/classes/ezuser.php" );

/* DEFINES */
define( "USER", 0 );
define( "INBOX", 1 );
define( "DRAFTS", 2 );
define( "SENT", 3 );
define( "TRASH", 4 );

class eZMailFolder
{
/************* CONSTRUCTORS DESTRUCTORS (virtual) ************************/    
    /*!
      constructor
    */
    function eZMailFolder( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        // default value
        $this->IsPublished = "false";
        
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
      Deletes a eZMailFolder object from the database.
    */
    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        if( $id == -1 )
            $id = $this->ID;
        
        $db->query( "DELETE FROM eZMail_Folder WHERE ID='$id'" );

        $mail = eZMailFolder::mail( "subject", 0, 10000, $id );
        foreach( $mail as $mailItem )
            $mailItem->delete();
    }

/***************** Get / fetch from database *******************************/
    /*!
      Stores a mail to the database.
    */
    function store()
    {
        $this->dbInit();

        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZMail_Folder SET
		                         UserID='$this->UserID',
		                         ParentID='$this->ParentID',
                                 Name='$this->Name',
                                 FolderType='$this->FolderType'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZMail_Folder SET
		                         UserID='$this->UserID',
		                         ParentID='$this->ParentID',
                                 Name='$this->Name',
                                 FolderType='$this->FolderType'
                                 WHERE ID='$this->ID'
                                 " );

            $this->State_ = "Coherent";
        }
        
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
            $this->Database->array_query( $account_array, "SELECT * FROM eZMail_Folder WHERE ID='$id'" );
            if ( count( $account_array ) > 1 )
            {
                die( "Error: Mail folders with the same ID was found in the database. This should not happen." );
            }
            else if( count( $account_array ) == 1 )
            {

                $this->ID = $account_array[0][ "ID" ];
                $this->UserID = $account_array[0][ "UserID" ];
                $this->ParentID = $account_array[0][ "ParentID" ];
                $this->Name = $account_array[0][ "Name" ];
                $this->FolderType = $account_array[0][ "FolderType" ];

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

/****************** BORING SET AND GET FUNCTIONS ***************************/

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
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
    */
    function setUser( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( get_class( $value ) == "ezuser" )
            $value = $value->id();
        
        $this->UserID = $value;
    }

  /*!
    */
    function name()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Name;
    }

    /*!
    */
    function setName( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Name = $value;
    }

    /*!
    */
    function parentID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ParentID;
    }

    /*!
    */
    function setParent( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

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
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->FolderType = $value;
    }

/****** INTERESTING FUNCTIONS *******************/
    /*!
      Adds a mail to this folder
     */
    function addMail( $mail, $removeFromOld = true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $mail ) == "ezmail" )
           $mail = $mail->id();

       if( $removeFromOld == true )
           $this->Database->query( "DELETE FROM eZMail_MailFolderLink WHERE MailID='$mail'" );
       
       $query = "INSERT INTO eZMail_MailFolderLink
                       SET FolderID='$this->ID', MailID='$mail'";
 
       $this->Database->query( $query );
    }

    /*!
      Removes a mail from this folder
     */
    function removeMail( $mail )
    {
      if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
 
       if ( get_class( $mail ) == "ezmail" )
       {
           $mailID = $mail->id();
            $query = "DELETE FROM eZMail_MailFolderLink
                       WHERE FolderID='$this->ID' AND MailID='$mailID'";
 
           $this->Database->query( $query );
       }
    }

    /*!
      Returns all folders with the folder given as parent.

      The folder are returned as an array of eZMailFolder objects.
    */
    function getByParent( $parent )
    {
      if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

      if ( get_class( $parent ) == "ezmailfolder" )
        {
            $this->dbInit();
 
            $return_array = array();
            $folder_array = array();
 
            $parentID = $parent->id();
 
            $this->Database->array_query( $category_array, "SELECT ID, Name FROM eZMail_Folder
                                          WHERE ParentID='$parentID'
                                          ORDER BY Name" );
 
            for ( $i=0; $i < count($category_array); $i++ )
            {
                $return_array[$i] = new eZMailFolder( $folder_array[$i]["ID"], 0 );
            }
 
            return $return_array;
        }
        else
        {
            return 0;
        }
    }                                

    /*!
      \static
      Returns all folders that belongs to this user as an array of eZMailFolders.
     */
    function getByUser( $user = false, $withSpecialFolders=false, $parentFolder = -1 )
    {
        if( get_class( $user ) != "ezuser" )
            $user = eZUser::currentUser();

        $noSpecial = "";
        if( $withSpecialFolders == false )
        {
            $noSpecial = "AND FolderType='0'";
        }
        $parentFolderSQL = "";
        if( $parentFolder != -1 )
            $parentFolderSQL = "AND ParentID='$parentFolder'";
        
        $return_array = array();
        $res = array();
        $userid = $user->id();
        $database = eZDB::globalDatabase();
        $query = "SELECT ID FROM eZMail_Folder WHERE UserID='$userid' $noSpecial $parentFolderSQL";
        $database->array_query( $res, $query );

        for ( $i=0; $i < count($res); $i++ )
        {
            $return_array[$i] = new eZMailFolder( $res[$i]["ID"] );
        }

        return $return_array;
    }

    /*
      Creates a tree of the folders for the current user.
     */
    function &getTree( $parentID=0, $level=0 )
    {
        $folderList = eZMailFolder::getByUser( false, false, $parentID );

        $tree = array();
        $level++;
        foreach ( $folderList as $folder )
        {
//            array_push( $tree, array( new eZMailFolder( $folder->id() ), $level ) );
            array_push( $tree, array( $folder->id(), $folder->name(),  $level ) );
            
            if ( $folder != 0 )
            {
                $tree = array_merge( $tree, eZMailFolder::getTree( $folder->id(), $level ) );
            }
        }
        return $tree;
    }

    
    /*
      Returns all the mail in the folder. $sortmode can be one of the following:
      subject, sender, date, subjectdec, senderdesc, datedesc.
      $offset and $limit sets how many mail to return in one bunch and where in the list to start.
     */
    function &mail( $sortmode="subject", $offset=0, $limit=50, $folderID = -1 )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( $folderID == -1 )
            $folderID = $this->ID;

        $db =& eZDB::globalDatabase();
        $query = "SELECT Mail.ID FROM eZMail_Mail AS Mail, eZMail_MailFolderLink AS Link
                  WHERE Mail.ID=Link.MailID AND Link.FolderID='$folderID'
                  ORDER BY Mail.UDate ASC
                  LIMIT $offset,$limit";

        $mail_array = array();
        $return_array = array();
        $db->array_query( $mail_array, $query );  
        for ( $i=0; $i < count($mail_array); $i++ )
        {
            $return_array[$i] = new eZMail( $mail_array[$i]["ID"] );
        }

        return $return_array;     
    }

    /*!
      Returns the number for mail in the folder. If $unreadOnly is set to true the function returns the number of unread mails.
      If you specify the folderID this function can be used as an static function.
     */
    function count( $unreadOnly = false, $folderID =-1 )
    {
        $db =& eZDB::globalDatabase();

        if( $folderID == -1 )
            $folderID = $this->ID;
        
        $unreadSQL = "";
        if( $unreadOnly == true )
            $unreadSQL = "AND Mail.Status='0'";
        
        $db->query_single( $res, "SELECT count( Mail.ID ) as Count from eZMail_Mail as Mail,
                                                eZMail_MailFolderLink as Link
                                                WHERE Mail.ID=Link.MailID AND Link.FolderID='$folderID' $unreadSQL" );
        return $res["Count"];
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
        if( get_class( $user ) != "ezuser" )
            $user = eZUser::currentUser();

        $userid = $user->id();

        if( $userid == 0 )
            return false;

        $database = eZDB::globalDatabase();
        $database->query_single( $res, "SELECT ID FROM eZMail_Folder WHERE FolderType='$specialType' AND UserID='$userid'" );

        if( $res["ID"] != "" )
            return new eZMailFolder( $res["ID"] );

        switch( $specialType )
        {
            case INBOX :
                $folderName = "Inbox";
                break;
            case SENT :
                $folderName = "Sent";
                break;
            case DRAFTS :
                $folderName = "Drafts";
                break;
            case TRASH :
                $folderName = "Trash";
                break;
            default:
                return false;
                break;
        }

        $database->query( "INSERT INTO eZMail_Folder SET FolderType='$specialType', UserID='$userid', ParentID='0', Name='$folderName'" );
        $database->query_single( $res, "SELECT ID FROM eZMail_Folder WHERE FolderType='$specialType' AND UserID='$userid'" );
        if( $res["ID"] != "" )
            return new eZMailFolder( $res["ID"] );
        
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
    var $ParentID;
    var $Name;
    var $FolderType=0;
    
    var $Database;
    var $IsConnected;
    var $State_;
}

?>
